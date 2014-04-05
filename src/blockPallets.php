<?php
require_once("dynamic/template/setup.php");
require_once("dynamic/api/lib/loader.php");

$view = "FORM";
$blockResults = [];

if (array_key_exists("block", $_POST)) {
  $recipe = RecipeController::get($_POST["recipeID"]);
  $start = $_POST["start"];
  $end = $_POST["end"];

  $blockedPallets = PalletController::blockAllInRange($recipe->id, $start, $end);

  $view = "BLOCK_RESULTS";
  $blockResults = (object) [
    "recipe" => $recipe,
    "start" => $start,
    "end" => $end,
    "pallets" => $blockedPallets,
  ];
}

echo $twig->render("blockPallets.twig", array(
  "title" => "Block Pallets",
  "view" => $view,

  // FROM view
  "recipes" => RecipeController::getAll(),

  // BLOCK_RESULTS view
  "blockResults" => $blockResults,
));

?>