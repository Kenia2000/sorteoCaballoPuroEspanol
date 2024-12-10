<?php

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

// URL de la tabla tickets en Supabase para obtener boletos disponibles
$url = $supabaseUrl . "/rest/v1/tickets?estado=eq.disponible";

// Hacer la solicitud GET a Supabase para obtener los boletos disponibles
$response = supabaseRequest($url, 'GET');

// Verificar si la respuesta contiene los boletos disponibles
if (isset($response) && count($response) > 0) {
    foreach ($response as $row) {
        $folio = $row['folio'];
        echo "<button class='btn btn-success ticket-btn' onclick='selectTicket($folio, this)'>$folio</button>";
    }
} else {
    echo "<p>No hay boletos disponibles.</p>";
}
?>
