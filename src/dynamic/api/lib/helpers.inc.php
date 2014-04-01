<?php

/* Database Helpers
 * ================================================================================ */

function getDBConn() {
  global $settings;

  // precondition: if we have a cached connection then return that
  if (array_key_exists('db', $settings)) {
    return $settings['db'];
  }

  try {
      $db = new PDO(
          $settings['db_url']
        , $settings['db_user']
        , $settings['db_pass']
      );

      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $settings['db'] = $db;

      return $db;

  } catch (PDOException $e) {
      // DB connection failed. 500 us out of here.
      header("HTTP/1.1 500 Internal Server Error");
      echo("Database connection error: " . $e);
      die();
  }
}


/* MVC Helpers
 * ================================================================================ */

/* Chooses an appropriate object for the request.
 *
 * The object that is returned is expected to be a CRUDObject or its descendant.
 *
 * A note on style: group common cases together by using switch/case's cascade
 * feature to reduce clode duplication.
 */
function chooseController($type) {
  $controller = null;

  switch($type) {
    case "Pallet":
      $controller = "PalletController";
      break;

    case "RawIngredient":
      $controller = "RawIngredientController";
      break;

    case "Recipe":
      $controller = "RecipeController";
      break;

    default:
      throw new Exception("Invalid Parameter: chooseController doesn't know how to handle object type $type.");
      break;
  }

  return $controller;
}

?>