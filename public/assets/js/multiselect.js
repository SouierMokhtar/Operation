/**
 * Multi-select dropdown component for Algérie Poste MVC
 */

function createMultiSelect(containerId, items, allText, btnTextId, dropdownId, onChange) {
    var container = document.getElementById(containerId);
    var btnText   = document.getElementById(btnTextId);
    var dropdown  = document.getElementById(dropdownId);
    var btn       = container.querySelector('.multi-select-btn');

    dropdown.innerHTML = '';

    // "Select all" option
    var allLabel    = document.createElement('label');
    allLabel.className = 'select-all';
    var allCheckbox = document.createElement('input');
    allCheckbox.type    = 'checkbox';
    allCheckbox.checked = true;
    allLabel.appendChild(allCheckbox);
    allLabel.appendChild(document.createTextNode(' Tout sélectionner'));
    allLabel.addEventListener('click', function(e) { e.stopPropagation(); });
    allCheckbox.addEventListener('change', function() {
        var checked    = allCheckbox.checked;
        var checkboxes = dropdown.querySelectorAll('.item-checkbox');
        checkboxes.forEach(function(cb) { cb.checked = checked; });
        updateButtonText();
        onChange();
    });
    dropdown.appendChild(allLabel);

    var divider       = document.createElement('div');
    divider.className = 'divider';
    dropdown.appendChild(divider);

    // Individual items
    items.forEach(function(item) {
        var label    = document.createElement('label');
        var checkbox = document.createElement('input');
        checkbox.type      = 'checkbox';
        checkbox.className = 'item-checkbox';
        checkbox.value     = item;
        checkbox.checked   = true;
        label.appendChild(checkbox);
        label.appendChild(document.createTextNode(' ' + item));
        label.addEventListener('click', function(e) { e.stopPropagation(); });
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateButtonText();
            onChange();
        });
        dropdown.appendChild(label);
    });

    function updateSelectAllState() {
        var checkboxes  = dropdown.querySelectorAll('.item-checkbox');
        var allChecked  = Array.from(checkboxes).every(function(cb) { return cb.checked; });
        var noneChecked = Array.from(checkboxes).every(function(cb) { return !cb.checked; });
        if (allChecked) {
            allCheckbox.checked       = true;
            allCheckbox.indeterminate = false;
        } else if (noneChecked) {
            allCheckbox.checked       = false;
            allCheckbox.indeterminate = false;
        } else {
            allCheckbox.checked       = false;
            allCheckbox.indeterminate = true;
        }
    }

    function updateButtonText() {
        var selected = getSelectedItems(containerId);
        if (selected.length === 0) {
            btnText.textContent = 'Aucun élément';
        } else if (selected.length === items.length) {
            btnText.textContent = allText;
        } else if (selected.length === 1) {
            btnText.textContent = selected[0];
        } else {
            btnText.textContent = selected.length + ' sélectionnés';
        }
    }

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('active');
    });

    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            dropdown.classList.remove('active');
        }
    });

    updateButtonText();
}

function getSelectedItems(containerId) {
    var dropdown   = document.getElementById(containerId).querySelector('.multi-select-dropdown');
    var checkboxes = dropdown.querySelectorAll('.item-checkbox');
    return Array.from(checkboxes).filter(function(cb) { return cb.checked; }).map(function(cb) { return cb.value; });
}
