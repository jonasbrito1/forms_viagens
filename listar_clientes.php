<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Evita problemas de CORS

require_once 'config.php';  // Inclui o arquivo de configuração para conectar ao banco de dados

try {
    // Consulta SQL para listar clientes
    $sql = "SELECT id, nome, data_nasc, cpf, email, rg, telefone FROM clientes_hr";
    $stmt = $conn->query($sql);

    $clientes = [];
    if ($stmt->rowCount() > 0) {
        // Fetching os dados de cada cliente
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientes[] = $row;
        }

        // Retorna os dados dos clientes como JSON
        echo json_encode([
            'status' => 'success',
            'data' => $clientes
        ]);
    } else {
        // Caso não haja clientes cadastrados
        echo json_encode([
            'status' => 'success',
            'data' => [],
            'message' => 'Nenhum cliente encontrado.'
        ]);
    }
} catch (Exception $e) {
    // Em caso de erro na consulta ao banco de dados
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao listar os clientes: ' . $e->getMessage()
    ]);
}

// A conexão PDO será fechada automaticamente no fim do script
?>
