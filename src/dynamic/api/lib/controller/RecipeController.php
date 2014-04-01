<?php

/* Recipe Controller
 * ================================================================================ */
class RecipeController implements CrudInterface, APIActionInterface {

  protected static $table = "recipes";


  /* Perform an Action
   * ------------------------------------------------------------ */
  public static function takeAction($request) {
    $response = null;

    $action = $request->getAction();
    switch($action) {
      case "create":
        $recipe = Recipe::FromData($request->getData());
        $createdRecipe = static::create($recipe);
        $response = new RestResponse(201, $createdRecipe, 'text/json');

        break;

      case "get":
        $id = $request->getData();

        try {
          $recipe = static::get($id);
          $response = new RestResponse(200, $recipe, 'text/json');

        } catch(ReadException $e) {
          $response = new RestResponse(404, null, 'text/plain');
        }

        break;

      case "getAll":
        $recipes = static::getAll();

        if (count($recipes) > 0) {
          $response = new RestResponse(200, $recipes, 'text/json');
        } else {
          $response = new RestResponse(404, [], 'text/json');
        }

        break;

      default:
        throw new Exception("Invalid Parameter: RecipeController->takeAction doesn't know how to handle action $action.");
        break;
    }

    return $response;
  }


  /* Create
   * ------------------------------------------------------ */
  public static function create($recipe) {
    $id = CrudController::create(static::$table, $recipe->ToEntry());

    foreach ($recipe->ingredients as $ingredient) {
      $ingredient->recipeID = $id;
      CrudController::create("recipe_ingredients", $ingredient->ToEntry());
    }

    $createdRecipe = static::get($id);

    return $createdRecipe;
  }


  /* Read
   * ------------------------------------------------------------ */
  public static function get($id) {
    $recipe = null;

    $entry = CrudController::get(static::$table, $id);
    $ingredientEntries = static::getIngredientEntries($id);
    if ($entry) {
      $recipe = Recipe::FromEntry($entry, $ingredientEntries);
    } else {
      throw new ReadException("RecipeController::get cannot find the Recipe. id==".$id);
    }

    return $recipe;
  }

  protected static function getIngredientEntries($id) {
    // get db info
    $db = getDBConn();
    $table = "recipe_ingredients";

    // build sql statement
    $vals = [
      ":recipeID": $id
    ];
    $sql = <<<SQL
      SELECT *
      FROM `$table`
      WHERE `recipe_id` = :recipeID
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the models
    return $statement->fetchAll(PDO::FETCH_OBJ);
  }

  public static function getAll() {
    $recipes = [];

    $entries = CrudController::getAll(static::$table);
    foreach ($entries as $entry) {
      $ingredientEntries = static::getIngredientEntries($entry->id);
      array_push($recipes, Recipe::FromEntry($entry, $ingredientEntries));
    }

    return $recipes;
  }

}

?>