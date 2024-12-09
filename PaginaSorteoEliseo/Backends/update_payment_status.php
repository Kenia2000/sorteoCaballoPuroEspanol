<?php
// Cargar las variables de entorno
$supabaseUrl = getenv('SUPABASE_URL'); // Variable de entorno para la URL de Supabase
$supabaseKey = getenv('SUPABASE_KEY'); // Variable de entorno para la clave de Supabase

// Función para realizar solicitudes a la API de Supabase
function supabaseRequest($endpoint, $method = 'GET', $data = null) {
    global $supabaseUrl, $supabaseKey;

    $url = $supabaseUrl . $endpoint;
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folio']) && isset($_POST['status'])) {
    $folio = intval($_POST['folio']);
    $status = $_POST['status'];

    // Validar el estado
    if (!in_array($status, ['pagado', 'sin pagar'])) {
        echo "Estado de pago no válido.";
        exit;
    }

    // Endpoint y datos para la solicitud
    $endpoint = "/rest/v1/tickets?folio=eq.$folio";
    $data = [
        "estado_pago" => $status,
    ];

    // Realizar la solicitud PATCH para actualizar el estado de pago
    $response = supabaseRequest($endpoint, 'PATCH', $data);

    if (isset($response['error'])) {
        echo "Error al actualizar el estado de pago: " . $response['error']['message'];
    } else {
        echo "success";
    }
}
?>



