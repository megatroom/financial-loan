<?php 
require_once("../../conexao.php");

$filtro_data = $_POST['filtro_data'];
$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];
$filtro_tipo = urlencode($_POST['filtro_tipo']);
$filtro_lancamento = urlencode($_POST['filtro_lancamento']);
$filtro_pendentes = $_POST['filtro_pendentes'];

$html = file_get_contents($url_sistema."painel/rel/financeiro.php?filtro_data=$filtro_data&dataInicial=$dataInicial&dataFinal=$dataFinal&filtro_tipo=$filtro_tipo&filtro_lancamento=$filtro_lancamento&filtro_pendentes=$filtro_pendentes");

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
	'financeiro.pdf',
	array("Attachment" => false)
);



 ?>