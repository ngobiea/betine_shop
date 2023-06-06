
document.getElementById('search-button').addEventListener('click', function() {
    var query = document.getElementById('query').value;

    $.ajax({
        type: "GET",
        url: '/search',
        data: {query: query},
        success: function(data) {
            // Handle the successful search results
            // Example: update the search results section with the received data
            document.getElementById('search-results').innerHTML = data;
        }
    });
});

