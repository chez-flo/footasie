<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
<CENTER>
<TABLE CELLPADDING=0 CELLSPACING=0>	
<TR><TD colspan=8 align=center>
<?php
	$sSQLMois = "SELECT distinct(MONTH(cre_date)) mois " .
		" FROM " . TBL_POULE . ", " . TBL_SAISON . ", " . TBL_EPS . ", " . TBL_CRENEAU . ", " . TBL_EQUIPE . ", " . TBL_MATCH . 
		" WHERE eps_eq_id = eq_id " .
			" and eps_pou_id = pou_id " .
			" and sai_pou_id = pou_id " .
			" and mat_eq_id_1 = eq_id " .
			" and cre_mat_id = mat_id " .
			" and mat_sai_annee = sai_annee " .
			" and eps_sai_annee = sai_annee " .
			" and sai_annee = '" . SAISON . "' " .
		" ORDER BY cre_date " ;
	$resultMois = $mysqli->query($sSQLMois) ;
	while ($rowMois = mysqli_fetch_array($resultMois)) {
		extract($rowMois) ;
		if($mois != "9") {
			echo " | " ;
		}
		echo "<A href=#" . convMoisIntChar($mois) . ">" . convMoisIntChar($mois) . "</A>" ;
	}
?>
</TR>
<TR><TD width='50' bgcolor='<?php echo COLOR_PROGRAMME ; ?>'><TD>&nbsp;Match &agrave; venir&nbsp;&nbsp;&nbsp;</TD>
<TD width='50' bgcolor='<?php echo COLOR_ATTENTE ; ?>'><TD>&nbsp;En attente du r&eacute;sultat&nbsp;&nbsp;&nbsp;</TD>
<TD width='50' bgcolor='<?php echo COLOR_REPORT ; ?>'><TD>&nbsp;Attention : match modifi&eacute;</TD>
<TD width='50' bgcolor='<?php echo COLOR_ARBITRAGE ; ?>'><TD>&nbsp;Modification d'arbitrage</TD></TR>
</TABLE>
<table cellpadding=1 cellspacing=1 style="font-size: 15px;" width='800px'><tr>
<?php
	$sSQL = "SELECT cre_date, MONTH(cre_date) mois, WEEK(cre_date) semaine, dayofweek(cre_date) jour, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
		" date_format(cre_date, ' %d '), " .
		" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
		" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
		" cre_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, ter_nom, sco1.sco_bp score1, sco2.sco_bp score2, sco1.sco_pen pen1, sco2.sco_pen pen2, CONCAT(eve_nom, ' : ', pou_nom) poule, eve_id, " .
		" CASE when mat_statut = 1 then '" . COLOR_JOUE . "' when cre_date < sysdate() then '" . COLOR_ATTENTE . "' when mat_statut = 2 then '" . COLOR_REPORT . "' when mat_statut = 3 then '" . COLOR_ARBITRAGE . "' when cre_date > sysdate() then '" . COLOR_PROGRAMME . "' else '' end style, " .
		" mat_id, mat_statut, ter_adresse " .
		" FROM " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_POULE . ", " . TBL_SCORE . " sco1, " . TBL_SCORE . " sco2, " . TBL_EPS . ", " . TBL_EQUIPE . " e1, " . TBL_EQUIPE . " e2, " . TBL_EQUIPE . " e3, " . TBL_MATCH . ", " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
		" WHERE eve_id = pou_eve_id " .
			" and eps_eq_id = e1.eq_id " .
			" and eps_pou_id = pou_id " .
			" and sai_pou_id = pou_id " .
			" and mat_eq_id_1 = e1.eq_id " .
			" and mat_eq_id_2 = e2.eq_id " .
			" and mat_eq_id_3 = e3.eq_id " .
			" and cre_mat_id = mat_id " .
			" and cre_ter_id = ter_id " .
			" and mat_id = sco1.sco_mat_id " .
			" and mat_id = sco2.sco_mat_id " .
			" and mat_eq_id_1 = sco1.sco_eq_id " .
			" and mat_eq_id_2 = sco2.sco_eq_id " .
			" and mat_pou_id = pou_id " .
			" and mat_sai_annee = sai_annee " .
			" and eps_sai_annee = sai_annee " .
			" and sai_annee = '" . SAISON . "' " .
		//		" and date_format(cre_date, '%Y%m%d') >= date_format(now(), '%Y%m%d') " .
		" union " .
			"SELECT cre_date, MONTH(cre_date) mois, WEEK(cre_date) semaine, dayofweek(cre_date) jour, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
			" date_format(cre_date, ' %d '), " .
			" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
			" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
			" cre_id, eq_nom eq1, '' eq2, '' arb, eq_id eqId1, '' eqId2, '' arbId, ter_nom, '' score1, '' score2, '' pen1, '' pen2, '' poule, '' eve_id, " .
			" '" . COLOR_LIBRE . "' style, mat_id, mat_statut, ter_adresse " .
			" FROM " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_POULE . ", " . TBL_EPS . ", " . TBL_EQUIPE . ", " . TBL_MATCH . ", " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
			" WHERE eve_id = pou_eve_id " .
				" and eps_eq_id = eq_id " .
				" and eps_pou_id = pou_id " .
				" and sai_pou_id = pou_id " .
				" and mat_eq_id_1 = eq_id " .
				" and cre_mat_id = mat_id " .
				" and cre_ter_id = ter_id " .
				" and mat_sai_annee = sai_annee " .
				" and eps_sai_annee = sai_annee " .
				" and sai_annee = '" . SAISON . "' " .
				" and mat_eq_id_2 is null " .
				" and mat_pou_id = pou_id " .
				" and date_format(cre_date, '%Y%m%d') >= date_format(now(), '%Y%m%d') " .
		" ORDER BY cre_date, ter_nom, cre_id " ;
	$result = $mysqli->query($sSQL) ;
	//echo $sSQL ;
	$mois_prec = "" ;
	$semaine_prec = "" ;
	$jour_prec = "" ;
	$i=0 ;
	while ($row = mysqli_fetch_array($result)) {
		extract($row) ;
		if(($i++%2)==0) {
			$bgColor = "bgColorPair" ;
		} else {
			$bgColor = "bgColorImpair" ;
		}
		/*if($mois!=$mois_prec) {
			echo "<tr><td colspan=7 align=center style='padding-top : 10px; padding-bottom:5px;'><b><a name =" . convMoisIntChar($mois) . " href=#top>" . convMoisIntChar($mois) . "</a></b></td>" ;
			echo "<td><a name=".$mois."></a>" ;
			echo "</tr>" ;
		}*/
		// echo "<tr bgcolor='".$style."'>" ;
		// echo "<td id='".$cre_id."'>".$jour."</td>" ;
		// echo "<td>".$ter_nom."</td>" ;
		// if($eq2<>"") {
			// echo "<td align=right>" ;
				// echo "<a href='javascript:void(0)' onclick='if(win_open)win.close();win_open=true;win=window.open(\"index.php?op=eq&id=".$eqId1."\", \"\", \"width=800, height=750\")'>".$eq1."</a>" ;
			// echo "</td>" ;
			// if($score1!="" && $score1==$score2 && $eve_id==4) {
				// echo "<td width='60' align='center' style='border:none'>".$score1." (".$pen1.") - (".$pen2.") ".$score2."</td>" ;
			// } else {
				// echo "<td width='60' align='center' style='border:none'>".$score1." - ".$score2."</td>" ;
			// }
			// echo "<td>" ;
				// echo "<a href='javascript:void(0)' onclick='if(win_open)win.close();win_open=true;win=window.open(\"index.php?op=eq&id=".$eqId2."\", \"\", \"width=800, height=750\")'>".$eq2."</a>" ;
			// echo "</td>" ;
			// echo "<td>" ;
				// echo "Arb : <a href='javascript:void(0)' onclick='if(win_open)win.close();win_open=true;win=window.open(\"index.php?op=eq&id=".$arbId."\", \"\", \"width=800, height=750\")'>".$arb."</a>" ;
			// echo "</td>" ;
			// echo "<td>".$poule."</td>" ;
			// if(isAdmin()) {
				// echo "<td onclick='document.location=\"index.php?op=majS&id=".$mat_id."&opp=calG\";'>Score</td>" ;
				// if($mat_statut!=1) {
					// echo "<td onclick='document.location=\"index.php?op=rep&id=".$mat_id."&opp=calG\";'>Reporter</td>" ;
				// }
			// }
		// }
		// else {
			// echo "<td align=center colspan=5>".$eq1."</td>" ;
		// }
		if($semaine!=$semaine_prec) {
			//echo "<tr><td><hr/></td></tr>" ;
		}
		if($jour!=$jour_prec) {
			echo "<tr><td align='center' style='font-weight: bold;font-size:25;' id='".$cre_id."'>" ;
			if($mois!=$mois_prec) {
				echo "<a name=".convMoisIntChar($mois)."></a>" ;
			}
			echo $jour ;
			echo "</td></tr>" ;
		}
		echo "<tr><td align='center' id='".$cre_id."'><table width='100%' cellpadding=2 cellspacing=0 style='border-left: 5px solid ".$style."; border-right: 5px solid ".$style."'>" ;
		echo "<tr class='".$bgColor."'>" ;
		if($eq2<>"") {
			extract(color($score1, $score2, $pen1, $pen2)) ;
			echo "<td align=center width='40%' class='".$color1."' style='font-size:15px;'>" ;
			echo "<a href='index.php?op=eq&id=".$eqId1."'>".$eq1."</a>" ;
			$sSQLPen1 = "select * from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $eqId1 ;
			$resultPen1 = $mysqli->query($sSQLPen1) ;
			while ($rowPen1 = mysqli_fetch_array($resultPen1)) {
				extract($rowPen1) ;
				echo "<sup>" . $pen_type . "</sup>" ;
			}
			echo "</td><td align=center width='20%' style='font-weight: bold'>" ;
			if($score1!="" && $score1==$score2 && $eve_id==4) {
				echo $score1." (".$pen1.") - (".$pen2.") ".$score2 ;
			} else {
				echo $score1." - ".$score2 ;
			}
			echo "</td><td align=center width='40%' class='".$color2."'>" ;
			echo "<a href='index.php?op=eq&id=".$eqId2."'>".$eq2."</a>" ;
			$sSQLPen2 = "select pen_type from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $eqId2 ;
			$resultPen2 = $mysqli->query($sSQLPen2) ;
			while ($rowPen2 = mysqli_fetch_array($resultPen2)) {
				extract($rowPen2) ;
				echo "<sup>" . $pen_type . "</sup>" ;
			}
			echo "</td>" ;
		}
		else {
			echo "<td align=center colspan='3'>" ;
			echo $eq1 ;
			echo "</td>" ;
		}
		echo "</tr>" ;
		echo "<tr class='".$bgColor."'>" ;
		echo "<td align=left style='padding-left:10px;font-size:14px;' width='40%'>" ;
		if($arb != "") {
			echo "<i>Arb : <a href='index.php?op=eq&id=".$arbId."'>".$arb."</a>" ;
			$sSQLArb = "select * from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $arbId ;
			$resultArb = $mysqli->query($sSQLArb) ;
			while ($rowArb = mysqli_fetch_array($resultArb)) {
				extract($rowArb) ;
				echo "<sup>" . $pen_type . "</sup>" ;
			}
			$sSQLArb2 = "select eq_id arbId2, eq_nom arb2 from " . TBL_MATCH . ", " . TBL_EQUIPE . " where mat_id = " . $mat_id . " and mat_eq_id_4 = eq_id ;" ;
			$resultArb2 = $mysqli->query($sSQLArb2) ;
			while ($rowArb2 = mysqli_fetch_array($resultArb2)) {
				extract($rowArb2) ;
				echo " & <a href='index.php?op=eq&id=".$arbId2."'>".$arb2."</a>" ;
				$sSQLArb2 = "select * from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $arbId2 ;
				$resultArb2 = $mysqli->query($sSQLArb2) ;
				while ($rowArb2 = mysqli_fetch_array($resultArb2)) {
					extract($rowArb2) ;
					echo "<sup>" . $pen_type . "</sup>" ;
				}
			}
			echo "</i>" ;
		}
		echo "</td>" ;
		echo "<td align=center width='20%'>" ;
		if($eq2=="" && $eqId1==1) { 
			echo "<a class='boutonDem' href=\"index.php?op=dem&id=".$cre_id."&opp=a\";'>Demander</a>" ; 
		
		}
		if(isCapitaine() && $arb!= "Amical") {
			if($style==COLOR_ATTENTE) {
				if($arbId==$_SESSION["eq_id"] || $arbId2==$_SESSION["eq_id"]) {
					echo "<a class='boutonMajScore' href=\"index.php?op=score&id=".$mat_id."&opp=a\";'>Score</a>" ;
				} else if($eqId1==$_SESSION["eq_id"] || $eqId2==$_SESSION["eq_id"]) {
					echo "<a class='boutonMajScore' href=\"index.php?op=score&id=".$mat_id."&opp=a\";'>Envoyer Score</a>" ;
				}
			}
			if($style!=COLOR_JOUE &&
				($eqId1==$_SESSION["eq_id"] || $eqId2==$_SESSION["eq_id"])) {
				echo "<a class='boutonReport' href=\"index.php?op=demRep&id=".$mat_id."&opp=a\";'>Demander Report</a>" ;
			}
		}
		echo "</td>" ;
		echo "<td align=right style='padding-right:10px' width='40%'>" ;
		echo "<i>".lienMap($ter_nom, $ter_adresse)."</i>" ;
		echo "</td></tr>" ;
		if(isAdmin()) {
			echo "<tr class='".$bgColor."'><td align=center colspan='3'>" ;
			if($eq2!="") {
				if($arb!= "Amical") {
					echo "<a class='boutonMajScore' href=\"index.php?op=score&id=".$mat_id."&opp=calG\";'>Score</a>" ;
				}
				else {
					echo "<a class='boutonReport' href=\"index.php?op=rep&id=".$mat_id."&opp=calG\";'>Reporter</a>" ;
					echo "<a class='boutonAnnuler' href=\"index.php?op=ann&id=".$mat_id."&opp=calG\";'>Annuler</a>" ;
				}
				if($mat_statut!=1) {
					echo "<a class='boutonReport' href=\"index.php?op=rep&id=".$mat_id."&opp=calG\";'>Reporter</a>" ;
					echo "<a class='boutonModArb' href=\"index.php?op=modArb&id=".$mat_id."&opp=a\";'>Modifier Arbitre</a>" ;
				}
			} else {
				echo "<a class='boutonDem' href=\"index.php?op=addM&id=".$cre_id."&opp=calG\";'>Ajout Match</a>" ;
			}
			echo "</td></tr>" ;
		}
		echo "</table></td></tr>" ;
		$mois_prec = $mois ;
		$semaine_prec = $semaine ;
		$jour_prec = $jour ;
		$arbId2="";
		$arb2="";	
	}
	
	$sSQLAuj="select cre_id from " . TBL_SAISON . ", " . TBL_POULE . ", " . TBL_MATCH . ", " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
		" where cre_mat_id = mat_id " .
			" and sai_pou_id = pou_id " .
			" and mat_pou_id = pou_id " .
			" and cre_ter_id = ter_id " .
			" and mat_sai_annee = sai_annee " .
			" and sai_annee = '" . SAISON . "' " .
			" and date_format(cre_date, '%Y%m%d') >= date_format(now(), '%Y%m%d') " .
		" ORDER BY cre_date, ter_nom, cre_id " ;
	$resultAuj = $mysqli->query($sSQLAuj) ;
	//echo $sSQLAuj ;
	while ($rowAuj = mysqli_fetch_array($resultAuj)) {
		extract($rowAuj) ;
		echo "<script>window.location.hash = '#".$cre_id."';</script>" ;
		break ;
	}
?>
</tr></table></td></tr></table>
</CENTER>
</div>
