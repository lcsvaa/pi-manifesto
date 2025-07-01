<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require 'conexao.php';

function resposta($status, $mensagem, $data = null) {
    echo json_encode(['status' => $status, 'message' => $mensagem, 'data' => $data]);
    exit;
}

$id = isset($_POST['id']) && $_POST['id'] !== '' ? intval($_POST['id']) : null;
$nomeItem = trim($_POST['nomeItem'] ?? '');
$descItem = trim($_POST['descItem'] ?? '');
$valorItem = floatval($_POST['valorItem'] ?? 0);
$idCategoria = intval($_POST['idCategoria'] ?? 0);
$idColecao = intval($_POST['idColecao'] ?? 0);
$estoqueTotal = isset($_POST['estoqueItem']) && is_numeric($_POST['estoqueItem']) ? intval($_POST['estoqueItem']) : -1;

// Validações básicas
if ($nomeItem === '' || $descItem === '' || $valorItem <= 0 || $idCategoria <= 0 || $idColecao <= 0) {
    resposta('error', 'Campos obrigatórios faltando ou inválidos.');
}

if ($estoqueTotal < 0) {
    resposta('error', 'Estoque inválido.');
}

try {
    $pdo->beginTransaction();

    if ($id) {
        $sqlUpdate = "UPDATE tb_produto SET 
                        nomeItem = :nomeItem,
                        descItem = :descItem,
                        valorItem = :valorItem,
                        estoqueItem = :estoqueItem,
                        idCategoria = :idCategoria,
                        idColecao = :idColecao
                      WHERE id = :id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':nomeItem' => $nomeItem,
            ':descItem' => $descItem,
            ':valorItem' => $valorItem,
            ':estoqueItem' => $estoqueTotal,
            ':idCategoria' => $idCategoria,
            ':idColecao' => $idColecao,
            ':id' => $id
        ]);
        $idProduto = $id;
    } else {
        $sqlInsert = "INSERT INTO tb_produto (nomeItem, descItem, valorItem, estoqueItem, idCategoria, idColecao)
                      VALUES (:nomeItem, :descItem, :valorItem, :estoqueItem, :idCategoria, :idColecao)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            ':nomeItem' => $nomeItem,
            ':descItem' => $descItem,
            ':valorItem' => $valorItem,
            ':estoqueItem' => $estoqueTotal,
            ':idCategoria' => $idCategoria,
            ':idColecao' => $idColecao
        ]);
        $idProduto = $pdo->lastInsertId();
    }

    // Uploads
    $uploadsDir = __DIR__ . '/uploads/produtos/';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    if (isset($_FILES['imagemPrincipal']) && $_FILES['imagemPrincipal']['error'] === UPLOAD_ERR_OK) {
        if ($id) {
            $pdo->prepare("UPDATE tb_imagemProduto SET statusImagem = 'inativa' WHERE idProduto = ? AND statusImagem = 'principal'")
                ->execute([$idProduto]);
        }

        $tmpName = $_FILES['imagemPrincipal']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['imagemPrincipal']['name'], PATHINFO_EXTENSION));
        $novoNome = uniqid('produto_principal_') . '.' . $ext;
        $destino = $uploadsDir . $novoNome;

        if (!move_uploaded_file($tmpName, $destino)) {
            $erro = error_get_last();
            throw new Exception('Falha ao mover imagem principal: ' . ($erro['message'] ?? 'erro desconhecido'));
        }

        $stmtImg = $pdo->prepare("INSERT INTO tb_imagemProduto (nomeImagem, statusImagem, idProduto) VALUES (:nomeImagem, 'principal', :idProduto)");
        $stmtImg->execute([':nomeImagem' => $novoNome, ':idProduto' => $idProduto]);
    } elseif (!$id) {
        resposta('error', 'Imagem principal é obrigatória para novo produto.');
    } elseif ($_FILES['imagemPrincipal']['error'] !== UPLOAD_ERR_NO_FILE) {
        resposta('error', 'Erro ao enviar imagem principal. Código: ' . $_FILES['imagemPrincipal']['error']);
    }

    if (isset($_FILES['outrasImagens']) && count($_FILES['outrasImagens']['name']) > 0) {
        $stmtImg = $pdo->prepare("INSERT INTO tb_imagemProduto (nomeImagem, statusImagem, idProduto) VALUES (:nomeImagem, 'ativa', :idProduto)");

        for ($i = 0; $i < count($_FILES['outrasImagens']['name']); $i++) {
            if ($_FILES['outrasImagens']['error'][$i] !== UPLOAD_ERR_OK) continue;

            $tmpName = $_FILES['outrasImagens']['tmp_name'][$i];
            $ext = strtolower(pathinfo($_FILES['outrasImagens']['name'][$i], PATHINFO_EXTENSION));
            $novoNome = uniqid('produto_') . '.' . $ext;
            $destino = $uploadsDir . $novoNome;

            if (!move_uploaded_file($tmpName, $destino)) {
                $erro = error_get_last();
                throw new Exception('Falha ao mover uma das imagens adicionais: ' . ($erro['message'] ?? 'erro desconhecido'));
            }

            $stmtImg->execute([':nomeImagem' => $novoNome, ':idProduto' => $idProduto]);
        }
    }

    $pdo->commit();

    resposta('success', $id ? 'Produto atualizado com sucesso!' : 'Produto criado com sucesso!', ['idProduto' => $idProduto]);

} catch (Exception $e) {
    $pdo->rollBack();
    resposta('error', 'Erro ao salvar produto: ' . $e->getMessage());
}