<?php

/* CRUD Interface
 * ================================================================================
 * Functions that define CRUD API actions for controllers.  Create and update
 * functions should return the result in case server side changes to the
 * object are made (ie fields are set by the DB).
 *
 * Error conditions, such as an invalid model or deleting an object with
 * referencers, should cause an exception to be thrown.  Let the calling code
 * worry about handling those errors, don't try and be nice.
 */
interface CrudInterface {

  /* Create
   * ------------------------------------------------------------
   * A summary of the create process:
   *
   *  1. Convert the input to a database entry.
   *  2. Insert the entry into the database.
   *  3. Call a create function for any related objects (if necessary).
   *  4. Create references (if necessary).
   *  5. Return the newly created entry in model form.
   */
  public static function create($model);

  /* Read
   * ------------------------------------------------------------
   * A summary of the read process:
   *
   *  1. Get the database entry(s).  Optimizations can occur here by pulling
   *     in related data (ie using joins to link other objects), as long as the
   *     model's FromEntry function is updated to reflect these altered inputs.
   *  2. Pass them to the model's FromEntry function.  This function will handle
   *     any additional conversions or data gathering to transition from entry to
   *     model.  See the `ModelInterface` for more details.
   *  3. Performing any additional checks (ie check references / dependencies)
   *     (if necessary).
   */
  public static function get($id);
  public static function getAll();

  /* Update
   * ------------------------------------------------------------
   * A summary of the update process:
   *
   *  1. Convert the input to a database entry.
   *  2. Update the entry in the database.
   *  3. Call an update function for any related objects (if necessary).
   *  4. Add / remove references by diffing the old and current (if necessary).
   *  5. Return the updated entry in model form.
   */
  public static function update($model);

  /* Delete
   * ------------------------------------------------------------
   * A summary of the delete process:
   *
   *  0. Check references for safe delete (if necessary).
   *  1. Delete the entry in the database.
   *  2. Call a delete function for any related objects (if necessary).
   *  3. Remove references (if necessary).
   */
  public static function delete($id);

}

?>