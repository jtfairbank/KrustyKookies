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
    return "" . $this->id . $this->customer . $this->deliveryDate . $this->items;
  }

  /* Constructors
   * ------------------------------------------------------ */
  public function __construct($id, $customer, $deliveryDate, $items) {
    // precondition: $customer must be a Customer
    if (!($customer instanceof Customer)) {
      throw new InvalidArgumentException("Order constructor: the $customer arguement must be of type Customer.");
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

  public static function ToEntry() {
    $entry = (object) [
      "id"            => $this->id,
      "customer_id"   => $this->customer->id,
      "delivery_date" => $this->deliveryDate,
    ];

    return $entry;
  }

}

?>