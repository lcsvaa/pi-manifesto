<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
    <div class="container">
        <div class="nav-left">
            <a href="index.php">
                <img src="img/logo.png" alt="Logo da loja" class="logo">
            </a>
        </div>

        <div class="nav-center" id="nav-center">
            <ul class="nav-links">
                <li><a href="Produtos.php">Produtos</a></li>
                <li><a href="novidades.php">Novidades</a></li>
                <li><a href="Colecoes.php" class="active">Colecoes</a></li>
            </ul>
        </div>

        <div class="nav-right" id="nav-right">
            <input type="text" class="search-bar" placeholder="Buscar produtos...">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="profile.php" class="login-btn"><i class="fas fa-user-circle"></i> Perfil</a>
            <?php else: ?>
                <a href="login.php" class="login-btn"><i class="fas fa-user"></i> Login</a>
            <?php endif; ?>
            <a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i></a>
        </div>

        <button class="hamburger-menu" id="hamburger-menu" aria-label="Menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </div>
</nav>