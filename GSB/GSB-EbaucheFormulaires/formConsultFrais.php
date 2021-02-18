<?php
session_start();
/*Lignes de commande connection a la base de donnée */
try{
$bdd = new PDO('mysql:host=localhost;dbname=gsbV2.2','root','');
}
catch (Exception $e)
{
die('Erreur : ' . $e->getMessage());
}
//Permet de mieux voir les erreurs
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<?php
if(empty($_SESSION['nom']) && (empty($_SESSION['prenom']))){
	echo "Veuillez vous connecter d'abord sur l'intranet: "."<a href='../index.php'>se connecter</a>";
}else{
	echo "Bienvenu [".$_SESSION['statut']."] Mr ".$_SESSION['nom'].' '.$_SESSION['prenom'];
?>
<html>
<head>
	<title>Suivi des frais de visite</title>
	<style type="text/css">
		<!-- body {background-color: white; color:5599EE; } 
			.titre { width : 180 ;  clear:left; float:left; } 
			.zone { float : left; color:7091BB } -->
	</style>
</head>
<body>
<div name="gauche" style="clear:left:;float:left;width:18%; background-color:white; height:100%;">
<div name="coin" style="height:10%;text-align:center;"><img src="logo.jpg" width="100" height="60"/></div>
<div name="menu" >
	<h2>Outils</h2>
	<li><a href="../index.php?uc=etatFrais&action=validationFrais">Retour accueil</a></li>
	<ul><li>Frais</li>
		<ul>
			<li><a href="formValidFrais.php" >Valider Fiche Frais</a></li>
			
		</ul>
	</ul>
</div>
</div>
<div name="droite" style="float:left;width:80%;">
	<div name="haut" style="margin: 2 2 2 2 ;float:left;"><h1>Suivi de remboursement des Frais</h1></div>	
	<div name="bas" style="margin : 10 2 2 2;clear:left;background-color:77AADD;color:white;">
<form method="post">
		<h2 style="text-decoration: underline;">1) Liste des Visiteurs</h2>
		 	<?php
