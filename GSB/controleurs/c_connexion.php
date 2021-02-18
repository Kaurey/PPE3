<?php
if(!isset($_REQUEST['action'])){
$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch($action){
case 'demandeConnexion':{
	include("vues/v_connexion.php");
	break;
}
case 'valideConnexion':{
	$login = $_REQUEST['login'];
	$mdp = $_REQUEST['mdp'];
	if (isset($_SESSION['statut'])) {
		$statut = $_SESSION['statut'];
	}
	$visiteur = $pdo->getInfosVisiteur($login,$mdp,isset($statut));
	
		if(!is_array( $visiteur)){
		ajouterErreur("Login ou mot de passe incorrect");
		include("vues/v_erreurs.php");
		include("vues/v_connexion.php");
	}
	else{
		$id = $visiteur['id'];
		$nom =  $visiteur['nom'];
		$prenom = $visiteur['prenom'];
		$statut = $visiteur['statut'];
		connecter($id,$nom,$prenom,$statut);
		include("vues/v_sommaire.php");
	}
	break;
}
case 'deconnexion':{
	if (isset($_SESSION)) {
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}
		session_destroy();
	}
	include("vues/v_connexion.php");
	break;
}
default :{
	include("vues/v_connexion.php");
	break;
}
}
?>