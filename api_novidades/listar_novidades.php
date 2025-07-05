<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once '../conexao.php';

echo json_encode(
  $pdo->query('SELECT * FROM tb_novidades ORDER BY dataNovidade DESC')
      ->fetchAll(PDO::FETCH_ASSOC)
);
