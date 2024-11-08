<?php
class LottoModel
{
   public function getLottoData($filename)
   {
      $data = [];
      if (file_exists($filename) && is_readable($filename)) {
         $file = fopen($filename, "r");
         while (($row = fgetcsv($file, 1000, ";")) !== FALSE) {
            $data[] = $row;
         }
         fclose($file);
      }
      return $data;
   }
}
