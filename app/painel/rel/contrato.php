<?php 
require_once("../../conexao.php");
$id = $_GET['id'];

$query = $pdo->query("SELECT * from emprestimos where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){

$valor = $res[0]['valor'];
$parcelas = $res[0]['parcelas'];
$juros_emp = $res[0]['juros_emp'];
$data_venc = $res[0]['data_venc'];
$data = $res[0]['data'];
$cliente = $res[0]['cliente'];
$juros = $res[0]['juros'];
$multa = $res[0]['multa'];
$usuario = $res[0]['usuario'];
$obs = $res[0]['obs'];
$frequencia = $res[0]['frequencia'];
$tipo_juros = $res[0]['tipo_juros'];

$data_vencF = date('d', strtotime($data_venc));
$dataF = implode('/', array_reverse(explode('-', $data)));
$valorF = number_format($valor, 2, ',', '.');
$jurosF = number_format($juros, 2, ',', '.');
$multaF = number_format($multa, 2, ',', '.');

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];
$cpf_cliente = @$res2[0]['cpf'];
$pessoa = @$res2[0]['pessoa'];

if($pessoa == "Física"){
	$tipo_pessoa = ' CPF ';
}else{
	$tipo_pessoa = ' empresa inscrita no CNPJ ';
}

$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario = @$res2[0]['nome'];


$classe_debito = '';
//verificar débito
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Não' and data_venc < curDate()");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$classe_debito = 'text-danger';
}


//verificar parcelas pagas
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$parcelas_pagas = @count($res2);



}else{
	echo 'Empréstimo não encontrado!';
	exit();
}



include('data_formatada.php');
$data_atual = $data_hoje;

 ?>

<div align="center" style="border-bottom:1px solid #000; font-size:18px; font-weight: bold">Modelo de Contrato de Empréstimo Empresa Simples de Crédito – (ESC)</div>

<!-- Excluir Abaixo -->
<p style="font-size:11px; color:red; text-align:center">Consultar um profissional que entenda sobre contratos para ver quais as melhores condições para se aplicar de acordo com sua necessidade, este é apenas um modelo de exemplo!</p>

<div style="font-size:13px">

<p>O presente contrato define as conições gerais aplicáveis ao Empréstimo, concedido pela <b> <?php echo mb_strtoupper($nome_sistema) ?></b>, na data de <?php echo $dataF ?>, <?php if($cnpj_sistema != ""){ ?>inscrita no CNPJ pelo número <?php echo $cnpj_sistema ?> doravante denominada
Mutuante <?php } ?>, e <b> <?php echo $nome_cliente ?></b>, <?php echo $tipo_pessoa ?> <?php echo $cpf_cliente ?>,
doravante denominada Mutuária, de acordo com a Lei Complementar nº 167 de 25/04/2018.
</p>
<br>
<br>

<b>CLÁUSULA PRIMEIRA: DEFINIÇÕES</b>
<p>
a) <b>TAXA DE JUROS TOTAL</b> - É a remuneração da ESC (Custo Efetivo Total do Empréstimo) – é a
nomenclatura estabelecida para denominar a remuneração da Mutuante que considera todos os
encargos e despesas incidentes na operação de empréstimo, contratada ou ofertada ao
microempreendedor individual, microempresa ou empresa de pequeno porte.</p>
<p>

b) <b>COAF</b> – é o Conselho de Controle de Atividades Financeiras.</p>
<p>c) <b>CONTRATO</b> – é o presente Contrato, as descrições do Quadro Resumo e eventuais anexos
discriminadores das garantias.</p>
<p>d) <b>CONTRATANTE MUTUÁRIA</b> – é o microempreendedor individual, microempresa ou empresa de
pequeno porte tomador do Empréstimo.</p>
<p>e) <b>IOF</b> – É o Imposto de Operação Financeira, conforme estabelecido na legislação aplicável, incidente
sobre o Empréstimo.</p>
<p>f) <b>CONTRATADA MUTUANTE</b> – é a Empresa Simples de Crédito, empresa que fornece o Empréstimo
a este contrato.</p>
<p>g) <b>DEVEDOR SOLIDÁRIO</b> – É pessoa física interveniente garantidora do empréstimo contraído pela
Contratante Mutuária.</p>
<p>h) <b>QUADRO RESUMO</b> – são as descrições exatas dos termos do Empréstimo disponibilizada a
Contratante Mutuária no momento da contratação, contendo valor solicitado, quantidade de
parcelas, datas dos vencimentos das parcelas, juros totais, datas dos vencimentos das parcelas,
total a pagar, IOF, incidente e opção das garantias acessórias.
</p>

<br>
<br>
<b>CLÁUSULA SEGUNDA: O EMPRÉSTIMO</b>

