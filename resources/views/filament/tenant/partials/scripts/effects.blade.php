{{-- resources/views/filament/tenant/partials/scripts/effects.blade.php --}}

document.addEventListener("DOMContentLoaded", function() {

    // Animación suave para transiciones de página
    const pageTransition = () => {
        const mainContent = document.querySelector(".fi-main");
        if (mainContent) {
            mainContent.style.opacity = "0";
            mainContent.style.transform = "translateY(10px)";
            
            setTimeout(() => {
                mainContent.style.transition = "all 0.3s ease";
                mainContent.style.opacity = "1";
                mainContent.style.transform = "translateY(0)";
            }, 100);
        }
    };
    
    // Observador para cambios en el DOM (navegación SPA)
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                // Re-aplicar efectos cuando se agreguen nuevos elementos
                initializeEffects();
            }
        });
    });
    
    // Observar cambios en el body
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Función para inicializar efectos
    function initializeEffects() {
        // Re-aplicar contador animado a nuevos badges
        const newBadges = document.querySelectorAll(".fi-badge-animated:not([data-animated])");
        newBadges.forEach(badge => {
            badge.setAttribute("data-animated", "true");
            const finalValue = parseInt(badge.textContent);
            if (!isNaN(finalValue) && finalValue > 0) {
                let currentValue = 0;
                const increment = finalValue / 20;
                
                const counter = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        badge.textContent = finalValue;
                        clearInterval(counter);
                    } else {
                        badge.textContent = Math.floor(currentValue);
                    }
                }, 30);
            }
        });
    }
    
    // Efecto de loading suave
    const addLoadingEffect = () => {
        const buttons = document.querySelectorAll(".fi-btn");
        buttons.forEach(button => {
            button.addEventListener("click", function() {
                if (!this.classList.contains("loading")) {
                    const originalText = this.innerHTML;
                    this.classList.add("loading");
                    this.style.position = "relative";
                    
                    // Agregar spinner
                    const spinner = document.createElement("span");
                    spinner.style.cssText = `
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 16px;
                        height: 16px;
                        border: 2px solid rgba(255,255,255,0.3);
                        border-top-color: white;
                        border-radius: 50%;
                        animation: spin 0.6s linear infinite;
                    `;
                    
                    // Ocultar texto original
                    this.style.color = "transparent";
                    this.appendChild(spinner);
                    
                    // Restaurar después de 2 segundos (ajustar según necesidad)
                    setTimeout(() => {
                        this.classList.remove("loading");
                        this.style.color = "";
                        this.innerHTML = originalText;
                    }, 2000);
                }
            });
        });
    };
    
    // Inicializar efectos de loading
    addLoadingEffect();
    
    // Agregar animación de spin para loading
    if (!document.getElementById("spin-animation")) {
        const style = document.createElement("style");
        style.id = "spin-animation";
        style.textContent = `
            @keyframes spin {
                to { transform: translate(-50%, -50%) rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
});
