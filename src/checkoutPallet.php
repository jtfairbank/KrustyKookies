<?php
require_once('dynamic/template/setup.php');
require_once('dynamic/api/lib/loader.php');

$view = 'FORM';
if (array_key_exists('checkout', $_POST)) {
  $view = 'CHECKED_OUT';

  // TODO
}

echo $twig->render("checkoutPallet.twig", array(
  'title' => 'Check Out Pallet',
  'view' => $view,

  // FORM view
  'pallets' => PalletController::getFree(),
  'orders' => OrderController::getUnfulfilled(),
));

?>