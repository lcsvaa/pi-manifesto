<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Manifesto</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/checkout.css">

    <link rel="icon" href="img/icone.png" type="image/png">
</head>
<body>
    <!-- NAVBAR -->

    <?php include_once "navbar.php" ?>

    <!-- BANNER -->
    <div class="banner">
        <h1>Finalizar Compra</h1>
    </div>

    <!-- CONTEÚDO PRINCIPAL -->
    <div class="checkout-container">
        <!-- Formulário de Entrega e Pagamento -->
        <div class="checkout-form">
            <h2 class="checkout-title">Informações de Entrega</h2>

            <div class="endereco-group">
                <h2 class="checkout-title">Selecione um Endereço de Entrega</h2>
                    <div class="shipping-methods" id="enderecos-container"></div>
                <button type="button" id="btn-novo-endereco" class="checkout-btn" style="margin-top: 10px;">
                    <i class="fas fa-plus"></i> Usar Novo Endereço
                </button>
            </div>
            
            <form id="delivery-form" style="display: none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" required>
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="numero">Número</label>
                        <input type="text" id="numero" name="numero" required>
                    </div>
                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro" required>
                    </div>
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" required>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" required>
                            <option value="">Selecione</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <!-- outros estados -->
                            <option value="SP">São Paulo</option>
                        </select>
                    </div>
                    </form>
                </div>
                
                <h2 class="checkout-title">Método de Envio</h2>
                <div class="shipping-methods">
                    <label class="shipping-option">
                        <input type="radio" name="shipping" value="standard" checked>
                        <div class="shipping-info">
                            <span class="shipping-name">Entrega Padrão</span>
                            <span class="shipping-price">R$ 15,90</span>
                            <span class="shipping-time">3-5 dias úteis</span>
                        </div>
                    </label>
                    
                    <label class="shipping-option">
                        <input type="radio" name="shipping" value="express">
                        <div class="shipping-info">
                            <span class="shipping-name">Entrega Expressa</span>
                            <span class="shipping-price">R$ 29,90</span>
                            <span class="shipping-time">1-2 dias úteis</span>
                        </div>
                    </label>
                </div>
                
                <h2 class="checkout-title">Método de Pagamento</h2>
                <div class="payment-methods">
                    <div class="payment-tabs">
                        <button type="button" class="payment-tab active" data-tab="credit">Cartão de Crédito</button>
                        <button type="button" class="payment-tab" data-tab="pix">PIX</button>
                        <button type="button" class="payment-tab" data-tab="boleto">Boleto</button>
                    </div>
                    
                    <div class="payment-content active" id="credit-tab">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card-number">Número do Cartão</label>
                                <input type="text" id="card-number" name="card-number" placeholder="0000 0000 0000 0000">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card-name">Nome no Cartão</label>
                                <input type="text" id="card-name" name="card-name">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card-expiry">Validade</label>
                                <input type="text" id="card-expiry" name="card-expiry" placeholder="MM/AA">
                            </div>
                            <div class="form-group">
                                <label for="card-cvv">CVV</label>
                                <input type="text" id="card-cvv" name="card-cvv" placeholder="000">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card-installments">Parcelamento</label>
                                <select id="card-installments" name="card-installments">
                                    <option value="1">1x de R$ 485,60</option>
                                    <option value="2">2x de R$ 242,80</option>
                                    <option value="3">3x de R$ 161,87</option>
                                    <option value="4">4x de R$ 121,40</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="payment-content" id="pix-tab">
                        <div class="pix-info">
                            <p>Você será redirecionado para realizar o pagamento via PIX após confirmar o pedido.</p>
                            <p>O pedido será processado assim que o pagamento for confirmado.</p>
                        </div>
                    </div>
                    
                    <div class="payment-content" id="boleto-tab">
                        <div class="boleto-info">
                            <p>O boleto será gerado após a confirmação do pedido e enviado para o seu e-mail.</p>
                            <p>O pedido será processado após a confirmação do pagamento, que pode levar até 3 dias úteis.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Resumo do Pedido -->
        <div class="checkout-summary">
            <h2 class="checkout-title">Resumo do Pedido</h2>
            
            <div class="summary-card">
                <div class="order-items">
                    <div class="order-item">
                        <img src="img/produtos/produto1.jpg" alt="Moletom Oversized" class="item-image">
                        <div class="item-details">
                            <h3 class="item-name">Moletom Oversized</h3>
                            <p class="item-variant">Tamanho: M | Cor: Preto</p>
                            <p class="item-price">R$ 249,90</p>
                            <p class="item-quantity">Quantidade: 1</p>
                        </div>
                    </div>
                    
                    <div class="order-item">
                        <img src="img/produtos/produto2.jpg" alt="Camiseta Estampada" class="item-image">
                        <div class="item-details">
                            <h3 class="item-name">Camiseta Estampada</h3>
                            <p class="item-variant">Tamanho: G | Cor: Branco</p>
                            <p class="item-price">R$ 129,90</p>
                            <p class="item-quantity">Quantidade: 2</p>
                        </div>
                    </div>
                    
                    <div class="order-item">
                        <img src="img/produtos/produto4.jpg" alt="Boné Ajustável" class="item-image">
                        <div class="item-details">
                            <h3 class="item-name">Boné Ajustável</h3>
                            <p class="item-variant">Cor: Preto</p>
                            <p class="item-price">R$ 89,90</p>
                            <p class="item-quantity">Quantidade: 1</p>
                        </div>
                    </div>
                </div>
                
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
            
            <button class="checkout-btn" id="confirm-order">Confirmar Pedido</button>
        </div>
    </div>

    <!-- FOOTER -->
     
    <?php include_once "footer.php" ?>

    <script src="js/checkout.js"></script>
</body>
</html>