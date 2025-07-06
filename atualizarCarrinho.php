<?php
session_start();
header('Content-Type: application/json');

require_once 'conexao.php';

// Suporte para JSON (POST)
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? ($_POST['id'] ?? null);
$tamanho = $data['tamanho'] ?? ($_POST['tamanho'] ?? '');


if (isset($_GET['key']) && isset($_GET['quantidade'])) {
    $key = $_GET['key'];
    $quantidade = max(1, intval($_GET['quantidade']));

    if (!isset($_SESSION['carrinho'][$key])) {
        echo json_encode(['status' => 'error', 'message' => 'Item não encontrado']);
        exit;
    }

    $item = $_SESSION['carrinho'][$key];

    // Validar estoque
    try {
        if ($item['tamanho'] !== 'Único') {
            $stmt = $pdo->prepare("SELECT estoque FROM tb_produto_tamanho WHERE idProduto = :id AND tamanho = :tamanho");
            $stmt->execute([':id' => $item['id'], ':tamanho' => $item['tamanho']]);
            $estoqueDisponivel = $stmt->fetchColumn();
        } else {
            $stmt = $pdo->prepare("SELECT estoqueItem FROM tb_produto WHERE id = :id");
            $stmt->execute([':id' => $item['id']]);
            $estoqueDisponivel = $stmt->fetchColumn();
        }

        if ($estoqueDisponivel === false) {
            echo json_encode(['status' => 'error', 'message' => 'Produto ou tamanho não encontrado.']);
            exit;
        }

        $estoqueDisponivel = intval($estoqueDisponivel);

        if ($quantidade > $estoqueDisponivel) {
            echo json_encode(['status' => 'error', 'message' => "Estoque insuficiente. Máximo permitido: $estoqueDisponivel unidade(s)."]);
            exit;
        }

        // Atualizar quantidade
        $_SESSION['carrinho'][$key]['qtd'] = $quantidade;
        echo json_encode(['status' => 'ok', 'message' => 'Quantidade atualizada']);
        exit;

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro no servidor.']);
        exit;
    }
}

// Suporte a POST/json sem chave
if (!$id || !isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida']);
    exit;
}

$quantidade = $data['quantidade'] ?? ($_POST['quantidade'] ?? 1);
$quantidade = max(1, intval($quantidade));

// Atualiza quantidade validando estoque
foreach ($_SESSION['carrinho'] as $key => &$item) {
    if ($item['id'] == $id && $item['tamanho'] == $tamanho) {
        try {
            if ($item['tamanho'] !== 'Único') {
                $stmt = $pdo->prepare("SELECT estoque FROM tb_produto_tamanho WHERE idProduto = :id AND tamanho = :tamanho");
                $stmt->execute([':id' => $item['id'], ':tamanho' => $item['tamanho']]);
                $estoqueDisponivel = $stmt->fetchColumn();
            } else {
                $stmt = $pdo->prepare("SELECT estoqueItem FROM tb_produto WHERE id = :id");
                $stmt->execute([':id' => $item['id']]);
                $estoqueDisponivel = $stmt->fetchColumn();
            }

            if ($estoqueDisponivel === false) {
                echo json_encode(['status' => 'error', 'message' => 'Produto ou tamanho não encontrado.']);
                exit;
            }

            $estoqueDisponivel = intval($estoqueDisponivel);

            if ($quantidade > $estoqueDisponivel) {
                echo json_encode(['status' => 'error', 'message' => "Estoque insuficiente. Máximo permitido: $estoqueDisponivel unidade(s)."]);
                exit;
            }

            $item['qtd'] = $quantidade;
            echo json_encode(['status' => 'ok', 'message' => 'Quantidade atualizada']);
            exit;

        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Erro no servidor.']);
            exit;
        }
    }
}

echo json_encode(['status' => 'error', 'message' => 'Item não encontrado']);