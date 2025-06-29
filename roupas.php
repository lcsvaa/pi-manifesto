<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Roupas - Manifesto</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/roupas.css" />

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

  <!-- BANNER -->
  <div class="banner">
    <h1>Roupas</h1>
  </div>

  <!-- FILTROS -->
  <div class="filtros-container">
    <div class="filtros">
      <!-- <h3>Filtrar por categoria:</h3> -->
      <div class="categorias-filtro">
        <button class="categoria-btn active" data-categoria="todos">Todos</button>
        <button class="categoria-btn" data-categoria="camisetas">Camisetas</button>
        <button class="categoria-btn" data-categoria="manga-longa">Manga Longa</button>
        <button class="categoria-btn" data-categoria="moletons">Moletons</button>
        <button class="categoria-btn" data-categoria="calcas">Calças</button>
        <button class="categoria-btn" data-categoria="jaquetas">Jaquetas</button>
      </div>
    </div>
  </div>

  <!-- PRODUTOS -->
  <div class="produtos-container">
    <!-- Camisetas -->
    <section class="categoria-section" id="camisetas">
      <h2>Camisetas</h2>
      <div class="produto-lista">
        <div class="produto-card">
          <img src="img/produto1.png" alt="Camiseta Básica" />
          <h3>Camiseta Básica</h3>
          <p class="preco">R$ 79,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="img/produto1.png" alt="Camiseta Oversized" />
          <h3>Camiseta Oversized</h3>
          <p class="preco">R$ 129,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="img/produto1.png" alt="Camiseta Estampada" />
          <h3>Camiseta Estampada</h3>
          <p class="preco">R$ 99,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
      </div>
    </section>

    <!-- Manga Longa -->
    <section class="categoria-section" id="manga-longa">
      <h2>Manga Longa</h2>
      <div class="produto-lista">
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/ad/aa/93/adaa934a3eeffb5189068d373266101b.jpg"
            alt="Camiseta Manga Longa Básica" />
          <h3>Manga Longa Básica</h3>
          <p class="preco">R$ 119,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/ad/aa/93/adaa934a3eeffb5189068d373266101b.jpg"
            alt="Manga Longa Estampada" />
          <h3>Manga Longa Estampada</h3>
          <p class="preco">R$ 139,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/ad/aa/93/adaa934a3eeffb5189068d373266101b.jpg"
            alt="Manga Longa Oversized" />
          <h3>Manga Longa Oversized</h3>
          <p class="preco">R$ 149,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
      </div>
    </section>

    <!-- Moletons -->
    <section class="categoria-section" id="moletons">
      <h2>Moletons</h2>
      <div class="produto-lista">
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/40/45/6a/40456a4a5e8df19cd4c9356cd3261575.jpg" alt="Moletom Capuz" />
          <h3>Moletom Capuz</h3>
          <p class="preco">R$ 229,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/40/45/6a/40456a4a5e8df19cd4c9356cd3261575.jpg" alt="Moletom Básico" />
          <h3>Moletom Básico</h3>
          <p class="preco">R$ 199,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/40/45/6a/40456a4a5e8df19cd4c9356cd3261575.jpg" alt="Moletom Estampado" />
          <h3>Moletom Estampado</h3>
          <p class="preco">R$ 249,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
      </div>
    </section>

    <!-- Calças -->
    <section class="categoria-section" id="calcas">
      <h2>Calças</h2>
      <div class="produto-lista">
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/57/08/5f/57085f31a17804021bc2cb795acdd349.jpg" alt="Calça Jogger" />
          <h3>Calça Jogger</h3>
          <p class="preco">R$ 189,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/57/08/5f/57085f31a17804021bc2cb795acdd349.jpg" alt="Calça Jeans" />
          <h3>Calça Jeans</h3>
          <p class="preco">R$ 219,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/57/08/5f/57085f31a17804021bc2cb795acdd349.jpg" alt="Calça Cargo" />
          <h3>Calça Cargo</h3>
          <p class="preco">R$ 199,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
      </div>
    </section>

    <!-- Jaquetas -->
    <section class="categoria-section" id="jaquetas">
      <h2>Jaquetas</h2>
      <div class="produto-lista">
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/2a/02/c6/2a02c6d0d7ebe96a4a981360aa9e867c.jpg" alt="Jaqueta Destroyed" />
          <h3>Jaqueta Destroyed</h3>
          <p class="preco">R$ 299,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/2a/02/c6/2a02c6d0d7ebe96a4a981360aa9e867c.jpg"
            alt="Jaqueta Corta-Vento" />
          <h3>Jaqueta Corta-Vento</h3>
          <p class="preco">R$ 259,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
        <div class="produto-card">
          <img src="https://i.pinimg.com/736x/2a/02/c6/2a02c6d0d7ebe96a4a981360aa9e867c.jpg" alt="Jaqueta de Couro" />
          <h3>Jaqueta de Couro</h3>
          <p class="preco">R$ 399,90</p>
          <button class="btn-comprar">Comprar</button>
        </div>
      </div>
    </section>
  </div>

  <!-- FOOTER -->
   
  <?php include_once "footer.php" ?>


  <!-- Botão Voltar ao Topo -->
  <button id="back-to-top" class="back-to-top" aria-label="Voltar ao topo">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- JavaScript -->
  <script src="js/script.js"></script>
  <script src="js/roupas.js"></script>
</body>

</html>