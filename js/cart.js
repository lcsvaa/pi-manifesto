// === MENU HAMBÚRGUER ===
const hamburgerMenu = document.getElementById('hamburger-menu');
const navCenter = document.getElementById('nav-center');
const navRight = document.getElementById('nav-right');
const body = document.body;

if (hamburgerMenu && navCenter && navRight) {
  hamburgerMenu.addEventListener('click', () => {
    hamburgerMenu.classList.toggle('active');
    navCenter.classList.toggle('open');
    navRight.classList.toggle('open');
    body.classList.toggle('menu-open');

    document.querySelectorAll('.hamburger-line').forEach((line, index) => {
      if (hamburgerMenu.classList.contains('active')) {
        if (index === 0) line.style.transform = 'translateY(8px) rotate(45deg)';
        if (index === 1) line.style.opacity = '0';
        if (index === 2) line.style.transform = 'translateY(-8px) rotate(-45deg)';
      } else {
        line.style.transform = '';
        line.style.opacity = '';
      }
    });
  });
}

// === ATUALIZAR QUANTIDADE ===
document.querySelectorAll('.quantity-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const input = btn.parentElement.querySelector('.quantity-input');
    if (!input) return;

    let value = parseInt(input.value);
    if (isNaN(value)) value = 1;

    if (btn.classList.contains('minus')) {
      value = Math.max(1, value - 1);
    } else {
      value++;
    }

    input.value = value;

    const key = btn.closest('.cart-item')?.dataset.key;
    if (!key) return;

    fetch(`atualizarCarrinho.php?key=${encodeURIComponent(key)}&quantidade=${value}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          updateCartTotal();
        }
      });
  });
});

// === REMOVER ITEM ===
document.querySelectorAll('.remove-item').forEach(btn => {
  btn.addEventListener('click', () => {
    const item = btn.closest('.cart-item');
    const key = item?.dataset.key;
    if (!key) return;

    fetch(`removerCarrinho.php?key=${encodeURIComponent(key)}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          item.remove();
          updateCartTotal();
          if (document.querySelectorAll('.cart-item').length === 0) {
            showEmptyCart();
          }
        }
      });
  });
});

// === LIMPAR CARRINHO ===
const clearBtn = document.getElementById('clear-cart');
if (clearBtn) {
  clearBtn.addEventListener('click', () => {
    if (confirm('Tem certeza que deseja limpar o carrinho?')) {
      fetch('limparCarrinho.php')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'ok') {
            location.reload();
          }
        });
    }
  });
}

// === APLICAR CUPOM ===
const couponBtn = document.querySelector('.coupon-btn');
if (couponBtn) {
  couponBtn.addEventListener('click', () => {
    const input = document.querySelector('.coupon-input');
    const couponCode = input?.value.trim();
    if (!couponCode) {
      alert('Por favor, insira um código de cupom');
      return;
    }

    fetch(`validarCupom.php?codigo=${encodeURIComponent(couponCode)}`)
      .then(res => res.json())
      .then(data => {
        alert(data.msg);
        if (data.status === 'ok') {
          updateCartTotal();
        }
      });
  });
}

// === FINALIZAR COMPRA ===
const checkoutBtn = document.querySelector('.checkout-btn');
if (checkoutBtn) {
  checkoutBtn.addEventListener('click', () => {
    const itens = document.querySelectorAll('.cart-item');
    if (itens.length === 0) {
      alert('Seu carrinho está vazio. Adicione itens antes de finalizar a compra.');
      return;
    }
    window.location.href = 'checkout.php';
  });
}

// === EXIBIR CARRINHO VAZIO ===
function showEmptyCart() {
  const cartItems = document.querySelector('.cart-items');
  if (!cartItems) return;

  cartItems.innerHTML = `
    <h2 class="cart-title">Seus Itens</h2>
    <div class="cart-empty">
      <i class="fas fa-shopping-cart"></i>
      <h3>Seu carrinho está vazio</h3>
      <p>Adicione itens para começar a comprar</p>
      <a href="index.php" class="btn-continue">Continuar Comprando</a>
    </div>
  `;

  document.querySelector('.summary-row:nth-child(1) span:last-child').textContent = 'R$ 0,00';
  document.querySelector('.summary-row:nth-child(2) span:last-child').textContent = 'R$ 0,00';
  document.querySelector('.summary-row:nth-child(3) span:last-child').textContent = '- R$ 0,00';
  document.querySelector('.summary-total span:last-child').textContent = 'R$ 0,00';

  const checkout = document.querySelector('.checkout-btn');
  if (checkout) checkout.disabled = true;
}

// === ATUALIZAR TOTAL ===
function updateCartTotal() {
  fetch('resumoCarrinho.php')
    .then(res => res.json())
    .then(data => {
      document.querySelector('.summary-row:nth-child(1) span:last-child').textContent = `R$ ${data.subtotal}`;
      document.querySelector('.summary-row:nth-child(2) span:last-child').textContent = `R$ ${data.frete}`;
      document.querySelector('.summary-row:nth-child(3) span:last-child').textContent = `- R$ ${data.desconto}`;
      document.querySelector('.summary-total span:last-child').textContent = `R$ ${data.total}`;
    });
}

// === INICIALIZAÇÃO ===
updateCartTotal();
