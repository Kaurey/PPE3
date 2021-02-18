<?php if($_SESSION['statut']!="visiteur"){?>
    <div id="contenu">
      <h2>Management des Fiches de Frais</h2>
         
      <form method="POST"  action="">
      <div class="corpsForm">
          
          <fieldset>
            <legend>Liste Choix Opération</legend>			
          </fieldset>
      </div>

      <div class="piedForm" style="text-align: left;">
      <li><a href="GSB-EbaucheFormulaires/formConsultFrais.php">Consulter les fiches de Frais</a></li>
      <li><a href="GSB-EbaucheFormulaires/formValidFrais.php">Valider les fiches de Frais</a></li>
      </div>
  
      </form>
<?php }?>