<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
<form id="formAjoutMatch" action="<?php echo PATH ; ?>code.php" method="POST"/>
<CENTER>
<table cellpadding=1 cellspacing=1><tr>
<?php
	$sSQL = "SELECT cre_date, MONTH(cre_date) mois, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
		" date_format(cre_date, ' %d '), " .
		" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
		" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
		" cre_id, ter_nom " .
		" FROM " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
		" WHERE cre_ter_id = ter_id " .
			" and cre_id = '" . htmlentities($_GET['id']) . "' " .
		" ORDER BY cre_date, ter_nom, cre_id " ;
	$result = $mysqli->query($sSQL) ;
	//echo $sSQL ;
	$mois_prec = "" ;
	while ($row = mysqli_fetch_array($result)) {
		extract($row) ;
		echo "<tr>" ;
		echo "<input type='hidden' name='opp' id='opp' value='".htmlentities($_GET['opp'])."'>" ;
		echo "<input type='hidden' name='cre_id' id='cre_id' value='".$cre_id."'>" ;
		echo "<td align='center'>".$jour." &agrave; ".$ter_nom."</td>" ;
		echo "</tr>" ;
	}
	$sSQLPoule = "SELECT pou_id, CONCAT(eve_nom, ' ', pou_nom) poule " .
		" FROM " . TBL_EVENEMENT . ", " . TBL_POULE . ", " . TBL_SAISON .
		" WHERE eve_id = pou_eve_id " .
			" and sai_pou_id = pou_id " .
			" and eve_id in ('1', '4') " .
			" and pou_id <> '1' " .
			" and sai_annee = '" . SAISON . "' " .
		" ORDER BY pou_id " ;
	//echo $sSQLPoule ;
	$resultPoule = $mysqli->query($sSQLPoule) ;
	echo "<tr>" ;
	echo "<td align='center'><select name='pou_id' id='pou_id' onChange=\"makeRequest('reponse.php?choix=0','pou_id','equipe')\"><option value='0'>-- S&eacute;lectionner une poule --</option>" ;
	//echo "<td align='center'><select name='pou_id' id='pou_id' onChange=\"alert('coucou');\"><option value='0'>-- S&eacute;lectionner une poule --</option>" ;
	while ($rowPoule = mysqli_fetch_array($resultPoule)) {
		extract($rowPoule) ;
		echo "<option value='".$pou_id."'>".carac_spec_html($poule)."</option>" ;
	}
		echo "</select></td>" ;
	echo "</tr>" ;
?>
	<tr>
		<td align='center' id='equipe' name='equipe'>
		</td>
	</tr>
		
	<tr>
		<td align='center'>
			<?php echo boutonRetour() . " " . boutonSubmit("ajoutMatch", "Valider") ; ?>
		</td>
	</tr>
</table>
</CENTER>
</form>
</div>
