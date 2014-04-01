<?php
require_once('dynamic/template/setup.php');

echo $twig->render("index.twig", array(
  // template variables
  'title' => 'Home',
));
?>

