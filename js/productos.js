document.addEventListener('DOMContentLoaded', () => {
    const productContainer = document.getElementById('product-container');
    const searchInput = document.getElementById('search');
    const categoryList = document.getElementById('category-list');
    const clearFiltersButton = document.getElementById('clear-filters');

    const productos = [
        {
            "id": 1,
            "brand": { "id": 1, "name": "Kingston" },
            "model": { "id": 1, "name": "Fury Beast" },
            "category": { "id": 1, "name": "Memorias" },
            "priceARS": 1000,
            "priceUSD": 10,
            "enabled": true,
            "stock": 10,
            "image": "../images/memoria2.jpg",
            "description": "Descripción del producto"
        },
        {
            "id": 2,
            "brand": { "id": 2, "name": "Corsair" },
            "model": { "id": 2, "name": "Vengeance LPX" },
            "category": { "id": 2, "name": "Memorias" },
            "priceARS": 2000,
            "priceUSD": 20,
            "enabled": true,
            "stock": 5,
            "image": "../images/vengeance.jpg",
            "description": "Descripción del producto 2"
        },
        {
            "id": 3,
            "brand": { "id": 3, "name": "HyperX" },
            "model": { "id": 3, "name": "Fury DDR4" },
            "category": { "id": 3, "name": "Categoría Ejemplo3" },
            "priceARS": 3000,
            "priceUSD": 30,
            "enabled": true,
            "stock": 8,
            "image": "../images/memoria1.jpg",
            "description": "Descripción del producto 3"
        },
        {
            "id": 4,
            "brand": { "id": 4, "name": "WD" },
            "model": { "id": 4, "name": "Black SN850X" },
            "category": { "id": 1, "name": "Memorias SSD" },
            "priceARS": 4000,
            "priceUSD": 40,
            "enabled": true,
            "stock": 15,
            "image": "../images/WD-SN850X.png",
            "description": "Descripción del producto 4"
        },
        {
            "id": 5,
            "brand": { "id": 5, "name": "Samsung" },
            "model": { "id": 5, "name": "990 PRO" },
            "category": { "id": 1, "name": "Memorias SSD" },
            "priceARS": 5000,
            "priceUSD": 50,
            "enabled": false,
            "stock": 20,
            "image": "../images/Samsung-990-Pro.png",
            "description": "Descripción del producto 5"
        },
        {
            "id": 6,
            "brand": { "id": 6, "name": "Acer" },
            "model": { "id": 6, "name": "Nitro ED270R" },
            "category": { "id": 3, "name": "Monitores" },
            "priceARS": 6000,
            "priceUSD": 60,
            "enabled": true,
            "stock": 12,
            "image": "../images/acer.jpg",
            "description": "Descripción del producto 6"
        },
        {
            "id": 7,
            "brand": { "id": 7, "name": "Viedwedge" },
            "model": { "id": 7, "name": "FHD" },
            "category": { "id": 1, "name": "Monitores" },
            "priceARS": 7000,
            "priceUSD": 70,
            "enabled": true,
            "stock": 0,
            "image": "../images/viedwedge.jpg",
            "description": "Descripción del producto 7"
        },
        {
            "id": 8,
            "brand": { "id": 8, "name": "Sceptre" },
            "model": { "id": 8, "name": "C248W-1920RN" },
            "category": { "id": 2, "name": "Monitores" },
            "priceARS": 8000,
            "priceUSD": 80,
            "enabled": false,
            "stock": 30,
            "image": "../images/sceptre.jpg",
            "description": "Monitores"
        },
        {
            "id": 9,
            "brand": { "id": 9, "name": "Redragon" },
            "model": { "id": 9, "name": "Horus K618" },
            "category": { "id": 3, "name": "Periféricos" },
            "priceARS": 9000,
            "priceUSD": 90,
            "enabled": true,
            "stock": 25,
            "image": "../images/teclado.jpg",
            "description": "Descripción del producto 9"
        },
        {
            "id": 10,
            "brand": { "id": 10, "name": "Redragon" },
            "model": { "id": 10, "name": "Star Pro M917GB" },
            "category": { "id": 1, "name": "Periféricos" },
            "priceARS": 10000,
            "priceUSD": 100,
            "enabled": true,
            "stock": 40,
            "image": "../images/mouse.jpg",
            "description": "Descripción del producto 10"
        }
    ];

    const categorias = [
        { "id": 1, "name": "Memorias SSD" },
        { "id": 2, "name": "Monitores" },
        { "id": 3, "name": "Periféricos" }
    ];

    displayCategories(categorias);
    displayProducts(productos);

    searchInput.addEventListener('input', () => {
        const filteredProducts = filterProducts(productos, searchInput.value);
        displayProducts(filteredProducts);
    });

    categoryList.addEventListener('click', (event) => {
        if (event.target.tagName === 'A') {
            const categoryId = event.target.dataset.categoryId;
            const filteredProducts = filterProductsById(productos, categoryId);
            displayProducts(filteredProducts);
        }
    });

    clearFiltersButton.addEventListener('click', () => {
        searchInput.value = '';
        displayProducts(productos);
    });

    function displayCategories(categorias) {
        categoryList.innerHTML = '';
        categorias.forEach(categoria => {
            const categoryItem = document.createElement('li');
            categoryItem.className = 'nav-item';
            categoryItem.innerHTML = `
                <a class="nav-link" href="#" data-category-id="${categoria.id}">${categoria.name}</a>
            `;
            categoryList.appendChild(categoryItem);
        });
    }

    function displayProducts(products) {
        productContainer.innerHTML = '';
        products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'col-md-4 mb-4';
            productCard.innerHTML = `
                <div class="card">
                    <img src="${product.image}" alt="${product.model.name}" style="object-fit: contain; width: 100%; height: 200px;">
                    <div class="card-body">
                        <h5 class="card-title">${product.brand.name} ${product.model.name}</h5>
                        <p class="card-text">${product.description}</p>
                        <p class="card-text">Precio: $${product.priceARS} / $${product.priceUSD} USD</p>
                        <p class="card-text">Stock: ${product.stock}</p>
                        <button class="btn btn-primary">Seleccionar</button>
                    </div>
                </div>
            `;
            productContainer.appendChild(productCard);
        });
    }

    function filterProducts(products, searchTerm) {
        return products.filter(product => 
            product.model.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            product.brand.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            `${product.brand.name} ${product.model.name}`.toLowerCase().includes(searchTerm.toLowerCase())
        );
    }

    function filterProductsById(products, categoryId) {
        return products.filter(product => product.category.id === parseInt(categoryId));
    }
});