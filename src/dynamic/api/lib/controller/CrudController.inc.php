<?php

/* CRUD Controller
 * ==========================================================================
 * TODO: failure conditions for create, update, delete.
 */

class CrudController {

  /* Create
   * ------------------------------------------------------------
   */
  public static function create($table, $entry) {
    // get db info
    $db = getDBConn();

    // build sql statement
    $vals = [];
    $sql = "";
    $fields = "";
    $pdoMarkers = "";

    $first = true;
    foreach ($entry as $key => $value) {
      if ($key == "id") {
        continue;
      }

      if (!$first) {
        $fields .= ", ";
        $pdoMarkers .= ", ";
      } else {
        $first = false;
      }

      $fields .= "`$key`";
      $pdoMarkers .= ":$key";
      $vals[":$key"] = $value;
    }

    $sql = <<<SQL
      INSERT INTO `$table`
      ($fields)
      VALUES ($pdoMarkers)
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR  => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the created ID
    return $db->lastInsertId();
  }


  /* Read
   * ------------------------------------------------------------
   */
  public static function get($table, $id) {
    // get db info
    $db = getDBConn();

    // build sql statement
    $vals = [":id" => $id];
    $sql = <<<SQL
      SELECT *
      FROM `$table`
      WHERE id=:id
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the entry
    return $statement->fetch(PDO::FETCH_OBJ);
  }

  public static function getAll($table) {
    // get db info
    $db = getDBConn();

    // build sql statement
    $vals = [];
    $sql = <<<SQL
      SELECT *
      FROM `$table`
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);

    // return the models
    return $statement->fetchAll(PDO::FETCH_OBJ);
  }


  /* Update
   * ------------------------------------------------------------
   */
  public static function update($table, $entry) {
    // get db info
    $db = getDBConn();

    // build sql statement
    $vals = [":id" => $entry->id];
    $sql = "";
    $setters = "";

    $first = true;
    foreach ($entry as $key => $value) {
      if ($key == "id") {
        continue;
      }

      if (!$first) {
        $setters .= ", ";
      } else {
        $first = false;
      }

      $setters .= "`$key`=:$key";
      $vals[":$key"] = $value;
    }

    $sql = <<<SQL
      UPDATE `$table`
      SET $setters
      WHERE id=:id
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);
  }

  /* Delete
   * ------------------------------------------------------------
   */
  public static function delete($table, $id) {
    // get db info
    $db = getDBConn();

    // build sql statement
    $vals = [":id" => $id];
    $sql = <<<SQL
      DELETE
      FROM `$table`
      WHERE id=:id
SQL;

    // execute sql
    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute($vals);
  }

}

?>