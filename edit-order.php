<?php




// display the extra data in the order admin panel
function co_admin_display_order_data($order)
{
;
    $user_meta = get_user_meta($order->get_user_id());
    $cedula = $user_meta['cedula'][0];
    $hijos = $user_meta['hijos'][0];
    $name = $user_meta['nickname'][0];
    $control =  get_post_meta($order->id, 'numero-control')[0];
    $pago =  get_post_meta($order->id, 'metodo-pago')[0]['tipo'];
    $banco =  get_post_meta($order->id, 'metodo-pago')[0]['banco'];
    $referencia =  get_post_meta($order->id, 'metodo-pago')[0]['referencia'];
?>
    <div class="form-field form-field-wide ">
        <?php if (!isset($cedula) && !isset($hijos)) : ?>
            <h4> Buscar Cliente por cedula </h4>
            <span class="select3-search select3-search--dropdown">
                <input class="select3-search__field" type="text" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="combobox" id="search_by_ci_in_order" style="visibility:hidden">
            </span>
        <?php endif; ?>
        <?php if (!isset($control)) : ?>
            <div id="co-admin-control-number">
                <div>
                    <h4> Número de control </h4>
                    <input type="text" name="numero-control" id="numero-control" value="<?= $control ?>" class="regular-text" required /><br />
                </div>
            </div>
        <?php endif; ?>
        <?php if (!isset($pago)) : ?>

            <div id="co-admin-metodo-pago">
                <h4> Metodo de Pago </h4>
                <div class="metodo-pago-container">
                    <label for="metodo-pago" class="metodo-pago-label">Efectivo<input type="radio" name="metodo-pago" id="metodo-pago-efectivo" class="regular-text" value="Efectivo" required /></label>
                    <label for="metodo-pago" class="metodo-pago-label">Transferencia<input type="radio" name="metodo-pago" id="metodo-pago-transferencia" class="regular-text" value="Transferencia" required /></label>
                    <label for="metodo-pago" class="metodo-pago-label">Mixto<input type="radio" name="metodo-pago" id="metodo-pago-mixto" class="regular-text" value="Mixto" required /></label>
                    <div id="extra-transferencia-inputs"></div>
                </div>
            </div>
        <?php endif; ?>

        <div id="co-admin-search-by-ci-result-container">
            <span class="spinner"></span>
            <div id="co-admin-search-by-ci-result-data">
                <?php if (isset($cedula) && isset($hijos)) : ?>
                    <p>
                        <h3>Numero de Control: <?= $control ?> </h3>
                    </p>
                    <p>
                        <h3>Name: <?= $name ?> </h3>
                    </p>
                    <p>
                        <h3>Cedula: <?= $cedula ?> </h3>
                    </p>
                    <p>
                        <h3>Hijos: <?= $hijos ?></h3>
                    </p>
                    <p>
                        <h3>Metodo de pago: <?= $pago ?></h3>
                    </p>
                    <?php if (isset($banco) && isset($referencia)) : ?>
                    <p>
                        <h3>Banco: <?= $banco ?></h3>
                        <h3>Referencia: <?= $referencia ?></h3>
                    </p>
                <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>
        <div class="co-admin-actions-order-btns">
            <button class="co-admin-save-order" style="display:none" <?= (!isset($cedula) && !isset($hijos)) ? 'disabled' : '' ?>>Completar <span class="dashicons dashicons-yes"></span></button>
            <button class="co-admin-imprimir-order" style="display:none" data-id="<?=$order->get_id()?>">Imprimir <span class="dashicons dashicons-format-aside"></span></button>
            <button class="co-admin-edit-order" style="display:none" ">Editar <span class="dashicons dashicons-edit-large"></span></button>
        </div>
    </div>
<?php }
add_action('woocommerce_admin_order_data_after_order_details', 'co_admin_display_order_data');


//guarda los metadatos en la orden
function co_admin_save_control_number($order_id, $post)
{
    $order = wc_get_order($order_id);
    if (isset($_POST['metodo-pago'])) {
        if ($_POST['metodo-pago'] != 'Efectivo') {
            $var_to_save = array('tipo' => $_POST['metodo-pago'], 'banco' => $_POST['banco'], 'referencia' => $_POST['referencia']);
        }else{
            $var_to_save = array('tipo' => $_POST['metodo-pago'], 'banco' => null, 'referencia' =>null);
        }
        $order->update_meta_data('metodo-pago', $var_to_save);
        $order->save_meta_data();
    }
    if (isset($_POST['numero-control'])) {
        $order->update_meta_data('numero-control', wc_clean($_POST['numero-control']));
        $order->save_meta_data();
    }
}
add_action('woocommerce_process_shop_order_meta', 'co_admin_save_control_number', 45, 2);


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
        if ($user) {

            $user_meta = get_user_meta($user[0]->data->ID);
            $user_to_send = array('id' => $user[0]->data->ID, 'cedula' => $user_meta['cedula'][0], 'hijos' => $user_meta['hijos'][0], 'name' => $user_meta['nickname'][0]);
            wp_send_json($user_to_send);
        }
        wp_send_json(false);

        die();
    }
}
