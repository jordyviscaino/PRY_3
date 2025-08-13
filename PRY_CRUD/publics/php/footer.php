<footer class="text-center p-3 mt-auto">
    <p>&copy; 2024 Mi Aplicación CRUD. Todos los derechos reservados.</p>
</footer>

<!-- Botón y Contenedor del Chatbot -->
<button id="bot-toggle-button" class="bot-toggle-button">
    <i class="bi bi-robot"></i>
</button>

<div id="chatbot-container" class="chatbot-container">
    <div class="chatbot-header">
        <h5>Asistente Virtual 🤖</h5>
        <button id="close-bot-button" class="close-bot-button">&times;</button>
    </div>

    <div class="chatbot-body">
        <p>¡Hola! ¿Necesitas ayuda? Déjanos tu consulta y te contactaremos lo antes posible.</p>
        
        <form id="contact-form">
            <div class="form-group">
                <label for="from_name">Nombre:</label>
                <input type="text" id="from_name" name="from_name" required>
            </div>
            <div class="form-group">
                <label for="user_email">Correo Electrónico:</label>
                <input type="email" id="user_email" name="user_email" required>
            </div>
            <div class="form-group">
                <label for="message">Mensaje:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
            </div>
            <button type="submit" class="submit-button">Enviar Mensaje</button>
        </form>
    </div>
</div>

<!-- Scripts Principales -->
<script src="/PRY_CRUD/publics/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>

<script>
    // --- INICIALIZACIÓN DE EMAILJS ---
    emailjs.init({
      publicKey: "5Y8XYH1FI0K-6_K_r", // 👈 Reemplaza con tu Public Key real
    });

    // --- Lógica del Chatbot ---
    const toggleButton = document.getElementById('bot-toggle-button');
    const chatContainer = document.getElementById('chatbot-container');
    const closeButton = document.getElementById('close-bot-button');
    const contactForm = document.getElementById('contact-form');

    const showChat = () => chatContainer.classList.add('visible');
    const hideChat = () => chatContainer.classList.remove('visible');

    toggleButton.addEventListener('click', () => chatContainer.classList.contains('visible') ? hideChat() : showChat());
    closeButton.addEventListener('click', hideChat);

    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const submitButton = this.querySelector('.submit-button');
        submitButton.textContent = 'Enviando...';
        submitButton.disabled = true;

        emailjs.sendForm("service_thjuhi8", "template_ljc6iwh", this)
            .then(() => {
                alert("¡Tu mensaje fue enviado! Gracias por contactarnos.");
                this.reset();
                hideChat();
            }, (error) => {
                console.error("Error al enviar el correo:", error);
                alert("Ocurrió un error al enviar el mensaje. Por favor, inténtalo de nuevo.");
            })
            .finally(() => {
                submitButton.textContent = 'Enviar Mensaje';
                submitButton.disabled = false;
            });
    });
</script>