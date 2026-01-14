/**
 * Lyrium Voice Search Script
 * Utiliza la Web Speech API para habilitar la búsqueda por voz.
 */

document.addEventListener("DOMContentLoaded", () => {
    const voiceBtn = document.getElementById("voiceBtn");
    const searchInput = document.getElementById("searchInput");
    const searchForm = document.getElementById("searchForm");

    if (!voiceBtn || !searchInput) return;

    // Verificar soporte de la API
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        voiceBtn.title = "Tu navegador no soporta búsqueda por voz";
        voiceBtn.classList.add("opacity-50", "cursor-not-allowed");
        return;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = "es-PE"; // Configurado para español
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    let isListening = false;

    // Función para actualizar el estilo del botón cuando escucha
    const setListeningState = (listening) => {
        isListening = listening;
        if (listening) {
            voiceBtn.classList.remove("bg-sky-500", "hover:bg-sky-600");
            voiceBtn.classList.add("bg-red-500", "animate-pulse");
            voiceBtn.innerHTML = '<i class="ph-microphone-slash text-xl"></i>';
            searchInput.placeholder = "Escuchando...";
        } else {
            voiceBtn.classList.add("bg-sky-500", "hover:bg-sky-600");
            voiceBtn.classList.remove("bg-red-500", "animate-pulse");
            voiceBtn.innerHTML = '<i class="ph-microphone text-xl"></i>';
            searchInput.placeholder = "¿Qué estás buscando?";
        }
    };

    voiceBtn.addEventListener("click", () => {
        if (isListening) {
            recognition.stop();
            return;
        }

        try {
            recognition.start();
        } catch (e) {
            console.error("Error al iniciar el reconocimiento de voz:", e);
        }
    });

    recognition.onstart = () => {
        setListeningState(true);
    };

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        searchInput.value = transcript;

        // Disparar evento de input para que el Live Search reaccione
        searchInput.dispatchEvent(new Event('input', { bubbles: true }));
    };

    recognition.onerror = (event) => {
        console.error("Error en reconocimiento de voz:", event.error);
        setListeningState(false);
    };

    recognition.onend = () => {
        setListeningState(false);
    };
});
