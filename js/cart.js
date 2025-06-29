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

        // Controle de Quantidade
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                let value = parseInt(input.value);
                
                if (this.classList.contains('minus')) {
                    if (value > 1) {
                        input.value = value - 1;
                    }
                } else if (this.classList.contains('plus')) {
                    input.value = value + 1;
                }
                
                // Aqui você atualizaria o total no carrinho
                updateCartTotal();
            });
        });

        // Remover Item
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const item = this.closest('.cart-item');
                item.style.animation = 'fadeOut 0.3s ease';
                
                setTimeout(() => {
                    item.remove();
                    // Verifica se o carrinho está vazio
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        showEmptyCart();
                    } else {
                        updateCartTotal();
                    }
                }, 300);
            });
        });

        // Aplicar Cupom
        document.querySelector('.coupon-btn').addEventListener('click', function() {
            const couponCode = document.querySelector('.coupon-input').value;
            
            if (couponCode.trim() === '') {
                alert('Por favor, insira um código de cupom');
                return;
            }
            
            // Aqui você validaria o cupom com o backend
            // Simulação de cupom válido
            if (couponCode.toUpperCase() === 'DESCONTO10') {
                alert('Cupom aplicado com sucesso! 10% de desconto concedido.');
                // Atualizar o resumo com o desconto
                document.querySelector('.summary-row:nth-child(3) span:last-child').textContent = '- R$ 48,56';
                document.querySelector('.summary-total span:last-child').textContent = 'R$ 437,04';
            } else {
                alert('Cupom inválido ou expirado');
            }
        });

        // Finalizar Compra
        document.querySelector('.checkout-btn').addEventListener('click', function() {
            // Verifica se o carrinho está vazio
            if (document.querySelectorAll('.cart-item').length === 0) {
                alert('Seu carrinho está vazio. Adicione itens antes de finalizar a compra.');
                return;
            }
            
            // Redireciona para a página de checkout
            window.location.href = 'checkout.html';
        });

        // Função para mostrar carrinho vazio
        function showEmptyCart() {
            const cartItems = document.querySelector('.cart-items');
            cartItems.innerHTML = `
                <h2 class="cart-title">Seus Itens</h2>
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Seu carrinho está vazio</h3>
                    <p>Adicione itens para começar a comprar</p>
                    <a href="index.html" class="btn-continue">Continuar Comprando</a>
                </div>
            `;
            
            // Atualiza o resumo para zero
            document.querySelector('.summary-row:nth-child(1) span:last-child').textContent = 'R$ 0,00';
            document.querySelector('.summary-row:nth-child(2) span:last-child').textContent = 'R$ 0,00';
            document.querySelector('.summary-row:nth-child(3) span:last-child').textContent = '- R$ 0,00';
            document.querySelector('.summary-total span:last-child').textContent = 'R$ 0,00';
            
            // Desabilita o botão de finalizar compra
            document.querySelector('.checkout-btn').disabled = true;
        }

        // Função para atualizar o total do carrinho
        function updateCartTotal() {
            // Esta função seria mais complexa em uma implementação real
            // Aqui está apenas uma simulação básica
            console.log('Atualizando total do carrinho...');
            // Em uma implementação real, você calcularia baseado nos itens e quantidades
        }

           document.getElementById('clear-cart').addEventListener('click', function() {
        if(confirm('Tem certeza que deseja remover todos os itens do carrinho?')) {
            // Lógica para limpar o carrinho
            const cartItems = document.querySelectorAll('.cart-item');
            cartItems.forEach(item => item.remove());
            
            // Atualizar o resumo
            document.querySelector('.summary-row span:last-child').textContent = 'R$ 0,00';
            document.querySelector('.summary-total span:last-child').textContent = 'R$ 0,00';
            
            // Mostrar mensagem ou estado vazio
            document.querySelector('.cart-items').innerHTML = `
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Seu carrinho está vazio</h3>
                    <p>Adicione itens para começar a comprar</p>
                    <a href="roupas.php" class="btn-continue">Continuar Comprando</a>
                </div>
            `;
            
            // Aqui você adicionaria a lógica para limpar o carrinho no backend
            // fetch('limpar-carrinho.php', { method: 'POST' })
            //   .then(response => response.json())
            //   .then(data => console.log('Carrinho limpo'));
        }
    });

        // Inicialização
        updateCartTotal();