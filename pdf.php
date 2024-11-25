<?php
require_once 'tcpdf/tcpdf.php';

session_start();
include_once('config.php');
// print_r($_SESSION);
;
    
// $logado = $_SESSION['nome'];
if(!empty($_GET['data']))
{
    $data = $_GET['data'];
    $proxima_data = date('d/m/Y', strtotime($data . ' +1 day'));    
    $hora = '08:00';


    $sql = "SELECT * FROM relatorio WHERE TIMESTAMP(saida) BETWEEN '$data 00:00:00' AND '$proxima_data $hora:00'";

    
    $sql = "SELECT * FROM relatorio WHERE nome LIKE '%$data%' or identificacao LIKE '%$data%' or veiculo LIKE '%$data%' or placa LIKE '%$data%' or rua LIKE '%$data%' or numero LIKE '%$data%' or sit_escola LIKE '%$data%' or sit_service LIKE '%$data%' or entrada LIKE '%$data%' or saida LIKE '%$data%' ORDER BY saida DESC";
}
else
{
    $sql = "SELECT * FROM relatorio ORDER BY saida DESC";
}
$result = $conexao->query($sql);
    
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Seu Nome');
$pdf->SetTitle('Relatório de Registros');
$pdf->SetMargins(1, 20, 1, 20);

$pdf->SetSubject('Relatório');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Obtem a data selecionada pelo botão de gerar relatório
$data_selecionada = $_GET['data']; // ou $_GET['data_selecionada'], dependendo de como você está enviando a data

// Converte a data selecionada para o formato Y-m-d
$data_selecionada = date('Y-m-d', strtotime($data_selecionada));

// Calcula a data seguinte adicionando 1 dia à data selecionada
$data_seguinte = date('d/m/Y', strtotime($data_selecionada . ' +1 day'));

// Formata a data selecionada para o formato d/m/Y
$data_selecionada = date('d/m/Y', strtotime($data_selecionada));

// Agora você pode usar as datas selecionada e seguinte para gerar o relatório
$pdf->SetFont('times', '', 12);
$pdf->SetHeaderData('', 12, 'Relatório de entrada e saída de visitantes da Vila Militar São Lázaro do serviço do dia ' . $data_selecionada . ' para o dia ' . $data_seguinte, '', array('align' => 'C'));
$pdf->AddPage('L', 'A4');

// ... resto do código ...

while($user_data = mysqli_fetch_assoc($result)) {
    $nome_width = $pdf->GetStringWidth($user_data['nome']);
    if (!isset($max_nome_width) || $nome_width > $max_nome_width) {
        $max_nome_width = $nome_width;
    }
}

// Voltar para o início do resultado
mysqli_data_seek($result, 0);
// Definir a cor de fundo do cabeçalho
$pdf->SetFillColor(180, 180, 180); // Cinza claro

// Definir a cor do texto do cabeçalho
$pdf->SetTextColor(50, 50, 50); // Preto escuro

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('times', '', 12);
// Imprimir o cabeçalho
$pdf->Cell($max_nome_width , 10, 'Nome', 1, 0, 'C', true); // Coluna 1
$pdf->Cell(30, 10, 'Identificação', 1, 0, 'C', true); // Coluna 2
$pdf->Cell(20, 10, 'Veículo', 1, 0, 'C', true); // Coluna 3
$pdf->Cell(20, 10, 'Placa', 1, 0, 'C', true); // Coluna 4
$pdf->Cell(65, 10, 'Rua', 1, 0, 'C', true); // Coluna 5
$pdf->Cell(7, 10, 'N°', 1, 0, 'C', true); // Coluna 6
$pdf->Cell(31.5, 10, 'Entrada', 1, 0, 'C', true); // Coluna 7
$pdf->Cell(31.8, 10, 'Saída', 1, 0, 'C', true); // Coluna 8
$pdf->Cell(20, 10, 'Escola', 1, 0, 'C', true); // Coluna 9 (ao lado da saida)
$pdf->Cell(18, 10, 'Serviços', 1, 1, 'C', true); // Coluna 10 (ao lado da saida)


$pdf->SetTextColor(0,0,0);
$pdf->SetFont('times', '', 11);

while($user_data = mysqli_fetch_assoc($result)) {


    
    $pdf->Cell($max_nome_width , 10, $user_data['nome'], 1, 0, 'L',false); // Coluna 1
    $pdf->Cell(30, 10, $user_data['identificacao'], 1, 0, 'L',false); // Coluna 2
    $pdf->Cell(20, 10, $user_data['veiculo'], 1, 0, 'L',false); // Coluna 3
    $pdf->Cell(20, 10, $user_data['placa'], 1, 0, 'L',false); // Coluna 4
    $pdf->Cell(65, 10, $user_data['rua'], 1, 0, 'L',false); // Coluna 5
    $pdf->Cell(7, 10, $user_data['numero'], 1, 0, 'L',false); // Coluna 6
    $pdf->Cell(31.5, 10, date('d/m/Y H:i', strtotime($user_data['entrada'])), 1, 0, 'L'); // Coluna 7
    $pdf->Cell(31.5, 10, date('d/m/Y H:i', strtotime($user_data['saida'])), 1, 0, 'L'); // Coluna 8
    $pdf->Cell(20, 10, ($user_data['sit_escola'] == 1) ? 'Sim' : 'Não', 1, 0, 'L', false); // Coluna 9 (ao lado da saida)
    $pdf->Cell(18, 10, ($user_data['sit_service'] == 1) ? 'Sim' : 'Não', 1, 1, 'L', false); // Coluna 10 (ao lado da saida)
}

$pdf->Output('relatorio.pdf', 'I');

?>
