<?php

/* Order Item
 * ========================================================================== */
class OrderItem implements ModelInterface {

  public $id;
  public $order_id;
  public $recipe;

  public function __toString() {
    return "" . $this->id . $this->order_id . $this->recipe;
  }

  /* Constructors
   * ------------------------------------------------------ */
  public function __construct($id, $order_id, $recipe) {
    // precondition
    if (!($recipe instanceof Recipe)) {
      throw new InvalidArgumentException("OrderItem constructor: the $recipe arguement must be of type Recipe.");
    }

    $this->id = $id;
    $this->order_id = $order_id;
    $this->recipe = $recipe;
  }

  /* Converters
   * ------------------------------------------------------
   * See the `ModelInterface`.
   */
  public static function FromData($data) {
    return new OrderItem(
      $data->id,
      $data->order_id,
      $data->recipe
    );
  }

  public static function FromEntry($entry, $prefix = "") {
    return new OrderItem(
      $entry[$prefix."id"],
      $entry[$prefix."order_id"],
      RecipeController::get($entry[$prefix."recipe_id"])
    );
  }

  public function ToEntry() {
    $entry = (object) [
      "id"        => $this->id,
      "order_id"  => $this->order_id,
      "recipe_id" => $this->recipe->id
    ];
  }

}

?>