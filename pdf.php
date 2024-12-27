<?php
ob_start();
session_start();
include_once('./vendor/autoload.php');
include_once('config.php');

// $logado = $_SESSION['nome'];
if (!empty($_GET['data'])) {
    $data = $_GET['data'];
    $proxima_data = date('Y-m-d', strtotime($data . ' +1 day')); // Formata a data corretamente
    $hora_limite = '08:00:00';

    // Consulta SQL para considerar o intervalo até 08:00 do dia seguinte
    $sql = "SELECT * FROM relatorio 
            WHERE saida BETWEEN '$data 00:00:00' AND '$proxima_data $hora_limite'";
} else {
    $sql = "SELECT * FROM relatorio ORDER BY saida ASC";
}

$result = $conexao->query($sql);

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);

// Define o cabeçalho do relatório
$mpdf->SetHTMLHeader('<h2 style="text-align: center; font-size: 11pt;">Relatório de entrada e saída de visitantes da Vila Militar São Lázaro do serviço do dia ' . date('d/m/Y', strtotime($_GET['data'])) . '</h2>');

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

$html_cabecalho = '
    <th style="background-color: #e5e5e5; white-space: nowrap;">Nome</th>
    <th style="background-color: #e5e5e5; white-space: nowrap;">Identificação</th>
    <th style="background-color: #e5e5e5; white-space: nowrap;">Veículo</th>
    <th style="background-color: #e5e5e5; white-space: nowrap;">Placa</th>
    <th style="background-color: #e5e5e5; white-space: nowrap;">Rua</th>
    <th style="background-color: #e5e5e5; white-space: nowrap;">Número</th>
    <th style="background-color: #e5e5e5; white-space: nowrap;">Escola</th>
    <th style="background-color: #e5e5e5; white-space: nowrap;">Serviço</th>
    <th style="background-color: #e5e5e5; white-space: nowrap;">Entrada</th>
    <th style="background-color: #e5e5e5; white-space: nowrap;">Saída</th>
';

$html = '
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
        font-size: 9pt;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    th {
        background-color: #f2f2f2;
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
    // Verifica se o veículo está em branco ou nulo
    $veiculo = !empty($row['veiculo']) ? htmlspecialchars($row['veiculo']) : 'PEDESTRE';

    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($row['nome']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['identificacao']) . '</td>';
    $html .= '<td>' . $veiculo . '</td>';
    $html .= '<td>' . htmlspecialchars($row['placa']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['rua']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['numero']) . '</td>';
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
