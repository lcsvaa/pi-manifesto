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
    <!-- NAVBAR -->

    <?php include_once "navbar.php" ?>

    <!-- BANNER -->
    <div class="banner">
        <h1>Seu Carrinho</h1>
    </div>

    <!-- CONTEÚDO PRINCIPAL -->
    <div class="cart-container">
        <!-- Itens do Carrinho -->
        <div class="cart-items">
            <h2 class="cart-title">Seus Itens</h2>

            <!-- Estado vazio do carrinho -->
            <!-- <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <h3>Seu carrinho está vazio</h3>
                <p>Adicione itens para começar a comprar</p>
                <a href="roupas.php" class="btn-continue">Continuar Comprando</a>
            </div> -->

            <!-- Itens no carrinho -->
            <div class="cart-item">
                <img src="img/produtos/produto1.jpg" alt="Moletom Oversized" class="item-image">
                <div class="item-details">
                    <h3 class="item-name">Moletom Oversized</h3>
                    <p class="item-variant">Tamanho: M | Cor: Preto</p>
                    <p class="item-price">R$ 249,90</p>
                    <div class="item-actions">
                        <div class="quantity-control">
                            <button class="quantity-btn minus">-</button>
                            <input type="number" value="1" min="1" class="quantity-input">
                            <button class="quantity-btn plus">+</button>
                        </div>
                        <button class="remove-item">Remover</button>
                    </div>
                </div>
            </div>

            <div class="cart-item">
                <img src="img/produtos/produto2.jpg" alt="Camiseta Estampada" class="item-image">
                <div class="item-details">
                    <h3 class="item-name">Camiseta Estampada</h3>
                    <p class="item-variant">Tamanho: G | Cor: Branco</p>
                    <p class="item-price">R$ 129,90</p>
                    <div class="item-actions">
                        <div class="quantity-control">
                            <button class="quantity-btn minus">-</button>
                            <input type="number" value="2" min="1" class="quantity-input">
                            <button class="quantity-btn plus">+</button>
                        </div>
                        <button class="remove-item">Remover</button>
                    </div>
                </div>
            </div>

            <div class="cart-item">
                <img src="img/produtos/produto4.jpg" alt="Boné Ajustável" class="item-image">
                <div class="item-details">
                    <h3 class="item-name">Boné Ajustável</h3>
                    <p class="item-variant">Cor: Preto</p>
                    <p class="item-price">R$ 89,90</p>
                    <div class="item-actions">
                        <div class="quantity-control">
                            <button class="quantity-btn minus">-</button>
                            <input type="number" value="1" min="1" class="quantity-input">
                            <button class="quantity-btn plus">+</button>
                        </div>
                        <button class="remove-item">Remover</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumo do Pedido -->
        <div class="cart-summary">
            <h2 class="cart-title">Resumo do Pedido</h2>

            <div class="summary-card">
                <div class="summary-row">
                    <span>Subtotal (3 itens)</span>
                    <span>R$ 469,70</span>
                </div>
                <div class="summary-row">
                    <span>Frete</span>
                    <span>R$ 15,90</span>
                </div>
                <div class="summary-row">
                    <span>Desconto</span>
                    <span>- R$ 0,00</span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span>R$ 485,60</span>
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

    <!-- FOOTER -->

    <?php include_once "footer.php" ?>


    <script src="js/cart.js"></script>
</body>

</html>