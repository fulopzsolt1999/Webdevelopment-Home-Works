<div class="container mt-4">
   <h2 class="text-center mb-4">Hatos Lottó Statisztika</h2>
   <table class="table table-striped table-dark table-hover text-center">
      <thead>
         <tr>
            <th>Év</th>
            <th>Hét</th>
            <th>Húzás dátuma</th>
            <th>6 találat (db)</th>
            <th>6 találat (Ft)</th>
            <th>5+1 találat (db)</th>
            <th>5+1 találat (Ft)</th>
            <th>5 találat (db)</th>
            <th>5 találat (Ft)</th>
            <th>4 találat (db)</th>
            <th>4 találat (Ft)</th>
            <th>3 találat (db)</th>
            <th>3 találat (Ft)</th>
            <th colspan="6">Nyerő számok</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($data as $row): ?>
            <tr>
               <?php foreach ($row as $col): ?>
                  <td><?php echo $col; ?></td>
               <?php endforeach; ?>
            </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>