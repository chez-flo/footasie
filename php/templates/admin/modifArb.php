<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
	<form id="formModifArb" action="<?php echo PATH ; ?>code.php" method="POST"/>
		<CENTER>
			<table cellpadding=1 cellspacing=1><tr>
			<?php
				$sSQLMatch = "SELECT CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
					" date_format(cre_date, ' %d '), " .
					" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
					" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
					" cre_id, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id eqId3, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) poule, mat_id, pou_id " .
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
				$resultMatch = $mysqli->query($sSQLMatch) ;
				//echo $sSQL ;
				$mois_prec = "" ;
				while ($rowMatch = mysqli_fetch_array($resultMatch)) {
					extract($rowMatch) ;
					echo "<input type='hidden' name='opp' id='opp' value='".htmlentities($_GET['opp'])."'>" ;
					echo "<input type='hidden' name='pou_id' id='pou_id' value='".$pou_id."'>" ;
					echo "<input type='hidden' name='mat_id' id='mat_id' value='".$mat_id."'>" ;
					echo "<input type='hidden' name='cre_id' id='cre_id' value='".$cre_id."'>" ;
					echo "<tr><td align='center' colspan='2'>".$jour." &agrave; ".$ter_nom." : ".$eq1." contre ".$eq2." arbitr&eacute; par ".$arb." en ".$poule."</td></tr>" ;
				}

				$sSql = "SELECT eq_id, eq_nom, pou_eve_id " .
						" FROM " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . 
						" WHERE eps_eq_id = eq_id " .
							" and eps_pou_id = pou_id " .
							" and eps_sai_annee = '" . SAISON . "' " .
							" and eps_pou_id = '2' " .
							" and eq_id not in ('1', '".$eqId3."') " .
							" ORDER BY eq_nom" ;
				//echo $sSQLPoule ;
				$resultSql = $mysqli->query($sSql) ;
				echo "<tr>" ;
				echo "<td align='right'>&Eacute;quipe rempla&ccedil;ante :&nbsp;</td>" ;
				echo "<td align='left'><select name='arb_id' id='arb_id'\"><option value='0'>-- S&eacute;lectionner l'&eacute;quipe rempla&ccedil;ante --</option>" ;
				while ($rowSql = mysqli_fetch_array($resultSql)) {
					extract($rowSql) ;
					echo "<option value='".$eq_id."'>".$eq_nom."</option>" ;
				}
					echo "</select></td>" ;
				echo "</tr>" ;
			?>
				<tr>
					<td align='center' colspan='2'>
						<?php echo boutonRetour() . " " . boutonSubmit("modifArb", "Valider") ; ?>
					</td>
				</tr>
			</table>
		</CENTER>
	</form>
</div>
