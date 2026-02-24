<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::obtenerConexion();
    }

    /**
     * Registra un nuevo usuario con todos los datos
     */
    public function registrar($datos) {
        // Verificar si email o username ya existen
        $sql = "SELECT id FROM usuarios WHERE email = :email OR username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':username', $datos['username']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "El email o nombre de usuario ya está registrado.";
        }

        // Hashear contraseña
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

        // Procesar intereses (array a string separado por comas)
        $intereses = isset($datos['intereses']) ? implode(',', $datos['intereses']) : '';

        // Insertar
        $sql = "INSERT INTO usuarios 
                (nombre, cedula, telefono, email, username, password, carrera, intereses, nivel_experiencia) 
                VALUES 
                (:nombre, :cedula, :telefono, :email, :username, :password, :carrera, :intereses, :nivel_experiencia)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':cedula', $datos['cedula']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':email', $datos['email']);
        $stmt->bindParam(':username', $datos['username']);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':carrera', $datos['carrera']);
        $stmt->bindParam(':intereses', $intereses);
        $stmt->bindParam(':nivel_experiencia', $datos['nivel_experiencia']);

        if ($stmt->execute()) {
            return true;
        } else {
            return "Error al registrar el usuario.";
        }
    }

    /**
     * Inicia sesión con email o username
     */
    public function login($identificador, $password) {
        $sql = "SELECT * FROM usuarios WHERE email = :identificador OR username = :identificador";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':identificador', $identificador);
        $stmt->execute();
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Verificar estado
            if ($usuario['estado'] !== 'activo') {
                return "Tu cuenta está pendiente de aprobación o inactiva.";
            }
            // Iniciar sesión
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            $_SESSION['usuario_username'] = $usuario['username'];
            return true;
        } else {
            return "Credenciales incorrectas.";
        }
    }

    /**
     * Verifica si hay sesión activa
     */
    public static function estaLogueado() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['usuario_id']);
    }

    /**
     * Verifica si el usuario logueado es admin
     */
    public static function esAdmin() {
        return self::estaLogueado() && $_SESSION['usuario_rol'] === 'admin';
    }

    /**
     * Cierra sesión
     */
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    /**
     * Obtiene miembros activos o pendientes según parámetro
     */
    public function obtenerMiembros($activos = true) {
        $estado = $activos ? 'activo' : 'pendiente';
        $sql = "SELECT id, nombre, email, carrera, intereses, nivel_experiencia, rol 
                FROM usuarios WHERE estado = :estado ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $estado);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un usuario por ID (útil para admin)
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}