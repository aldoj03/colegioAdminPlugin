<?php
// add cedula in orders list

function webroom_add_order_new_column_header($columns)
{

    $new_columns = array();

    foreach ($columns as $column_name => $column_info) {

        $new_columns[$column_name] = $column_info;

        if ('order_number' === $column_name) {
            $new_columns['order_ci'] = 'Cédula';
        }
        if ('order_total' === $column_name) {
            $new_columns['imprimir'] = 'Imprimir';
        }
    }

    return $new_columns;
}
add_filter('manage_edit-shop_order_columns', 'webroom_add_order_new_column_header', 20);

add_action('manage_shop_order_posts_custom_column', 'webroom_add_wc_order_admin_list_column_content');

function webroom_add_wc_order_admin_list_column_content($column)
{

    global $post;

    if ('order_ci' === $column) {

        $order = wc_get_order($post->ID);
        $user_meta = get_user_meta($order->get_customer_id());
        $cedula = $user_meta['cedula'][0];

        echo '<p>' . $cedula . '</p>';
    }

    if ('imprimir' === $column) {

        $order = wc_get_order($post->ID);
        if ($order->get_status() == 'completed') {

            echo '<a href="?imprimir=true&imprimir_id=' . $post->ID . '" class="button imprimir_enlace">Imprimir</a>';
        }
    }
}
