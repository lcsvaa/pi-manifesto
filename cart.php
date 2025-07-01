<?php
require_once "conexao.php";
session_start();

function formatarPreco($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

$itensCarrinho = $_SESSION['carrinho'] ?? [];

$subtotal = 0;
foreach ($itensCarrinho as $item) {
    $subtotal += $item['preco'] * $item['qtd'];
}

$desconto = 0;
if (isset($_SESSION['cupom'])) {
    $desconto = ($subtotal * $_SESSION['cupom']['porcentagem']) / 100;
}

$frete = 15.00; 
$total = $subtotal - $desconto + $frete;


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | Manifesto</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
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
                    <a href="roupas.php" class="btn-continue">Continuar Comprando</a>
                </div>
            <?php else: ?>
                <?php foreach ($itensCarrinho as $key => $item): ?>
                    <div class="cart-item" data-key="<?= $key ?>">
                        <img src="uploads/produtos/<?= htmlspecialchars($item['imagem'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($item['nome']) ?>" class="item-image">
                        <div class="item-details">
                            <h3 class="item-name"><?= htmlspecialchars($item['nome']) ?></h3>
                            <p class="item-variant">Tamanho: <?= htmlspecialchars($item['tamanho']) ?></p>
                            <p class="item-price"><?= formatarPreco($item['preco']) ?></p>
                            <div class="item-actions">
                                <div class="quantity-control">
                                    <button class="quantity-btn minus">-</button>
                                    <input type="number" value="<?= $item['qtd'] ?>" min="1" class="quantity-input">
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
                    <span><?= formatarPreco($subtotal) ?></span>
                </div>
                <?php if (count($itensCarrinho) > 0): ?>
                    <div class="summary-row">
                        <span>Frete</span>
                        <span><?= formatarPreco($frete) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($desconto > 0): ?>
                        <div class="summary-row">
                            <span>Desconto</span>
                            <span>- <?= formatarPreco($desconto) ?></span>
                        </div>
                <?php endif; ?>
                <div class="summary-row summary-total">
                <span>Total</span>
                    <span>
                        <?= count($itensCarrinho) > 0 ? formatarPreco($total) : 'R$ 0,00' ?>
                    </span>
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

            <button class="checkout-btn">Finalizar Compra</button>
        </div>
    </div>

    <?php include_once "footer.php" ?>
    <script src="js/cart.js"></script>
</body>
</html>