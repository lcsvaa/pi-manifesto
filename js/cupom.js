$(document).ready(function() {
  function formatarData(dataStr) {
    if (!dataStr) return '';
    const [ano, mes, dia] = dataStr.split("-");
    return `${dia}/${mes}/${ano}`;
  }

  // Gera HTML do card de cupom com dados
  function gerarCupomCard(cupom) {
    return `
      <div class="cupom-card" data-id="${cupom.idCupom}">
        <div class="cupom-header">
          <span class="cupom-codigo">${cupom.codigo}</span>
          <span class="cupom-status ${cupom.statusCupom === 'ativo' ? 'active' : 'inactive'}">
            ${cupom.statusCupom === 'ativo' ? 'Ativo' : 'Desativado'}
          </span>
        </div>
        <div class="cupom-info">
          <p><strong>Desconto:</strong> ${cupom.tipoCupom === 'porcentagem' ? cupom.porcentagemDesconto + '%' : 'R$ ' + parseFloat(cupom.porcentagemDesconto).toFixed(2)}</p>
          <p><strong>Validade:</strong> ${formatarData(cupom.dataValidade)}</p>
          <p><strong>Usos restantes:</strong> ${cupom.quantidadeUso - cupom.utilizados}</p>
          <p><strong>Valor limite para uso:</strong> R$ ${parseFloat(cupom.valorCompraMin).toFixed(2)}</p>
          <p><strong>Tipo:</strong> ${cupom.tipoCupom === 'porcentagem' ? 'Porcentagem' : 'Valor Fixo'}</p>
          <p><strong>Descrição:</strong> ${cupom.descricaoCupom || ''}</p>
        </div>
        <div class="cupom-actions">
          <button class="btn-action editar" id="edita-cupom" data-id="${cupom.idCupom}"><i class="fas fa-edit"></i> Editar</button>
          <button class="btn-action trocar-status" data-id="${cupom.idCupom}">
            <i class="fas fa-toggle-${cupom.statusCupom === 'ativo' ? 'off' : 'on'}"></i> 
            ${cupom.statusCupom === 'ativo' ? 'Desativar' : 'Ativar'}
          </button>
        </div>
      </div>
    `;
  }

  // Carregar e listar cupons
  function carregarCupons() {
    $.ajax({
      url: 'listarCupons.php',
      method: 'GET',
      dataType: 'json',
      success: function (dados) {
        const container = $('#lista-cupons');
        container.empty();
        dados.forEach(cupom => container.append(gerarCupomCard(cupom)));
      },
      error: function() {
        alert('Erro ao carregar os cupons.');
      }
    });
  }

  // Resetar form de cupom
  function resetForm() {
    $('#cupomForm')[0].reset();
    $('#cupom-id').val('');
  }

  // Preencher formulário para edição
  function preencherFormulario(cupom) {
    $('#cupom-id').val(cupom.idCupom);
    $('#cupom-codigo').val(cupom.codigo);
    $('#cupom-desconto').val(cupom.porcentagemDesconto);
    $('#cupom-tipo').val(cupom.tipoCupom);
    $('#cupom-validade').val(cupom.dataValidade);
    $('#cupom-usos').val(cupom.quantidadeUso);
    $('#cupom-limite').val(cupom.valorCompraMin);
    $('#cupom-descricao').val(cupom.descricaoCupom);
  }

  // Envio do formulário criar/editar
  $('#cupomForm').on('submit', function(e) {
  e.preventDefault();

  const dados = {
    id: $('#cupom-id').val(),
    codigo: $('#cupom-codigo').val(),
    porcentagemDesconto: parseFloat($('#cupom-desconto').val()), // nome correto esperado no PHP
    tipoDesconto: $('#cupom-tipo').val(),                         // nome correto esperado no PHP
    dataValidade: $('#cupom-validade').val(),
    quantidadeUso: $('#cupom-usos').val(),
    valorCompraMin: parseFloat($('#cupom-limite').val()),
    descricao: $('#cupom-descricao').val()
  };

  $.ajax({
    url: 'criarEditarCupom.php',
    type: 'POST',
    data: dados,
    dataType: 'json',
    success: function(res) {
      if(res.status === 'success') {
        alert(res.message);
        resetForm();
        carregarCupons();
        $('.add-cupom-form').hide();
        $('.add-cupom-btn').show();
      } else {
        alert('Erro: ' + res.message);
      }
    },
    error: function() {
      alert('Erro no servidor ao salvar cupom.');
    }
  });
});

  // Abrir formulário para edição ao clicar editar
  $(document).on('click', '#edita-cupom', function() {
    const id = $(this).data('id');
    $.ajax({
      url: 'obterCupom.php',
      method: 'GET',
      data: { idCupom: id },
      dataType: 'json',
      success: function(cupom) {
        preencherFormulario(cupom);
        $('.add-cupom-form').show();
        $('.add-cupom-btn').hide();
        $('#cupomForm')[0].scrollIntoView({ behavior: 'smooth' });
      },
      error: function() {
        alert('Erro ao obter dados do cupom para edição.');
      }
    });
  });

  // Trocar status (ativar/desativar)
  $(document).on('click', '.btn-action.trocar-status', function() {
  const id = $(this).data('id');
  $.ajax({
    url: 'trocarStatusCupom.php',
    method: 'POST',
    data: { idCupom: id },
    dataType: 'json',
    success: function(res) {
      if(res.status === 'success') {
        alert(res.message);
        carregarCupons(); // <- recarrega todos os cupons com status atualizado
      } else {
        alert('Erro: ' + res.message);
      }
    },
    error: function() {
      alert('Erro ao trocar status do cupom.');
    }
  });
});


  // Inicializa listagem ao carregar página
  carregarCupons();

  // Cancelar form (esconder e limpar)
  $('.btn-cancel').on('click', function() {
    resetForm();
    $('.add-cupom-form').hide();
    $('.add-cupom-btn').show();
  });
});
