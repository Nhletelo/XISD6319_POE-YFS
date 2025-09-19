
    // Mobile menu functionality
    document.querySelector('.menu-toggle').addEventListener('click', function() {
        document.getElementById('main-nav').classList.toggle('active');
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const nav = document.getElementById('main-nav');
        const menuToggle = document.querySelector('.menu-toggle');
        
        if (!nav.contains(event.target) && event.target !== menuToggle && !menuToggle.contains(event.target)) {
            nav.classList.remove('active');
        }
    });

