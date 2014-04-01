<?php

/* Pallet Controller
 * ================================================================================ */
class PalletController implements CrudInterface, APIActionInterface {

  protected static $table = "pallets";


  /* Perform an Action
   * ------------------------------------------------------------ */
  public static function takeAction($request) {
    $response = null;

    $action = $request->getAction();
    switch($action) {
      case "create":
        $pallet = Pallet::FromData($request->getData());

        try {
          $createdPallet = static::create($pallet);
          $response = new RestResponse(201, $createdPallet, 'text/json');

        } catch(RangeException $e) {
          $response = new RestResponse(409, null, 'text/plain');
        }

        break;

      case "get":
        $id = $request->getData();

        try {
          $pallet = static::get($id);
          $response = new RestResponse(200, $pallet, 'text/json');

        } catch(ReadException $e) {
          $response = new RestResponse(404, null, 'text/plain');
        }

        break;

      case "getAll":
        $pallets = static::getAll();

        if (count($pallets) > 0) {
          $response = new RestResponse(200, $pallets, 'text/json');
        } else {
          $response = new RestResponse(404, [], 'text/json');
        }

        break;

      case "getFree":
        $pallets = static::getFree();

        if (count($pallets) > 0) {
          $response = new RestResponse(200, $pallets, 'text/json');
        } else {
          $response = new RestResponse(404, [], 'text/json');
        }

        break;

      case "getAllInRange":
        $start = $request->getData()['start'];
        $end = $request->getData()['end'];
        $pallets = static::getAllInRange($start, $end);

        if (count($pallets) > 0) {
          $response = new RestResponse(200, $pallets, 'text/json');
        } else {
          $response = new RestResponse(404, [], 'text/json');
        }

        break;

      case "getAllByRecipe":
        $recipeID = $request->getData();
        $pallets = static::getAllByRecipe($recipeID);

        if (count($pallets) > 0) {
          $response = new RestResponse(200, $pallets, 'text/json');
        } else {
          $response = new RestResponse(404, [], 'text/json');
        }

        break;

      case "getAllByCustomer":
        $customer = $request->getData();
        $pallets = static::getAllByCustomer($customer);

        if (count($pallets) > 0) {
          $response = new RestResponse(200, $pallets, 'text/json');
        } else {
          $response = new RestResponse(404, [], 'text/json');
        }

        break;

      case "blockAllInRange":
        $recipe = Pallet::FromData($request->getData()['recipe']);
        $start = $request->getData()['start'];
        $end = $request->getData()['end'];
        $updatedPallets = static::block($recipe, $start, $end);
        $response = new RestResponse(200, $updatedPallets, 'text/json');

        break;

      default:
        throw new Exception("Invalid Parameter: PalletController->takeAction doesn't know how to handle action $action.");
        break;
    }

    return $response;
  }


  /* Create
   * ------------------------------------------------------ */
  public static function create($pallet) {
    // TODO: race conition if multiple pallets are created at once
    //       use transactions, and rollback if RawMaterials::debit throws an error
    try {
      RawIngredientController::debit($pallet->recipe);

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
    $pallets = [];

    $entries = CrudController::getAll(static::$table);
    foreach ($entries as $entry) {
      if (!$entry->blocked) {
        array_push($pallets, Pallet::FromEntry($entry));
      }
    }

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
      FROM `{static::$table}`
      WHERE DATE(`produced_on`) BETWEEN DATE(:start) AND DATE(:end)
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the models
    return $statement->fetchAll(PDO::FETCH_OBJ);
  }

  public static function getAllByRecipe($recipeID) {
    // get db info
    $db = getDBConn();

    // build sql statement
    $vals = [
      ":recipeID" => $recipeID
    ];
    $sql = <<<SQL
      SELECT *
      FROM `{static::$table}`
      WHERE `recipe_id` = :recipeID
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the models
    return $statement->fetchAll(PDO::FETCH_OBJ);
  }

  public static function getAllByCustomer($customer) {
    // get db info
    $db = getDBConn();
    $table = static::$table;

    // build sql statement
    $vals = [
      ":customerID" => $customer->id
    ];
    $sql = <<<SQL
      SELECT `pallets`.`*`
      FROM `$table`
      JOIN `loading_order_items`
        ON `$table`.`id` = `loading_order_items`.`pallet_id`
      JOIN `order_items`
        ON `loading_order_items`.`order_item_id` = `order_items`.`id`
      JOIN `order`
        ON `order_items`.`order_id` = `order`.`id`
      JOIN `customers`
        ON `order`.`customer_id` = `customers`.`id`
      WHERE `customer` = :customerID
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the models
    return $statement->fetchAll(PDO::FETCH_OBJ);
  }


  /* Update
   * ------------------------------------------------------ */
  public static function update($pallet) {
    throw new Exception("PalletController: not implemented exception.");
  }

  public static function blockAllInRange($recipe, $start, $end) {
    $blockedPallets = [];

    $pallets = static::getAllInRange($recipe, $start, $end);
    foreach ($pallets as &$pallet) {
      if ($pallet->recipe->id === $recipe->id) {
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