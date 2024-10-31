<?php

class Usuario {
    private $conn;
    private $loggedIn = false;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($username, $password) {
        try {
            // Consulta para verificar el usuario
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verificamos si el usuario existe y la contraseña es correcta
            if ($user && password_verify($password, $user['password'])) {
                $this->loggedIn = true;
                echo "<script>
                        alert('Login exitoso');
                        // Aquí puedes invocar un modal de éxito
                      </script>";
            } else {
                // Mostramos un mensaje que nos indique si el usuario o la contraseña son incorrectos. 
                if (!$user) {
                    echo "<script>
                            document.getElementById('username-error').innerText = 'Usuario incorrecto';
                          </script>";
                } else {
                    echo "<script>
                            document.getElementById('password-error').innerText = 'Contraseña incorrecta';
                          </script>";
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function logout() {
        $this->loggedIn = false;
    }

    public function isLoggedIn() {
        return $this->loggedIn;
    }

    public function register($username, $password) {
        // Lógica para registrar un nuevo usuario
    }

    public function buscarProductos($query) {
        // Lógica para buscar productos en la base de datos y devolver los resultados
        // (No recuerdo si linkeabamos tablas)
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE nombre LIKE ?");
        $stmt->execute(['%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarAlCarrito($productoId) {
        if (!$this->isLoggedIn()) {
            // Usuario no logueado, almacenar en la sesión
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }
    
            if (isset($_SESSION['carrito'][$productoId])) {
                $_SESSION['carrito'][$productoId]['cantidad'] += 1;
            } else {
                // Obtener detalles del producto desde la base de datos
                $stmt = $this->conn->prepare("SELECT * FROM productos WHERE id = :productoId");
                $stmt->bindParam(':productoId', $productoId);
                $stmt->execute();
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($producto) {
                    $_SESSION['carrito'][$productoId] = [
                        // Dudas con las cantidades, por eso dejo 1 por defecto. Quizás 
                        // deberíamos rechequear el carrito en la vista de carrito.
                        'nombre' => $producto['nombre'],
                        'precio' => $producto['precio'],
                        'cantidad' => 1
                    ];
                } else {
                    echo "Producto no encontrado.";
                }
            }
        } else {
            // Usuario logueado, almacenar en la base de datos
            $userId = $_SESSION['user_id']; // Asumiendo que el ID del usuario está almacenado en la sesión
    
            // Verificamos si el producto ya está en el carrito
            $stmt = $this->conn->prepare("SELECT * FROM carrito WHERE user_id = :userId AND producto_id = :productoId");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':productoId', $productoId);
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($item) {
                // Actualizar la cantidad del producto en el carrito
                $stmt = $this->conn->prepare("UPDATE carrito SET cantidad = cantidad + 1 WHERE user_id = :userId AND producto_id = :productoId");
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':productoId', $productoId);
                $stmt->execute();
            } else {
                // Obtener detalles del producto desde la base de datos
                $stmt = $this->conn->prepare("SELECT * FROM productos WHERE id = :productoId");
                $stmt->bindParam(':productoId', $productoId);
                $stmt->execute();
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($producto) {
                    // Insertar el producto en el carrito
                    $stmt = $this->conn->prepare("INSERT INTO carrito (user_id, producto_id, nombre, precio, cantidad) VALUES (:userId, :productoId, :nombre, :precio, 1)");
                    $stmt->bindParam(':userId', $userId);
                    $stmt->bindParam(':productoId', $productoId);
                    $stmt->bindParam(':nombre', $producto['nombre']);
                    $stmt->bindParam(':precio', $producto['precio']);
                    $stmt->execute();
                } else {
                    echo "Producto no encontrado.";
                }
            }
        }
    }

    public function verCarrito() {
        $carrito = [];

        if (!$this->isLoggedIn()) {
            // Usuario no logueado, obtener el carrito desde la sesión
            if (isset($_SESSION['carrito'])) {
                $carrito = $_SESSION['carrito'];
            }
        } else {
            // Usuario logueado, obtener el carrito desde la base de datos
            $userId = $_SESSION['user_id']; // Asumiendo que el ID del usuario está almacenado en la sesión
    
            $stmt = $this->conn->prepare("SELECT * FROM carrito WHERE user_id = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return $carrito;
    }

    public function realizarCompra() {
        if (!$this->isLoggedIn()) {
            throw new Exception("Debe estar logueado para realizar una compra.");
        }
    }

    public function seleccionarMedioPago($medioPago) {
        if (!$this->isLoggedIn()) {
            throw new Exception("Debe estar logueado para seleccionar un medio de pago.");
        }
    }

    // Pensar si es necesario
    public function realizarPagoParcial($monto) {
        if (!$this->isLoggedIn()) {
            throw new Exception("Debe estar logueado para realizar un pago parcial.");
        }
    }
}