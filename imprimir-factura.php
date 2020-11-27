<?php


require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$data_array = array();

$order = wc_get_order($_GET['imprimir_id']);

$user_meta = get_user_meta($order->get_customer_id());

$cedula = $user_meta['cedula'][0];
$hijos = $user_meta['hijos'][0];
$nombre = $user_meta['nickname'][0];
$pago =  get_post_meta($order->id, 'metodo-pago')[0]['tipo'];
$control =  get_post_meta($order->id, 'numero-control')[0];
array_push($data_array, $cedula, $hijos, $nombre, $pago, $control);
if ($pago != 'Efectivo') {

    $banco =  get_post_meta($order->id, 'metodo-pago')[0]['banco'];
    $referencia =  get_post_meta($order->id, 'metodo-pago')[0]['referencia'];
    array_push($data_array, $banco, $referencia);
}



$imprimir = '
<style>
    .factura {
        padding: 16px;
    }

    header {
        display: flex;
        justify-content: space-around;
    }

    .logo_container {
        width: 100px;
    }

    .encabezado {
        line-height: 22px;
    }

    .encabezado p {
        text-align: center;
        margin: 0;
    }

    .data {
        border-radius: 18px;
        border: 1px solid rgb(55, 55, 255);
        padding: 16px;
    }

    .forma_pago {
        display: flex;
        justify-content: space-around;
    }
    .forma_pago div{
        display:flex;
        align-items:center
    }
    .content {
        width: 100%;
        
        
    }
    forma_pago input{
        margin-top:10px
    }
    .main_content{
        margin-top: 16px;
        border: 1px solid rgb(55, 55, 255);
        border-radius: 18px;
        padding: 8px;
    }

    table {
        border: none;
        border-collapse: collapse;
    }
   
    table td {
        text-align: center;
    }

    tr>td,
    tr>th {
        padding-bottom: 1em;
    }

    tbody td,
    tbody th {
        border-left: 1px solid #000;
        border-right: 1px solid #000;
    }

    tbody td:first-child,
    tbody th:first-child {
        border-left: none;
    }

    tbody td:last-child,
    tbody th:last-child {
        border-right: none;
    }

    tfoot td {
        padding-top: 2em;

    }

    .footer span {
        margin-right: 30px;
    }
    table{
        width:100%
    }
</style>
        <div class="factura">
            <header>  <div>
            <div>No De Control:'.$control.'</div>
            <div>No De Factura:'.$order->id.'</div>
            <div class="fecha">
                '.$order->order_date.'
            </div>
        </div>        
        <div class="encabezado">
            <p>U:E: COLEGIO <br>"LA VILLA DE LOS NIÑOS"</p>
            <p>Villa Olímpica Parte Alta de las Lomas</p>
            <p>San Cristóbal - Edo. Táchira.</p>
            <p>Telf: (0276) 341.2274</p>
            <p>Rif: J-30689977-4</p>
        </div>
        <div class="logo_container">
            <img src="" alt="">
        </div>
    </header>
    <div class="data">
        <p><strong>Nombre o Razón Social: </strong>'.$nombre .'</p>
        <p><strong>RIF.CI. o Pasaporte No.: </strong>'.$cedula .'</p>
        <p class="forma_pago">Forma de Pago:<p> Efectivo: <input type="checkbox" '.($pago == 'Efectivo'? 'checked':'').'>
        Transferencia: <input type="checkbox"' .($pago == 'Transferencia'? 'checked':'').'>
        Mixto: <input type="checkbox" ' .($pago == 'Mixto'? 'checked':'').' ></p></p>
        
                
    </div>
    <div class="main_content">
        <table class="content">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Nombre o Razon Social</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';

foreach ($order->get_items() as $item_id => $item ) {
    $product = $item->get_product();
    $product_name   = $item->get_name(); 
    $item_quantity  = $item->get_quantity();
    $item_total     = $item->get_total();
    $active_price   = $product->get_price(); 
    $imprimir .=    '<tr>
                        <td>'.$item_quantity.'</td>
                        <td>'.$product_name.'</td>
                        <td>'. $item_total / $item_quantity  .'Bs</td>
                        <td>'.$item_total.'Bs</td>
                    </tr>';

}

$imprimir .= '   
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Moton Total: </td>
                    <td>'.$order->get_total().'Bs</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="footer">
        <span>No de referencia:'. (isset($referencia)? $referencia:'') .'</span>
        <span>Banco: '. (isset($banco)? $banco:'') .'</span>
    </div>
</div>';


// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($imprimir);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
