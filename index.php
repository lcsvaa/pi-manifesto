<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manifesto</title>
  <link rel="stylesheet" href="css/style.css" />

  <!-- Fontes do Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&family=Quicksand:wght@400;600&display=swap"
    rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Icone -->
  <link rel="icon" href="img/icone.png" type="image/png">

</head>

<body>

  <!-- NAVBAR -->

  <?php include_once "navbar.php" ?>

  <!-- CARROSSEL -->
<section class="carousel-section">
  <div class="carousel-wrapper">
    <div class="carousel" id="carousel">
      <?php
      require_once 'conexao.php';
      // Buscar imagens ativas ou principais, sem produto vinculado (ou com)
      $imagens = $pdo->query("
        SELECT i.*, p.nomeItem as produtoNome 
        FROM tb_imagem i
        LEFT JOIN tb_produto p ON i.idProduto = p.id
        WHERE i.statusImagem IN ('ativa', 'principal') 
        ORDER BY i.statusImagem DESC
      ");
      
      foreach ($imagens as $img) {
        echo '<div class="carousel-item">';
        echo '<img src="uploads/carrossel/'.$img['nomeImagem'].'" alt="'.htmlspecialchars($img['produtoNome'] ?? 'Imagem do carrossel').'" />';
        
        if ($img['idProduto']) {
          echo '<button class="buy-now" data-id="'.$img['idProduto'].'">Compre '.htmlspecialchars($img['produtoNome']).'</button>';
        } else {
          echo '<button class="buy-now">Ver Mais</button>';
        }
        echo '</div>';
      }
      ?>
    </div>
    <div class="carousel-controls">
      <button id="prev-button" aria-label="Slide anterior"><i class="fa fa-chevron-left"></i></button>
      <button id="next-button" aria-label="Próximo slide"><i class="fa fa-chevron-right"></i></button>
    </div>
  </div>
</section>

  <!-- LANÇAMENTOS -->
<section class="lancamentos">
  <div class="container">
    <h2>Lançamentos</h2>
    <div class="produto-lista">
      <?php
      require_once 'conexao.php';

      // Buscar os produtos mais recentes, por exemplo
      $sql = "SELECT p.id, p.nomeItem, p.valorItem, i.nomeImagem 
              FROM tb_produto p 
              LEFT JOIN tb_imagemproduto i ON i.idProduto = p.id AND i.statusImagem = 'principal'
              ORDER BY p.id DESC
              LIMIT 6";

      $stmt = $pdo->query($sql);

      while ($produto = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $nome = htmlspecialchars($produto['nomeItem']);
          $preco = number_format($produto['valorItem'], 2, ',', '.');
          $img = $produto['nomeImagem'] ? "uploads/produtos/" . $produto['nomeImagem'] : "img/default-product.png";

          echo '<div class="produto-card">';
          echo '<img src="' . $img . '" alt="' . $nome . '" />';
          echo '<h3>' . $nome . '</h3>';
          echo '<p class="preco">R$ ' . $preco . '</p>';
          echo '<a href="detalhes-produto.php?id=' . $produto['id'] . '" class="btn-comprar">Comprar</a>';
          echo '</div>';
      }
      ?>
    </div>
  </div>
</section>

  <!-- COLEÇÃO -->
  <section class="colecao">
    <div class="container">
      <h2>Coleção X</h2>
      <div class="produto-lista">
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/50/e2/7a/50e27a667474ceb55a260c860ace8926.jpg" alt="Jaqueta Destroyed" />
          <h3>Jaqueta Destroyed</h3>
          <p class="preco">R$ 299,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/c6/d8/5e/c6d85e4f5e6c01810783f4aedce6fced.jpg" alt="Moletom Capuz" />
          <h3>Moletom Capuz</h3>
          <p class="preco">R$ 229,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/32/a0/40/32a040284ec3caa9e781b291fae82374.jpg" alt="Tênis Skate" />
          <h3>Tênis Skate</h3>
          <p class="preco">R$ 349,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <?php include_once "footer.php" ?>

  <!-- JavaScript -->
  <script src="js/script.js"></script>
  <script> 
    

  </script>
</body>

</html>