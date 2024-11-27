let brands = [];
let models = [];
let currentModelId = null; 


async function fetchModels() {

    const response = await fetch('/LabWebFrontEnd/includes/admin/modelo.php');

    return response.json();

}

async function fetchBrands() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/marca.php');
    return response.json();
}

async function loadModels() {
    try {
        const modelos = await fetchModels();
        models = modelos;
        updateModelsTable();
    } catch (error) {
        showToastError(error.message);
    }
}

function updateModelsTable() {
    const tbody = document.querySelector('#modelsTable tbody');
    tbody.innerHTML = ''; 
    models.forEach(model => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${model.id_modelo}</td>
            <td>${model.nombre}</td>
            <td>${model.nombre_marca}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${model.id_modelo}" data-bs-toggle="modal" data-bs-target="#editModelModal"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${model.id_modelo}" data-bs-toggle="modal" data-bs-target="#deleteModelModal"><i class="fas fa-trash"></i> Eliminar</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => loadModelForEdit(btn.getAttribute('data-id')));
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => confirmDelete(btn.getAttribute('data-id')));
    });

}

async function loadBrands() {
    try {
        const marcas = await fetchBrands();
        brands = marcas;

        const brandSelect = document.getElementById("brandSelect");

        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.id_marca; 
            option.textContent = brand.nombre;  
            brandSelect.appendChild(option);
        });
        
    } catch (error) {
        showToastError(error.message);
    }
}

async function addModel(name, brandId) {
    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/modelo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombre: name, id_marca: brandId })
        });
        const result = await response.json();
        if (result.result) {
            loadModels();
        } else {
            throw new Error(result.error || 'Error al agregar el modelo');
        }
    } catch (error) {
        showToastError(error.message);
    }
}


async function editModel(id, name, brandId) {
    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/modelo.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_modelo: id, nombre: name, id_marca: brandId })
        });
        const result = await response.json();
        if (result.result) {
            loadModels();
        } else {
            throw new Error(result.error || 'Error al editar el modelo');
        }
    } catch (error) {
        showToastError(error.message);
    }
}

async function deleteModel(id) {
    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/modelo.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_modelo: id })
        });
        const result = await response.json();
        if (result.result) {
            loadModels();
        } else {
            throw new Error(result.error || 'Error al eliminar el modelo');
        }
    } catch (error) {
        showToastError(error.message);
    }
}

function confirmDelete(id) {
    currentModelId = id;
    const model = models.find(m => m.id_modelo == id);
    if (model) {
        document.getElementById('deleteModelName').innerText = model.nombre;
        document.getElementById('deleteModelId').innerText = id;
    } else {
        showToastError(error.message);
    }
}

function loadModelForEdit(id) {
    const model = models.find(m => m.id_modelo == id);
    if (!model) {
        showToastError(error.message);
        return;
    }

    const modelNameInput = document.getElementById('editModelName');
    const modelIdInput = document.getElementById('editModelId');
    const brandSelect = document.getElementById('editBrandSelect');

    if (modelNameInput && modelIdInput && brandSelect) {
        modelNameInput.value = model.nombre;
        modelIdInput.value = model.id_modelo;

        brandSelect.innerHTML = ''; 
        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.id_marca;
            option.textContent = brand.nombre;
            if (brand.nombre === model.brand) {
                option.selected = true;
            }
            brandSelect.appendChild(option);
        });
    } else {
        showToastError(error.message);
    }
}



function validateModelName(input) {
    const modelName = input.value.trim();

    input.classList.remove('is-invalid');
    if (input.nextElementSibling) {
        input.nextElementSibling.remove();
    }

    if (!modelName) {
        input.classList.add('is-invalid');
        const errorMsg = document.createElement('div');
        errorMsg.classList.add('invalid-feedback');
        errorMsg.innerText = 'El nombre del modelo es obligatorio';
        input.after(errorMsg);
        return false;
    }

    return true; 
}

function validateBrandSelection(select) {
    const selectedValue = select.value;

    select.classList.remove('is-invalid');
    if (select.nextElementSibling) {
        select.nextElementSibling.remove();
    }

    if (!selectedValue) {
        select.classList.add('is-invalid');
        const errorMsg = document.createElement('div');
        errorMsg.classList.add('invalid-feedback');
        errorMsg.innerText = 'Debes seleccionar una marca válida';
        select.after(errorMsg);
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


    document.getElementById('addModelForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const modelNameInput = document.getElementById('modelName');
        const brandSelectInput = document.getElementById('brandSelect');
        clearErrorOnInput(modelNameInput);

        if (!validateModelName(modelNameInput)) {
            return; 
        }

        if (!validateBrandSelection(brandSelectInput)) {
            return;
        }

        const selectedBrandId = brandSelectInput.value;

        try {
            await addModel(modelNameInput.value.trim(), selectedBrandId);
            modelNameInput.value = ''; 
            brandSelectInput.selectedIndex = 0;
            updateModelsTable();
            bootstrap.Modal.getInstance(document.getElementById('addModelModal')).hide();
        } catch (error) {
            showToastError(error.message);
        }
    });

    
    document.getElementById('editModelForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const editModelNameInput = document.getElementById('editModelName').value;
        const editModelIdInput = document.getElementById('editModelId').value;
        const editBrandSelect = document.getElementById('editBrandSelect').value;

            try {
                await editModel(editModelIdInput, editModelNameInput, editBrandSelect);
                updateModelsTable();
                bootstrap.Modal.getInstance(document.getElementById('editModelModal')).hide();
            } catch (error) {
                showToastError(error.message);
            }
         
    });


    document.getElementById('confirmDelete').addEventListener('click', async function () {
        try {
            await deleteModel(currentModelId);
            updateModelsTable();
            bootstrap.Modal.getInstance(document.getElementById('deleteModelModal')).hide();
        } catch (error) {
            showToastError(error.message);
        }
    });
    

    loadBrands();
    loadModels();
});