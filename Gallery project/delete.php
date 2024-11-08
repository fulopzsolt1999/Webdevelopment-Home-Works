<?php
define("GALLFOLDER", "galleries");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $path = GALLFOLDER . "/" . $_POST['path'];
   $type = $_POST['type'];
   if ($type == "img") {
      if (file_exists($path)) {
         unlink($path);
         echo 'success';
      } else {
         echo 'error';
      }
   } else if ($type == "dir") {
      if (is_dir($path)) {
         rmdir($path);
         echo 'success';
      } else {
         echo 'error';
      }
   }
}
