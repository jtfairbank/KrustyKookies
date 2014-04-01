<?php

/* Loading Order
 * ========================================================================== */

// TODO: reconcile this with the model interface- `FromEntry` is an issue
// class LoadingOrder extends ModelInterface {
class LoadingOrder {

  public $id;
  public $truckID;
  public $items;

  public function __toString() {
    return "" . $this->id . $this->truckID . $this->items;
  }

  /* Constructor
   * ------------------------------------------------------ */
  public function __construct($id, $truckID, $items) {
    $this->id = $id;
    $this->truckID = $truckID;
    $this->items = $items;
  }

  /* Converters
   * ------------------------------------------------------------
   * See the `ModelInterface`.
   */
  public static function FromData($data) {
    return new LoadingOrder(
      $data->id,
      $data->truckID,
      $data->items
    );
  }

  public static function FromEntry($entry, $itemEntries) {
    $items = [];
    foreach ($itemEntries as $itemEntry) {
      array_push($items, new LoadingOrderItem($itemEntry));
    }

    return new LoadingOrder(
      $entry->id,
      $entry->truckID,
      $items
    );
  }

  public function ToEntry() {
    $entry = (object) [
      "id"       => $this->id,
      "truck_id" => $this->truckID,
    ];

    return $entry;
  }

}

?>