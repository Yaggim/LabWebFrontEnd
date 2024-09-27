
// Lógica para mostrar contacto en pantallas medianas a chicas
document.getElementById('contactLink').addEventListener('click', function() {
    const offcanvasElement = document.querySelector('.offcanvas');
    const offcanvasInstancia = bootstrap.Offcanvas.getInstance(offcanvasElement);

    if (offcanvasInstancia) {
      offcanvasInstancia.hide();
    }

    setTimeout(function() {
        document.getElementById('contacto').scrollIntoView({ behavior: 'smooth' });
    }, 300);
});