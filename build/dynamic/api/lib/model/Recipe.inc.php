<?php

/* Loading Order
 * ========================================================================== */

// TODO: reconcile this with the model interface- `FromEntry` is an issue
// class Recipe extends ModelInterface {
class Recipe {

  public $id;
  public $name;
  public $ingredients;

  public function __toString() {
    $ingredientsString = "";
    foreach ($this->ingredients as $ingredient) {
      $ingredientsString .= $ingredient;
    }

    return "" . $this->id . $this->name . $ingredientsString;
  }

  /* Constructor
   * ------------------------------------------------------ */
  public function __construct($id, $name, $ingredients) {
    $this->id = $id;
    $this->name = $name;
    $this->ingredients = $ingredients;
  }

  /* Converters
   * ------------------------------------------------------------
   * See the `ModelInterface`.
   */
  public static function FromData($data) {
    return new Recipe(
      $data->id,
      $data->name,
      $data->ingredients
    );
  }

  public static function FromEntry($entry, $ingredientEntries) {
    $ingredients = [];
    foreach ($ingredientEntries as $ingredientEntry) {
      array_push($ingredients, RecipeIngredient::FromEntry($ingredientEntry));
    }

    return new Recipe(
      $entry->id,
      $entry->name,
      $ingredients
    );
  }

  public function ToEntry() {
    $entry = (object) [
      "id"   => $this->id,
      "name" => $this->name,
    ];

    return $entry;
  }

}

?>