carregarProdutos();
atualizarVisibilidade();

$(document).on('click', '#edita-produto', function() {
  const idProduto = $(this).data('id');

  $.ajax({
    url: 'buscarProdutoId.php',
    type: 'GET',
    dataType: 'json',
    data: { id: idProduto },
    success: function(res) {
      if (res.status === 'success') {
        console.log(res.produto);
        const produto = res.produto;

        // Preenche o form existente
        $('#produto-id').val(produto.id);
        $('#produto-nome').val(produto.nomeItem);
        $('#produto-descricao').val(produto.descItem);
        $('#produto-preco').val(produto.valorItem);
        $('#produto-estoque').val(produto.estoqueItem);
        $('#produto-categoria-form').val(produto.idCategoria);
        $('#produto-colecao').val(produto.idColecao);

        // Mostra o container que envolve o form
        $('.add-produto-form').show();

        // Scroll suave até o form
        $('#form-produto')[0].scrollIntoView({ behavior: 'smooth' });

      } else {
        alert('Erro: ' + res.message);
      }
    },
    error: function() {
      alert('Erro na requisição.');
    }
  });
});

// Apaga produto 

$(document).on('click', '.apaga-produto', function () {
  const id = $(this).data('id');

  if (confirm('Tem certeza que deseja remover este produto?')) {
    $.ajax({
      url: 'apagarProduto.php',
      type: 'POST',
      data: { id: id },
      dataType: 'json',
      success: function (res) {
        console.log('Resposta apagar:', res);
        alert(res.message);
        if (res.status === 'success') {
          carregarProdutos(); 
        }
      },
      error: function (xhr, error) {
          console.error('Erro ao excluir produto:', error);
          console.log('Resposta completa:', xhr.responseText);
          alert('Erro no servidor: ' + error);
      }
    });
  }
});

//Busca de produtos

$(document).ready(function () {

  $('#busca-produto-adm').on('input', function () {
    const termo = $(this).val().trim();

    $.ajax({
      url: 'buscarProdutos.php',
      type: 'GET',
      data: { termo: termo },
      dataType: 'json',
      success: function (res) {
        const container = $('#produtos-container');
        container.empty();

        if (res.status === 'success') {
          if (res.produtos.length === 0) {
            container.append('<p class="sem-produtos">Nenhum produto encontrado.</p>');
            return;
          }

          res.produtos.forEach(prod => {
            const card = `
              <div class="produto-card ${prod.statusProduto === 'desativado' ? 'produto-desativado' : ''}">
                <img src="uploads/produtos/${prod.imagem}" alt="${prod.nomeItem}">
                <div class="produto-info">
                  <h3>${prod.nomeItem}</h3>
                  <p class="produto-categoria">${prod.categoria}</p>
                  <p class="produto-preco">R$ ${parseFloat(prod.valorItem).toFixed(2)}</p>
                  <div class="produto-estoque">
                    <span>Estoque: ${prod.estoqueItem}</span>
                    <div class="estoque-actions">
                      <button class="btn-action small btn-estoque" data-id="${prod.id}" data-acao="incrementar"><i class="fas fa-plus"></i></button>
                      <button class="btn-action small btn-estoque" data-id="${prod.id}" data-acao="decrementar"><i class="fas fa-minus"></i></button>
                    </div>
                  </div>
                </div>
                <div class="produto-actions">
                  <button class="btn-action editar" id="edita-produto" data-id="${prod.id}"><i class="fas fa-edit"></i> Editar</button>
                  <button class="btn-action remover apaga-produto" data-id="${prod.id}"> Excluir </button>
                </div>
              </div>
            `;
            container.append(card);
          });
        } else {
          container.append('<p class="sem-produtos">Erro ao buscar produtos.</p>');
        }
      },
      error: function () {
        $('#produtos-container').html('<p class="sem-produtos">Erro na requisição.</p>');
      }
    });
  });
});

$('#form-produto').on('submit', function(e) {
  e.preventDefault();

  const form = this;
  const formData = new FormData(form);

  $.ajax({
    url: 'criarEditarProdutos.php',
    type: 'POST',
    data: formData,
    processData: false,  
    contentType: false,  
    dataType: 'json',
    success: function(res) {
      if (res.status === 'success') {
        alert(res.message);
        form.reset();
        carregarProdutos();  
      } else {
        alert('Erro: ' + res.message);
      }
    },
    error: function(xhr, status, error) {
      alert('Erro no servidor: ' + error);
    }
  });
});

$(document).on('click', '.btn-estoque', function() {
  const idProduto = $(this).data('id');
  const acao = $(this).data('acao'); // "incrementar" ou "decrementar"

  gerenciarEstoque(idProduto, acao);
});


$('#produto-categoria').on('change', function() {
  const idCategoria = $(this).val();

  $.ajax({
    url: 'listarProdutoFiltro.php',
    type: 'GET',
    dataType: 'json',
    data: { idCategoria }, 
    success: function(res) {
      const container = $('#produtos-container');
      container.empty();

      if (res.status === 'success') {
        if (res.produtos.length === 0) {
          container.append('<p class="sem-produtos">Nenhum produto encontrado para essa categoria.</p>');
          return;
        }

        res.produtos.forEach(prod => {
          const card = `
            <div class="produto-card ${prod.statusProduto === 'desativado' ? 'produto-desativado' : ''}">
              <img src="uploads/produtos/${prod.imagem}" alt="${prod.nomeItem}">
              <div class="produto-info">
                <h3>${prod.nomeItem}</h3>
                <p class="produto-categoria">${prod.categoria}</p>
                <p class="produto-preco">R$ ${parseFloat(prod.valorItem).toFixed(2)}</p>
                <div class="produto-estoque">
                  <span>Estoque: ${prod.estoqueItem}</span>
                  <div class="estoque-actions">
                    <button class="btn-action small btn-estoque" data-id="${prod.id}" data-acao="incrementar"><i class="fas fa-plus"></i></button>
                    <button class="btn-action small btn-estoque" data-id="${prod.id}" data-acao="decrementar"><i class="fas fa-minus"></i></button>
                  </div>
                </div>
              </div>
              <div class="produto-actions">
                <button class="btn-action editar" id="edita-produto" data-id="${prod.id}"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn-action remover apaga-produto" data-id="${prod.id}"> Excluir </button>
              </div>
            </div>
          `;
          container.append(card);
        });
      } else {
        alert('Erro: ' + res.message);
      }
    },
    error: function() {
      alert('Erro ao buscar produtos.');
    }
  });
});

