<?php
defined('ABSPATH') || exit;

function relatacerj_lista_guias() {

    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $arquivo = plugin_dir_path(__FILE__) . '../data/guias.csv';

    if (!file_exists($arquivo)) {
        return [];
    }

    $lista = [];

    if (($fp = fopen($arquivo, 'r')) !== false) {

        // pula cabeçalho
        fgetcsv($fp);

        while (($linha = fgetcsv($fp)) !== false) {
            if (!empty($linha[0])) {
                $lista[] = trim($linha[0]);
            }
        }

        fclose($fp);
    }

    setlocale(LC_COLLATE, 'pt_BR.UTF-8', 'pt_BR', 'Portuguese_Brazil');
    sort($lista, SORT_LOCALE_STRING);

    $cache = $lista;
    return $lista;
}

