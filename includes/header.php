<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVA - Un espacio seguro para cada mujer</title>
    <link rel="stylesheet" href="public/css/style.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container header-content">
            <div class="logo">
                 <img src="img/logo 3.png" alt="Logo EVA" class="logo-img">
            </div>
            <nav class="main-nav">
                <ul>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="denuncia_anonima.php">Denuncia Anónima</a></li>
                        <li class="dropdown">
                            <a href="#">Servicios <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="psicologia.php">Psicología</a></li>
                                <li><a href="abogados.php">Asesoría Legal</a></li>
                                <li><a href="medicina.php">Atención Médica</a></li>
                            </ul>
                        </li>
                        <li><a href="donaciones.php">Donaciones</a></li>
                        <li><a href="organizaciones.php">Organizaciones Afiliadas</a></li>
                        <li><a href="feedback.php">Feedback</a></li>
                        <li><a href="#">Hola, <?php echo htmlspecialchars($_SESSION['nom_usu']); ?></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                     <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>



   
    