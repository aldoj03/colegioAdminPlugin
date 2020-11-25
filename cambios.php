<?php

function wporg_remove_all_dashboard_metaboxes()
{
    // require_once 'imprimir.php';

    // Remove Welcome panel
    remove_action('welcome_panel', 'wp_welcome_panel');
    // Remove the rest of the dashboard widgets
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('health_check_status', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal');
    remove_meta_box('woocommerce_dashboard_recent_reviews', 'dashboard', 'normal');





    wp_add_dashboard_widget(
        'co_admin_cambios_widget',
        esc_html__('Cambios', 'co-admin'),
        'co_admin_cambios_widget_function'
    );

    wp_add_dashboard_widget(
        'co_admin_cambios_history_widget',
        esc_html__('Historial de cambios', 'co-admin'),
        'co_admin_cambios_history_widget_function'
    );
}
add_action('wp_dashboard_setup', 'wporg_remove_all_dashboard_metaboxes');






function co_admin_cambios_history_widget_function()
{
    $posts = get_posts(array('post_type' => 'cambios'));

   

    $html = '<ul><li>Dolar------------Tasa</li>';
    foreach ($posts as $post) {
        $posts_meta = get_post_meta($post->ID);
        $tasa = $posts_meta['tasa'][0];
        $dolar = $posts_meta['dolar'][0];
        $date = $post->post_date;
        $html .= '<li>'.$dolar.'USD ------'.$tasa.'BS-------------'.$date.' </li>';
    }

    $html .= '</ul>';
    echo $html;
}


function co_admin_cambios_widget_function()
{
    $posts = get_posts(array('post_type' => 'cambios'));

    $meta_data = get_post_meta($posts[0]->ID);
    $cambio = $meta_data['tasa'][0];
    $dolar = $meta_data['dolar'][0];

?><form method="POST" action="<?php echo admin_url('admin.php'); ?>">
        <h4><strong style="color:red">Al Actualizar se modificará el precio de la mensualidad</strong></h4>
        <table class="form-table" role="presentation">
            <tbody>
                <tr class="user-user-login-wrap">
                    <th><label for="dolar">Precio de Dolares</label></th>
                    <td><input type="number" name="dolar" id="dolar" value="<?php echo $dolar ?>" class="regular-text" required> <span class="description">Precio fijo en dolares.</span></td>
                </tr>
                <tr class="user-user-login-wrap">
                    <th><label for="tasa">Cambio en Bs</label></th>
                    <td><input type="number" name="tasa" id="tasa" value="<?php echo $cambio ?>" class="regular-text" required> <span class="description">Cambio en Bolívares respecto al Dolar.</span></td>
                </tr>
                <tr class="user-user-login-wrap">
                    <td>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Actualizar precios"></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </form><?php
        }

        function newCambio()
        {

            $dolar = $_POST['dolar'];
            $tasa = $_POST['tasa'];

            if (isset($dolar) && isset($tasa)) {

                $res = wp_insert_post(array(
                    'post_title' => current_time('Y-m-d g:i:s a'),
                    'post_type' => 'cambios',
                    'post_status'   => 'publish',
                    'meta_input'   => array(
                        'dolar' => $_POST['dolar'],
                        'tasa' => $_POST['tasa'],
                    ),
                ), true);

                update_products_by_x($dolar, $tasa);
                wp_redirect($_SERVER['HTTP_REFERER']);
            }
        }


        function update_products_by_x($dolar, $tasa)
        {


            // getting all products
            $products_ids = get_posts(array(
                'post_type'        => 'product', // or ['product','product_variation'],
                'post_status'      => 'publish',
                'fields'           => 'ids',
            ));



            $newPrice = $dolar * $tasa;
            if (isset($tasa) && isset($dolar)) {


                // Loop through product Ids
                foreach ($products_ids as $product_id) {

                    // Get the WC_Product object
                    $product = wc_get_product($product_id);
                    $product->set_price($newPrice);
                    $product->set_regular_price($newPrice);
                    // Mark product as updated
                    $product->save();
                }
            }
        }
