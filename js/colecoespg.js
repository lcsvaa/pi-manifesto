// Menu Hamburguer
const hamburgerMenu = document.getElementById('hamburger-menu');
const navCenter = document.getElementById('nav-center');
const navRight = document.getElementById('nav-right');
const body = document.body;

hamburgerMenu.addEventListener('click', () => {
  hamburgerMenu.classList.toggle('active');
  navCenter.classList.toggle('open');
  navRight.classList.toggle('open');
  body.classList.toggle('menu-open');

  if (hamburgerMenu.classList.contains('active')) {
    document.querySelectorAll('.hamburger-line').forEach((line, index) => {
      if (index === 0) line.style.transform = 'translateY(8px) rotate(45deg)';
      if (index === 1) line.style.opacity = '0';
      if (index === 2) line.style.transform = 'translateY(-8px) rotate(-45deg)';
    });
  } else {
    document.querySelectorAll('.hamburger-line').forEach(line => {
      line.style.transform = '';
      line.style.opacity = '';
    });
  }
});

// Filtro de categorias
const categoryBtns = document.querySelectorAll('.category-btn');
const productCards = document.querySelectorAll('.produto-card');

categoryBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    // Remove active class from all buttons
    categoryBtns.forEach(b => b.classList.remove('active'));
    // Add active class to clicked button
    btn.classList.add('active');

    const category = btn.dataset.category;

    // Filtrar produtos pela categoria selecionada
    productCards.forEach(card => {
      if (category === 'todos' || card.dataset.category === category) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  });
});

// Botão Voltar ao Topo
const backToTopButton = document.getElementById('back-to-top');

window.addEventListener('scroll', () => {
  if (window.pageYOffset > 300) {
    backToTopButton.style.display = 'flex';
  } else {
    backToTopButton.style.display = 'none';
  }
});

backToTopButton.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});
