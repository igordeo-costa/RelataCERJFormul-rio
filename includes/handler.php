<?php
defined('ABSPATH') || exit;

// ===== LISTAS =====
require_once plugin_dir_path(__FILE__) . 'guias.php';
require_once plugin_dir_path(__FILE__) . 'participantes.php';

// ===== AUTOCOMPLETE GUIAS =====
add_action('wp_ajax_relatacerj_buscar_guias', 'relatacerj_buscar_guias');
add_action('wp_ajax_nopriv_relatacerj_buscar_guias', 'relatacerj_buscar_guias');

function relatacerj_buscar_guias() {
    $term  = sanitize_text_field($_GET['term'] ?? '');
    $guias = relatacerj_lista_guias();

    $resultados = array_filter($guias, fn($g) => stripos($g, $term) !== false);
    wp_send_json(array_values($resultados));
}

// ===== AUTOCOMPLETE PARTICIPANTES =====
add_action('wp_ajax_relatacerj_buscar_participantes', 'relatacerj_buscar_participantes');
add_action('wp_ajax_nopriv_relatacerj_buscar_participantes', 'relatacerj_buscar_participantes');

function relatacerj_buscar_participantes() {
    $term  = sanitize_text_field($_GET['term'] ?? '');
    $lista = relatacerj_lista_participantes();

    $resultados = array_filter($lista, fn($p) => stripos($p, $term) !== false);
    wp_send_json(array_values($resultados));
}

// ===== FUNÇÃO ÚNICA DE SIMILARIDADE =====
function relatacerj_verificar_similaridade_participantes(array $digitados) {

    $oficiais = relatacerj_lista_participantes();
    $avisos   = [];

    foreach ($digitados as $d) {
        foreach ($oficiais as $o) {

            $a = mb_strtolower($d);
            $b = mb_strtolower($o);

            similar_text($a, $b, $pct);

            if ($pct >= 60 && $d !== $o) {
                $avisos[] = [
                    'digitado' => $d,
                    'oficial'  => $o,
                    'score'    => round($pct)
                ];
            }
        }
    }

    return $avisos;
}

// ===== AJAX: VERIFICAÇÃO DE SIMILARIDADE (ÚNICO) =====
add_action('wp_ajax_relatacerj_verificar_similaridade', 'relatacerj_ajax_verificar_similaridade');
add_action('wp_ajax_nopriv_relatacerj_verificar_similaridade', 'relatacerj_ajax_verificar_similaridade');

function relatacerj_ajax_verificar_similaridade() {

    $nome = sanitize_text_field($_GET['nome'] ?? '');
    if ($nome === '') {
        wp_send_json([]);
    }

    $avisos = relatacerj_verificar_similaridade_participantes([$nome]);
    wp_send_json($avisos);
}

// ===== NORMALIZAÇÕES =====
function relatacerj_normalizar_data($data) {
    $dt = DateTime::createFromFormat('Y-m-d', $data);
    return $dt ? $dt->format('d/m/Y') : '';
}

function relatacerj_normalizar_hora($hora) {
    $dt = DateTime::createFromFormat('H:i', $hora);
    return $dt ? $dt->format('H:i') : '';
}

// ===== PROCESSA FORMULÁRIO =====
add_action('init', 'relatacerj_processar_formulario');

function relatacerj_processar_formulario() {

    if (
        !isset($_POST['relatacerj_submit'], $_POST['relatacerj_nonce']) ||
        !wp_verify_nonce($_POST['relatacerj_nonce'], 'relatacerj_salvar')
    ) return;

    $erros = [];

    // ---- GUIAS ----
    $guias = array_filter(array_map(
        'sanitize_text_field',
        $_POST['guias'] ?? []
    ));

    $guias_validos = relatacerj_lista_guias();
    $guias = array_values(array_filter($guias, fn($g) =>
        in_array($g, $guias_validos, true)
    ));

    if (empty($guias)) return;

    // ---- PARTICIPANTES (LISTA ABERTA) ----
    setlocale(LC_COLLATE, 'pt_BR.UTF-8');

    $participantes_array = array_unique(array_filter(array_map(
        'sanitize_text_field',
        $_POST['participantes'] ?? []
    )));

    if (empty($participantes_array)) {
        $erros[] = 'Informe ao menos um participante.';
    }

    // ⚠️ aqui NÃO bloqueia — frontend já avisou
    $avisos = relatacerj_verificar_similaridade_participantes($participantes_array);

    sort($participantes_array, SORT_LOCALE_STRING);
    $participantes = implode(', ', $participantes_array);

    // ---- DADOS ----
    $dados = [
        'excursao'             => sanitize_text_field($_POST['excursao'] ?? ''),
        'guia'                 => implode(', ', $guias),
        'guias_auxiliares'     => sanitize_text_field($_POST['guias_auxiliares'] ?? ''),
        'local'                => sanitize_text_field($_POST['local'] ?? ''),
        'categoria'            => sanitize_text_field($_POST['categoria'] ?? ''),
        'data_inicio'          => relatacerj_normalizar_data($_POST['data_inicio'] ?? ''),
        'data_fim'             => relatacerj_normalizar_data($_POST['data_fim'] ?? ''),
        'hora_inicio'          => relatacerj_normalizar_hora($_POST['hora_inicio'] ?? ''),
        'hora_fim'             => relatacerj_normalizar_hora($_POST['hora_fim'] ?? ''),
        'condicoes_climaticas' => sanitize_text_field($_POST['condicoes_climaticas'] ?? ''),
        'participantes'        => $participantes,
        'relato'               => sanitize_textarea_field($_POST['relato'] ?? ''),
        'pontos_atencao'       => sanitize_textarea_field($_POST['pontos_atencao'] ?? ''),
        'preenchido_por'       => sanitize_text_field($_POST['preenchido_por'] ?? ''),
        'timestamp'            => current_time('Y-m-d H:i:s'),
    ];

    // ---- CSV ----
    $dir = wp_upload_dir()['basedir'];
    $csv = $dir . '/relatos_excursao.csv';

    $novo = !file_exists($csv);
    $fp = fopen($csv, 'a');

    if ($novo) fputcsv($fp, array_keys($dados));
    fputcsv($fp, array_values($dados));
    fclose($fp);
}

