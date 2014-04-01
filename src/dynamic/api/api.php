<?php

/* Requirements
 * ------------------------------------------------------------ */
require_once("lib/loader.php");


/* Process the Request
 * ================================================================================
 * This function actually responds to the request, processing it and returning a
 * response to the API caller.
 *
 * A self executing anonymous function is used to prevent global scope pollution.
 */
call_user_func(function() {
  $request = RestRequest::GetCurrentRequest();
  $response = $request->fufill();
  $response->send();
});

?>
