let categories = [];
let combos = [];
let allProducts = [];
let selectedProducts = [];
let currentComboId = null;

async function fetchCombos() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/combo.php');
    return response.json();
}

async function loadCombos() {
    try {
        const combo = await fetchCombos();
        combos = combo;
        updateCombosTable();
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

async function fetchCategories() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/categoria.php');
    return response.json();
}

async function loadCategories() {
    try {
        const categorias = await fetchCategories();
        categories = categorias;
        const categorySelect = document.getElementById("productCategory");
        categorySelect.innerHTML = '';
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id_categoria;
            option.textContent = category.nombre;
            categorySelect.appendChild(option);
        });

        const defaultCategoryId = categories[0].id_categoria;
        await loadProductsByCategory(defaultCategoryId);

        categorySelect.addEventListener('change', async (event) => {
            const selectedCategoryId = event.target.value;
            await loadProductsByCategory(selectedCategoryId);
        });
    } catch (error) {
        showToastError(error.message);
    }
}

async function fetchProducts() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php');
    return response.json();
}

async function loadProductsByCategory(categoryId) {
    try {
        const products = await fetchProducts();
        const productSelect = document.getElementById("comboProducts");

        productSelect.innerHTML = '';
        const filteredProducts = products.filter(product => product.id_categoria === parseInt(categoryId));

        filteredProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id_producto;
            option.textContent = `${product.brand_name} - ${product.model_name}`;
            productSelect.appendChild(option);
        });

        // Limpiar allProducts antes de agregar nuevos productos
        allProducts = filteredProducts;
    } catch (error) {
        showToastError(error.message);
    }
}

function addProductToList(product) {
    const alreadySelected = selectedProducts.find(p => p.id_producto === product.id_producto);

    if (!alreadySelected) {
        const productDetails = allProducts.find(p => p.id_producto === product.id_producto) || product;
        selectedProducts.push({ ...productDetails, cantidad: 0 }); // Cantidad inicial de 1
        renderSelectedProductsTable();
    }
}

function removeProductFromList(productId) {
    selectedProducts = selectedProducts.filter(p => p.id_producto !== productId);
    renderSelectedProductsTable();
}

