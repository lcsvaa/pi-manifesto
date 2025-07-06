<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['user_id']) || isset($_SESSION['is_admin'])) {
    echo json_encode(['logado' => true]);
} else {
    echo json_encode(['logado' => false]);
}