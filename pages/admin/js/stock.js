// stock.js
import { producto } from './conexion.js'; // Ajusta la ruta según sea necesario

let selectedProductId = null;
let products = [];

// Función para cargar los productos inicialmente
async function loadProducts() {
    try {
        const productos = await producto();
        products = productos;
        updateProductsTable();
    } catch (error) {
        console.error('Error cargando los productos:', error);
    }
}

// Función para actualizar la tabla con los productos actuales
function updateProductsTable() {
    const tableBody = document.querySelector('#stock table tbody');
    if (!tableBody) {
        console.error('No se encontró el elemento de la tabla.');
        return;
    }

    tableBody.innerHTML = '';

    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.id}</td>
            <td>${product.brand.name}</td>
            <td>${product.model.name}</td>
            <td>${product.category.name}</td>
            <td>${product.stock}</td>
            <td>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#manageStockModal" data-id="${product.id}">
                    Gestionar Stock
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });

    document.querySelectorAll('#stock .btn-primary').forEach(btn => {
        btn.addEventListener('click', () => {
            selectedProductId = btn.getAttribute('data-id');
        });
    });
}

// Función para simular la actualización del stock
async function simulateUpdateStock(productId, quantity, action) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const productIndex = products.findIndex(p => p.id === parseInt(productId));
            if (productIndex === -1) {
                reject(new Error('Producto no encontrado'));
                return;
            }

            
            if (action === 'add') {
                products[productIndex].stock += quantity;
            } else if (action === 'subtract') {
                if (quantity > products[productIndex].stock) {
                    reject(new Error('La cantidad ingresada es mayor al stock actual'));
                    return;
                }
                products[productIndex].stock -= quantity;
                if (products[productIndex].stock < 0) {
                    products[productIndex].stock = 0;
                }
            } else {
                reject(new Error('Acción no válida'));
                return;
            }

            // Actualiza la tabla para reflejar los cambios
            updateProductsTable();

            resolve();
        }, 1000);
    });
}

// Código para manejar el DOM y eventos
document.addEventListener('DOMContentLoaded', () => {
    const manageStockModal = document.getElementById('manageStockModal');
    const manageStockForm = document.getElementById('manageStockForm');
    const errorMessage = document.getElementById('error-message');

    if (manageStockModal) {
        manageStockModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            selectedProductId = button.getAttribute('data-id');
            errorMessage.classList.add('d-none'); 
        });
    } else {
        console.error('No se encontró el modal de gestión de stock.');
    }
    if (manageStockForm) {
        manageStockForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const quantityInput = document.getElementById('stockQuantity');
            const actionSelect = document.getElementById('stockAction');

            const quantity = parseInt(quantityInput.value.trim(), 10);
            const action = actionSelect.value;

            if (isNaN(quantity) || quantity <= 0) {
                errorMessage.textContent = 'Por favor, ingresa una cantidad válida.';
                errorMessage.classList.remove('d-none');
                return;
            }

            try {
                await simulateUpdateStock(selectedProductId, quantity, action);
                errorMessage.classList.add('d-none');
                bootstrap.Modal.getInstance(manageStockModal).hide();
            } catch (error) {
                if (error.message === 'La cantidad ingresada es mayor al stock actual') {
                    errorMessage.textContent = error.message;
                    errorMessage.classList.remove('d-none');
                } else {
                    console.error('Error al gestionar el stock:', error);
                }
            }
        });
    } else {
        console.error('No se encontró el formulario de gestión de stock.');
    }

    loadProducts(); // Cargar productos al inicio
});