<?php 


$pag = 'dispositivos';

if(@$dispositivos == 'ocultar')
{
	echo "<script>window.location='../index.php'</script>";
    exit();
}

require_once("../conexao.php");

$query = $pdo->prepare("SELECT * FROM dispositivos where status_api IS NOT NULL ");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_dispositivos = @count($res);

$ativo = $bloqueado = 0;

foreach($res as $dispositivo)
{
    if($dispositivo['status'] == 'Ativo'){
        $ativo++;
    }
    elseif($dispositivo['status'] == 'bloqueado'){
        $bloqueado++;
    }
}

$total = $ativo + $bloqueado;

 ?>
 
 <br>
    

<div class="main-page margin-mobile" style="margin-top: 10px">
    <div class="row">
        <div class="col-md-4">
            <button type="button" class="btn btn-primary" onclick="add()">
                <span class="fa fa-plus"></span> Adicionar Dispositivo
            </button>
        </div>
    </div>
</div>


<div class="bs-example widget-shadow" style="padding:15px; margin-top:0px" id="listar"></div>
<input type="hidden" id="ids">

<div class="modal fade" id="modaldispositivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Realize a Leitura do QRCode</h4>
                <button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
            <div class="modal-body text-center">
                <div id="qrCodeContainer" style="display: none;"></div>
                <div id="statusMessage" style="display: none;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<br>

<p style="border:1px solid #000; padding:10px">
    <i class="fa fa-info-circle text-primary"></i> <b>ATENÇÃO: </b> <span >Para conectar seu whatsapp ao sistema através dessa opção você precisa primeiro ter um plano na Menuia que é uma das empresas que prestam esse serviço, se já tem o plano basta pegar o Authkey lá e colocar ele no campo (Instancia / AuthKey) que fica nas configurações, após isso clique em + Adicionar Dispositivo e ele já tem que dar a possibilidade de fazer a leitura do QRCode para conectar seu dispositivo.</span>
</p>


<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
function add(appkey = '') {

    var authkey = '<?= $instancia_whatsapp; ?>';

     if(authkey == 'v9t7zpp51nsSCMeqqJWfI4lj8iGG12tyMqW8PwvBH3CojiUaHMV' || authkey == ''){
        alert('Insira nas configurações a sua Instancia / Authkey da Menuia!');
        return;
     }

    var total_dispositivos = '<?= $total_dispositivos; ?>';    
    if(total_dispositivos >= 1){
        alert('Você já tem 1 dispositivo conectado, remova ele para conectar outro!');
        return;
    }

    $('#modaldispositivo').modal('show');

    
    $('#statusMessage').hide();

    $.ajax({
        url: 'paginas/' + pag + '/appkey.php',
        method: 'POST',
        data: { appkey: appkey },
        dataType: 'json',
        success: function(resposta) {
            if (resposta.status == 200) 
            {
                checar_conexao(resposta.appkey, function(status) {
                    if (status == 501 || status == 404) 
                    {
                        qrCode(resposta.appkey);

                        // Chama novamente após 10 segundos
                        setTimeout(function() {
                            add(appkey);
                        }, 10000); 
                    } else if (status == 200) {
                        var qrImage = $('<img>').attr('src', 'https://chatbot.menuia.com/uploads/connected.png').css('max-width', '100%');
                        $('#qrCodeContainer').html(qrImage).show();

                        // Segunda requisição AJAX para salvar no banco de dados
                        $.ajax({
                            url: 'paginas/' + pag + '/atualizar.php',
                            method: 'POST',
                            data: { appkey: resposta.appkey },
                            dataType: 'json',
                            success: function(resultado) {
                                if (resultado.status == 200) {
                                    $('#statusMessage').html('Dispositivo Conectado!').show();
                                    setTimeout(function() {
                                        location.reload(); // Atualiza a página
                                    }, 3000); // 10000 milissegundos = 10 segundos
                                } else {
                                    $('#statusMessage').html('Dispositivo Conectado, porém não conseguiu salvar no banco de dados!').show();
                                }
                            },
                            error: function() {
                                console.log('Ocorreu um erro interno ao tentar salvar no banco de dados');
                            }
                        });
                    }
                });
            }else if(resposta.status == 404)
            {
                $('#statusMessage').html('Appkey Invalida, gere um novo dispositivo!').show();
            }
            else 
            {
                console.log('Erro');
            }
        },
        error: function() {
            console.log('Erro na requisição AJAX');
        }
    });
}



function checar_conexao(appkey, callback) {
    var authkey = '<?= $instancia_whatsapp; ?>';
    $.ajax({
        url: 'https://chatbot.menuia.com/api/developer',
        type: 'POST',
        data: {
            authkey: authkey,
            message: appkey,
            licence: 'hugocursos',
            checkDispositivo: 'true'
        },
        success: function(response) {
            $('#loadingIndicator').hide();
            if (response.status === 200) 
            {
               $.ajax({
                    url: 'paginas/' + pag + '/atualizar_numero.php',
                    method: 'POST',
                    data: { appkey: appkey, dados: response.dados},
                    dataType: 'json',
                    success: function(resultado)
                    {
                    callback(200);
                    },
                    error: function() {
                    console.log('Ocorreu um erro interno ao tentar salvar no banco de dados');
                    }
                });
                
                callback(200);
            }
            else if (response.status === 404 || response.status === 403) 
            {
                callback(404);
            } 
            else 
            {
                callback(500);
            }
        },
        error: function(error) {
            callback(501);
        }
    });
}


function qrCode(appkey)
{
     var authkey = '<?= $instancia_whatsapp; ?>';
    $.ajax({
        url: 'https://chatbot.menuia.com/api/developer', //Gerando um QRCODE
        type: 'POST',
        data: {
            authkey: authkey,
            message: appkey,
            licence: 'hugocursos',
            conecteQR: 'true'
        },
        success: function (response) 
        {
            if (response.status === 200) 
            {
                var qrCode = response.message.qr;
                var qrImage = $('<img>').attr('src', qrCode).css('max-width', '100%');
               $('#qrCodeContainer').html(qrImage).show();
            } 
            else 
            {
                console.log(response);
                $('#statusMessage').html('Erro ao carregar o QR code.').show();
            }
        },
        error: function (error) 
        {
            if(error.status === 403)
            {
                $('#loadingIndicator').hide();
                var qrImage = $('<img>').attr('src', 'https://chatbot.menuia.com/public/license.jpg').css('max-width', '70%');
                $('#qrCodeContainer').html(qrImage).show();
                $('#statusMessage').html('Sua licença se encontra ativa em outro dispositivo, faça upgrade ou entre contato com a Menuia para mais informações!').show();
                 return;
            }
            else
            {
                console.log(error);
                $('#loadingIndicator').hide();
                $('#statusMessage').html('Ocorreu um erro na requisição do Qr Code.').show();
                
            }
           
        }
        });  
}
</script>

