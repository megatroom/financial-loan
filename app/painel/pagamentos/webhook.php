<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
//error_reporting(E_ALL);

include("./config.php");
include("../../conexao.php");

if($_GET["topic"]=="" || $_GET["id"]==""){
    die("sem_dados");
}

// CONSULTAR PAGAMENTO
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/'.$id,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$TOKEN_MERCADO_PAGO,
),
));

$response_original = curl_exec($curl);

curl_close($curl);
$response = json_decode($response_original, true);

if(empty($response)){ die("dados_vazios"); }

$idcobranca = $response["collection"]["external_reference"];
$status = $response["collection"]["status"];
$payment_method_id = $response["collection"]["payment_method_id"];
$transaction_amount= $response["collection"]["transaction_amount"];
$id_mercado_pago = $response["collection"]["id"];

if($status == "approved"){

    $pdo->query("UPDATE receber SET pago = 'Sim', data_pgto = curDate() where ref_pix = '$id_mercado_pago'");

    $query2 = $pdo->query("SELECT * from receber where ref_pix = '$id_mercado_pago' order by id desc limit 1");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$hash = @$res2[0]['hash'];
$cliente = @$res2[0]['cliente'];
$id_ref = @$res2[0]['id_ref'];
$valor = @$res2[0]['valor'];
$parcela = @$res2[0]['parcela'];
$nova_parcela = $parcela + 1;
$recorrencia = @$res2[0]['recorrencia'];
$data_venc = @$res2[0]['data_venc'];
$dias_frequencia = @$res2[0]['frequencia'];
$descricao = @$res2[0]['descricao'];

require("excluir_agendamento.php");

$hash = @$res2[0]['hash2'];
require("excluir_agendamento.php");

if($recorrencia == 'Sim'){

    if($dias_frequencia == 30 || $dias_frequencia == 31){           
            $novo_vencimento = date('Y-m-d', @strtotime("+1 month",@strtotime($data_venc)));
        }else if($dias_frequencia == 90){           
            $novo_vencimento = date('Y-m-d', @strtotime("+3 month",@strtotime($data_venc)));
        }else if($dias_frequencia == 180){ 
            $novo_vencimento = date('Y-m-d', @strtotime("6 month",@strtotime($data_venc)));
        }else if($dias_frequencia == 360 || $dias_frequencia == 365){           
            $novo_vencimento = date('Y-m-d', @strtotime("+12 month",@strtotime($data_venc)));

        }else{          
            $novo_vencimento = date('Y-m-d', @strtotime("+$dias_frequencia days",@strtotime($data_venc)));
        }

    //criar outra conta a receber na mesma data de vencimento com a frequência associada
    $pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = 'Cobrança', id_ref = '$id_ref', valor = '$valor', parcela = '$nova_parcela', usuario_lanc = '0', data = curDate(), data_venc = '$novo_vencimento', pago = 'Não', descricao = '$descricao', frequencia = '$dias_frequencia', recorrencia = 'Sim' ");
}


    echo json_encode(array("status" => "pago"));
    die;

}
