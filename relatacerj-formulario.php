<?php
/**
 * Plugin Name: RelataCERJ - Formulário
 * Description: Formulário simples que gera CSV normalizado
 * Version: 1.0
 */

defined('ABSPATH') || exit;

// Inclui os arquivos de funcionalidades
require_once plugin_dir_path(__FILE__) . 'includes/relato_excursao.php';
require_once plugin_dir_path(__FILE__) . 'includes/handler.php';

/**
 * Enfileira CSS e JS do plugin
 */
function relatacerj_enqueue_scripts() {
    // CSS do formulário
    wp_enqueue_style(
        'relatacerj-form-style',
        plugin_dir_url(__FILE__) . 'assets/form.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'assets/form.css')
    );

    // jQuery UI Autocomplete (necessário para o autocomplete)
    wp_enqueue_script('jquery-ui-autocomplete');

    // JS de autocomplete
    wp_enqueue_script(
        'relatacerj-autocomplete',
        plugin_dir_url(__FILE__) . 'assets/autocomplete.js',
        ['jquery', 'jquery-ui-autocomplete'],
        filemtime(plugin_dir_path(__FILE__) . 'assets/autocomplete.js'),
        true
    );

    // Passa a URL do AJAX para o JS
    wp_localize_script('relatacerj-autocomplete', 'relatacerj_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);

}
add_action('wp_enqueue_scripts', 'relatacerj_enqueue_scripts');

