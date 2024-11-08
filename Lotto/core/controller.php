<?php
require_once 'core/model.php';

class LottoController
{
   private $model;

   public function __construct()
   {
      $this->model = new LottoModel();
   }

   public function GetOtosData()
   {
      return $this->model->getLottoData('data/otos.csv');
   }

   public function GetHatosData()
   {
      return $this->model->getLottoData('data/hatos.csv');
   }

   public function DisplayOtos()
   {
      $data = $this->GetOtosData();
      include 'core/otos_view.php';
   }

   public function DisplayHatos()
   {
      $data = $this->GetHatosData();
      include 'core/hatos_view.php';
   }

   public function DisplayMainStatistics()
   {
      $otos = $this->GetOtosData();
      $hatos = $this->GetHatosData();
      include 'core/index_view.php';
   }
}

// ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ Most drawn numbers ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤

// ¤ ¤ Ötös lottó ¤ ¤
function GetOtosNumbers(array $otos): void
{
   $otosNumbers = [];
   foreach ($otos as $row) {
      foreach (array_slice($row, -5) as $col) {
         array_push($otosNumbers, $col);
      }
   }
   ConvertToShowableData(CountAndSortData($otosNumbers), 5);
}

// ¤ ¤ Hatos lottó ¤ ¤
function GetHatosNumbers(array $hatos): void
{
   $hatosNumbers = [];
   foreach ($hatos as $row) {
      if ($row[0] <= 2007 || $row[0] == 2007 && $row[1] < 30) {
         foreach (array_slice($row, -7) as $col) {
            if ($col !== "") {
               array_push($hatosNumbers, $col);
            }
         }
      } else if ($row[0] <= 2016 || $row[0] == 2016 && $row[1] < 48) {
         foreach (array_slice($row, -7, -2) as $col) {
            array_push($hatosNumbers, $col);
         }
      } else {
         foreach (array_slice($row, -6) as $col) {
            array_push($hatosNumbers, $col);
         }
      }
   }
   ConvertToShowableData(CountAndSortData($hatosNumbers), 6);
}

// ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ Most winning numbers ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤ ¤

// ¤ ¤ Ötös lottó ¤ ¤
function OtosJackpotNumbers(array $otos): void
{
   $otosJackpotNumbers = [];
   foreach ($otos as $row) {
      if ($row[3] > 0) {
         foreach (array_slice($row, -5) as $col) {
            array_push($otosJackpotNumbers, $col);
         }
      }
   }
   ConvertToShowableData(CountAndSortData($otosJackpotNumbers), 10);
}

// ¤ ¤ Hatos lottó ¤ ¤
function HatosJackpotNumbers(array $hatos): void
{
   $hatosJackpotNumbers = [];
   foreach ($hatos as $row) {
      if ($row[3] > 0) {
         if ($row[0] <= 2007 || $row[0] == 2007 && $row[1] < 30) {
            foreach (array_slice($row, -7) as $col) {
               if ($col !== "") {
                  array_push($hatosJackpotNumbers, $col);
               }
            }
         } else if ($row[0] <= 2016 || $row[0] == 2016 && $row[1] < 48) {
            foreach (array_slice($row, -7, -2) as $col) {
               array_push($hatosJackpotNumbers, $col);
            }
         } else {
            foreach (array_slice($row, -6) as $col) {
               array_push($hatosJackpotNumbers, $col);
            }
         }
      }
   }
   ConvertToShowableData(CountAndSortData($hatosJackpotNumbers), 10);
}

// ¤ ¤ ¤ ¤ ¤ Top numbers appeared in the top 10 draws with the most hits  ¤ ¤ ¤ ¤ ¤

// ¤ ¤ Ötös lottó ¤ ¤
function CountOtosDraws(array $otos): void
{
   $countedDraws = [];
   $countedTopDrawedNumbers = [];

   foreach ($otos as $row) {
      $currentNumbers = [];
      $drawsSum = (int)$row[4] + (int)$row[6] + (int)$row[8] + (int)$row[10];
      foreach (array_slice($row, -5) as $col) {
         array_push($currentNumbers, $col);
      }
      array_push($countedDraws, [$drawsSum, $currentNumbers]);
   }

   usort($countedDraws, 'Top10DrawsWithMostHits');

   foreach (array_slice($countedDraws, 0, 10) as $row) {
      foreach ($row[1] as $num) {
         array_push($countedTopDrawedNumbers, $num);
      }
   }

   ConvertToShowableData(CountAndSortData($countedTopDrawedNumbers), 10);
   ShowTop10DrawsWithMostHits(array_slice($countedDraws, 0, 10));
}

