let cantidadNotificaciones = 0;

/**
 * Muestra una notificación toast temporal.
 * @param {string} mensaje El mensaje a mostrar.
 * @param {string} tipo El tipo de toast (success, danger, info, warning).
 */
function mostrarToast(mensaje, tipo = "success") {
    const container = document.getElementById("toastContainer");
    if (!container) return;

    const toast = document.createElement("div");
    toast.className = `toast align-items-center text-bg-${tipo} border-0 show`;
    toast.setAttribute("role", "alert");
    toast.setAttribute("aria-live", "assertive");
    toast.setAttribute("aria-atomic", "true");

    toast.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">${mensaje}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
  `;

    container.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast, { delay: 5000 });
    bsToast.show();

    toast.addEventListener("hidden.bs.toast", () => toast.remove());
}

/**
 * Agrega una notificación al historial y muestra un toast.
 * @param {string} texto El texto de la notificación.
 * @param {string} tipo El tipo de notificación (success, danger, etc.).
 */
function agregarNotificacion(texto, tipo = "success") {
    mostrarToast(texto, tipo);

    const notiList = document.getElementById("notiList");
    const notiBadge = document.getElementById("notiBadge");

    if (!notiList || !notiBadge) return;

    const item = document.createElement("li");
    const fecha = new Date().toLocaleTimeString();

    item.innerHTML = `<a class="dropdown-item" href="#"><div>${texto}<br><small class="text-muted">${fecha}</small></div></a>`;
    notiList.insertBefore(item, notiList.children[1]);

    cantidadNotificaciones++;
    notiBadge.textContent = cantidadNotificaciones;
    notiBadge.style.display = "inline-block";
}

// Cuando se abre la campana, limpiar badge
document.getElementById("notiButton")?.addEventListener("click", () => {
    cantidadNotificaciones = 0;
    const notiBadge = document.getElementById("notiBadge");
    if (notiBadge) notiBadge.style.display = "none";
});