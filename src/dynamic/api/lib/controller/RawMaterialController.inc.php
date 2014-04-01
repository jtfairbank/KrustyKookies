<?php

/* RawMaterial Controller
 * ================================================================================ */
class RawMaterialController implements CrudInterface {

  protected static $table = "raw_materials";

  /* Create
   * ------------------------------------------------------ */
  public static function create($ingredient) {
    throw new Exception("RawIngredientController: not implemented exception.");
  }


  /* Read
   * ------------------------------------------------------------ */
  public static function get($id) {
    $material = null;

    $entry = CrudController::get(static::$table, $id);
    if ($entry) {
      $material = RawMaterial::FromEntry($entry);
    } else {
      throw new ReadException("RawMaterialController::get cannot find the RawMaterial. id==".$id);
    }

    return $material;
  }

  public static function getAll() {
    $materials = [];

    $entries = CrudController::getAll(static::$table);
    foreach ($entries as $entry) {
      array_push($materials, RawMaterial::FromEntry($entry));
    }

    return $materials;
  }

  public static function canDebit($recipe) {
    $canDebit = true;

    foreach ($recipe->ingredients as $ingredient) {
      // get again incase the amount has changed since the recipe was requested by the web client
      // instead of getting it straight from $recipe->ingredients->rawMaterial
      $material = static::get($ingredient->rawMaterial->id);
      $leftover = $material->amount - $ingredient->amount;
      if ($leftover < 0) {
        $canDebit = false;
      }
    }

    return $canDebit;
  }


  /* Update
   * ------------------------------------------------------ */
  public static function update($ingredient) {
    throw new Exception("RawIngredientController: not implemented exception.");
  }

  public static function debit($recipe) {
    if (static::canDebit($recipe)) {
      $debitedMaterials = [];

      foreach ($recipe->ingredients as $ingredient) {
        // get again incase the amount has changed since the recipe was requested by the web client
        // instead of getting it straight from $recipe->ingredients->rawMaterial
        $material = static::get($ingredient->rawMaterial->id);
        $material->amount -= $ingredient->amount;
        CrudController::update(static::$table, $material->toEntry());

        array_push($debitedMaterials, $material);
      }
    } else {
      throw new RangeException("RawIngredientController::debit cannot reduce a raw material below an amount of 0.");
    }
  }


  /* Delete
   * ------------------------------------------------------ */
  public static function delete($id) {
    throw new Exception("RawIngredientController: not implemented exception.");
  }

}

?>