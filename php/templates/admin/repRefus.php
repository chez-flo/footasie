<?php
	$sSQL = "select rep_id, rep_mat_id, rep_cre_id, rep_eq_id_dem, rep_eq_id_rec " .
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
	
	$query4 = "update ".TBL_REPORT." set rep_reponse = '-1', rep_rep_d = now() where rep_id = '".htmlentities($_GET['id'])."'" ;
	$result4 = $mysqli->query($query4) ;

	envoiMailReportRefus($rep_mat_id, $rep_cre_id, $rep_eq_id_dem, $refus) ;
	header('Location: index.php?op=eq&id='.$_SESSION['eq_id']) ;
	exit();
	
?>
