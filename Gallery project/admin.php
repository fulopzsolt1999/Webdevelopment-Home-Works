<?php
define("GALLFOLDER", "galleries");

if (isset($_POST["ok"])) {
   if (isset($_POST["galName"]) && trim($_POST["galName"]) != "") {
      $galName = htmlspecialchars($_POST["galName"]);
      mkdir(GALLFOLDER . "/" . $galName, 644);
      if (file_exists(GALLFOLDER . "/" . $galName) /* && fileperms(GALLFOLDER . "/" . $galName) == 644 */) {
         $result["success"] = true;
         $result["info"] = "Az új galéria létrejött!";
      } else {
         $result["success"] = false;
         $result["info"] = "Az új galéria létrehozása sikertelen!";
      }
   } else {
      $result["success"] = false;
      $result["info"] = "Hibás galéria név!";
   }
}

if (isset($_POST["upload"])) {
   if (isset($_POST["gallery"]) && file_exists(GALLFOLDER . "/" . $_POST["gallery"]) && is_dir(GALLFOLDER . "/" . $_POST["gallery"])) {
      $gallery = htmlspecialchars($_POST["gallery"]);
      if (isset($_FILES["images"])) {
         if (count($_FILES["images"]["tmp_name"]) > 0) {
            for ($i = 0; $i < count($_FILES["images"]["tmp_name"]); $i++) {
               if ($_FILES["images"]["error"][$i] == 0) {
                  $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES["images"]["tmp_name"][$i]);
                  if (in_array($mime, ["image/jpeg", "image/png"])) {
                     if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], GALLFOLDER . "/" . $gallery . "/" . basename($_FILES["images"]["name"][$i]))) {
                        $result["files"][$i]["success"] = true;
                        $result["files"][$i]["info"] = "A(z) " . basename($_FILES["images"]["name"][$i]) . " kép feltöltése sikeres!";
                     } else {
                        $result["files"][$i]["success"] = false;
                        $result["files"][$i]["info"] = "A(z) " . basename($_FILES["images"]["name"][$i]) . " kép feltöltése meghiúsult!";
                     }
                  } else {
                     $result["files"][$i]["success"] = false;
                     $result["files"][$i]["info"] = "A(z) " . basename($_FILES["images"]["name"][$i]) . " fájl típusa nem megfelelő!";
                  }
               } else {
                  $result["files"][$i]["success"] = false;
                  $result["files"][$i]["info"] = "A(z) " . basename($_FILES["images"]["name"][$i]) . " fájl feltöltése sikertelen!";
               }
            }
            $result["success"] = true;
            $result["info"] = "A képek feltöltése lezárult!";
         } else {
            $result["success"] = false;
            $result["info"] = "Nincs feltöltendő kép!";
         }
      } else {
         $result["success"] = false;
         $result["info"] = "Nem érkezett fájl a hívás során!";
      }
   } else {
      $result["success"] = false;
      $result["info"] = "Nem létezik a galéria!";
   }
}

// Galériák begyűjtése
$dir = opendir(GALLFOLDER);
$galleries = array();
if ($dir !== false) {
   while (($gal = readdir($dir)) !== false) {
      if ($gal != "." && $gal != ".." && is_dir(GALLFOLDER . "/" . $gal)) {
         $galleries[] = $gal;
      }
   }
   closedir($dir);
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <title></title>
</head>

<body>
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand ms-3" href="#">Gallery</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
         <div class="navbar-nav">
            <a class="nav-item nav-link active" href="index.php">Főoldal</a>
            <a class="nav-item nav-link" href="admin.php">Adminisztráció</a>
         </div>
      </div>
   </nav>
   <?php
   if (isset($result)) {
      echo "<p class=\"" . ($result["success"] ? "success" : "error") . "\">" . $result["info"] . "</p>";
      if ($result["success"] && isset($result["files"]) && is_iterable($result["files"])) {
         echo "<p>A feltöltés részletei:<ul>";
         foreach ($result["files"] as $finfo) {
            echo "<li class=\"" . ($finfo["success"] ? "success" : "error") . "\">" . $finfo["info"] . "</li>";
         }
         echo "</ul></p>";
      }
   }
   ?>
   <div class="container align-items-center mt-5">
      <div class="row d-flex justify-content-around">
         <div class="col-6">
            <h2 class="mb-3 text-center">Galéria létrehozása</h2>
            <form class="form-inline" method="post">
               <div class="form-group mb-2 text-center">
                  <label for="galName">Galéria neve:</label>
                  <input type="text" class="form-control w-50 m-auto my-3" name="galName" id="galName" placeholder="Galéria neve">
               </div>
               <input type="submit" class="btn btn-primary d-flex mx-auto" name="ok" value="Létrehozás">
            </form>
         </div>
         <div class="col-6">
            <h2 class="mb-3 text-center">Kép feltöltése</h2>
            <form method="post" enctype="multipart/form-data">
               <div class="form-group text-center">
                  <label for="gallery">Galéria, ahova a képek feltöltésre kerülnek:</label>
                  <select name="gallery" class="form-control w-50 m-auto my-3">
                     <?php
                     foreach ($galleries as $gall) {
                        echo "<option value=\"{$gall}\">{$gall}</option>";
                     }
                     ?>
                  </select>
               </div>
               <div class="form-group text-center mb-3">
                  <label for="images">Képek:</label>
                  <input type="file" class="form-control-file" name="images[]" id="images" multiple accept="image/jpeg, image/png">
               </div>
               <input type="submit" class="btn btn-primary d-flex mx-auto" name="upload" value="Feltöltés">
            </form>
         </div>
      </div>
   </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>