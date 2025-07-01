<?php
require_once "conexao.php";
session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "Produto inválido.";
    exit;
}

// Buscar dados do produto
$stmt = $pdo->prepare("
    SELECT p.*, c.ctgNome, co.colecaoNome, i.nomeImagem
    FROM tb_produto p
    LEFT JOIN tb_imagem i ON i.idProduto = p.id
    LEFT JOIN tb_categoria c ON p.idCategoria = c.id
    LEFT JOIN tb_colecao co ON p.idColecao = co.id
    WHERE p.id = :id
");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo "Produto não encontrado.";
    exit;
}

// Buscar imagens do produto para a galeria
$stmtImgs = $pdo->prepare("SELECT nomeImagem FROM tb_imagemproduto WHERE idProduto = :idProduto ORDER BY statusImagem DESC");
$stmtImgs->bindParam(':idProduto', $id, PDO::PARAM_INT);
$stmtImgs->execute();
$imagens = $stmtImgs->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Detalhes do Produto - <?= htmlspecialchars($produto['nomeItem']) ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/detalhes-produto.css" />
</head>

<body>
  <!-- NAVBAR -->
  <?php include_once "navbar.php" ?>

  <div class="container">
    <div class="product-detail">
      <!-- Galeria de Imagens -->
      <div class="product-gallery">
        <?php 

        $imagemPrincipal = $imagens[0]['nomeImagem'] ?? '';
        ?>
        <?php if ($imagemPrincipal): ?>
          <img src="uploads/produtos/<?= htmlspecialchars($imagemPrincipal) ?>" alt="<?= htmlspecialchars($produto['nomeItem']) ?>" class="main-image" id="mainImage">
        <?php else: ?>
          <p>Imagem não disponível</p>
        <?php endif; ?>

        <div class="thumbnail-container">
          <?php foreach ($imagens as $index => $img): ?>
            <img 
              src="uploads/produtos/<?= htmlspecialchars($img['nomeImagem']) ?>" 
              alt="Miniatura <?= $index + 1 ?>" 
              class="thumbnail <?= $index === 0 ? 'active' : '' ?>" 
              onclick="changeImage(this, 'uploads/produtos/<?= htmlspecialchars($img['nomeImagem']) ?>')"
            >
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Informações do Produto -->
      <div class="product-info">
        <h1 class="product-title"><?= htmlspecialchars($produto['nomeItem']) ?></h1>

        <div class="product-price">R$ <?= number_format($produto['valorItem'], 2, ',', '.') ?></div>

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

        <div class="quantity-control">
          <span class="option-title">Quantidade:</span>
          <button class="qty-btn" onclick="changeQuantity(-1)">-</button>
          <input type="number" value="1" min="1" class="qty-input" id="quantity" max="<?= intval($produto['estoqueItem']) ?>">
          <button class="qty-btn" onclick="changeQuantity(1)">+</button>
        </div>

        <button class="add-to-cart" id="btnAdd">
          <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
        </button>

        <div class="product-description">
          <h3>Descrição do Produto</h3>
          <p><?= nl2br(htmlspecialchars($produto['descItem'])) ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <?php include_once "footer.php" ?>

  <script src="js/detalhes-produto.js"></script>
  <script src="js/cart.js"></script>

</body>

</html>
