<?php include 'includes/header.php'; ?>

    <section class="donation-section">
        <div class="container">
            <h2>Apoya nuestra causa</h2>
            <p style="margin-bottom: 30px; color: #555;">Cada donación nos ayuda a seguir empoderando a mujeres vulnerables.</p>

            <div class="donation-options">
                <div class="donation-option" data-amount="10">
                    <h4>$10</h4>
                    <p>Pequeño aporte</p>
                </div>
                <div class="donation-option selected" data-amount="25">
                    <h4>$25</h4>
                    <p>Ayuda media</p>
                </div>
                <div class="donation-option" data-amount="50">
                    <h4>$50</h4>
                    <p>Gran ayuda</p>
                </div>
                <div class="donation-option" data-amount="100">
                    <h4>$100</h4>
                    <p>Cambia vidas</p>
                </div>
                <div class="donation-option" data-amount="otro">
                    <h4>Otro</h4>
                    <p>Personalizado</p>
                </div>
            </div>

            <div class="donation-form">
                <h3>Realiza tu Donación</h3>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="donation_amount">Monto de la Donación ($):</label>
                        <input type="number" id="donation_amount_input" name="donation_amount" value="25" step="0.01" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre_donante">Tu Nombre (Opcional):</label>
                        <input type="text" id="nombre_donante" name="nombre_donante" placeholder="Nombre completo">
                    </div>
                    <div class="form-group">
                        <label for="email_donante">Tu Correo Electrónico (Opcional):</label>
                        <input type="email" id="email_donante" name="email_donante" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="form-group">
                        <label for="metodo_pago">Método de Pago:</label>
                        <select id="metodo_pago" name="metodo_pago" required>
                            <option value="">Selecciona</option>
                            <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                            <option value="paypal">PayPal</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                        </select>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="anonimo_donacion" name="anonimo_donacion">
                        <label for="anonimo_donacion">Donar de forma anónima</label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Donar Ahora</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>