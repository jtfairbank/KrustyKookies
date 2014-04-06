<?php

/* Pallet Controller
 * ================================================================================ */
class PalletController implements CrudInterface {

  protected static $table = "pallets";


  /* Create
   * ------------------------------------------------------ */
  public static function create($pallet) {
    // TODO: race conition if multiple pallets are created at once
    //       use transactions, and rollback if RawMaterials::debit throws an error
    try {
      RawMaterialController::debit($pallet->recipe);

      $id = CrudController::create(static::$table, $pallet->ToEntry());
      $createdPallet = static::get($id);
    } catch(RangeException $e) {
      throw $e;
    }

    return $createdPallet;
  }


  /* Read
   * ------------------------------------------------------------ */
  public static function get($id) {
    $pallet = null;

    $entry = CrudController::get(static::$table, $id);
    if ($entry) {
      $pallet = Pallet::FromEntry($entry);
    } else {
      throw new ReadException("PalletController::get cannot find the Pallet. id==".$id);
    }

    return $pallet;
  }

  public static function getAll() {
    $pallets = [];

    $entries = CrudController::getAll(static::$table);
    foreach ($entries as $entry) {
      array_push($pallets, Pallet::FromEntry($entry));
    }

    return $pallets;
  }

  public static function getFree() {
    // get db info
    $db = getDBConn();

    // build sql statement
    $vals = [];
    $sql = <<<SQL
      SELECT *
      FROM `pallets`
      LEFT OUTER JOIN `loading_order_items`
        ON `pallets`.`id` = `loading_order_items`.`pallet_id`
      WHERE `pallets`.`blocked` == 0
        AND `loading_order_items`.`id` IS NULL
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the models
    $palletEntries = $statement->fetchAll(PDO::FETCH_OBJ);
    $pallets = static::palletEntriesToPallets($palletEntries);

    return $pallets;
  }

  public static function getAllInRange($start, $end) {
    // get db info
    $db = getDBConn();

    // build sql statement
    $vals = [
      ":start" => $start,
      ":end" => $end
    ];
    $sql = <<<SQL
      SELECT *
      FROM `pallets`
      WHERE DATE(`produced_on`) BETWEEN DATE(:start) AND DATE(:end)
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the models
    $palletEntries = $statement->fetchAll(PDO::FETCH_OBJ);
    $pallets = static::palletEntriesToPallets($palletEntries);

    return $pallets;
  }

  public static function getAllByRecipe($recipeID) {
    // get db info
    $db = getDBConn();
    $table = static::$table;

    // build sql statement
    $vals = [
      ":recipeID" => $recipeID
    ];
    $sql = <<<SQL
      SELECT *
      FROM `$table`
      WHERE `recipe_id` = :recipeID
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the models
    $palletEntries = $statement->fetchAll(PDO::FETCH_OBJ);
    $pallets = static::palletEntriesToPallets($palletEntries);

    return $pallets;
  }

  public static function getAllByCustomer($customer) {
    // get db info
    $db = getDBConn();

    // build sql statement
    $vals = [
      ":customerID" => $customer->id
    ];
    $sql = <<<SQL
      SELECT `pallets`.*
      FROM `pallets`
      JOIN `loading_order_items`
        ON `pallets`.`id` = `loading_order_items`.`pallet_id`
      JOIN `order_items`
        ON `loading_order_items`.`order_item_id` = `order_items`.`id`
      JOIN `order`
        ON `order_items`.`order_id` = `order`.`id`
      JOIN `customers`
        ON `order`.`customer_id` = `customers`.`id`
      WHERE `customers`.`id` = :customerID
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the models
    $palletEntries = $statement->fetchAll(PDO::FETCH_OBJ);
    $pallets = static::palletEntriesToPallets($palletEntries);

    return $pallets;
  }

  protected static function palletEntriesToPallets($entries) {
    $pallets = [];

    foreach ($entries as $entry) {
      $pallet = Pallet::FromEntry($entry);
      array_push($pallets, $pallet);
    }

    return $pallets;
  }


  /* Update
   * ------------------------------------------------------ */
  public static function update($pallet) {
    throw new Exception("PalletController: not implemented exception.");
  }

  public static function blockAllInRange($recipeID, $start, $end) {
    $blockedPallets = [];

    $pallets = static::getAllInRange($start, $end);
    foreach ($pallets as &$pallet) {
      if ($pallet->recipe->id === $recipeID) {
        $pallet->blocked = true;
        CrudController::update(static::$table, $pallet->ToEntry());
        array_push($blockedPallets, $pallet);
      }
    }

    return $blockedPallets;
  }


  /* Delete
   * ------------------------------------------------------ */
  public static function delete($id) {
    throw new Exception("PalletController: not implemented exception.");
  }

}

?>