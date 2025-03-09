<?php

include("./config.php");
include("../../conexao.php");

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
//error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

$status_pag = array(
    "approved" => "Aprovado",
    "rejected" => "Rejeitado",
    "in_process" => "Pendente aprovação",
);

$status_pag_motivo = array(
"approved" => array("accredited" => "Pronto, seu pagamento foi aprovado!"),
"in_process" => array(
    "pending_contingency" => "Estamos processando o pagamento. Não se preocupe, em menos de 2 dias úteis informaremos por e-mail se foi creditado.",
    "pending_review_manual" => "Estamos processando seu pagamento. Não se preocupe, em menos de 2 dias úteis informaremos por e-mail se foi creditado ou se necessitamos de mais informação."
),
"rejected" => array(
    "cc_rejected_bad_filled_card_number" => "Revise o número do cartão.",
    "cc_rejected_bad_filled_date" => "Revise a data de vencimento.",
    "cc_rejected_bad_filled_other" => "Revise os dados.",
    "cc_rejected_bad_filled_security_code" => "Revise o código de segurança do cartão.",
    "cc_rejected_blacklist" => "Não pudemos processar seu pagamento.",
    "cc_rejected_call_for_authorize" => "Você deve autorizar ao payment_method_id o pagamento do valor ao Mercado Pago.",
    "cc_rejected_card_disabled" => "Ligue para o payment_method_id para ativar seu cartão. O telefone está no verso do seu cartão.",
    "cc_rejected_card_error" => "Não conseguimos processar seu pagamento.",
    "cc_rejected_duplicated_payment" => "Você já efetuou um pagamento com esse valor. Caso precise pagar novamente, utilize outro cartão ou outra forma de pagamento.",
    "cc_rejected_high_risk" => "Seu pagamento foi recusado. Escolha outra forma de pagamento. Recomendamos meios de pagamento em dinheiro.",
    "cc_rejected_insufficient_amount" => "O payment_method_id possui saldo insuficiente.",
    "cc_rejected_invalid_installments" => "O payment_method_id não processa pagamentos em installments parcelas.",
    "cc_rejected_max_attempts" => "Você atingiu o limite de tentativas permitido. Escolha outro cartão ou outra forma de pagamento.",
    "cc_rejected_other_reason" => "payment_method_id não processa o pagamento.",
    "cc_rejected_card_type_not_allowed" => "O pagamento foi rejeitado porque o usuário não tem a função crédito habilitada em seu cartão multiplo (débito e crédito)."
)
);

if ($_GET["acc"] == "check") {

    $id = $_GET["id"];
    $id_conta = $_GET["id_conta"];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $TOKEN_MERCADO_PAGO,
        ),
    ));

    $response_original = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response_original, true);

    $idcobranca = $response["external_reference"];
    $status = $response["status"];
    $payment_method_id = $response["payment_method_id"];
    $transaction_amount = $response["transaction_amount"];
    $id_mercadopago = $response["id"];

    $pdo->query("UPDATE receber SET ref_pix = '$id_mercadopago' where id = '$id_conta'");

    // pix
    if($payment_method_id=="pix"){
       
    $pdo->query("UPDATE receber SET forma_pgto = 'Pix' where id = '$id_conta'");

    }

    // bolbradesco
    if($payment_method_id=="bolbradesco"){

       $pdo->query("UPDATE receber SET forma_pgto = 'Boleto' where id = '$id_conta'");

    }
   
    if ($status == "approved") { // PAGAMENTO APROVADO

        $pdo->query("UPDATE receber SET pago = 'Sim', data_pgto = curDate() where id = '$id_conta'");


    $query2 = $pdo->query("SELECT * from receber where id = '$id_conta' order by id desc limit 1");
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
$hash = @$res2[0]['hash2'];


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

        //verificar se a parcela já está criada
    $query2 = $pdo->query("SELECT * from receber where (referencia = 'Cobrança' or referencia = 'Empréstimo') and id_ref = '$id_ref' and data_venc = '$novo_vencimento'");
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    $ja_criada = @count($res2);

    if($ja_criada == 0){

    //criar outra conta a receber na mesma data de vencimento com a frequência associada
    $pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = 'Cobrança', id_ref = '$id_ref', valor = '$valor', parcela = '$nova_parcela', usuario_lanc = '0', data = curDate(), data_venc = '$novo_vencimento', pago = 'Não', descricao = '$descricao', frequencia = '$dias_frequencia', recorrencia = 'Sim' ");

    }
}

        echo json_encode(array("status" => "pago"));
        die;
    
    } else {

        echo json_encode(array("status" => $status));
        die;

    }

}
// FIM

// GERAR PAGAMENTO
try {
    
    $parsed_body = json_decode(file_get_contents('php://input'), true);
    $TIPO_PAGAMENTO = $parsed_body["payment_method_id"];
    $parsed_body["notification_url"] = $URL_NOTIFICACAO;
    $parsed_body["capture"] = true;
    
} catch(Exception $exception) {

    $response_fields = array('error_message' => $exception->getMessage());
    echo json_encode($response_fields);
    die;

}

// ENVIAR
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS => json_encode($parsed_body),
CURLOPT_HTTPHEADER => array(
    'X-Idempotency-Key: '.date('Y-m-d-H:i:s-').rand(0, 1500),
    'Authorization: Bearer '.$TOKEN_MERCADO_PAGO,
    'Content-Type: application/json',
),
));

$response = curl_exec($curl);
curl_close($curl);

$payment = json_decode($response);

if($payment->id === null) {
    $error_message = 'Erro ao realizar o pagamento, contacte com o suporte.';
    if($payment->message !== null) {
        $sdk_error_message = $payment->message;
        $error_message = $sdk_error_message !== null ? $sdk_error_message : $error_message;
    }
    if($error_message == "Invalid transaction_amount"){
        $error_message = "Valor de pagamento inválido";
    }
    echo json_encode(array("status" => false, "message" => $error_message));
    die;
    //throw new Exception($error_message);
} 

$idcobranca = $payment->external_reference;
$status = $payment->status;
$payment_method_id = $payment->payment_method_id;
$transaction_amount = $payment->transaction_amount;
$id_mercadopago = $payment->id;

if($TIPO_PAGAMENTO=="pix"){

   
    $status_mostrar = ($payment->status=="pending")? true : false;

} elseif($TIPO_PAGAMENTO=="bolbradesco" || $TIPO_PAGAMENTO=="pec"){ // boleto

   
    $status_mostrar = ($payment->status=="pending")? true: false;

} else { // cartao

   
    $status_mostrar = true;

}

$transaction_data = array(
    'id' => $payment->id,
    'status' => $status_mostrar,
    'tipo' => $TIPO_PAGAMENTO,
    'message' => $status_pag_motivo[$payment->status][$payment->status_detail],
);

echo json_encode($transaction_data);
die;


