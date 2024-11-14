let ventas = [];
let productos = [];
let combos = [];
let estados = [];
let currentVentaId = null;

async function fetchVentas() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/ventas.php');
    return response.json();
}

async function fetchProductos() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php');
    return response.json();
}

async function fetchCombos() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/combo.php');
    return response.json();
}

async function fetchEstados() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/estadosVentas.php');
    return response.json();
}

async function loadVentas() {
    try {
        const ventasData = await fetchVentas();
        ventas = ventasData;
        updateVentasTable();
    } catch (error) {
        showToastError(error.message);
    }
}

async function loadProductos() {
    try {
        const productosData = await fetchProductos();
        productos = productosData;
        const productSelect = document.getElementById("filterProducto");
        productSelect.innerHTML = '<option value="">Todos los productos</option>';
        productos.forEach(producto => {
            const option = document.createElement('option');
            option.value = producto.id_producto;
            option.textContent = `${producto.brand_name} - ${producto.model_name}`;
            productSelect.appendChild(option);
        });
    } catch (error) {
        showToastError(error.message);
    }
}

async function loadCombos() {
    try {
        const combosData = await fetchCombos();
        combos = combosData;
        const comboSelect = document.getElementById("filterCombo");
        comboSelect.innerHTML = '<option value="">Todos los combos</option>';
        combos.forEach(combo => {
            const option = document.createElement('option');
            option.value = combo.id_combo;
            option.textContent = combo.nombre;
            comboSelect.appendChild(option);
        });
    } catch (error) {
        showToastError(error.message);
    }
}

async function loadEstados() {
    try {
        const estadosData = await fetchEstados();
        estados = estadosData;
        const estadoSelect = document.getElementById("editEstadoVenta");
        estadoSelect.innerHTML = '';
        estados.forEach(estado => {
            const option = document.createElement('option');
            option.value = estado.id_estados_ventas;
            option.textContent = estado.estado;
            estadoSelect.appendChild(option);
        });
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

function updateVentasTable() {
    const tbody = document.getElementById('ventasTableBody');
    tbody.innerHTML = '';

    ventas.forEach(venta => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${venta.id_venta_cabecera}</td>
            <td>${venta.fecha_venta}</td>
            <td>${venta.precio_total_pesos}</td>
            <td>${venta.persona_nombre}</td>
            <td>${venta.estado}</td>
            <td>
                <button class="btn btn-info btn-sm" data-id="${venta.id_venta_cabecera}" data-bs-toggle="modal" data-bs-target="#ventaDetallesModal"><i class="fas fa-eye"></i> Ver Detalles</button>
                <button class="btn btn-warning btn-sm" data-id="${venta.id_venta_cabecera}" data-bs-toggle="modal" data-bs-target="#editEstadoVentaModal"><i class="fas fa-edit"></i> Editar Estado</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    document.querySelectorAll('.btn-info').forEach(btn => {
        btn.addEventListener('click', () => loadVentaDetalles(btn.getAttribute('data-id')));
    });

    document.querySelectorAll('.btn-warning').forEach(btn => {
        btn.addEventListener('click', () => loadVentaForEdit(btn.getAttribute('data-id')));
    });
}

async function loadVentaDetalles(id) {
    try {
        const response = await fetch(`/LabWebFrontEnd/includes/admin/ventas.php?id_venta_cabecera=${id}`);
        const detalles = await response.json();
        const tbody = document.getElementById('ventaDetallesTableBody');
        tbody.innerHTML = '';

        detalles.forEach(detalle => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${detalle.producto_nombre || detalle.combo_nombre}</td>
                <td>${detalle.cantidad}</td>
                <td>${detalle.precio_unitario_pesos}</td>
            `;
            tbody.appendChild(row);
        });
    } catch (error) {
        showToastError(error.message);
    }
}

async function loadVentaForEdit(id) {
    const venta = ventas.find(v => v.id_venta_cabecera == id);
    if (!venta) {
        console.error("Venta no encontrada");
        return;
    }

    currentVentaId = id;
    document.getElementById('editEstadoVenta').value = venta.id_estado_venta;
}

async function updateVentaEstado() {
    const id_estado_venta = document.getElementById('editEstadoVenta').value;

    const updatedVenta = {
        id_venta_cabecera: currentVentaId,
        id_estado_venta
    };

    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/ventas.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updatedVenta)
        });
        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.error || 'Error al actualizar el estado de la venta');
        }
        loadVentas();
        bootstrap.Modal.getInstance(document.getElementById('editEstadoVentaModal')).hide();
    } catch (error) {
        showToastError(error.message);
    }
}

document.getElementById('filterButton').addEventListener('click', async function () {
    const producto = document.getElementById('filterProducto').value;
    const combo = document.getElementById('filterCombo').value;

    try {
        const response = await fetch(`/LabWebFrontEnd/includes/admin/ventas.php?producto=${producto}&combo=${combo}`);
        const ventasData = await response.json();
        ventas = ventasData;
        updateVentasTable();
    } catch (error) {
        showToastError(error.message);
    }
});

document.getElementById('updateEstadoVentaButton').addEventListener('click', async function () {
    await updateVentaEstado();
});

document.addEventListener('DOMContentLoaded', () => {
    loadVentas();
    loadProductos();
    loadCombos();
    loadEstados();
});