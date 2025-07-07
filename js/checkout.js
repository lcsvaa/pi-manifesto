// Menu Hamburguer (mesmo do cart.js)
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

// Restrições no carregamento da página
window.addEventListener('DOMContentLoaded', () => {
    // Aviso geral
    alert('Aceitamos apenas pagamento via PIX e entrega padrão.');

    // Desativar outras abas de pagamento
    document.querySelectorAll('.payment-tab').forEach(tab => {
        const isPix = tab.dataset.tab === 'pix';
        if (!isPix) {
            tab.setAttribute('disabled', 'disabled');
            tab.classList.add('disabled');
        } else {
            tab.classList.add('active');
        }
    });

    // Ativar somente conteúdo PIX
    document.querySelectorAll('.payment-content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById('pix-tab').classList.add('active');

    // Desativar entrega expressa
    const expressRadio = document.querySelector('input[name="shipping"][value="express"]');
    if (expressRadio) {
        expressRadio.disabled = true;
        expressRadio.closest('.shipping-option').classList.add('disabled');
    }
});

// Máscaras para campos de formulário
document.getElementById('cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/^(\d{5})(\d)/, '$1-$2');
    e.target.value = value;
});

document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
    value = value.replace(/(\d)(\d{4})$/, '$1-$2');
    e.target.value = value;
});

document.getElementById('card-number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
    e.target.value = value;
});

document.getElementById('card-expiry').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/^(\d{2})(\d)/, '$1/$2');
    e.target.value = value;
});

let possuiEndereco = false; 

window.addEventListener('DOMContentLoaded', () => {
  fetch('buscar_enderecos_usuario.php')
    .then(res => res.json())
    .then(data => {
      const formEndereco = document.getElementById('novo-endereco-form');
      const botaoAlterarEndereco = document.querySelector('form[action="profile.php"]');

      // Parágrafo de aviso
      let avisoEndereco = document.getElementById('aviso-endereco');
      if (!avisoEndereco) {
        avisoEndereco = document.createElement('p');
        avisoEndereco.id = 'aviso-endereco';
        avisoEndereco.style.color = '#e91e63';
        avisoEndereco.style.margin = '10px 0';
        botaoAlterarEndereco.parentNode.insertBefore(avisoEndereco, botaoAlterarEndereco);
      }

      if (data.status === 'ok' && data.enderecos.length > 0) {
        const d = data.enderecos[0];
        document.getElementById('nome').value = d.nomeUser || '';
        document.getElementById('email').value = d.email || '';
        document.getElementById('telefone').value = d.telefone || '';
        document.getElementById('cep').value = d.cep || '';
        document.getElementById('endereco').value = d.endereco || '';
        document.getElementById('numero').value = d.numero || '';
        document.getElementById('complemento').value = d.complemento || '';
        document.getElementById('bairro').value = d.bairro || '';
        document.getElementById('cidade').value = d.cidade || '';

        formEndereco.style.display = 'block';
        avisoEndereco.textContent = '';
        possuiEndereco = true; // 
      } else {
        formEndereco.style.display = 'none';
        avisoEndereco.textContent = 'Você não tem endereços cadastrados. Por favor, cadastre no perfil.';
        possuiEndereco = false; 
      }
    })
    .catch(err => {
      console.error('Erro ao carregar dados do perfil:', err);
    });
});

document.getElementById('confirm-order').addEventListener('click', async function(e) {
  e.preventDefault();

  if (!possuiEndereco) {
    alert('Você precisa cadastrar um endereço antes de finalizar a compra.');
    return;
  }

  const formaPagamento = document.querySelector('.payment-tab.active')?.dataset.tab || 'indefinido';
  const shippingInput = document.querySelector('input[name="shipping"]:checked');
  const shipping = shippingInput ? shippingInput.value : 'standard';

  const dadosPedido = {
    formaPagamento,
    shipping
  };

  try {
    const res = await fetch('finalizarCompra.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(dadosPedido)
    });

    const data = await res.json();

    if (data.status === 'ok') {
      alert(data.msg);
      // window.location.href = 'confirmacao.html';
    } else {
      alert('Erro: ' + data.msg);
      if (data.error) console.error('Erro detalhado:', data.error);
    }

  } catch (error) {
    alert('Erro ao enviar pedido. Tente novamente.');
    console.error(error);
  }
});