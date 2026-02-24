<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (!User::estaLogueado()) {
    header('Location: login.php');
    exit;
}

$nombre = $_SESSION['usuario_nombre'];
$rol = $_SESSION['usuario_rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - CIAUBA</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <img src="img/logo-uba-horizontal1.png" alt="uba_logo">
        <div class="logo">
            <h1>Club de Ingeniería Aplicada UBA</h1>
            <p>Aprende • Construye • Mejora</p>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="information.php">Información</a></li>
                <li><a href="members.php">Miembros</a></li>
                <li><a href="work_together.php">Work Together</a></li>
                <?php if ($rol === 'admin'): ?>
                    <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Cerrar sesión (<?php echo $nombre; ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="dashboard">
            <h2>Bienvenido, <?php echo htmlspecialchars($nombre); ?>!</h2>
            <p>Has iniciado sesión correctamente. Desde aquí puedes acceder a las diferentes secciones del club.</p>
            
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Miembros</h3>
                    <p>Conoce a los otros miembros del club.</p>
                    <a href="members.php" class="btn">Ver miembros</a>
                </div>
                <div class="card">
                    <h3>Foro</h3>
                    <p>Participa en las discusiones técnicas.</p>
                    <a href="work_together.php" class="btn">Ir al foro</a>
                </div>
                <?php if ($rol === 'admin'): ?>
                <div class="card">
                    <h3>Administración</h3>
                    <p>Gestiona miembros y contenido.</p>
                    <a href="admin.php" class="btn">Panel admin</a>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>Club de Ingeniería Aplicada UBA &copy; 2025</p>
        <p>Contacto: rhysuba@gmail.com</p>
    </footer>
</body>
</html>