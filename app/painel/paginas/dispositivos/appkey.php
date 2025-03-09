<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$tabela = 'dispositivos';
require_once("../../../conexao.php");
$appkey = $_POST['appkey'];

if (empty($appkey)) 
{
    $result = $pdo->query("SELECT * FROM dispositivos WHERE status_api IS NULL LIMIT 1");
    $dispositivo = $result->fetch(PDO::FETCH_ASSOC);

    if (!$dispositivo) 
    {
        $nucleo = rand(1, 4);
        $versao = 2;
        $appkey = uniqid('appkey_', true);
        $stmt = $pdo->prepare("INSERT INTO dispositivos SET appkey = :appkey");
        $stmt->bindParam(':appkey', $appkey);
        
        if($stmt->execute())
        {
            $status = 200;

            //salvar no config esse appKey
            $pdo->query("UPDATE config SET token = '$appkey'");

        }else{
            $status = 500;
        }
    } 
    else 
    {
        $appkey = $dispositivo['appkey'];
        $status = 200;
    }
}
else
{
    $result = $pdo->query("SELECT * FROM dispositivos WHERE appkey = '$appkey' LIMIT 1");
    $dispositivo = $result->fetch(PDO::FETCH_ASSOC); 
    
    if($dispositivo)
    {
        $status = 200;
    }else{
        $status = 404;
    }
}

$response = json_encode(['appkey' => $appkey, 'status' => $status]);
echo $response;