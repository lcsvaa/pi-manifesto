<?php
require_once 'conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

session_start();
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

// Verifica CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Token de segurança inválido']);
    exit;
}

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'add_address':
        case 'update_address':
            $cep = preg_replace('/[^0-9]/', '', $_POST['address-cep'] ?? '');
            $rua = filter_input(INPUT_POST, 'address-street', FILTER_SANITIZE_STRING);
            $numero = filter_input(INPUT_POST, 'address-number', FILTER_SANITIZE_STRING);
            $complemento = filter_input(INPUT_POST, 'address-complement', FILTER_SANITIZE_STRING);
            $bairro = filter_input(INPUT_POST, 'address-neighborhood', FILTER_SANITIZE_STRING);
            $cidade = filter_input(INPUT_POST, 'address-city', FILTER_SANITIZE_STRING);
            $apelido = filter_input(INPUT_POST, 'address-name', FILTER_SANITIZE_STRING) ?? 'Meu Endereço';
            $isDefault = isset($_POST['address-default']);

            if (strlen($cep) !== 8 || empty($rua) || empty($numero) || empty($bairro) || empty($cidade)) {
                throw new Exception('Preencha todos os campos obrigatórios corretamente');
            }

            // Formata CEP
            $cep = substr($cep, 0, 5) . '-' . substr($cep, 5, 3);

            // Se for principal, adiciona marcação
            if ($isDefault) {
                $apelido = '(Principal) ' . str_replace('(Principal) ', '', $apelido);
                // Remove marcação de outros endereços
                $stmt = $pdo->prepare("UPDATE tb_endereco 
                                      SET apelidoEndereco = REPLACE(apelidoEndereco, '(Principal) ', '') 
                                      WHERE idUsuario = ?");
                $stmt->execute([$userId]);
            }

            if ($action === 'update_address' && !empty($_POST['address_id'])) {
                $addressId = filter_input(INPUT_POST, 'address_id', FILTER_VALIDATE_INT);
                $stmt = $pdo->prepare("UPDATE tb_endereco SET 
                    apelidoEndereco = ?, cep = ?, rua = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?
                    WHERE idEndereco = ? AND idUsuario = ?");
                $stmt->execute([$apelido, $cep, $rua, $numero, $complemento, $bairro, $cidade, $addressId, $userId]);
                
                $message = 'Endereço atualizado com sucesso!';
            } else {
                $stmt = $pdo->prepare("INSERT INTO tb_endereco 
                    (apelidoEndereco, cep, rua, numero, complemento, bairro, cidade, idUsuario) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$apelido, $cep, $rua, $numero, $complemento, $bairro, $cidade, $userId]);
                
                $addressId = $pdo->lastInsertId();
                $message = 'Endereço cadastrado com sucesso!';
            }

            echo json_encode([
                'success' => true,
                'message' => $message,
                'address_id' => $addressId
            ]);
            break;

        case 'delete_address':
            $addressId = filter_input(INPUT_POST, 'address_id', FILTER_VALIDATE_INT);
            if (!$addressId) {
                throw new Exception('ID do endereço inválido');
            }

            $stmt = $pdo->prepare("DELETE FROM tb_endereco WHERE idEndereco = ? AND idUsuario = ?");
            $stmt->execute([$addressId, $userId]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Endereço excluído com sucesso']);
            } else {
                throw new Exception('Endereço não encontrado ou você não tem permissão');
            }
            break;

        case 'get_address':
            $addressId = filter_input(INPUT_POST, 'address_id', FILTER_VALIDATE_INT);
            if (!$addressId) {
                throw new Exception('ID do endereço inválido');
            }

            $stmt = $pdo->prepare("SELECT * FROM tb_endereco WHERE idEndereco = ? AND idUsuario = ?");
            $stmt->execute([$addressId, $userId]);
            $address = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($address) {
                echo json_encode(['success' => true, 'address' => $address]);
            } else {
                throw new Exception('Endereço não encontrado ou você não tem permissão');
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);

            case 'update_personal_data':
    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING);
    
    if (!$userId || !$name || !$email || !$phone || !$birthdate) {
        throw new Exception('Preencha todos os campos obrigatórios');
    }

    // Validação básica de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('E-mail inválido');
    }

    // Verifica se email já existe (para outro usuário)
    $stmt = $pdo->prepare("SELECT id FROM tb_usuario WHERE email = ? AND id != ?");
    $stmt->execute([$email, $userId]);
    if ($stmt->fetch()) {
        throw new Exception('Este e-mail já está sendo usado por outro usuário');
    }

    // Validação da idade (mínimo 18 anos)
    $birthdateObj = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birthdateObj)->y;
    if ($age < 18) {
        throw new Exception('Você deve ter pelo menos 18 anos');
    }

    // Atualiza no banco de dados
    $stmt = $pdo->prepare("UPDATE tb_usuario 
                          SET nomeUser = ?, email = ?, telefone = ?, dataNascimento = ? 
                          WHERE id = ?");
    $stmt->execute([$name, $email, $phone, $birthdate, $userId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Dados atualizados com sucesso!'
        ]);
    } else {
        throw new Exception('Nenhum dado foi alterado');
    }
    break;

    case 'change_password':
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        throw new Exception('Preencha todos os campos!');
    }
    
    if ($newPassword !== $confirmPassword) {
        throw new Exception('As senhas não coincidem!');
    }
    
    // Verifica a senha atual
    $stmt = $pdo->prepare("SELECT senha FROM tb_usuario WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!password_verify($currentPassword, $user['senha'])) {
        throw new Exception('Senha atual incorreta!');
    }
    
    // Atualiza a senha
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE tb_usuario SET senha = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $userId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Senha alterada com sucesso!'
    ]);
    break;
    }


} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

