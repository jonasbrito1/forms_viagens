<?php
ob_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $data_nasc = $_POST['data_nasc'] ?? '';
        $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
        $email = $_POST['email'] ?? '';
        $rg = preg_replace('/\D/', '', $_POST['rg'] ?? '');
        $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');

        if (empty($nome) || empty($data_nasc) || empty($cpf) || empty($email) || empty($rg) || empty($telefone)) {
            ob_end_clean();
            echo json_encode([
                'status' => 'error',
                'message' => 'Todos os campos obrigatórios devem ser preenchidos.'
            ]);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ob_end_clean();
            echo json_encode([
                'status' => 'error',
                'message' => 'E-mail inválido.'
            ]);
            exit();
        }

        if (!preg_match("/^\d{11}$/", $cpf)) {
            ob_end_clean();
            echo json_encode([
                'status' => 'error',
                'message' => 'CPF deve ter 11 dígitos (somente números).'
            ]);
            exit();
        }

        if (!preg_match("/^\d{8,9}$/", $rg)) {
            ob_end_clean();
            echo json_encode([
                'status' => 'error',
                'message' => 'RG deve ter entre 8 e 9 dígitos (somente números).'
            ]);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO clientes_hr (nome, data_nasc, cpf, email, rg, telefone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $data_nasc, $cpf, $email, $rg, $telefone]);

        ob_end_clean();
        echo json_encode([
            'status' => 'success',
            'message' => 'Cadastro concluído com sucesso, entraremos em contato em breve. Obrigado!'
        ]);
    } else {
        ob_end_clean();
        echo json_encode([
            'status' => 'error',
            'message' => 'Método de requisição inválido. Use POST.'
        ]);
    }
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao processar o cadastro: ' . $e->getMessage()
    ]);
}
?>
