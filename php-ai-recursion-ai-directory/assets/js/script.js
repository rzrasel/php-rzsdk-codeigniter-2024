function toggleCheckboxes(className) {
    const checkboxes = document.querySelectorAll('input.' + className + "[type='checkbox']");
    if (checkboxes.length === 0) return;

    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
        toggleStrike(cb);
    });
}

function toggleFolder(className) {
    const containers = document.querySelectorAll('.' + className);
    const icon = document.querySelector('[data-folder-icon="' + className + '"]');

    containers.forEach(el => el.classList.toggle('open'));

    if (icon) {
        const isOpen = containers[0].classList.contains('open');
        icon.textContent = isOpen ? '▼' : '▶';
    }

    // Toggle checkboxes under this folder
    toggleCheckboxes(className);
}

function toggleStrike(checkbox) {
    const label = checkbox.closest('label');
    if (label) {
        if (!checkbox.checked) {
            label.classList.add('strike');
        } else {
            label.classList.remove('strike');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const folderNames = document.querySelectorAll('.folder-name');
    folderNames.forEach(function(folderName) {
        folderName.addEventListener('click', function() {
            const className = folderName.getAttribute('data-folder');
            toggleFolder(className);
        });
    });

    // Apply strike class on load based on checkbox state
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
        toggleStrike(cb);
        cb.addEventListener('change', () => toggleStrike(cb));
    });
});