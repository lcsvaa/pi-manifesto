<?php
require_once "conexao.php";
session_start();

$isLoggedIn = isset($_SESSION['usuario']);

$itensCarrinho = $_SESSION['carrinho'] ?? [];
$cupomAtivo = $_SESSION['cupom'] ?? null;

$subtotal = 0;
foreach ($itensCarrinho as $item) {
    if (is_array($item) && isset($item['preco'], $item['qtd'])) {
        $subtotal += floatval($item['preco']) * intval($item['qtd']);
    }
}

$desconto = 0;
if ($cupomAtivo && isset($cupomAtivo['tipo'], $cupomAtivo['valor'])) {
    if ($cupomAtivo['tipo'] === 'porcentagem') {
        $desconto = ($subtotal * $cupomAtivo['valor']) / 100;
    } elseif ($cupomAtivo['tipo'] === 'valor') {
        $desconto = $cupomAtivo['valor'];
    }
}

$frete = 0;
$total = 0;
if (!empty($itensCarrinho)) {
    $frete = 15.90;
    $total = $subtotal - $desconto + $frete;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | Manifesto</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/notificacation.css"/>
    <link rel="stylesheet" href="css/cart.css">
    <link rel="icon" href="img/icone.png" type="image/png">
</head>
<body>
<?php include_once "navbar.php" ?>

<div class="banner">
    <h1>Seu Carrinho</h1>
</div>

<div class="cart-container">
    <div class="cart-items">
        <h2 class="cart-title">Seus Itens</h2>

        <?php if (empty($itensCarrinho)): ?>
            <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <h3>Seu carrinho está vazio</h3>
                <p>Adicione itens para começar a comprar</p>
                <a href="produtos.php" class="btn-continue">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <?php foreach ($itensCarrinho as $key => $item):

                if (
                    !is_array($item) ||
                    !isset($item['id'], $item['nome'], $item['preco'], $item['qtd'], $item['tamanho'])
                ) {
                    continue;
                }

                if ($item['tamanho'] !== 'Único') {
                    $stmt = $pdo->prepare("SELECT estoque FROM tb_produto_tamanho WHERE idProduto = :idProduto AND tamanho = :tamanho");
                    $stmt->execute(['idProduto' => $item['id'], 'tamanho' => $item['tamanho']]);
                    $estoqueMax = (int) $stmt->fetchColumn();
                } else {
                    $stmt = $pdo->prepare("SELECT estoqueItem FROM tb_produto WHERE id = :idProduto");
                    $stmt->execute(['idProduto' => $item['id']]);
                    $estoqueMax = (int) $stmt->fetchColumn();
                }

                if ($estoqueMax < 1) {
                    $estoqueMax = 1;
                }
            ?>
                <div class="cart-item" data-key="<?= htmlspecialchars($key) ?>">
                    <img src="uploads/produtos/<?= htmlspecialchars($item['imagem'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($item['nome']) ?>" class="item-image">
                    <div class="item-details">
                        <h3 class="item-name"><?= htmlspecialchars($item['nome']) ?></h3>
                        <p class="item-variant">Tamanho: <?= htmlspecialchars($item['tamanho']) ?></p>
                        <p class="item-price">R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
                        <div class="item-actions">
                            <div class="quantity-control">
                                <button class="quantity-btn minus">-</button>
                                <input type="number"
                                    value="<?= intval($item['qtd']) ?>"
                                    min="1"
                                    class="quantity-input"
                                    max="<?= $estoqueMax ?>"
                                    data-estoque="<?= $estoqueMax ?>"
                                >
                                <button class="quantity-btn plus">+</button>
                            </div>
                            <button class="remove-item">Remover</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="cart-summary">
        <h2 class="cart-title">Resumo do Pedido</h2>
        <div class="summary-card">
            <div class="summary-row">
                <span>Subtotal (<?= count($itensCarrinho) ?> itens)</span>
                <span class="subtotal-value">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
            </div>
            <?php if (count($itensCarrinho) > 0): ?>
                <div class="summary-row">
                    <span>Frete</span>
                    <span class="frete-value">R$ <?= number_format($frete, 2, ',', '.') ?></span>
                </div>
            <?php endif; ?>
            <?php if ($desconto > 0): ?>
                <div class="summary-row">
                    <span>Desconto</span>
                    <span class="desconto-value">- R$ <?= number_format($desconto, 2, ',', '.') ?></span>
                </div>
            <?php endif; ?>
            <div class="summary-row summary-total">
                <span>Total</span>
                <span class="total-value">R$ <?= number_format($total, 2, ',', '.') ?></span>
            </div>
        </div>

        <div class="summary-card">
            <h3>Cupom de Desconto</h3>
            <div class="coupon-form">
                <input type="text" placeholder="Código do cupom" class="coupon-input">
                <button class="coupon-btn">Aplicar</button>
            </div>
        </div>

        <div class="summary-card">
            <button class="clear-cart-btn" id="clear-cart">
                <i class="fas fa-trash-alt"></i> Limpar Carrinho
            </button>
        </div>

            <?php if (isset($_SESSION['user_id'])): ?>
            <form action="checkout.php" method="get">
                <button type="submit" class="checkout-btn">Finalizar Compra</button>
            </form>
        <?php else: ?>
            <button class="checkout-btn disabled" onclick="alert('Você precisa estar logado para finalizar a compra.')">Finalizar Compra</button>
            <?php endif; ?>
    </div>
</div>

<?php include_once "footer.php" ?>
<script>
  let descontoTipo = <?= isset($cupomAtivo['tipo']) ? json_encode($cupomAtivo['tipo']) : 'null' ?>;
  let descontoValor = <?= isset($cupomAtivo['valor']) ? json_encode($cupomAtivo['valor']) : '0' ?>;
</script>
<script src="js/cart.js"></script>
</body>
</html>
