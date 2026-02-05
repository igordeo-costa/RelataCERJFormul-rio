<?php
defined('ABSPATH') || exit;

function relatacerj_lista_participantes() {

    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $arquivo = plugin_dir_path(__FILE__) . '../data/participantes.csv';

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

    sort($lista, SORT_LOCALE_STRING);

    $cache = $lista;
    return $lista;
}

