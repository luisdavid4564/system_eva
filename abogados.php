<?php
include 'includes/session_handler.php';
include 'includes/header.php';
include 'includes/db_config.php';

if(!isUserLoggedIn()){
    redirectToLogin();
}

$success_message = '';
$error_message = '';

$id_usu = $_SESSION['id_usu'] ?? null;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $db = new Conn();
    $conn = $db->Conexion();

    $nombre_reunion = htmlspecialchars(trim($_POST['nombre_reunion']));
    $profesional_id = intval($_POST['profesional_cita']);
    $fecha_cita = htmlspecialchars(trim($_POST['fecha_cita']));
    $hora_cita = htmlspecialchars(trim($_POST['hora_cita']));
    $tipo_cita = htmlspecialchars(trim($_POST['tipo_cita']));
    $motivo_cita = htmlspecialchars(trim($_POST['motivo_cita']));


    if(empty($nombre_reunion) || empty($profesional_id) || empty($fecha_cita) || empty($hora_cita) || empty($tipo_cita)){
        $error_message = "Todos los campos obligatorios deben ser llenados.";
    } elseif($id_usu == null){
        $error_message = "No se pudo identificar al usuario. Por favor, inicia sesión de nuevo.";
    } else{
        try{
            $stmt = $conn->prepare("INSERT INTO citas (id_usu, nom_cita, id_profe, fechaH_cita, hora_cita, tipo_cita, motivo_cita)
                                VALUES (:id_usu, :nom_cita, :id_profe, :fechaH_cita, :hora_cita, :tipo_cita, :motivo_cita)");

            $stmt->bindParam(':id_usu', $id_usu, PDO::PARAM_INT);
            $stmt->bindParam(':nom_cita', $nombre_reunion);
            $stmt->bindParam(':id_profe', $profesional_id, PDO::PARAM_INT);
            $stmt->bindParam(':fechaH_cita', $fecha_cita);
            $stmt->bindParam(':hora_cita', $hora_cita);
            $stmt->bindParam(':tipo_cita', $tipo_cita);
            $stmt->bindParam(':motivo_cita', $motivo_cita);

            if($stmt->execute()){
                $success_message = "¡Cita solicitada con éxito! Nos pondremos en contacto contigo pronto.";  
            } else{
                $error_message = "Hubo un error al solicitar la cita. Inténtalo de nuevo.";
            }
        }catch (PDOException $e){
            $error_message = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<section class="form-section">
    <div class="container">
        <h2>Recibe asesoria legal.</h2>
        <p style="text-align: center; margin-bottom: 40px; color: #555;">Estamos contigo para ayudarte en el ambito legal, de forma segura.</p>

        <div class="professional-grid">
            <div class="professional-card">
               <img src="img\Harvey Specter_.jpeg" alt="Abogado Pedro Torres" class="avatar"> 
               <h3>Lic. Pedro Torres</h3>
               <p>Abogado Penal</p>
               <p>Especialista en area penal.</p>
               <a href="#" class="btn btn-primary">Agendar Reunión</a>
            </div>
            <div class="professional-card">
               <img src="img\descarga.jpeg" alt="Abogado Pedro Torres" class="avatar"> 
               <h3>Lic. Katherina Acosta</h3>
               <p>Abogada Familiar</p>
               <p>Especialista en area familiar.</p>
               <a href="#" class="btn btn-primary">Agendar Reunión</a>
            </div>
            <div class="professional-card">
               <img src="img\A photo of a stylish woman in a modern _ Premium AI-generated image.jpeg" alt="Abogado Pedro Torres" class="avatar"> 
               <h3>Lic. Alejandra Peña</h3>
               <p>Abogada Civil</p>
               <p>Especialista en area Civil.</p>
               <a href="#" class="btn btn-primary">Agendar Reunión</a>
            </div>
        </div>

        <div class="form-container" style="margin-top: 50px;">
            <h2>Agendar Reunion de Área Legal</h2>

            <?php if(!empty($success_message)): ?>
                <p style="color: green; text-align: center; margin-bottom: 15px"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <?php if(!empty($error_message)): ?>
                <p style="color: red; text-align: center; margin-bottom: 15px"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="" method="POST"> <div class="form-group">
                    <label for="nombre_reunion">Tu Nombre:</label>
                    <input type="text" id="nombre_reunion" name="nombre_reunion" placeholder="Nombre" required
                        value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="profesional_cita">Abogado Preferido:</label>
                    <select id="profesional_cita" name="profesional_cita" required>
                        <option value="">Selecciona un abogado</option>
                        <option value="4">Lic. Pedro Torres</option> 
                        <option value="5">Lic. Katherina Acosta</option>
                        <option value="6">Lic. Alejandra Peña</option>
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
                    <label for="tipo_cita">Tipo Cita:</label>
                    <select id="tipo_cita" name="tipo_cita" required>
                        <option value="Presencial">Presencial</option>
                        <option value="Videollamada">Videollamada</option>
                        <option value="Telefónica">Telefónica</option>
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






               



               