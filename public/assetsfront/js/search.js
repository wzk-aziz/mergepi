document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    // Input event listener for live search
    searchInput.addEventListener('input', function(event) {
        const searchTerm = event.target.value.trim();

        if (searchTerm !== '') {
            // Perform live search
            liveSearch(searchTerm);
        } else {
            // Clear search results if search term is empty
            searchResults.innerHTML = '';
        }
    });

    // Implementation of liveSearch function
    function liveSearch(searchTerm) {
        // Send AJAX request to the server
        fetch('/search?q=' + searchTerm)
            .then(response => response.json())
            .then(data => {
                // Clear previous search results
                searchResults.innerHTML = '';

                // Render new search results
                data.forEach(result => {
                    const listItem = document.createElement('li');
                    listItem.textContent = result.titre; // Assuming 'titre' is the property in your search results
                    searchResults.appendChild(listItem);
                });
            })
            .catch(error => console.error('Error:', error));
    }
});

// Path: public/assetsfront/js/search.js
// Author:laaroussi ya azeu
