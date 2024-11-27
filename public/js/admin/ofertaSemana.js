let allProducts = [];
let ofertas = [];
let currentOfertaId = null;

async function fetchProducts() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php');
    return response.json();
}

async function fetchOfertas() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/ofertaSemana.php');
    return response.json();
}

async function loadOfertas() {
    try {
        const ofertasData = await fetchOfertas();
        ofertas = ofertasData;
        setTimeout(updateOfertasTable, 200);
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

async function loadProducts() {
    try {
        const products = await fetchProducts();
        allProducts = products;
        const productSelect = document.getElementById("ofertaProducto");
        const editProductSelect = document.getElementById("editOfertaProducto");

        productSelect.innerHTML = '';
        editProductSelect.innerHTML = '';

        products.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id_producto;
            option.textContent = `${product.brand_name} - ${product.model_name}`;
            productSelect.appendChild(option);

            const editOption = document.createElement('option');
            editOption.value = product.id_producto;
            editOption.textContent = `${product.brand_name} - ${product.model_name}`;
            editProductSelect.appendChild(editOption);
        });
    } catch (error) {
        showToastError(error.message);
    }
}

function updateOfertasTable() {
    
    const tbody = document.getElementById('ofertasTableBody');
    tbody.innerHTML = '';

    ofertas.forEach(oferta => {
        const product = allProducts.find(p => p.id_producto === oferta.id_producto);

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${oferta.id_oferta_semana}</td>
            <td>${oferta.descripcion}</td>
            <td>${oferta.descuento}%</td>
            <td>${product ? `${product.brand_name} - ${product.model_name}` : 'Producto no encontrado'}</td>
            <td>${oferta.fecha_inicio}</td>
            <td>${oferta.fecha_fin}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${oferta.id_oferta_semana}" data-bs-toggle="modal" data-bs-target="#editOfertaSemanaModal"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${oferta.id_oferta_semana}" data-bs-toggle="modal" data-bs-target="#deleteOfertaSemanaModal"><i class="fas fa-trash"></i> Eliminar</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => loadOfertaForEdit(btn.getAttribute('data-id')));
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => confirmDelete(btn.getAttribute('data-id')));
    });
}

document.getElementById('createOfertaSemanaForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const descripcion = document.getElementById('ofertaDescripcion').value.trim();
    const descuento = parseInt(document.getElementById('ofertaDescuento').value, 10);
    const id_producto = parseInt(document.getElementById('ofertaProducto').value, 10);
    const fecha_inicio = document.getElementById('ofertaFechaInicio').value + ' 00:00:00';;
    const fecha_fin = document.getElementById('ofertaFechaFin').value + ' 23:59:59';

    const newOferta = {
        descripcion,
        descuento,
        id_producto,
        fecha_inicio,
        fecha_fin
    };

    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/ofertaSemana.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(newOferta)
        });
        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.error || 'Error al agregar la oferta');
        }
        loadOfertas();
        bootstrap.Modal.getInstance(document.getElementById('addOfertaSemanaModal')).hide();
    } catch (error) {
        showToastError(error.message);
    }
});

document.getElementById('editOfertaSemanaForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const descripcion = document.getElementById('editOfertaDescripcion').value.trim();
    const descuento = parseInt(document.getElementById('editOfertaDescuento').value, 10);
    const id_producto = parseInt(document.getElementById('editOfertaProducto').value, 10);
    const fecha_inicio = document.getElementById('editOfertaFechaInicio').value + ' 00:00:00';
    const fecha_fin = document.getElementById('editOfertaFechaFin').value + ' 23:59:59';

    const updatedOferta = {
        id_oferta_semana: currentOfertaId,
        descripcion,
        descuento,
        id_producto,
        fecha_inicio,
        fecha_fin
    };

    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/ofertaSemana.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updatedOferta)
        });
        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.error || 'Error al actualizar la oferta');
        }
        loadOfertas();
        bootstrap.Modal.getInstance(document.getElementById('editOfertaSemanaModal')).hide();
    } catch (error) {
        showToastError(error.message);
    }
});

async function loadOfertaForEdit(id) {
    const oferta = ofertas.find(o => o.id_oferta_semana == id);
    if (!oferta) {
        console.error("Oferta no encontrada");
        return;
    }

    currentOfertaId = id;
    document.getElementById('editOfertaDescripcion').value = oferta.descripcion;
    document.getElementById('editOfertaDescuento').value = oferta.descuento;
    document.getElementById('editOfertaProducto').value = oferta.id_producto;

    const fechaInicio = new Date(oferta.fecha_inicio).toISOString().split('T')[0];
    const fechaFin = new Date(oferta.fecha_fin).toISOString().split('T')[0];

    document.getElementById('editOfertaFechaInicio').value = fechaInicio;
    document.getElementById('editOfertaFechaFin').value = fechaFin;
}

async function deleteOferta(id) {
    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/ofertaSemana.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_oferta_semana: id })
        });
        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.error || 'Error al eliminar la oferta');
        }
        loadOfertas();
    } catch (error) {
        showToastError(error.message);
    }
}

function confirmDelete(id) {
    currentOfertaId = id;
    const oferta = ofertas.find(o => o.id_oferta_semana == id);
    document.getElementById('deleteOfertaName').innerText = oferta.descripcion;
    document.getElementById('deleteOfertaId').innerText = id;
}

document.getElementById('confirmDelete').addEventListener('click', async function () {
    try {
        await deleteOferta(currentOfertaId);
        bootstrap.Modal.getInstance(document.getElementById('deleteOfertaSemanaModal')).hide();
    } catch (error) {
        showToastError(error.message);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    loadOfertas();
});