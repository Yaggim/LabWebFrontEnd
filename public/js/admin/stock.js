let selectedProductId = null;
let products = [];

async function fetchProducts() {

    const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php');

    return response.json();

}

async function fetchMovementTypes() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php?movement_types=true');
    return response.json();
}

async function loadMovementTypes() {
    try {
        const movementTypes = await fetchMovementTypes();
        const reasonSelect = document.getElementById('stockReason');
        reasonSelect.innerHTML = ''; // Limpiar opciones existentes

        movementTypes.forEach(type => {
            const option = document.createElement('option');
            option.value = type.id_movimientos_stock_tipo;
            option.textContent = type.detalle + ' - ' + type.tipo_movimimiento;
            reasonSelect.appendChild(option);
        });
    } catch (error) {
        showToastError(error.message);
    }
}

// Función para cargar los productos inicialmente
async function loadProducts() {
    try {
        const productos = await fetchProducts();
        products = productos;
        updateProductsTable();
    } catch (error) {
        showToastError(error.message);
    }
}

function showToastError(message) {
    const toastElement = document.getElementById('errorToast');
    const toastMessageElement = document.getElementById('errorToastMessage');
    toastMessageElement.innerText = message;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
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
            <td>${product.id_producto}</td>
            <td>${product.brand_name}</td>
            <td>${product.model_name}</td>
            <td>${product.category_name}</td>
            <td>${product.stock}</td>
            <td>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#manageStockModal" data-id="${product.id_producto}">
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

async function updateStock(productId, quantity, action, reason) {
    const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_producto: productId, cantidad: quantity, accion: action, id_movimiento_stock_tipo: reason })
    });
    console.log(response);
    const result = await response.json();
    console.log(result);
    if (!result.result) {
        throw new Error(result.error || 'Error al actualizar el stock');
    }
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
            const reasonInput = document.getElementById('stockReason');

            const quantity = parseInt(quantityInput.value.trim(), 10);
            const action = actionSelect.value;
            const reason = reasonInput.value.trim();

            if (isNaN(quantity) || quantity <= 0) {
                errorMessage.textContent = 'Por favor, ingresa una cantidad válida.';
                errorMessage.classList.remove('d-none');
                return;
            }

            try {
                await updateStock(selectedProductId, quantity, action, reason);
                errorMessage.classList.add('d-none');
                bootstrap.Modal.getInstance(manageStockModal).hide();
                loadProducts(); 
            } catch (error) {
                if (error.message === 'La cantidad ingresada es mayor al stock actual') {
                    errorMessage.textContent = error.message;
                    errorMessage.classList.remove('d-none');
                } else {
                    showToastError(error.message);
                }
            }
        });
    } else {
        console.error('No se encontró el formulario de gestión de stock.');
    }

    loadProducts(); 
    loadMovementTypes(); 
});