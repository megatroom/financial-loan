<?php 
include('../../conexao.php');

$id = @$_POST['id'];
$enviar = @$_POST['enviar'];

//ALIMENTAR OS DADOS NO RELATÓRIO
$html = file_get_contents($url_sistema."painel/rel/recibo.php?id=$id");


//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Transfer-Encoding: binary");
header("Content-Type: image/png");

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$pdf = new DOMPDF($options);



//Definir o tamanho do papel e orientação da página
$pdf->set_paper(array(0, 0, 320.28, 290.89));

//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);

//RENDERIZAR O PDF
$pdf->render();


$output = $pdf->output();
$arquivo = "../pdf/recibo_".$id.".pdf";
	
if(file_put_contents($arquivo,$output) <> false) {
	$pdf->stream(
	'recibo.pdf',
	array("Attachment" => false)
);

}


$query = $pdo->query("SELECT * from receber where id = '$id' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$cliente = $res[0]['cliente'];
$parcela = $res[0]['parcela'];

$query = $pdo->query("SELECT * from clientes where id = '$cliente' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$telefone = $res[0]['telefone'];

if($token != "" and $instancia != "" and $enviar == 'Sim'){
	//enviar relatório para o whatsapp
	$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
	$mensagem = 'Recibo_Parcela_'.$parcela;
	$url_envio = $url_sistema."painel/pdf/recibo_".$id.".pdf";
	require("../apis/file.php");

}


?>