<p>a) A Contratada Mutuante concedeu a Contratante Mutuária um Empréstimo no valor mutuado e de
acordo com as demais condições indicadas no Quadro Resumo, cujo montante líquido, deduzida a
remuneração da ESC, o IOF e eventuais pendências financeiras relativas a contratos anteriores, foi
liberado por meio de crédito na conta da Contratante Mutuária.
Modelo de Contrato de Empréstimo Empresa Simples de Crédito – (ESC)</p>

<p>b) A Contratante Mutuária se obriga a restituir a Contratada Mutuante o valor total devido indicado
no Quadro Resumo, sendo que os juros do Empréstimo serão calculados de forma exponencial e
capitalizados diariamente, com base em um ano de 365 (trezentos e sessenta e cinco) dias.</p>

<p>c) Fica ajustado que qualquer tolerância por parte da Contratada Mutuante, assim como a não
exigência imediata de qualquer crédito, ou o recebimento após o vencimento, antecipado ou
tempestivo, de qualquer debito, não constituirá novação, nem modificação do ajuste, nem
qualquer precedente ou expectativa de direito da Contratada Mutuante de execução imediata.</p>
<br>
<br>

<b>CLÁUSULA TERCEIRA: TARIFAS</b>

<p>a) A Contratada Mutuante não cobrará qualquer valor a título de tarifa de originação do Empréstimo.</p>

<br>
<br>

<b>CLÁUSULA QUARTA: O PAGAMENTO DO EMPRÉSTIMO</b>

<p>a) As parcelas do empréstimo poderão ou não estar representadas por Notas Promissórias, emitidas
pela Contratante e avalizadas pelo Devedor Solidário e deverão ser quitadas nos respectivos
vencimentos, mediante opção no Quadro Resumo.</p>

<p>b) As parcelas poderão, ainda estar garantidas pela cessão Fiduciária de Direitos Creditórios, de
titularidade da Contratante Mutuária, mediante opção no Quadro Resumo, sendo que as cláusulas
da garantia fiduciária e a relação dos direitos creditórios com seus respectivos valores, devedores
e vencimentos, constarão de um anexo, especifico, parte integrante do Contrato.</p>

<p>c) Caso a data de vencimento de qualquer das parcelas indicadas no Quadro Resumo não seja Dia Útil,
o valor devido deverá ser quitado no dia útil subsequente, sem a incidência de juros moratórios.</p>

<br>
<br>

<b>CLÁUSULA QUINTA: PAGAMENTO ANTECIPADO DO EMPRÉSTIMO</b>

<p>a) O Empréstimo poderá ser pago antecipadamente para a Contratada Mutuante por opção do
Contratante Mutuante aplicando-se a redução proporcional utilizando-se a mesma taxa de juros
contratada.</p>

<br>
<br>
<b>CLÁUSULA SEXTA: ATRASO DA CONTRATANTE MUTUÁRIA</b>

<p>a) Para efeitos deste Contrato, entende-se por atraso o não pagamento no prazo e pela forma devida,
de qualquer quantia de valor da parcela devida, ou qualquer outra obrigação, contraída junto a
Contratada Mutuante em decorrência deste Contrato.</p>

<p>b) A configuração de atraso ocorrera independentemente de qualquer aviso ou notificação,
resultando do simples descumprimento das obrigações assumidas neste contrato.
Modelo de Contrato de Empréstimo Empresa Simples de Crédito – (ESC)</p>

<p>c) O atraso no pagamento de quaisquer valores devidos, vencidos e não pagos na época em que forem
exigíveis por força do disposto neste Contrato, ou nas hipóteses de vencimento antecipado da
divida adiante previste, configurará a situação de atraso, ficando a divida sujeita do vencimento ao
efetivo pagamento, aos seguintes encargos:<br>
- <b><i>Juros moratórios</i></b>, em caso de atraso, será cobrado uma taxa de <b> <?php echo $juros ?>%</b> ao dia;<br>
- <b><i>Multa moratória</i></b> de <b> R$ <?php echo $multaF ?> </b> Reais  e que incidirá sobre o valor da parcela em atraso;</p>

<br>
<br>

<b>CLÁUSULA SÉTIMA: VENCIMENTO ANTECIPADO DO EMPRÉSTIMO</b>

<p>a) No caso de apuração de falsidade, fraude ou inexatidão de qualquer declaração, informação ou
documento que houverem sido prestados pela Contratante Mutuária seus representantes legais
e/ou garantidores, ocorrerá o vencimento antecipado da totalidade do empréstimo em aberto.
CLÁUSULA OITAVA: DISPOSIÇÕES GERAIS</p>

