<?php
require("../../conexao.php");

$tel_teste = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_sistema);


  $url = "http://api.wordmensagens.com.br/send-text";

  $data = array('instance' => $instancia,
                'to' => $tel_teste,
                'token' => $token,
                'message' => "Mensagem a ser Enviada");


  $options = array('http' => array(
                 'method' => 'POST',
                 'content' => http_build_query($data)
  ));

  $stream = stream_context_create($options);

  $result = @file_get_contents($url, false, $stream);

  echo $result;
?>
  