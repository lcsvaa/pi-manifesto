<?php
session_start();
require_once 'conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Ler entrada JSON
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['id'])) {
    echo json_encode(['status' => 'erro', 'msg' => 'Dados inválidos']);
    exit;
}

$id = intval($input['id']);
$tamanho = trim($input['tamanho'] ?? 'Único'); // remove espaços em branco extras
$qtdSolicitada = intval($input['qtd']);
$imagem = $input['imagem'] ?? 'default.png';

$key = $id . '_' . $tamanho;

try {
    if ($tamanho !== 'Único') {
        $stmt = $pdo->prepare("SELECT estoque FROM tb_produto_tamanho WHERE idProduto = :id AND tamanho = :tamanho");
        $stmt->execute([
            ':id' => $id,
            ':tamanho' => $tamanho
        ]);
        $estoqueDisponivel = $stmt->fetchColumn();
    } else {
        $stmt = $pdo->prepare("SELECT estoqueItem FROM tb_produto WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $estoqueDisponivel = $stmt->fetchColumn();
    }

    if ($estoqueDisponivel === false) {
        echo json_encode(['status' => 'erro', 'msg' => 'Produto ou tamanho não encontrado.']);
        exit;
    }

    $estoqueDisponivel = intval($estoqueDisponivel);

    $qtdAtualNoCarrinho = $_SESSION['carrinho'][$key]['qtd'] ?? 0;
    $qtdTotal = $qtdAtualNoCarrinho + $qtdSolicitada;

    if ($qtdTotal > $estoqueDisponivel) {
        echo json_encode([
            'status' => 'erro',
            'msg' => "Estoque insuficiente. Máximo permitido: $estoqueDisponivel unidade(s)."
        ]);
        exit;
    }

    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    if (isset($_SESSION['carrinho'][$key])) {
        $_SESSION['carrinho'][$key]['qtd'] += $qtdSolicitada;
    } else {
        $_SESSION['carrinho'][$key] = [
            'id' => $id,
            'nome' => $input['nome'],
            'preco' => $input['preco'],
            'qtd' => $qtdSolicitada,
            'tamanho' => $tamanho,
            'imagem' => $imagem
        ];
    }

    echo json_encode(['status' => 'ok']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'msg' => 'Erro no servidor.']);
}
