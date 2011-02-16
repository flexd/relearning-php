<?php
// Very very basic way of handling requests through a index file.
// Not MVC but directs index.php?t=something to controllers/something.php. Whatever happens there is up to you!
class Handler
{
  function __construct()
  {
    // Check if $_GET['t'] is set, default to index.
    if (isset($_GET['t']))
    {
      $t = $_GET['t'];
    }
    else {
      $t = "index";
    }
    $this->$t;
  }
  public function __get($name)
  {
    // Check if file exists, return 404 if it doesn't.
    if (file_exists("controllers/$name.php")) 
    {
      include "controllers/$name.php";
    }
    else
    {
      header("HTTP/1.1 404 Not Found"); // Requested page does not exist.
      exit;
    } 
  } // __get
} // Handler

?>