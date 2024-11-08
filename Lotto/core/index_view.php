<div class="container mt-4">
   <h2 class="text-center mb-4">Lottó Statisztikák</h2>
   <div>
      <h3>Legtöbbet kihúzott 6 szám</h3>
      <div class="row text-center">
         <div class="col-6">
            <h4 class="m-3">Ötös lottó</h4>
            <?php
            GetOtosNumbers($otos);
            ?>
         </div>
         <div class="col-6">
            <h4 class="m-3">Hatos lottó</h4>
            <?php
            GetHatosNumbers($hatos);
            ?>
         </div>
      </div>
   </div>
   <div>
      <h3>Számok amikkel a legtöbbször jött ki telitalálat</h3>
      <div class="row text-center">
         <div class="col-6">
            <h4 class="m-3">Ötös lottó</h4>
            <?php
            OtosJackpotNumbers($otos);
            ?>
         </div>
         <div class="col-6">
            <h4 class="m-3">Hatos lottó</h4>
            <?php
            HatosJackpotNumbers($hatos);
            ?>
         </div>
      </div>
   </div>
   <div>
      <h3>Adjuk meg azokat a számokat növekvő sorrendben előfordulások szerint, amik a top 10 legtöbb találatot tartalmazó húzásban megjelentek.</h3>
      <div class="row text-center">
         <div class="col-6">
            <h4 class="m-3">Ötös lottó</h4>
            <?php
            CountOtosDraws($otos);
            ?>
         </div>
         <div class="col-6">
            <h4 class="m-3">Hatos lottó</h4>
            <?php
            CountHatosDraws($hatos);
            ?>
         </div>
      </div>
   </div>
</div>