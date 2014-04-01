<?php

/* Load All the Server Scripts
 * ================================================================================
 * Self executing anonymous functions are used to prevent global scope pollution.
 */

/* Helpers
 * ------------------------------------------------------------ */
// Based on ulogin as well, although the author seems to have taken it from
// somewhere else, possibly http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions.
// Need to call it `endsWith2` cause `endsWith` is defined by ulogin.
function endsWith2($haystack, $needle) {
  $length = strlen($needle);
  if ($length == 0) {
    return true;
  }

  return (substr($haystack, -$length) === $needle);
}

/* Load the RS Library
 * ------------------------------------------------------------
 * Recursively traverse and load all php include files in `lib`.
 *
 * Based on ulogin's `config/all.inc` file.
 */
$root = dirname(__FILE__);

// an stack, so make sure the first dir is on top (last)
$dirs = [
  $root . "/model",
  $root . "/controller",
  $root . "/exception",
  $root . "/interface",
  $root,
];

while (count($dirs) > 0) {
  $root = array_pop($dirs);
  $handle = opendir($root);
  $file = readdir($handle);

  while ($file != false) {
    $path = "$root/$file";

    if (is_file($path) && endsWith2($file, '.inc.php')) {
      require_once($path);
    }

    $file = readdir($handle);
  }

  closedir($handle);
}

?>
