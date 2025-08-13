/**
 * Cambia el tema de la página entre claro y oscuro.
 * @param {HTMLElement} selector El elemento <select> que activa el cambio.
 */
function cambiarTema(selector) {
  const tema = selector.value;

  if (tema === "tema-oscuro") {
    document.body.classList.add("modo-oscuro");
    localStorage.setItem("tema", "oscuro"); // Guarda la preferencia
  } else {
    document.body.classList.remove("modo-oscuro");
    localStorage.setItem("tema", "claro"); // Guarda la preferencia
  }
}

/**
 * Se ejecuta cuando el documento HTML ha sido cargado por completo.
 */
document.addEventListener("DOMContentLoaded", () => {
  // --- Lógica para el menú de hamburguesa (de tu código) ---
  const btnHamburguesa = document.querySelector(".hamburger-toggle");
  if (btnHamburguesa) {
    btnHamburguesa.addEventListener("click", () => {
      btnHamburguesa.classList.toggle("active");
    });
  }

  // --- Lógica para cargar el tema guardado (LA PARTE QUE FALTABA) ---
  const temaGuardado = localStorage.getItem("tema") || "claro";
  const selectorDeTema = document.getElementById("tema-seleccion");

  if (temaGuardado === "oscuro") {
    document.body.classList.add("modo-oscuro");
  }

  // Asegura que el selector muestre la opción correcta al cargar
  if (selectorDeTema) {
    selectorDeTema.value = `tema-${temaGuardado}`;
  }
});