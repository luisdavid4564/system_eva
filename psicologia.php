<?php
include 'includes/session_handler.php'; 
include 'includes/header.php';
include 'includes/db_config.php'; 

if (!isUserLoggedIn()) {
    redirectToLogin();
}

$success_message = '';
$error_message = '';


$id_usu = $_SESSION['id_usu'] ?? null; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Conn();
    $conn = $db->Conexion();

    $nombre_cita = htmlspecialchars(trim($_POST['nombre_cita']));
    $profesional_id = intval($_POST['profesional_cita']); 
    $fecha_cita = htmlspecialchars(trim($_POST['fecha_cita']));
    $hora_cita = htmlspecialchars(trim($_POST['hora_cita']));
    $tipo_cita = htmlspecialchars(trim($_POST['tipo_cita']));
    $motivo_cita = htmlspecialchars(trim($_POST['motivo_cita']));

   
    if (empty($nombre_cita) || empty($profesional_id) || empty($fecha_cita) || empty($hora_cita) || empty($tipo_cita)) {
        $error_message = "Todos los campos obligatorios deben ser llenados.";
    } elseif ($id_usu === null) {
        $error_message = "No se pudo identificar al usuario. Por favor, inicia sesión de nuevo.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO citas (id_usu, nom_cita, id_profe, fechaH_cita, hora_cita, tipo_cita, motivo_cita)
                                    VALUES (:id_usu, :nom_cita, :id_profe, :fechaH_cita, :hora_cita, :tipo_cita, :motivo_cita)");

            $stmt->bindParam(':id_usu', $id_usu, PDO::PARAM_INT);
            $stmt->bindParam(':nom_cita', $nombre_cita);
            $stmt->bindParam(':id_profe', $profesional_id, PDO::PARAM_INT);
            $stmt->bindParam(':fechaH_cita', $fecha_cita);
            $stmt->bindParam(':hora_cita', $hora_cita);
            $stmt->bindParam(':tipo_cita', $tipo_cita);
            $stmt->bindParam(':motivo_cita', $motivo_cita);

            if ($stmt->execute()) {
                $success_message = "¡Cita solicitada con éxito! Nos pondremos en contacto contigo pronto.";
            } else {
                $error_message = "Hubo un error al solicitar la cita. Inténtalo de nuevo.";
            }
        } catch (PDOException $e) {
            $error_message = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<section class="form-section">
    <div class="container">
        <h2>Recibe orientación gratuita y confidencial.</h2>
        <p style="text-align: center; margin-bottom: 40px; color: #555;">Estamos contigo para ayudarte a superar el miedo, de forma segura y confidencial.</p>

        <div class="professional-grid">
            <div class="professional-card">
                <img src="img\👩🏽_⚕️🤍_ig_ @carolatanasio_.jpg" alt="Psicóloga Ana Pérez" class="avatar">
                <h3>Psi. Ana Pérez</h3>
                <p>Psicóloga Clínica</p>
                <p>Especialista en trauma y ansiedad.</p>
                <a href="#" class="btn btn-primary">Reservar Cita</a>
            </div>
            <div class="professional-card">
                <img src="img\5 CONSEJOS PARA UNA EXPANSIÓN EFECTIVA A NUEVOS MERCADOS.jpg" alt="Psicólogo Juan Soto" class="avatar">
                <h3>Lic. Juan Soto</h3>
                <p>Psicólogo Forense</p>
                <p>Especializado en apoyo a víctimas de violencia.</p>
                <a href="#" class="btn btn-primary">Reservar Cita</a>
            </div>
            <div class="professional-card">
                <img src="img\descarga.jpg" alt="Psicóloga María Vega" class="avatar">
                <h3>Psi. María Vega</h3>
                <p>Terapeuta Familiar</p>
                <p>Ayuda en la reconstrucción de lazos familiares.</p>
                <a href="#" class="btn btn-primary">Reservar Cita</a>
            </div>
        </div>

        <div class="form-container" style="margin-top: 50px;">
            <h2>Solicitar Cita de Psicología</h2>

            <?php if (!empty($success_message)): ?>
                <p style="color: green; text-align: center; margin-bottom: 15px;"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="" method="POST"> <div class="form-group">
                    <label for="nombre_cita">Tu Nombre:</label>
                    <input type="text" id="nombre_cita" name="nombre_cita" placeholder="Nombre" required
                           value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="profesional_cita">Psicólogo Preferido:</label>
                    <select id="profesional_cita" name="profesional_cita" required>
                        <option value="">Selecciona un psicólogo</option>
                        <option value="1">Psi. Ana Pérez</option>
                        <option value="2">Lic. Juan Soto</option>
                        <option value="3">Psi. María Vega</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_cita">Fecha Preferida:</label>
                    <input type="date" id="fecha_cita" name="fecha_cita" required>
                </div>
                <div class="form-group">
                    <label for="hora_cita">Hora Preferida:</label>
                    <input type="time" id="hora_cita" name="hora_cita" required>
                </div>
                <div class="form-group">
                    <label for="tipo_cita">Tipo de Cita:</label>
                    <select id="tipo_cita" name="tipo_cita" required>
                        <option value="videollamada">Videollamada</option>
                        <option value="presencial">Presencial</option>
                        <option value="telefonica">Telefónica</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="motivo_cita">Motivo de la Cita (Opcional):</label>
                    <textarea id="motivo_cita" name="motivo_cita" rows="4" placeholder="Breve descripción del motivo de tu cita."></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Solicitar Cita</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>