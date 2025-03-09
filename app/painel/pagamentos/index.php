<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
//error_reporting(E_ALL);

include("./config.php");
include("../../conexao.php");

$id_conta = $_GET['id_conta'];


$query = $pdo->query("SELECT * FROM receber where id = '$id_conta'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

$id_par = $res[0]['id'];
$valor = $res[0]['valor'];
$parcela = $res[0]['parcela'];
$data_venc = $res[0]['data_venc'];
$data_pgto = $res[0]['data_pgto'];
$pago = $res[0]['pago'];
$descricao = $res[0]['referencia'];
$cliente = $res[0]['cliente'];
$ref_pix = $res[0]['ref_pix'];
$id_ref = $res[0]['id_ref'];
$referencia = $res[0]['referencia'];




//CALCULAR OS JUROS PARA A CONTA CASO EXISTA
$data_atual = date('Y-m-d');

if($referencia == 'Cobrança'){   
    $sql_consulta = 'cobrancas';
}else{    
    $sql_consulta = 'emprestimos';
}

$query = $pdo->query("SELECT * FROM $sql_consulta where id = '$id_ref'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$multa = $res[0]['multa'];
$juros = $res[0]['juros'];


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

$valorF = number_format($valor, 2, ',', '.');

if($ref_pix != ""){
     require('consultar_pagamento.php');
     if($status_api == 'approved'){
         echo 'Essa conta Já foi Paga';  
         exit();  
        }
}

$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente =  $res[0]['nome'];
$cpf_cliente =  $res[0]['cpf'];
$email_cliente =  $res[0]['email'];

if($email_cliente == ""){
    $email_cliente = "cobranca@sistema.com";
}

$token_valor = ($valor!="")? sha1($valor) : "";
$doc = $cpf_cliente;
$doc =  str_replace(array(",", ".", "-", "/", " "), "", $doc);
$ref = $_REQUEST["ref"];
$email = $email_cliente;
$gerarDireto = $_REQUEST["gerarDireto"];
$descricao = $descricao;
$nome = $nome_cliente;
$sobrenome = $_REQUEST["sobrenome"];






?>
<html lang="pt-br">
<head>
    <title>Pagamento</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <link href="./assets/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/signin.css" rel="stylesheet">
    <script src="./assets/jquery-3.6.4.min.js"></script>
</head>
<body  class="text-center">


<form action="../rel/recibo_class.php" method="post" style="display:none">
    <input type="hidden" name="id" value="<?=$id_conta;?>">
     <input type="hidden" name="enviar" value="Sim">
    <button id="btn_form" type="submit"></button>
</form>



<div style="max-width: 500px; max-height: 800px; margin: 0 auto;  text-align: center; margin-bottom: 20px; word-break: break-all;" >


<div id="info_pagamento" style="text-align: center;"> 
        <h4 class="h3 mb-3 font-weight-normal" style=" font-size: 18px; border-radius: 4px;"><span>(Parcela <?=$parcela;?>)</span> <span style="color:green">R$ <?=$valorF;?></span></h4>  

</div>    

<div  id="paymentBrick_container">
        </div>
        <div id="statusScreenBrick_container">
        </div>
        <div class="form-signin" id="form-pago" style="display:none;text-align: center;">
            <h1 class="h3 mb-3 font-weight-normal">Obrigado!</h1>
            <img class="mb-4"  src="<?=$url_sistema;?>painel/pagamentos/assets/check_ok.png" alt="" width="120" height="120">
            <br>
            <h5><?=$MSG_APOS_PAGAMENTO;?></h5>
            <br>
            Código do pagamento: <?php echo $_GET["id"]; ?>
        </div>
    </div>
    <style>
        body{font-family:arial}
    </style>
    <script>
        var payment_check;
        const mp = new MercadoPago('<?=$TOKEN_MERCADO_PAGO_PUBLICO;?>', {
            locale: 'pt-BR'
        });
        const bricksBuilder = mp.bricks();
        const renderPaymentBrick = async (bricksBuilder) => {
            const settings = {
                initialization: {
                    amount: '<?=$valor;?>',
                    payer: {
                        firstName: "<?=$nome;?>",
                        lastName: "<?=$sobrenome;?>",
                        email: "<?=$email;?>",
                        identification: {
                            type: '<?=(strlen($doc)>11? "CNPJ" : "CPF");?>',
                            number: '<?=$doc;?>',
                        },
                        address: {
                            zipCode: '',
                            federalUnit: '',
                            city: '',
                            neighborhood: '',
                            streetName: '',
                            streetNumber: '',
                            complement: '',
                        }
                    },
                },
                customization: {
                    visual: {
                        style: {
                            theme: "dark",
                        },
                    },
                    paymentMethods: {
                        <?php if($ATIVAR_CARTAO_CREDITO=="1"){?>creditCard: "all",<?php } ?>
                        <?php if($ATIVAR_CARTAO_DEBIDO=="1"){?>debitCard: "all",<?php } ?>
                        <?php if($ATIVAR_BOLETO=="1"){?>ticket: "all",<?php } ?>
                        <?php if($ATIVAR_PIX=="1"){?>bankTransfer: "all",<?php } ?>
                        maxInstallments: 12
                    },
                },
                callbacks: {
                    onReady: () => {
                    },
                    onSubmit: ({ selectedPaymentMethod, formData }) => {

                        formData.external_reference = '<?=$ref;?>';
                        formData.description = '<?=$descricao;?>';
                        var id_conta = '<?=$id_conta;?>';

                        return new Promise((resolve, reject) => {
                            fetch("<?=$url_sistema;?>painel/pagamentos/process_payment.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify(formData),
                            })
                            .then((response) => response.json())
                            .then((response) => {
                // receber o resultado do pagamento
                                if(response.status==true){
                                    window.location.href = "<?=$url_sistema;?>painel/pagamentos/index.php?id="+response.id+'&id_conta='+id_conta;
                                }
                                if(response.status!=true){
                                    alert(response.message);
                                }
                                resolve();
                            })
                            .catch((error) => {
                                reject();
                            });
                        });
                    },
                    onError: (error) => {
                        console.error(error);
                    },
                },
            };
            window.paymentBrickController = await bricksBuilder.create(
                "payment",
                "paymentBrick_container",
                settings
                );
        };

        const renderStatusScreenBrick = async (bricksBuilder) => {
            const settings = {
                initialization: {
                    paymentId: '<?=$_GET["id"];?>',
                },
                customization: {
                    visual: {
                        hideStatusDetails: false,
                        hideTransactionDate: false,
                        style: {
            theme: 'dark', // 'default' | 'dark' | 'bootstrap' | 'flat'
        }
    },
    backUrls: {
        //'error': '<http://<your domain>/error>',
        //'return': '<http://<your domain>/homepage>'
    }
},
callbacks: {
    onReady: () => {
        check("<?=$_GET["id"];?>", "<?=$_GET["id_conta"];?>");
    },
    onError: (error) => {
    },
},
};
window.statusScreenBrickController = await bricksBuilder.create('statusScreen', 'statusScreenBrick_container', settings);
};

<?php if($_GET["id"]!=""){ ?>
    renderStatusScreenBrick(bricksBuilder);
<?php } else { ?>
    <?php if($valor==""){?>
        alert("O valor do pagamento está vazio.");
    <?php } ?>
    renderPaymentBrick(bricksBuilder);
<?php } ?>
var redi = "<?=$URL_REDIRECIONAR;?>";
function check(id, id_conta) {
    var settings = {
        "url": "<?=$url_sistema;?>painel/pagamentos/process_payment.php?acc=check&id=" + id + "&id_conta=" + id_conta,
        "method": "GET",
        "timeout": 0
    };
    $.ajax(settings).done(function(response) {
        try {
            if (response.status == "pago") {
                $("#statusScreenBrick_container").slideUp("fast");
                $("#form-pago").slideDown("fast");
                if (redi.trim() == "Sim") {
                    setTimeout(() => {
                        //window.location = redi;
                        $("#btn_form").click();
                    }, 6000);
                }
            } else {
                setTimeout(() => {
                    check(id)
                }, 3000);
            }
        } catch (error) {
            alert("Erro ao localizar o pagamento, contacte com o suporte");
        }
    });
}
</script>
</body>
</html>