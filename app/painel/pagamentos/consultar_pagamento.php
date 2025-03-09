<?php
//require("../../conexao.php");
include('tokens.php');
$data_atual = date('Y-m-d');
//$ref_pix = '102345603532';
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
$total_pago = @$resultado->transaction_amount;
$metodo_pagamento = @$resultado->payment_method_id; // Ex: "pix", "visa", "master"
$tipo_pagamento = @$resultado->payment_type_id; // Ex: "credit_card", "debit_card", "ticket (boleto)", "bank_transfer (pix)"

if($metodo_pagamento == 'pix'){
    $metodo_pagamento = 'Pix';
}


if($status_api == 'approved'){
    

$query2 = $pdo->query("SELECT * from receber where ref_pix = '$ref_pix' and pago != 'Sim' order by id desc limit 1");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$hash = @$res2[0]['hash'];
$cliente = @$res2[0]['cliente'];
$id_ref = @$res2[0]['id_ref'];
$valor = @$res2[0]['valor'];
$parcela = @$res2[0]['parcela'];
$nova_parcela = $parcela + 1;
$recorrencia = @$res2[0]['recorrencia'];
$referencia = @$res2[0]['referencia'];
$data_venc = @$res2[0]['data_venc'];
$dias_frequencia = @$res2[0]['frequencia'];
$descricao = @$res2[0]['descricao'];
$pago = @$res2[0]['pago'];

//CALCULAR OS JUROS PARA A CONTA CASO EXISTA
$data_atual = date('Y-m-d');

if($referencia == 'Cobrança'){   
    $sql_consulta = 'cobrancas';
}else{    
    $sql_consulta = 'emprestimos';
}

$query22 = $pdo->query("SELECT * FROM $sql_consulta where id = '$id_ref'");
$res22 = $query22->fetchAll(PDO::FETCH_ASSOC);
$multa = $res22[0]['multa'];
$juros = $res22[0]['juros'];


$valor_multa = 0;
$valor_juros = 0;
$dias_vencido = 0;

if(@strtotime($data_venc) < @strtotime($data_atual) and $pago != 'Sim'){

$valor_multa = $multa;

//calcular quanto dias está atrasado

$data_inicio = new DateTime($data_venc);
$data_fim = new DateTime($data_atual);
$dateInterval = $data_inicio->diff($data_fim);
$dias_vencido = $dateInterval->days;

$valor_juros = $dias_vencido * ($juros * $valor / 100);
$valor_juros = number_format($valor_juros, 2, '.', '.');

$valor = $valor_juros + $valor_multa + $valor;

}

//dados do cliente
$query22 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res22 = $query22->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $res22[0]['nome'];
$tel_cliente = $res22[0]['telefone'];
$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $tel_cliente);
$telefone_envio = $tel_cliente;
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

        //verificação de feriados
    require("verificar_feriados.php");

    //criar outra conta a receber na mesma data de vencimento com a frequência associada
    $pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = '$referencia', id_ref = '$id_ref', valor = '$valor', parcela = '$nova_parcela', usuario_lanc = '0', data = curDate(), data_venc = '$novo_vencimento', pago = 'Não', descricao = '$descricao', frequencia = '$dias_frequencia', recorrencia = 'Sim', hora_alerta = '$hora_random' ");
       $ult_id_conta = $pdo->lastInsertId();


    }
}

$pdo->query("UPDATE receber SET pago = 'Sim', valor = '$total_pago', data_pgto = curDate(), hora = curTime(), forma_pgto = '$metodo_pagamento' where ref_pix = '$ref_pix'");

}
//var_dump($resultado);
?>