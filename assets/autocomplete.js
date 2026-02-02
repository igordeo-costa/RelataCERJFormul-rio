document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('relatacerj-guias-wrapper');
    const addBtn = document.getElementById('relatacerj-add-guia');

    if (!wrapper || !addBtn) return;

    function initAutocomplete(input) {
        if (typeof jQuery === 'undefined' || !jQuery.ui) return;

       jQuery(input).autocomplete({
    minLength: 2,
    appendTo: input.parentElement,
    source: function(request, response) {
        jQuery.getJSON(relatacerj_ajax_object.ajax_url, {
            action: 'relatacerj_buscar_guias',
            term: request.term
        }, response);
    }
});

    }

    // Inicializa autocomplete nos inputs já existentes
    wrapper.querySelectorAll('input.relatacerj-guia').forEach(initAutocomplete);

    // Adiciona novo input com autocomplete
    addBtn.addEventListener('click', function () {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'guias[]';
        input.className = 'relatacerj-guia';
        input.required = true;

        wrapper.appendChild(input);
        initAutocomplete(input);
    });
});

