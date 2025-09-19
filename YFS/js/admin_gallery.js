        // Confirm delete function
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.href = `?delete=${id}`;
            modal.style.display = 'flex';
        }
        
        // Close modal function
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeModal();
            }
        }
        
        // Form validation
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('image');
            const file = fileInput.files[0];
            
            if (file) {
                // Check file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    e.preventDefault();
                    alert('Please select a valid image file (JPEG, PNG, GIF, or WEBP).');
                    return false;
                }
                
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    e.preventDefault();
                    alert('Image size must be less than 5MB.');
                    return false;
                }
            }
        });
   