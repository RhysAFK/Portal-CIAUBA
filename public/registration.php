<?php
require_once __DIR__ . '/../vendor/autoload.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $datos = [
        'nombre' => trim($_POST['fullName'] ?? ''),
        'cedula' => trim($_POST['studentId'] ?? ''),
        'telefono' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'username' => trim($_POST['username'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'passwordConfirm' => $_POST['passwordConfirm'] ?? '',
        'carrera' => $_POST['major'] ?? '',
        'intereses' => $_POST['interests'] ?? [], // array
        'nivel_experiencia' => $_POST['experience'] ?? 'beginner'
    ];

    // Validaciones básicas
    if (in_array('', $datos)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif ($datos['password'] !== $datos['passwordConfirm']) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($datos['password']) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        $user = new User();
        $resultado = $user->registrar($datos);
        if ($resultado === true) {
            $exito = 'Registro exitoso. Tu cuenta está pendiente de aprobación por un administrador.';
            // Limpiar campos (opcional)
            $datos = [];
        } else {
            $error = $resultado;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - CIAUBA</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
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
                <li><a href="register.php">Registro</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="registration-form">
            <h2>Únete al club</h2>
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($exito): ?>
                <div class="exito"><?php echo htmlspecialchars($exito); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <fieldset>
                    <legend>Información personal</legend>
                    
                    <div class="form-group">
                        <label for="fullName">Nombre completo *</label>
                        <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($datos['nombre'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="studentId">Cédula *</label>
                        <input type="text" id="studentId" name="studentId" value="<?php echo htmlspecialchars($datos['cedula'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Teléfono *</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($datos['telefono'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo de contacto *</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($datos['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="major">Carrera *</label>
                        <select id="major" name="major" required>
                            <option value="Ingeniería en Sistemas" <?php echo (isset($datos['carrera']) && $datos['carrera']=='Ingeniería en Sistemas')?'selected':''; ?>>Ingeniería en Sistemas</option>
                            <option value="Ingeniería Eléctrica" <?php echo (isset($datos['carrera']) && $datos['carrera']=='Ingeniería Eléctrica')?'selected':''; ?>>Ingeniería Eléctrica</option>
                            <option value="Ingeniería Mecánica" <?php echo (isset($datos['carrera']) && $datos['carrera']=='Ingeniería Mecánica')?'selected':''; ?>>Ingeniería Mecánica</option>
                            <option value="Ingeniería Civil" <?php echo (isset($datos['carrera']) && $datos['carrera']=='Ingeniería Civil')?'selected':''; ?>>Ingeniería Civil</option>
                            <option value="Otra" <?php echo (isset($datos['carrera']) && $datos['carrera']=='Otra')?'selected':''; ?>>Otra</option>
                        </select>
                    </div>
                </fieldset>
                
                <fieldset>
                    <legend>Intereses técnicos</legend>
                    <p>Marca las áreas en las que estás interesado:</p>
                    
                    <?php
                    $intereses = [
                        'robotics' => 'Robótica y automatización',
                        'embedded' => 'Sistemas embebidos',
                        'webdev' => 'Desarrollo web y móvil',
                        '3dprinting' => 'Impresión 3D y prototipado',
                        'iot' => 'IoT y dispositivos conectados',
                        'renewable' => 'Energías renovables',
                        'ai' => 'Inteligencia artificial y machine learning',
                        'vr' => 'Realidad virtual y aumentada'
                    ];
                    $interesesSeleccionados = isset($datos['intereses']) ? (array)$datos['intereses'] : [];
                    ?>
                    <div class="form-group checkbox-group">
                        <?php foreach ($intereses as $valor => $etiqueta): ?>
                        <label>
                            <input type="checkbox" name="interests[]" value="<?php echo $valor; ?>" 
                                <?php echo in_array($valor, $interesesSeleccionados) ? 'checked' : ''; ?>>
                            <?php echo $etiqueta; ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="experience">Nivel de experiencia previa</label>
                        <select id="experience" name="experience">
                            <option value="beginner" <?php echo (isset($datos['nivel_experiencia']) && $datos['nivel_experiencia']=='beginner')?'selected':''; ?>>Básico (0-1 años)</option>
                            <option value="intermediate" <?php echo (isset($datos['nivel_experiencia']) && $datos['nivel_experiencia']=='intermediate')?'selected':''; ?>>Intermedio (1-3 años)</option>
                            <option value="advanced" <?php echo (isset($datos['nivel_experiencia']) && $datos['nivel_experiencia']=='advanced')?'selected':''; ?>>Avanzado (3+ años)</option>
                        </select>
                    </div>
                </fieldset>
                
                <fieldset>
                    <legend>Cuenta del Foro</legend>
                    
                    <div class="form-group">
                        <label for="username">Usuario *</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($datos['username'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña *</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="passwordConfirm">Confirmar contraseña *</label>
                        <input type="password" id="passwordConfirm" name="passwordConfirm" required>
                    </div>
                </fieldset>
                
                <div class="form-group terms">
                    <label>
                        <input type="checkbox" name="terms" required>
                        Estoy de acuerdo con el <a href="#">Código de Conducta</a> y los <a href="#">Términos de Permanencia</a> *
                    </label>
                </div>
                
                <button type="submit">Subir registro</button>
                <button type="reset">Vaciar formulario</button>
            </form>
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
        </section>
    </main>

    <footer>
        <p>Club de Ingeniería Aplicada UBA &copy; 2025</p>
        <p>Contact: rhysuba@gmail.com</p>
    </footer>
</body>
</html>