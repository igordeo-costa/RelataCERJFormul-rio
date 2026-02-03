document.addEventListener('DOMContentLoaded', function () {

    /* ===== GUIAS ===== */
    const guiasWrapper = document.getElementById('relatacerj-guias-wrapper');
    const addGuiaBtn   = document.getElementById('relatacerj-add-guia');

    function initGuiasAutocomplete(input) {
        if (typeof jQuery === 'undefined' || !jQuery.ui) return;

        jQuery(input).autocomplete({
            minLength: 2,
            appendTo: input.parentElement,
            source: function (request, response) {
                jQuery.getJSON(relatacerj_ajax_object.ajax_url, {
                    action: 'relatacerj_buscar_guias',
                    term: request.term
                }, response);
            }
        });
    }

    if (guiasWrapper && addGuiaBtn) {

        guiasWrapper
            .querySelectorAll('input.relatacerj-guia')
            .forEach(initGuiasAutocomplete);

        addGuiaBtn.addEventListener('click', function () {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'guias[]';
            input.className = 'relatacerj-guia';
            input.required = true;

            guiasWrapper.appendChild(input);
            initGuiasAutocomplete(input);
        });
    }

    /* ===== PARTICIPANTES ===== */
    const partWrapper = document.getElementById('relatacerj-participantes-wrapper');
    const addPartBtn  = document.getElementById('relatacerj-add-participante');

    function initParticipantesAutocomplete(input) {
        if (typeof jQuery === 'undefined' || !jQuery.ui) return;

        jQuery(input).autocomplete({
            minLength: 2,
            appendTo: input.parentElement,
            source: function (request, response) {
                jQuery.getJSON(relatacerj_ajax_object.ajax_url, {
                    action: 'relatacerj_buscar_participantes',
                    term: request.term
                }, response);
            }
        });
    }

    if (partWrapper && addPartBtn) {

        partWrapper
            .querySelectorAll('input.relatacerj-participante')
            .forEach(initParticipantesAutocomplete);

        addPartBtn.addEventListener('click', function () {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'participantes[]';
            input.className = 'relatacerj-participante';
            input.required = true;

            partWrapper.appendChild(input);
            initParticipantesAutocomplete(input);
        });
    }

});


