import { categoria as getCategories, producto as getProductsByCategory, combos as getCombos } from "./conexion.js";

let categories = [];
let combos = [];
let allProducts = []; // Para manejar todos los productos cargados
let selectedProducts = []; // Productos seleccionados para el nuevo combo
let currentComboId = null; 

async function loadCombos(){
    try {
        const combo = await getCombos();
        combos = combo;
        updateCombosTable();
    } catch (error) {
        console.error('Error cargando los combos:', error);
    }
}

async function loadCategories() {
    try {
        const categorias = await getCategories();
        categories = categorias;
        const categorySelect = document.getElementById("productCategory");
        
        categorias.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });

        // Cargar productos de la primera categoría por defecto
        const defaultCategoryId = categories[0].id; // Puedes ajustar esto según la categoría que desees como predeterminada
        await loadProductsByCategory(defaultCategoryId);
        

        categorySelect.addEventListener('change', async (event) => {
            const selectedCategoryId = event.target.value;
            await loadProductsByCategory(selectedCategoryId);
        });
    } catch (error) {
        console.error('Error cargando las categorías:', error);
    }
}


async function loadProductsByCategory(categoryId) {
    try {
        const products = await getProductsByCategory(categoryId);
        const productSelect = document.getElementById("comboProducts");
        
        productSelect.innerHTML = ''; // Limpiar los productos anteriores
        const filteredProducts = products.filter(product => product.category.id === parseInt(categoryId));

        filteredProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = `${product.brand.name} - ${product.model.name}`;
            productSelect.appendChild(option);
        });

        // Guardar todos los productos en allProducts para referencia
        allProducts = [...allProducts, ...filteredProducts];
    } catch (error) {
        console.error('Error cargando los productos:', error);
    }
}

// Función para agregar productos a la lista seleccionada
function addProductToList(product) {
    const alreadySelected = selectedProducts.find(p => p.id === product.id);

    if (!alreadySelected) {
        // Completar detalles del producto si no están disponibles
        const productDetails = allProducts.find(p => p.id === product.id) || product;
        selectedProducts.push(productDetails);
        renderSelectedProductsTable();
    }
}
// Función para eliminar productos de la lista seleccionada
function removeProductFromList(productId) {
    selectedProducts = selectedProducts.filter(p => p.id !== productId);
    renderSelectedProductsTable();
}

