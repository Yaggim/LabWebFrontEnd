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
        $carrito = $this->verCarrito();

        if (empty($carrito)) {
            throw new Exception("El carrito está vacío.");
        }

        $userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'usuario@example.com';
        $userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Usuario';

        $subject = "Confirmación de compra";
        $message = "Hola $userName,\n\nGracias por tu compra. Aquí tienes los detalles de tu pedido:\n\n";

        foreach ($carrito as $productoId => $item) {
            $message .= "Producto: " . $item['nombre'] . "\n";
            $message .= "Cantidad: " . $item['cantidad'] . "\n";
            $message .= "Precio: $" . $item['precio'] . "\n";
            $message .= "Total: $" . ($item['precio'] * $item['cantidad']) . "\n\n";
        }

        $message .= "Gracias por comprar con nosotros.\n\nSaludos,\nEquipo de HardTech";

        $this->vaciarCarrito();
    }

    private function obtenerProductoDesdeLocalStorage($productoId) {
        if ($this->productosCache === null) {
            $filePath = __DIR__ . '/productos.json';
            if (!file_exists($filePath)) {
                throw new Exception("El archivo productos.json no existe.");
            }

            $productosJson = file_get_contents($filePath);
            $productos = json_decode($productosJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Error al decodificar el archivo JSON: " . json_last_error_msg());
            }

            $this->productosCache = $productos;
        }

        return isset($this->productosCache[$productoId]) ? $this->productosCache[$productoId] : null;
    }

    private function obtenerCarritoDesdeLocalStorage() {
        if (isset($_COOKIE['carrito'])) {
            $carrito = json_decode($_COOKIE['carrito'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }

            return $carrito;
        }

        return [];
    }

    private function guardarCarritoEnLocalStorage($carrito) {
        $carritoJson = json_encode($carrito);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al codificar el carrito a JSON: " . json_last_error_msg());
        }
    
        setcookie('carrito', $carritoJson, time() + (86400 * 30), "/");
    }

    private function vaciarCarrito() {
        setcookie('carrito', '', time() - 3600, "/");
    
        if (isset($_COOKIE['carrito'])) {
            unset($_COOKIE['carrito']);
        }
    }
}
?>