
const tabs = document.querySelectorAll('.tabs button');
const tabContents = document.querySelectorAll('.tab-content');

tabs.forEach(tab => {
    tab.addEventListener('click', function() {
        tabs.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        tabContents.forEach(content => content.classList.remove('active'));
        document.getElementById(this.getAttribute('data-tab')).classList.add('active');
    });
});
