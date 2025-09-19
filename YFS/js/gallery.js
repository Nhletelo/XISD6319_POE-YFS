    document.addEventListener('DOMContentLoaded', function() {
        // View Toggle
        const gridViewBtn = document.getElementById('grid-view');
        const listViewBtn = document.getElementById('list-view');
        const gallery = document.getElementById('image-gallery');
        
        gridViewBtn.addEventListener('click', function() {
            gallery.classList.remove('list-view');
            gallery.classList.add('grid-view');
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
        });
        
        listViewBtn.addEventListener('click', function() {
            gallery.classList.remove('grid-view');
            gallery.classList.add('list-view');
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
        });
        
        // Filtering
        const filterButtons = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const searchInput = document.getElementById('search-input');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const filterValue = this.getAttribute('data-filter');
                
                // Filter items
                galleryItems.forEach(item => {
                    if (filterValue === 'all') {
                        item.style.display = 'block';
                    } else {
                        const itemCategory = item.getAttribute('data-category');
                        if (itemCategory === filterValue) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });
            });
        });
        
        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            
            galleryItems.forEach(item => {
                const title = item.getAttribute('data-title').toLowerCase();
                if (title.includes(searchValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Lightbox configuration
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'showImageNumberLabel': true,
            'alwaysShowNavOnTouchDevices': true
        });

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

        // Newsletter form submission
        document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input').value;
            alert(`Thank you for subscribing with: ${email}\nYou will receive our updates shortly.`);
            this.reset();
        });
    });
