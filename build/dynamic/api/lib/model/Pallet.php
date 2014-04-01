<?php

/* Pallet
 * ========================================================================== */
class Pallet implements ModelInterface {

  public $id;
  public $recipe;
  public $producedOn;
  public $blocked;

  public function __toString() {
    return "" . $this->id . $this->recipe . $this->producedOn . $this->blocked;
  }

  /* Constructors
   * ------------------------------------------------------ */
  public function __construct($id, $recipe, $producedOn, $blocked) {
    // precondition: $recipe must be a Recipe
    if (!($recipe instanceof Recipe)) {
      throw new InvalidArgumentException("Pallet constructor: the $recipe arguement must be of type Recipe.");
    }

    $this->id = $id;
    $this->recipe = $recipe;
    $this->producedOn = $producedOn;
    $this->blocked = $blocked;
  }

  /* Converters
   * ------------------------------------------------------------
   * See the `ModelInterface`.
   */
  public static function FromData($data) {
    return new Pallet(
      $data->id,
      $data->recipe,
      $data->producedOn,
      $data->blocked
    );
  }

  public static function FromEntry($entry) {
    return new Pallet(
      $entry->id,
      RecipeController::get($entry->recipe_id),
      $entry->producedOn,
      $entry->blocked === 1 ? true : false
    );
  }

  public static function ToEntry() {
    $entry = (object) [
      "id"         => $this->id,
      "recipe_id"  => $this->recipe->id,
      "producedOn" => $this->producedOn,
      "blocked"    => $this->blocked === true ? 1 : 0;
    ];

    return $entry;
  }

}

?>