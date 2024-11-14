<?php
class Carrito {
    private $userId;
    private $productosCache = null;

    public function __construct($userId = null) {
        $this->userId = $userId;
    }

    public function agregarAlCarrito($productoId, $cantidad = 1) {
        $carrito = $this->obtenerCarritoDesdeLocalStorage();
        if (isset($carrito[$productoId])) {
            $carrito[$productoId]['cantidad'] += $cantidad;
        } else {
            // Obtener detalles del producto desde el almacenamiento local
            $producto = $this->obtenerProductoDesdeLocalStorage($productoId);

            if ($producto) {
                $carrito[$productoId] = [
                    'nombre' => $producto['descripcion'],
                    'precio' => $producto['precio_usd'],
                    'cantidad' => $cantidad
                ];
            } else {
                echo "Producto no encontrado.";
            }
        }
        $this->guardarCarritoEnLocalStorage($carrito);
    }

    public function verCarrito() {
        return $this->obtenerCarritoDesdeLocalStorage();
    }

    public function eliminarDelCarrito($productoId) {
        $carrito = $this->obtenerCarritoDesdeLocalStorage();
        if (isset($carrito[$productoId])) {
            unset($carrito[$productoId]);
            $this->guardarCarritoEnLocalStorage($carrito);
            return true;
        }
        return false;
    }

    public function realizarCompra() {
        // Obtener el carrito del usuario
        $carrito = $this->verCarrito();

        if (empty($carrito)) {
            throw new Exception("El carrito está vacío.");
        }

        // Datos del usuario (puedes obtenerlos de la sesión o de otro lugar)
        $userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'usuario@example.com';
        $userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Usuario';

        // Generar el contenido del correo electrónico
        $subject = "Confirmación de compra";
        $message = "Hola $userName,\n\nGracias por tu compra. Aquí tienes los detalles de tu pedido:\n\n";

        foreach ($carrito as $productoId => $item) {
            $message .= "Producto: " . $item['nombre'] . "\n";
            $message .= "Cantidad: " . $item['cantidad'] . "\n";
            $message .= "Precio: $" . $item['precio'] . "\n";
            $message .= "Total: $" . ($item['precio'] * $item['cantidad']) . "\n\n";
        }

        $message .= "Gracias por comprar con nosotros.\n\nSaludos,\nEquipo de HardTech";

        // Vaciar el carrito del usuario
        $this->vaciarCarrito();
    }

    private function obtenerProductoDesdeLocalStorage($productoId) {
        // Verificar si los productos ya están cargados en la caché
        if ($this->productosCache === null) {
            // Verificar si el archivo existe
            $filePath = __DIR__ . '/productos.json';
            if (!file_exists($filePath)) {
                throw new Exception("El archivo productos.json no existe.");
            }

            // Leer y decodificar el archivo JSON
            $productosJson = file_get_contents($filePath);
            $productos = json_decode($productosJson, true);

            // Manejo de errores al decodificar JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Error al decodificar el archivo JSON: " . json_last_error_msg());
            }

            // Almacenar los productos en la caché
            $this->productosCache = $productos;
        }

        // Devolver el producto solicitado
        return isset($this->productosCache[$productoId]) ? $this->productosCache[$productoId] : null;
    }

    private function obtenerCarritoDesdeLocalStorage() {
        // Verificar si la cookie 'carrito' existe
        if (isset($_COOKIE['carrito'])) {
            // Decodificar el contenido de la cookie
            $carrito = json_decode($_COOKIE['carrito'], true);

            // Manejo de errores al decodificar JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Si hay un error en la decodificación, devolver un carrito vacío
                return [];
            }

            // Devolver el carrito decodificado
            return $carrito;
        }

        // Si la cookie no existe, devolver un carrito vacío
        return [];
    }

    private function guardarCarritoEnLocalStorage($carrito) {
        // Convertir el carrito a formato JSON
        $carritoJson = json_encode($carrito);
    
        // Manejo de errores al codificar JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al codificar el carrito a JSON: " . json_last_error_msg());
        }
    
        // Establecer la cookie 'carrito' con el JSON del carrito
        // La cookie expirará en 30 días (86400 segundos por día)
        setcookie('carrito', $carritoJson, time() + (86400 * 30), "/");
    }

    private function vaciarCarrito() {
        // Establecer la cookie 'carrito' con un valor vacío y una fecha de expiración en el pasado
        setcookie('carrito', '', time() - 3600, "/");
    
        // Verificar si la cookie se ha eliminado correctamente
        if (isset($_COOKIE['carrito'])) {
            unset($_COOKIE['carrito']);
        }
    }
}
?>