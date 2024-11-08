<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
   <title>Lottó statisztika</title>
</head>

<body>
   <?php
   include 'navbar.php';
   require_once 'core/controller.php';
   $controller = new LottoController();
   $controller->DisplayMainStatistics();
   ?>
   <div class="container"></div>
   <script src="static/js/script.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>