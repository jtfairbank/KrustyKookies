<?php

/* RawMaterial
 * ========================================================================== */
class RawMaterial implements ModelInterface {

  public $id;
  public $name;
  public $amount;

  public function __toString() {
    return "" . $this->id . $this->name . $this->amount;
  }

  /* Constructors
   * ------------------------------------------------------ */
  public function __construct($id, $name, $amount) {
    $this->id = $id;
    $this->name = $name;
    $this->address = $amount;
  }

  /* Converters
   * ------------------------------------------------------------
   * See the `ModelInterface`.
   */
  public static function FromData($data) {
    return new RawMaterial(
      $data->id,
      $data->name,
      $data->amount
    );
  }

  public static function FromEntry($entry) {
    return new RawMaterial(
      $entry->id,
      $entry->name,
      $entry->amount_in_stock
    );
  }

  public function ToEntry() {
    $entry = (object) [
      "id"              => $this->id,
      "name"            => $this->name,
      "amount_in_stock" => $this->amount,
    ];

    return $entry;
  }

}

?>