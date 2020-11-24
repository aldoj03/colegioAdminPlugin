<?php

function wporg_remove_all_dashboard_metaboxes()
{
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
        'wporg_dashboard_widget',
        esc_html__('Example Dashboard Widget', 'wporg'),
        'wporg_dashboard_widget_function'
    );

  
    global $wp_meta_boxes;


    $default_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

  
    $example_widget_backup = array('example_dashboard_widget' => $default_dashboard['example_dashboard_widget']);
    unset($default_dashboard['example_dashboard_widget']);

    $sorted_dashboard = array_merge($example_widget_backup, $default_dashboard);

   
    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}
add_action('wp_dashboard_setup', 'wporg_remove_all_dashboard_metaboxes');







function wporg_dashboard_widget_function()
{
    $posts= get_posts(array('post_type'=> 'cambios'));

    $meta_data = get_post_meta($posts[0]->ID);
    $cambio = $meta_data['tasa'][0];
    $dolar = $meta_data['dolar'][0];

?><form  method="POST"  action="<?php echo admin_url( 'admin.php' ); ?>">
    <h4><strong style="color:red">Al Actualizar se modificará el precio de la mensualidad</strong></h4>
        <table class="form-table" role="presentation">
            <tbody>
                <tr class="user-user-login-wrap">
                    <th><label for="dolar">Precio de Dolares</label></th>
                    <td><input type="number" name="dolar" id="dolar" value="<?php echo $dolar?>" class="regular-text" required> <span class="description">Precio fijo en dolares.</span></td>
                </tr>
                <tr class="user-user-login-wrap">
                    <th><label for="tasa">Cambio en Bs</label></th>
                    <td><input type="number" name="tasa" id="tasa" value="<?php echo $cambio?>" class="regular-text" required>  <span class="description">Cambio en Bolívares respecto al Dolar.</span></td>
                </tr>
                <tr class="user-user-login-wrap">
                    <td><p class="submit"><input type="submit" name="submit" id="submit"  class="button button-primary" value="Actualizar precios"></p></td>
                </tr>
            </tbody>
        </table>
    </form><?php
        }


// add_action('admin_post_custom_form_submit','our_custom_form_function');


// function our_custom_form_function(){
//     wp_redirect(admin_url('admin.php?page=your_custom_page_where_form_is'));
// }










function newCambio(){
   
    if(isset($_POST['dolar']) && isset($_POST['tasa'] ) ){
      
       $res = wp_insert_post(array(
            'post_title'=> current_time('Y-m-d g:i:s a'),
            'post_type' => 'cambios',
            'post_status'   => 'publish',
            'meta_input'   => array(
                'dolar' => $_POST['dolar'],
                'tasa' => $_POST['tasa'],
            ),
        ), true);

    
        wp_redirect( $_SERVER['HTTP_REFERER'] );
      
    }


}


