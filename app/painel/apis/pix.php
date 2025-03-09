<!DOCTYPE html>
<html>
<head>
    <title>Pagar Conta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- js-->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<?php 
require_once('../../conexao.php');
require_once('apiConfig.php');
$data_atual = date('Y-m-d');
if($access_token == ""){
	echo 'Configure o Token da Api Pix no arquivo ApiConfig';
	exit();
}

$id_ref = "";
//RECUPERAR OS DADOS DA CONTA
$id_conta = $_GET['id'];
$query = $pdo->query("SELECT * FROM receber where id = '$id_conta'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
$id_par = $res[0]['id'];
$valor = $res[0]['valor'];
$parcela = $res[0]['parcela'];
$data_venc = $res[0]['data_venc'];
$data_pgto = $res[0]['data_pgto'];
$pago = $res[0]['pago'];
$descricao = $res[0]['descricao'];
$cliente = $res[0]['cliente'];
$ref_pix = $res[0]['ref_pix'];
$id_ref = $res[0]['id_ref'];

$query = $pdo->query("SELECT * FROM emprestimos where id = '$id_ref'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$multa = $res[0]['multa'];
$juros = $res[0]['juros'];


$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente =  $res[0]['nome'];

$data_vencF = implode('/', array_reverse(explode('-', $data_venc)));
$data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));
$valorF = number_format($valor, 2, ',', '.');


$valor_final = $valor;

$valor_multa = 0;
$valor_juros = 0;
$dias_vencido = 0;
$classe_venc = '';
if(strtotime($data_venc) < strtotime($data_atual) and $pago != 'Sim'){
$classe_venc = 'text-danger';
$valor_multa = $multa;

//calcular quanto dias está atrasado

$data_inicio = new DateTime($data_venc);
$data_fim = new DateTime($data_atual);
$dateInterval = $data_inicio->diff($data_fim);
$dias_vencido = $dateInterval->days;

$valor_juros = $dias_vencido * ($juros * $valor / 100);

$valor_final = $valor_juros + $valor_multa + $valor;


}

$valor_final = number_format($valor_final, 2);
$valor_finalF = number_format($valor_final, 2, ',', '.');


if($ref_pix != ""){
        require('consultar_pagamento.php');
                if(@$status_api == 'approved' || $pago == 'Sim'){
                echo "
        <div align='center'>
        <span>".$nome_cliente."</span><br>
        <span>Parcela: ".$parcela."</span><br>
        <span><b>R$ ".$valor_finalF."</b></span><br>
        <p><img src='".$url_sistema."painel/images/000.png'></p>
        </div>
        ";


                exit();
            }
        }

}


$curl = curl_init();

    $dados["transaction_amount"]                    = (float)$valor_final;
    $dados["description"]                           = $descricao;
    $dados["external_reference"]                    = $id_conta;
    $dados["payment_method_id"]                     = "pix";
    $dados["notification_url"]                      = "https://google.com";
    $dados["payer"]["email"]                        = "teste@hotmail.com";
    $dados["payer"]["first_name"]                   = "User";
    $dados["payer"]["last_name"]                    = "Teste";
    
    $dados["payer"]["identification"]["type"]       = "CPF";
    $dados["payer"]["identification"]["number"]     = "34152426764";
    
    $dados["payer"]["address"]["zip_code"]          = "06233200";
    $dados["payer"]["address"]["street_name"]       = "Av. das Nações Unidas";
    $dados["payer"]["address"]["street_number"]     = "3003";
    $dados["payer"]["address"]["neighborhood"]      = "Bonfim";
    $dados["payer"]["address"]["city"]              = "Osasco";
    $dados["payer"]["address"]["federal_unit"]      = "SP";

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($dados),
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'content-type: application/json',
        'Authorization: Bearer '.$access_token
    ),
    ));
    $response = curl_exec($curl);
    $resultado = json_decode($response);

    $id = $dados["external_reference"];
    //var_dump($response);
curl_close($curl);
$codigo_pix = $resultado->point_of_interaction->transaction_data->qr_code;

$id_ref = $resultado->id;

echo "
<div align='center'>
<span>".$nome_cliente."</span><br>
<span>Parcela: ".$parcela."</span><br>
<span><b>R$ ".$valor_finalF."</b></span><br>
<img style='display:block;' width='200px' id='base64image'
       src='data:image/jpeg;base64, ".$resultado->point_of_interaction->transaction_data->qr_code_base64."'/>";
echo '
 <a style="margin-left:15px" class="link-neutro" href="#" onClick="copiar()"><i class="bi bi-clipboard text-primary"></i> <span ><small><small>Copiar Chave Pix <br><input type="text" id="chave_pix_copia" value="'.$codigo_pix.'" style="background: transparent; border:none; width:100px; opacity:0" readonly></small></small></span> </a>';
echo '<div style="margin:10px; border:1px solid #000; font-size:11px" >'.$codigo_pix.'</div>';
echo '<input type="hidden" id="codigo_pix" value="'.$id_ref.'">';

echo '<br><br><button onclick="reload()" style="padding:2px; background:green; color:#FFF">Confirmar Pagamento</button>';

echo '</div>';


//inserir na conta a ref pix
$pdo->query("UPDATE receber SET ref_pix = '$id_ref' where id = '$id_conta'");

?>   

</body>
</html>



<script type="text/javascript">
    $(document).ready( function () {
        setInterval(verificar_pgto, 3000);
} );

  function copiar(){
    document.querySelector("#chave_pix_copia").select();
    document.querySelector("#chave_pix_copia").setSelectionRange(0, 99999); /* Para mobile */
    document.execCommand("copy");
    //$("#chave_pix_copia").hide();
    alert('Chave Pix Copiada! Use a opção Copie e Cole para Pagar')
  }

  function reload(){
    location.reload();
  }

  function verificar_pgto(){
    var ref = "<?=$id_ref?>";
    
      $.ajax({
        url: '<?=$url_sistema?>painel/apis/consultar_pagamento2.php',
        method: 'POST',
        data: {ref},
        dataType: "html",

        success:function(result){            
            if(result.trim() == 'approved'){
                reload();
            }        
        }
    });
  }
</script>


