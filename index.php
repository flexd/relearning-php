<?php
// All 'heavy' lifting happens in the handler.
include 'config/db.inc';
require 'include/handler.inc';
?>

<?php
include 'views/header.inc';
$h = new Handler(); // This is where the content will appear, place this in the layout!
include 'views/footer.inc';
?>