
function mostrarModal(titulo, mensaje, botonTexto = "Aceptar") {
    // Elimina el modal previo, si existe
    const modalExistente = document.getElementById("modal");
    if (modalExistente) {
        modalExistente.remove();
    }

    // Crear elementos del modal
    const modal = document.createElement("div");
    modal.classList.add("modal", "fade");
    modal.id = "modal";
    modal.tabIndex = -1;
    modal.setAttribute("role", "dialog");
    modal.setAttribute("aria-hidden", "true");

    const modalDialog = document.createElement("div");
    modalDialog.classList.add("modal-dialog");
    modalDialog.setAttribute("role", "document");

    const modalContent = document.createElement("div");
    modalContent.classList.add("modal-content");

    // Modal header
    const modalHeader = document.createElement("div");
    modalHeader.classList.add("modal-header");

    const modalTitle = document.createElement("h4");
    modalTitle.classList.add("modal-title");
    modalTitle.textContent = titulo;

    const closeButton = document.createElement("button");
    closeButton.classList.add("btn-close");
    closeButton.id = "btnCerrar";
    closeButton.setAttribute("type", "button");
    closeButton.setAttribute("data-bs-dismiss", "modal");
    closeButton.setAttribute("aria-label", "Close");

    // Agregar el título y el botón de cierre al header
    modalHeader.appendChild(modalTitle);
    modalHeader.appendChild(closeButton);

    // Modal body
    const modalBody = document.createElement("div");
    modalBody.classList.add("modal-body");

    if (Array.isArray(mensaje)) {
        Array.from(mensaje).forEach((message) => {
            const modalMessage = document.createElement("p");
            modalMessage.textContent = message;
            modalBody.appendChild(modalMessage);
        });
    } else {
        const modalMessage = document.createElement("p");
        modalMessage.textContent = mensaje;
        modalBody.appendChild(modalMessage);
    }

    // Modal footer
    const modalFooter = document.createElement("div");
    modalFooter.classList.add("modal-footer");

    const actionButton = document.createElement("button");
    actionButton.classList.add("btn", "btn-success");
    actionButton.setAttribute("type", "button");
    actionButton.setAttribute("data-bs-dismiss", "modal");
    actionButton.id = "btnModal";
    actionButton.textContent = botonTexto;

    modalFooter.appendChild(actionButton);

    // Ensamblar el modal
    modalContent.appendChild(modalHeader);
    modalContent.appendChild(modalBody);
    modalContent.appendChild(modalFooter);
    modalDialog.appendChild(modalContent);
    modal.appendChild(modalDialog);
    document.body.appendChild(modal);

    // Inicializar y mostrar el modal usando Bootstrap
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}
