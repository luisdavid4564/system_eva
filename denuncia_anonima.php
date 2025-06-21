<?php
include 'includes/session_handler.php';
include 'includes/header.php';
include 'includes/db_config.php';

if(!isUserLoggedIn()){
    redirectToLogin();
}

$message = '';
$tipo_violencia_val = '';
$descripcion_val = '';
$anonimo_denuncia_val = 1; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Conn();
    $conn = $db->Conexion();

    $tipo_violencia = $_POST['tipo_violencia'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    
    $ubicacion_incidente_form = $_POST['ubicacion'] ?? NULL;
    $fecha_incidente_form = $_POST['fecha_incidente'] ?? NULL;

    $anonimo_denuncia = isset($_POST['anonimo_denuncia']) ? 1 : 0; 

    
    $tipo_violencia_val = $tipo_violencia;
    $descripcion_val = $descripcion;
    $anonimo_denuncia_val = $anonimo_denuncia;

    $id_usuario = NULL; /*TODO: POR DEFECTO LA DENUNCIA ES ANONIMA */

    if (isset($_SESSION['id_usu']) && $anonimo_denuncia == 0) {
        $id_usuario = $_SESSION['id_usu'];
    }

    if (empty($tipo_violencia) || empty($descripcion)) {
        $message = '<p class="error-message">Por favor, selecciona el tipo de violencia y describe la situación.</p>';
    } else {
        try {
            $adjuntos_nombres = [];
            $upload_dir = 'uploads\denuncias';

            /* TODO: Crear la carpeta si no existe */
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (isset($_FILES['adjuntos']) && !empty($_FILES['adjuntos']['name'][0])) {
                $total_files = count($_FILES['adjuntos']['name']);

                for ($i = 0; $i < $total_files; $i++) {
                    $file_name = $_FILES['adjuntos']['name'][$i];
                    $file_tmp_name = $_FILES['adjuntos']['tmp_name'][$i];
                    $file_size = $_FILES['adjuntos']['size'][$i];
                    $file_error = $_FILES['adjuntos']['error'][$i];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'mp3', 'mp4', 'pdf'];
                    $max_file_size = 5 * 1024 * 1024; // 5MB

                    if ($file_error !== UPLOAD_ERR_OK) {
                        $message = '<p class="error-message">Error al subir el archivo ' . htmlspecialchars($file_name) . '. Código: ' . $file_error . '</p>';
                        continue;
                    }

                    if (!in_array($file_ext, $allowed_extensions)) {
                        $message = '<p class="error-message">Tipo de archivo no permitido para ' . htmlspecialchars($file_name) . '.</p>';
                        continue;
                    }

                    if ($file_size > $max_file_size) {
                        $message = '<p class="error-message">El archivo ' . htmlspecialchars($file_name) . ' excede el tamaño máximo permitido de 5MB.</p>';
                        continue;
                    }

                    $new_file_name = uniqid('denuncia_', true) . '.' . $file_ext;
                    $destination = $upload_dir . $new_file_name;

                    if (move_uploaded_file($file_tmp_name, $destination)) {
                        $adjuntos_nombres[] = $new_file_name;
                    } else {
                        $message = '<p class="error-message">Error al mover el archivo ' . htmlspecialchars($file_name) . '.</p>';
                        continue;
                    }
                }
            }

            
            $adjuntos_json = !empty($adjuntos_nombres) ? json_encode($adjuntos_nombres) : NULL;
            $ubiInci_denu_val = NULL;
            $est_denu_val = 'Pendiente';

            $stmt = $conn->prepare("INSERT INTO Denuncias (id_usu, descri_denu, tipoVio_denu, est_denu, anoni_denu, ubiInci_denu, adjuntos_denu)
                                    VALUES (:id_usu, :descri_denu, :tipoVio_denu, :est_denu, :anoni_denu, :ubiInci_denu, :adjuntos_denu)");
            $params = [
                ':id_usu' => $id_usuario,
                ':descri_denu' => $descripcion,
                ':tipoVio_denu' => $tipo_violencia,
                ':est_denu' => $est_denu_val,
                ':anoni_denu' => $anonimo_denuncia,
                ':ubiInci_denu' => $ubiInci_denu_val,
                ':adjuntos_denu' => $adjuntos_json
            ];

            $stmt->execute($params);

            $message = '<p class="success-message">¡Tu denuncia ha sido enviada con éxito! Gracias por romper el silencio.</p>';

            $tipo_violencia_val = '';
            $descripcion_val = '';
            $anonimo_denuncia_val = 1;

        } catch (PDOException $e) {
            $errorInfo = $e->errorInfo;
            $detailedMessage = "SQLSTATE: " . ($errorInfo[0] ?? 'N/A') . "<br>";
            $detailedMessage .= "Code: " . ($errorInfo[1] ?? 'N/A') . "<br>";
            $detailedMessage .= "Message: " . ($errorInfo[2] ?? $e->getMessage());

            $message = '<p class="error-message">Error al registrar la denuncia: <br>' . $detailedMessage . '</p>';
        }
    }
}
?>

<section class="form-section">
    <div class="container">
        <div class="form-container">
            <h2>Denuncia Anónima</h2>
            <p style="text-align: center; margin-bottom: 25px; color: #555;">Comunica una situación de peligro de forma segura y confidencial.</p>
            <?php echo $message; ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="tipo_violencia">Tipo de Violencia:</label>
                    <select id="tipo_violencia" name="tipo_violencia" required>
                        <option value="">Selecciona un tipo</option>
                        <option value="fisica" <?php echo ($tipo_violencia_val == 'fisica') ? 'selected' : ''; ?>>Física</option>
                        <option value="psicologica" <?php echo ($tipo_violencia_val == 'psicologica') ? 'selected' : ''; ?>>Psicológica</option>
                        <option value="sexual" <?php echo ($tipo_violencia_val == 'sexual') ? 'selected' : ''; ?>>Sexual</option>
                        <option value="economica" <?php echo ($tipo_violencia_val == 'economica') ? 'selected' : ''; ?>>Económica</option>
                        <option value="acoso" <?php echo ($tipo_violencia_val == 'acoso') ? 'selected' : ''; ?>>Acoso</option>
                        <option value="otra" <?php echo ($tipo_violencia_val == 'otra') ? 'selected' : ''; ?>>Otra</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="descripcion">Describe la situación:</label>
                    <textarea id="descripcion" name="descripcion" rows="8" placeholder="Sé lo más detallada posible..." required><?php echo htmlspecialchars($descripcion_val); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="ubicacion">¿Dónde ocurrió el incidente? (Opcional)</label>
                    <input type="text" id="ubicacion" name="ubicacion" placeholder="Ej: Mi casa, en el trabajo, vía pública">
                </div>
                <div class="form-group">
                    <label for="fecha_incidente">¿Cuándo ocurrió el incidente? (Fecha aproximada)</label>
                    <input type="date" id="fecha_incidente" name="fecha_incidente">
                </div>
                <div class="form-group">
                    <label for="adjuntos">Adjuntar evidencia (fotos, audios, etc. - opcional):</label>
                    <input type="file" id="adjuntos" name="adjuntos[]" multiple>
                    <small style="color: #777;">(Máx. 5MB por archivo, formatos permitidos: jpg, png, mp3, mp4, pdf)</small>
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="anonimo_denuncia" name="anonimo_denuncia" <?php echo ($anonimo_denuncia_val == 1) ? 'checked' : ''; ?>>
                    <label for="anonimo_denuncia">Mantener mi denuncia anónima</label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enviar Denuncia</button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php include 'includes\footer.php'; ?>