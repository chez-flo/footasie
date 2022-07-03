<?php
	extract($_GET) ;
	$sSQL = "DELETE FROM " . TBL_COMMENTAIRE . " WHERE com_id = '" . $com_id . "' and com_jou_id = '" . $_SESSION["id"] . "' and com_eq_id = '" . $_SESSION["eq_id"] . "' and com_mat_id = '" . $id . "'; " ;
	$ret = $mysqli->query($sSQL);
	if(isset($opp)) {
		redirect("index.php?op=".$opp."&id=".$id) ;
	}
	else {
		redirect("index.php") ;
	}
?>