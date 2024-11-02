let categories = [];
let currentCategoryId = null;

async function fetchCategories() {
    const response = await fetch('../../includes/admin/categoria.php');
    if (!response.ok) {
        throw new Error('Error al obtener las categorías');
    }
    return response.json();
}

async function addCategory(name) {
    try {
        const response = await fetch('../../includes/admin/categoria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombre: name })
        });
        const result = await response.json();
        if (result.result) {
            loadCategories();
        } else {
            throw new Error(result.error || 'Error al agregar la categoría');
        }
    } catch (error) {
        displayError('addCategoryError', error.message);
    }
}

async function editCategory(id, name) {
    try {
        const response = await fetch('../../includes/admin/categoria.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_categoria: id, nombre: name })
        });
        const result = await response.json();
        if (result.result) {
            loadCategories();
        } else {
            throw new Error(result.error || 'Error al editar la categoría');
        }
    } catch (error) {
        displayError('editCategoryError', error.message);
    }
}

async function deleteCategory(id) {
    try {
        const response = await fetch('../../includes/admin/categoria.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_categoria: id })
        });
        const result = await response.json();
        if (result.result) {
            loadCategories();
        } else {
            throw new Error(result.error || 'Error al eliminar la categoría');
        }
    } catch (error) {
        displayError('deleteCategoryError', error.message);
    }
}

async function loadCategories() {
    try {
        const categorias = await fetchCategories();
        categories = categorias;
        updateCategoriesTable();
    } catch (error) {
        console.error('Error cargando las categorías:', error);
    }
}

function updateCategoriesTable() {
    const tbody = document.querySelector('#categoryTable tbody');
    tbody.innerHTML = '';

    categories.forEach(category => {
        const row = document.createElement('tr');

        const idCell = document.createElement('td');
        idCell.textContent = category.id_categoria;

        const nameCell = document.createElement('td');
        nameCell.textContent = category.nombre;

        const actionsCell = document.createElement('td');

        const editButton = document.createElement('button');
        editButton.classList.add('btn', 'btn-warning', 'btn-sm', 'edit-btn');
        editButton.setAttribute('data-id', category.id_categoria);
        editButton.setAttribute('data-bs-toggle', 'modal');
        editButton.setAttribute('data-bs-target', '#editCategoryModal');
        const editIcon = document.createElement('i');
        editIcon.classList.add('fas', 'fa-edit');
        editButton.appendChild(editIcon);
        editButton.appendChild(document.createTextNode(' Editar'));

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm', 'delete-btn');
        deleteButton.setAttribute('data-id', category.id_categoria);
        deleteButton.setAttribute('data-bs-toggle', 'modal');
        deleteButton.setAttribute('data-bs-target', '#deleteCategoryModal');
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
        btn.addEventListener('click', () => loadCategoryForEdit(btn.getAttribute('data-id')));
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => confirmDelete(btn.getAttribute('data-id')));
    });
}

function loadCategoryForEdit(id) {
    const category = categories.find(c => c.id_categoria == id);
    if (category) {
        document.getElementById('editCategoryName').value = category.nombre;
        document.getElementById('editCategoryId').value = category.id_categoria;
    } else {
        console.error('Categoría no encontrada');
    }
}

function confirmDelete(id) {
    currentCategoryId = id;
    const category = categories.find(c => c.id_categoria == id);
    if (category) {
        document.getElementById('deleteCategoryName').innerText = category.nombre;
        document.getElementById('deleteCategoryId').innerText = id;
    } else {
        console.error('Categoría no encontrada');
    }
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

function displayError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    errorElement.innerText = message;
    errorElement.style.display = 'block';
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

        const editCategoryNameInput = document.getElementById('editCategoryName');
        const editCategoryIdInput = document.getElementById('editCategoryId');
        clearErrorOnInput(editCategoryNameInput);

        if (!validateCategoryName(editCategoryNameInput)) {
            return;
        }

        try {
            await editCategory(editCategoryIdInput.value, editCategoryNameInput.value.trim());
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