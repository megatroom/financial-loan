<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$nome_usuario  = $_SESSION['nome'];

$tabela = 'dispositivos';

require_once("../../../conexao.php");

$data_atual = date('Y-m-d');

$dataAtual = date("Y-m-d");
$query = $pdo->prepare("SELECT * FROM $tabela where status_api IS NOT NULL");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);


if($res)
{

echo <<<HTML
<small>
<table class="table table-hover text-center">
     <thead class="thead-light">
        <tr>
            <th scope="col" class="esc" style="padding-left: 50px !important;">ID</th>
            <th scope="col" class="esc" style="padding-left: 50px !important;">Telefone</th>
            <th scope="col" class="esc" style="padding-left: 190px !important; ">Appkey</th>
            <th scope="col" class="esc" style="padding-left: 100px !important;">Status</th>
            <th scope="col" class="esc" style="padding-left: 120px !important; p">Ações</th>
        </tr>
    </thead>
<tbody>
HTML;


$i = 0;
foreach($res as $dispositivos)
{
$i++;
$id = $dispositivos['id'];
$appkey = $dispositivos['appkey'];
$status = $dispositivos['status'];
$telefone = $dispositivos['telefone'] ?? '';
$nucleo = $dispositivos['nucleo'] ?? '';


?>

<tr style="" class="tabelaResultados">
    <td class="esc"><?= $i; ?></td>
    <td class="esc"><?= $telefone; ?></td>
    <td class="esc"><?= $appkey;?></td>
    <td class="esc"><?=$status;?></td>
    <td class="esc">
    	
            
    <li class="dropdown head-dpdn2" style="display: inline-block;">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

        <ul class="dropdown-menu" style="margin-left:-230px;">
        <li>
        <div class="notification_desc2">
        <p>Confirmar Exclusão? <a href="#" onclick="excluir('<?= $id; ?>')"><span class="text-danger">Sim</span></a></p>
        </div>
        </li>                                       
        </ul>
</li>

        <big>
            <a href="#" onclick="add('<?= $appkey; ?>'); $('#modaldispositivo').modal('show');" title="Reconectar Dispositivo">
                <i class="fa fa-wifi" style="color:#3d1002"></i>
            </a>
        </big>

    </td>
</tr>



<?php



}





echo <<<HTML

</tbody>

<small><div align="center" id="mensagem-excluir"></div></small>

</table>

<br>

<div align="right">


</div>

HTML;



}else{

	echo '<small>Nenhum Registro Encontrado!</small>';

}

?>

<script>

   function alterarStatus(status, id = '')
   {
        
        if (status !== 'bloqueado') 
        {
            $.ajax({
                url: 'paginas/' + pag + '/status.php', // Verifique se 'pag' está definido corretamente
                method: 'POST',
                data: { status: status, id: id }, // Enviar status e id como objeto
                dataType: 'text',
                success: function(mensagem) {
                    listar();
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error);
                }
            });
        } else {
            $.ajax({
                url: 'paginas/' + pag + '/excluir.php', // Verifique se 'pag' está definido corretamente
                method: 'POST',
                data: { id: id }, // Apenas enviar id para a exclusão
                dataType: 'text',
                success: function(mensagem) {
                    listar();
                    location.reload();

                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error);
                }
            });
        }
    }

</script>






