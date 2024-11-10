import { marca as getBrands, categoria as getCategories, modelo as getModels, producto as getProducts } from "./conexion.js";

let brands = [];
let categories = [];
let models = [];
let products = [];
let currentProductId = null;

async function loadProducts() {
    try {
        const productos = await getProducts();
        products = productos;
        updateProductsTable();
    } catch (error) {
        console.error('Error cargando los productos:', error);
    }
}

function updateProductsTable() {
    const tbody = document.querySelector('#productsTable tbody');
    tbody.innerHTML = '';
    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.id}</td>
            <td>${product.brand.name}</td>
            <td>${product.model.name}</td>
            <td>${product.category.name}</td>
            <td>${product.priceARS}</td>
            <td>${product.priceUSD}</td>
            <td>${product.enabled ? 'Sí' : 'No'}</td>
            <td>${product.image}</td>
            <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${product.id}" data-bs-toggle="modal" data-bs-target="#editProductModal"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${product.id}" data-bs-toggle="modal" data-bs-target="#deleteProductModal"><i class="fas fa-trash"></i> Eliminar</button>
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

async function loadBrands() {
    try {
        const marcas = await getBrands(); 
        brands = marcas;

        const brandSelect = document.getElementById("productBrand");

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

async function loadCategories() {
    try {
        const categorias = await getCategories();
        categories = categorias;
        const categorySelect = document.getElementById("productCategory");
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando las categorías:', error);
    }
}

async function loadModels() {
    try {
        const modelos = await getModels(); 
        models = modelos;

        const modelSelect = document.getElementById("productModel");

        models.forEach(model => {
            const option = document.createElement('option');
            option.value = model.id; 
            option.textContent = model.name;  
            modelSelect.appendChild(option);
        });
        
    } catch (error) {
        console.error('Error cargando las marcas:', error);
    }
}

function addProduct(product) {
    return new Promise((resolve) => {
        setTimeout(() => {
            const newId = products.length ? products[products.length - 1].id + 1 : 1;
            products.push({ ...product, id: newId });
            resolve();
        }, 1000);
    });
}

function editProduct(id, updatedProduct) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const product = products.find(p => p.id == id);
            if (product) {
                Object.assign(product, updatedProduct);
                resolve();
            } else {
                reject(new Error('Producto no encontrado'));
            }
        }, 1000);
    });
}

function deleteProduct(id) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            const index = products.findIndex(p => p.id == id);
            if (index !== -1) {
                products.splice(index, 1);
                resolve();
            } else {
                reject(new Error('Producto no encontrado'));
            }
        }, 1000); 
    });
}

function confirmDelete(id) {
    currentProductId = id;
    document.getElementById('deleteProductId').innerText = id;
}

function loadProductForEdit(id) {
    const product = products.find(p => p.id == id);
    if (!product) {
        console.error('Producto no encontrado');
        return;
    }

    // Obtener los elementos del formulario de edición
    const productBrandSelect = document.getElementById('editProductBrand');
    const productModelSelect = document.getElementById('editProductModel');
    const productCategorySelect = document.getElementById('editProductCategory');
    const productPriceARSInput = document.getElementById('editProductPriceARS');
    const productPriceUSDInput = document.getElementById('editProductPriceUSD');
    const productEnabledCheckbox = document.getElementById('editProductEnabled');
    const productImageInput = document.getElementById('editProductImage');
    const productDescriptionTextarea = document.getElementById('editProductDescription');

    // Verificar que los elementos existen en el DOM
    if (productBrandSelect && productModelSelect && productCategorySelect &&
        productPriceARSInput && productPriceUSDInput && productEnabledCheckbox &&
        productImageInput && productDescriptionTextarea) {

        // Llenar los selects de marca, modelo y categoría con las opciones disponibles
        productBrandSelect.innerHTML = ''; 
        brands.forEach(brand => {
            const option = document.createElement('option');
            option.value = brand.id;
            option.textContent = brand.name;
            if (brand.id === product.brand.id) {
                option.selected = true;
            }
            productBrandSelect.appendChild(option);
        });

        productModelSelect.innerHTML = ''; 
        models.forEach(model => {
            const option = document.createElement('option');
            option.value = model.id;
            option.textContent = model.name;
            if (model.id === product.model.id) {
                option.selected = true;
            }
            productModelSelect.appendChild(option);
        });

        productCategorySelect.innerHTML = ''; 
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            if (category.id === product.category.id) {
                option.selected = true;
            }
            productCategorySelect.appendChild(option);
        });

        // Asignar los valores del producto a los campos del formulario
        productPriceARSInput.value = product.priceARS;
        productPriceUSDInput.value = product.priceUSD || '';
        productEnabledCheckbox.checked = product.enabled;
        productImageInput.value = product.image;
        productDescriptionTextarea.value = product.description;
        document.getElementById('editProductId').value = id;
    } else {
        console.error('No se encontraron los elementos de edición en el DOM.');
    }
}


document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('addProductForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const newProduct = {
            brand: {
                id: parseInt(document.getElementById('productBrand').value),
                name: brands.find(b => b.id == document.getElementById('productBrand').value).name
            },
            model: {
                id: parseInt(document.getElementById('productModel').value),
                name: models.find(m => m.id == document.getElementById('productModel').value).name
            },
            category: {
                id: parseInt(document.getElementById('productCategory').value),
                name: categories.find(c => c.id == document.getElementById('productCategory').value).name
            },
            priceARS: parseFloat(document.getElementById('productPriceARS').value),
            priceUSD: parseFloat(document.getElementById('productPriceUSD').value) || null,
            enabled: document.getElementById('productEnabled').checked,
            image: document.getElementById('productImage').value.trim(),
            description: document.getElementById('productDescription').value.trim(),
        };

        try {
            await addProduct(newProduct);
            document.getElementById('addProductForm').reset();
            updateProductsTable();
            bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
        } catch (error) {
            console.error('Error al agregar el producto:', error);
        }
    });

    document.getElementById('editProductForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const editProductId  = document.getElementById('editProductId').value;

        const updatedProduct = {
            brand: {
                id: parseInt(document.getElementById('editProductBrand').value),
                name: brands.find(b => b.id == document.getElementById('editProductBrand').value).name
            },
            model: {
                id: parseInt(document.getElementById('editProductModel').value),
                name: models.find(m => m.id == document.getElementById('editProductModel').value).name
            },
            category: {
                id: parseInt(document.getElementById('editProductCategory').value),
                name: categories.find(c => c.id == document.getElementById('editProductCategory').value).name
            },
            priceARS: parseFloat(document.getElementById('editProductPriceARS').value),
            priceUSD: parseFloat(document.getElementById('editProductPriceUSD').value) || null,
            enabled: document.getElementById('editProductEnabled').checked,
            image: document.getElementById('editProductImage').value.trim(),
            description: document.getElementById('editProductDescription').value.trim(),
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
            console.error('Error al editar el producto:', error);
        }
    });

    document.getElementById('confirmDelete').addEventListener('click', async function () {
        if (currentProductId) {
            try {
                await deleteProduct(currentProductId);
                updateProductsTable();
                bootstrap.Modal.getInstance(document.getElementById('deleteProductModal')).hide();
            } catch (error) {
                console.error('Error al eliminar el producto:', error);
            }
        } else {
            console.error('ID del producto actual no definido.');
        }
    });




    loadBrands();
    loadCategories();
    loadModels();
    loadProducts();
});