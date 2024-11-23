function toggleHeart(element) {
    element.classList.toggle('active');
    let icon = element.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
    }

    // Get package ID from closest package card instead of link
    let packageCard = element.closest('.package-card');
    let uniq_id = packageCard.querySelector('.package-card-link').href.split('uniq_id=')[1];

    // Get user email from the session in PHP
    let userEmail = "<?php echo $_SESSION['user_email'] ?? ''; ?>";

    // Send an AJAX request to save the favorite
    fetch('save_favorite.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ uniq_id: uniq_id, email: userEmail, favorite: icon.classList.contains('fas') })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Favorite status updated successfully.');
        } else {
            console.error('Error updating favorite status.');
        }
    })
    .catch(error => console.error('Error:', error));
}
