<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
	<CENTER>
		<table class="tabDetail" id="match">
			<tr>
				<th align=center>
					Matchs
				</th>
			</tr>
	<?php
		$sSQL = "SELECT MONTH(cre_date) mois, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
			" date_format(cre_date, ' %d '), " .
			" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
			" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
			" mat_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, ter_nom, sco1.sco_bp score_1, sco2.sco_bp score_2, sco1.sco_pen pen_1, sco2.sco_pen pen_2, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, eve_id, " .
			" CASE when mat_statut = 1 then '" . COLOR_JOUE . "' when cre_date < sysdate() then '" . COLOR_ATTENTE . "' when mat_statut = 2 then '" . COLOR_REPORT . "' when mat_statut = 3 then '" . COLOR_ARBITRAGE . "' when cre_date > sysdate() then '" . COLOR_PROGRAMME . "' else '' end style, mat_statut " .
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
				" and mat_sai_annee = sai_annee " .
				" and eps_sai_annee = sai_annee " .
				" and sai_annee = '" . SAISON . "' " .
				" and mat_pou_id = pou_id " .
				" and (e1.eq_id = '" . $_SESSION['eq_id'] . "' or e2.eq_id = '" . $_SESSION['eq_id'] . "' or e3.eq_id = '" . $_SESSION['eq_id'] . "') " .
			" ORDER BY cre_date " ;
		$result = $mysqli->query($sSQL) ;
		//echo $sSQL ;
		$mois_prec = "" ;
		$i=0 ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			extract(color($score_1, $score_2, $pen_1, $pen_2)) ;
			if(($i++%2)==0) {
				$bgColor = "bgColorPair" ;
			} else {
				$bgColor = "bgColorImpair" ;
			}
			echo "<tr bgcolor='".$style."' id='match_'><td align='center'><table width='100%' cellpadding=2 cellspacing=0>" ;
			echo "<tr class='".$bgColor."'>" ;
			echo "<td align=center width='40%' class='".$color1."' style='font-size:15px;'>" ;
			echo "<a href='index.php?op=eq&id=".$eqId1."'>".$eq1."</a>" ;
			$sSQLPen1 = "select * from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $eqId1 ;
			$resultPen1 = $mysqli->query($sSQLPen1) ;
			while ($rowPen1 = mysqli_fetch_array($resultPen1)) {
				extract($rowPen1) ;
				echo "<sup>" . $pen_type . "</sup>" ;
			}
			echo "</td>" ;
			echo "<td align=center width='20%' style='font-weight: bold'>" ;
			if($score_1!="" && $score_1==$score_2 && $eve_id==4) {
				echo $score_1." (".$pen_1.") - (".$pen_2.") ".$score_2 ;
			} else {
				echo $score_1." - ".$score_2 ;
			}
			echo "</td>" ;
			echo "<td align=center width='40%' class='".$color2."' style='font-size:15px;'>" ;
			echo "<a href='index.php?op=eq&id=".$eqId2."'>".$eq2."</a>" ;
			$sSQLPen2 = "select * from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $eqId2 ;
			$resultPen2 = $mysqli->query($sSQLPen2) ;
			while ($rowPen2 = mysqli_fetch_array($resultPen2)) {
				extract($rowPen2) ;
				echo "<sup>" . $pen_type . "</sup>" ;
			}
			echo "</td>" ;
			echo "</tr><tr class='".$bgColor."'><td align=center colspan='3'>&agrave; ".$ter_nom." le ".$jour ;
			if($arb != "") {
				echo "&nbsp;(<a href='index.php?op=eq&id=".$arbId."'>".$arb."</a>)" ;
			}
			if(isCapitaine() && $arb!= "Amical") {
				if($style==COLOR_ATTENTE) {
					if($arbId==$_SESSION["eq_id"]) {
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
			if(isJoueur()) {
				if($style!=COLOR_JOUE && $style!=COLOR_ATTENTE) {
					echo "<a class='boutonConvoquer' href=\"index.php?op=dm&id=".$mat_id."&opp=a\";'>D&eacute;tail du match</a>" ;
				}
			}
			/*if(isCapitaine() && $style==COLOR_ATTENTE) {
				if($arbId==$_SESSION["eq_id"]) {
					echo "<a class='boutonMajScore' href=\"index.php?op=score&id=".$mat_id."&opp=calG\";'>Score</a>" ;
				} else if($eqId1==$_SESSION["eq_id"] || $eqId2==$_SESSION["eq_id"]) {
					echo "<a class='boutonMajScore' href=\"index.php?op=score&id=".$mat_id."&opp=calG\";'>Envoyer Score</a>" ;
				}
			}*/
			if(isAdmin() && $eq2!="") {
				if($style==COLOR_ATTENTE || $style==COLOR_JOUE) {
					echo "<a class='boutonMajScore' href=\"index.php?op=score&id=".$mat_id."&opp=a\";'>Score</a>" ;
				} else {
					if($mat_statut!=1) {
						echo "<a class='boutonReport' href=\"index.php?op=rep&id=".$mat_id."&opp=a\";'>Reporter</a>" ;
						echo "<a class='boutonModArb' href=\"index.php?op=modArb&id=".$mat_id."&opp=a\";'>Modifier Arbitre</a>" ;
					}
				}
			}
			echo "</td></tr></table></td></tr>" ;
			$mois_prec = $mois ;
		}
	?>
			</tr>
		</table>
	</CENTER>
</div>