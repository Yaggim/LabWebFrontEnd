<?php

require_once __DIR__.'/Crud.php';

class Usuario extends Crud {
    private $loggedIn = false;

    public function login($username, $password) {
        try {
            // Consulta para verificar el usuario
            $stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verificamos si el usuario existe y la contraseña es correcta
            if ($user && password_verify($password, $user['password'])) {
                $this->loggedIn = true;

                $userData = $this->getDatosUsuario($user['id'], $user['id_persona']);

                $_SESSION['usuario'] = [
                    'id' => $user['id'],
                    'nombre' => $userData['nombre'],
                    'apellido' => $userData['apellido'],
                    'dni' => $userData['dni'],
                    'telefono' => $userData['telefono'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'password' => $user['password'],
                    'id_persona' => $user['id_persona'],
                ];

                return ['success' => true];
            } else {
                return [
                    'success' => false,
                    'error' => !$user ? 'Usuario incorrecto' : 'Contraseña incorrecta'
                ];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error en el servidor. Inténtalo más tarde.'];
        }
    }

    public function logout() {
        try {
            $this->loggedIn = false;
            session_unset();
            if (session_destroy()) {
                return ['success' => true];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error en el servidor. Inténtalo más tarde.'];
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['usuario']) || $this->loggedIn;
    }

    public function register($nombre, $apellido, $dni, $telefono, $username, $email, $password) {
        // Lógica para registrar un nuevo usuario
        try {
            $this->conexion->beginTransaction();

            if (empty($nombre) || empty($apellido) || empty($dni) || empty($telefono) || empty($username) || empty($email) || empty($password)) {
                throw new Exception("Todos los campos son obligatorios.");
            }
            
            $stmt = $this->conexion->prepare("SELECT 1
                                            FROM personas
                                            WHERE dni = :dni");
            $stmt->bindParam(':dni', $dni);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                throw new Exception("El DNI ya está registrado.");
            }

            $stmt = $this->conexion->prepare("SELECT 1
                                            FROM usuarios
                                            WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                throw new Exception("El correo electrónico ya está registrado.");
            }

            $query = <<<SQL
                INSERT INTO
                    personas (nombre, apellido, dni, telefono)
                    VALUES (:nombre, :apellido, :dni, :telefono)
            SQL;

            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->execute();

            $id_persona = $this->conexion->lastInsertId();

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $query = <<<SQL
                INSERT INTO
                    usuarios (username, email, password, estado, id_persona)
                VALUES (:username, :email, :password, :estado, :id_persona)
            SQL;

            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $estado = 1; // Estado: activo por defecto.
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id_persona', $id_persona);
            $stmt->execute();
            
            $id_usuario = $this->conexion->lastInsertId();
            
            $query = <<<SQL
                INSERT INTO
                    roles_usuarios (id_rol, id_usuario)
                VALUES (:id_rol, :id_usuario)
            SQL;
            
            $stmt = $this->conexion->prepare($query);
            $id_rol = 2; // Rol: cliente por defecto.
            $stmt->bindParam(':id_rol', $id_rol);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();

            $this->conexion->commit();
            $this->login($username, $password);

        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }

    }

    public function actualizarPerfilUsuario($newTelefono, $newEmail, $newPassword, $oldPassword) {
        try {
            $this->conexion->beginTransaction();

            if (empty($newTelefono) || empty($newEmail) || empty($newPassword) || empty($oldPassword)) {
                throw new Exception("Todos los campos son obligatorios.");
            }

            $id_usuario = $_SESSION['usuario']['id'];
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $query = <<<SQL
                    UPDATE usuarios AS u
                    JOIN personas AS p ON u.id_persona = p.id_persona
                    SET u.email = :email,
                        u.password = :password,
                        p.telefono = :telefono
                    WHERE u.id = :id_usuario;
                    SQL;
            
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':email', $newEmail);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':telefono', $newTelefono);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();

            $this->conexion->commit();

            $_SESSION['usuario']['email'] = $newEmail;
            $_SESSION['usuario']['password'] = $hashedPassword;

        }
        catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    public function getDatosUsuario($id_usuario, $id_persona) {
        try {
            $this->conexion->beginTransaction();

            if (empty($id_usuario) || empty($id_persona)) {
                throw new Exception("El usuario debe estar logueado.");
            }

            $query = <<<SQL
                SELECT
                    u.id, u.username, u.email, u.password, u.estado, u.id_persona,
                    p.nombre, p.apellido, p.dni, p.telefono
                FROM
                    usuarios AS u
                JOIN
                    personas AS p ON u.id_persona = p.id_persona
                WHERE
                    u.id = :id_usuario AND p.id_persona = :id_persona;
            SQL;

            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':id_persona', $id_persona);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;

        } catch (Exception $e) {
            throw $e;
        }
    }
}