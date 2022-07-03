<?php
	extract($_GET) ;
	if(!isset($idJ)) {
		$idJ = $_SESSION["id"] ;
	}
	if($status=="-1" || $status=="0" || $status=="1") {
		$sSQL = "UPDATE " . TBL_EJM . " SET ejm_valid='".$status."' WHERE ejm_mat_id = '" . $id . "' and ejm_jou_id = '" . $idJ . "' and ejm_eq_id = '" . $_SESSION["eq_id"] . "'; " ;
		$ret = $mysqli->query($sSQL);
	}
	if(isset($opp)) {
		redirect("index.php?op=".$opp."&id=".$id) ;
	}
	else {
		redirect("index.php") ;
	}
?>