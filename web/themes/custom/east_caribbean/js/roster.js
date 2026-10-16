document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.roster-filters .filter-btn');
    const rosterRows = document.querySelectorAll('.view-id-roster tbody tr');

    if (!filterBtns.length || !rosterRows.length) return;

    filterBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            filterBtns.forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-primary');
            });
            e.target.classList.remove('btn-outline-primary');
            e.target.classList.add('btn-primary', 'active');

            const filterValue = e.target.getAttribute('data-filter');

            rosterRows.forEach(row => {
                const firCell = row.querySelector('.views-field-field-roster-fir');

                if (!firCell) return;

                const firText = firCell.textContent.trim();

                if (filterValue === 'all') {
                    row.style.display = '';
                } else {
                    if (firText === filterValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    });
});