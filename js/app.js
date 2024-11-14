// Lógica para mostrar contacto en pantallas medianas a chicas
const linkContactoHamburguesa = document.getElementById('contactLink');
if (linkContactoHamburguesa) {
    linkContactoHamburguesa.addEventListener('click', function () {
        const offcanvasElement = document.querySelector('.offcanvas');
        const offcanvasInstancia = bootstrap.Offcanvas.getInstance(offcanvasElement);

        if (offcanvasInstancia) {
            offcanvasInstancia.hide();
        }

        setTimeout(function () {
            document.getElementById('contacto').scrollIntoView({ behavior: 'smooth' });
        }, 300);
    });
}

const btnPerfil = document.getElementById('btnPerfil');
if (btnPerfil) {
    btnPerfil.addEventListener('click', function () {
        const basePath = window.location.pathname.split('/')[1];
        window.location.href = `/${basePath}/perfil`;
    });
}

const btnAdmin = document.getElementById('btnAdmin');
if (btnAdmin) {
    btnAdmin.addEventListener('click', function () {
        const basePath = window.location.pathname.split('/')[1];
        window.location.href = `/${basePath}/admin`;
    });
}

const botonesEditarPerfil = document.getElementsByClassName('btnEditarPerfil');
Array.from(botonesEditarPerfil).forEach((boton) => {
    boton.addEventListener('click', function () {
        const basePath = window.location.pathname.split('/')[1];
        window.location.href = `/${basePath}/editar-perfil`;
    });
});

const botonesVolverHome = document.getElementsByClassName('btnVolverHome');
Array.from(botonesVolverHome).forEach((boton) => {
    boton.addEventListener('click', function () {
        window.location.href = `home`;
    });
});
