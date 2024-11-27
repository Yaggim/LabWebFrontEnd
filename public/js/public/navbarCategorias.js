document.addEventListener('DOMContentLoaded', () => {
    const categoryList = document.querySelector(`#${window.innerWidth <= 991 ? 'cat-burger' : 'cat-navbar'}`);
    const basePath = window.location.pathname.split('/')[1];

    let categorias = [];

    async function fetchCategories() {
        const response = await fetch(`/${basePath}/includes/admin/categoria.php`);
        return response.json();
    }
    
    async function loadProductsAndCategories() {
        try {
            categorias = await fetchCategories();
            displayCategories(categorias);
        } catch (error) {
            console.error('Error cargando productos y categorías:', error);
        }
    }

    categoryList.addEventListener('click', (event) => {
        if (event.target.tagName === 'A') {
            const categoryId = event.target.dataset.categoryId;

            localStorage.setItem('selectedCategoryId', categoryId);
            window.location.href = `/${basePath}/productos`;
        }
    });

    function displayCategories(categorias) {
        categoryList.innerHTML = '';
        categorias.forEach(categoria => {
            const categoryItem = document.createElement('li');
            const categoryLink = document.createElement('a');

            categoryLink.classList.add('dropdown-item');
            categoryLink.href = '#';
            categoryLink.setAttribute('data-category-id', categoria.id_categoria);
            categoryLink.innerText = categoria.nombre;

            categoryItem.appendChild(categoryLink);
            categoryList.appendChild(categoryItem);
        });
    };

    loadProductsAndCategories();

});