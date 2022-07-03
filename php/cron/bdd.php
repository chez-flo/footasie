<?php 
	define ('DB_HOST', 'asiesophihasie.mysql.db'); 
	define ('DB_DATABASE', 'asiesophihasie'); 
	define ('DB_USER', 'asiesophihasie'); 
	define ('DB_PASSWORD', '7Siesophia'); 
	
	define ('TBL_COMMENTAIRE', 'f_commentaires');
	define ('TBL_CORRESP', 'f_correspondant');
	define ('TBL_CRENEAU', 'f_creneau');
	define ('TBL_DOCUMENT', 'f_document');
	define ('TBL_DROIT', 'f_droit_user');
	define ('TBL_EQUIPE', 'f_equipe');
	define ('TBL_EQUIPE_CORRESP', 'f_equipe_correspondant');
	define ('TBL_EJT', 'f_equipe_joueur_type');
	define ('TBL_EJM', 'f_equipe_joueur_match');
	define ('TBL_EJM_TMP', 'f_equipe_joueur_match_tmp');
	define ('TBL_EPS', 'f_equipe_poule_saison');
	define ('TBL_EVENEMENT', 'f_evenement');
	define ('TBL_INSCRIPTION', 'f_inscription');
	define ('TBL_JOUEUR', 'f_joueur');
	define ('TBL_MATCH', 'f_match');
	define ('TBL_PARM', 'f_parm');
	define ('TBL_PENALITE', 'f_penalite');
	define ('TBL_POULE', 'f_poule');
	define ('TBL_REPORT', 'f_report');
	define ('TBL_SAISON', 'f_saison');
	define ('TBL_SCORE', 'f_score');
	define ('TBL_SOCIETE', 'f_societe');
	define ('TBL_TERRAIN', 'f_terrain');
	define ('TBL_TYPE', 'f_type');
	define ('TBL_USER', 'f_user');
	
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die("Impossible de se connecter au serveur \"$host\"");
	//mysqli_select_db(DB_DATABASE) or die("Impossible de se connecter à la base de donnees \"$bdd\"");
	$mysqli->set_charset("utf8");
	
	$sSQL = "SELECT parm_valeur, CONCAT(parm_valeur, '/', parm_valeur+1) nom " ;
	$sSQL .= " FROM " . TBL_PARM  ;
	$sSQL .= " WHERE parm_id = '1' ;" ;
	$result = $mysqli->query($sSQL) ;
	while($row = mysqli_fetch_array($result)) {
		extract($row) ;
		define ('SAISON', $parm_valeur);
		define ('NOM_SAISON', $nom);
	}
	$sSQL = "SELECT parm_valeur " ;
	$sSQL .= " FROM " . TBL_PARM  ;
	$sSQL .= " WHERE parm_id = '2' ;" ;
	$result = $mysqli->query($sSQL) ;
	while($row = mysqli_fetch_array($result)) {
		extract($row) ;
		define ('ADR_MAIL', $parm_valeur);
	}
	$sSQL = "SELECT parm_valeur, CONCAT(parm_valeur, '/', parm_valeur+1) nom " ;
	$sSQL .= " FROM " . TBL_PARM  ;
	$sSQL .= " WHERE parm_id = '3' ;" ;
	$result = $mysqli->query($sSQL) ;
	while($row = mysqli_fetch_array($result)) {
		extract($row) ;
		define ('SAISON_INS', $parm_valeur);
		define ('NOM_SAISON_INS', $nom);
	}

?>