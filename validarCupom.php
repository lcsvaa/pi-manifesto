<?php
require_once "conexao.php";
session_start();
header('Content-Type: application/json');


$codigo = strtoupper(trim($_GET['codigo'] ?? ''));

if (empty($codigo)) {
    echo json_encode(['status' => 'error', 'msg' => 'Código do cupom não informado.']);
    exit;
}


$stmt = $pdo->prepare("
    SELECT * FROM tb_cupom
    WHERE codigo = :codigo AND statusCupom = 'ativo' AND dataValidade >= CURDATE()
");
$stmt->bindParam(':codigo', $codigo);
$stmt->execute();
$cupom = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cupom) {
    echo json_encode(['status' => 'error', 'msg' => 'Cupom inválido, expirado ou inativo.']);
    exit;
}


$subtotal = 0;
foreach ($_SESSION['carrinho'] ?? [] as $item) {
    $subtotal += $item['preco'] * $item['qtd'];
}

if ($subtotal < $cupom['valorCompraMin']) {
    echo json_encode([
        'status' => 'error',
        'msg' => "Cupom válido, mas o valor mínimo de compra é R$ " . number_format($cupom['valorCompraMin'], 2, ',', '.')
    ]);
    exit;
}


if ($cupom['quantidadeUso'] > 0 && $cupom['utilizados'] >= $cupom['quantidadeUso']) {
    echo json_encode(['status' => 'error', 'msg' => 'Este cupom já atingiu o número máximo de usos.']);
    exit;
}


$_SESSION['cupom'] = [
    'codigo' => $cupom['codigo'],
    'porcentagem' => $cupom['porcentagemDesconto'],
    'descricao' => $cupom['descricaoCupom'] ?? '',
    'id' => $cupom['idCupom']
];

echo json_encode([
    'status' => 'ok',
    'msg' => "Cupom aplicado com sucesso! Desconto de {$cupom['porcentagemDesconto']}%."
]);