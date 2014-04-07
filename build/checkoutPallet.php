<?php
require_once('dynamic/template/setup.php');
require_once('dynamic/api/lib/loader.php');

$view = 'FORM';
$checkedOut = null;
if (array_key_exists('checkout', $_POST)) {
  $pallet = PalletController::checkout($_POST['palletID']);

  $view = 'CHECKED_OUT';
  $checkedOut = $pallet;
}

echo $twig->render("checkoutPallet.twig", array(
  'title' => 'Check Out Pallet',
  'view' => $view,

  // FORM view
  'pallets' => PalletController::getFree(),
  'orders' => OrderController::getUnfulfilled(),

  // CHECKED OUT view
  'checkedOut' => $checkedOut,
));

?>