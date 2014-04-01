<?php

/* Model Interface
 * ================================================================================
 * Functions that define converting between data passed to the server,
 * db entries, and models.
 *
 * Note that 'this type' refers the the type defined by the model implementing
 * this interface.
 */
interface ModelInterface {

  /* Converters
   * ------------------------------------------------------------
   *
   *  * data  -> model
   *  * entry -> model
   *  * data  -> model -> entry == data -> entry
   *  * model -> entry
   *
   * The model can act as data, so there is no need for a `ToData` converter.
   * See the `FromData` method's comments for more info on the relationship
   * between Data and Models.
   */

  /* **From Data**
   *
   * Convert from request data into the object model.
   *
   * The object data is just an anonymous object of the model's fields, so there
   * is no need to explicitly convert a model into it- a model can just act as
   * data.  The reverse is not true, however, because PHP doesn't have any object
   * casting, so a model must be explicitly created from data.
   */
  public static function FromData($data);

  /* **From Entry**
   *
   * Convert from a database entry into the object model.
   *
   * The CrudController relies on models to handle gathering necessary resources
   * for Read operations by calling the FromEntry function.  Here, the model is
   * responsible for converting data, looking up objects (ie from a referenced id)
   * and handling its references.
   */
  public static function FromEntry($entry);

  /* **To Entry**
   *
   * Convert from the object model into a database entry (anonymous object).
   */
  public function ToEntry();
}

?>