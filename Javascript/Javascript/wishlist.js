function toggleWishlist(element) {
    const card = element.closest('.wishlist-card');
    const uniqId = card.getAttribute('data-uniq-id');

    // Send AJAX request to remove the item from the wishlist
    fetch('toggle_wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'uniq_id=' + encodeURIComponent(uniqId)
    })
    .then(response => response.json())
    .then(data => {
        console.log("Response from server:", data); // Debugging: Log the response

        if (data.success) {
            // Remove the card from the wishlist view if the backend update is successful
            card.remove();
        } else {
            alert(data.message || 'Error removing item from wishlist.');
        }
    })
    .catch(error => console.error('Error:', error));
}
