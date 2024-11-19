document.addEventListener('DOMContentLoaded', () => {
    const productContainer = document.getElementById('product-container');
    const searchInput = document.getElementById('search');
    const categoryList = document.getElementById('category-list');
    const clearFiltersButton = document.getElementById('clear-filters');

    let productos = [];
    let categorias = [];
    let combos = [];
    let dolarBlue = 0;

    async function fetchDolarBlue() {
        const response = await fetch("https://dolarapi.com/v1/dolares/blue");
        const data = await response.json();
        dolarBlue = data.venta;
    }
    
    async function fetchProducts() {
        const response = await fetch('./includes/admin/producto.php');
        return response.json();
    }

    async function fetchCategories() {
        const response = await fetch('./includes/admin/categoria.php');
        return response.json();
    }

    async function fetchCombos() {
        const response = await fetch('/LabWebFrontEnd/includes/admin/combo.php');
        return response.json();
    }
    
    async function loadProductsAndCategories() {
        try {
            await fetchDolarBlue();
            productos = await fetchProducts();
            categorias = await fetchCategories();
            combos = await fetchCombos();
            displayCategories(categorias);
            displayProducts(productos, combos);
        } catch (error) {
            console.error('Error cargando productos y categorías:', error);
        }
    }

    if (searchInput && categoryList && clearFiltersButton) {
        loadProductsAndCategories();

        searchInput.addEventListener('input', () => {
            const filteredProducts = filterProducts(productos, searchInput.value);
            const filteredCombos = filterCombos(combos, searchInput.value);
            displayProducts(filteredProducts, filteredCombos);
        });

        categoryList.addEventListener('click', (event) => {
            if (event.target.tagName === 'A') {
                const categoryId = event.target.dataset.categoryId;
                if (categoryId === 'combos') {
                    displayProducts([], combos);
                } else {
                    const filteredProducts = filterProductsById(productos, categoryId);
                    displayProducts(filteredProducts, []);
                }
            }
        });

        clearFiltersButton.addEventListener('click', () => {
            searchInput.value = '';
            displayProducts(productos, combos);
        });
    }

    function displayCategories(categorias) {
        categoryList.innerHTML = '';
        categorias.forEach(categoria => {
            const categoryItem = document.createElement('li');
            categoryItem.className = 'nav-item';
            categoryItem.innerHTML = `
                <a class="nav-link" href="#" data-category-id="${categoria.id_categoria}">${categoria.nombre}</a>
            `;
            categoryList.appendChild(categoryItem);
        });

        // Agregar opción para filtrar por combos
        const combosItem = document.createElement('li');
        combosItem.className = 'nav-item';
        combosItem.innerHTML = `
            <a class="nav-link" href="#" data-category-id="combos">Combos</a>
        `;
        categoryList.appendChild(combosItem);
    };

    function displayProducts(products, combos) {
        productContainer.innerHTML = '';

        // Mostrar productos
        products.forEach(product => {
            if (product.habilitado) {
                const productCard = document.createElement('div');
                productCard.className = 'col-md-4 mb-4';
                const descripcionTruncada = product.descripcion.length > 100 ? product.descripcion.substring(0, 100) + '...' : product.descripcion;

                const nombreMarcaModelo = product.brand_name + ' ' + product.model_name;
                const categoryName = product.category_name
                        .normalize('NFD')                         
                        .replace(/[\u0300-\u036f]/g, '')            
                        .toLowerCase()                              
                        .replace(/[^a-z0-9]/g, ''); 
              
                const productNombre = nombreMarcaModelo.trim().toLowerCase().replaceAll(' ', '-');

                productCard.innerHTML = `
                    <div class="card">
                        <img src="${product.imagenes[0]}" alt="${product.model_name}" style="object-fit: contain; width: 100%; height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">${nombreMarcaModelo}</h5>
                            <p class="card-text">${descripcionTruncada}</p>
                            <p class="card-text">Precio: $${(product.precio_usd * dolarBlue).toFixed(2)}</p>
                            <p class="card-text">Stock: ${product.stock}</p>
                            <a href="${categoryName}/${productNombre}/${product.id_producto}" class="btn btn-primary">Seleccionar</a>
                        </div>
                    </div>
                `;
                productContainer.appendChild(productCard);
            }
        });

        // Mostrar combos
        combos.forEach(combo => {
            if (combo.habilitado) {
                const comboCard = document.createElement('div');
                comboCard.className = 'col-md-4 mb-4';
                const descripcionTruncada = combo.productos.map(p => p.brand_name + ' ' + p.model_name).join(' ').substring(0, 100) + '...';

                // Calcular el precio del combo
                const precioTotalProductos = combo.productos.reduce((total, producto) => total + (producto.precio_usd * producto.cantidad), 0);
                const precioConDescuento = precioTotalProductos * (1 - combo.descuento / 100);

                comboCard.innerHTML = `
                    <div class="card">
                        <img src="${combo.imagenes[0]}" alt="${combo.nombre}" style="object-fit: contain; width: 100%; height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">${combo.nombre}</h5>
                            <p class="card-text">${descripcionTruncada}</p>
                            <p class="card-text">Precio: $${(precioConDescuento * dolarBlue).toFixed(2)}</p>
                            <p class="card-text">Descuento total por combo: ${combo.descuento}%</p>
                            <a href="combos/${combo.nombre.toLowerCase().replaceAll(' ', '-')}/${combo.id_combo}" class="btn btn-primary">Seleccionar</a>
                        </div>
                    </div>
                `;
                productContainer.appendChild(comboCard);
            }
        });
    };

    function filterProducts(products, searchTerm) {
        return products.filter(product => 
            product.habilitado &&
            (product.model_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            product.brand_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            `${product.brand_name} ${product.model_name}`.toLowerCase().includes(searchTerm.toLowerCase()))
        );
    };

    function filterCombos(combos, searchTerm) {
        return combos.filter(combo => 
            combo.habilitado &&
            combo.nombre.toLowerCase().includes(searchTerm.toLowerCase())
        );
    };

    function filterProductsById(products, categoryId) {
        return products.filter(product => 
            product.habilitado && product.id_categoria === parseInt(categoryId)
        );
    }

});