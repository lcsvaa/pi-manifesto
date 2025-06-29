<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acessórios | Manifesto</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/acessorios.css">

    <link rel="icon" href="img/icone.png" type="image/png">

</head>

<body>
    <!-- NAVBAR -->
    <?php include_once "navbar.php" ?>

    <!-- BANNER -->
    <div class="banner">
        <h1>Acessórios</h1>
    </div>

    <div class="filtros-container">
        <div class="filtros">
            <!-- <h3>Filtrar por categoria:</h3> -->
            <div class="categorias-filtro">
                <button class="category-btn active" data-category="todos">Todos</button>
                <button class="category-btn" data-category="chaveiros">Chaveiros</button>
                <button class="category-btn" data-category="pins">Pins</button>
                <button class="category-btn" data-category="adesivos">Adesivos</button>
                <button class="category-btn" data-category="patches">Patches</button>
            </div>
        </div>
    </div>

    <!-- CONTEÚDO PRINCIPAL -->
    <div class="container">


        <!-- LISTA DE PRODUTOS -->
        <div class="produto-lista">
            <!-- Chaveiro 1 -->
            <div class="produto-card" data-category="chaveiros">
                <span class="tag">Novo</span>
                <img src="img/produto2.png" alt="Chaveiro Logo Metálico">
                <div class="produto-info">
                    <span class="produto-tipo">Chaveiro</span>
                    <h3>Logo Metálico</h3>
                    <span class="preco">R$ 29,90</span>
                    <button class="btn-comprar">Adicionar ao Carrinho</button>
                </div>
            </div>

            <!-- Pin 1 -->
            <div class="produto-card" data-category="pins">
                <img src="https://img.kwcdn.com/product/fancy/672a6480-d44d-4fed-8ffc-0cb21f5a473d.jpg?imageMogr2/auto-orient%7CimageView2/2/w/800/q/70/format/webp"
                    alt="Pin Especial Edição Limitada">
                <div class="produto-info">
                    <span class="produto-tipo">Pin</span>
                    <h3>Edição Limitada</h3>
                    <span class="preco">R$ 24,90</span>
                    <button class="btn-comprar">Adicionar ao Carrinho</button>
                </div>
            </div>

            <!-- Adesivo 1 -->
            <div class="produto-card" data-category="adesivos">
                <span class="tag">Promoção</span>
                <img src="img/produto3.png" alt="Pacote de Adesivos Variados">
                <div class="produto-info">
                    <span class="produto-tipo">Adesivos</span>
                    <h3>Pacote Variado</h3>
                    <span class="preco">R$ 19,90</span>
                    <button class="btn-comprar">Adicionar ao Carrinho</button>
                </div>
            </div>

            <!-- Patch 1 -->
            <div class="produto-card" data-category="patches">
                <img src="https://i.etsystatic.com/22783248/r/il/de319c/4767719348/il_fullxfull.4767719348_5jx7.jpg"
                    alt="Patch Bordado Clássico">
                <div class="produto-info">
                    <span class="produto-tipo">Patch</span>
                    <h3>Bordado Clássico</h3>
                    <span class="preco">R$ 34,90</span>
                    <button class="btn-comprar">Adicionar ao Carrinho</button>
                </div>
            </div>

            <!-- Chaveiro 2 -->
            <div class="produto-card" data-category="chaveiros">
                <img src="img/produto12.png" alt="Chaveiro de Borracha">
                <div class="produto-info">
                    <span class="produto-tipo">Chaveiro</span>
                    <h3>Modelo Borracha</h3>
                    <span class="preco">R$ 22,90</span>
                    <button class="btn-comprar">Adicionar ao Carrinho</button>
                </div>
            </div>

            <!-- Pin 2 -->
            <div class="produto-card" data-category="pins">
                <span class="tag">Novo</span>
                <img src="https://www.foreverskateshop.com.br/cdn/shop/files/pin-powell-peralta-Skeleton-01_530x.png?v=1718738786"
                    alt="Pin de Skate">
                <div class="produto-info">
                    <span class="produto-tipo">Pin</span>
                    <h3>Skate Vintage</h3>
                    <span class="preco">R$ 27,90</span>
                    <button class="btn-comprar">Adicionar ao Carrinho</button>
                </div>
            </div>

            <!-- Adesivo 2 -->
            <div class="produto-card" data-category="adesivos">
                <img src="https://as2.ftcdn.net/jpg/05/93/30/63/1024W_F_593306397_Qk7QjXhBQfZT8C13uLSAhxFuUBjTEYw0_NW1.jpg"
                    alt="Adesivo Grande">
                <div class="produto-info">
                    <span class="produto-tipo">Adesivo</span>
                    <h3>Grande Logo</h3>
                    <span class="preco">R$ 14,90</span>
                    <button class="btn-comprar">Adicionar ao Carrinho</button>
                </div>
            </div>

            <!-- Patch 2 -->
            <div class="produto-card" data-category="patches">
                <span class="tag">Últimas Unidades</span>
                <img src="https://t3.ftcdn.net/jpg/05/43/61/76/240_F_543617652_IWrHcgA8DbKFpijhSeHHUsOCpWvy9byp.jpg"
                    alt="Patch Especial">
                <div class="produto-info">
                    <span class="produto-tipo">Patch</span>
                    <h3>Edição Especial</h3>
                    <span class="preco">R$ 39,90</span>
                    <button class="btn-comprar">Adicionar ao Carrinho</button>
                </div>
            </div>
        </div>
    </div>

      <!-- FOOTER -->
    <?php include_once "footer.php" ?>

<!-- Botão Voltar ao Topo -->
<button id="back-to-top" class="back-to-top" aria-label="Voltar ao topo">
  <i class="fas fa-arrow-up"></i>
</button>

    <script src="js/acessorios.js"></script>
</body>

</html>