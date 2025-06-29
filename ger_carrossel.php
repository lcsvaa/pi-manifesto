<?php
require_once 'conexao.php';

$imagens = $pdo->query("SELECT * FROM tb_imagem WHERE idProduto IS NULL ORDER BY statusImagem DESC");

foreach ($imagens as $img) {
  echo '
  <div class="item-card" data-id="'.$img['idImagem'].'">
    <img src="uploads/carrossel/'.$img['nomeImagem'].'" alt="Imagem do carrossel">
    <div class="item-info">
      <span class="status-badge '.$img['statusImagem'].'">'.$img['statusImagem'].'</span>
      '.(!empty($img['idProduto']) ? '<span class="product-link">Vinculado a produto</span>' : '').'
    </div>
    <div class="item-actions">
      <button class="btn-action editar" data-id="'.$img['idImagem'].'">
        <i class="fas fa-edit"></i> Editar
      </button>
      <button class="btn-action remover" data-id="'.$img['idImagem'].'">
        <i class="fas fa-trash"></i> Remover
      </button>
    </div>
  </div>';
}
?>