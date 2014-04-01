<?php

/* Customer
 * ========================================================================== */
class Customer implements ModelInterface {

  public $id;
  public $name;
  public $address;

  public function __toString() {
    return "" . $this->id . $this->name . $this->address;
  }

  /* Constructors
   * ------------------------------------------------------ */
  public function __construct($id, $name, $address) {
    $this->id = $id;
    $this->name = $name;
    $this->address = $address;
  }

  /* Converters
   * ------------------------------------------------------------
   * See the `ModelInterface`.
   */
  public static function FromData($data) {
    return new Customer(
      $data->id,
      $data->name,
      $data->address
    );
  }

  public static function FromEntry($entry) {
    return new Customer(
      $entry->id,
      $entry->name,
      $entry->address
    );
  }

  public static function ToEntry() {
    $entry = (object) [
      "id"      => $this->id,
      "name"    => $this->name,
      "address" => $this->address,
    ];

    return $entry;
  }

}

?>