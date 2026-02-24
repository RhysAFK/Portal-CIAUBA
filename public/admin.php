<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (!User::esAdmin()) {
    header('Location: index.php');
    exit;
}

$userModel = new User();
$pendientes = $userModel->obtenerMiembros(false); // pendientes
$activos = $userModel->obtenerMiembros(true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIAUBA - Admin</title>
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
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logout.php">Cerrar sesión (<?php echo $_SESSION['usuario_nombre']; ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="admin-header">
            <h2>Panel de Administración</h2>
            <p>Bienvenido, <strong><?php echo $_SESSION['usuario_nombre']; ?></strong></p>
            <div class="admin-alert">
                <p>⚠️ Tienes <?php echo count($pendientes); ?> miembros pendientes de aprobación.</p>
            </div>
        </section>

        <div class="admin-layout">
            <aside class="admin-sidebar">
                <h3>Navegación</h3>
                <nav class="admin-menu">
                    <ul>
                        <li><a href="#members" class="active">Gestión de Miembros</a></li>
                        <li><a href="#projects">Proyectos</a></li>
                        <li><a href="#forum">Foro</a></li>
                        <li><a href="#settings">Configuración</a></li>
                    </ul>
                </nav>
            </aside>

            <div class="admin-content">
                <!-- Sección de miembros (visible por defecto) -->
                <section id="members" class="admin-section active">
                    <h3>Miembros Pendientes</h3>
                    <?php if (empty($pendientes)): ?>
                        <p>No hay miembros pendientes.</p>
                    <?php else: ?>
                        <table class="members-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Carrera</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendientes as $p): ?>
                                <tr>
                                    <td><?php echo $p['id']; ?></td>
                                    <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($p['email']); ?></td>
                                    <td><?php echo htmlspecialchars($p['carrera']); ?></td>
                                    <td>
                                        <a href="aprobar.php?id=<?php echo $p['id']; ?>" class="action-btn approve">Aprobar</a>
                                        <a href="rechazar.php?id=<?php echo $p['id']; ?>" class="action-btn reject">Rechazar</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <h3 style="margin-top: 2rem;">Miembros Activos (<?php echo count($activos); ?>)</h3>
                    <table class="members-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Carrera</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activos as $a): ?>
                            <tr>
                                <td><?php echo $a['id']; ?></td>
                                <td><?php echo htmlspecialchars($a['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($a['email']); ?></td>
                                <td><?php echo htmlspecialchars($a['carrera']); ?></td>
                                <td><?php echo $a['rol']; ?></td>
                                <td>
                                    <a href="#" class="action-btn view">Ver</a>
                                    <a href="#" class="action-btn edit">Editar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <!-- Otras secciones (aún no implementadas) -->
                <section id="projects" class="admin-section">
                    <h3>Gestión de Proyectos</h3>
                    <p>Próximamente...</p>
                </section>
                <section id="forum" class="admin-section">
                    <h3>Moderación del Foro</h3>
                    <p>Próximamente...</p>
                </section>
                <section id="settings" class="admin-section">
                    <h3>Configuración</h3>
                    <p>Próximamente...</p>
                </section>
            </div>
        </div>
    </main>

    <!-- Script simple para cambiar secciones (opcional) -->
    <script>
        document.querySelectorAll('.admin-menu a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelectorAll('.admin-menu a').forEach(a => a.classList.remove('active'));
                link.classList.add('active');
                const target = link.getAttribute('href').substring(1);
                document.querySelectorAll('.admin-section').forEach(section => {
                    section.classList.remove('active');
                });
                document.getElementById(target).classList.add('active');
            });
        });
    </script>
</body>
</html>