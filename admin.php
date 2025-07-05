<?php
// Inicia a sessão de forma segura
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Verificação de segurança
if (!isset($_SESSION['is_admin'])) {
  session_unset();
  session_destroy();
  header('Location: login.php?error=acesso_negado');
  exit();
}

// Remove qualquer vestígio de sessão de usuário comum
if (isset($_SESSION['user_id'])) {
  unset($_SESSION['user_id']);
  unset($_SESSION['user_name']);
}

require_once 'conexao.php';

// Definição do título da página (sem consulta ao banco pois não temos user_id para admin)
$page_title = "Painel Administrativo";
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Painel Administrativo | Manifesto</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/admin.css" />
  <link rel="stylesheet" href="css/admin-novidades.css">
  <link rel="stylesheet" href="css/notificacao.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Fontes do Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Icone -->
  <link rel="icon" href="img/icone.png" type="image/png">
</head>

<body class="admin-body">

  <!-- NAVBAR ADMIN -->
  <nav class="navbar admin-nav">
    <div class="container">
      <div class="nav-left">
        <a href="index.php">
          <img src="img/logo.png" alt="Logo da loja" class="logo" />
        </a>
        <div class="nav-right">
          <span class="admin-welcome"><b>Olá, Admin</b></span>
          <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
      </div>
  </nav>

  <!-- CATEGORIAS -->
  <div class="admin-categories">
    <div class="container">
      <div class="category-tabs">
        <button class="category-tab active" data-category="clientes">
          <i class="fas fa-users"></i> Clientes
        </button>
        <button class="category-tab" data-category="pedidos">
          <i class="fas fa-shopping-bag"></i> Pedidos
        </button>
        <button class="category-tab" data-category="conteudo">
          <i class="fas fa-paint-brush"></i> Conteúdo do Site
        </button>
        <button class="category-tab" data-category="produtos">
          <i class="fas fa-tshirt"></i> Produtos e Estoque
        </button>
        <button class="category-tab" data-category="cupons">
          <i class="fas fa-tag"></i> Cupons de Desconto
        </button>
      </div>
    </div>
  </div>

  <!-- CONTEÚDO PRINCIPAL -->
  <main class="admin-main">
    <div class="container">
      <!-- Seção Clientes -->
      <section class="admin-section active" id="clientes-section">
        <h1 class="section-title"><i class="fas fa-users"></i> Gerenciamento de Clientes</h1>

        <?php
        // Processa mensagens de feedback
        if (isset($_SESSION['message'])): ?>
          <div class="message"><?= $_SESSION['message'] ?></div>
          <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="search-filter">
          <input type="text" id="client-search" placeholder="Buscar por nome, e-mail ou CPF..." class="admin-search">
          <select class="admin-filter" id="client-status-filter">
            <option value="all">Todos os status</option>
            <option value="ativo">Ativos</option>
            <option value="desativado">Desativados</option>
            <option value="admin">Administradores</option>
          </select>
        </div>

        <div class="table-responsive">
          <table class="clientes-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>CPF</th>
                <th>Data Nasc.</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Busca os clientes no banco de dados
              try {
                $query = "SELECT id, nomeUser, email, cpf, dataNascimento, tipoUser, statusATV 
                    FROM tb_usuario 
                    ORDER BY id ASC";
                $stmt = $pdo->query($query);

                while ($cliente = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                  <tr>
                    <td data-label="ID"><?= htmlspecialchars($cliente['id']) ?></td>
                    <td data-label="Nome"><?= htmlspecialchars($cliente['nomeUser']) ?></td>
                    <td data-label="E-mail"><?= htmlspecialchars($cliente['email']) ?></td>
                    <td data-label="CPF"><?= htmlspecialchars($cliente['cpf']) ?></td>
                    <td data-label="Data Nasc."><?= $cliente['dataNascimento'] ? date('d/m/Y', strtotime($cliente['dataNascimento'])) : '--' ?></td>
                    <td data-label="Tipo"><?= ucfirst(htmlspecialchars($cliente['tipoUser'])) ?></td>
                    <td data-label="Status">
                      <span class="status-badge <?= htmlspecialchars($cliente['statusATV']) ?> <?= $cliente['tipoUser'] === 'admin' ? 'admin' : '' ?>">
                        <?= ucfirst(htmlspecialchars($cliente['statusATV'])) ?>
                      </span>
                    </td>
                    <td data-label="Ações" class="actions">
                      <button class="btn-action btn-view view-btn" data-id="<?= $cliente['id'] ?>">
                        <i class="fas fa-eye"></i>
                      </button>

                      <?php if ($cliente['tipoUser'] !== 'admin'): ?>
                        <form method="post" style="display:inline;">
                          <input type="hidden" name="action" value="promote">
                          <input type="hidden" name="user_id" value="<?= $cliente['id'] ?>">
                          <button type="submit" class="btn-action btn-promote"
                            onclick="return confirm('Tem certeza que deseja tornar este usuário administrador?')">
                            <i class="fas fa-user-shield"></i>
                          </button>
                        </form>
                      <?php else: ?>
                        <form method="post" style="display:inline;">
                          <input type="hidden" name="action" value="demote">
                          <input type="hidden" name="user_id" value="<?= $cliente['id'] ?>">
                          <button type="submit" class="btn-action btn-demote"
                            onclick="return confirm('Remover privilégios de admin deste usuário?')">
                            <i class="fas fa-user-minus"></i>
                          </button>
                        </form>
                      <?php endif; ?>

                      <form method="post" style="display:inline;">
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="user_id" value="<?= $cliente['id'] ?>">
                        <button type="submit" class="btn-action <?= $cliente['statusATV'] == 'ativo' ? 'btn-disable' : 'btn-enable' ?>">
                          <?php if ($cliente['statusATV'] == 'ativo'): ?>
                            <i class="fas fa-ban"></i>
                          <?php else: ?>
                            <i class="fas fa-check"></i>
                          <?php endif; ?>
                        </button>
                      </form>

                      <form method="post" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user_id" value="<?= $cliente['id'] ?>">
                        <button type="submit" class="btn-action btn-delete"
                          onclick="return confirm('Tem certeza que deseja excluir permanentemente este usuário?')">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php } catch (PDOException $e) { ?>
                <tr>
                  <td colspan="8" style="color:red;text-align:center;">
                    Erro ao carregar clientes: <?= $e->getMessage() ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </section>


      <!-- Seção Pedidos -->
      <section class="admin-section" id="pedidos-section">
        <h1 class="section-title"><i class="fas fa-shopping-bag"></i> Gerenciamento de Pedidos</h1>

        <div class="section-actions">
          <div class="search-filter">
            <input type="text" placeholder="Buscar pedido..." class="admin-search">
            <select class="admin-filter">
              <option>Todos os status</option>
              <option>Pendente</option>
              <option>Processando</option>
              <option>Enviado</option>
              <option>Entregue</option>
              <option>Cancelado</option>
            </select>
          </div>
        </div>

        <div class="pedidos-list">
          <div class="pedido-card">
            <div class="pedido-header">
              <span class="pedido-id">#ORD-2023-001</span>
              <span class="pedido-status pending">Pendente</span>
              <span class="pedido-date">15/05/2023</span>
              <span class="pedido-total">R$ 429,80</span>
            </div>
            <div class="pedido-details">
              <div class="pedido-cliente">
                <p><strong>Cliente:</strong> João Silva</p>
                <p><strong>E-mail:</strong> joao@exemplo.com</p>
              </div>
              <div class="pedido-produtos">
                <p><strong>Produtos:</strong></p>
                <ul>
                  <li>Camiseta Oversized (R$ 129,90) - Tamanho: M</li>
                  <li>Calça Jogger (R$ 189,90) - Tamanho: 42</li>
                  <li>Boné Snapback (R$ 89,90)</li>
                </ul>
              </div>
              <div class="pedido-actions">
                <button class="btn-action processar"><i class="fas fa-cog"></i> Processar</button>
                <button class="btn-action cancelar"><i class="fas fa-times"></i> Cancelar</button>
                <button class="btn-action detalhes"><i class="fas fa-eye"></i> Detalhes</button>
              </div>
            </div>
          </div>

          <div class="pedido-card">
            <div class="pedido-header">
              <span class="pedido-id">#ORD-2023-002</span>
              <span class="pedido-status shipped">Enviado</span>
              <span class="pedido-date">10/05/2023</span>
              <span class="pedido-total">R$ 299,90</span>
            </div>
            <div class="pedido-details">
              <div class="pedido-cliente">
                <p><strong>Cliente:</strong> Maria Souza</p>
                <p><strong>E-mail:</strong> maria@exemplo.com</p>
              </div>
              <div class="pedido-produtos">
                <p><strong>Produtos:</strong></p>
                <ul>
                  <li>Jaqueta Destroyed (R$ 299,90) - Tamanho: G</li>
                </ul>
              </div>
              <div class="pedido-actions">
                <button class="btn-action rastreio"><i class="fas fa-truck"></i> Atualizar Rastreio</button>
                <button class="btn-action concluir"><i class="fas fa-check"></i> Marcar como Entregue</button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Seção Conteúdo do Site -->
      <section class="admin-section" id="conteudo-section">
        <h1 class="section-title"><i class="fas fa-paint-brush"></i> Gerenciamento de Conteúdo</h1>

        <div class="content-tabs">
          <button class="content-tab active" data-content="carrossel">Carrossel</button>
          <button class="content-tab" data-content="lancamentos">Lançamentos</button>
          <button class="content-tab" data-content="colecao">Coleção X</button>
          <button class="content-tab" data-content="novidades">Novidades</button>
          <button class="content-tab" data-content="categorias">Categorias e Coleções</button>
        </div>

        <!-- Subseção Carrossel -->
      <div class="content-panel active" id="carrossel-panel">
        <h2><i class="fas fa-images"></i> Gerenciar Carrossel</h2>
  
  <div class="current-items">
    <h3>Imagens Ativas no Site</h3>
    <div class="items-grid" id="carrossel-grid">
      <?php
      require_once 'conexao.php';
      // Buscar apenas imagens ativas ou principais
      $imagens = $pdo->query("SELECT * FROM tb_imagem WHERE statusImagem IN ('ativa', 'principal') ORDER BY statusImagem DESC");

      foreach ($imagens as $img) {
        echo '
        <div class="item-card" data-id="'.$img['idImagem'].'">
          <img src="uploads/carrossel/'.$img['nomeImagem'].'" alt="Imagem do carrossel">
          <div class="item-info">
            <span class="status-badge '.$img['statusImagem'].'">'.$img['statusImagem'].'</span>
          </div>
          <div class="item-actions">
            <button class="btn-action editar" data-id="'.$img['idImagem'].'">
              <i class="fas fa-edit"></i> Editar
            </button>
            <button class="btn-action remover" data-id="'.$img['idImagem'].'">
              <i class="fas fa-trash"></i> Remover
            </button>
          </div>
        </div>';
      }
      ?>
    </div>
  </div>

  <div class="add-item-form">
    <h3>Adicionar Nova Imagem ao Carrossel</h3>
    <form id="form-carrossel" enctype="multipart/form-data">
      <div class="form-row">
        <div class="form-group">
          <label for="carrossel-image">Imagem:</label>
          <input type="file" id="carrossel-image" name="imagem" accept="image/*" required>
          <small>Tamanho recomendado: 1200x600px</small>
        </div>
        <div class="form-group">
          <label for="carrossel-status">Status:</label>
          <select id="carrossel-status" name="status">
            <option value="inativa">Inativa (não aparece no site)</option>
            <option value="ativa">Ativa</option>
            <option value="principal">Principal (destaque)</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="carrossel-link">Link da imagem (opcional):</label>
        <input type="url" id="carrossel-link" name="link" placeholder="https://example.com">
      </div>

      <div class="form-buttons">
        <button type="submit" class="btn-submit">Adicionar Imagem</button>
      </div>
    </form>
  </div>
</div>

        <!-- Subseção Lançamentos -->
        <div class="content-panel" id="lancamentos-panel">
          <h2><i class="fas fa-star"></i> Gerenciar Lançamentos</h2>

          <div class="current-items">
            <h3>Produtos em Destaque</h3>
            <div class="items-grid">
              <div class="item-card">
                <img src="img/produto10.png" alt="Camiseta Oversized">
                <div class="item-info">
                  <h4>Camiseta Oversized</h4>
                  <p>R$ 129,90</p>
                </div>
                <div class="item-actions">
                  <button class="btn-action editar"><i class="fas fa-edit"></i> Editar</button>
                  <button class="btn-action remover"><i class="fas fa-trash"></i> Remover</button>
                </div>
              </div>
            </div>
          </div>

          <div class="add-item-form">
            <h3>Adicionar Produto</h3>
            <form>
              <div class="form-group">
                <label for="lancamento-produto">Selecionar Produto:</label>
                <select id="lancamento-produto">
                  <option>Selecione um produto</option>
                  <option>Camiseta Premium</option>
                  <option>Calça Jogger</option>
                  <option>Boné Snapback</option>
                </select>
              </div>
              <div class="form-buttons">
                <button type="submit" class="btn-submit">Adicionar à Seção</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Subseção Coleção X -->
        <div class="content-panel" id="colecao-panel">
          <h2><i class="fas fa-star"></i> Gerenciar Coleção X</h2>

          <div class="current-items">
            <h3>Produtos em Destaque</h3>
            <div class="items-grid">
              <div class="item-card">
                <img src="img/produto10.png" alt="Camiseta Oversized">
                <div class="item-info">
                  <h4>Camiseta Oversized</h4>
                  <p>R$ 129,90</p>
                </div>
                <div class="item-actions">
                  <button class="btn-action editar"><i class="fas fa-edit"></i> Editar</button>
                  <button class="btn-action remover"><i class="fas fa-trash"></i> Remover</button>
                </div>
              </div>
            </div>
          </div>

          <div class="add-item-form">
            <h3>Adicionar Produto</h3>
            <form>
              <div class="form-group">
                <label for="colecao-produto">Selecionar Produto:</label>
                <select id="colecao-produto">
                  <option>Selecione um produto</option>
                  <option>Camiseta Premium</option>
                  <option>Calça Jogger</option>
                  <option>Boné Snapback</option>
                </select>
              </div>
              <div class="form-buttons">
                <button type="submit" class="btn-submit">Adicionar à Seção</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Subseção Novidades -->
        <div class="content-panel" id="novidades-panel">
          <h2><i class="fas fa-newspaper"></i> Gerenciar Novidades</h2>

          <div class="current-items">
            <h3>Postagens Atuais</h3>
            <!-- <div class="items-grid">
              <div class="item-card">
                <img src="https://i.pinimg.com/736x/8e/cf/2d/8ecf2d139e5a08d19ea8a4767b23c165.jpg" alt="Postagem 1">
                <div class="item-info">
                  <h4>Conheça nossa nova coleção Outono/Inverno</h4>
                  <p class="item-date">15 MARÇO 2023</p>
                </div>
                <div class="item-actions">
                  <button class="btn-action editar"><i class="fas fa-edit"></i> Editar</button>
                  <button class="btn-action remover"><i class="fas fa-trash"></i> Remover</button>
                </div>
              </div>
            </div> -->
            <div id="novidades-grid"></div>
          </div>

          <div class="add-item-form">
            <h3>Adicionar Nova Postagem</h3>
            <form id="form-novidade" action="api_novidades/adicionar_novidades.php" method="post" enctype="multipart/form-data">
              <div class="form-row">
                <div class="form-group">
                  <label for="post-title">Título:</label>
                  <input type="text" name="titulo" id="post-title" placeholder="Título da postagem">
                </div>
                <div class="form-group">
                  <label for="post-date">Data:</label>
                  <input type="date" name="data" id="post-date">
                </div>
              </div>
              <div class="form-group">
                <label for="post-image">Imagem:</label>
                <input type="file" name="imagem" id="post-image" accept="image/*">
              </div>
              <div class="form-group">
                <label for="post-content">Conteúdo:</label>
                <textarea name="conteudo" id="post-content" rows="5" placeholder="Conteúdo da postagem"></textarea>
              </div>
              <div class="form-buttons">
                <button type="submit" class="btn-submit">Publicar Postagem</button>
              </div>
            </form>
          </div>
        </div>
      </section>

      <!-- Adicione isso após a subseção Novidades -->
<div class="content-panel" id="categorias-panel">
  <h2><i class="fas fa-tags"></i> Gerenciar Categorias e Coleções</h2>

  <div class="dual-management">
    <!-- Seção de Categorias -->
    <div class="management-section">
      <h3><i class="fas fa-tag"></i> Categorias</h3>
      
      <div class="current-items">
        <div class="items-grid" id="categorias-grid">
          <?php
          $categorias = $pdo->query("SELECT * FROM tb_categoria ORDER BY id");
          foreach ($categorias as $cat): ?>
            <div class="item-card" data-id="<?= $cat['id'] ?>">
              <div class="item-info">
                <h4><?= htmlspecialchars($cat['ctgNome']) ?></h4>
              </div>
              <div class="item-actions">
                <button class="btn-action editar" data-id="<?= $cat['id'] ?>">
                  <i class="fas fa-edit"></i> Editar
                </button>
                <button class="btn-action remover" data-id="<?= $cat['id'] ?>">
                  <i class="fas fa-trash"></i> Remover
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="add-item-form">
        <h4>Adicionar Nova Categoria</h4>
        <form id="form-categoria">
          <div class="form-group">
            <label for="categoria-nome">Nome da Categoria:</label>
            <input type="text" id="categoria-nome" name="nome" required>
          </div>
          <div class="form-buttons">
            <button type="submit" class="btn-submit">Adicionar Categoria</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Seção de Coleções -->
    <div class="management-section">
      <h3><i class="fas fa-layer-group"></i> Coleções</h3>
      
      <div class="current-items">
        <div class="items-grid" id="colecoes-grid">
          <?php
          $colecoes = $pdo->query("SELECT * FROM tb_colecao ORDER BY id");
          foreach ($colecoes as $col): ?>
            <div class="item-card" data-id="<?= $col['id'] ?>">
              <div class="item-info">
                <h4><?= htmlspecialchars($col['colecaoNome']) ?></h4>
              </div>
              <div class="item-actions">
                <button class="btn-action editar" data-id="<?= $col['id'] ?>">
                  <i class="fas fa-edit"></i> Editar
                </button>
                <button class="btn-action remover" data-id="<?= $col['id'] ?>">
                  <i class="fas fa-trash"></i> Remover
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="add-item-form">
        <h4>Adicionar Nova Coleção</h4>
        <form id="form-colecao">
          <div class="form-group">
            <label for="colecao-nome">Nome da Coleção:</label>
            <input type="text" id="colecao-nome" name="nome" required>
          </div>
          <div class="form-buttons">
            <button type="submit" class="btn-submit">Adicionar Coleção</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

      <!-- Seção Produtos -->
      <!-- Seção Produtos -->
      <section class="admin-section" id="produtos-section">
        <h1 class="section-title"><i class="fas fa-tshirt"></i> Gerenciamento de Produtos</h1>

        <div class="section-actions">
          <button class="btn-action add-produto-btn"><i class="fas fa-plus"></i> Adicionar Novo Produto</button>
          <div class="search-filter">
            <input type="text" placeholder="Buscar produto por nome..." class="admin-search" id="busca-produto-adm">
            <select class="admin-filter" id="produto-categoria" name="idCategoria">
                  <option value="">Selecione</option>
            </select>
          </div>
        </div>

        <div class="produtos-grid" id="produtos-container"></div>

        <!-- Formulário para adicionar novo produto -->
        <div class="add-produto-form" style="display: none;">
          <h2><i class="fas fa-plus-circle"></i> Adicionar Novo Produto</h2>
          <form id="form-produto" enctype="multipart/form-data">
              <input type="hidden" id="produto-id" name="id" value="">
              
              <div class="form-row">
                <div class="form-group">
                  <label for="produto-nome">Nome do Produto:</label>
                  <input type="text" id="produto-nome" name="nomeItem" required>
                </div>
                <div class="form-group">
                  <label for="produto-categoria">Categoria:</label>
                  <select class="admin-filter" id="produto-categoria-form" name="idCategoria" required>
                    <option value="">Selecione</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="produto-colecao">Coleção:</label>
                  <select id="produto-colecao" name="idColecao" required>
                    <option value="">Selecione</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="produto-preco">Preço (R$):</label>
                  <input type="number" id="produto-preco" name="valorItem" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                  <label for="produto-estoque">Estoque Inicial (total):</label>
                  <input type="number" id="produto-estoque" name="estoqueItem" min="0" required>
                </div>
              </div>

              <div class="form-group" id="tamanhoUnicoCheck">
                <label for="tamanhoUnico">
                  Produto tamanho único?
                  <input type="checkbox" id="produto-tamanho-unico" name="tamanhoUnico">
                </label>
              </div>

              <div id="estoque-tamanhos-container" style="display:none; margin-bottom: 1rem;">
                <div class="form-group">
                  <label for="estoque-p">Estoque P:</label>
                  <input type="number" id="estoque-p" name="estoqueP" min="0" value="0">
                </div>
                <div class="form-group">
                  <label for="estoque-m">Estoque M:</label>
                  <input type="number" id="estoque-m" name="estoqueM" min="0" value="0">
                </div>
                <div class="form-group">
                  <label for="estoque-g">Estoque G:</label>
                  <input type="number" id="estoque-g" name="estoqueG" min="0" value="0">
                </div>
                <p id="msg-erro-estoque" style="color:red; display:none; margin-top:0.5rem;">
                  A soma dos estoques P, M e G não pode ser maior que o estoque total.
                </p>
              </div>

              <div class="form-group">
                <label for="produto-descricao">Descrição:</label>
                <textarea id="produto-descricao" name="descItem" rows="3" required></textarea>
              </div>

              <div class="form-group">
                <label for="produto-imagem-principal">Imagem Principal:</label>
                <input type="file" id="produto-imagem-principal" name="imagemPrincipal" accept="image/*" required>
              </div>

              <div class="form-group">
                <label for="produto-outras-imagens">Outras Imagens:</label>
                <input type="file" id="produto-outras-imagens" name="outrasImagens[]" accept="image/*" multiple>
              </div>

              <div class="form-buttons">
                <button type="submit" class="btn-submit">Salvar Produto</button>
                <button type="button" class="btn-cancel">Cancelar</button>
              </div>
            </form>
        </div>
      </section>

      <!-- Seção Cupons -->
      <section class="admin-section" id="cupons-section">
        <h1 class="section-title"><i class="fas fa-tag"></i> Gerenciamento de Cupons</h1>

        <div class="section-actions">
          <button class="btn-action add-cupom-btn"><i class="fas fa-plus"></i> Criar Novo Cupom</button>
        </div>

        <div class="cupons-list" id="lista-cupons"></div>

        <!-- Formulário para adicionar novo cupom -->
        <div class="add-cupom-form" style="display: none;">
          <h2><i class="fas fa-tag"></i> Criar Novo Cupom</h2>
        <form id="cupomForm">
        <input type="hidden" id="cupom-id" name="id"> <!-- nome correto aqui -->
        <div class="form-row">
          <div class="form-group">
            <label for="cupom-codigo">Código do Cupom:</label>
            <input type="text" id="cupom-codigo" name="codigo" required>
          </div>
          <div class="form-group">
            <label for="porcentagemDesconto">Valor do Desconto:</label>
            <input type="number" id="cupom-desconto" name="porcentagemDesconto" min="1" required>
          </div>
          <div class="form-group">
            <label for="cupom-tipo">Tipo de Desconto:</label>
            <select id="cupom-tipo" name="tipoDesconto" required>
              <option value="" disabled selected>Selecione</option>
              <option value="porcentagem">Porcentagem</option>
              <option value="valor">Valor</option>
            </select>
          
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="cupom-validade">Data de Validade:</label>
            <input type="date" id="cupom-validade" name="dataValidade" required>
          </div>
          <div class="form-group">
            <label for="cupom-usos">Número de Usos:</label>
            <input type="number" id="cupom-usos" name="quantidadeUso" min="1" placeholder="Ilimitado se vazio">
          </div>
          <div class="form-group">
            <label for="valorCompraMin">Valor Limite para Uso:</label>
            <input type="number" id="cupom-limite" name="valorLimite" min="0" step="0.01" placeholder="Sem limite se vazio">
          </div>
        </div>

        <div class="form-group">
          <label for="cupom-descricao">Descrição (opcional):</label>
          <textarea id="cupom-descricao" name="descricao" rows="2" placeholder="Ex: Cupom de Natal 2023"></textarea>
        </div>

        <div class="form-buttons">
          <button type="submit" class="btn-submit">Salvar Cupom</button>
          <button type="button" class="btn-cancel">Cancelar</button>
        </div>
      </form>
        </div>
      </section>
    </div>
  </main>



  <!-- FOOTER -->
  <footer class="footer admin-footer">
    <div class="container">
      <div class="footer-col">
        <h4>Painel Admin</h4>
        <ul>
          <li><a href="#" class="active">Dashboard</a></li>
          <li><a href="#">Configurações</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Relatórios</h4>
        <ul>
          <li><a href="#">Vendas</a></li>
          <li><a href="#">Produtos</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Ajuda</h4>
        <ul>
          <li><a href="https://docs.google.com/document/d/1toTRZPs8Em4QLgxcWydiwaK3nB6VKAdHX1r7TB9lZvI/edit?tab=t.eeplm5qqqjy0" target="_blank">Documentação</a></li>
          <li><a href="#">Suporte</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Voltar ao Site</h4>
        <a href="index.html" class="back-to-site">Site Principal <i class="fas fa-external-link-alt"></i></a>
      </div>
    </div>
  </footer>

  <!-- Créditos -->
  <div class="footer-creditos admin-creditos">
    <p>
      <a href="https://github.com/BexTheFrog" target="_blank">Beatriz</a> |
      <a href="https://www.behance.net/lucsvaa" target="_blank">Lucas</a> |
      <a href="https://github.com/Ryanslx" target="_blank">Ryan</a>
    </p>
  </div>

  <!-- JavaScript -->
  <script src="auth-check.js"></script>
  <script src="js/admin.js"></script>
  <script src="js/cupom.js"></script>
  <script src="js/produto.js"></script>
  <script src="js/categorias.js"></script>
  <script src="js/colecoes.js"></script>
  <script src="js/admin-novidades.js" type="module"></script>
</body>

</html>