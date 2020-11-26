<?php
/*
Plugin Name: Colegio Administracion
Description: plugin de administracion para el colegio
Version: 0.17
*/

function co_admin_enqueue()
{


    wp_enqueue_style('co_admin_style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('co_admin_script', plugin_dir_url(__FILE__) . 'assets/js/index.js', array('jquery'));
    wp_localize_script('co_admin_script', 'wp_ajax_tets_vars', array(
        'ajaxUrl' => admin_url('admin-ajax.php')
    ));


    register_post_type(
        'cambios',
        // CPT Options
        array(
            'labels' => array(
                'name' => __('Cambios'),
                'singular_name' => __('cambio')
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'cambio'),
            'show_in_rest' => true,

        )
    );

    newCambio();

    if (isset($_GET['imprimir']) && isset($_GET['imprimir_id'])) {

        include 'imprimir.php';
    }
}

add_action('init', 'co_admin_enqueue');









//remove dashboard main widgets








include 'adit_user-meta.php';
include 'cambios.php';
include 'edit-order.php';
include 'edit-pedidos-list.php';
include 'informes.php';


