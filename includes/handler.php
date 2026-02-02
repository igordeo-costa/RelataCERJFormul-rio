<?php
defined('ABSPATH') || exit;

/**
 * Lista de guias válidos
 */
function relatacerj_lista_guias() {
    return [
        'Alexandre "Xandão" Gomes',
        'Bruno Waldman',
        'Caiê Visintin',
        'Carla Romão',
        'Daniel Rodriguez',
        'Henrique Menescal',
        'Jana Menezes',
        'Júlio Mello',
        'Kátia Perensim',
        'Livia Cardoso',
        'Luiz Puppin',
        'Marcelo Magal',
        'Mariana Lopes',
        'Mariozinho Richard',
        'Miriam Gerber',
        'Pedro Bugim',
        'Thiago Gabriel',
        'Valéria Aquino',
        'Fernando "Velho" Fajardo',
        'Waldecy Lucena',
        'Zé Kili'
    ];
}

/**
 * AJAX para buscar guias (autocomplete)
 */
add_action('wp_ajax_relatacerj_buscar_guias', 'relatacerj_buscar_guias');
add_action('wp_ajax_nopriv_relatacerj_buscar_guias', 'relatacerj_buscar_guias');

function relatacerj_buscar_guias() {
    $term = sanitize_text_field($_GET['term'] ?? '');
    $guias = relatacerj_lista_guias();

    $resultados = array_filter($guias, function ($guia) use ($term) {
        return stripos($guia, $term) !== false;
    });

    wp_send_json(array_values($resultados));
}

/**
 * Normaliza datas para formato brasileiro
 */
function relatacerj_normalizar_data($data) {
    if (empty($data)) return '';

    $dt = DateTime::createFromFormat('Y-m-d', $data);
    return $dt ? $dt->format('d/m/Y') : '';
}

/**
 * Normaliza hora para formato H:i
 */
function relatacerj_normalizar_hora($hora) {
    if (empty($hora)) return '';

    $dt = DateTime::createFromFormat('H:i', $hora);
    return $dt ? $dt->format('H:i') : '';
}

/**
 * Processa o formulário e salva no CSV
 */
add_action('init', 'relatacerj_processar_formulario');

function relatacerj_processar_formulario() {

    if (
        !isset($_POST['relatacerj_submit']) ||
        !isset($_POST['relatacerj_nonce']) ||
        !wp_verify_nonce($_POST['relatacerj_nonce'], 'relatacerj_salvar')
    ) {
        return;
    }

    // PROCESSA os guias
    $guias_array = $_POST['guias'] ?? [];
    $guias_array = array_map('sanitize_text_field', $guias_array);
    $guias_array = array_filter($guias_array);

    // VALIDA cada guia
    $guias_validos = relatacerj_lista_guias();
    $guias_array = array_filter($guias_array, function($guia) use ($guias_validos) {
        return in_array($guia, $guias_validos, true);
    });

    // Se nenhum guia válido, aborta
    if (empty($guias_array)) {
        return;
    }

    $guias = implode(', ', $guias_array);

    // Monta o array de dados
    $dados = [
        'excursao'             => sanitize_text_field($_POST['excursao'] ?? ''),
        'guia'                 => $guias,
        'guias_auxiliares'     => sanitize_text_field($_POST['guias_auxiliares'] ?? ''),
        'local'                => sanitize_text_field($_POST['local'] ?? ''),
        'categoria'            => sanitize_text_field($_POST['categoria'] ?? ''),
        'data_inicio'          => relatacerj_normalizar_data($_POST['data_inicio'] ?? ''),
        'data_fim'             => relatacerj_normalizar_data($_POST['data_fim'] ?? ''),
        'hora_inicio'          => relatacerj_normalizar_hora($_POST['hora_inicio'] ?? ''),
        'hora_fim'             => relatacerj_normalizar_hora($_POST['hora_fim'] ?? ''),
        'condicoes_climaticas' => sanitize_text_field($_POST['condicoes_climaticas'] ?? ''),
        'participantes'        => sanitize_textarea_field($_POST['participantes'] ?? ''),
        'relato'               => sanitize_textarea_field($_POST['relato'] ?? ''),
        'pontos_atencao'       => sanitize_textarea_field($_POST['pontos_atencao'] ?? ''),
        'preenchido_por'       => sanitize_text_field($_POST['preenchido_por'] ?? ''),
        'timestamp'            => current_time('Y-m-d H:i:s'),
    ];

    // Grava no CSV
    $upload_dir = wp_upload_dir();
    $csv_path = $upload_dir['basedir'] . '/relatos_excursao.csv';

    $novo = !file_exists($csv_path);
    $fp = fopen($csv_path, 'a');

    if ($novo) {
        fputcsv($fp, array_keys($dados));
    }

    fputcsv($fp, array_values($dados));
    fclose($fp);
}

