document.addEventListener('DOMContentLoaded', function() {
  
    btnAdd?.addEventListener("click", () => {
      const id = new URLSearchParams(window.location.search).get("id");
      const nome = document.querySelector(".product-title").textContent;
      const preco = parseFloat(
        document.querySelector(".product-price").textContent.replace("R$ ", "").replace(",", ".")
      );
      const qtd = parseInt(document.getElementById("quantity").value);
      const tamanho = document.querySelector(".size-btn.selected")?.textContent || "Único";
      const imagem = document.getElementById("mainImage").getAttribute("src").split("/").pop();

      const produto = {
        id,
        nome,
        preco,
        qtd,
        tamanho,
        imagem
      };

      fetch("adicionarCarrinho.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(produto)
      })
        .then(res => res.json())
        .then(data => {
          if (data.status === "ok") {
            alert("Produto adicionado ao carrinho!");
          } else {
            alert("Erro ao adicionar ao carrinho.");
          }
        });
    });
  });
  
  // Menu Hamburguer
  const hamburger = document.getElementById('hamburger-menu');
  const navCenter = document.getElementById('nav-center');
  const navRight = document.getElementById('nav-right');
  
  if (hamburger && navCenter && navRight) {
    hamburger.addEventListener('click', function() {
      this.classList.toggle('active');
      navCenter.classList.toggle('open');
      navRight.classList.toggle('open');
      document.body.classList.toggle('menu-open');
      
      const isExpanded = this.classList.contains('active');
      this.setAttribute('aria-expanded', isExpanded);
    });

    const closeMenu = () => {
      hamburger.classList.remove('active');
      navCenter.classList.remove('open');
      navRight.classList.remove('open');
      document.body.classList.remove('menu-open');
      hamburger.setAttribute('aria-expanded', 'false');
    };

    document.querySelectorAll('.nav-links a, .login-btn, .cart-btn').forEach(link => {
      link.addEventListener('click', closeMenu);
    });

    document.addEventListener('click', (event) => {
      const isClickInsideNav = navCenter.contains(event.target) || 
                             navRight.contains(event.target) || 
                             hamburger.contains(event.target);
      
      if (!isClickInsideNav && hamburger.classList.contains('active')) {
        closeMenu();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && hamburger.classList.contains('active')) {
        closeMenu();
        hamburger.focus();
      }
    });
  }

  // Função para trocar imagem principal (deixa global para usar inline)
  window.changeImage = function(element, newImage) {
    document.querySelectorAll('.thumbnail').forEach(thumb => {
      thumb.classList.remove('active');
    });
    element.classList.add('active');
    document.getElementById('mainImage').src = newImage;
  }

  // Função para alterar quantidade (deixa global para usar inline)
  window.changeQuantity = function(change) {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) + change;
    if (value < 1) value = 1;
    const max = parseInt(input.max) || 1000;
    if (value > max) value = max;
    input.value = value;
  }

  // Selecionar tamanho
  document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
      this.classList.add('selected');
    });
  });

  // Selecionar cor (se tiver)
  document.querySelectorAll('.color-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('selected'));
      this.classList.add('selected');
    });
  });