<?php include('templates/bandeau.php') ; ?>
<script type="text/javascript">
	function Valid_Form(form) {
		liste = document.getElementById("cre_libre");
		texte = liste.options[liste.selectedIndex].text;
		if(liste.value==0 || texte=="--- Choisissez une date ---") {
			alert("Merci de faire votre choix de créneau");
			return false;
		}
		return true ;
	}
</script>
<div class = "PagePrincipale">
<form id="formDemReport" action="<?php echo PATH ; ?>code.php" method="POST"/>
<CENTER>
<?php
	$sSQL = "SELECT CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
		" date_format(cre_date, ' %d '), " .
		" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
		" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
		" cre_id, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) poule, mat_id, pou_id, date_format(cre_date, '%V') cre_date_semaine_report, cre_date date_match " .
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
	}
	if($_SESSION["eq_id"]!=$eqId1 && $_SESSION["eq_id"]!=$eqId2 && $_SESSION["eq_id"]!=$arbId) {
		redirect(PATH."index.php") ;
	}
	echo "<input type='hidden' name='opp' id='opp' value='".htmlentities($_GET['opp'])."'>" ;
	echo "<input type='hidden' name='pou_id' id='pou_id' value='".$pou_id."'>" ;
	echo "<input type='hidden' name='mat_id' id='mat_id' value='".$mat_id."'>" ;
	echo "<input type='hidden' name='cre_id' id='cre_id' value='".$cre_id."'>" ;
	echo "<input type='hidden' name='eq_id' id='eq_id' value='".$_SESSION["eq_id"]."'>" ;
	if($eqId1==$_SESSION["eq_id"]) {
		echo "<input type='hidden' name='eq_id_rec' id='eq_id_rec' value='".$eqId2."'>" ;
	} else {
		echo "<input type='hidden' name='eq_id_rec' id='eq_id_rec' value='".$eqId1."'>" ;
	}
	echo "<table cellpadding=1 cellspacing=1 style='font-size: 15px ;'><tr>" ;
	echo "<tr><td colspan='2'>".$jour." &agrave; ".$ter_nom." : ".$eq1." contre ".$eq2." arbitr&eacute; par ".$arb." en ".$poule."</td></tr>" ;
	
	if($pou_id < 30) {
		$date_max = SAISON+1 . '-05-01 12:30:00';
	} else {
		$date_max = SAISON+1 . '-07-01 12:30:00';
	}
	
	//echo "<option value='0'>Aucun pour le moment ".$eqId1." - ".$eqId2." - ".$arbId."</option>" ;
	$sSQLLibre = "SELECT cre_id, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
			" date_format(cre_date, ' %d '), " .
			" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
			" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
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
				//" and date_format(cre_date, '%Y%m%d') between date_format(now(), '%Y%m%d') and date_format(DATE_ADD('".$date_match."', INTERVAL 2 MONTH), '%Y%m%d') " .
				//" and date_format(cre_date, '%Y%m%d') between date_format(now(), '%Y%m%d') and date_format(DATE_ADD('".$date_match."', INTERVAL 12 MONTH), '%Y%m%d') " .
				" and date_format(cre_date, '%Y%m%d') between date_format(now(), '%Y%m%d') and date_format('" . $date_max . "', '%Y%m%d')" .
		" ORDER BY cre_date, ter_nom, cre_id " ;
	//echo $sSQLLibre ;
	echo "<tr><td align='right'>Cr&eacute;neau propos&eacute;* : </td><td><select name='cre_libre' id='cre_libre'>" ;
	echo "<option value='0'>--- Choisissez une date ---</option>" ;
	$resultLibre = $mysqli->query($sSQLLibre) ;
	while ($rowLibre = mysqli_fetch_array($resultLibre)) {
		extract($rowLibre) ;
		$flagJoue=false ;
		$flagSemaine=false ;
		$flagReserve=false ;
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
		$sSQLReserve = "select rep_cre_id from " . TBL_REPORT . " where rep_cre_id = '" . $cre_id . "' and rep_reponse = 0" ;
		$resultReserve = $mysqli->query($sSQLReserve) ;
		while ($rowReserve = mysqli_fetch_array($resultReserve)) {
			extract($rowReserve) ;
			$flagReserve=true ;
		}
		
		if(!$flagJoue) {
			if($flagSemaine) {
				echo "<option value='".$cre_id."' class='optionSemaine'>".$jour." &agrave; ".$ter_nom."</option>" ;
			} elseif($flagReserve) {
				echo "<option value='".$cre_id."' class='optionReserve' disabled>".$jour." &agrave; ".$ter_nom."</option>" ;
			} else {
				echo "<option value='".$cre_id."' class='optionOk'>".$jour." &agrave; ".$ter_nom."</option>" ;
			}
		}
	}
	echo "</select></td></tr>" ;
	//echo $sSQLLibre ;
?>
	<tr>
		<td colspan="2" align="left">
			<i><ul>
				<li class="optionOk">Cr&eacute;neaux libres o&ugrave; aucune &eacute;quipe ne joue ni arbitre ce jour-l&agrave;,<br/>et aucune &eacute;quipe ne joue la m&ecirc;me semaine.</li>
				<li class="optionSemaine">Cr&eacute;neaux libres mais au moins une des &eacute;quipes joue la même semaine.</li>
				<li class="optionReserve">Cr&eacute;neaux d&eacute;j&agrave; r&eacute;serv&eacute; en attente de r&eacute;ponse.</li>
			</ul></i>
		</td>
	</tr>
	<tr>
		<td valign='top' align='right'>
			Motif : 
		</td>
		<td>
			<textarea name="report_text" id="report_text" rows="10" cols="50">Report</textarea>
		</td>
	</tr>
	<tr>
		<td align=center colspan='2'>
			<?php echo boutonRetour() . " " . boutonSubmit("demReport", "Valider", 'return Valid_Form(document.forms["formDemReport"]);') ; ?>
		</td>
	</tr>
</table>
</CENTER>
</form>
</div>
