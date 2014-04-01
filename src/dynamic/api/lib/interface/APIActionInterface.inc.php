<?php

/* API Action Interface
 * ================================================================================
 * Defines a layer to translate between http requests/responses and the
 * controller.
 */
interface APIActionInterface {

  /* **Take Action**
   *
   * This function fulfills an API request.  It should take the necessary action,
   * and create a success or error RestResponse based on the results.
   *
   * Assume that the `$request's` data is appropriate for each action: a model
   * for create, id for get, etc.
   *
   * **Inputs**
   *
   *   * `request` - The API request to fulfill.
   *
   * **Returns**
   *
   * A `RestResponse` object used to send the response.
   */
  public static function takeAction($request);

}

?>