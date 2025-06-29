document.addEventListener('DOMContentLoaded', function() {
  // Menu Hamburguer
  const hamburger = document.getElementById('hamburger-menu');
  const navCenter = document.getElementById('nav-center');
  const navRight = document.getElementById('nav-right');
  
  // Verifica se os elementos existem antes de adicionar eventos
  if (hamburger && navCenter && navRight) {
    hamburger.addEventListener('click', function() {
      this.classList.toggle('active');
      navCenter.classList.toggle('open');
      navRight.classList.toggle('open');
      document.body.classList.toggle('menu-open');
      
      // Adiciona/remove aria-expanded para acessibilidade
      const isExpanded = this.classList.contains('active');
      this.setAttribute('aria-expanded', isExpanded);
    });

    // Fechar menu ao clicar em um link ou fora do menu
    const closeMenu = () => {
      hamburger.classList.remove('active');
      navCenter.classList.remove('open');
      navRight.classList.remove('open');
      document.body.classList.remove('menu-open');
      hamburger.setAttribute('aria-expanded', 'false');
    };

    // Fecha ao clicar em links de navegação
    document.querySelectorAll('.nav-links a, .login-btn, .cart-btn').forEach(link => {
      link.addEventListener('click', closeMenu);
    });

    // Fecha ao clicar fora do menu (opcional)
    document.addEventListener('click', (event) => {
      const isClickInsideNav = navCenter.contains(event.target) || 
                             navRight.contains(event.target) || 
                             hamburger.contains(event.target);
      
      if (!isClickInsideNav && hamburger.classList.contains('active')) {
        closeMenu();
      }
    });

    // Adiciona tecla ESC para fechar o menu (melhoria de acessibilidade)
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && hamburger.classList.contains('active')) {
        closeMenu();
        hamburger.focus(); // Retorna o foco para o botão do menu
      }
    });
  }
});    
    
    // Função para trocar imagem principal
    function changeImage(element, newImage) {
      // Remove classe 'active' de todas as miniaturas
      document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
      });
      
      // Adiciona classe 'active' na miniatura clicada
      element.classList.add('active');
      
      // Atualiza imagem principal
      document.getElementById('mainImage').src = newImage;
    }
    
    // Função para selecionar tamanho
    document.querySelectorAll('.size-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
      });
    });
    
    // Função para selecionar cor
    document.querySelectorAll('.color-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
      });
    });
    
    // Função para alterar quantidade
    function changeQuantity(change) {
      const input = document.getElementById('quantity');
      let value = parseInt(input.value) + change;
      if (value < 1) value = 1;
      input.value = value;
    }