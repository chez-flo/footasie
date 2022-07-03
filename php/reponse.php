<?php
include("conf.php") ;
include("function.php") ;
	switch(htmlentities($_GET['choix'])) {
		case 0 :
			$aff = searchEquipeByPoule($_POST['val_sel']) ;
			break ;
		case 1 :
			$aff = createOptionsEquipes($_POST['val_sel']) ;
			break ;
		case 2 :
			$aff = remplirChampsEquipe($_POST['val_sel']) ;
			break ;
		case 3 :
			$aff = checkSociete($_POST['val_sel']) ;
			break ;
		case 4 :
			$aff = checkEquipe($_POST['val_sel']) ;
			break ;
		case 5 :
			$aff = createOptionsAjoutJoueurs($_POST['val_sel']) ;
			break ;
		default : 
			$aff = "Erreur dans le choix : " . htmlentities($_GET['choix']) ;
			break ;
	}

// envoi reponse Php a Ajax	
	echo $aff;
?>