<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
<form id="formScore" action="<?php echo PATH ; ?>code.php" method="POST"/>
<CENTER>
<table cellpadding=1 cellspacing=1><tr>
<?php
	$sSQL = "SELECT cre_date, MONTH(cre_date) mois, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
		" date_format(cre_date, ' %d '), " .
		" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
		" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
		" cre_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, ter_nom, sco1.sco_bp score1, sco2.sco_bp score2, sco1.sco_pen pen1, sco2.sco_pen pen2, CONCAT(eve_nom, ' : ', pou_nom) poule, eve_id, " .
		" CASE when mat_statut = 1 then '" . COLOR_JOUE . "' when cre_date < sysdate() then '" . COLOR_ATTENTE . "' when mat_statut = 2 then '" . COLOR_REPORT . "' when mat_statut = 3 then '" . COLOR_ARBITRAGE . "' when cre_date > sysdate() then '" . COLOR_PROGRAMME . "' else '' end style, " .
		" IF(cre_date<now(), true, false) flagMaj, mat_statut, mat_id, pou_id, pou_eve_id " .
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
		$arb2 = "" ;
		$arbId2 = "" ;
		$sSQLArb2 = "select eq_id arbId2, eq_nom arb2 from " . TBL_MATCH . ", " . TBL_EQUIPE . " where mat_id = " . $mat_id . " and mat_eq_id_4 = eq_id ;" ;
		$resultArb2 = $mysqli->query($sSQLArb2) ;
		while ($rowArb2 = mysqli_fetch_array($resultArb2)) {
			extract($rowArb2) ;
		}
		if($_SESSION["eq_id"]!=$arbId && $_SESSION["eq_id"]!=$arbId2 && $_SESSION["eq_id"]!=$eqId1 && $_SESSION["eq_id"]!=$eqId2 && !isAdmin()) {
			redirect(PATH."index.php") ;
		}
		echo "<tr>" ;
		echo "<input type='hidden' name='opp' id='opp' value='".htmlentities($_GET['opp'])."'>" ;
		echo "<input type='hidden' name='pou_id' id='pou_id' value='".$pou_id."'>" ;
		echo "<input type='hidden' name='mat_id' id='mat_id' value='".$mat_id."'>" ;
		echo "<input type='hidden' name='pou_eve_id' id='pou_eve_id' value='".$pou_eve_id."'>" ;
		echo "<input type='hidden' name='eq1' id='eq1' value='".$eqId1."'>" ;
		echo "<input type='hidden' name='eq2' id='eq2' value='".$eqId2."'>" ;
		echo "<input type='hidden' name='arbId' id='arbId' value='".$arbId."'>" ;
		echo "<td colspan='2' align='center'>".$jour." &agrave; ".$ter_nom." en ".$poule."</td>" ;
		echo "</tr>" ;
		echo "<tr>" ;
		echo "<td style='padding-top: 10px ;'>".$eq1."</td>" ;
		echo "<td style='padding-top: 10px ;'><input type='text' name='score1' id='score1' value='".$score1."' size='1' maxlength='2' autofocus>" ;
		if($pou_eve_id==4) {
			echo " - <input type='text' name='pen1' id='pen1' value='".$pen1."' size='1' maxlength='2'>" ;
		}
		echo "</td>" ;
		echo "</tr>" ;
		echo "<tr>" ;
		echo "<td>".$eq2."</td>" ;
		echo "<td><input type='text' name='score2' id='score2' value='".$score2."' size='1' maxlength='2'>" ;
		if($pou_eve_id==4) {
			echo " - <input type='text' name='pen2' id='pen2' value='".$pen2."' size='1' maxlength='2'>" ;
		}
		echo "</td>" ;
		echo "</tr>" ;
		echo "<tr>" ;
		if($pou_eve_id!=4) {
			echo "<td>Pr&eacute;sence ".$arb."</td>" ;
			$pen=0 ;
			$query4 = "select count(*) pen from f_penalite where pen_eq_id = '".$arbId."' and pen_type = 'A' and pen_mat_id = '".$mat_id."'" ;
			$result4 = $mysqli->query($query4) ;
			while ($row4 = mysqli_fetch_array($result4)) {
				extract($row4) ;
			}
			if($pen==0) {
				echo "<td><select name='arb' id='arb'><option value='1' selected>Oui</option><option value='0'>Non</option></select></td>" ;
			} else {
				echo "<td><select name='arb' id='arb'><option value='1'>Oui</option><option value='0' selected>Non</option></select></td>" ;
			}
		} else {
			echo "<td>Arbitres absents</td>" ;
			echo "<td><select name='arb' id='arb'><option value='1' selected>Aucun</option><option value='".$arbId."'>".$arb."</option><option value='".$arbId2."'>".$arb2."</option><option value='0'>Les Deux</option></select></td>" ;
		} 
		echo "</tr>" ;
		echo "<tr>" ;
		echo "<td>Forfait ?</td>" ;
		echo "<td colspan=2><select name='forfait' id='forfait'>".
		"<option value='0' selected>Non</option>".
		"<option value='".$eqId1."'>".$eq1."</option>".
		"<option value='".$eqId2."'>".$eq2."</option>".
		"</select>".
		"</td>" ;
	}
?>
	<tr>
		<td colspan="2" style="padding-top: 10px ;">
			Commentaires : 
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<textarea name="com_text" id="com_text" rows="5" cols="50"></textarea>
		</td>
	</tr>
	<tr>
		<td colspan='2' align='center'>
			<?php if(isAdmin() || $_SESSION["eq_id"]==$arbId || $_SESSION["eq_id"]==$arbId2) { 
				echo boutonRetour() . " " . boutonSubmit("majScore", "Valider") . " " . bouton("razScore", "RAZ Score", "submit", "boutonRazScore") ;
			} else if($_SESSION["eq_id"]==$eqId1 || $_SESSION["eq_id"]==$eqId2) { 
				echo boutonRetour() . " " . boutonSubmit("envScore", "Envoyer") ;
			} ?>
		</td>
	</tr>
</table>
</CENTER>
</form>
</div>
