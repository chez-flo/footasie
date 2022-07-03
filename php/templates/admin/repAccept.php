<?php
	$sSQL = "select rep_id, rep_mat_id, rep_cre_id " .
				" from " . TBL_REPORT . 
				" where rep_eq_id_rec = '".$_SESSION['eq_id']."' " .
					" and rep_reponse = 0 " .
					" and rep_id = '".htmlentities($_GET['id'])."' " ;
	$result = $mysqli->query($sSQL) ;
	if(mysqli_num_rows($result)==0) {
		header('Location: index.php') ;
		exit();
	} 
	while ($row = mysqli_fetch_array($result)) {
		extract($row) ;
	}
	
	$sSQLCreneau = "select cre_id " .
				" from " . TBL_CRENEAU . 
				" where cre_mat_id = '".$rep_mat_id."' " ;
	$resultCreneau = $mysqli->query($sSQLCreneau) ;
	while ($rowCreneau = mysqli_fetch_array($resultCreneau)) {
		extract($rowCreneau) ;
	}
	
	$query1 = "update ".TBL_MATCH." set mat_statut = '2' where mat_id = '".$rep_mat_id."' ;" ;
	$result1 = $mysqli->query($query1) ;
	$query2 = "update ".TBL_CRENEAU." set cre_mat_id = '".SAISON."0000' where cre_id = '".$cre_id."' ;" ;
	$result2 = $mysqli->query($query2) ;
	$query3 = "update ".TBL_CRENEAU." set cre_mat_id = '".$rep_mat_id."' where cre_id = '".$rep_cre_id."' ;" ;
	$result3 = $mysqli->query($query3) ;
	$query4 = "update ".TBL_REPORT." set rep_reponse = '1', rep_rep_d = now() where rep_id = '".htmlentities($_GET['id'])."'" ;
	$result4 = $mysqli->query($query4) ;
	envoiMailReport($rep_mat_id, $cre_id) ;
	header('Location: index.php?op=eq&id='.$_SESSION['eq_id']) ;
	exit();
	
?>
