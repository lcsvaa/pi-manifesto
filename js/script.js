// Menu Mobile
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger-menu');
    const mobileMenu = document.getElementById('mobile-menu');
    
    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });
    
    // Fechar menu ao clicar em um link
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
  // Menu Hamburguer
  const hamburger = document.getElementById('hamburger-menu');
  const navCenter = document.getElementById('nav-center');
  const navRight = document.getElementById('nav-right');
  
  hamburger.addEventListener('click', function() {
    this.classList.toggle('active');
    navCenter.classList.toggle('open');
    navRight.classList.toggle('open');
    document.body.classList.toggle('menu-open');
  });

  // Fechar menu ao clicar em um link
  document.querySelectorAll('.nav-links a, .login-btn, .cart-btn').forEach(link => {
    link.addEventListener('click', () => {
      hamburger.classList.remove('active');
      navCenter.classList.remove('open');
      navRight.classList.remove('open');
      document.body.classList.remove('menu-open');
    });
  });

  // Carrossel
  const prevButton = document.getElementById('prev-button');
  const nextButton = document.getElementById('next-button');
  const carousel = document.getElementById('carousel');
  const carouselItems = document.querySelectorAll('.carousel-item');
  let currentIndex = 0;

  function updateCarousel() {
    const carouselWidth = carousel.offsetWidth;
    carousel.style.transform = `translateX(-${carouselWidth * currentIndex}px)`;
  }

  function moveToPrevSlide() {
    currentIndex = (currentIndex === 0) ? carouselItems.length - 1 : currentIndex - 1;
    updateCarousel();
  }

  function moveToNextSlide() {
    currentIndex = (currentIndex === carouselItems.length - 1) ? 0 : currentIndex + 1;
    updateCarousel();
  }

  prevButton.addEventListener('click', moveToPrevSlide);
  nextButton.addEventListener('click', moveToNextSlide);

  // Redimensionamento
  window.addEventListener('resize', updateCarousel);
  
  // Inicializar
  updateCarousel();
});

    // Função para adicionar produto ao carrinho
    function adicionarAoCarrinho(nome, preco, imagem) {
      // Recupera o carrinho do localStorage ou cria um novo array vazio
      let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
      
      // Adiciona o novo produto ao carrinho
      carrinho.push({
        nome: nome,
        preco: preco,
        imagem: imagem
      });
      
      // Salva o carrinho atualizado no localStorage
      localStorage.setItem('carrinho', JSON.stringify(carrinho));
      
      // Mostra mensagem de sucesso
      alert(`${nome} foi adicionado ao carrinho!`);
      
      // Atualiza o contador do carrinho na navbar
      atualizarContadorCarrinho();
    }
    
    // Função para atualizar o contador do carrinho
    function atualizarContadorCarrinho() {
      let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
      let contador = document.querySelector('.cart-btn .contador');
      
      if (!contador) {
        contador = document.createElement('span');
        contador.className = 'contador';
        document.querySelector('.cart-btn').appendChild(contador);
      }
      
      contador.textContent = carrinho.length > 0 ? carrinho.length : '';
    }
    
    // Atualiza o contador quando a página carrega
    document.addEventListener('DOMContentLoaded', atualizarContadorCarrinho);