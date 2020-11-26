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

$imprimir = '';
foreach ($data_array as $key => $value) {
   $imprimir .= '<h3>'.$key .': </h3>' . $value;
}


// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($imprimir);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
