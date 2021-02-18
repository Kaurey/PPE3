<?php
session_start();
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Validation des frais de visite</title>
	<style type="text/css">
			body {background-color: white; color:EE8855; } 
			.titre { width : 180 ;  clear:left; float:left; } 
			.zone { float : left; color:CC8855 }
			td {text-align: center;}
			input{text-align: center;}
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
		<li><a href="formConsultFrais.php">Consulter</a></li>
		</ul>
	</ul>
</div>
</div>
<div name="droite" style="float:left;width:80%;">
	<div name="haut" style="margin: 2 2 2 2 ;height:10%;float:left;"><h1>Validation des Frais</h1></div>	
	<div name="bas" style="margin : 10 2 2 2;clear:left;background-color:EE8844;color:white;">
	<form method="post">
		<h1> Validation des frais par visiteur </h1>
		<label class="titre">Choisir le visiteur :</label>
		<br>
<form method="post">
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
			<label class="titre">Mois/Annee :</label><br>
			<input type="month" id="start" name="trip-start " value="">
			<br>
   			<input type="hidden" id="date" name="date" value="" placeholder="La date conforme pour Bdd">
   			<input type="hidden" id="fullDate" name="fullDate" value="">
   			<input type="hidden" id="fulldate" name="fulldate" value="">
   			<div style="width: 60%;text-align: center;">
   			<input type="submit" value="Afficher Information" onclick="getValue()">
			</div>
			<script type="text/javascript">
			   function getValue() {
			   	var r = confirm("Appuyer Sur OK Pour Confirmer");
			   	if (r == true) {
			   	var now = new Date();
			   	var jour = now.getDate();
			    var date = document.getElementById("start").value;
			    var dateHorsForfait =date;
			    var visiteur = document.getElementById("id").value;
			    document.getElementById("date").value=date.replace('-','');
				document.getElementById("select").value=visiteur;
				document.getElementById("fullDate").value=dateHorsForfait;
				document.getElementById("fulldate").value=dateHorsForfait+"-"+jour;
				}else{}
				}
			</script>
			<br>
		<p class="titre" />
		<div style="clear:left;"><h2><?php echo 'Fiche Visiteur :'.$output_per['prenom'].'-'.$output_per['nom'].'<br>';?>Frais au forfait </h2></div>
		<table style="color:white;" border="1">
			<tr><th>Repas midi</th><th>Nuitee </th><th>Etape</th><th>Km</th><th>Montant</th></tr>
			<?php
			if(isset($_POST['date']) && isset($_POST['select'])){
			$frais = $bdd->query("SELECT * FROM `lignefraisforfait` JOIN fraisforfait on lignefraisforfait.idFraisForfait = fraisforfait.id WHERE `mois` = '".$_POST['date']."' && `idVisiteur`='".$_POST['select']."'");
			$test = $bdd->query("SELECT SUM(montant) as 'total' FROM `lignefraisforfait` JOIN fraisforfait on lignefraisforfait.idFraisForfait = fraisforfait.id WHERE `mois` = '".$_POST['date']."' && `idVisiteur`='".$_POST['select']."'");
			$horsfrais = $bdd->query("SELECT * FROM `lignefraishorsforfait` WHERE `idVisiteur` ='".$_POST['select']."' && mois='".$_POST['date']."'");
			$horsClass=$bdd->query("SELECT * FROM `fichefrais` WHERE `mois`='".$_POST['date']."' && `idVisiteur`='".$_POST['select']."'");
			$situation = $bdd->query("SELECT idEtat from fichefrais WHERE `mois`='".$_POST['date']."' && `idVisiteur`='".$_POST['select']."'");
			$etat=$bdd->query("SELECT * from etat");

			while ($bdd=$frais->fetch()) {
				echo '<td>'.$bdd['quantite'].'</td>';
			}
			while ($bdd=$test->fetch()) {
				echo '<td>'.$bdd['total'].'</td>';
				$totalFrais=$bdd['total'];
			}
			}
			?>
			<tr align="center">
		</table>
		<p class="titre" /><div style="clear:left;"><h2>Hors Forfait</h2></div>
		<table style="color:white;" border="1">
			<tr><th>Date</th><th>Libelle </th><th>Montant</th></tr>
			<tr align="center">
				<?php
				if(isset($_POST['date']) && isset($_POST['select'])){
				$totalhorsfrais = 0;
				while ($bdd=$horsfrais->fetch()) {
					echo '<td>'.$bdd['mois'].'</td>';
					echo '<td>'.$bdd['libelle'].'</td>';
					echo '<td>'.$bdd['montant'].'</td>'.'<tr>';
					$totalhorsfrais = $bdd['montant']+$totalhorsfrais;
				}
			}
				?>
		</table>		
		<p class="titre"><div style="clear:left;"><h2>Hors Classification</h2></div></p>
				<table style="color:white;" border="1">
			<tr><th>Nb Justificatifs</th><th>Montant Total</th><th>Situation</th></tr>
			<tr align="center">
				<?php
				if(isset($_POST['date']) && isset($_POST['select'])){
				while ($bdd=$horsClass->fetch()) {
					$Total= $totalhorsfrais+$totalFrais;
					echo '<td>'.'<input value='.$bdd['nbJustificatifs'].'>'.'</>'.'</td>';
					echo '<td>'.'<input value='.$Total.'>'.'</>'.'</td>';
					$_POST['nbJustificatifs']=$bdd['nbJustificatifs'];
					$_POST['Total'] = $Total;
				}
				while ($bdd=$situation->fetch()) {
					echo '<td>'.$bdd['idEtat'].'</td>';
				}
				?>
			</tr>
		</table>
</form>
<?php
}
?>
<h2 style="text-decoration: underline;">Veuillez Corrigiez si nessecaire</h2>
	<div style="padding-left: 35%">
	
		<?php
	if (isset($_POST['update']))
	{
		$nbJustificatifs= $_POST['nbJustificatifs'];
		$montantValide= $_POST['montantValide'];
		$dateModif= $_POST['dateModif'];
		$idEtat= $_POST['idEtat'];
		$idVisiteur= $_POST['idVisiteur'];
		$mois= $_POST['mois'];

 $sql = "UPDATE fichefrais SET nbJustificatifs=:nbJustificatifs,montantValide=:montantValide,dateModif=:dateModif,idEtat=:idEtat WHERE idVisiteur=:idVisiteur && mois=:mois";

  $stmt = $bdd->prepare($sql);
  $stmt_exec = $stmt->execute(array(
  	":nbJustificatifs"=>$nbJustificatifs,
  	":montantValide" => $montantValide,
  	":dateModif" => $dateModif,
  	":idEtat"=> $idEtat,
	":idVisiteur"=> $idVisiteur,
	":mois"=> $mois
  ));

  if ($stmt_exec)
  {
  echo '<script>alert("Data Mise a jour")</script>';
  }
  else
  {
  	  echo '<script>alert("Request Fail")</script>';
  }
  }
