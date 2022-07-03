<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
<form id="formReport" action="<?php echo PATH ; ?>code.php" method="POST"/>
<CENTER>
<table cellpadding=1 cellspacing=1 style='font-size: 15px ;'><tr>
<?php
	$sSQL = "SELECT CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
		" date_format(cre_date, ' %d '), " .
		" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
		" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
		" date_format(cre_date, '%W') jourMatch, " .
		" cre_id, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) poule, mat_id, pou_id, date_format(cre_date, '%V') cre_date_semaine_report " .
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
			" and mat_id = '" . htmlentities($_GET['id']) . "' " .
		" ORDER BY cre_date, ter_nom, cre_id " ;
	$result = $mysqli->query($sSQL) ;
	//echo $sSQL ;
	$mois_prec = "" ;
	while ($row = mysqli_fetch_array($result)) {
		extract($row) ;
		echo "<input type='hidden' name='opp' id='opp' value='".htmlentities($_GET['opp'])."'>" ;
		echo "<input type='hidden' name='pou_id' id='pou_id' value='".$pou_id."'>" ;
		echo "<input type='hidden' name='mat_id' id='mat_id' value='".$mat_id."'>" ;
		echo "<input type='hidden' name='cre_id' id='cre_id' value='".$cre_id."'>" ;
		echo "<tr><td>".$jour." &agrave; ".$ter_nom." : ".$eq1." contre ".$eq2." arbitr&eacute; par ".$arb." en ".$poule."</td></tr>" ;
		echo "<tr><td>&Eacute;quipe demandeuse : <select name='eq_id' id='eq_id'><option value='0'>Aucune</option><option value='".$eqId1."'>".$eq1."</option><option value='".$eqId2."'>".$eq2."</option></select></td></tr>" ;
	}
	$terMatch = $ter_nom ;
	
	$sSQLLibre = "SELECT cre_id, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
			" date_format(cre_date, ' %d '), " .
			" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
			" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
			" date_format(cre_date, '%W') jourCre, " .
			" ter_nom, date_format(cre_date, '%Y%m%d') cre_date_jour, date_format(cre_date, '%V') cre_date_semaine  " .
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
				" and mat_id = " . SAISON . "0000 " .
				" and mat_pou_id = pou_id " .
				" and date_format(cre_date, '%Y%m%d') > '20201201' " .
				" and date_format(cre_date, '%Y%m%d') >= date_format(now(), '%Y%m%d') " .
		" ORDER BY cre_date, ter_nom, cre_id " ;
	//echo $sSQLLibre ;
	echo "<tr><td align=center><select name='cre_libre' id='cre_libre'>" ;
	$resultLibre = $mysqli->query($sSQLLibre) ;
	while ($rowLibre = mysqli_fetch_array($resultLibre)) {
		extract($rowLibre) ;
		$flagJoue=false ;
		$flagSemaine=false ;
		$sSQLJour = "select mat_eq_id_1, mat_eq_id_2, mat_eq_id_3 from " . TBL_MATCH . ", " . TBL_CRENEAU . " where cre_mat_id = mat_id " .
			" and mat_sai_annee = '" . SAISON . "' " .
			" and date_format(cre_date, '%Y%m%d') = '" . $cre_date_jour ."'" ;
		$resultJour = $mysqli->query($sSQLJour) ;
		$eq_test = "" ;
		while ($rowJour = mysqli_fetch_array($resultJour)) {
			extract($rowJour) ;
			if($mat_eq_id_1==$eqId1 || $mat_eq_id_1==$eqId2 || $mat_eq_id_1==$arbId || 
				$mat_eq_id_2==$eqId1 || $mat_eq_id_2==$eqId2 || $mat_eq_id_2==$arbId || 
				$mat_eq_id_3==$eqId1 || $mat_eq_id_3==$eqId2 || $mat_eq_id_3==$arbId  ) {
				$flagJoue=true ;
			}
		}
		if($cre_date_semaine!=$cre_date_semaine_report) {
			$sSQLSemaine = "select mat_eq_id_1, mat_eq_id_2, mat_eq_id_3, date_format(cre_date, '%Y%m%d') date_cre from " . TBL_MATCH . ", " . TBL_CRENEAU . " where cre_mat_id = mat_id " .
				" and mat_sai_annee = '" . SAISON . "' " .
				" and date_format(cre_date, '%V') = '" . $cre_date_semaine ."'" ;
			$resultSemaine = $mysqli->query($sSQLSemaine) ;
			while ($rowSemaine = mysqli_fetch_array($resultSemaine)) {
				extract($rowSemaine) ;
				if($mat_eq_id_1==$eqId1 || $mat_eq_id_1==$eqId2 || 
					$mat_eq_id_2==$eqId1 || $mat_eq_id_2==$eqId2 ) {
					$flagSemaine=true ;
				}
			}
		}
		
		if(!$flagJoue) {
			if(!$flagSemaine) {
				echo "<option value='".$cre_id."' class='optionOk'>".$jour." &agrave; ".$ter_nom."</option>" ;
			} else {
				echo "<option value='".$cre_id."' class='optionSemaine'>".$jour." &agrave; ".$ter_nom."</option>" ;
			}
		} else {
			echo "<option value='".$cre_id."' class='optionMemeJour'>".$jour." &agrave; ".$ter_nom."</option>" ;
		}
		/*
		if($ter_nom == $terMatch && $jourMatch == $jourCre) {
			echo "<option value='".$cre_id."' class='optionOk'>".$jour." &agrave; ".$ter_nom."</option>" ;
		}
		*/
	}
	echo "</select></td></tr>" ;
?>
	<tr>
		<td align=center>
			<textarea name="report_text" id="report_text" rows="10" cols="50">Report</textarea>
		</td>
	</tr>
	<tr>
		<td align=center>
			<?php echo boutonRetour() . " " . boutonSubmit("report", "Valider") ; ?>
		</td>
	</tr>
</table>
</CENTER>
</form>
</div>