<p>b) A Contratante Mutuária seus representantes legais e/ou garantidores declaram que todas as
informações fornecidas no momento da solicitação do Empréstimo são verdadeiras, especialmente
acerca da licitude da origem da renda e patrimônio, bem como estarem cientes das disposições
previstas na Lei nº 9.613/96 com as alterações introduzidas, inclusive pela Lei nº 12.683/12
devendo ainda informar à Contratada Mutuante sobre eventuais alterações nos dados cadastrais,
sendo de sua responsabilidade todas as consequências decorrentes do descumprimento dessa
obrigação. Além disso, a Contratada Mutuante, sempre que necessário poderá solicitar a
atualização dos dados cadastrais das partes do Contrato, inclusive garantidores e representantes
legais.</p>

<p>c) A Contratante Mutuária autoriza a Contratada Mutuante, em caráter irrevogável e irretratável e na
forma da regulamentação aplicável a (i) transmitir e consultar informações sobre o Contratante
e/ou relativas a esta operação de Empréstimos à Centrais de Risco de Crédito utilizando tais
informações, inclusive para analise de capacidade de crédito do Contratante, bem como fornecer
tais informações a terceiros que sejam contratados para prestar serviços de controle e cobrança
por quaisquer meios, das obrigações assumida pelas Contratantes Mutuária com relação a este
Contrato, (ii) levar a registro este Contrato em entidade Registradora autorizada pelo Banco
Central; e (iii) em caso de inadimplemento, inserir o nome da Contratante Mutuária e de seus
garantidores m bancos públicos ou privados de restrição cadastral.</p>

<p>d) A contratante Mutuária está ciente de que a Contratada Mutuante está sujeita a mecanismos de
controle para fins de prevenção à lavagem de dinheiro e sobre o dever de comunicação ao COAF
de operações que possam estar configuradas na Lei 9.613/98 (que dispõe sobre os crimes de
lavagem ou ocultação de bens, direitos e valores) e demais disposições legais pertinentes à matéria.</p>

<p>e) Independentemente das garantias acessórias ofertadas, o presente contrato, assinado por 2 (duas)
testemunhas é título executivo extrajudicial para a cobrança executiva das obrigações assumidas.</p>

<p>f) Fica eleito o Foro da Sede da Contratada Mutuante para resolver quaisquer questões relativas ao
presente Contrato.
Modelo de Contrato de Empréstimo Empresa Simples de Crédito – (ESC)</p>

<p>g) O presente contrato é firmado em 3 (três) vias, assinado pelas partes e testemunhas, sendo que
uma das vias é nesse ato entregue a Contratante Mutuária.
(Belo Horizonte), (<?php echo mb_strtoupper($data_atual) ?>)</p>

<br><br>

<div>
	
<br>
___________________________________________________________		
<br><b>(Nome) (CPF OU CNPJ)<br>
CONTRATANTE MUTUÁRIA</b>

<br><br><br><br>
___________________________________________________________	
<br><b>(Nome)<br>
DEVEDORES SOLIDÁRIOS</b>

<br><br><br><br>
___________________________________________________________	
<br><b>(Nome)
<br>CONTRATADA MUTUANTE<br></b>


<br><br><br><br>
<b>TESTEMUNHAS</b>

<br><br><br><br>
___________________________________________________________	
<br>Nome:<br>
<br>CPF:<br>
<br>RG:

<br><br><br><br>
___________________________________________________________	
<br>Nome:<br>
<br>CPF:<br>
<br>RG:


<br><br><br><br><br>

<?php 
//total das parcelas
$valor_total_juros = 0;
$total_parcelas = 0;
$query = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id'  order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
for($i=0; $i < $total_reg; $i++){
		$valor_p = $res[$i]['valor'];
		$total_parcelas += $valor_p;
		$valor_total_juros = $total_parcelas - $valor;
		$valor_total_jurosF = number_format($valor_total_juros, 2, ',', '.');

	}
}
 ?>

<div style="border-bottom: 1px solid #000; font-size:15px">DETALHAMENTO DO EMPRÉSTIMO</div>
<br>
<b>Valor:</b> R$ <?php echo $valorF ?> <br>
<b>Júros:</b> <?php echo $juros_emp ?>% <br>
<b>Júros pago ao final do empréstimo :</b> R$ <?php echo $valor_total_jurosF ?> <br>
<b>Data do empréstimo:</b> <?php echo $dataF ?> <br>

<?php 
$query = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id'  order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
for($i=0; $i < $total_reg; $i++){
$id_par = $res[$i]['id'];
$valor = $res[$i]['valor'];
$parcela = $res[$i]['parcela'];
$data_venc = $res[$i]['data_venc'];
$data_pgto = $res[$i]['data_pgto'];
$pago = $res[$i]['pago'];

$data_vencF = implode('/', array_reverse(explode('-', $data_venc)));
$data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));
$valorF = number_format($valor, 2, ',', '.');

echo "<br>(".$parcela.") <b>R$ ".$valorF."</b> <i>Vencimento: ".$data_vencF."</i>";

}
}
 ?>

</div>
</div>