function gerenciarEstoque(idProduto, acao) {
  $.ajax({
    url: 'gerenciarEstoque.php',
    type: 'POST',
    data: { idProduto: idProduto, acao: acao },
    dataType: 'json',
    success: function(res) {
      if (res.status === 'success') {
        //alert(res.message);
        const card = $(`.produto-card button.btn-estoque[data-id="${idProduto}"]`).closest('.produto-card');
        card.find('.produto-estoque > span').text('Estoque: ' + res.novoEstoque);
      } else {
        alert('Erro: ' + res.message);
      }
    },
    error: function(xhr, status, error) {
      alert('Erro ao atualizar estoque: ' + error);
    }
  });
}

function carregarProdutos() {
  $.ajax({
    url: 'listarProdutos.php',
    type: 'GET',
    dataType: 'json',
    success: function(res) {
      const container = $('#produtos-container');
      container.empty();

      if (res.status === 'success') {
        if (res.produtos.length === 0) {
          container.append('<p class="sem-produtos">Nenhum produto registrado ainda.</p>');
          return;
        }

        res.produtos.forEach(prod => {
          console.log('Imagem:', prod.imagem);
          const card = `
            <div class="produto-card ${prod.statusProduto === 'desativado' ? 'produto-desativado' : ''}">
              <img src="uploads/produtos/${prod.imagem}" alt="${prod.nomeItem}">
              <div class="produto-info">
                <h3>${prod.nomeItem}</h3>
                <p class="produto-categoria">${prod.categoria}</p>
                <p class="produto-preco">R$ ${parseFloat(prod.valorItem).toFixed(2)}</p>
                <div class="produto-estoque">
                  <span>Estoque: ${prod.estoqueItem}</span>
                  <div class="estoque-actions">
                    <button class="btn-action small btn-estoque" data-id="${prod.id}" data-acao="incrementar"><i class="fas fa-plus"></i></button>
                    <button class="btn-action small btn-estoque" data-id="${prod.id}" data-acao="decrementar"><i class="fas fa-minus"></i></button>
                  </div>
                </div>
              </div>
              <div class="produto-actions">
                <button class="btn-action editar" id="edita-produto" data-id="${prod.id}"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn-action remover apaga-produto" data-id="${prod.id}"> Excluir </button>
              </div>
            </div>
          `;
          container.append(card);
        });
      } else {
        alert('Erro: ' + res.message);
      }
    },
    error: function() {
      alert('Erro ao buscar produtos.');
    }
  });
}


function atualizarVisibilidade() {
  if ($('#produto-tamanho-unico').is(':checked')) {
    $('#estoque-tamanhos-container').hide();
    $('#msg-erro-estoque').hide();
    // Opcional: zerar os inputs P, M, G ao esconder
    $('#estoque-p, #estoque-m, #estoque-g').val(0);
  } else {
    $('#estoque-tamanhos-container').show();
    validarSoma();
  }
}


function validarSoma() {
  const estoqueTotal = Number($('#produto-estoque').val()) || 0;
  const soma = (Number($('#estoque-p').val()) || 0) + (Number($('#estoque-m').val()) || 0) + (Number($('#estoque-g').val()) || 0);

  if (soma > estoqueTotal) {
    $('#msg-erro-estoque').show();
    return false;
  } else {
    $('#msg-erro-estoque').hide();
    return true;
  }
}


$(document).on('click', '#edita-produto', function() {
  const idProduto = $(this).data('id');

  $.ajax({
    url: 'buscarProdutoId.php',
    type: 'GET',
    dataType: 'json',
    data: { id: idProduto },
    success: function(res) {
      if (res.status === 'success') {
        const produto = res.produto;

        $('#produto-id').val(produto.id);
        $('#produto-nome').val(produto.nomeItem);
        $('#produto-descricao').val(produto.descItem);
        $('#produto-preco').val(produto.valorItem);
        $('#produto-estoque').val(produto.estoqueItem);
        $('#produto-categoria-form').val(produto.idCategoria);
        $('#produto-colecao').val(produto.idColecao);

        $('#produto-tamanho-unico').prop('checked', produto.tamanhoUnico);

        $('#estoque-p').val(produto.estoqueP || 0);
        $('#estoque-m').val(produto.estoqueM || 0);
        $('#estoque-g').val(produto.estoqueG || 0);

        atualizarVisibilidade();

        $('.add-produto-form').show();
        $('#form-produto')[0].scrollIntoView({ behavior: 'smooth' });

      } else {
        alert('Erro: ' + res.message);
      }
    },
    error: function() {
      alert('Erro na requisição.');
    }
  });
});

$('#produto-tamanho-unico').on('change', atualizarVisibilidade);
$('#estoque-p, #estoque-m, #estoque-g, #produto-estoque').on('input', function() {
  if (!$('#produto-tamanho-unico').is(':checked')) {
    validarSoma();
  }
});