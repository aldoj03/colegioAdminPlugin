<?php



function wpdocs_register_my_custom_menu_page(){
    add_menu_page( 
        'Informes',
        'informes',
        'manage_options',
        'informes',
        'my_custom_menu_page',
        ' dashicons-portfolio',
        6
    ); 
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );
 
/**
 * Display a custom menu page
 */
function my_custom_menu_page(){
    $args = array(
        'date_completed' => date('YYYY-MM-DD'),
    );
    $orders = wc_get_orders( $args );
    var_dump($orders);
    ?>
    <div class="wrap">
        <h2>Informes</h2>
        <br>
        <br>
        <button class="btns__informes button ingresos">Imprimir Ingresos diarios</button>
        <button class="btns__informes button morosos">Imprimir Morosos</button>
    </div>

    <?php
      
}