<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métodos de Pago</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .fade-button:hover {
            opacity: 0.8;
            transition: opacity 0.5s;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="container-fluid bg-dark text-white text-center p-3 d-flex justify-content-between align-items-center">
        <button class="btn btn-light fade-button" onclick="window.location.href='ticket_verification.php'">Verificador de Boletos</button>
        <img src="Recursos/LogoRifaCaballoCheo.png" alt="Logo de la Rifa" style="height: 120px;">
        <button class="btn btn-light fade-button" onclick="window.location.href='tickets_available.php'">Boletos Disponibles</button>
    </header>

    <!-- Body -->
    <main class="container mt-5">
        
        <!-- Sección de Imágenes y Texto -->
        <div class="mb-4">
            <img src="Recursos/metodo_pago1.png" alt="" class="img-fluid mb-3">
            <img src="Recursos/metodo_pago2.png" alt="" class="img-fluid mb-3">
        </div>
        
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <div class="mt-5 d-flex justify-content-around">
            <a href="https://www.facebook.com/Eliseo_Corona" target="_blank">
                <img src="Recursos/facebook.png" alt="Facebook" style="width: 40px;">
            </a>
            <a href="https://www.facebook.com/Eliseo_Corona" target="_blank">
                Visitar nuestra página de Facebook
            </a>

            <a href="#" id="whatsappLink">
                <img src="Recursos/whatsapp.png" alt="WhatsApp" style="width: 40px;">
            </a>
            <a href="#" id="whatsappTextLink">
                Contáctanos en WhatsApp
            </a>
        </div>
    </footer>

    <!-- Script para elegir un número de WhatsApp aleatorio -->
    <script>
        // Lista de números de teléfono
        const phoneNumbers = [
            '+52 317 131 4785', // Número 1
            '+52 317 123 3961', // Luis 
            '+52 33 4856 5315', // Tia Chely 
            '+52 317 106 1654',  // Tio Oscar 
            '+52 317 111 8557' // Maestra 
        ];

        // Generar un número aleatorio
        const randomPhoneNumber = phoneNumbers[Math.floor(Math.random() * phoneNumbers.length)];

        // Mensaje predeterminado
        const message = 'Hola, quiero más información sobre la rifa';

        // Construir el enlace de WhatsApp
        const whatsappLink = `https://wa.me/${randomPhoneNumber}?text=${encodeURIComponent(message)}`;

        // Asignar el enlace al elemento del DOM
        document.getElementById('whatsappLink').setAttribute('href', whatsappLink);
        document.getElementById('whatsappTextLink').setAttribute('href', whatsappLink);
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
