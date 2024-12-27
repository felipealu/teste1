<?php
ob_start();
session_start();
include_once('./vendor/autoload.php');
include_once('config.php');
;

// $logado = $_SESSION['nome'];
if(!empty($_GET['data']))
{
    $data = $_GET['data'];
    $proxima_data = date('d/m/Y', strtotime($data . ' +1 day'));
    $hora = '08:00';


    $sql = "SELECT * FROM relatorio WHERE TIMESTAMP(saida) BETWEEN '$data 00:00:00' AND '$proxima_data $hora:00'";


    $sql = "SELECT * FROM relatorio WHERE nome LIKE '%$data%' or identificacao LIKE '%$data%' or veiculo LIKE '%$data%' or placa LIKE '%$data%' or rua LIKE '%$data%' or numero LIKE '%$data%' or si>
}
else
{
    $sql = "SELECT * FROM relatorio ORDER BY saida ASC";
}
$result = $conexao->query($sql);

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);

// Define o cabeçalho do relatório
$mpdf->SetHTMLHeader('<h2 style="text-align: center; font-size: 11pt;">Relatório de entrada e saída de visitantes da Vila Militar São Lázaro do serviço do dia ' . date('d/m/Y', strtotime($_GET['da>

// Obtém a data atual formatada
$data_atual = date('d') . ' de ' . date('F') . ' de ' . date('Y');

// Define o rodapé com a data e o número da página
$mpdf->SetHTMLFooter('
    <table style="width: 100%; font-size: 9pt; border-top: 1px solid #000;">
        <tr>
            <td style="text-align: left;">Rio de Janeiro, ' . $data_atual . '</td>
            <td style="text-align: right;">Página {PAGENO}/{nbpg}</td>
        </tr>
    </table>
');

$html_cabecalho = '<th style="background-color: #e5e5e5;">Nome</th><th style="background-color: #e5e5e5;">Identificação</th><th style="background-color: #e5e5e5;">Veículo</th><th style="background>

$html = '
<style>
    body {
        background-image: url("logo.jpeg"); /* Caminho relativo */
        background-size: 210mm 297mm;
        background-position: center;
        background-repeat: no-repeat;
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100vh;
        min-height: 297mm;
        transform: scale(1);
        transform-origin: top left;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
        white-space: nowrap; /* Evita quebra de linha */
        overflow: hidden; /* Esconde texto que ultrapassa */
        text-overflow: ellipsis; /* Adiciona "..." se o texto for longo */
        font-size: 9pt; /* Define o tamanho padrão da fonte */
    }
    th {
        background-color: #f2f2f2;
    }
    td {
        max-width: 150px; /* Ajuste a largura conforme necessário */
        word-wrap: break-word; /* Para quebra de palavra se necessário */
        vertical-align: middle; /* Alinha verticalmente */
    }
</style>

<body>
    <h2 style="text-align: center;">Relatório de entrada e saída</h2>
    <table>
        <thead>
            <tr>' . $html_cabecalho . '</tr>
        </thead>
        <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: smaller;">' . $row['nome'] . '</td>';
    $html .= '<td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: smaller;">' . $row['identificacao'] . '</td>';
    $html .= '<td>' . $row['veiculo'] . '</td>';
    $html .= '<td>' . $row['placa'] . '</td>';
    $html .= '<td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: smaller;">' . $row['rua'] . '</td>';
    $html .= '<td>' . $row['numero'] . '</td>';
    $html .= '<td>' . ($row['sit_escola'] == 1 ? 'Sim' : 'Não') . '</td>';
    $html .= '<td>' . ($row['sit_service'] == 1 ? 'Sim' : 'Não') . '</td>';
    $html .= '<td>' . date('d/m/Y H:i:s', strtotime($row['entrada'])) . '</td>';
    $html .= '<td>' . date('d/m/Y H:i:s', strtotime($row['saida'])) . '</td>';
    $html .= '</tr>';
}

$html .= '
        </tbody>
    </table>
</body>
';

$mpdf->WriteHTML($html);

$mpdf->Output('relatorio.pdf', 'I');
?>

