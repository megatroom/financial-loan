<?php 


@session_start();

$id_usu = @$_SESSION['id'];  
$nivel_usu = @$_SESSION['nivel']; 
require_once("../../conexao.php");

$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];
$cliente = $_POST['cliente'];
$corretor = $_POST['corretor'];



$html = file_get_contents($url_sistema."painel/rel/lucro.php?dataInicial=$dataInicial&dataFinal=$dataFinal&id_usu=$id_usu&nivel_usu=$nivel_usu&cliente=$cliente&corretor=$corretor");

//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Transfer-Encoding: binary");
header("Content-Type: image/png");

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new DOMPDF($options);


//Definir o tamanho do papel e orientação da página
$pdf->set_paper('A4', 'portrait');

//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);

//RENDERIZAR O PDF
$pdf->render();
//NOMEAR O PDF GERADO


$pdf->stream(
	'emprestimos.pdf',
	array("Attachment" => false)
);



 ?>