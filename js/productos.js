document.addEventListener('DOMContentLoaded', () => {
    const productContainer = document.getElementById('product-container');
    const searchInput = document.getElementById('search');
    const categoryList = document.getElementById('category-list');
    const clearFiltersButton = document.getElementById('clear-filters');

    const productos = [
    {
        "id": 1,
        "brand": {
            "id": 1,
            "name": "Kingston"
        },
        "model": {
            "id": 1,
            "name": "Fury Beast"
        },
        "category": {
            "id": 1,
            "name": "Memorias"
        },
        "priceARS": 55200,
        "priceUSD": 55.2,
        "enabled": true,
        "stock": 10,
        "image": [
            "../images/productos/prod1_foto1.jpg",
            "../images/productos/prod1_foto2.jpg"],
        "description": "Si tu computadora funciona con lentitud, si un programa no responde o no se carga, lo más probable es que se trate de un problema de memoria. Estos son posibles indicios de un rendimiento defectuoso en el día a día de tus tareas. Por ello, contar con una memoria Kingston -sinónimo de trayectoria y excelencia- mejorará la productividad de tu equipo."
    },
    {
        "id": 2,
        "brand": {
            "id": 2,
            "name": "Corsair"
        },
        "model": {
            "id": 2,
            "name": "Vengeance LPX"
        },
        "category": {
            "id": 1,
            "name": "Memorias"
        },
        "priceARS": 88100,
        "priceUSD": 88.1,
        "enabled": true,
        "stock": 2,
        "image": [
            "../images/productos/prod2_foto1.jpg",
            "../images/productos/prod2_foto2.jpg"],
        "description": "Con diseños únicos y modernos, Corsair es una de las marcas más elegidas por los usuarios al momento de comprar una memoria ram. Cargar programas más rápido, aumentar la capacidad de responder y ejecutar aplicaciones de uso intensivo son algunas de las características y ventajas que tendrás al momento de adquirir esta memoria. No esperes más para mejorar el rendimiento de tu computadora."
    },
    {
        "id": 3,
        "brand": {
            "id": 3,
            "name": "AMD"
        },
        "model": {
            "id": 3,
            "name": "Ryzen 3"
        },
        "category": {
            "id": 2,
            "name": "Micros"
        },
        "priceARS": 350500,
        "priceUSD": 350.5,
        "enabled": true,
        "stock": 8,
        "image": [
            "../images/productos/prod3_foto1.jpg",
            "../images/productos/prod3_foto2.jpg"],
        "description": "Mejora tu experiencia de juego con el Procesador gamer AMD Ryzen 3 3200G, diseñado para brindarte un rendimiento óptimo en tus partidas. Con sus 4 núcleos y una frecuencia máxima de 4 GHz, disfrutarás de una velocidad y fluidez excepcionales en tus juegos favoritos. Además, su arquitectura x86-64 te garantiza una compatibilidad amplia con diversos sistemas y aplicaciones."
    },
    {
        "id": 4,
        "brand": {
            "id": 4,
            "name": "WD"
        },
        "model": {
            "id": 4,
            "name": "Black SN850X"
        },
        "category": {
            "id": 3,
            "name": "Discos"
        },
        "priceARS": 95700,
        "priceUSD": 95.7,
        "enabled": true,
        "stock": 15,
        "image": [
            "../images/productos/prod4_foto1.jpg",
            "../images/productos/prod4_foto2.jpg"],
        "description": "Western Digital es una marca de renombre mundial en almacenamiento de datos con la cual podés crear, experimentar y guardar contenidos a través de una amplia gama de dispositivos. La alta seguridad y rendimiento que brindan sus unidades la convierten en una de las empresas más elegidas del mercado. El WD Black SN850X Wds200t2xhe está adaptado para que puedas acceder de forma rápida a tus documentos digitales gracias a su tecnología en estado sólido."
    },
    {
        "id": 5,
        "brand": {
            "id": 5,
            "name": "Samsung"
        },
        "model": {
            "id": 5,
            "name": "990 PRO"
        },
        "category": {
            "id": 3,
            "name": "Discos"
        },
        "priceARS": 150000,
        "priceUSD": 150,
        "enabled": false,
        "stock": 20,
        "image": [
            "../images/productos/prod5_foto1.jpg",
            "../images/productos/prod5_foto2.jpg"],
        "description": "Considerada una de las marcas más innovadoras en el rubro de electrónica, Samsung ofrece productos de calidad y se destaca por su especialización en unidades de almacenamiento. El 990 Pro MZ-V9P1T0B/AM está adaptado para que puedas acceder de forma rápida a tus documentos digitales gracias a su tecnología en estado sólido."
    },
    {
        "id": 6,
        "brand": {
            "id": 6,
            "name": "Acer"
        },
        "model": {
            "id": 6,
            "name": "Gaming Kg241q"
        },
        "category": {
            "id": 4,
            "name": "Monitores"
        },
        "priceARS": 220000,
        "priceUSD": 220,
        "enabled": true,
        "stock": 12,
        "image": [
            "../images/productos/prod6_foto1.jpg",
            "../images/productos/prod6_foto2.jpg"],
        "description": "Sumérgete en una experiencia de juego inigualable con el Monitor Gamer Acer Nitro KG1 KG241Q Sbmiipx de 24 pulgadas. Con una resolución Full HD de 1920x1080 píxeles y una relación de aspecto de 16:9, disfrutarás de imágenes nítidas y colores vibrantes en cada partida. Gracias a su frecuencia de actualización de 165 Hz y tiempo de respuesta GTG de 1 ms, podrás apreciar cada detalle en tiempo real, sin desenfoques ni retrasos."
    },
    {
        "id": 7,
        "brand": {
            "id": 7,
            "name": "AOC"
        },
        "model": {
            "id": 7,
            "name": "G2790VX"
        },
        "category": {
            "id": 4,
            "name": "Monitores"
        },
        "priceARS": 115000,
        "priceUSD": 115,
        "enabled": true,
        "stock": 1,
        "image": [
            "../images/productos/prod7_foto1.jpg",
            "../images/productos/prod7_foto2.jpg"],
        "description": "La tecnología AMD FreeSync permite una comunicación eficiente entre el monitor y la placa de video, proporcionando imágenes fluidas y sin efecto fantasma. Tiempo de respuesta de apenas 1 ms para movimientos rápidos y sensibles, mejorando el desempeño en juegos. Tasa de refresco de 144 hz para movimientos suaves y fluidos, mejorando la experiencia visual. Diseño borderless para una visualización amplia y sin interrupciones."
    },
    {
        "id": 8,
        "brand": {
            "id": 8,
            "name": "Nisuta"
        },
        "model": {
            "id": 8,
            "name": "NSKBG5RL"
        },
        "category": {
            "id": 5,
            "name": "Perifericos"
        },
        "priceARS": 35000,
        "priceUSD": 35,
        "enabled": true,
        "stock": 30,
        "image": [
            "../images/productos/prod8_foto1.jpg",
            "../images/productos/prod8_foto2.jpg"],
        "description": "Este teclado Nisuta de alto rendimiento permite que puedas disfrutar de horas ilimitadas de juegos. Está diseñado especialmente para que puedas expresar tanto tus habilidades como tu estilo. Podrás mejorar tu experiencia de gaming, ya seas un aficionado o todo un experto y hacer que tus jugadas alcancen otro nivel."
    },
    {
        "id": 9,
        "brand": {
            "id": 9,
            "name": "Crucial "
        },
        "model": {
            "id": 9,
            "name": "CT1000BX500SSD1 "
        },
        "category": {
            "id": 3,
            "name": "Discos"
        },
        "priceARS": 95100,
        "priceUSD": 95.1,
        "enabled": false,
        "stock": 25,
        "image": [
            "../images/productos/prod9_foto1.jpg",
            "../images/productos/prod9_foto2.jpg"],
        "description": "Con la unidad en estado sólido Crucial de 1TB vas a incrementar la capacidad de respuesta de tu equipo. Gracias a esta tecnología podrás invertir en velocidad y eficiencia para el inicio, la carga y la transferencia de datos."
    },
    {
        "id": 10,
        "brand": {
            "id": 10,
            "name": "Redragon"
        },
        "model": {
            "id": 10,
            "name": "Star Pro M917GB"
        },
        "category": {
            "id": 5,
            "name": "Perifericos"
        },
        "priceARS": 87000,
        "priceUSD": 87,
        "enabled": true,
        "stock": 0,
        "image": [
            "../images/productos/prod10_foto1.jpg",
            "../images/productos/prod10_foto2.jpg"],
        "description": "Parte de una nueva generación enfocada 100% en el rendimiento, el St4ar Pro es un ratón de alto nivel desarrollado para gamers realmente exigentes. Es liviano y cómodo -pesa alrededor de 60 g- y cuenta con doble conexión inalámbrica, cable de paracord y patines de teflón para que el movimiento sea siempre suave y ligero. Está equipado, además, con uno de los sensores más avanzados de su generación y switches Huano que soportan hasta 20 millones de clics."
    }
];

    const categorias = [
        { "id": 1, "name": "Memorias" },
        { "id": 2, "name": "Micros" },
        { "id": 3, "name": "Discos" },
        { "id": 4, "name": "Monitores" },
        { "id": 5, "name": "Perifericos" }
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
            if (product.enabled) {
                const productCard = document.createElement('div');
                productCard.className = 'col-md-4 mb-4';
                const descripcionTruncada = product.description.length > 100 ? product.description.substring(0, 100) + '...' : product.description;
                productCard.innerHTML = `
                    <div class="card">
                        <img src="${product.image[0]}" alt="${product.model.name}" style="object-fit: contain; width: 100%; height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">${product.brand.name} ${product.model.name}</h5>
                            <p class="card-text">${descripcionTruncada}</p>
                            <p class="card-text">Precio: $${product.priceARS} / $${product.priceUSD} USD</p>
                            <p class="card-text">Stock: ${product.stock}</p>
                            <a href="producto.html?id=${product.id}" class="btn btn-primary">Seleccionar</a>
                        </div>
                    </div>
                `;
                productContainer.appendChild(productCard);
            }
        });
    }

    function filterProducts(products, searchTerm) {
        return products.filter(product => 
            product.enabled &&
            (product.model.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            product.brand.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            `${product.brand.name} ${product.model.name}`.toLowerCase().includes(searchTerm.toLowerCase()))
        );
    }

    function filterProductsById(products, categoryId) {
        return products.filter(product => 
            product.enabled && product.category.id === parseInt(categoryId)
        );
    }
});