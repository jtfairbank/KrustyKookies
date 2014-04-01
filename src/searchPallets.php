<?php
require_once("dynamic/template/setup.php");
require_once("dynamic/api/lib/loader.php");

$view = "CHOOSE_SEARCH";
$searchResults = null;

if (array_key_exists("searchType", $_GET)) {
  if ($_GET["searchType"] === "ByRecipe") {
    $recipe = RecipeController::get($_GET["recipeID"]);
    $pallets = PalletController::getAllByRecipe($recipe);

    $view = "RECIPE_SEARCH";
    $searchResults = (object) [
      "recipe" => $recipe,
      "pallets" => $pallets,
    ];

  } elseif ($_GET["searchType"] === "ByDateRange") {
    $start = $_GET["start"];
    $end = $_GET["end"];
    $pallets = PalletController::getAllInRange($start, $end);

    $view = "DATERANGE_SEARCH";
    $searchResults = (object) [
      "start" => $start,
      "end" => $end,
      "pallets" => $pallets,
    ];
  }
}

echo $twig->render("searchPallets.twig", array(
  // template variables
  "title" => "Check In Pallet",
  "view" => $view,
  "recipes" => RecipeController::getAll(),
  "searchResults" => $searchResults,
));
?>