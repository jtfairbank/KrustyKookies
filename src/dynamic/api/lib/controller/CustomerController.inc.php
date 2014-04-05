<?php

/* Customer Controller
 * ================================================================================ */
class CustomerController implements CrudInterface {

  protected static $table = "customers";


  /* Create
   * ------------------------------------------------------ */
  public static function create($customer) {
    throw new Exception("CustomerController: not implemented exception.");
  }


  /* Read
   * ------------------------------------------------------------ */
  public static function get($id) {
    $customer = null;

    $entry = CrudController::get(static::$table, $id);
    if ($entry) {
      $customer = Customer::FromEntry($entry);
    } else {
      throw new ReadException("CustomerController::get cannot find the Customer. id==".$id);
    }

    return $customer;
  }

  public static function getAll() {
    $customers = [];

    $entries = CrudController::getAll(static::$table);
    foreach ($entries as $entry) {
      array_push($customers, Customer::FromEntry($entry));
    }

    return $customers;
  }


  /* Update
   * ------------------------------------------------------ */
  public static function update($customer) {
    throw new Exception("CustomerController: not implemented exception.");
  }


  /* Delete
   * ------------------------------------------------------ */
  public static function delete($id) {
    throw new Exception("CustomerController: not implemented exception.");
  }

}

?>