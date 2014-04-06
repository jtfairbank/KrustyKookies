<?php

/* Order Controller
 * ========================================================================== */
class OrderController implements CrudInterface {

  protected static $table = "order";


  /* Create
   * ------------------------------------------------------ */
  public static function create($pallet) {
    throw new Exception("OrderController: not implemented exception.");
  }


  /* Read
   * ------------------------------------------------------
   * A single SQL statement is used to select all order items for the order,
   * joined with the order itself.  The OrderItems and Order are then created.
   */
  public static function get($id) {
    $db = getDBConn();

    // Get all order items for the specified order.
    $vals = [
      ":orderID" => $id
    ];
    $sql = <<<SQL
      SELECT `order`.*,
             `order_items`.`id` AS `order_item_id`,
             `order_items`.`order_id` AS `order_item_order_id`,
             `order_items`.`recipe_id` AS `order_item_recipe_id`
      FROM `order_items`
      JOIN `order`
        ON `order_items`.`order_id` = `order`.`id`
      WHERE `order_items`.`order_id` = :orderID
SQL;

    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    $orderAndItemEntries = $statement->fetchAll(PDO::FETCH_OBJ);
    $orders = static::coalesceEntries($orderAndItemEntries);

    // postcondition
    if (count($orders) === 0) {
      throw new ReadException("OrderController::get cannot find the Order. id==".$id);
    }

    return $orders[0];
  }

  public static function getAll() {
    $db = getDBConn();

    // Get all order items for all orders.
    $sql = <<<SQL
      SELECT `order`.*,
             `order_items`.`id` AS `order_item_id`,
             `order_items`.`order_id` AS `order_item_order_id`,
             `order_items`.`recipe_id` AS `order_item_recipe_id`
      FROM `order_items`
      JOIN `order`
        ON `order_items`.`order_id` = `order`.`id`
      ORDER BY `order`.`id`
SQL;

    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    $orderAndItemEntries = $statement->fetchAll(PDO::FETCH_OBJ);
    $orders = static::coalesceEntries($orderAndItemEntries);

    return $orders;
  }

  // The orders returned by this only contain a subset of their order items:
  // the order items that aren't in a loading order item (ie are unfulfilled).
  public static function getUnfulfilled() {
    $db = getDBConn();

    // Get all order items (and their orders) that aren't in a loading_order_item.
    $vals = [];
    $sql = <<<SQL
      SELECT `order`.*,
             `order_items`.`id` AS `order_item_id`,
             `order_items`.`order_id` AS `order_item_order_id`,
             `order_items`.`recipe_id` AS `order_item_recipe_id`
      FROM `order_items`
      JOIN `order`
        ON `order_items`.`order_id` = `order`.`id`
      LEFT OUTER JOIN `loading_order_items`
        ON `order_items`.`id` = `loading_order_items`.`order_item_id`
      WHERE `loading_order_items`.`id` IS NULL
SQL;

    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    $orderAndItemEntries = $statement->fetchAll(PDO::FETCH_OBJ);
    $unfulfilledOrders = static::coalesceEntries($orderAndItemEntries);

    return $unfulfilledOrders;
  }

  protected static function coalesceEntries($orderAndItemEntries) {
    $orderEntries = [];
    $orderItems = [];
    foreach ($orderAndItemEntries as $mixedEntry) {
      $key = $mixedEntry->id;
      if (!array_key_exists($key, $orderItems)) {
        array_push($orderEntries, $mixedEntry);
        $orderItems[$key] = [];
      }

      $orderItem = OrderItem::FromEntry($mixedEntry, "order_item_");
      array_push($orderItems[$key], $orderItem);
    }

    $orders = [];
    foreach ($orderEntries as $orderEntry) {
      $key = $orderEntry->id;
      $order = Order::FromEntry($orderEntry, $orderItems[$key]);
      array_push($orders, $order);
    }

    return $orders;
  }


  /* Update
   * ------------------------------------------------------ */
  public static function update($pallet) {
    throw new Exception("OrderController: not implemented exception.");
  }


  /* Delete
   * ------------------------------------------------------ */
  public static function delete($id) {
    throw new Exception("OrderController: not implemented exception.");
  }

}

?>