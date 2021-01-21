<div style="text-align: center;">
<input type="button" value="imprimer" onclick="imprimer();">
</div>
<script type="text/javascript">     
function imprimer() {    
	var imprimer = document.getElementById('imprimer');
	var popupcontenu = window.open('', '_blank');
	popupcontenu.document.open();
	popupcontenu.document.write('<html><body onload="window.print()">' + imprimer.innerHTML + '</html>');
	popupcontenu.document.close();
}
</script>
<div id="imprimer">
  <h3>Fiche de frais du mois <?php echo $numMois."-".$numAnnee?> : <?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
    </h3>
    <div class="encadre">
      <style type="text/css">@media print{.encadre{text-align: center;}}</style>
    <p>
        Etat : <?php echo $libEtat?> depuis le <?php echo $dateModif?> <br> Montant validé : <?php echo $montantValide?>             
    </p>
    <div id="espace" class="espasce">
  	<table class="listeLegere">
            <style type="text/css">@media print{.listeLegere{text-align: center;margin: 0 auto;border: 1px solid black;border-spacing: 35px 1rem;};}</style>
  	   <caption>Eléments forfaitisés </caption>
    <th>Frais forfaitaire</th>
    <th>Quantité</th>
    <th>Montant  unitaire</th>
    <th>Total</th>
		</tr>
        <tr>
        <?php
        $sommeFinal1=0;
// nom forfaitaire ligne tableau
          foreach (  $lesFraisForfait as $unFraisForfait  ) 
		  {
				$libelle = $unFraisForfait['libelle'];
        $prix = $unFraisForfait['montant'];
        $quantite = $unFraisForfait['quantite'];
        $somme=$prix*$quantite;
        $sommeFinal1+=$somme;
		?>
                <tr class="qteForfait">
                  <td><?php echo $libelle?></td>
                  <td><?php echo $quantite?></td>
                  <td><?php echo $prix?></td> 
                  <td><?php echo $somme?></td>
              </tr>
		 <?php
          }
		?>
		</tr>
    </table>

<?//--------------------------------MODIFIER SEULEMENT AU DESSUS-------------------------------------------------------------------------------------------------------//?>

  	<table class="listeLegere">
  	   <p>Descriptif des éléments hors forfait - <?php echo $nbJustificatifs ?> justificatifs reçus -
       </p>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>                
             </tr>
        <?php     
        $sommeFinal2=0; 
          foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
		  {
			$date = $unFraisHorsForfait['date'];
			$libelle = $unFraisHorsForfait['libelle'];
			$montant = $unFraisHorsForfait['montant'];
      $sommeFinal2+=$montant;
		?>
             <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td><?//montant en bas sous date//?>
                <td><?php echo $montant ?></td>
             </tr> 
        <?php 
          }
		?>
    <th>Total Final</th>
    <td><?php echo $sommeFinal2+$sommeFinal1 ?></td>
    </table>
    <p style="text-align: right;margin-right:10%">Signature</p>
    </div>
  </div>
    </div>
  </div>
</div>













