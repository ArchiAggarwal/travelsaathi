function toggleCompanyFields() {
    var role = document.getElementById('role').value;
    var companyFields = document.getElementById('company-fields');
    var container = document.getElementById('signup');
    
    if (role === 'tour_company') {
        companyFields.style.display = 'block';
        container.style.height = '800px'; // Elongate form for Tour Company
    } else {
        companyFields.style.display = 'none';
        container.style.height = '500px'; // Default height for other roles
    }
}