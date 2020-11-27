<?php


function wpdocs_register_my_custom_menu_page(){
   
add_menu_page( 'informes', 'ingresos','ingresos', 'manage_options', 'ingresos' );
add_menu_page('Informes', 'Informes', 'manage_options', 'informes','my_custom_menu_page',
' dashicons-portfolio',
6);
add_submenu_page( 'informes', 'Ingresos', 'Ingresos',
    'manage_options', 'ingresos', 'co_admin_ingresos_page');
add_submenu_page( 'informes', 'Morosos', 'Morosos',
    'manage_options', 'morosos', 'co_admin_morosos_page');
  
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );
 
/**
 * Display a custom menu page
 */
function my_custom_menu_page(){
    $url_base = get_site_url( );
   
    ?>
    <div class="wrap">
        <h2>Informes</h2>
        <br>
        <br>
        <div>
            <h2>Ingresos por fecha</h2>
            <form action="" style="display:flex; align-items:center">
                <input type="date" name="fecha_ingresos" id="fecha_ingresos" required>
                <input type="hidden" name="page" value="ingresos" >
                <button class="btns__informes button ingresos">Obtener resultados</button>
            </form>
        </div>
        <div>
            <h2>Morosos</h2>
                <a href="<?=$url_base?>/wp-admin/admin.php?page=morosos" class="btns__informes button morosos">Obtener resultados</a>
        </div>
    </div>

    <?php
      
}

 
function co_admin_ingresos_page() {
    
    
    
    if(isset($_GET['fecha_ingresos'])){
        $args = array(
            'date_completed' =>$_GET['fecha_ingresos'],
        );
        $resultados_title = 'Ingresos de '.$_GET['fecha_ingresos'];
    }else{

        $args = array(
            'date_completed' => date('yy-m-d'),
        );
        $resultados_title = 'Ingresos de hoy';

    }
    $orders = wc_get_orders( $args );
    // var_dump( $orders)
    ?>
    <div class="wrap">
    <h1><?= $resultados_title ?></h1>
        <table style="margin-top:30px" class="wp-list-table widefat fixed striped table-view-list posts">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
            <th scope="col" id="order_number" class="manage-column column-order_number column-primary sortable desc">
                Pedido
            </th
            ><th scope="col" class="manage-column column-order_ci">Cédula</th>
            </th>
            <th scope="col"  class="manage-column column-order_status">Estado</th>
            <th scope="col"  class="manage-column column-order_status">Total</th>
            <th scope="col"  class="manage-column column-order_status">Imprimir</th>
           </tr>
	</thead>

	<tbody id="the-list" >
    <?php 
    
        foreach ($orders as $order) {

            $orderID = $order->ID;
            $orderTotal = $order->get_total();
            $user_meta = get_user_meta($order->get_customer_id());
            $cedula = $user_meta['cedula'][0];
            $nombre = $user_meta['nickname'][0];
            ?>
            
            <tr class="iedit author-self level-0 type-shop_order status-wc-completed hentry">
			<th scope="row" class="check-column">		
			    <div class="locked-indicator">
				    <span class="locked-indicator-icon" aria-hidden="true"></span>				
			    </div>
			</th>
            <td class="order_number column-order_number has-row-actions column-primary" data-colname="Pedido">
            <a href="http://localhost/wordpress/wp-admin/post.php?post=<?=$orderID?>&amp;action=edit" class="order-view"><strong>#<?= $orderID.' ' .  $nombre ?></strong></a></td><td class="order_ci column-order_ci" data-colname="Cédula"><p><?= $cedula ?></p></td><td class="order_status column-order_status" data-colname="Estado"><mark class="order-status status-completed tips"><span>Completado</span></mark></td><td class="billing_address column-billing_address hidden" data-colname="Facturación">–</td><td class="shipping_address column-shipping_address hidden" data-colname="Enviar a">–</td><td class="order_total column-order_total" data-colname="Total"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">Bs</span><?= $orderTotal ?></span></td><td class="imprimir column-imprimir" data-colname="Imprimir"><a href="?imprimir=true&amp;imprimir_id=<?= $orderID ?>" class="button imprimir_enlace">Imprimir</a></td><td class="wc_actions column-wc_actions hidden" data-colname="Acciones"><p></p></td>		</tr>
			</tbody>
            <?php
        }
        ?>

</table>
</div>
    <?php
}
 
function co_admin_morosos_page() {

    ?>
        <div class="wrap">
        <h1>Morosos</h1>
        </div>
    <?php
}

