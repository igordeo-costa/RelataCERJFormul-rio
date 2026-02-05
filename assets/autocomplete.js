document.addEventListener('DOMContentLoaded', function () {

    if (typeof jQuery === 'undefined' || !jQuery.ui) {
        console.error('jQuery ou jQuery UI não carregado');
        return;
    }

    /* ===== GUIAS ===== */
    const guiasWrapper = document.getElementById('relatacerj-guias-wrapper');
    const addGuiaBtn   = document.getElementById('relatacerj-add-guia');

    function initGuiasAutocomplete(input) {
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

            guiasWrapper.appendChild(input);
            initGuiasAutocomplete(input);
        });
    }

    /* ===== PARTICIPANTES ===== */
    const partWrapper = document.getElementById('relatacerj-participantes-wrapper');
    const addPartBtn  = document.getElementById('relatacerj-add-participante');

    function initParticipantesAutocomplete(input) {

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

        // ===== AVISO DE SIMILARIDADE =====
        input.addEventListener('blur', function () {

            const nome = input.value.trim();
            if (!nome) return;

            jQuery.getJSON(relatacerj_ajax_object.ajax_url, {
                action: 'relatacerj_verificar_similaridade',
                nome: nome
            }, function (avisos) {

                if (!Array.isArray(avisos) || avisos.length === 0) return;

                // monta mensagens legíveis
                const mensagens = avisos.map(a =>
                    `⚠️ Tem certeza de que "${a.digitado}" e "${a.oficial}" não são a mesma pessoa?`
                );

                alert(
                    mensagens.join('\n\n') +
                    '\n\nSe os nomes se referem à mesma pessoa, selecione o nome completo na lista ao digitar.'
                );
            });
        });

        // dispara o aviso também ao selecionar no autocomplete
        jQuery(input).on('autocompleteselect', function () {
            input.blur();
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

            partWrapper.appendChild(input);
            initParticipantesAutocomplete(input);
        });
    }

});

