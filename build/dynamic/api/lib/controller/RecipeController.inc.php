<?php

/* Recipe Controller
 * ================================================================================ */
class RecipeController implements CrudInterface {

  protected static $table = "recipes";


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

    // build sql statement
    $vals = [
      ":recipeID" => $id
    ];
    $sql = <<<SQL
      SELECT *
      FROM `recipe_ingredients`
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


  /* Update
   * ------------------------------------------------------ */
  public static function update($recipe) {
    throw new Exception("RecipeController: not implemented exception.");
  }


  /* Delete
   * ------------------------------------------------------ */
  public static function delete($id) {
    throw new Exception("RecipeController: not implemented exception.");
  }

}

?>