$option_per="SELECT * FROM `visiteur` where statut='visiteur'";
try{
    $stmt_per=$bdd->prepare($option_per);
    $stmt_per->execute();
    $results_per=$stmt_per->fetchAll();
}
catch(Exception $ex)
{
    echo($ex->getMessage());
}
?>
	<select name="q" id="id">
    <option>Recherche Des Visiteurs</option>
    <?php foreach($results_per as $output_per){?>
    <option value="<?php echo $output_per["id"];?>"><?php echo "ID:[".$output_per["id"]."] ".$output_per["prenom"].' '.$output_per['nom']?></optiton>
    <?php } ?>
    </select>
    <input type="hidden" id="select" name="select" value=""></input>
    <br>
		<h2 style="text-decoration: underline;">2) Période </h2>
			<label class="titre">Mois/Année :</label><br>
			<input type="month" id="start" name="trip-start " value="">
			<br>
   			<input type="hidden" id="date" name="date" value="" placeholder="La date conforme pour Bdd">
   			<input type="hidden" id="fullDate" name="fullDate" value="">
   			<input type="submit" value="Afficher Information" onclick="getValue()">
			<script type="text/javascript">

			   function getValue() {
			   	var r = confirm("Appuyer Sur OK Pour Confirmer");
			   	if (r == true) {
			    var date = document.getElementById("start").value;
			    var dateHorsForfait =date;
			    var visiteur = document.getElementById("id").value;
			    document.getElementById("date").value=date.replace('-','');
				document.getElementById("select").value=visiteur;
				document.getElementById("fullDate").value=dateHorsForfait;
				}else{}
				}
			</script>
		<p class="titre"/>
		<div style="clear:left;"><h2 style="text-decoration: underline;">3) Frais au forfait </h2></div>
		<p>Quantité x Prix = Resultat</p>
		<table style="color:white;" border="1">
				<tr><th>Repas midi</th><th>Nuitée </th><th>Etape</th><th>Km</th><th>Situation</th><th>Date opération</th><th>Remboursement</th></tr>
			<tr align="center">
			<?php
				if(isset($_POST['date']) && isset($_POST['select'])){
	$requete=$bdd->query("SELECT montant,quantite,lignefraisforfait.mois FROM `fraisforfait` LEFT JOIN `lignefraisforfait` ON `lignefraisforfait`.`idFraisForfait` = `fraisforfait`.`id` WHERE mois ='".$_POST['date']."' && idVisiteur='".$_POST['select']."'");
	$plus=$bdd->query("SELECT `etat`.`libelle`, fichefrais.dateModif as date,etat.id as etat,`nbJustificatifs` FROM `etat` LEFT JOIN `fichefrais` ON `fichefrais`.`idEtat` = `etat`.`id` WHERE fichefrais.idVisiteur='".$_POST['select']."' && mois='".$_POST['date']."'");
	$horsFrais=$bdd->query("SELECT lignefraishorsforfait.date,lignefraishorsforfait.libelle,lignefraishorsforfait.montant,fichefrais.idEtat,fichefrais.dateModif,`nbJustificatifs` FROM `fichefrais` LEFT JOIN `lignefraishorsforfait` ON `lignefraishorsforfait`.`idVisiteur` = `fichefrais`.`idVisiteur` WHERE fichefrais.idVisiteur='".$_POST['select']."' && fichefrais.mois='".$_POST['date']."' && lignefraishorsforfait.date LIKE '".$_POST['fullDate']."-%'");

	while($bdd=$requete->fetch()){
				$somme=$bdd['quantite']*$bdd["montant"];
				echo "<td>".$bdd['quantite']." x ".$bdd['montant']."<br>"."= ".$somme."€"."</td>";
		}
	while ($bdd=$plus->fetch()){	
		$nombreJusti=$bdd['nbJustificatifs'];
		echo "<td>".$bdd['libelle']."</td>";
		echo "<td>".$bdd['date']."</td>";
		echo "<td>".$bdd['libelle']."</td>";
	}
	?>
</tr>
</table>
<div style="text-align: center;">
<h2>I) Nombre de Justificatif Des "Frais Forfait"</h2>
<input type="text" size="4" name="hcMontant" value="<?php if(isset($nombreJusti)){echo $nombreJusti;}?>" style="text-align: center;"></input>
</div>
		<p class="titre" /><div style="clear:left;"><h2 style="text-decoration: underline;">4) Hors Forfait</h2></div>
		<table style="color:white;" border="1">
			<tr><th>Date</th><th>Libellé </th><th>Montant</th><th>Situation</th><th>Date opération</th><th>Nombre de Justificatif</th></tr>
			<?php
			while ($bdd=$horsFrais->fetch()) {
				$nombreJustiHorsFrais=$bdd['nbJustificatifs'];
				?>
			<tr align="center">
				<td width="100" ><label size="12" name="hfDate1"/><?php echo $bdd['date']?></td>
				<td width="220"><label size="30" name="hfLib1"/><?php echo $bdd['libelle']?></td>
				<td width="100" ><label size="12" name="hfDate1"/><?php echo $bdd['montant']?></td>
				<td width="220" id="etat"><label size="30" name="hfLib1"/><?php echo $bdd['idEtat']?></td>
				<td width="220"><label size="30" name="hfLib1"/><?php echo $bdd['dateModif']?></td>
				<td width="220"><label size="30" name="hfLib1"/><?php echo $bdd['nbJustificatifs']?></td>
			<?php
			}
			?>
			</tr>
		</table>
		<div style="text-align: center">
		<h2>II) Nombre de Justificatif Des "Hors Forfait"</h2>
		<input type="text" style="text-align: center;" size="4" value="<?php if(isset($nombreJustiHorsFrais)){echo $nombreJustiHorsFrais;}?>">	
		</div>
		<p class="titre"></p>
		<div style="color: white;text-decoration: underline;text-align: right;margin-right:5px ">
			<h2>Nb Total Justificatifs
			<input type="text" size="4" name="hcMontant" value="<?php if(isset($nombreJusti)&& isset($nombreJustiHorsFrais)){echo $nombreJusti+$nombreJustiHorsFrais;}?>"></input>
			</h2>
		</div>
	</form>
	<?php
	}
	?>
	</div>
</div>
<?php }?>
</body>
</html>