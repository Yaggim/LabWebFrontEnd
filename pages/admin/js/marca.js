import { marca as getBrands } from "./conexion.js";

let brands = [];
let currentBrandId = null; 

function deleteBrand(id) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const index = brands.findIndex(b => b.id == id);
            if (index !== -1) {
                brands.splice(index, 1);
                resolve();
            } else {
                reject(new Error('Marca no encontrada'));
            }
        }, 1000); 
    });
}

function editBrand(id, name) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const brand = brands.find(b => b.id == id);
            if (brand) {
                brand.name = name;
                resolve();
            } else {
                reject(new Error('Marca no encontrada'));
            }
        }, 1000); 
    });
}

function addBrand(name) {
    return new Promise((resolve) => {
        setTimeout(() => {
            const newId = brands.length ? brands[brands.length - 1].id + 1 : 1;
            brands.push({ id: newId, name });
            resolve();
        }, 1000); 
    });
}

async function loadBrands() {
    try {
        const marcas = await getBrands();
        brands = marcas;
        updateBrandsTable();
    } catch (error) {
        console.error('Error cargando las marcas:', error);
    }
}

function updateBrandsTable() {
    const tbody = document.querySelector('#brandsTable tbody');
    tbody.innerHTML = ''; 

    /*
    brands.forEach(brand => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${brand.id}</td>
            <td>${brand.name}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${brand.id}" data-bs-toggle="modal" data-bs-target="#editBrandModal"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${brand.id}" data-bs-toggle="modal" data-bs-target="#deleteBrandModal"><i class="fas fa-trash"></i> Eliminar</button>
            </td>
        `;
        tbody.appendChild(row);
    });
    */

    brands.forEach(brand => {
        // Crear una nueva fila
        const row = document.createElement('tr');

        // Crear las celdas de la tabla (td) para id y name
        const idCell = document.createElement('td');
        idCell.textContent = brand.id;

        const nameCell = document.createElement('td');
        nameCell.textContent = brand.name;

        // Crear la celda de acciones
        const actionsCell = document.createElement('td');

        // Crear el botón de editar
        const editButton = document.createElement('button');
        editButton.classList.add('btn', 'btn-warning', 'btn-sm', 'edit-btn');
        editButton.setAttribute('data-id', brand.id);
        editButton.setAttribute('data-bs-toggle', 'modal');
        editButton.setAttribute('data-bs-target', '#editBrandModal');
        
         // Crear el ícono dentro del botón de editar
         const editIcon = document.createElement('i');
         editIcon.classList.add('fas', 'fa-edit');
         editButton.appendChild(editIcon);
         editButton.appendChild(document.createTextNode(' Editar'));

        // Crear el botón de eliminar
        const deleteButton = document.createElement('button');
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm', 'delete-btn');
        deleteButton.setAttribute('data-id', brand.id);
        deleteButton.setAttribute('data-bs-toggle', 'modal');
        deleteButton.setAttribute('data-bs-target', '#deleteBrandModal');
        
        const deleteIcon = document.createElement('i');
        deleteIcon.classList.add('fas', 'fa-trash');
        deleteButton.appendChild(deleteIcon);
        deleteButton.innerHTML = ' Borrar';
        
        // Añadir los botones a la celda de acciones
        actionsCell.appendChild(editButton);
        actionsCell.appendChild(deleteButton);

        // Añadir las celdas a la fila
        row.appendChild(idCell);
        row.appendChild(nameCell);
        row.appendChild(actionsCell);

        // Añadir la fila al tbody
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
    const brand = brands.find(b => b.id == id);
    document.getElementById('editBrandName').value = brand.name;
    document.getElementById('editBrandId').value = brand.id;
}


function confirmDelete(id) {
    currentBrandId = id;
    const brand = brands.find(b => b.id == id);
    document.getElementById('deleteBrandName').innerText = brand.name;
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
            console.error('Error al agregar la marca:', error);
        }
    });


    document.getElementById('editBrandForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const editBrandNameInput = document.getElementById('editBrandName').value;
        const editBrandIdInput = document.getElementById('editBrandId').value;

            try {
                await editBrand(editBrandIdInput, editBrandNameInput);
                updateBrandsTable();
                bootstrap.Modal.getInstance(document.getElementById('editBrandModal')).hide();
            } catch (error) {
                console.error('Error al editar la marca:', error);
            }
         
    });


    document.getElementById('confirmDelete').addEventListener('click', async function () {
        try {
            await deleteBrand(currentBrandId);
            updateBrandsTable();
            bootstrap.Modal.getInstance(document.getElementById('deleteBrandModal')).hide();
        } catch (error) {
            console.error('Error al eliminar la marca:', error);
        }
    });


    loadBrands();
});