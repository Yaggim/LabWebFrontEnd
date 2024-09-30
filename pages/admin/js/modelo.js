import { marca as getBrands, modelo as getModels } from "./conexion.js";

let brands = [];
let models = [];
let currentModelId = null; 

async function loadModels() {
    try {
        const modelos = await getModels();
        models = modelos;
        updateModelsTable();
    } catch (error) {
        console.error('Error cargando los modelos:', error);
    }
}

function updateModelsTable() {
    const tbody = document.querySelector('#modelsTable tbody');
    tbody.innerHTML = ''; 
    models.forEach(model => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${model.id}</td>
            <td>${model.name}</td>
            <td>${model.brand}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${model.id}" data-bs-toggle="modal" data-bs-target="#editModelModal"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${model.id}" data-bs-toggle="modal" data-bs-target="#deleteModelModal"><i class="fas fa-trash"></i> Eliminar</button>
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
        const marcas = await getBrands(); 
        brands = marcas;

        const brandSelect = document.getElementById("brandSelect");

        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.id; 
            option.textContent = brand.name;  
            brandSelect.appendChild(option);
        });
        
    } catch (error) {
        console.error('Error cargando las marcas:', error);
    }
}


function addModel(name, brandId) {
    return new Promise((resolve) => {
        setTimeout(() => {
            const newId = models.length ? models[models.length - 1].id + 1 : 1;
            const brand = brands.find(b => b.id === parseInt(brandId));
            models.push({ id: newId, name, brand: brand ? brand.name : 'Desconocida'});
            resolve();
        }, 1000); 
    });
}


function editModel(id, name, brandId) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const model = models.find(m => m.id == id); 
            const brand = brands.find(b => b.id === parseInt(brandId));
            if (model) {
                model.name = name;
                model.brand = brand.name; 
                resolve();
            } else {
                reject(new Error('Modelo no encontrado'));
            }
        }, 1000); 
    });
}

function deleteModel(id) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const index = models.findIndex(b => b.id == id);
            if (index !== -1) {
                models.splice(index, 1);
                resolve();
            } else {
                reject(new Error('Modelo no encontrado'));
            }
        }, 1000); 
    });
}




function confirmDelete(id) {
    currentModelId = id;
    const model = models.find(b => b.id == id);
    document.getElementById('deleteModelName').innerText = model.name;
    document.getElementById('deleteModelId').innerText = id;
}

function loadModelForEdit(id) {
    const model = models.find(m => m.id == id);
    if (!model) {
        console.error('Modelo no encontrado');
        return;
    }

    const modelNameInput = document.getElementById('editModelName');
    const modelIdInput = document.getElementById('editModelId');
    const brandSelect = document.getElementById('editBrandSelect');

    if (modelNameInput && modelIdInput && brandSelect) {
        modelNameInput.value = model.name;
        modelIdInput.value = model.id;

        brandSelect.innerHTML = ''; 
        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.id;
            option.textContent = brand.name;
            if (brand.name === model.brand) {
                option.selected = true;
            }
            brandSelect.appendChild(option);
        });
    } else {
        console.error('No se encontraron los elementos de edición en el DOM.');
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
            console.error('Error al agregar el modelo:', error);
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
                console.error('Error al editar el modelo:', error);
            }
         
    });


    document.getElementById('confirmDelete').addEventListener('click', async function () {
        try {
            await deleteModel(currentModelId);
            updateModelsTable();
            bootstrap.Modal.getInstance(document.getElementById('deleteModelModal')).hide();
        } catch (error) {
            console.error('Error al eliminar el modelo:', error);
        }
    });
    

    loadBrands();
    loadModels();
});