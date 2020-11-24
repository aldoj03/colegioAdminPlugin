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


    register_post_type( 'cambios',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Cambios' ),
                'singular_name' => __( 'cambio' )
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'cambio'),
            'show_in_rest' => true,
 
        )
    );

    newCambio();


}

add_action('init', 'co_admin_enqueue');









//remove dashboard main widgets



        // add cedula in orders list

        // function webroom_add_order_new_column_header( $columns ) {

        //     $new_columns = array();

        //     foreach ( $columns as $column_name => $column_info ) {

        //         $new_columns[ $column_name ] = $column_info;

        //         if ( 'order_total' === $column_name ) {
        //             $new_columns['order_details'] = __( 'Details', 'my-textdomain' );
        //         }
        //     }

        //     return $new_columns;
        // }
        // add_filter( 'manage_edit-shop_order_columns', 'webroom_add_order_new_column_header', 20);

        // add_action( 'manage_shop_order_posts_custom_column', 'webroom_add_wc_order_admin_list_column_content' );

        // function webroom_add_wc_order_admin_list_column_content( $column ) {

        //     global $post;

        //     if ( 'order_details' === $column ) {

        //         $order = wc_get_order( $post->ID );
        //         echo '<p>Phone: ' . $order->get_billing_phone() . '</p>';

        //     }
        // }





        include 'adit_user-meta.php';
        include 'cambios.php';
        include 'edit-order.php';

