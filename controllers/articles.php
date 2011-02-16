<?php
// TODO: Cache this and make it less horrible!
$dir = opendir('html/');
ob_start();
while(false != ($file = readdir($dir)))
{
  if(($file != ".") and ($file != ".."))
  {
    print file_get_contents('html/'.$file);
  }
}
ob_end_flush();
?>