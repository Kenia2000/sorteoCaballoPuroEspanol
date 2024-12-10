<?php   
// Cargar las variables de entorno para Supabase
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

$mensaje = "";
$boletoInfo = null;
$mostrarImagen = true; // Variable para controlar si mostramos la imagen
$boletosWhatsapp = []; // Para almacenar los boletos encontrados por WhatsApp

// Verificar si se envió un número de boleto o número de WhatsApp
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se ingresó un número de boleto
    if (isset($_POST['ticketNumber']) && !empty($_POST['ticketNumber'])) {
        $ticketNumber = intval($_POST['ticketNumber']);

        // Validar que el folio esté entre 1 y 10,000
        if ($ticketNumber < 1 || $ticketNumber > 10000) {
            $mensaje = "El número de folio debe estar entre 1 y 10,000.";
        } else {
            // Solicitud a Supabase para verificar el folio, nombre, apellidos y otros datos
            $endpoint = "/rest/v1/tickets?folio=eq.$ticketNumber";
            $ticketData = supabaseRequest($endpoint);

            if (isset($ticketData[0])) {
                $boletoInfo = $ticketData[0];

                // Verificar si el estado en la tabla `tickets` es "reservado" y el estado de pago es "sin pagar"
                if ($boletoInfo['estado'] === 'reservado' && $boletoInfo['estado_pago'] === 'sin pagar') {
                    $mensaje = "No se ha realizado el pago del boleto o nuestros administradores no han cambiado su estado de pago. Recuerde que el plazo límite para que su pago se procese es de 24 horas. Si el problema persiste comuníquese a nuestros contactos que aparecen al final de la página.";
                    $mostrarImagen = false; // No mostrar la imagen si está sin pagar
                }
            } else {
                $mensaje = "El boleto no está reservado o no existe.";
                $mostrarImagen = false; // No mostrar la imagen si no existe
            }
        }
    } 

    // Verificar si se ingresó un número de WhatsApp
    elseif (isset($_POST['whatsapp']) && !empty($_POST['whatsapp'])) {
        $whatsapp = $_POST['whatsapp'];
        
        // Validar WhatsApp (solo números y longitud)
        if (!preg_match('/^[0-9]{10}$/', $whatsapp)) {
            $mensaje = "El número de WhatsApp no es válido.";
        } else {
            // Solicitud a Supabase para obtener todos los boletos asociados a un número de WhatsApp
            $endpoint = "/rest/v1/tickets?whatsapp=eq.$whatsapp&estado_pago=eq.pagado";
            $ticketData = supabaseRequest($endpoint);

            if (!empty($ticketData)) {
                // Guardar los boletos encontrados
                foreach ($ticketData as $row) {
                    $boletosWhatsapp[] = $row;
                }
            } else {
                $mensaje = "No se encontraron boletos con ese número de WhatsApp o no están pagados.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Boleto - Rifa de Caballo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .boleto-container {
            position: relative;
            width: 100%;
            max-width: 700px;
            margin: 20px auto;
        }
        .boleto-container img {
            width: 100%;
        }
        .boleto-text {
            position: absolute;
            color: black;
            font-weight: bold;
            font-size: 18px;
            text-align: left; /* Alineado a la izquierda */
            line-height: 1.8;
        }
        .folio { top: 35px; left: 580px; }
        .nombre { top: 76px; left: 530px; }
        .whatsapp { top: 115px; left: 530px; }
        .estado { top: 154px; left: 530px; }
        .estado_pago {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .thank-you-message {
            font-size: 18px;
            font-weight: bold;
            color: green;
            margin-top: 20px;
        }
        table th, table td {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="container-fluid bg-dark text-white text-center p-3 d-flex align-items-center justify-content-between">
        <button class="btn btn-light" onclick="window.location.href='index.php'">Página Principal</button>
        <img src="Recursos/LogoRifaCaballoCheo.png" alt="Logo de la Rifa" style="height: 120px;">
        <button class="btn btn-light" onclick="window.location.href='tickets_available.php'">Boletos Disponibles</button>
    </header>

    <!-- Body -->
    <main class="container mt-5">
        <h2 class="text-center">Verificación de Boleto</h2>

        <!-- Formulario para ingresar número de boleto o WhatsApp -->
        <form method="POST" class="text-center mt-4">
            <div class="form-group">
                <label for="ticketNumber">Número de Boleto</label>
                <input type="number" name="ticketNumber" id="ticketNumber" class="form-control mx-auto" style="max-width: 200px;">
            </div>
            <div class="form-group">
                <label for="whatsapp">Número de WhatsApp</label>
                <input type="text" name="whatsapp" id="whatsapp" class="form-control mx-auto" style="max-width: 200px;">
            </div>
            <button type="submit" class="btn btn-primary">Verificar Boleto</button>
        </form>

        <!-- Mensaje o Imagen del Boleto -->
        <div class="mt-5 text-center">
            <!-- Mostrar mensaje de error o boleto -->
            <?php if (!empty($boletoInfo) && $mostrarImagen): ?>
                <div class="boleto-container">
                    <img src="Recursos/RIFA DE CABALLO PURO ESPAÑOL.jpg" alt="Boleto">
                    <div class="boleto-text folio"><?php echo htmlspecialchars($boletoInfo['folio']); ?></div>
                    <div class="boleto-text nombre"><?php echo htmlspecialchars($boletoInfo['nombre_completo']); ?></div>
                    <div class="boleto-text whatsapp"><?php echo htmlspecialchars($boletoInfo['whatsapp']); ?></div>
                    <div class="boleto-text estado"><?php echo htmlspecialchars($boletoInfo['reservacion_estado']); ?></div>
                </div>
                <div class="thank-you-message">
                    Gracias por participar en este sorteo con causa, ¡Te deseamos mucha suerte!<br>
                    Si requieres más boletos da click a <a href="tickets_available.php">Boletos Disponibles</a>.
                </div>
                <!-- Solo el botón de "Descargar" -->
                <a href="generate_pdf.php?ticketNumber=<?php echo $boletoInfo['folio']; ?>" class="btn btn-success mt-3" download>
                    Descargar Boleto
                </a>
            <?php elseif (!empty($boletosWhatsapp)): ?>
                <p>Boletos asociados al WhatsApp:</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Nombre</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($boletosWhatsapp as $boleto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($boleto['folio']); ?></td>
                                <td><?php echo htmlspecialchars($boleto['nombre_completo']); ?></td>
                                <td><a href="generate_pdf.php?ticketNumber=<?php echo $boleto['folio']; ?>" class="btn btn-success" download>Descargar</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-danger"><?php echo htmlspecialchars($mensaje); ?></p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>&copy; 2024 Rifa Caballo. Todos los derechos reservados.</p>
    </footer>

    <!-- JS para Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

