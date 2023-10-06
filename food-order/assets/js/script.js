var foodList = [
  'Pizza',
  'Burger',
  'Salad',
  'Pasta',
  'Sushi',
  'Steak',
];
var searchInput = document.getElementById('food-search');
var searchResults = document.getElementById('search-results');

searchInput.addEventListener('input', function () {
    var query = searchInput.value.toLowerCase();
    var filteredFoods = [];

    // Filter the food list based on the query
    for (var i = 0; i < foodList.length; i++) {
        var food = foodList[i].toLowerCase();
        if (food.includes(query)) {
            filteredFoods.push(foodList[i]);
        }
    }

    // Display the filtered results
    displayResults(filteredFoods);
});

function displayResults(results) {
    searchResults.innerHTML = '';

    if (results.length > 0) {
        for (var i = 0; i < results.length; i++) {
            var listItem = document.createElement('li');
            listItem.textContent = results[i];
            searchResults.appendChild(listItem);
        }
    } else {
        var noResultsItem = document.createElement('li');
        noResultsItem.textContent = 'No matching foods found.';
        searchResults.appendChild(noResultsItem);
    }
}
