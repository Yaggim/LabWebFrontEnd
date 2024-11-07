let brands = [];
let categories = [];
let models = [];
let products = [];
let currentProductId = null;

async function fetchProducts() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php');
    return response.json();
}

async function fetchModels() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/modelo.php');
    return response.json();
}

async function fetchCategories() {
    const response = await fetch('/LabWebFrontEnd/includes/admin/categoria.php');
    return response.json();
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
        const productos = await fetchProducts();
        products = productos;
        updateProductsTable();
    } catch (error) {
        showToastError(error.message);
    }
}

function updateProductsTable() {
    const tbody = document.querySelector('#productsTable tbody');
    tbody.innerHTML = '';
    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.id_producto}</td>
            <td>${product.brand_name}</td>
            <td>${product.model_name}</td>
            <td>${product.category_name}</td>
            <td>${product.stock}</td>
            <td>${product.precio_usd}</td>
            <td>${product.habilitado ? 'Sí' : 'No'}</td>
            <td>${product.imagenes[0]}</td>
            <td>${product.imagenes[1]}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${product.id_producto}" data-bs-toggle="modal" data-bs-target="#editProductModal"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${product.id_producto}" data-bs-toggle="modal" data-bs-target="#deleteProductModal"><i class="fas fa-trash"></i> Eliminar</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => loadProductForEdit(btn.getAttribute('data-id')));
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => confirmDelete(btn.getAttribute('data-id')));
    });
}

async function loadCategories() {
    try {
        const categorias = await fetchCategories();
        categories = categorias;
        const categorySelect = document.getElementById("productCategory");
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id_categoria;
            option.textContent = category.nombre;
            categorySelect.appendChild(option);
        });
    } catch (error) {
        showToastError(error.message);
    }
}

async function loadModels() {
    try {
        const modelos = await fetchModels(); 
        models = modelos;

        const modelSelect = document.getElementById("productModel");

        models.forEach(model => {
            const option = document.createElement('option');
            option.value = model.id_modelo; 
            option.textContent = model.nombre;  
            modelSelect.appendChild(option);
        });
        
    } catch (error) {
        showToastError(error.message);
    }
}

async function addProduct(product) {
    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(product)
        });
        const result = await response.json();
        if (result.result) {
            loadProducts();
        } else {
            throw new Error(result.error || 'Error al agregar el producto');
        }
    } catch (error) {
        showToastError(error.message);
    }
}

async function editProduct(id, updatedProduct) {
    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_producto: id, ...updatedProduct })
        });
        const result = await response.json();
        if (result.result) {
            loadProducts();
        } else {
            throw new Error(result.error || 'Error al editar el producto');
        }
    } catch (error) {
        showToastError(error.message);
    }
}

async function deleteProduct(id) {
    try {
        const response = await fetch('/LabWebFrontEnd/includes/admin/producto.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_producto: id })
        });
        const result = await response.json();
        if (result.result) {
            loadProducts();
        } else {
            throw new Error(result.error || 'Error al eliminar el producto');
        }
    } catch (error) {
        showToastError(error.message);
    }
}

function confirmDelete(id) {
    currentProductId = id;
    document.getElementById('deleteProductId').innerText = id;
}

function removeImageField(button) {
    button.parentElement.remove();
}

