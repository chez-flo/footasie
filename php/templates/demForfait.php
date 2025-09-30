<?php include('templates/bandeau.php') ; ?>
<div class = "PagePrincipale">
<form id="formDemForfait" action="<?php echo PATH ; ?>code.php" method="POST"/>
<CENTER>
<?php
	$sSQL = "SELECT CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
		" date_format(cre_date, ' %d '), " .
		" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
		" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
		" cre_id, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) poule, mat_id, pou_id, date_format(cre_date, '%V') cre_date_semaine_report, cre_date date_match, pou_eve_id pou_eve_id " .
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
	echo "<input type='hidden' name='eq1' id='eq1' value='".$eqId1."'>" ;
	echo "<input type='hidden' name='eq2' id='eq2' value='".$eqId2."'>" ;
	echo "<input type='hidden' name='arbId' id='arbId' value='".$arbId."'>" ;
	echo "<input type='hidden' name='pou_eve_id' id='pou_eve_id' value='".$pou_eve_id."'>" ;
	echo "<input type='hidden' name='score1' id='score1' value='0'>" ;
	echo "<input type='hidden' name='score2' id='score2' value='0'>" ;
	echo "<input type='hidden' name='pen1' id='pen1' value='0'>" ;
	echo "<input type='hidden' name='pen2' id='pen2' value='0'>" ;
	echo "<input type='hidden' name='com_text' id='com_text' value=''>" ;
	echo "<input type='hidden' name='eq_id' id='eq_id' value='".$_SESSION["eq_id"]."'>" ;
	echo "<input type='hidden' name='forfait' id='forfait' value='".$_SESSION["eq_id"]."'>" ;
	if($eqId1==$_SESSION["eq_id"]) {
		echo "<input type='hidden' name='eq_id_rec' id='eq_id_rec' value='".$eqId2."'>" ;
	} else {
		echo "<input type='hidden' name='eq_id_rec' id='eq_id_rec' value='".$eqId1."'>" ;
	}
	echo "<table cellpadding=1 cellspacing=1 style='font-size: 15px ;'><tr>" ;
	echo "<tr><td colspan='2'>".$jour." &agrave; ".$ter_nom." : ".$eq1." contre ".$eq2." arbitr&eacute; par ".$arb." en ".$poule."</td></tr>" ;
	
?>
	
	<tr>
		<td align=center colspan='2'>
			<?php echo boutonRetour() . " " . boutonSubmit("majScore", "Valider le forfait", 'return Valid_Form(document.forms["formDemForfait"]);') ; ?>
		</td>
	</tr>
</table>
</CENTER>
</form>
</div>