// Función para renderizar la tabla de productos seleccionados
function renderSelectedProductsTable() {
    const tableBody = document.querySelector("#selectedProductsTable tbody");
    tableBody.innerHTML = ''; // Limpiar la tabla

    selectedProducts.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.brand.name}</td>
            <td>${product.model.name}</td>
            <td>${product.category.name}</td>
            <td><button class="btn btn-danger btn-sm">Eliminar</button></td>
        `;
        tableBody.appendChild(row);

        // Añadir evento de eliminación al botón
        const deleteButton = row.querySelector('button');
        deleteButton.addEventListener('click', () => removeProductFromList(product.id));
    });
}

// Agregar productos seleccionados al hacer clic en el botón
document.getElementById('addProductBtn').addEventListener('click', () => {
    const productSelect = document.getElementById("comboProducts");
    const selectedOptions = Array.from(productSelect.selectedOptions);

    const warningMessage = document.getElementById('warningMessage');
    if (selectedOptions.length === 0) {
        warningMessage.classList.remove('d-none');
        return; 
    } else {
        warningMessage.classList.add('d-none');
    }

    selectedOptions.forEach(option => {
        const productId = option.value;
        const product = allProducts.find(p => p.id == productId); 
        addProductToList(product);
    });
});

async function addCombo(combo) {
    console.log('Combo recibido:', combo);
    
    if (!combo || !combo.name || !Array.isArray(combo.products)) {
        console.error('Combo inválido:', combo);
        return Promise.reject('Datos del combo inválidos');
    }

    // Completar detalles del producto usando `allProducts`
    const completeProducts = combo.products.map(product => {
        const productDetails = allProducts.find(p => p.id === product.id);
        return productDetails || { id: product.id }; // Manejar caso en el que el producto no se encuentra
    });

    const newCombo = {
        ...combo,
        products: completeProducts
    };

    return new Promise((resolve) => {
        setTimeout(() => {
            console.log('Combos antes de agregar:', combos);
            const newId = combos.length ? combos[combos.length - 1].id + 1 : 1;
            combos.push({ ...newCombo, id: newId });
            resolve();
        }, 1000);
    });
}

function deleteCombo(id) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const index = combos.findIndex(c => c.id == id);
            if (index !== -1) {
                combos.splice(index, 1);
                resolve();
            } else {
                reject(new Error('Combo no encontrado'));
            }
        }, 1000); 
    });
}

function confirmDelete(id) {
    currentComboId = id;
    const combo = combos.find(c => c.id == id);
    document.getElementById('deleteComboName').innerText = combo.name;
    document.getElementById('deleteComboId').innerText = id;
}


// Llamada para cargar las categorías cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {

    document.getElementById('createComboForm').addEventListener('submit', async function (e) {

        e.preventDefault();

        const comboName = document.getElementById('comboName').value.trim();
        
        if (selectedProducts.length === 0) {
            document.getElementById('warningMessage').classList.remove('d-none');
            return;
        }

        const newCombo = {
            name: comboName,
            products: selectedProducts.map(p => ({ id: p.id }))
        };


        try {
            const result = await addCombo(newCombo);

            document.getElementById('createComboForm').reset();
            selectedProducts = []; // Limpiar productos seleccionados
            renderSelectedProductsTable(); // Limpiar la tabla de productos seleccionados
            updateCombosTable();
            bootstrap.Modal.getInstance(document.getElementById('addComboModal')).hide();
        } catch (error) {
            console.error('Error al agregar el combo:', error);
        }
    });

    document.getElementById('confirmDelete').addEventListener('click', async function () {
        try {
            await deleteCombo(currentComboId);
            updateCombosTable();
            bootstrap.Modal.getInstance(document.getElementById('deleteComboModal')).hide();
        } catch (error) {
            console.error('Error al eliminar el Combo:', error);
        }
    });


    loadCategories();
    loadCombos();
    loadEditCategories();
});

async function loadEditCategories() {
    try {
        const categorias = await getCategories();
        categories = categorias;
        const categorySelect = document.getElementById("editProductCategory");
        
        categorias.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });

       
       
        const defaultCategoryId = categories[0].id; 
        await loadProductsEditByCategory(defaultCategoryId);

        categorySelect.addEventListener('change', async (event) => {
            const selectedCategoryId = event.target.value;
            await loadProductsByCategory(selectedCategoryId);
        });
    } catch (error) {
        console.error('Error cargando las categorías:', error);
    }
}

async function loadProductsEditByCategory(categoryId) {
    try {
        const products = await getProductsByCategory(categoryId);
        const productSelect = document.getElementById("editComboProducts");
        
        productSelect.innerHTML = ''; // Limpiar los productos anteriores
        const filteredProducts = products.filter(product => product.category.id === parseInt(categoryId));

        filteredProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = `${product.brand.name} - ${product.model.name}`;
            productSelect.appendChild(option);
        });

        allProducts = [...allProducts, ...filteredProducts];
    } catch (error) {
        console.error('Error cargando los productos:', error);
    }
}

async function loadCombosForEdit(id) {
  const combo = combos.find((c) => c.id == id);
  if (!combo) {
    console.error("Combo no encontrado");
    return;
  }



  const comboNameInput = document.getElementById("editComboName");
  const comboIdInput = document.getElementById("editComboId");
  const productCategorySelect = document.getElementById("editProductCategory");
  const editComboProducts = document.getElementById("editComboProducts");
  const editSelectedProductsTable = document
    .getElementById("editSelectedProductsTable")
    .getElementsByTagName("tbody")[0];

  // Renderizar los productos seleccionados en el modal de edición
  editSelectedProductsTable.innerHTML = "";
  combo.products.forEach((product) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>${product.brand.name}</td>
            <td>${product.model.name}</td>
            <td>${product.category.name}</td>
            <td><button class="btn btn-danger btn-sm remove-btn" data-id="${product.id}">Eliminar</button></td>
        `;
    editSelectedProductsTable.appendChild(row);
  });
}


function updateCombosTable() {
    const tbody = document.querySelector('#combosTable tbody');
    tbody.innerHTML = ''; 

    combos.forEach(combo => {
        const row = document.createElement('tr');

        // Validar y manejar productos
        const productNames = combo.products.map(product => {
            const brandName = product.brand && product.brand.name ? product.brand.name : 'Marca desconocida';
            const modelName = product.model && product.model.name ? product.model.name : 'Modelo desconocido';
            return `${brandName} - ${modelName}`;
        }).join(', ');

        // Crear fila de tabla
        row.innerHTML = `
            <td>${combo.id}</td>
            <td>${combo.name}</td>
            <td>${productNames}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${combo.id}" data-bs-toggle="modal" data-bs-target="#editCombosModal"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${combo.id}" data-bs-toggle="modal" data-bs-target="#deleteComboModal"><i class="fas fa-trash"></i> Eliminar</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    // Agregar eventos a botones
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => loadCombosForEdit(btn.getAttribute('data-id')));
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => confirmDelete(btn.getAttribute('data-id')));
    });
}
