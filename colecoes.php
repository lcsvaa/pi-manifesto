<?php
require_once "conexao.php";
session_start();

// Buscar coleções
$colecoes = $pdo->query("SELECT * FROM tb_colecao")->fetchAll(PDO::FETCH_ASSOC);

// Criar mapeamento idColecao => slug
$slugPorIdColecao = [];
foreach ($colecoes as $colecao) {
    $slug = strtolower(preg_replace('/\s+/', '-', $colecao['colecaoNome']));
    $slugPorIdColecao[$colecao['id']] = $slug;
}

$stmt = $pdo->prepare("
    SELECT p.id, p.nomeItem, p.valorItem, p.idColecao, i.nomeImagem
    FROM tb_produto p
    LEFT JOIN tb_imagemproduto i ON i.idProduto = p.id AND i.statusImagem = 'principal'
    ORDER BY p.nomeItem
");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organizar produtos por coleção (não obrigatório, mas pode ser útil)
$produtosPorColecao = [];
foreach ($produtos as $produto) {
    $idCol = $produto['idColecao'];
    $slug = $slugPorIdColecao[$idCol] ?? 'sem-colecao';
    $produtosPorColecao[$slug][] = $produto;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Coleções | Manifesto</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/acessorios.css" />
    <link rel="icon" href="img/icone.png" type="image/png" />
</head>

<body>
    <!-- NAVBAR -->
    <?php include_once "navbar.php" ?>

    <!-- BANNER -->
    <div class="banner">
        <h1>Coleções</h1>
    </div>

    <!-- FILTROS -->
    <div class="filtros-container">
        <div class="filtros">
            <div class="categorias-filtro">
                <button class="category-btn active" data-category="todos">Todos</button>
                <?php foreach ($colecoes as $colecao):
                    $slug = strtolower(preg_replace('/\s+/', '-', $colecao['colecaoNome']));
                ?>
                    <button class="category-btn" data-category="<?= $slug ?>">
                        <?= htmlspecialchars($colecao['colecaoNome']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- PRODUTOS -->
    <div class="container">
        <div class="produto-lista">
            <?php foreach ($produtos as $produto):
                $slug = $slugPorIdColecao[$produto['idColecao']] ?? 'sem-colecao';
            ?>
            <div class="produto-card" data-category="<?= $slug ?>">
                <img src="uploads/produtos/<?= htmlspecialchars($produto['nomeImagem'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($produto['nomeItem']) ?>" />
                <div class="produto-info">
                    <h3><?= htmlspecialchars($produto['nomeItem']) ?></h3>
                    <span class="preco">R$ <?= number_format($produto['valorItem'], 2, ',', '.') ?></span>
                    <a href="detalhes-produto.php?id=<?= $produto['id'] ?>" class="btn-comprar">Comprar</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- FOOTER -->
    <?php include_once "footer.php" ?>

    <!-- Botão Voltar ao Topo -->
    <button id="back-to-top" class="back-to-top" aria-label="Voltar ao topo">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="js/colecoespg.js"></script>
</body>

</html>
