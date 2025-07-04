<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos | Manifesto</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pedidos.css">
    <link rel="icon" href="img/icone.png" type="image/png">

</head>

<body>
    <!-- NAVBAR -->
    
    <?php include_once "navbar.php" ?>

    <div class="resultados-produtos" style="display: none;">
        <p class="sem-resultados" style="display: none; color: #888; padding: 1rem;">Nenhum produto encontrado.</p>
        <div id="lista-produtos"></div>
    </div>

    <!-- BANNER -->
    <div class="banner">
        <h1>Meus Pedidos</h1>
    </div>

    <!-- CONTEÚDO PRINCIPAL -->
    <div class="orders-container">
        <!-- Abas de Navegação -->
        <div class="order-tabs">
            <div class="order-tab active" data-tab="all">Todos</div>
            <div class="order-tab" data-tab="processing">Processando</div>
            <div class="order-tab" data-tab="shipped">Enviados</div>
            <div class="order-tab" data-tab="delivered">Entregues</div>
            <div class="order-tab" data-tab="cancelled">Cancelados</div>
        </div>

        <!-- Lista de Pedidos - Todos -->
        <div class="order-list active" id="all-orders">
            <!-- Pedido 1 -->
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="order-id">Pedido #12345</span>
                        <span class="order-date">Realizado em: 15/03/2023</span>
                    </div>
                    <span class="order-status status-delivered">Entregue</span>
                </div>

                <div class="order-details">
                    <div class="order-products">
                        <div class="product-item">
                            <img src="img/produtos/produto1.jpg" alt="Moletom Oversized" class="product-image">
                            <div class="product-info">
                                <h4 class="product-name">Moletom Oversized</h4>
                                <p class="product-variant">Tamanho: M | Cor: Preto</p>
                                <p class="product-price">R$ 249,90</p>
                            </div>
                        </div>

                        <div class="product-item">
                            <img src="img/produtos/produto2.jpg" alt="Camiseta Estampada" class="product-image">
                            <div class="product-info">
                                <h4 class="product-name">Camiseta Estampada</h4>
                                <p class="product-variant">Tamanho: G | Cor: Branco</p>
                                <p class="product-price">R$ 129,90</p>
                            </div>
                        </div>
                    </div>

                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>R$ 379,80</span>
                        </div>
                        <div class="summary-row">
                            <span>Frete:</span>
                            <span>R$ 15,90</span>
                        </div>
                        <div class="summary-row">
                            <span>Desconto:</span>
                            <span>- R$ 25,00</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total:</span>
                            <span>R$ 370,70</span>
                        </div>
                    </div>
                </div>

                <div class="order-actions">
                    <button class="action-btn btn-primary">Comprar Novamente</button>
                    <button class="action-btn btn-secondary">Avaliar Produtos</button>
                </div>
            </div>

            <!-- Pedido 2 -->
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="order-id">Pedido #12344</span>
                        <span class="order-date">Realizado em: 10/03/2023</span>
                    </div>
                    <span class="order-status status-shipped">Enviado</span>
                </div>

                <div class="order-details">
                    <div class="order-products">
                        <div class="product-item">
                            <img src="img/produtos/produto3.jpg" alt="Calça Cargo" class="product-image">
                            <div class="product-info">
                                <h4 class="product-name">Calça Cargo</h4>
                                <p class="product-variant">Tamanho: 42 | Cor: Verde Militar</p>
                                <p class="product-price">R$ 199,90</p>
                            </div>
                        </div>
                    </div>

                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>R$ 199,90</span>
                        </div>
                        <div class="summary-row">
                            <span>Frete:</span>
                            <span>Grátis</span>
                        </div>
                        <div class="summary-row">
                            <span>Desconto:</span>
                            <span>- R$ 0,00</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total:</span>
                            <span>R$ 199,90</span>
                        </div>
                    </div>
                </div>

                <div class="order-actions">
                    <button class="action-btn btn-primary">Rastrear Pedido</button>
                    <button class="action-btn btn-secondary">Detalhes</button>
                </div>
            </div>
        </div>

        <!-- Lista de Pedidos - Processando -->
        <div class="order-list" id="processing-orders">
            <div class="empty-orders">
                <i class="fas fa-box-open"></i>
                <h3>Nenhum pedido em processamento</h3>
                <p>Você não tem nenhum pedido sendo processado no momento.</p>
                <a href="index.html" class="btn-continue">Continuar Comprando</a>
            </div>
        </div>

        <!-- Lista de Pedidos - Enviados -->
        <div class="order-list" id="shipped-orders">
            <!-- Pedido do exemplo anterior apareceria aqui -->
        </div>

        <!-- Lista de Pedidos - Entregues -->
        <div class="order-list" id="delivered-orders">
            <!-- Pedido do exemplo anterior apareceria aqui -->
        </div>

        <!-- Lista de Pedidos - Cancelados -->
        <div class="order-list" id="cancelled-orders">
            <div class="empty-orders">
                <i class="fas fa-ban"></i>
                <h3>Nenhum pedido cancelado</h3>
                <p>Você não tem nenhum pedido cancelado.</p>
                <a href="index.html" class="btn-continue">Continuar Comprando</a>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
     
    <?php include_once "footer.php" ?>

    <script src="js/pedidos.js"></script>
</body>

</html>