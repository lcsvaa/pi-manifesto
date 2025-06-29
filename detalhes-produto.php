<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalhes do Produto - Manifesto</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/detalhes-produto.css">

</head>

<body>

  <!-- NAVBAR -->

  <?php include_once "navbar.php" ?>

  <div class="container">
    <div class="product-detail">
      <!-- Galeria de Imagens -->
      <div class="product-gallery">
        <img src="img/produto1.png" alt="Camiseta Oversized" class="main-image" id="mainImage">
        <div class="thumbnail-container">
          <img src="img/produto1.png" alt="Miniatura 1" class="thumbnail active" onclick="changeImage(this, 'https://via.placeholder.com/600x600')">
          <img src="img/produto1.png" alt="Miniatura 2" class="thumbnail" onclick="changeImage(this, 'https://via.placeholder.com/600x600/333')">
          <img src="img/produto1.png" alt="Miniatura 3" class="thumbnail" onclick="changeImage(this, 'https://via.placeholder.com/600x600/555')">
        </div>
      </div>

      <!-- Informações do Produto -->
      <div class="product-info">
        <h1 class="product-title">Camiseta Oversized</h1>

        <div class="product-price">R$ 129,90</div>

        <!-- Tamanhos -->
        <div class="option-group">
          <span class="option-title">Tamanho:</span>
          <div class="size-options">
            <div class="size-btn selected">P</div>
            <div class="size-btn">M</div>
            <div class="size-btn">G</div>
            <div class="size-btn">GG</div>
          </div>
        </div>

        <!-- Cores -->
        <div class="option-group">
          <span class="option-title">Cor:</span>
          <div class="color-options">
            <div class="color-btn selected" style="background-color: #000;"></div>
            <div class="color-btn" style="background-color: #e91e63;"></div>
            <div class="color-btn" style="background-color: #2196F3;"></div>
          </div>
        </div>

        <!-- Quantidade -->
        <div class="quantity-control">
          <span class="option-title">Quantidade:</span>
          <button class="qty-btn" onclick="changeQuantity(-1)">-</button>
          <input type="number" value="1" min="1" class="qty-input" id="quantity">
          <button class="qty-btn" onclick="changeQuantity(1)">+</button>
        </div>

        <!-- Botão Comprar -->
        <button class="add-to-cart">
          <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
        </button>

        <!-- Descrição -->
        <div class="product-description">
          <h3>Descrição do Produto</h3>
          <p>Camiseta oversized de algodão orgânico com corte relaxado e caimento perfeito. Ideal para looks casuais e confortáveis. Possui estampa exclusiva serigrafada e costuras reforçadas para maior durabilidade.</p>
          <ul>
            <li>100% algodão orgânico</li>
            <li>Estampa serigrafada</li>
            <li>Corte oversized</li>
            <li>Disponível em 4 tamanhos</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->

  <?php include_once "footer.php" ?>


  <script src="js/detalhes-produto.js"></script>
</body>

</html>