<?php
require_once('dynamic/template/setup.php');
require_once('dynamic/api/lib/loader.php');

$view = 'FORM';
if (array_key_exists('recipeID', $_POST)) {
  $view = 'CHECKED_IN';

  $recipe = RecipeController::get($_POST['recipeID']);

  $pallet = new Pallet(
    null,                         // id
    $recipe,                      // recipe
    null,                         // producedOn- set by DB
    false                         // blocked
  );

  try {
    PalletController::create($pallet);

  } catch(RangeException $e) {
    $view = 'CHECKIN_FAILED';
  }
}

echo $twig->render("checkinPallet.twig", array(
  // template variables
  'title' => 'Check In Pallet',
  'recipes' => RecipeController::getAll(),
  'view' => $view,
));

?>