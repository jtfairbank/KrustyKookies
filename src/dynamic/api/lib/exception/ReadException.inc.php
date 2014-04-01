<?php

/* Exception: Read
 * ==========================================================================
 * A runtime exception that occurs when an object cannot be read from the
 * database.
 */
class ReadException extends RuntimeException {

  /* Constructor
   * ------------------------------------------------------ */
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }

}

?>