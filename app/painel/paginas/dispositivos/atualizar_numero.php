<?php
header('Content-Type: application/json');

// Exibir todos os erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tabela no banco de dados
$tabela = 'dispositivos';

// Incluir a conexão com o banco de dados
require_once("../../../conexao.php");

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar os dados do POST
    $appkey = $_POST['appkey'] ?? null;
    $dados = $_POST['dados'] ?? null;
    $telefone = $dados['phone'] ?? null;

    // Verificar se os dados necessários estão presentes
    if ($appkey && $telefone) {
        // Preparar a consulta SQL para atualizar o telefone
        $stmt = $pdo->prepare("UPDATE $tabela SET telefone = :telefone WHERE appkey = :appkey");

        // Executar a consulta com os valores recebidos
        if ($stmt->execute([':telefone' => $telefone, ':appkey' => $appkey])) {
            $response = json_encode(['status' => 200, 'message' => 'Telefone atualizado com sucesso']);
        } else {
            $response = json_encode(['status' => 500, 'message' => 'Erro ao atualizar o telefone']);
        }
    } else {
        $response = json_encode(['status' => 400, 'message' => 'Dados insuficientes']);
    }
} else {
    $response = json_encode(['status' => 405, 'message' => 'Método não permitido']);
}

echo $response;
?>
