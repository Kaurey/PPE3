    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
    
</h2>
    
      </div>  
        <ul id="menuList">
			<li >
				  Visiteur :
          <br>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']?>
          <br>
          <b style="text-decoration: underline;">Statut : <?php echo $_SESSION['statut']?></b>			
      </li><hr>
          <?php if($_SESSION['statut']!="visiteur"){?>
          
            <li class="smenu">
              <h3><a href="index.php?uc=etatFrais&action=validationFrais" title="Valider FicheFrais Client">Opération Comptable</a></h3>
           </li>
           <?php }?>
          
          <?php if($_SESSION['statut']!="comptable"){?>
           <li class="smenu">
              <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
           </li>
           <hr>
           <li class="smenu">
              <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
            <?php }?> 
                <hr>
              <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>
        
    </div>
    