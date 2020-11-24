<?php




// display the extra data in the order admin panel
function kia_display_order_data_in_admin($order){ 
    
    $user_meta = get_user_meta($order->get_user_id());
    $cedula = $user_meta['cedula'][0];
    $hijos = $user_meta['hijos'][0];
    $name = $user_meta['nickname'][0];
    ?>
    <div class="form-field form-field-wide ">
    <?php if(!isset($cedula) && !isset($hijos)): ?>
        <h4> Buscar Cliente por cedula </h4>
        <span class="select3-search select3-search--dropdown">
            <input class="select3-search__field" type="text" tabindex="0" autocomplete="off"
             autocorrect="off" autocapitalize="none" spellcheck="false"
              role="combobox" id="search_by_ci_in_order" style="visibility:hidden">
        </span>
    <?php endif; ?>

        <div id="co-admin-search-by-ci-result-container">
            <span class="spinner"></span>
            <div id="co-admin-search-by-ci-result-data">
             <?php if(isset($cedula) && isset($hijos)): ?>
                <p><h3>Name: <?=$name?> </h3></p> <p><h3>Cedula: <?=$cedula?> </h3></p><p><h3>Hijos: <?=$hijos?></h3></p>
            <?php endif; ?>
            </div>
        </div>
        <div class="co-admin-actions-order-btns">
            <button class="co-admin-save-order" style="display:none">Completar <span class="dashicons dashicons-yes"></span></button>
            <button class="co-admin-imprimir-order" style="display:none">Imprimir <span class="dashicons dashicons-format-aside"></span></button>
            <button class="co-admin-edit-order" style="display:none">Editar <span class="dashicons dashicons-edit-large"></span></button>
        </div>
    </div>
<?php }
add_action('woocommerce_admin_order_data_after_order_details', 'kia_display_order_data_in_admin');




add_action('wp_ajax_nopriv_send-user-by-cedula', 'sendUserByCedula');
add_action('wp_ajax_send-user-by-cedula', 'sendUserByCedula');

// Función que procesa la llamada AJAX
function sendUserByCedula()
{
    $cedula  = isset($_POST['cedula']) ? $_POST['cedula'] : false;
    if (!$cedula) {
        wp_send_json(false);
    } else {
        $user = get_users(array(
            'meta_key' => 'cedula',
            'meta_value' => $cedula
        ));
        if($user){

            $user_meta = get_user_meta($user[0]->data->ID);
            $user_to_send = array('id' => $user[0]->data->ID, 'cedula' => $user_meta['cedula'][0], 'hijos' => $user_meta['hijos'][0], 'name' => $user_meta['nickname'][0]);
            wp_send_json($user_to_send);
        }
        wp_send_json(false);
        
        die();
    }
}
