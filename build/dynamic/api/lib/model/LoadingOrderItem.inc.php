<?php

/* Loading Order Item
 * ========================================================================== */
class LoadingOrderItem implements ModelInterface {

  public $id;
  public $loadingOrderID;
  public $pallet;

  public function __toString() {
    return "" . $this->id . $this->loadingOrderID . $this->pallet;
  }

  /* Constructors
   * ------------------------------------------------------ */
  public function __construct($id, $loadingOrderID, $pallet) {
    // precondition: $pallet must be a Pallet
    if (!($pallet instanceof Pallet)) {
      throw new InvalidArgumentException("LoadingOrderItem constructor: the $pallet arguement must be of type Pallet.");
    }

    $this->id = $id;
    $this->loadingOrderID = $loadingOrderID;
    $this->pallet = $pallet;
  }

  /* Converters
   * ------------------------------------------------------------
   * See the `ModelInterface`.
   */
  public static function FromData($data) {
    return new Customer(
      $data->id,
      $data->loadingOrderID,
      $data->pallet
    );
  }

  public static function FromEntry($entry) {
    return new Customer(
      $entry->id,
      $entry->loading_order_id,
      PalletController::get($entry->pallet_id)
    );
  }

  public function ToEntry() {
    $entry = (object) [
      "id"               => $this->id,
      "loading_order_id" => $this->loadingOrderID,
      "pallet_id"        => $this->pallet->id,
    ];

    return $entry;
  }

}

?>