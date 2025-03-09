<?php

include('apiConfig.php');
//$ref_pix = '59947648042';
$ref = @$_POST['ref'];
if($ref != ""){
    $ref_pix = $ref;
}else{
    exit();
}
$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/'.$ref_pix,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'content-type: application/json',
        'Authorization: Bearer '.$access_token
    ),
    ));
    $response = curl_exec($curl);
    $resultado = json_decode($response);
curl_close($curl);
//echo $resultado->status;
$status_api = $resultado->status;

if($status_api == 'approved'){
    $pdo->query("UPDATE receber SET pago = 'Sim', data_pgto = curDate(), forma_pgto = 'Pix' where ref_pix = '$ref_pix'");    
}
//var_dump($resultado);
echo $status_api;
?>