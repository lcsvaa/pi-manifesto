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

// Máscara para campos de formulário
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


window.addEventListener('DOMContentLoaded', () => {
  fetch('getEnderecoUsuario.php')
    .then(res => res.json())
    .then(data => {
      if (data.status === 'ok') {
        const d = data.dados;
        document.getElementById('nome').value = d.nome || '';
        document.getElementById('email').value = d.email || '';
        document.getElementById('telefone').value = d.telefone || '';
        document.getElementById('cep').value = d.cep || '';
        document.getElementById('endereco').value = d.endereco || '';
        document.getElementById('numero').value = d.numero || '';
        document.getElementById('complemento').value = d.complemento || '';
        document.getElementById('bairro').value = d.bairro || '';
        document.getElementById('cidade').value = d.cidade || '';
        document.getElementById('estado').value = d.estado || '';
      } else if (data.status === 'vazio') {
        console.log('Usuário logado, mas sem endereço cadastrado.');
      } else {
        console.warn(data.msg);
      }
    })
    .catch(err => {
      console.error('Erro ao carregar dados do perfil:', err);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('enderecos-container');
    const formNovo = document.getElementById('delivery-form');
    const btnNovo = document.getElementById('btn-novo-endereco');

    fetch('buscar_enderecos_usuario.php')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'ok' && data.enderecos.length > 0) {
                data.enderecos.forEach((end, index) => {
                    const label = document.createElement('label');
                    label.className = 'shipping-option';

                    label.innerHTML = `
                        <input type="radio" name="enderecoSelecionado" value="${end.idEndereco}" ${index === 0 ? 'checked' : ''}>
                        <div class="shipping-info">
                            <span class="shipping-name">${end.nome}</span>
                            <span class="shipping-time">${end.endereco}, ${end.numero} - ${end.bairro}</span>
                            <span class="shipping-price">${end.cidade} - ${end.estado}, ${end.cep}</span>
                        </div>
                    `;
                    container.appendChild(label);
                });
            } else {
                // Não tem endereço → exibe formulário
                formNovo.style.display = 'block';
                btnNovo.style.display = 'none';
            }
        });

    btnNovo.addEventListener('click', () => {
        formNovo.style.display = 'block';
        btnNovo.style.display = 'none';
    });
});

document.getElementById('confirm-order').addEventListener('click', async function(e) {
    e.preventDefault();

    try {
        const res = await fetch('verificaLogin.php');
        const data = await res.json();

        if (!data.logado) {
            alert('Você precisa estar logado para finalizar a compra.');
            window.location.href = 'login.php'; // ou login.html
            return;
        }

        // Se estiver logado, validar formulário normalmente
        const form = document.getElementById('delivery-form');
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = '#e91e63';
                isValid = false;
            } else {
                field.style.borderColor = '#333';
            }
        });

        if (isValid) {
            alert('Pedido confirmado com sucesso! Você será redirecionado para a página de confirmação.');
            // window.location.href = 'order-confirmation.html';
        } else {
            alert('Por favor, preencha todos os campos obrigatórios.');
        }

    } catch (error) {
        alert('Erro ao verificar login. Tente novamente.');
    }
});
