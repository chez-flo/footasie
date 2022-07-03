<?php 
	include("auth.php") ;
	include("conf.php") ;
header('Content-type: text/html; charset=UTF-8');
	if(isset($_POST['majScore'])) {
		extract($_POST) ;
		if($forfait==0) {
			if($score1==$score2) {
				$pt1 = 2 ;
				$pt2 = 2 ;
			} elseif($score1>$score2) {
				$pt1 = 4 ;
				$pt2 = 1 ;
				$eqW = $eq1 ;
			} else {
				$pt1 = 1 ;
				$pt2 = 4 ;
				$eqW = $eq2 ;
			}
			$query7 = "delete from ".TBL_PENALITE." where pen_type = 'A' and pen_mat_id = '".$mat_id."'" ;
			$result7 = $mysqli->query($query7) ;
			$query1 = "update ".TBL_MATCH." set mat_statut = '1' where mat_id = '".$mat_id."' ;" ;
			$result1 = $mysqli->query($query1) ;
			if($pou_eve_id==4 && $score1==$score2) {
				$query2 = "update ".TBL_SCORE." set sco_bp = '".$score1."', sco_bc = '".$score2."', sco_points = '".$pt1."', sco_pen = '".$pen1."' where sco_mat_id = '".$mat_id."' and sco_eq_id = '".$eq1."' ;" ;
				$result2 = $mysqli->query($query2) ;
				$query3 = "update ".TBL_SCORE." set sco_bp = '".$score2."', sco_bc = '".$score1."', sco_points = '".$pt2."', sco_pen = '".$pen2."' where sco_mat_id = '".$mat_id."' and sco_eq_id = '".$eq2."' ;" ;
				$result3 = $mysqli->query($query3) ;
				if($pen1>$pen2) {
					$eqW = $eq1 ;
				}
				else {
					$eqW = $eq2 ;
				}
			}
			else {
				$query2 = "update ".TBL_SCORE." set sco_bp = '".$score1."', sco_bc = '".$score2."', sco_points = '".$pt1."', sco_pen = null where sco_mat_id = '".$mat_id."' and sco_eq_id = '".$eq1."' ;" ;
				$result2 = $mysqli->query($query2) ;
				$query3 = "update ".TBL_SCORE." set sco_bp = '".$score2."', sco_bc = '".$score1."', sco_points = '".$pt2."', sco_pen = null where sco_mat_id = '".$mat_id."' and sco_eq_id = '".$eq2."' ;" ;
				$result3 = $mysqli->query($query3) ;
			}
			if($pou_eve_id!=4) {
				if($arb!=1) {
					$query4 = "select count(*) cpt from ".TBL_PENALITE." where pen_eq_id = '".$arbId."' and pen_type = 'A' and pen_mat_id in (select mat_id from ".TBL_MATCH.", ".TBL_POULE." where mat_sai_annee = '".SAISON."' and mat_pou_id = pou_id and pou_eve_id = '".$pou_eve_id."' and mat_id <> '".$mat_id."')" ;
					$result4 = $mysqli->query($query4) ;
					while ($row4 = mysqli_fetch_array($result4)) {
						extract($row4) ;
						if($cpt==0) {
							$nbPen = 1 ;
						} elseif($cpt==1) {
							$nbPen = 4 ;
						} elseif($cpt==2) {
							$nbPen = 5 ;
						} elseif($cpt==3) {
							$nbPen = 5 ;
						}
						$query5 = "insert into ".TBL_PENALITE." (pen_eq_id, pen_pou_id, pen_mat_id, pen_point, pen_type, pen_commentaire, pen_date) values " . 
							"('".$arbId."', (select pou_id from ".TBL_EPS.", ".TBL_POULE." where eps_sai_annee = '".SAISON."' and eps_pou_id = pou_id and pou_eve_id = '".$pou_eve_id."' and eps_eq_id = '".$arbId."'), '".$mat_id."', '".$nbPen."', 'A', 'Défaut Arbitrage', now()) ;" ;
						$result5 = $mysqli->query($query5) ;
					}
				}
			} else {
				if($arb>1) {
					$query5 = "insert into ".TBL_PENALITE." (pen_eq_id, pen_pou_id, pen_mat_id, pen_point, pen_type, pen_commentaire, pen_date) values " . 
						"('".$arb."', '".$pou_id."', '".$mat_id."', '0', 'A', 'Défaut Arbitrage', now()) ;" ;
					$result5 = $mysqli->query($query5) ;
					
				} else if($arb==0) {
					$query4 = "select mat_eq_id_3, mat_eq_id_4 from ".TBL_MATCH." where mat_id '".$mat_id."' ; " ;
					$result4 = $mysqli->query($query4) ;
					while ($row4 = mysqli_fetch_array($result4)) {
						extract($row4) ;
						$query5 = "insert into ".TBL_PENALITE." (pen_eq_id, pen_pou_id, pen_mat_id, pen_point, pen_type, pen_commentaire, pen_date) values " . 
							"('".$mat_eq_id_3."', (select pou_id from ".TBL_EPS.", ".TBL_POULE." where eps_sai_annee = '".SAISON."' and eps_pou_id = pou_id and pou_eve_id = '".$pou_eve_id."' and eps_eq_id = '".$mat_eq_id_3."'), '".$mat_id."', '0', 'A', 'Défaut Arbitrage', now()) ;" ;
						$result5 = $mysqli->query($query5) ;
						$query5 = "insert into ".TBL_PENALITE." (pen_eq_id, pen_pou_id, pen_mat_id, pen_point, pen_type, pen_commentaire, pen_date) values " . 
							"('".$mat_eq_id_4."', (select pou_id from ".TBL_EPS.", ".TBL_POULE." where eps_sai_annee = '".SAISON."' and eps_pou_id = pou_id and pou_eve_id = '".$pou_eve_id."' and eps_eq_id = '".$mat_eq_id_4."'), '".$mat_id."', '0', 'A', 'Défaut Arbitrage', now()) ;" ;
						$result5 = $mysqli->query($query5) ;
					}
				} else {
					$query7 = "delete from ".TBL_PENALITE." where and pen_type = 'A' and pen_mat_id = '".$mat_id."'" ;
					$result7 = $mysqli->query($query7) ;
				}
			}
		}
		else {
			if($forfait==$eq1) {
				$nonForfait=$eq2 ;
				$eqW = $eq2 ;
			} else {
				$nonForfait=$eq1 ;
				$eqW = $eq1 ;
			}
			$query7 = "delete from ".TBL_PENALITE." where pen_eq_id = '".$forfait."' and pen_mat_id = '".$mat_id."'" ;
			$result7 = $mysqli->query($query7) ;
			$query7 = "delete from ".TBL_PENALITE." where pen_eq_id = '".$arbId."' and pen_type = 'A' and pen_mat_id = '".$mat_id."'" ;
			$result7 = $mysqli->query($query7) ;
			$query1 = "update ".TBL_MATCH." set mat_statut = '1' where mat_id = '".$mat_id."' ;" ;
			$result1 = $mysqli->query($query1) ;
			$query2 = "update ".TBL_SCORE." set sco_bp = '3', sco_bc = '0', sco_points = '4', sco_pen = null where sco_mat_id = '".$mat_id."' and sco_eq_id = '".$nonForfait."' ;" ;
			$result2 = $mysqli->query($query2) ;
			$query3 = "update ".TBL_SCORE." set sco_bp = '0', sco_bc = '3', sco_points = '1', sco_pen = null where sco_mat_id = '".$mat_id."' and sco_eq_id = '".$forfait."' ;" ;
			$result3 = $mysqli->query($query3) ;
			$query5 = "insert into ".TBL_PENALITE." (pen_eq_id, pen_pou_id, pen_mat_id, pen_point, pen_type, pen_commentaire, pen_date) values " . 
						"('".$forfait."', '".$pou_id."', '".$mat_id."', '1', 'F', 'Forfait', now()) ;" ;
			$result5 = $mysqli->query($query5) ;
		}
		if($pou_eve_id==4) {
			$query9 = "select sai_pou_id pou_id_sup from ".TBL_SAISON." where sai_annee = '".SAISON."' and sai_pou_id = (".$pou_id."+1)" ;
			//echo $query9 ;
			$result9 = $mysqli->query($query9) ;
			while ($row9 = mysqli_fetch_array($result9)) {
				extract($row9) ;
				$query11 = "delete from ".TBL_EPS." where eps_eq_id in ('".$eq1."', '".$eq2."') and eps_pou_id = '".$pou_id_sup."' and eps_sai_annee = '".SAISON."' " ;
				$result11 = $mysqli->query($query11) ;
				$query10 = "insert into ".TBL_EPS." (eps_eq_id, eps_pou_id, eps_sai_annee) values " . 
					"('".$eqW."', '".$pou_id_sup."', '".SAISON."') ;" ;
				$result10 = $mysqli->query($query10) ;
			}
		}
		if($com_text!="") {
			$query12 = "update ".TBL_MATCH." set mat_commentaire = CONCAT(mat_commentaire, CHAR(13), '".mysqli_real_escape_string($mysqli,$com_text)."') where mat_id = '".$mat_id."' ;" ;
			$result12 = $mysqli->query($query12) ;
		}
		envoiMailScore($mat_id, $score1, $score2, $arb, $forfait, $com_text, $pen1, $pen2) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['envScore'])) {
		extract($_POST) ;
		envoiMailScore($mat_id, $score1, $score2, $arb, $forfait, $com_text, $pen1="", $pen2="") ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['razScore'])) {
		extract($_POST) ;
		$query1 = "update ".TBL_MATCH." set mat_statut = '0' where mat_id = '".$mat_id."' ;" ;
		$result1 = $mysqli->query($query1) ;
		$query2 = "update ".TBL_SCORE." set sco_bp = null, sco_bc = null, sco_pen = null, sco_points = null where sco_mat_id = '".$mat_id."' ;" ;
		$result2 = $mysqli->query($query2) ;
		$query7 = "delete from ".TBL_PENALITE." where pen_type in ('A', 'F') and pen_mat_id = '".$mat_id."'" ;
		$result7 = $mysqli->query($query7) ;
		$query11 = "delete from ".TBL_EPS." where eps_eq_id in ('".$eq1."', '".$eq2."') and eps_pou_id = (".$pou_id."+1) and eps_sai_annee = '".SAISON."' " ;
		$result11 = $mysqli->query($query11) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['annuleMatch'])) {
		extract($_POST) ;
		$query1 = "delete from ".TBL_MATCH." where mat_id = '".$mat_id."' ;" ;
		$result1 = $mysqli->query($query1) ;
		$query2 = "delete from ".TBL_SCORE." where sco_mat_id = '".$mat_id."' ;" ;
		$result2 = $mysqli->query($query2) ;
		$query3 = "update ".TBL_CRENEAU." set cre_mat_id = '".SAISON."0000' where cre_id = '".$cre_id."' ;" ;
		$result3 = $mysqli->query($query3) ;
		envoiMailAnnule($eq1, $eq2, $eqId1, $eqId2, $jour, $ter_nom) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['report'])) {
		extract($_POST) ;
		$query1 = "update ".TBL_MATCH." set mat_statut = '2' where mat_id = '".$mat_id."' ;" ;
		$result1 = $mysqli->query($query1) ;
		$query2 = "update ".TBL_CRENEAU." set cre_mat_id = '".SAISON."0000' where cre_id = '".$cre_id."' ;" ;
		$result2 = $mysqli->query($query2) ;
		$query3 = "update ".TBL_CRENEAU." set cre_mat_id = '".$mat_id."' where cre_id = '".$cre_libre."' ;" ;
		$result3 = $mysqli->query($query3) ;
		if($eq_id>0) {
			$query4 = "delete from ".TBL_PENALITE." where pen_eq_id = '".$eq_id."' and pen_type = 'R' and pen_mat_id = '".$mat_id."'" ;
			$result4 = $mysqli->query($query4) ;
			$query5 = "select count(*) cpt from ".TBL_PENALITE." where pen_type = 'R' and pen_mat_id = '".$mat_id."'" ;
			$result5 = $mysqli->query($query5) ;
			while ($row5 = mysqli_fetch_array($result5)) {
				extract($row5) ;
				if($cpt==0) {
					$query7 = "select count(*) cptPen from ".TBL_PENALITE." where pen_eq_id = '".$eq_id."' and pen_mat_id like '".SAISON."%' and pen_type = 'R'" ;
					$result7 = $mysqli->query($query7) ;
					while ($row7 = mysqli_fetch_array($result7)) {
						extract($row7) ;
						if($cptPen>2) {
							$query6 = "insert into ".TBL_PENALITE." (pen_eq_id, pen_pou_id, pen_mat_id, pen_point, pen_type, pen_commentaire, pen_date) values " . 
									"('".$eq_id."', '".$pou_id."', '".$mat_id."', '1', 'R', '".mysqli_real_escape_string($mysqli,$report_text)."', now()) ;" ;
						} else {
							$query6 = "insert into ".TBL_PENALITE." (pen_eq_id, pen_pou_id, pen_mat_id, pen_point, pen_type, pen_commentaire, pen_date) values " . 
									"('".$eq_id."', '".$pou_id."', '".$mat_id."', '0', 'R', '".mysqli_real_escape_string($mysqli,$report_text)."', now()) ;" ;
						}
						$result6 = $mysqli->query($query6) ;
					}
				}
			}
		}
		envoiMailReport($mat_id, $cre_id) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['demReport'])) {
		extract($_POST) ;
		$query4 = "delete from ".TBL_PENALITE." where pen_eq_id = '".$eq_id."' and pen_type = 'R' and pen_mat_id = '".$mat_id."'" ;
		$result4 = $mysqli->query($query4) ;
		$query5 = "select count(*) cpt from ".TBL_PENALITE." where pen_type = 'R' and pen_mat_id = '".$mat_id."'" ;
		$result5 = $mysqli->query($query5) ;
		while ($row5 = mysqli_fetch_array($result5)) {
			extract($row5) ;
			if($cpt==0) {
				$query7 = "select count(*) cptPen from ".TBL_PENALITE." where pen_eq_id = '".$eq_id."' and pen_mat_id like '".SAISON."%' and pen_type = 'R'" ;
				$result7 = $mysqli->query($query7) ;
				while ($row7 = mysqli_fetch_array($result7)) {
					extract($row7) ;
					if($cptPen>2) {
						$query6 = "insert into ".TBL_PENALITE." (pen_eq_id, pen_pou_id, pen_mat_id, pen_point, pen_type, pen_commentaire, pen_date) values " . 
								"('".$eq_id."', '".$pou_id."', '".$mat_id."', '1', 'R', '".mysqli_real_escape_string($mysqli,$report_text)."', now()) ;" ;
					} else {
						$query6 = "insert into ".TBL_PENALITE." (pen_eq_id, pen_pou_id, pen_mat_id, pen_point, pen_type, pen_commentaire, pen_date) values " . 
								"('".$eq_id."', '".$pou_id."', '".$mat_id."', '0', 'R', '".mysqli_real_escape_string($mysqli,$report_text)."', now()) ;" ;
					}
					$result6 = $mysqli->query($query6) ;
				}
			}
		}
		$query7 = "insert into ".TBL_REPORT." (rep_eq_id_dem, rep_eq_id_rec, rep_mat_id, rep_cre_id, rep_dem_d) values " . 
								"('".$eq_id."', '".$eq_id_rec."', '".$mat_id."', '".$cre_libre."', now()) ;" ;
		$result7 = $mysqli->query($query7) ;
		envoiMailDemReport($mat_id, $eq_id, $cre_libre, $report_text) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['ajoutMatch'])) {
		extract($_POST) ;
		if($eve_id==1) {
			$statut = 1 ;
			$queryArb = "select eq_id from ".TBL_EQUIPE." where eq_nom = 'Amical'" ;
			$resultArb = $mysqli->query($queryArb) ;
			while ($rowArb = mysqli_fetch_array($resultArb)) {
				extract($rowArb) ;
			} 
			$arb = $eq_id ;
		} else {
			$statut = 0 ;
		}
		$query1 = "insert into ".TBL_MATCH." (mat_eq_id_1, mat_eq_id_2, mat_eq_id_3, mat_journee, mat_statut, mat_pou_id, mat_sai_annee) values " . 
					"('".$eq1."', '".$eq2."', '".$arb."', '0', '".$statut."', '".$pou_id."', '".SAISON."') ;" ;
		$result1 = $mysqli->query($query1) ;
		$query2 = "select max(mat_id) mat_id from ".TBL_MATCH." where mat_sai_annee = '".SAISON."'" ;
		$result2 = $mysqli->query($query2) ;
		while ($row2 = mysqli_fetch_array($result2)) {
			extract($row2) ;
		}
		$query3 = "update ".TBL_CRENEAU." set cre_mat_id = '".$mat_id."' where cre_id = '".$cre_id."' ;" ;
		$result3 = $mysqli->query($query3) ;
		$query4 = "insert into ".TBL_SCORE." (sco_mat_id, sco_eq_id) values " . 
					"('".$mat_id."', '".$eq1."') ;" ;
		$result4 = $mysqli->query($query4) ;
		$query5 = "insert into ".TBL_SCORE." (sco_mat_id, sco_eq_id) values " . 
					"('".$mat_id."', '".$eq2."') ;" ;
		$result5 = $mysqli->query($query5) ;
		envoiMailAjout($mat_id) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['modifArb'])) {
		extract($_POST) ;
		$query2 = "select mat_eq_id_3 from ".TBL_MATCH." where mat_id = '".$mat_id."'" ;
		$result2 = $mysqli->query($query2) ;
		while ($row2 = mysqli_fetch_array($result2)) {
			extract($row2) ;
		}
		$query1 = "update ".TBL_MATCH." set mat_statut = '3', mat_eq_id_3 = '".$arb_id."' where mat_id = '".$mat_id."' ;" ;
		$result1 = $mysqli->query($query1) ;
		envoiMailModifArb($mat_id, $mat_eq_id_3) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['demCreneau'])) {
		extract($_POST) ;
		envoiMailDemandeCreneau($cre_id, $eq_id, $nom, $mail, $raison) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['info'])) {
		extract($_POST) ;
		envoiMailInfos($mail) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['relance'])) {
		extract($_POST) ;
		envoiMailRelance($mat_id, $relance_text) ;
		header('Location: index.php?op='.$opp) ;
		exit();
	}
	if(isset($_POST['majJoueur'])) {
		extract($_POST) ;
		$sSQL = "SELECT jou_nom, jou_mail " .
			" FROM " . TBL_JOUEUR . ", " . TBL_EJT .  
			" WHERE jou_id = ejt_jou_id " .
				" AND ejt_eq_id = '" . $_SESSION['eq_id'] . "' " .
				" AND ejt_sai_annee = '" . SAISON . "' " .
				" AND ejt_typ_id = 1 " .
				" AND jou_id = '" . $jou_id . "' " ;
		$result = $mysqli->query($sSQL) ;
		if(mysqli_num_rows($result0)!=0) {
			$sSQLUpdate = "update " . TBL_JOUEUR . " set jou_nom = '" . securite_bdd(ucwords(strtolower(htmlentities(trim(trim($prenom)." ".trim($nom)), ENT_NOQUOTES, "UTF-8")))) . "', jou_mail = '" . securite_bdd(strtolower(htmlentities($mail, ENT_NOQUOTES, "UTF-8"))) . "' WHERE jou_id = '" . $jou_id . "'" ;
			$resultUpdate = $mysqli->query($sSQLUpdate) ;
		}
		header('Location: index.php?op=geq') ;
		exit();
	}
	if(isset($_POST['supprimerJoueur'])) {
		extract($_POST) ;
		$sSQLDelete = "delete from " . TBL_EJT . " where ejt_jou_id = '" . $jou_id . "' and ejt_eq_id = '" . $_SESSION['eq_id'] . "' and ejt_sai_annee = '" . SAISON . "' " ;
		$resultDelete = $mysqli->query($sSQLDelete) ;
		header('Location: index.php?op=geq') ;
		exit();
	}
	if(isset($_POST['addJoueur'])) {
		extract($_POST) ;
		if($type==1) {
			$sSQL = "DELETE FROM " . TBL_EJT . " WHERE ejt_sai_annee = '".SAISON."' and ejt_eq_id = '".$_SESSION['eq_id']."' and ejt_typ_id = '1' ;" ;
			$ret = $mysqli->query($sSQL);
			if(isset($joueurs_ok_1)) {
				for($i=0;$i<sizeof($joueurs_ok_1);$i++) {
					//$sSQLInsert = "insert into " . TBL_EJM . " (ejm_eq_id, ejm_jou_id, ejm_mat_id, ejm_valid) values ('".$_SESSION['eq_id']."', '" . $joueurs_ok_1[$i] . "', '".$mat_id."', '".$valid."')" ;
					//$resultInsert = $mysqli->query($sSQLInsert) ;
					$sSQLInsert2 = "insert into " . TBL_EJT . " (ejt_jou_id, ejt_eq_id, ejt_typ_id, ejt_sai_annee) values ('" . $joueurs_ok_1[$i] . "', '" . $_SESSION['eq_id'] . "', '1', '" . SAISON . "')" ;
					$resultInsert2 = $mysqli->query($sSQLInsert2) ;
/*					if(!$dejaInvite) {
						envoiMailValidEvent($joueurs_ok_1[$i],$_SESSION["utilisateur"],$id) ;
					}
					else {
						envoiMailModifEvent($joueurs_ok_1[$i],$titre,$id) ;
					}*/
				}
			}
		}
		elseif($type==2) {
			if(is_numeric($jou_id_2)) {
				$query0 = "select ejt_jou_id cpt from ".TBL_EJT." where ejt_jou_id = '" . $jou_id_2 . "' and ejt_typ_id = '2' and ejt_sai_annee = '" . SAISON . "'" ;
				$result0 = $mysqli->query($query0) ;
				if(mysqli_num_rows($result0)==0) {
					$sSQLInsert2 = "insert into " . TBL_EJT . " (ejt_jou_id, ejt_eq_id, ejt_typ_id, ejt_sai_annee) values ('" . $jou_id_2 . "', '" . $_SESSION['eq_id'] . "', '2', '" . SAISON . "')" ;
					$resultInsert2 = $mysqli->query($sSQLInsert2) ;
				}
			}
		}
		header('Location: index.php?op=geq') ;
		exit();
	}
	if(isset($_POST['invJoueurs'])) {
		extract($_POST) ;
		$sSQL = "DELETE FROM " . TBL_EJM_TMP . " ; " ;
		$ret = $mysqli->query($sSQL);
		$sSQL = "INSERT INTO ".TBL_EJM_TMP." SELECT * FROM ".TBL_EJM." WHERE ejm_mat_id = '".$mat_id."' and ejm_eq_id = '".$_SESSION['eq_id']."' ;" ;
		$ret = $mysqli->query($sSQL);
		$sSQL = "DELETE FROM " . TBL_EJM . " WHERE ejm_mat_id = '".$mat_id."' and ejm_eq_id = '".$_SESSION['eq_id']."' ;" ;
		$ret = $mysqli->query($sSQL);
		if(isset($invites)) {
			$inv=array() ;
			for($i=0;$i<sizeof($invites);$i++) {
				$invites[$i] = substr($invites[$i],strrpos($invites[$i],"_")+1) ;
			}
			for($i=0;$i<sizeof($invites);$i++) {
				$flag=false ;
				for($j=0;$j<sizeof($inv);$j++) {
					if($invites[$i]==$inv[$j]) {
						$flag=true ;
					}
				}
				if(!$flag) {
					$valid="0" ;
					$dejaInvite=false ;
					$sSQLTemp = "SELECT ejm_jou_id, ejm_valid FROM " . TBL_EJM_TMP . " WHERE ejm_mat_id = '".$mat_id."' and ejm_eq_id = '".$_SESSION['eq_id']."' ;" ;
					$result = $mysqli->query($sSQLTemp) ;
					while ($rowTemp = mysqli_fetch_array($result)) {
						if($invites[$i]==$rowTemp['ejm_jou_id']) {
							$valid = $rowTemp['ejm_valid'] ;
							$dejaInvite=true ;
						}
					}
					$sSQLInsert = "insert into " . TBL_EJM . " (ejm_eq_id, ejm_jou_id, ejm_mat_id, ejm_valid) values ('".$_SESSION['eq_id']."', '" . $invites[$i] . "', '".$mat_id."', '".$valid."')" ;
					$resultInsert = $mysqli->query($sSQLInsert) ;
/*					if(!$dejaInvite) {
						envoiMailValidEvent($invites[$i],$_SESSION["utilisateur"],$id) ;
					}
					else {
						envoiMailModifEvent($invites[$i],$titre,$id) ;
					}
	*/				$inv[sizeof($inv)]=$invites[$i] ;
				}
			}
		}
		$sSQL = "DELETE FROM " . TBL_EJM_TMP . " ; " ;
		// echo $sSQL . "<br/>" ;
		$ret = $mysqli->query($sSQL);
		header('Location: index.php?op=dm&id='.$mat_id) ;
		exit();
	}
	if(isset($_POST['convJoueurs'])) {
		extract($_POST) ;
		if(isset($convoques)) {
			for($i=0;$i<sizeof($convoques);$i++) {
				$convoques[$i] = substr($convoques[$i],strrpos($convoques[$i],"_")+1) ;
				$sSQLTemp = "SELECT ejm_jou_id, ejm_valid FROM " . TBL_EJM . " WHERE ejm_mat_id = '".$mat_id."' and ejm_eq_id = '".$_SESSION['eq_id']."' and ejm_valid='2' ;" ;
				$result = $mysqli->query($sSQLTemp) ;
				$dejaInvite=false ;
				while ($rowTemp = mysqli_fetch_array($result)) {
					if($convoques[$i]==$rowTemp['ejm_jou_id']) {
						$dejaInvite=true ;
					}
				}
				if(!$dejaInvite) {
			//		envoiMailValidEvent($convoques[$i],$_SESSION["utilisateur"],$id) ;
					$sSQL = "UPDATE " . TBL_EJM . " set ejm_valid = '2' where ejm_mat_id = '".$mat_id."' and ejm_eq_id = '".$_SESSION['eq_id']."' and ejm_jou_id = '".$convoques[$i]."' and ejm_valid = '1' ;" ;
					$ret = $mysqli->query($sSQL);
				} /*else {
					envoiMailModifEvent($convoques[$i],$titre,$id) ;
				}*/
			}
		}
		header('Location: index.php?op=dm&id='.$mat_id) ;
		exit();
	}
	if(isset($_POST['chEquipe'])) {
		extract($_POST) ;
		for($i=0; $i<$_SESSION["nb_eq"]; $i++) {
			if($eq_id==$_SESSION["tab_eq_id"][$i]) { break ; }
		}
		if($i<$_SESSION["nb_eq"]) {
			$_SESSION["droit"] = $_SESSION["tab_droit"][$i] ;
			$_SESSION["eq_id"] = $_SESSION["tab_eq_id"][$i] ;
			$_SESSION["validate"] = $_SESSION["tab_validate"][$i] ;
		}
		header('Location: index.php?op=eq&id='.$_SESSION['eq_id']) ;
		exit();
	}
	if(isset($_POST['addIns'])) {
		extract($_POST) ;
		if($soc_id==0) {
			$sSQLSoc = "insert into " . TBL_SOCIETE .  
						" (soc_nom) values ('".securite_bdd($societe)."') ;" ;
			$resultSoc = $mysqli->query($sSQLSoc) ;
			$soc_id = mysqli_insert_id() ;
		} else {
			$sSQLSoc = "select count(*) cptSoc " .
						" from " . TBL_SOCIETE .  
						" where soc_id = '".$soc_id."' ;" ;
			$resultSoc = $mysqli->query($sSQLSoc) ;
			while ($rowSoc = mysqli_fetch_array($resultSoc)) {
				extract($rowSoc) ;
			}
			if($cptSoc==0) {
				header('Location: index.php?op=err&v=ins') ;
				exit();
			}
		}
		$sSQL = "update f_equipe " .
					"set eq_ter_id = '" . securite_bdd($ter_id) ."', " .
					"eq_jour = '" . securite_bdd($jour) ."', " .
					"eq_coupe = '" . securite_bdd($coupe) ."', " .
					"eq_couleur = '" . securite_bdd($couleur) ."', " .
					"eq_couleur_ext = '" . securite_bdd($couleur_ext) ."', " .
					"eq_soc_id = '" . securite_bdd($soc_id) ."', " .
					"eq_commentaire = '" . securite_bdd($commentaire) ."' " .
				"where eq_id = '" . securite_bdd($eq_id) ."'; " ;
		$result = $mysqli->query($sSQL) ;
		$sSQLInsert =  "insert into " . TBL_EPS .  
					" (eps_eq_id, eps_pou_id, eps_sai_annee) values ('".securite_bdd($eq_id)."', '2', '".SAISON_INS."') ;" ;
		$resultInsert = $mysqli->query($sSQLInsert) ;
		if($nom_1!="" && $mail_1 != "") {
			// Vérification du nom déjà existant
			$sSQLVerif = "select jou_id, jou_mail from " . TBL_JOUEUR . " where lower(jou_nom) = lower('" . securite_bdd($nom_1) ."') ;" ;
			$resultVerif = $mysqli->query($sSQLVerif) ;
			if(mysqli_num_rows($resultVerif)>0) {
				while ($rowVerif = mysqli_fetch_array($resultVerif)) {
					extract($rowVerif) ;
					if($jou_mail=="") {
						$mdp1 = chaine_aleatoire(10) ;
						$sSQLUpdate =  "update " . TBL_JOUEUR . " set jou_mail = '" . securite_bdd($mail_1) . "', jou_password = '".md5($mdp1)."' where jou_id = '" . $jou_id . "' ;" ;
						$resultUpdate = $mysqli->query($sSQLUpdate) ;
						envoiMailCreationCapitaine($mail_1, $mdp1, $eq_id) ;
					}
				}
			} else {
				$mdp1 = chaine_aleatoire(10) ;
				$sSQLInsert =  "insert into " . TBL_JOUEUR . 
					" (jou_nom, jou_mail, jou_password, jou_dro_id, jou_nb_conn) values ('".ucwords(strtolower(securite_bdd($nom_1)))."', '".securite_bdd($mail_1)."', '".md5($mdp1)."', '2', '0') ;" ;
				$resultInsert = $mysqli->query($sSQLInsert) ;
				$jou_id = $mysqli->insert_id ;
				envoiMailCreationCapitaine($mail_1, $mdp1, $eq_id) ;
			}
			$sSQLInsert =  "insert into " . TBL_EQUIPE_CORRESP . 
					" (ec_eq_id, ec_jou_id) values ('".securite_bdd($eq_id)."', '".$jou_id."') ;" ;
			$resultInsert = $mysqli->query($sSQLInsert) ;
		}
		if($nom_2!="" && $mail_2 != "") {
			// Vérification du nom déjà existant
			$sSQLVerif = "select jou_id, jou_mail from " . TBL_JOUEUR . " where lower(jou_nom) = lower('" . securite_bdd($nom_2) ."') ;" ;
			$resultVerif = $mysqli->query($sSQLVerif) ;
			if(mysqli_num_rows($resultVerif)>0) {
				while ($rowVerif = mysqli_fetch_array($resultVerif)) {
					extract($rowVerif) ;
					if($jou_mail=="") {
						$mdp2 = chaine_aleatoire(10) ;
						$sSQLUpdate =  "update " . TBL_JOUEUR . " set jou_mail = '" . securite_bdd($mail_2) . "', jou_password = '".md5($mdp2)."' where jou_id = '" . $jou_id . "' ;" ;
						$resultUpdate = $mysqli->query($sSQLUpdate) ;
						envoiMailCreationCapitaine($mail_2, $mdp2, $eq_id) ;
					}
				}
			} else {
				$mdp2 = chaine_aleatoire(10) ;
				$sSQLInsert =  "insert into " . TBL_JOUEUR . 
					" (jou_nom, jou_mail, jou_password, jou_dro_id, jou_nb_conn) values ('".ucwords(strtolower(securite_bdd($nom_2)))."', '".securite_bdd($mail_2)."', '".md5($mdp2)."', '2', '0') ;" ;
				$resultInsert = $mysqli->query($sSQLInsert) ;
				$jou_id = $mysqli->insert_id ;
				envoiMailCreationCapitaine($mail_2, $mdp2, $eq_id) ;
			}
			$sSQLInsert =  "insert into " . TBL_EQUIPE_CORRESP . 
					" (ec_eq_id, ec_jou_id) values ('".securite_bdd($eq_id)."', '".$jou_id."') ;" ;
			$resultInsert = $mysqli->query($sSQLInsert) ;
		}
		$sSQLInsert =  "insert into " . TBL_EPS . 
					" (eps_eq_id, eps_pou_id, eps_sai_annee) values ('".securite_bdd($eq_id)."', '2', '".SAISON_INS."') ;" ;
		$resultInsert = $mysqli->query($sSQLInsert) ;
		envoiMailInscription(securite_bdd($eq_id)) ;
		header('Location: index.php?op=vins') ;
		exit();
	}
	if(isset($_POST['addInsNew'])) {
		extract($_POST) ;
		if($soc_id_new==0) {
			$sSQLVerif = "select soc_id soc_id_new, soc_nom from " . TBL_SOCIETE . " where lower(soc_nom) = lower('" . securite_bdd($societe_new) ."') ;" ;
			$resultVerif = $mysqli->query($sSQLVerif) ;
			if(mysqli_num_rows($resultVerif)>0) {
				while ($rowVerif = mysqli_fetch_array($resultVerif)) {
					extract($rowVerif) ;
				}
			} else {
				$sSQLSoc = "insert into " . TBL_SOCIETE .  
							" (soc_nom) values ('".securite_bdd($societe_new)."') ;" ;
				$resultSoc = $mysqli->query($sSQLSoc) ;
				$soc_id_new = $mysqli->insert_id ;
			}
		} else {
			$sSQLSoc = "select count(*) cptSoc " .
						" from " . TBL_SOCIETE .  
						" where soc_id = '".$soc_id_new."' ;" ;
			$resultSoc = $mysqli->query($sSQLSoc) ;
			while ($rowSoc = mysqli_fetch_array($resultSoc)) {
				extract($rowSoc) ;
			}
			if($cptSoc==0) {
				header('Location: index.php?op=err&v=ins') ;
				exit();
			}
		}
		$sSQLVerif = "select eq_id, eq_nom from " . TBL_EQUIPE . " where lower(eq_nom) = lower('" . securite_bdd($eq_nom_new) ."') ;" ;
		$resultVerif = $mysqli->query($sSQLVerif) ;
		if(mysqli_num_rows($resultVerif)>0) {
			while ($rowVerif = mysqli_fetch_array($resultVerif)) {
				extract($rowVerif) ;
			}
		} else {
			$sqlMax = "select max(eq_id)+1 eq_id_new from " . TBL_EQUIPE . " where eq_id < '1000000' ;" ;
			$resultMax = $mysqli->query($sqlMax) ;
			while ($rowMax = mysqli_fetch_array($resultMax)) {
				extract($rowMax) ;
			}
			$sSQLEq =  "insert into " . TBL_EQUIPE .  
						" (eq_id, eq_nom, eq_ter_id, eq_jour,eq_coupe, eq_couleur, eq_couleur_ext, eq_soc_id, eq_commentaire) " .
						" values ('".$eq_id_new."', '".securite_bdd($eq_nom_new)."', '".securite_bdd($ter_id_new)."', '".securite_bdd($jour_new)."', '".securite_bdd($coupe_new)."', '".securite_bdd($couleur_new)."', '".securite_bdd($couleur_ext_new)."', '".securite_bdd($soc_id_new)."', '".securite_bdd($commentaire_new)."') ;" ;
			$resultEq = $mysqli->query($sSQLEq) ;
			$eq_id = $mysqli->insert_id ;
		}
		// Vérification du nom déjà existant
		$sSQLVerif = "select jou_id, jou_mail from " . TBL_JOUEUR . " where lower(jou_nom) = lower('" . securite_bdd($nom_1_new) ."') ;" ;
		$resultVerif = $mysqli->query($sSQLVerif) ;
		if(mysqli_num_rows($resultVerif)>0) {
			while ($rowVerif = mysqli_fetch_array($resultVerif)) {
				extract($rowVerif) ;
				if($jou_mail=="") {
					$mdp1 = chaine_aleatoire(10) ;
					$sSQLUpdate =  "update " . TBL_JOUEUR . " set jou_mail = '" . securite_bdd($mail_1_new) . "', jou_password = '".md5($mdp1)."' where jou_id = '" . securite_bdd($jou_id) . "' ;" ;
					$resultUpdate = $mysqli->query($sSQLUpdate) ;
					envoiMailCreationCapitaine($mail_1_new, $mdp1, $eq_id) ;
				}
			}
		} else {
			$mdp1 = chaine_aleatoire(10) ;
			$sSQLInsert =  "insert into " . TBL_JOUEUR . 
				" (jou_nom, jou_mail, jou_password, jou_dro_id, jou_nb_conn) values ('".ucwords(strtolower(securite_bdd($nom_1_new)))."', '".securite_bdd($mail_1_new)."', '".md5($mdp1)."', '2', '0') ;" ;
			$resultInsert = $mysqli->query($sSQLInsert) ;
			$jou_id = $mysqli->insert_id ;
			envoiMailCreationCapitaine($mail_1_new, $mdp1, $eq_id) ;
		}
		$sSQLInsert =  "insert ignore into " . TBL_EQUIPE_CORRESP . 
				" (ec_eq_id, ec_jou_id) values ('".securite_bdd($eq_id)."', '".$jou_id."') ;" ;
		$resultInsert = $mysqli->query($sSQLInsert) ;
		if($nom_2_new!="" && $mail_2_new != "") {
			// Vérification du nom déjà existant
			$sSQLVerif = "select jou_id, jou_mail from " . TBL_JOUEUR . " where lower(jou_nom) = lower('" . securite_bdd($nom_2_new) ."') ;" ;
			$resultVerif = $mysqli->query($sSQLVerif) ;
			if(mysqli_num_rows($resultVerif)>0) {
				while ($rowVerif = mysqli_fetch_array($resultVerif)) {
					extract($rowVerif) ;
					if($jou_mail=="") {
						$mdp2 = chaine_aleatoire(10) ;
						$sSQLUpdate =  "update " . TBL_JOUEUR . " set jou_mail = '" . securite_bdd($mail_2_new) . "', jou_password = '".md5($mdp2)."' where jou_id = '" . $jou_id . "' ;" ;
						$resultUpdate = $mysqli->query($sSQLUpdate) ;
						envoiMailCreationCapitaine($mail_2_new, $mdp2, $eq_id) ;
					}
				}
			} else {
				$mdp2 = chaine_aleatoire(10) ;
				$sSQLInsert =  "insert ignore into " . TBL_JOUEUR . 
					" (jou_nom, jou_mail, jou_password, jou_dro_id, jou_nb_conn) values ('".ucwords(strtolower(securite_bdd($nom_2_new)))."', '".securite_bdd($mail_2_new)."', '".md5($mdp2)."', '2', '0') ;" ;
				$resultInsert = $mysqli->query($sSQLInsert) ;
				$jou_id = $mysqli->insert_id ;
				envoiMailCreationCapitaine($mail_2_new, $mdp2, $eq_id) ;
			}
			$sSQLInsert =  "insert ignore into " . TBL_EQUIPE_CORRESP . 
					" (ec_eq_id, ec_jou_id) values ('".securite_bdd($eq_id)."', '".$jou_id."') ;" ;
			$resultInsert = $mysqli->query($sSQLInsert) ;
		}
		$sSQLInsert =  "insert ignore into " . TBL_EPS .  
					" (eps_eq_id, eps_pou_id, eps_sai_annee) values ('".securite_bdd($eq_id)."', '2', '".SAISON_INS."') ;" ;
		$resultInsert = $mysqli->query($sSQLInsert) ;
		envoiMailInscription(securite_bdd($eq_id)) ;
		header('Location: index.php?op=vins') ;
		exit();
	}
?>