function renderSelectedProductsTable(tableId = 'selectedProductsTable') {
    const tableBody = document.querySelector(`#${tableId} tbody`);
    tableBody.innerHTML = '';

    selectedProducts.forEach(product => {
        const maxQuantity = product.cantidad + product.stock; // Suma de cantidad actual y stock disponible

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.brand_name}</td>
            <td>${product.model_name}</td>
            <td>${product.category_name}</td>
            <td><input type="number" class="form-control product-quantity" data-id="${product.id_producto}" value="${product.cantidad}" min="1" max="${maxQuantity}"></td>
            <td><button class="btn btn-danger btn-sm">Eliminar</button></td>
        `;
        tableBody.appendChild(row);

        const deleteButton = row.querySelector('button');
        deleteButton.addEventListener('click', () => {
            removeProductFromList(product.id_producto);
            renderSelectedProductsTable(tableId);
        });

        const quantityInput = row.querySelector('.product-quantity');
        quantityInput.addEventListener('change', (event) => {
            const newQuantity = parseInt(event.target.value, 10);
            const productId = parseInt(event.target.getAttribute('data-id'), 10);
            const productIndex = selectedProducts.findIndex(p => p.id_producto === productId);
            if (productIndex !== -1) {
                selectedProducts[productIndex].cantidad = newQuantity;
            }
        });
    });
}

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
        const product = allProducts.find(p => p.id_producto == productId);
        addProductToList(product);
    });
});

async function addCombo(combo) {
    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/combo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(combo)
        });
        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.error || 'Error al agregar el combo');
        }
        loadCombos();
        return result;
    } catch (error) {
        showToastError(error.message);
        throw error;
    }
}

async function deleteCombo(id) {
    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/combo.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_combo: id })
        });
        const result = await response.json();
        if (result.result) {
            loadCombos();
        } else {
            throw new Error(result.error || 'Error al eliminar el combo');
        }
    } catch (error) {
        showToastError(error.message);
        throw error;
    }
}

function confirmDelete(id) {
    currentComboId = id;
    const combo = combos.find(c => c.id_combo == id);
    document.getElementById('deleteComboName').innerText = combo.nombre;
    document.getElementById('deleteComboId').innerText = id;
}

document.addEventListener('DOMContentLoaded', () => {

    document.getElementById('addImageField').addEventListener('click', function() {
        const container = document.getElementById('imageContainer');
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control mb-2';
        input.placeholder = 'URL de imagen';
        container.appendChild(input);
    });
    
    document.getElementById('editImageField').addEventListener('click', function() {
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control mb-2';
        input.placeholder = 'URL de imagen';
        document.getElementById('editImageContainer').appendChild(input);
    });

    document.getElementById('createComboForm').addEventListener('submit', async function (e) {
        e.preventDefault();
    
        const comboName = document.getElementById('comboName').value.trim();
        const comboDiscount = parseInt(document.getElementById('comboDiscount').value, 10);
        const imageInputs = document.querySelectorAll('#imageContainer input[type="text"]');
        const images = Array.from(imageInputs).map(input => input.value).filter(url => url);
    
        if (selectedProducts.length === 0) {
            document.getElementById('warningMessage').classList.remove('d-none');
            return;
        }
    
        const newCombo = {
            nombre: comboName,
            habilitado: document.getElementById('comboProductEnabled').checked ? 1 : 0,
            descuento: comboDiscount,
            imagenes: images,
            productos: selectedProducts.map(p => ({ id_producto: p.id_producto, cantidad: p.cantidad }))
        };
    
        try {
            const result = await addCombo(newCombo);
    
            document.getElementById('createComboForm').reset();
            selectedProducts = [];
            renderSelectedProductsTable();
            updateCombosTable();
            bootstrap.Modal.getInstance(document.getElementById('addComboModal')).hide();
        } catch (error) {
            showToastError(error.message);
        }
    });
    
    document.getElementById('editCombosForm').addEventListener('submit', async function (e) {
        e.preventDefault();
    
        const comboId = document.getElementById('editComboId').value;
        const comboName = document.getElementById('editComboName').value.trim();
        const comboDiscount = parseInt(document.getElementById('editComboDiscount').value, 10);
        const enabled = document.getElementById('editComboProductEnabled').checked ? 1 : 0;
        const imageFields = document.querySelectorAll('#editImageContainer input[type="text"]');
        const images = Array.from(imageFields).map(input => input.value).filter(url => url);
    
        const updatedCombo = {
            id_combo: comboId,
            nombre: comboName,
            habilitado: enabled,
            descuento: comboDiscount,
            imagenes: images,
            productos: selectedProducts.map(p => ({ id_producto: p.id_producto, cantidad: p.cantidad }))
        };
    
        try {
            const response = await fetch('/LabWebFrontEnd/includes/admin/combo.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(updatedCombo)
            });
    
            const result = await response.json();
            if (!response.ok) {
                throw new Error(result.error || 'Error al actualizar el combo');
            }
    
            document.getElementById('editCombosForm').reset();
            updateCombosTable();
            bootstrap.Modal.getInstance(document.getElementById('editCombosModal')).hide();
        } catch (error) {
            showToastError(error.message);
        }
    });


    document.getElementById('confirmDelete').addEventListener('click', async function () {
        try {
            await deleteCombo(currentComboId);
            updateCombosTable();
            bootstrap.Modal.getInstance(document.getElementById('deleteComboModal')).hide();
        } catch (error) {
            showToastError(error.message);
        }
    });

    loadCategories();
    loadCombos();
    loadEditCategories();
});

async function loadEditCategories() {
    try {
        const categorias = await fetchCategories();
        categories = categorias;
        const categorySelect = document.getElementById("editProductCategory");

        categorySelect.innerHTML = ''; // Limpiar las opciones antes de agregar nuevas
        categorias.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id_categoria;
            option.textContent = category.nombre;
            categorySelect.appendChild(option);
        });

        const defaultCategoryId = categories[0].id_categoria;
        await loadProductsEditByCategory(defaultCategoryId);

        categorySelect.addEventListener('change', async (event) => {
            const selectedCategoryId = event.target.value;
            await loadProductsEditByCategory(selectedCategoryId);
        });
    } catch (error) {
        showToastError(error.message);
    }
}

async function loadProductsEditByCategory(categoryId) {
    try {
        const products = await fetchProducts();
        const productSelect = document.getElementById("editComboProducts");

        productSelect.innerHTML = ''; // Limpiar las opciones antes de agregar nuevas
        const filteredProducts = products.filter(product => product.id_categoria === parseInt(categoryId));

        filteredProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id_producto;
            option.textContent = `${product.brand_name} - ${product.model_name}`;
            productSelect.appendChild(option);
        });

        allProducts = filteredProducts;
    } catch (error) {
        showToastError(error.message);
    }
}

async function loadCombosForEdit(id) {
    const combo = combos.find((c) => c.id_combo == id);
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
    const editImageContainer = document.getElementById('editImageContainer');

    comboNameInput.value = combo.nombre;
    comboIdInput.value = combo.id_combo;
    document.getElementById('editComboProductEnabled').checked = combo.habilitado;
    document.getElementById('editComboDiscount').value = combo.descuento;

    editImageContainer.innerHTML = '';
    combo.imagenes.forEach(url => {
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control mb-2';
        input.value = url;
        editImageContainer.appendChild(input);
    });

    selectedProducts = combo.productos.map(product => ({
        ...product,
        cantidad: product.cantidad // Asegurarse de usar la cantidad correcta
    }));

    renderSelectedProductsTable('editSelectedProductsTable');

    editComboProducts.innerHTML = '';
    allProducts.forEach(product => {
        const option = document.createElement('option');
        option.value = product.id_producto;
        option.textContent = `${product.brand_name} - ${product.model_name}`;
        editComboProducts.appendChild(option);
    });
}

document.getElementById('editAddProductBtn').addEventListener('click', () => {
    const productSelect = document.getElementById("editComboProducts");
    const selectedOptions = Array.from(productSelect.selectedOptions);

    const warningMessage = document.getElementById('editWarningMessage');
    if (selectedOptions.length === 0) {
        warningMessage.classList.remove('d-none');
        return;
    } else {
        warningMessage.classList.add('d-none');
    }

    selectedOptions.forEach(option => {
        const productId = option.value;
        const product = allProducts.find(p => p.id_producto == productId);
        addProductToList(product);
        renderSelectedProductsTable('editSelectedProductsTable');
    });
});

function updateCombosTable() {
    const tbody = document.querySelector('#combosTable tbody');
    tbody.innerHTML = '';

    combos.forEach(combo => {
        const row = document.createElement('tr');

        const productNames = combo.productos.map(product => {
            const brandName = product.brand_name ? product.brand_name : 'Marca desconocida';
            const modelName = product.model_name ? product.model_name : 'Modelo desconocido';
            return `${brandName} - ${modelName} (Cantidad: ${product.cantidad})`;
        }).join(', ');

        const imageUrls = combo.imagenes.map(url => `${url}`).join(', ');

        row.innerHTML = `
            <td>${combo.id_combo}</td>
            <td>${combo.nombre}</td>
            <td>${productNames}</td>
            <td>${combo.habilitado ? 'Sí' : 'No'}</td>
            <td>${combo.descuento}</td>
            <td>${imageUrls}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${combo.id_combo}" data-bs-toggle="modal" data-bs-target="#editCombosModal"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${combo.id_combo}" data-bs-toggle="modal" data-bs-target="#deleteComboModal"><i class="fas fa-trash"></i> Eliminar</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => loadCombosForEdit(btn.getAttribute('data-id')));
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => confirmDelete(btn.getAttribute('data-id')));
    });
}