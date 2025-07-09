<?php
include 'includes\session_handler.php'; 
include 'includes\header.php';
include 'includes\db_config.php';

if(!isUserLoggedIn()){
    redirectToLogin();
}

$message = '';
$donation_amount_val = '25';
$nombre_donante_val = '';
$email_donante_val = '';
$metodo_pago_val = '';
$anonimo_donacion_val = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Conn();
    $conn = $db->Conexion();

    $donation_amount = $_POST['donation_amount'] ?? '';
    $nombre_donante = $_POST['nombre_donante'] ?? '';
    $email_donante = $_POST['email_donante'] ?? '';
    $metodo_pago = $_POST['metodo_pago'] ?? '';
    $anonimo_donacion = isset($_POST['anonimo_donacion']) ? 1 : 0;

    $donation_amount_val = htmlspecialchars($donation_amount);
    $nombre_donante_val = htmlspecialchars($nombre_donante);
    $email_donante_val = htmlspecialchars($email_donante);
    $metodo_pago_val = htmlspecialchars($metodo_pago);
    $anonimo_donacion_val = $anonimo_donacion;

    if (empty($donation_amount) || !is_numeric($donation_amount) || (float)$donation_amount <= 0) {
        $message = '<p class="error-message">Por favor, introduce un monto de donación válido y positivo.</p>';
    } elseif (empty($metodo_pago)) {
        $message = '<p class="error-message">Por favor, selecciona un método de pago.</p>';
    } else {
        try {
            $sql = "INSERT INTO donaciones (monto_dona, nomb_dona, email_dona, metodoP_dona, anoni_dona, confirP_dona)
                    VALUES (:monto_dona, :nomb_dona, :email_dona, :metodoP_dona, :anoni_dona, :confirP_dona)";
            
            $stmt = $conn->prepare($sql);

            $param_name = ($anonimo_donacion == 1) ? NULL : (!empty($nombre_donante) ? $nombre_donante : NULL);
            $param_email = ($anonimo_donacion == 1) ? NULL : (!empty($email_donante) ? $email_donante : NULL);
            

            $stmt->bindParam(':monto_dona', $donation_amount, PDO::PARAM_STR);
            $stmt->bindParam(':nomb_dona', $param_name, PDO::PARAM_STR);
            $stmt->bindParam(':email_dona', $param_email, PDO::PARAM_STR);
            $stmt->bindParam(':metodoP_dona', $metodo_pago, PDO::PARAM_STR);
            $stmt->bindParam(':anoni_dona', $anonimo_donacion, PDO::PARAM_INT);
            $stmt->bindValue(':confirP_dona', 'Pendiente', PDO::PARAM_STR);

            $stmt->execute();

            $message = '<p class="success-message">¡Gracias por tu donación! Tu donación ha sido registrada exitosamente.</p>';

            $donation_amount_val = '25';
            $nombre_donante_val = '';
            $email_donante_val = '';
            $metodo_pago_val = '';
            $anonimo_donacion_val = 1;

        } catch (PDOException $e) {
            $errorInfo = $e->errorInfo;
            $detailedMessage = "SQLSTATE: " . ($errorInfo[0] ?? 'N/A') . "<br>";
            $detailedMessage .= "Code: " . ($errorInfo[1] ?? 'N/A') . "<br>";
            $detailedMessage .= "Message: " . ($errorInfo[2] ?? $e->getMessage());

            $message = '<p class="error-message">Error al registrar la donación: <br>' . $detailedMessage . '</p>';
        } finally {
            $conn = null;
        }
    }
}
?>

<section class="donation-section">
    <div class="container">
        <h2>Apoya nuestra causa</h2>
        <p style="margin-bottom: 30px; color: #555;">Cada donación nos ayuda a seguir empoderando a mujeres vulnerables.</p>

        <div class="donation-options">
            <div class="donation-option" data-amount="10">
                <h4>$10</h4>
                <p>Pequeño aporte</p>
            </div>
            <div class="donation-option" data-amount="25">
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
            <?php echo $message; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="donation_amount_input">Monto de la Donación ($):</label>
                    <input type="number" id="donation_amount_input" name="donation_amount" value="<?php echo $donation_amount_val; ?>" step="0.01" min="1" required>
                </div>
                <div class="form-group">
                    <label for="nombre_donante">Tu Nombre (Opcional):</label>
                    <input type="text" id="nombre_donante" name="nombre_donante" placeholder="Nombre completo" value="<?php echo $nombre_donante_val; ?>">
                </div>
                <div class="form-group">
                    <label for="email_donante">Tu Correo Electrónico (Opcional):</label>
                    <input type="email" id="email_donante" name="email_donante" placeholder="correo@ejemplo.com" value="<?php echo $email_donante_val; ?>">
                </div>
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago:</label>
                    <select id="metodo_pago" name="metodo_pago" required>
                        <option value="">Selecciona</option>
                        <option value="tarjeta" <?php echo ($metodo_pago_val == 'tarjeta') ? 'selected' : ''; ?>>Tarjeta de Crédito/Débito</option>
                        <option value="paypal" <?php echo ($metodo_pago_val == 'paypal') ? 'selected' : ''; ?>>PayPal</option>
                        <option value="transferencia" <?php echo ($metodo_pago_val == 'transferencia') ? 'selected' : ''; ?>>Transferencia Bancaria</option>
                    </select>
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="anonimo_donacion" name="anonimo_donacion" <?php echo ($anonimo_donacion_val == 1) ? 'checked' : ''; ?>>
                    <label for="anonimo_donacion">Donar de forma anónima</label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Donar Ahora</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes\footer.php'; ?>

<style>
    .success-message {
        color: green;
        background-color: #e6ffe6;
        border: 1px solid green;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .error-message {
        color: red;
        background-color: #ffe6e6;
        border: 1px solid red;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
</style>