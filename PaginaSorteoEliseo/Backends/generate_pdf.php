<?php
// Asegúrate de que esta ruta sea correcta según tu estructura
require_once('tcpdf/tcpdf.php');

// Cargar las variables de entorno
$supabaseUrl = getenv('SUPABASE_URL'); // Variable de entorno para la URL de Supabase
$supabaseKey = getenv('SUPABASE_KEY'); // Variable de entorno para la clave de Supabase

// Cargar el SDK de Supabase a través de cURL para hacer peticiones HTTP
function supabaseRequest($url, $method = 'GET', $data = null) {
    global $supabaseUrl, $supabaseKey;

    $headers = [
        "Content-Type: application/json",
        "apikey: $supabaseKey",
        "Authorization: Bearer $supabaseKey",
    ];

    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Obtener el número de ticket
$ticketNumber = isset($_GET['ticketNumber']) ? intval($_GET['ticketNumber']) : 0;

$boletoInfo = null;

if ($ticketNumber > 0) {
    // URL de la API de Supabase para obtener la información del boleto
    $url = $supabaseUrl . "/rest/v1/tickets?folio=eq.$ticketNumber&select=folio,estado_pago,reservaciones(nombre_completo,whatsapp,estado)";

    // Hacer la solicitud GET a Supabase para obtener la información del boleto
    $response = supabaseRequest($url, 'GET');

    if (isset($response) && count($response) > 0) {
        $boletoInfo = $response[0]; // Asumimos que solo habrá un boleto con ese folio
    }
}

// Verificar si el boleto existe
if ($boletoInfo) {
    // Crear una instancia de TCPDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Rifa Caballo Tío Eliseo');
    $pdf->SetTitle('Boleto ' . $boletoInfo['folio']);
    $pdf->SetMargins(10, 10, 10);

    // Agregar una página
    $pdf->AddPage();

    // Configuración de fuentes y colores
    $pdf->SetFont('helvetica', '', 12);

    // Agregar la imagen de fondo
    $backgroundImage = 'Recursos/RIFA DE CABALLO PURO ESPAÑOL.jpg';
    if (file_exists($backgroundImage)) {
        $pdf->Image($backgroundImage, 10, 10, 190); // Ajusta los valores según sea necesario
    } else {
        $pdf->SetXY(10, 20);
        $pdf->Cell(0, 10, 'Error: No se pudo cargar la imagen de fondo.', 0, 1, 'C');
    }

    // Añadir el texto sobre la imagen
    $pdf->SetXY(167, 21.5);
    $pdf->Cell(0, 0, ' ' . $boletoInfo['folio'], 0, 1, 'L');
    $pdf->SetXY(152, 32.5);
    $pdf->Cell(0, 0, ' ' . $boletoInfo['reservaciones'][0]['nombre_completo'], 0, 1, 'L');
    $pdf->SetXY(152, 43);
    $pdf->Cell(0, 0, ' ' . $boletoInfo['reservaciones'][0]['whatsapp'], 0, 1, 'L');
    $pdf->SetXY(153, 53.5);
    $pdf->Cell(0, 0, '' . $boletoInfo['reservaciones'][0]['estado'], 0, 1, 'L');
    $pdf->SetXY(152, 73);
    $pdf->Cell(0, 0, 'Estado de pago: ' . $boletoInfo['estado_pago'], 0, 1, 'L');

    // Generar el PDF y enviarlo para visualización (en lugar de descarga)
    $fileName = 'BOLETO_' . $boletoInfo['folio'] . '.pdf';
    $pdf->Output($fileName, 'I');  // 'I' para visualizar el PDF en el navegador
} else {
    echo "Boleto no encontrado.";
}
?>
