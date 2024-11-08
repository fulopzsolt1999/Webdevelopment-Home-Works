<?php
define("GALLFOLDER", "galleries");
if (isset($_GET["gall"])) {
   $listGalls = false;
   $gallery = htmlspecialchars($_GET["gall"]);
   if (file_exists(GALLFOLDER . "/" . $gallery) && is_dir(GALLFOLDER . "/" . $gallery)) {
      $images = scandir(GALLFOLDER . "/" . $gallery);
   } else {
      $error = "Ismeretlen galéria!";
   }
} else {
   $listGalls = true;
   $galleries = scandir(GALLFOLDER);
   if (count($galleries) == 0) {
      $error = "Jelenleg egyetlen galéria sem létezik!";
   }
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
   <meta charset="UTF-8">
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
   echo "<h1 class=\"my-5 text-center\">" . ($listGalls ? "Feltöltött galériák" : "$gallery galéria képei") . "</h1>";
   if (isset($error)) {
      echo "<h2 class=\"error\">$error</h2>";
   } else {
      define("DIV", 3);
      echo "<div class=\"grid\">";
      if ($listGalls) {
         foreach ($galleries as $gal) {
            if (!($gal != "." && $gal != ".." && is_dir(GALLFOLDER . "/" . $gal))) {
               continue;
            }
            echo "
            <div class=\"card mx-3\" style=\"width: 18rem;\">
               <a href=\"?gall=$gal\">
                  <img class=\"card-img-top\" src=\"gallery.jpg\">
               </a>
               <div class=\"card-body d-flex-col justify-content-center align-items-center text-center\">
                  <p class=\"card-text\">$gal</p>
                  <button class=\"gallery-delete btn btn-danger\" id=\"$gal\">Törlés</button>
               </div>
            </div>";
         }
      } else {
         foreach ($images as $image) {
            if (!is_file(GALLFOLDER . "/" . $gallery . "/" . $image)) {
               continue;
            }
            $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), GALLFOLDER . "/" . $gallery . "/" . $image);
            if (!in_array($mime, ["image/jpeg", "image/png"])) {
               continue;
            }
            print("<div class=\"item\"><img class=\"image\" data-id=\"" . $gallery . "/" . $image . "\" width=\"256\" src=\"" . (GALLFOLDER . "/" . $gallery . "/" . $image) . "\" style=\"opacity: 0.8;\"></div>");
         }
      }
      echo "</div>";
   }
   ?>
   <script src="//cdnjs.cloudflare.com/ajax/libs/waterfall.js/1.0.2/waterfall.min.js"></script>
   <script>
      waterfall(".grid");
      window.addEventListener('resize', () => {
         waterfall(".grid");
      });
      const allImages = document.querySelectorAll(".image");
      const deleteBtns = document.querySelectorAll(".gallery-delete");
      document.querySelector(".grid").addEventListener("mouseover", (event) => {
         allImages.forEach(image => {
            if (image === event.target) {
               image.style.opacity = 1;
            }
         });
      });
      document.querySelector(".grid").addEventListener("mouseout", () => {
         allImages.forEach(image => {
            image.style.opacity = 0.8;
         });
      });
      allImages.forEach(image => {
         image.addEventListener('click', (event) => {
            const imageId = event.target.getAttribute('data-id');
            const confirmation = confirm("Biztosan törölni szeretnéd ezt a képet?");
            if (confirmation) {
               const formData = new FormData();
               formData.append('type', "img");
               formData.append('path', imageId);
               fetch('delete.php', {
                     method: 'POST',
                     body: formData
                  })
                  .then(response => response.text())
                  .then(text => {
                     if (text === 'success') {
                        window.location.reload();
                     } else {
                        console.error('Error:', text);
                     }
                  });
            }
         });
      });
      deleteBtns.forEach(button => {
         button.addEventListener('click', (event) => {
            const dirPath = event.target.id;
            const confirmation = confirm("Biztosan törölni szeretnéd ezt a galáriaét?");
            if (confirmation) {
               const formData = new FormData();
               formData.append('type', 'dir');
               formData.append('path', dirPath);
               fetch('delete.php', {
                     method: 'POST',
                     body: formData
                  })
                  .then(response => response.text())
                  .then(text => {
                     if (text === 'success') {
                        window.location.reload();
                     } else {
                        console.error('Error:', text);
                     }
                  });
            }
         });
      })
   </script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>