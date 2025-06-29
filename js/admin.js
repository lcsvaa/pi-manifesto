document.addEventListener('DOMContentLoaded', function() {
  // Verifica a aba ativa a partir da URL
  const urlParams = new URLSearchParams(window.location.search);
  const activeTab = urlParams.get('tab') || 'pedidos'; // Default para 'pedidos'
  
  // Ativa a aba correta no carregamento
  activateTab(activeTab);

  // Alternar entre categorias
  const categoryTabs = document.querySelectorAll('.category-tab');
  
  categoryTabs.forEach(tab => {
    tab.addEventListener('click', function(e) {
      e.preventDefault();
      
      const category = this.getAttribute('data-category');
      // Atualiza a URL sem recarregar a página
      history.pushState(null, '', `admin.php?tab=${category}`);
      
      // Ativa a aba selecionada
      activateTab(category);
    });
  });

  // Função para ativar a aba selecionada
  function activateTab(category) {
    // Remove active class from all tabs and sections
    document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
    
    // Add active class to clicked tab and corresponding section
    const activeTab = document.querySelector(`.category-tab[data-category="${category}"]`);
    if (activeTab) {
      activeTab.classList.add('active');
      document.getElementById(`${category}-section`).classList.add('active');
    } else {
      // Fallback caso a aba não exista
      document.querySelector('.category-tab[data-category="pedidos"]').classList.add('active');
      document.getElementById('pedidos-section').classcons.add('active');
    }
  }

  // Alternar entre abas de conteúdo
  const contentTabs = document.querySelectorAll('.content-tab');
  
  contentTabs.forEach(tab => {
    tab.addEventListener('click', function() {
      // Remove active class from all tabs and panels
      document.querySelectorAll('.content-tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.content-panel').forEach(p => p.classList.remove('active'));
      
      // Add active class to clicked tab and corresponding panel
      this.classList.add('active');
      const content = this.getAttribute('data-content');
      document.getElementById(`${content}-panel`).classList.add('active');
    });
  });
  
  // Formulário de adicionar produto
  const addProdutoBtn = document.querySelector('.add-produto-btn');
  const addProdutoForm = document.querySelector('.add-produto-form');
  
  if (addProdutoBtn && addProdutoForm) {
    addProdutoBtn.addEventListener('click', function() {
      addProdutoForm.style.display = 'block';
      this.style.display = 'none';
      addProdutoForm.scrollIntoView({ behavior: 'smooth' });
    });
    
    const cancelProdutoBtn = addProdutoForm.querySelector('.btn-cancel');
    cancelProdutoBtn.addEventListener('click', function() {
      addProdutoForm.style.display = 'none';
      addProdutoBtn.style.display = 'inline-flex';
    });
  }
  
  // Formulário de adicionar cupom
  const addCupomBtn = document.querySelector('.add-cupom-btn');
  const addCupomForm = document.querySelector('.add-cupom-form');
  
  if (addCupomBtn && addCupomForm) {
    addCupomBtn.addEventListener('click', function() {
      addCupomForm.style.display = 'block';
      this.style.display = 'none';
      addCupomForm.scrollIntoView({ behavior: 'smooth' });
    });
    
    const cancelCupomBtn = addCupomForm.querySelector('.btn-cancel');
    cancelCupomBtn.addEventListener('click', function() {
      addCupomForm.style.display = 'none';
      addCupomBtn.style.display = 'inline-flex';
    });
  }
  
  // Validação de formulários
  const forms = document.querySelectorAll('form');
  
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      const submitButton = this.querySelector('button[type="submit"]');
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
      }
      
      // Aqui você pode adicionar a lógica para enviar os dados via AJAX
      // Exemplo:
      /*
      fetch('processa_form.php', {
        method: 'POST',
        body: new FormData(this)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Operação realizada com sucesso!');
          this.reset();
          if (this.closest('.add-produto-form')) {
            addProdutoForm.style.display = 'none';
            addProdutoBtn.style.display = 'inline-flex';
          }
          if (this.closest('.add-cupom-form')) {
            addCupomForm.style.display = 'none';
            addCupomBtn.style.display = 'inline-flex';
          }
          // Recarregar dados se necessário
        } else {
          alert('Erro: ' + data.message);
        }
      })
      .catch(error => {
        alert('Erro na requisição: ' + error);
      })
      .finally(() => {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = 'Salvar';
        }
      });
      */
      
      // Temporário - apenas para demonstração
      setTimeout(() => {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.innerHTML = 'Salvar';
        }
        alert('Formulário enviado com sucesso!');
        this.reset();
        
        if (this.closest('.add-produto-form')) {
          addProdutoForm.style.display = 'none';
          addProdutoBtn.style.display = 'inline-flex';
        }
        
        if (this.closest('.add-cupom-form')) {
          addCupomForm.style.display = 'none';
          addCupomBtn.style.display = 'inline-flex';
        }
      }, 1500);
      
      e.preventDefault();
    });
  });
  
  // Ações de estoque
  document.querySelectorAll('.estoque-actions .fa-plus').forEach(btn => {
    btn.addEventListener('click', function() {
      const stockElement = this.closest('.produto-estoque').querySelector('span');
      let stock = parseInt(stockElement.textContent.replace('Estoque: ', ''));
      stockElement.textContent = `Estoque: ${stock + 1}`;
      
      // Aqui você pode adicionar uma chamada AJAX para atualizar no banco de dados
      // Exemplo:
      // const productId = this.closest('.produto-card').dataset.id;
      // fetch(`atualiza_estoque.php?id=${productId}&action=increment`, { method: 'POST' });
    });
  });
  
  document.querySelectorAll('.estoque-actions .fa-minus').forEach(btn => {
    btn.addEventListener('click', function() {
      const stockElement = this.closest('.produto-estoque').querySelector('span');
      let stock = parseInt(stockElement.textContent.replace('Estoque: ', ''));
      if (stock > 0) {
        stockElement.textContent = `Estoque: ${stock - 1}`;
        
        // Aqui você pode adicionar uma chamada AJAX para atualizar no banco de dados
        // Exemplo:
        // const productId = this.closest('.produto-card').dataset.id;
        // fetch(`atualiza_estoque.php?id=${productId}&action=decrement`, { method: 'POST' });
      }
    });
  });

  // Filtro de clientes (se existir na página)
  const clientSearch = document.getElementById('client-search');
  const clientStatusFilter = document.getElementById('client-status-filter');
  
  if (clientSearch && clientStatusFilter) {
    clientSearch.addEventListener('input', filterClients);
    clientStatusFilter.addEventListener('change', filterClients);
    
    function filterClients() {
      const status = clientStatusFilter.value;
      const searchTerm = clientSearch.value.toLowerCase();
      const rows = document.querySelectorAll('.clientes-table tbody tr');
      
      rows.forEach(row => {
        const rowStatus = row.querySelector('.status-badge').classList.contains('ativo') ? 'ativo' : 'desativado';
        const rowType = row.querySelector('td[data-label="Tipo"]').textContent.toLowerCase();
        const rowText = row.textContent.toLowerCase();
        
        const statusMatch = status === 'all' || 
                          (status === 'admin' && rowType.includes('admin')) || 
                          (status !== 'admin' && rowStatus === status);
        const searchMatch = searchTerm === '' || rowText.includes(searchTerm);
        
        if (statusMatch && searchMatch) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }
  }
});

// Manipula o botão voltar/avançar do navegador
window.addEventListener('popstate', function() {
  const urlParams = new URLSearchParams(window.location.search);
  const activeTab = urlParams.get('tab') || 'pedidos';
  document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
  
  const activeTabElement = document.querySelector(`.category-tab[data-category="${activeTab}"]`);
  if (activeTabElement) {
    activeTabElement.classList.add('active');
    document.getElementById(`${activeTab}-section`).classList.add('active');
  }
});