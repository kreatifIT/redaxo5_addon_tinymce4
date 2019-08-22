<?php

if (rex::isBackend() && isset($_REQUEST['page']) && !isset($_REQUEST['_pjax'])) {
    if (defined('rex_view::JS_IMMUTABLE')) {
        // R5.7+ support
        // disable asset-streaming, because tiny will load plugins after the main file which wouldn't work
        rex_view::addJsFile(rex_url::addonAssets('tinymce4', 'tinymce/tinymce.min.js'), [rex_view::JS_IMMUTABLE => false]);
    } else {
        rex_view::addJsFile(rex_url::addonAssets('tinymce4', 'tinymce/tinymce.min.js'));
    }

    // css klappt noch nicht im Moment, weil Dialog und 
    // Filemanager die gleichen Klassen verwenden, das Innere des Dialogs aber 
    // nicht responsive ist.
    //rex_view::addCssFile(rex_url::addonAssets('tinymce4', 'backend.css'));
    $user = \rex::getUser();
    if ($user) {
        $lang = $user->getLanguage();
        if ('' == $lang) {
            $lang = strtolower($dbconfig = \rex::getProperty('lang'));
        }
        $service_container = Tinymce4\Services\ServiceContainer::getInstance();
        $map = $service_container->getParameter('be_lang_map');
        if (!isset($map[$lang])) {
            $lang_pack = 'en_gb';
        } else {
            $lang_pack = $map[$lang];
        } 
        // Tinymce Übersetzungen laden
        rex_view::addJsFile(rex_url::addonAssets('tinymce4', 'tinymce/langs/'.$lang_pack.'.js'));
        // Tinymce init script
        rex_view::addJsFile(rex_url::addonAssets('tinymce4', 'tinymce4_init.'.$lang_pack.'.js'));
        
        // Wenn Tinymce neu installiert wurde, gibt es die Datei noch nicht
        $filename = \rex_path::addonAssets('tinymce4', 'tinymce4_init.'.$lang_pack.'.js');
        $modTime = (int) \rex_config::get('tinymce4', 'profile_upd_date', 1);

        if ($modTime >= (int) @filemtime($filename)) {
            $service_container->get('ProfileRepository')->rebuildInitScripts();
        }
    }
}

if (isset($_REQUEST['tinymce4_call'])) {
    rex_extension::register('PACKAGES_INCLUDED', function($ep) {
        if (isset($_REQUEST['tinymce4_call'])) {
            $service_container = Tinymce4\Services\ServiceContainer::getInstance();
            echo $service_container->handleRoute($_REQUEST['tinymce4_call']);
            die();
        }
    });
}
