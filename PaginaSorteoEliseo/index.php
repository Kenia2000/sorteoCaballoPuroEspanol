<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorteo De Caballo Puro Español</title>
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
        <!-- Carrusel de imágenes con cambio automático cada 5 segundos -->
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="3000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="Recursos/CaballoTioEliseoNueva1.jpg" class="d-block w-100" alt="Imagen 1">
                </div>
                <div class="carousel-item">
                    <img src="Recursos/CaballoTioEliseoNueva2.jpg" class="d-block w-100" alt="Imagen 2">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        

        <!-- Botón para comprar boleto -->
        <button class="btn btn-primary btn-lg btn-block mb-4" onclick="window.location.href='tickets_available.php'">Comprar Boleto</button>

        <p class="text-center">La rifa será para ayuda de Eliseo.</p>

        <!-- Nueva imagen debajo del carrusel -->
        <div class="mb-4 text-center">
            <img src="Recursos/TioCheo.png" alt="Imagen adicional" class="img-fluid">
        </div>
        

        <h4>Tiempo restante para el sorteo</h4>
        <p id="countdown" class="font-weight-bold text-danger"></p>

        <p>Adquiere tu boleto</p>
        <button class="btn btn-primary btn-lg btn-block mb-4" onclick="window.location.href='tickets_available.php'">Comprar Boleto</button>
        
        <div id="faq" class="accordion">
            <!-- Preguntas frecuentes aquí -->
            <div class="card">
                <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#faq1">
                        ¿Cuál es el propósito de la rifa?+
                    </a>
                </div>
                <div id="faq1" class="collapse" data-parent="#faq">
                    <div class="card-body">
                        La rifa es para ayudar a Eliseo en sus tratamientos y terapias.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#faq1">
                        ¿Qué le paso a Eliseo?+
                    </a>
                </div>
                <div id="faq1" class="collapse" data-parent="#faq">
                    <div class="card-body">
                        Eliseo sufrió un derrame cerebral.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#faq1">
                        ¿Cuál es el premio?+
                    </a>
                </div>
                <div id="faq1" class="collapse" data-parent="#faq">
                    <div class="card-body">
                        Un caballo PURO ESPAÑOL con silla de montar.<br>
                        La edad de el caballo es de 4 años, nació el 1 de marzo de 2020.<br>
                        La medida del caballo a la cruz es de 1.66 m.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#faq1">
                        ¿Cómo se eligirá el ganador?+
                    </a>
                </div>
                <div id="faq1" class="collapse" data-parent="#faq">
                    <div class="card-body">
                        El ganador será el folio que coincida con las últimas 4 cifras del premio mayor de la lotería nacional.
                        (a excepción del 10,000 - 5 cifras) 
                    </div>
                </div>
            </div>
            <!-- Agrega más preguntas en el mismo formato -->
        </div>
        
    </main>




    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Temporizador de cuenta regresiva
        const countdown = document.getElementById('countdown');
        const eventDate = new Date("2024-12-31T23:59:59").getTime();  // Fecha del 31 de diciembre de 2024

        const updateCountdown = setInterval(() => {
            const now = new Date().getTime();
            const distance = eventDate - now;
            
            if (distance < 0) {
                clearInterval(updateCountdown);
                countdown.innerHTML = "SORTEO FINALIZADO, ¡MUCHA SUERTE!";
            } else {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                countdown.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }
        }, 1000);
    </script>

<footer class="bg-dark text-white text-center py-3">
    <div class="mt-5 d-flex justify-content-around">
        <!-- Icono y enlace a la página de Facebook -->
        <a href="https://www.facebook.com/share/15d5psKicx/?mibextid=LQQJ4d" target="_blank">
            <img src="Recursos/facebook.png" alt="Facebook" style="width: 40px;">
        </a>
        <a href="https://www.facebook.com/share/15d5psKicx/?mibextid=LQQJ4d" target="_blank">
            Visitar nuestra página de Facebook-<br>Sorteo de Caballo Puro Español Eliseo Corona
        </a>

        <!-- Icono de WhatsApp -->
        <a href="#" id="whatsappLink">
            <img src="Recursos/whatsapp.png" alt="WhatsApp" style="width: 40px;">
        </a>
        <a href="#" id="whatsappTextLink">
            Contáctanos en WhatsApp
        </a>
    </div>
    

    <!-- Script para elegir un número de WhatsApp aleatorio -->
    <script>
        // Lista de números de teléfono
        const phoneNumbers = [
            '+52 317 131 4785', // Número 1
        '+52 317 123 3961', // Luis 
        '+52 33 4856 5315', // Tia Chely 
        '+52 317 106 1654',  // Tio Oscar 
        '+52 317 111 8557' //Maestra 
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
</footer>


</body>
</html>
