<?php
// Cargar las variables de entorno
$supabaseUrl = getenv('SUPABASE_URL'); // Variable de entorno para la URL de Supabase
$supabaseKey = getenv('SUPABASE_KEY'); // Variable de entorno para la clave de Supabase

// Función para hacer solicitudes a Supabase
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

// Validación de los datos recibidos
$tickets = isset($_POST['tickets']) ? $_POST['tickets'] : '';
$whatsapp = isset($_POST['whatsapp']) ? $_POST['whatsapp'] : '';
$nombre = isset($_POST['firstName']) ? $_POST['firstName'] : '';
$apellidos = isset($_POST['lastName']) ? $_POST['lastName'] : '';
$estado = isset($_POST['state']) ? $_POST['state'] : '';

// Validar si los campos están vacíos
if (empty($tickets) || empty($whatsapp) || empty($nombre) || empty($apellidos) || empty($estado)) {
    echo json_encode(['error' => 'Todos los campos son requeridos.']);
    exit;
}

// Validar el número de WhatsApp (solo 10 dígitos numéricos)
if (!preg_match("/^[0-9]{10}$/", $whatsapp)) {
    echo json_encode(['error' => 'El número de WhatsApp debe tener 10 dígitos.']);
    exit;
}

// Asegurarse de que $tickets es un string antes de hacer explode()
if (is_array($tickets)) {
    $tickets = implode(',', $tickets); // Si es un array, convertirlo a string
}

$tickets = explode(',', $tickets); // Ahora está garantizado que $tickets es un array

// Insertar la reservación en la base de datos
$reservacionData = [
    "nombre" => $nombre,
    "apellidos" => $apellidos,
    "whatsapp" => $whatsapp,
    "estado" => $estado,
    "ticket" => implode(',', $tickets),
];

$url = $supabaseUrl . "/rest/v1/reservaciones";

// Insertar la nueva reservación en Supabase
$response = supabaseRequest($url, 'POST', $reservacionData);

if (isset($response['error'])) {
    echo json_encode(['error' => 'Hubo un problema con la reserva. Por favor, intente nuevamente.']);
    exit;
}

// Obtener el ID de la última reservación insertada
$reservacion_id = $response['id']; // Asumimos que Supabase devuelve el ID

// Actualizar los boletos como reservados y concatenar el folio con el nombre y apellidos
foreach ($tickets as $ticket) {
    $nombreCompleto = $nombre . " " . $apellidos;
    $concatenado = $ticket . " - " . $nombreCompleto;

    // Actualizar estado de los boletos en Supabase
    $url = $supabaseUrl . "/rest/v1/tickets?folio=eq.$ticket";
    $data = [
        "estado" => "reservado",
        "reservacion_id" => $reservacion_id,
    ];

    $response = supabaseRequest($url, 'PATCH', $data);

    if (isset($response['error'])) {
        echo json_encode(['error' => 'Hubo un problema al actualizar los boletos.']);
        exit;
    }
}

// Confirmar la transacción
echo json_encode(['success' => 'Reservación completada. ¡Gracias por participar en la rifa!']);
?>
