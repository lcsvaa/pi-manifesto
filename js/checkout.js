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

// Tabs de pagamento
document.querySelectorAll('.payment-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Remove a classe active de todas as tabs e conteúdos
        document.querySelectorAll('.payment-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.payment-content').forEach(c => c.classList.remove('active'));
        
        // Adiciona a classe active na tab clicada
        this.classList.add('active');
        
        // Mostra o conteúdo correspondente
        const tabId = this.getAttribute('data-tab');
        document.getElementById(`${tabId}-tab`).classList.add('active');
    });
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

// Validação do formulário
document.getElementById('confirm-order').addEventListener('click', function(e) {
    e.preventDefault();
    
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
        // Simulação de envio do formulário
        alert('Pedido confirmado com sucesso! Você será redirecionado para a página de confirmação.');
        // window.location.href = 'order-confirmation.html';
    } else {
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});