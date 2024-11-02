let brands = [];
let currentBrandId = null;

async function fetchBrands() {
    const response = await fetch('../../includes/admin/marca.php');
    return response.json();
}

async function addBrand(name) {
    try {
        const response = await fetch('../../includes/admin/marca.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombre: name })
        });
        const result = await response.json();
        if (result.result) {
            loadBrands();
        } else {
            throw new Error(result.error || 'Error al agregar la marca');
        }
    } catch (error) {
        showToastError(error.message);
    }
}

async function editBrand(id, name) {
    try {
        const response = await fetch('../../includes/admin/marca.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_marca: id, nombre: name })
        });
        const result = await response.json();
        if (result.result) {
            loadBrands();
        } else {
            throw new Error(result.error || 'Error al editar la marca');
        }
    } catch (error) {
        showToastError(error.message);
    }
}

async function deleteBrand(id) {
    try {
        const response = await fetch('../../includes/admin/marca.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_marca: id })
        });
        const result = await response.json();
        if (result.result) {
            loadBrands();
        } else {
            throw new Error(result.error || 'Error al eliminar la marca');
        }
    } catch (error) {
        showToastError(error.message);
    }
}

async function loadBrands() {
    try {
        const response = await fetch('../../includes/admin/marca.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
        });
        const marcas = await response.json();
        brands = marcas;
        updateBrandsTable();
    } catch (error) {
        showToastError(error.message);
    }
}

function updateBrandsTable() {
    const tbody = document.querySelector('#brandsTable tbody');
    tbody.innerHTML = '';

    brands.forEach(brand => {
        const row = document.createElement('tr');

        const idCell = document.createElement('td');
        idCell.textContent = brand.id_marca;

        const nameCell = document.createElement('td');
        nameCell.textContent = brand.nombre;

        const actionsCell = document.createElement('td');

        const editButton = document.createElement('button');
        editButton.classList.add('btn', 'btn-warning', 'btn-sm', 'edit-btn');
        editButton.setAttribute('data-id', brand.id_marca);
        editButton.setAttribute('data-bs-toggle', 'modal');
        editButton.setAttribute('data-bs-target', '#editBrandModal');
        const editIcon = document.createElement('i');
        editIcon.classList.add('fas', 'fa-edit');
        editButton.appendChild(editIcon);
        editButton.appendChild(document.createTextNode(' Editar'));

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm', 'delete-btn');
        deleteButton.setAttribute('data-id', brand.id_marca);
        deleteButton.setAttribute('data-bs-toggle', 'modal');
        deleteButton.setAttribute('data-bs-target', '#deleteBrandModal');
        const deleteIcon = document.createElement('i');
        deleteIcon.classList.add('fas', 'fa-trash');
        deleteButton.appendChild(deleteIcon);
        deleteButton.appendChild(document.createTextNode(' Eliminar'));

        actionsCell.appendChild(editButton);
        actionsCell.appendChild(deleteButton);

        row.appendChild(idCell);
        row.appendChild(nameCell);
        row.appendChild(actionsCell);

        tbody.appendChild(row);
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => loadBrandForEdit(btn.getAttribute('data-id')));
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => confirmDelete(btn.getAttribute('data-id')));
    });
}

function loadBrandForEdit(id) {
    const brand = brands.find(b => b.id_marca == id);
    document.getElementById('editBrandName').value = brand.nombre;
    document.getElementById('editBrandId').value = brand.id_marca;
}

function confirmDelete(id) {
    currentBrandId = id;
    const brand = brands.find(b => b.id_marca == id);
    document.getElementById('deleteBrandName').innerText = brand.nombre;
    document.getElementById('deleteBrandId').innerText = id;
}

function validateBrandName(input) {
    const brandName = input.value.trim();

    input.classList.remove('is-invalid');
    if (input.nextElementSibling) {
        input.nextElementSibling.remove();
    }

    if (!brandName) {
        input.classList.add('is-invalid');
        const errorMsg = document.createElement('div');
        errorMsg.classList.add('invalid-feedback');
        errorMsg.innerText = 'El nombre de la marca es obligatorio';
        input.after(errorMsg);
        return false;
    }

    return true;
}

function clearErrorOnInput(input) {
    input.addEventListener('input', () => {
        if (input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
            if (input.nextElementSibling) {
                input.nextElementSibling.remove();
            }
        }
    });
}


function showToastError(message) {
    const toastElement = document.getElementById('errorToast');
    const toastMessageElement = document.getElementById('errorToastMessage');
    toastMessageElement.innerText = message;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('addBrandForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const brandNameInput = document.getElementById('brandName');
        clearErrorOnInput(brandNameInput);
        
        if (!validateBrandName(brandNameInput)) {
            return;
        }

        try {
            await addBrand(brandNameInput.value.trim());
            brandNameInput.value = '';
            updateBrandsTable();
            bootstrap.Modal.getInstance(document.getElementById('addBrandModal')).hide();
        } catch (error) {
            showToastError(error.message);
        }
    });

    document.getElementById('editBrandForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const editBrandNameInput = document.getElementById('editBrandName');
        const editBrandIdInput = document.getElementById('editBrandId');
        clearErrorOnInput(editBrandNameInput);

        if (!validateBrandName(editBrandNameInput)) {
            return;
        }

        try {
            await editBrand(editBrandIdInput.value, editBrandNameInput.value.trim());
            updateBrandsTable();
            bootstrap.Modal.getInstance(document.getElementById('editBrandModal')).hide();
        } catch (error) {
            showToastError(error.message);
        }
    });

    document.getElementById('confirmDelete').addEventListener('click', async function () {
        try {
            await deleteBrand(currentBrandId);
            updateBrandsTable();
            bootstrap.Modal.getInstance(document.getElementById('deleteBrandModal')).hide();
        } catch (error) {
            showToastError(error.message);
        }
    });

    loadBrands();
});

