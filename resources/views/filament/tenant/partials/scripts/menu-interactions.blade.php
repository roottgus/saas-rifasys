{{-- resources/views/filament/tenant/partials/scripts/menu-interactions.blade.php --}}

document.addEventListener("DOMContentLoaded", function() {
    // Efecto de ondas al hacer clic en items del menú
    const menuItems = document.querySelectorAll(".fi-sidebar-item a");
    
    menuItems.forEach(item => {
        item.addEventListener("click", function(e) {
            // Crear elemento de onda
            const ripple = document.createElement("span");
            ripple.classList.add("ripple-effect");
            
            // Posición del click
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + "px";
            ripple.style.left = x + "px";
            ripple.style.top = y + "px";
            
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
        
        // Efecto de transición mejorada
        item.addEventListener("mouseenter", function() {
            this.style.transition = "all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55)";
        });
    });
    
    // Animación de entrada escalonada para items del menú
    const sidebarItems = document.querySelectorAll(".fi-sidebar-item");
    sidebarItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.05}s`;
    });
    
    // Efecto parallax en el header del sidebar al hacer scroll
    const sidebar = document.querySelector(".fi-sidebar");
    const sidebarHeader = document.querySelector(".fi-sidebar-header");
    
    if (sidebar && sidebarHeader) {
        sidebar.addEventListener("scroll", function() {
            const scrolled = sidebar.scrollTop;
            sidebarHeader.style.transform = `translateY(${scrolled * 0.3}px)`;
        });
    }
    
    // Efecto de hover con seguimiento del mouse
    menuItems.forEach(item => {
        item.addEventListener("mousemove", function(e) {
            const rect = this.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            
            this.style.background = `radial-gradient(circle at ${x}% ${y}%, rgba(102, 126, 234, 0.1), transparent 70%)`;
        });
        
        item.addEventListener("mouseleave", function() {
            this.style.background = "";
        });
    });
    
    // Contador animado para badges
    const badges = document.querySelectorAll(".fi-sidebar-item .fi-badge");
    badges.forEach(badge => {
        const finalValue = parseInt(badge.textContent);
        if (!isNaN(finalValue)) {
            let currentValue = 0;
            const increment = finalValue / 30;
            
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
});