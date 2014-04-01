<?php

/* Loading Order Item
 * ========================================================================== */
class RecipeIngredient implements ModelInterface {

  public $id;
  public $recipeID;
  public $rawMaterial;
  public $amount;

  public function __toString() {
    return "" . $id . $recipeID . $rawMaterial . $amount;
  }

  /* Constructors
   * ------------------------------------------------------ */
  public function __construct($id, $recipeID, $rawMaterial, $amount) {
    // precondition: $rawMaterial must be a RawMaterial
    if (!($rawMaterial instanceof RawMaterial)) {
      throw new InvalidArgumentException("RecipeIngredient constructor: the $pallet arguement must be of type RawMaterial.");
    }

    $this->id = $id;
    $this->recipeID = $recipeID;
    $this->rawMaterial = $rawMaterial;
    $this->amount = $amount;
  }

  /* Converters
   * ------------------------------------------------------------
   * See the `ModelInterface`.
   */
  public static function FromData($data) {
    return new Customer(
      $data->id,
      $data->recipeID,
      $data->rawMaterial,
      $data->amount
    );
  }

  public static function FromEntry($entry) {
    return new Customer(
      $entry->id,
      $entry->recipe_id,
      RawMaterialController::get($entry->raw_material_id),
      $entry->amount
    );
  }

  public function ToEntry() {
    $entry = (object) [
      "id"              => $this->id,
      "recipe_id"       => $this->recipeID,
      "raw_material_id" => $this->rawMaterial->id,
      "amount"          => $this->amount,
    ];

    return $entry;
  }

}

?>