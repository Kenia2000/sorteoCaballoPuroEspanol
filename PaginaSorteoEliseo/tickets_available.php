<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletos Disponibles - Rifa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .ticket-btn {
            margin: 2px;
            width: 60px;
            height: 40px;
            background-color: transparent;
            border: 2px solid blue;
            color: blue;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .ticket-btn:hover {
            background-color: green;
            color: white;
            cursor: pointer;
        }

        .selected {
            background-color: green !important;
            color: white !important;
            border-color: green !important;
        }

        .ticket-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 5px;
            max-height: 500px;
            overflow-y: auto;
        }

        #reserveBtn {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: none;
        }

        .state-select {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .state-search {
            width: 200px;
        }

        .state-dropdown {
            width: 300px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="container-fluid bg-dark text-white text-center p-3 d-flex align-items-center justify-content-between">
        <button class="btn btn-light" onclick="window.location.href='payment_methods.php'">Métodos de Pago</button>
        <img src="Recursos/LogoRifaCaballoCheo.png" alt="Logo de la Rifa" style="height: 120px;">
        <button class="btn btn-light" onclick="window.location.href='ticket_verification.php'">Verificador de Boletos</button>
    </header>

    <!-- Main Content -->
    <main class="container mt-4">
        <h4 class="text-center mb-4">Seleccione sus boletos</h4>

        <!-- Tabla de botones de boletos -->
        <div class="ticket-grid my-4" id="ticketGrid">
            <!-- Los boletos disponibles serán agregados aquí dinámicamente -->
        </div>

        <!-- Botón para apartar los boletos -->
        <button class="btn btn-warning" id="reserveBtn" onclick="showReserveModal()">Apartar Boletos</button>

        <!-- Modal para apartar boletos -->
        <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reserveModalLabel">Apartar Boletos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Boletos seleccionados: <span id="selectedTicketsList"></span></p>
                        <form id="reserveForm">
                            <div class="form-group">
                                <label for="whatsapp">Número de WhatsApp:</label>
                                <input type="text" class="form-control" id="whatsapp" required pattern="[0-9]{10}" title="Ingrese un número válido de 10 dígitos">
                            </div>
                            <div class="form-group">
                                <label for="firstName">Nombre:</label>
                                <input type="text" class="form-control" id="firstName" required onblur="capitalizeFirstLetter('firstName')">
                            </div>
                            <div class="form-group">
                                <label for="lastName">Apellidos:</label>
                                <input type="text" class="form-control" id="lastName" required onblur="capitalizeFirstLetter('lastName')">
                            </div>
                            <div class="form-group">
                                <label>Si eres de Estados Unidos, elige como estado: <strong>Estados Unidos</strong></label>
                            </div>
                            <div class="form-group state-select">
                                <input type="text" class="form-control state-search" id="stateSearch" placeholder="Buscar estado">
                                <select class="form-control state-dropdown" id="state" required size="10">
                                    <option value="">Seleccione su estado</option>
                                    <!-- Lista de estados de México -->
                                    <option value="Aguascalientes">Aguascalientes</option>
                                    <option value="Baja California">Baja California</option>
                                    <option value="Baja California Sur">Baja California Sur</option>
                                    <option value="Campeche">Campeche</option>
                                    <option value="Chiapas">Chiapas</option>
                                    <option value="Chihuahua">Chihuahua</option>
                                    <option value="Coahuila">Coahuila</option>
                                    <option value="Colima">Colima</option>
                                    <option value="Durango">Durango</option>
                                    <option value="Guanajuato">Guanajuato</option>
                                    <option value="Guerrero">Guerrero</option>
                                    <option value="Hidalgo">Hidalgo</option>
                                    <option value="Jalisco">Jalisco</option>
                                    <option value="Mexico">México</option>
                                    <option value="Michoacán">Michoacán</option>
                                    <option value="Morelos">Morelos</option>
                                    <option value="Nayarit">Nayarit</option>
                                    <option value="Nuevo León">Nuevo León</option>
                                    <option value="Oaxaca">Oaxaca</option>
                                    <option value="Puebla">Puebla</option>
                                    <option value="Querétaro">Querétaro</option>
                                    <option value="Quintana Roo">Quintana Roo</option>
                                    <option value="San Luis Potosí">San Luis Potosí</option>
                                    <option value="Sinaloa">Sinaloa</option>
                                    <option value="Sonora">Sonora</option>
                                    <option value="Tabasco">Tabasco</option>
                                    <option value="Tamaulipas">Tamaulipas</option>
                                    <option value="Tlaxcala">Tlaxcala</option>
                                    <option value="Veracruz">Veracruz</option>
                                    <option value="Yucatán">Yucatán</option>
                                    <option value="Zacatecas">Zacatecas</option>
                                    <option value="Estados Unidos">Estados Unidos</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="validateConfirmation()">Confirmar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal de confirmación de datos -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Verifique sus datos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Estás de acuerdo con la información ingresada:</p>
                        <ul>
                            <li><strong>Boletos seleccionados:</strong> <span id="confirmSelectedTickets"></span></li>
                            <li><strong>Número de WhatsApp:</strong> <span id="confirmWhatsapp"></span></li>
                            <li><strong>Nombre:</strong> <span id="confirmFirstName"></span></li>
                            <li><strong>Apellidos:</strong> <span id="confirmLastName"></span></li>
                            <li><strong>Estado:</strong> <span id="confirmState"></span></li>
                        </ul>
                        <p>Si deseas hacer una modificación en tus datos, da click en "Cancelar" y modifica tus datos.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="returnToForm()">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="submitReservationFinal()">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de reserva -->
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Reserva Exitosa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="modalMessage">Su reserva ha sido registrada con éxito. Recuerde que el límite para procesar el pago de su boleto es de 24 horas. Si no realiza el pago, su boleto ya no será apartado.</p>
                        <a id="whatsappLink" href="#" target="_blank" class="btn btn-success">Enviar mensaje por WhatsApp para reservar boleto</a>
                    </div>
                </div>
            </div>
        </div>

    <footer class="bg-dark text-white text-center py-3">
        <div class="mt-5 d-flex justify-content-around">
            <img src="Recursos/facebook.png" alt="Facebook" style="width: 40px;">
        </a>
        <a href="https://www.facebook.com/share/15d5psKicx/?mibextid=LQQJ4d" target="_blank">
            Visitar nuestra página de Facebook-<br>Sorteo de Caballo Puro Español Eliseo Corona
        </a>

        <img src="Recursos/whatsapp.png" alt="WhatsApp" style="width: 40px;">
        </a>
        <a href="#" id="whatsappTextLink">
            Contáctanos en WhatsApp
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>

    <script>
        const supabase = supabase.createClient(
    'https://laggvqixpvltchyrflnu.supabase.co', // Reemplaza esto con tu URL de Supabase
    'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImxhZ2d2cWl4cHZsdGNoeXJmbG51Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzIwMzMzMTMsImV4cCI6MjA0NzYwOTMxM30.6TX04NoDpcVO0xr4xhdn6V91PuGZbINkENqF5LHQO74'     // Reemplaza esto con tu clave pública de Supabase
);
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

        let selectedTickets = [];

        // Fetch available tickets from Supabase
        async function fetchAvailableTickets() {
            try {
                const { data, error } = await supabase
                    .from('tickets')
                    .select('*')
                    .eq('status', 'available');

                if (error) {
                    alert("Error al cargar los boletos disponibles.");
                    return;
                }

                let ticketGridHTML = '';
                data.forEach(ticket => {
                    ticketGridHTML += `<button class="ticket-btn" onclick="selectTicket(${ticket.id}, this)">${ticket.number}</button>`;
                });

                $('#ticketGrid').html(ticketGridHTML);
            } catch (error) {
                alert("Error al cargar los boletos.");
            }
        }

        $(document).ready(function() {
            fetchAvailableTickets();
            $('#stateSearch').on('input', function() {
                var search = $(this).val().toLowerCase();
                $('#state option').each(function() {
                    if ($(this).text().toLowerCase().indexOf(search) === -1) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
        });

        // Seleccionar boleto
        function selectTicket(ticketId, button) {
            const ticketIndex = selectedTickets.indexOf(ticketId);

            if (ticketIndex === -1) {
                selectedTickets.push(ticketId);
                $(button).addClass('selected');
            } else {
                selectedTickets.splice(ticketIndex, 1);
                $(button).removeClass('selected');
            }

            $('#reserveBtn').toggle(selectedTickets.length > 0);
        }

        // Mostrar el modal de confirmación
        function showReserveModal() {
            const ticketList = selectedTickets.join(', ');
            $('#selectedTicketsList').text(ticketList);
            $('#reserveModal').modal('show');
        }

        // Validar y mostrar la confirmación
        function validateConfirmation() {
            const whatsapp = $('#whatsapp').val();
            const firstName = $('#firstName').val();
            const lastName = $('#lastName').val();
            const state = $('#state').val();

            if (!whatsapp || !firstName || !lastName || !state) {
                alert("Por favor, complete todos los campos.");
                return;
            }

            $('#confirmSelectedTickets').text(selectedTickets.join(', '));
            $('#confirmWhatsapp').text(whatsapp);
            $('#confirmFirstName').text(firstName);
            $('#confirmLastName').text(lastName);
            $('#confirmState').text(state);

            $('#reserveModal').modal('hide');
            $('#confirmationModal').modal('show');
        }

        // Volver al formulario
        function returnToForm() {
            $('#confirmationModal').modal('hide');
            $('#reserveModal').modal('show');
        }

        // Confirmar la reserva
        async function submitReservationFinal() {
            const whatsapp = $('#whatsapp').val();
            const firstName = $('#firstName').val();
            const lastName = $('#lastName').val();
            const state = $('#state').val();

            try {
                const { data, error } = await supabase
                    .from('reservations')
                    .insert([
                        {
                            tickets: selectedTickets.join(','),
                            whatsapp,
                            first_name: firstName,
                            last_name: lastName,
                            state,
                            reserved_at: new Date(),
                        }
                    ]);

                if (error) {
                    alert("Error al guardar la reserva.");
                    return;
                }

                $('#confirmationModal').modal('hide');
                $('#paymentModal').modal('show');
                $('#whatsappLink').attr('href', `https://wa.me/${whatsapp}?text=Estoy%20interesado%20en%20completar%20mi%20reserva%20de%20boletos.`);
            } catch (error) {
                alert("Error al procesar la reserva.");
            }
        }
    </script>
</body>
</html>
