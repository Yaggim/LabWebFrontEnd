<?php
class Carrito {
    private $userId;

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

        // Aquí puedes agregar la lógica para procesar la compra
        // Por ejemplo, enviar un correo electrónico de confirmación, etc.

        // Vaciar el carrito del usuario
        $this->vaciarCarrito();
    }

    private function obtenerProductoDesdeLocalStorage($productoId) {
        // Aquí debes implementar la lógica para obtener los detalles del producto desde el almacenamiento local
        // Por ejemplo, puedes tener un archivo JSON con los detalles de los productos
        $productos = json_decode(file_get_contents('productos.json'), true);
        return isset($productos[$productoId]) ? $productos[$productoId] : null;
    }

    private function obtenerCarritoDesdeLocalStorage() {
        // Aquí debes implementar la lógica para obtener el carrito desde el almacenamiento local
        return isset($_COOKIE['carrito']) ? json_decode($_COOKIE['carrito'], true) : [];
    }

    private function guardarCarritoEnLocalStorage($carrito) {
        // Aquí debes implementar la lógica para guardar el carrito en el almacenamiento local
        setcookie('carrito', json_encode($carrito), time() + (86400 * 30), "/"); // 86400 = 1 día
    }

    private function vaciarCarrito() {
        // Aquí debes implementar la lógica para vaciar el carrito
        setcookie('carrito', '', time() - 3600, "/"); // Expirar la cookie
    }
}
?>