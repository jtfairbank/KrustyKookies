<?php

/* Order
 * ========================================================================== */

// TODO: reconcile this with the model interface- `FromEntry` is an issue
// class Order implements ModelInterface {
class Order {

  public $id;
  public $customer;
  public $deliveryDate;
  public $items;

  public function __toString() {
    $itemsString = "";
    foreach ($this->items as $item) {
      $itemsString .= $item;
    }

    return "" . $this->id . $this->customer . $this->deliveryDate . $itemsString;
  }

  /* Constructors
   * ------------------------------------------------------ */
  public function __construct($id, $customer, $deliveryDate, $items) {
    // precondition
    if (!($customer instanceof Customer)) {
      throw new InvalidArgumentException("Order constructor: the $customer arguement must be of type Customer.");
    }

    // precondition
    foreach ($items in $item) {
      if (!($item instanceof OrderItem)) {
        throw new InvalidArgumentException("Order constructor: all $items must be of type OrderItem.");
      }
    }

    $this->id = $id;
    $this->customer = $customer;
    $this->deliveryDate = $deliveryDate;
    $this->items = $items;
  }

  /* Converters
   * ------------------------------------------------------------
   * See the `ModelInterface`.
   */
  public static function FromData($data) {
    return new Order(
      $data->id,
      $data->customer,
      $data->deliveryDate,
      $data->items
    );
  }

  public static function FromEntry($entry, $items) {
    return new Order(
      $entry->id,
      CustomerController::get($entry->customer_id),
      $entry->delivery_date,
      $items
    );
  }

  public function ToEntry() {
    $entry = (object) [
      "id"            => $this->id,
      "customer_id"   => $this->customer->id,
      "delivery_date" => $this->deliveryDate,
    ];

    return $entry;
  }

}

?>