function loadProductForEdit(id) {
    const product = products.find(p => p.id_producto == id);
    if (!product) {
        console.error('Producto no encontrado');
        return;
    }

    const productModelSelect = document.getElementById('editProductModel');
    const productCategorySelect = document.getElementById('editProductCategory');
    const productStockInput = document.getElementById('editProductStock');
    const productPriceUSDInput = document.getElementById('editProductPriceUSD');
    const productEnabledCheckbox = document.getElementById('editProductEnabled');
    const productDescriptionTextarea = document.getElementById('editProductDescription');
    const editImageContainer = document.getElementById('editImageContainer');

    if (productModelSelect && productCategorySelect &&
        productStockInput && productPriceUSDInput && productEnabledCheckbox &&
        productDescriptionTextarea && editImageContainer) {

        productModelSelect.innerHTML = ''; 
        models.forEach(model => {
            const option = document.createElement('option');
            option.value = model.id_modelo;
            option.textContent = model.nombre;
            if (model.id_modelo === product.model_id) {
                option.selected = true;
            }
            productModelSelect.appendChild(option);
        });

        productCategorySelect.innerHTML = ''; 
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id_categoria;
            option.textContent = category.nombre;
            if (category.id_categoria === product.category_id) {
                option.selected = true;
            }
            productCategorySelect.appendChild(option);
        });

        productStockInput.value = product.stock || '';
        productPriceUSDInput.value = product.precio_usd || '';
        productEnabledCheckbox.checked = product.habilitado;
        productDescriptionTextarea.value = product.descripcion;

        // Mostrar las imágenes existentes
        editImageContainer.innerHTML = '';
        product.imagenes.forEach((url, index) => {
            const div = document.createElement('div');
            div.className = 'mb-2';
            div.innerHTML = `
                <input type="text" class="form-control mb-2" value="${url}" placeholder="URL de imagen">
                <button type="button" class="btn btn-danger btn-sm remove-image">Eliminar</button>
            `;
            editImageContainer.appendChild(div);
        });
        
        // Agrega el evento después de que hayas agregado los botones
        editImageContainer.querySelectorAll('.remove-image').forEach(button => {
            button.addEventListener('click', function() {
                removeImageField(this);
            });
        });

        document.getElementById('editProductId').value = id;
    } else {
        console.error('No se encontraron los elementos de edición en el DOM.');
    }
}

document.getElementById('addImageField').addEventListener('click', function() {
    const container = document.getElementById('imageContainer');
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control mb-2';
    input.placeholder = 'URL de imagen';
    container.insertBefore(input, this);
});

document.getElementById('editImageField').addEventListener('click', function() {
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control mb-2';
    input.placeholder = 'URL de imagen';
    document.getElementById('editImageContainer').appendChild(input);
});

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('addProductForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const imageInputs = document.querySelectorAll('#imageContainer input[type="text"]');
        const images = Array.from(imageInputs).map(input => input.value).filter(url => url);

        const newProduct = {
            id_modelo: parseInt(document.getElementById('productModel').value),
            id_categoria: parseInt(document.getElementById('productCategory').value),
            stock: parseInt(document.getElementById('productStock').value),
            precio_usd: parseFloat(document.getElementById('productPriceUSD').value) || null,
            habilitado: document.getElementById('productEnabled').checked ? 1 : 0,
            descripcion: document.getElementById('productDescription').value.trim(),
            imagenes: images
        };

        try {
            await addProduct(newProduct);
            document.getElementById('addProductForm').reset();
            updateProductsTable();
            bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
        } catch (error) {
            showToastError(error.message);
        }
    });

    document.getElementById('editProductForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const editProductId = document.getElementById('editProductId').value;
        const imageFields = document.querySelectorAll('#editImageContainer input[type="text"]');
        const images = Array.from(imageFields).map(input => input.value).filter(url => url);

        const updatedProduct = {
            id_modelo: parseInt(document.getElementById('editProductModel').value),
            id_categoria: parseInt(document.getElementById('editProductCategory').value),
            stock: parseFloat(document.getElementById('editProductStock').value),
            precio_usd: parseFloat(document.getElementById('editProductPriceUSD').value) || null,
            habilitado: document.getElementById('editProductEnabled').checked ? 1 : 0,
            descripcion: document.getElementById('editProductDescription').value.trim(),
            imagenes: images
        };

        try {
            if (editProductId) {
                await editProduct(editProductId, updatedProduct);
                updateProductsTable();
                bootstrap.Modal.getInstance(document.getElementById('editProductModal')).hide();
            } else {
                console.error('ID del producto actual no definido.');
            }
        } catch (error) {
            showToastError(error.message);
        }
    });

    document.getElementById('confirmDelete').addEventListener('click', async function () {
        if (currentProductId) {
            try {
                await deleteProduct(currentProductId);
                updateProductsTable();
                bootstrap.Modal.getInstance(document.getElementById('deleteProductModal')).hide();
            } catch (error) {
                showToastError(error.message);
            }
        } else {
            console.error('ID del producto actual no definido.');
        }
    });

    loadCategories();
    loadModels();
    loadProducts();
});