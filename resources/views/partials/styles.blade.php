{{-- resources/views/partials/styles.blade.php --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
    
    * {
        font-family: 'Poppins', sans-serif;
    }
    
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #4c51bf 100%);
    }
    
    .hero-gradient {
        background: linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%);
    }
    
    .text-gradient {
        background: linear-gradient(135deg, #00306D 0%, #00306D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .floating {
        animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .scroll-logos {
        animation: scroll 20s linear infinite;
    }
    
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    .pulse-button {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    
    .nav-sticky {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }
</style>