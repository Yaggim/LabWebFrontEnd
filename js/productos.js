document.addEventListener('DOMContentLoaded', () => {
    const productContainer = document.getElementById('product-container');
    const searchInput = document.getElementById('search');
    const categoryList = document.getElementById('category-list');
    const clearFiltersButton = document.getElementById('clear-filters');

    let productos = [];
    let categorias = [];
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
    
    async function loadProductsAndCategories() {
        try {
            await fetchDolarBlue();
            productos = await fetchProducts();
            categorias = await fetchCategories();
            displayCategories(categorias);
            displayProducts(productos);
        } catch (error) {
            console.error('Error cargando productos y categorías:', error);
        }
    }

    if (searchInput && categoryList && clearFiltersButton) {
        loadProductsAndCategories();

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
    };

    function displayProducts(products) {
        productContainer.innerHTML = '';
        products.forEach(product => {
            if (product.habilitado) {
                const productCard = document.createElement('div');
                productCard.className = 'col-md-4 mb-4';
                const descripcionTruncada = product.descripcion.length > 100 ? product.descripcion.substring(0, 100) + '...' : product.descripcion;

                const nombreMarcaModelo = product.brand_name + ' ' + product.model_name;
                const productNombre = nombreMarcaModelo.trim().toLowerCase().replaceAll(' ', '-');

                productCard.innerHTML = `
                    <div class="card">
                        <img src="${product.imagenes[0]}" alt="${product.model_name}" style="object-fit: contain; width: 100%; height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">${nombreMarcaModelo}</h5>
                            <p class="card-text">${descripcionTruncada}</p>
                            <p class="card-text">Precio: $${(product.precio_usd * dolarBlue).toFixed(2)} / $${product.precio_usd} USD</p>
                            <p class="card-text">Stock: ${product.stock}</p>
                            <a href="${product.category_name}/${productNombre}/${product.id_producto}" class="btn btn-primary">Seleccionar</a>
                        </div>
                    </div>
                `;
                productContainer.appendChild(productCard);
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

    function filterProductsById(products, categoryId) {
        return products.filter(product => 
            product.habilitado && product.category_id === parseInt(categoryId)
        );
    }

});