?>

<form method="POST">
	<div style="padding-bottom: 25%">
	<label>ID Visiteur:</label><br><input type="text" name="idVisiteur" value="<?php if(isset($_POST['select'])){ echo $_POST['select'];}?>" placeholder="idvisiteur"><br>

	<label>Date selon le Mois(annee_mois):</label><br><input type="text" name="mois" value="<?php if(isset($_POST['date'])){echo $_POST['date'];}?>" placeholder="mois"><br>

	<label>Nb Justificatif(s):</label><br><input type="text" name="nbJustificatifs" value="<?php if(isset($_POST['nbJustificatifs'])){ echo $_POST['nbJustificatifs'];}?>" placeholder="justi"><br>

	<label>Montant Total du Mois:</label><br><input type="text" name="montantValide" value="<?php if(isset($Total)){echo $Total;}?>"  placeholder="montantValide"><br>

	<label>Date enregistrement(aujourd'hui par defaut):</label><br><input type="text" name="dateModif" value="<?php if(isset($_POST['fulldate'])){echo $_POST['fulldate'];}?>"  placeholder="dateModif"><br>

	<label>ID Etat:</label><br><input type="text" name="idEtat" placeholder="idEtat"><br>
		<select>
		<?php
			while ($bdd=$etat->fetch()) {
			echo '<option value='.$bdd['id'].'>'.'ID:['.$bdd['id'].'] '.$bdd['libelle'].'</option>';
				}
			}?>
		</select>
		<input type="submit" name="update">
		</div>
	</div>
</div>
</div>
</body>
</html>