// ¤ ¤ Hatos lottó ¤ ¤
function CountHatosDraws(array $hatos): void
{
   $countedDraws = [];
   $countedTopDrawedNumbers = [];

   foreach ($hatos as $row) {

      $currentNumbers = [];
      $drawsSum = (int)$row[3] + (int)$row[5] + (int)$row[7] + (int)$row[9] + (int)$row[11];

      if ($row[0] <= 2007 || ($row[0] == 2007 && $row[1] < 30)) {
         foreach (array_slice($row, -7) as $col) {
            if ($col !== "") {
               array_push($currentNumbers, $col);
            }
         }
      } else if ($row[0] <= 2016 || ($row[0] == 2016 && $row[1] < 48)) {
         foreach (array_slice($row, -7, -2) as $col) {
            array_push($currentNumbers, $col);
         }
      } else {
         foreach (array_slice($row, -6) as $col) {
            array_push($currentNumbers, $col);
         }
      }
      array_push($countedDraws, [$drawsSum, $currentNumbers]);
   }
   usort($countedDraws, 'Top10DrawsWithMostHits');

   foreach (array_slice($countedDraws, 0, 10) as $row) {
      foreach ($row[1] as $num) {
         array_push($countedTopDrawedNumbers, $num);
      }
   }

   ConvertToShowableData(CountAndSortData($countedTopDrawedNumbers), 10);
   ShowTop10DrawsWithMostHits(array_slice($countedDraws, 0, 10));
}

// ¤ ¤ ¤ ¤ ¤ Count and Sort the given numbers in one dimensional array (DESC) ¤ ¤ ¤ ¤ ¤
function CountAndSortData(array $data): array
{
   $countedAndSortedNumbers = array_count_values($data);
   arsort($countedAndSortedNumbers);
   return $countedAndSortedNumbers;
}

// ¤ ¤ ¤ ¤ ¤ Sort the top 10 draws with the most hits in two dimensional array (DESC) ¤ ¤ ¤ ¤ ¤
function Top10DrawsWithMostHits($l, $r): int
{
   return (($l[0] == $r[0]) ? 0 : ($l[0] > $r[0] ? -1 : 1));
}

// ¤ ¤ ¤ ¤ ¤ Convert to showable data ¤ ¤ ¤ ¤ ¤
function ConvertToShowableData(array $countedNumbers, int $top): void
{
   $counter = 0;
   $mostDrawnNums = [];
   foreach ($countedNumbers as $key => $value) {
      if ($counter < $top) {
         array_push($mostDrawnNums, [$key, $value]);
         $counter++;
      } else {
         break;
      }
   }
   ShowResultTable($mostDrawnNums);
}

// ¤ ¤ ¤ ¤ ¤ Show result table ¤ ¤ ¤ ¤ ¤
function ShowResultTable(array $mostDrawnNums)
{
   echo "
   <table class=\"table table-striped table-dark table-hover text-center\">
      <thead>
         <tr>
            <th>Kihúzott szám</th>
            <th>Kihúzások száma</th>
         </tr>
      </thead>
      <tbody>";
   foreach ($mostDrawnNums as $row) {
      echo "";
      echo "<tr>";
      foreach ($row as $col) {
         echo "<td>{$col}</td>";
      }
      echo "</tr>";
   }
   echo "</tbody></table>";
}

function ShowTop10DrawsWithMostHits(array $draws): void
{
   echo "
   <table class=\"table table-striped table-dark table-hover text-center\">
      <thead>
         <tr>
            <th>Találatok száma</th>
            <th>Kihúzott számok</th>
         </tr>
      </thead>
      <tbody>";
   foreach ($draws as $row) {
      echo "";
      echo "<tr>";
      echo "<td>{$row[0]}</td>";
      echo "<td>";
      foreach ($row[1] as $col) {
         echo "{$col} ";
      }
      echo "</td>";
      echo "</tr>";
   }
   echo "</tbody></table>";
}
