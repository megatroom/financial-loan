<?php 

if($seletor_api == 'menuia'){
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://chatbot.menuia.com/api/create-message',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array(
 'appkey' => $token,
  'authkey' => $instancia,
  'to' => $telefone_envio,
  'message' => $mensagem,
  'file' => $url_envio,
  'sandbox' => 'false'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;
}

if($seletor_api == 'wm'){
  $data = array('instance' => $instancia,
  'to' => $telefone_envio,
  'token' => $token,
  'message' => "",
  'url' => $url_envio);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.wordmensagens.com.br/send-docnew',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $data,
));

$response = curl_exec($curl);

curl_close($curl);

$response = json_decode($response, true);

if($response['erro'] == false){
  //echo "Enviado com sucesso";
}else if($response['erro'] == true){
 // echo "Erro no Envio > ".$response['message'];
}
}


 ?>