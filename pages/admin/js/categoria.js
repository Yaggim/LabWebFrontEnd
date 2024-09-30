import { categoria as getCategories } from "./conexion.js";

let categories = [];
let currentCategoryId = null; 

function deleteCategory(id) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const index = categories.findIndex(c => c.id == id);
            if (index !== -1) {
                categories.splice(index, 1);
                resolve();
            } else {
                reject(new Error('Categoría no encontrada'));
            }
        }, 1000); // Simula un retraso de 1 segundo
    });
}

function editCategory(id, name) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const category = categories.find(c => c.id == id);
            if (category) {
                category.name = name;
                resolve();
            } else {
                reject(new Error('Categoría no encontrada'));
            }
        }, 1000); // Simula un retraso de 1 segundo
    });
}

function addCategory(name) {
    return new Promise((resolve) => {
        setTimeout(() => {
            const newId = categories.length ? categories[categories.length - 1].id + 1 : 1;
            categories.push({ id: newId, name });
            resolve();
        }, 1000); // Simula un retraso de 1 segundo
    });
}

async function loadCategories() {
    try {
        const categorias = await getCategories();
        categories = categorias;
        updateCategoriesTable();
    } catch (error) {
        console.error('Error cargando las categorías:', error);
    }
}

function updateCategoriesTable() {
    const tbody = document.querySelector('#categoriesTable tbody');
    tbody.innerHTML = ''; 
    categories.forEach(category => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${category.id}</td>
            <td>${category.name}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${category.id}" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${category.id}" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"><i class="fas fa-trash"></i> Eliminar</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => loadCategoryForEdit(btn.getAttribute('data-id')));
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => confirmDelete(btn.getAttribute('data-id')));
    });
}


function loadCategoryForEdit(id) {
    const category = categories.find(c => c.id == id);
    document.getElementById('editCategoryName').value = category.name;
    document.getElementById('editCategoryId').value = category.id;
}


function confirmDelete(id) {
    currentCategoryId = id;
    const category = categories.find(c => c.id == id);
    document.getElementById('deleteCategoryName').innerText = category.name;
    document.getElementById('deleteCategoryId').innerText = id;
}



function validateCategoryName(input) {
    const categoryName = input.value.trim();

    input.classList.remove('is-invalid');
    if (input.nextElementSibling) {
        input.nextElementSibling.remove();
    }

    if (!categoryName) {
        input.classList.add('is-invalid');
        const errorMsg = document.createElement('div');
        errorMsg.classList.add('invalid-feedback');
        errorMsg.innerText = 'El nombre de la categoría es obligatorio';
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


    document.getElementById('addCategoryForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const categoryNameInput = document.getElementById('categoryName');
        clearErrorOnInput(categoryNameInput);

        if (!validateCategoryName(categoryNameInput)) {
            return; 
        }

        try {
            await addCategory(categoryNameInput.value.trim());
            categoryNameInput.value = ''; 
            updateCategoriesTable();
            bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
        } catch (error) {
            console.error('Error al agregar la categoría:', error);
        }
    });


    document.getElementById('editCategoryForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const editCategoryNameInput = document.getElementById('editCategoryName').value;
        const editCategoryIdInput = document.getElementById('editCategoryId').value;

            try {
                await editCategory(editCategoryIdInput, editCategoryNameInput);
                updateCategoriesTable();
                bootstrap.Modal.getInstance(document.getElementById('editCategoryModal')).hide();
            } catch (error) {
                console.error('Error al editar la categoría:', error);
            }
         
    });


    document.getElementById('confirmDelete').addEventListener('click', async function () {
        try {
            await deleteCategory(currentCategoryId);
            updateCategoriesTable();
            bootstrap.Modal.getInstance(document.getElementById('deleteCategoryModal')).hide();
        } catch (error) {
            console.error('Error al eliminar la categoría:', error);
        }
    });


    loadCategories();
});