<?php include('templates/bandeau.php') ; ?>

<?php 
	$sSQLColor = "select sai_nb_equipe, sai_nb_montee, sai_nb_descente from " . TBL_SAISON . ", " . TBL_POULE . " where sai_pou_id = pou_id and sai_annee = '" . SAISON . "' and pou_eve_id = 3 order by pou_nom " ;
	$resultColor = $mysqli->query($sSQLColor) ;
	//echo $sSQLColor	 ;
	if (mysqli_num_rows($resultColor)==0) {
		echo "erreur" ;
	}
	else {
		while ($rowColor = mysqli_fetch_array($resultColor)) {
			extract($rowColor) ;
			for($i=1;$i<=$sai_nb_equipe;$i++) {
				$color = COLOR ;
				if($i==1) { $classementColor[$i] = COLOR_CHAMPION ; continue ; }
				if($i-$sai_nb_montee<=0) { $classementColor[$i] = COLOR_MONTEE ; continue ; }
				if($i+$sai_nb_descente>$sai_nb_equipe) { $classementColor[$i] = COLOR_DESCENTE ; continue ; }
				$classementColor[$i] = $color ;
			}
		}
	}
?>

<div class = "PagePrincipale">
	<CENTER>
		<TABLE CELLPADDING=0 CELLSPACING=0 style='margin-bottom: 10px'>	
			<TR>
				<TD width='800' align=justify>
					<center><b>R&eacute;glement des phases de Poules de <?php echo NOM_EVENEMENT . " " . NOM_SAISON ; ?></b></center>
					<p>Dans les poules, le classement des &eacute;quipes s'effectue &agrave; l'aide des crit&egrave;res suivants :</p>
					<UL>
						<LI>Plus grand nombre de points</li>
						<LI>Meilleure diff&eacute;rence de buts</li>
						<LI>Plus grand nombre de buts marqu&eacute;s</li>
					</UL>
					<p>Les crit&egrave;res suivants seront utilis&eacute;s (dans cet ordre) pour d&eacute;partager les &eacute;quipes en cas d'&eacute;galit&eacute; :</p>
					<UL>
						<LI>Nombre de p&eacute;nalit&eacute;s d'arbitrage</li>
						<LI>Diff&eacute;rence de buts particuli&egrave;re</li>
					</UL>
					<p>Cette ann&eacute;e, <?php echo NOM_EVENEMENT ; ?> se d&eacute;roulera sous la forme d'un tournoi dans un format analogue &agrave; celui de la Ligue des Champions. &Agrave; savoir : les &eacute;quipes sont r&eacute;parties par chapeaux, chacune rencontrera des &eacute;quipes des autres chapeaux pendant la phase de poule &eacute;tablissant ainsi un classement. Enfin, les 4 ou 8 meilleurs au classement (selon disponibilit&eacute;s des terrains) se d&eacute;partageront pendant une phase finale &agrave; &eacute;liminations directes.</p>
				</td>
			</tr>
		</table>
		<TABLE CELLPADDING=0 CELLSPACING=0>	
			<TR>
				<TD align=center>
		<?php
			$sSQLJournee = "SELECT distinct(pou_nom) " .
				" FROM " . TBL_POULE . ", " . TBL_SAISON . ", " . TBL_EPS . ", " . TBL_EQUIPE . ", " . TBL_MATCH . 
				" WHERE eps_eq_id = eq_id " .
					" and eps_pou_id = pou_id " .
					" and sai_pou_id = pou_id " .
					" and mat_eq_id_1 = eq_id " .
					" and pou_eve_id = 3 " .
					" and mat_sai_annee = sai_annee " .
					" and eps_sai_annee = sai_annee " .
					" and sai_annee = '" . SAISON . "' " .
				" ORDER BY pou_nom " ;
			$resultJournee = $mysqli->query($sSQLJournee) ;
			while ($row = mysqli_fetch_array($resultJournee)) {
				extract($row) ;
				if($pou_nom != "A" && $pou_nom != "Swiss ELOCAR Cup" ) {
					echo " | " ;
				}
				echo "<A href=#" . $pou_nom . ">Poule " . $pou_nom . "</A>" ;
			}
		?>
				</td>
			</tr>
		</table>
		<table>
			<tr>
		<?php
			$sSQL = "SELECT pou_nom, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
				" date_format(cre_date, ' %d '), " .
				" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
				" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
				" mat_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, ter_nom, sco1.sco_bp score_1, sco2.sco_bp score_2, " .
				" CASE when mat_statut = 1 then '" . COLOR_JOUE . "' when cre_date < sysdate() then '" . COLOR_ATTENTE . "' when mat_statut = 2 then '" . COLOR_REPORT . "' when mat_statut = 3 then '" . COLOR_ARBITRAGE . "' when cre_date > sysdate() then '" . COLOR_PROGRAMME . "' else '' end style " .
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
					" and eve_id = 3 " .
				" ORDER BY pou_nom, cre_date " ;
			$result = $mysqli->query($sSQL) ;
			//echo $sSQL ;
			$poule_prec = "" ;
			$flag=0;
			$col=0;
			$nbCol=1;
			$x=0;
			while ($row = mysqli_fetch_array($result)) {
				extract($row) ;
				if($pou_nom!=$poule_prec) {
					if($flag++!=0) { // Classement de la poule précédente
						echo "</table>" .
						"<TABLE class='tabDetail'>".
							"<TR>".
								"<TH WIDTH=35 ALIGN=center>POS</TH>".
								"<TH WIDTH=200 ALIGN=left>EQUIPE</TH>".
								"<TH WIDTH=35 ALIGN=center>PTS</TH>".
								"<TH WIDTH=35 ALIGN=center>J</TH>".
								"<TH WIDTH=35 ALIGN=center>G</TH>".
								"<TH WIDTH=35 ALIGN=center>N</TH>".
								"<TH WIDTH=35 ALIGN=center>P</TH>".
								"<TH WIDTH=35 ALIGN=center>BP</TH>".
								"<TH WIDTH=35 ALIGN=center>BC</TH>".
								"<TH WIDTH=35 ALIGN=center>Diff.</TH>".
								"<TH WIDTH=35 ALIGN=center>Arb.</TH>".
								"<TH WIDTH=35 ALIGN=center>Forf.</TH>".
							"</TR>" ;
						$sSQLClassement = "select eq_id, eq_nom, coalesce(sum(sco_points)-(select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id),0) pts, " .
									" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_points is not null and mat_sai_annee = '" . SAISON . "') j, " .
									" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp> sco_bc and mat_sai_annee = '" . SAISON . "') g, " .
									" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp= sco_bc and mat_sai_annee = '" . SAISON . "') n, " .
									" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp< sco_bc and mat_sai_annee = '" . SAISON . "') p, " .
									" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pen_type = 'A') nbPen, " .
									" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pen_type = 'F') nbFor, " .
									" coalesce(sum(sco_bp),0) bp, coalesce(sum(sco_bc),0) bc, coalesce(sum(sco_bp)-sum(sco_bc),0) diff " .

								" from " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_SCORE . ", " . TBL_MATCH . " , " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . " " .
								" where sco_eq_id = eq_id " .
									" and eps_eq_id = eq_id " .
									" and sco_mat_id = mat_id " .
									" and mat_pou_id = pou_id " .
									" and eve_id = pou_eve_id " .
									" and sai_pou_id = pou_id " .
									" and eps_pou_id = pou_id " .
									" and mat_sai_annee = sai_annee " .
									" and eps_sai_annee = sai_annee " .
									" and sai_annee = '" . SAISON . "' " .
									" and pou_nom = '" . $poule_prec . "' " .
									" and eve_id = 3 " .
								" group by eq_nom order by pts desc, diff desc, bp desc, eq_nom " ;
						$resultClassement = $mysqli->query($sSQLClassement) ;
						//echo $sSQLClassement ;
						$i=1 ;
						while ($rowClassement = mysqli_fetch_array($resultClassement)) {
							extract($rowClassement) ;
							echo "<TR>" ;
								echo "<TD WIDTH=30 ALIGN=center style='color: ".$classementColor[$i]."'>".$i."</TD>" ;
									echo "<TD WIDTH=200 ALIGN=left style='border-left: 5px solid ".$classementColor[$i].";'><a href='index.php?op=eq&id=".$eq_id."'>".$eq_nom."</a></TD>" ;
									echo "<TD WIDTH=30 ALIGN=center>".$pts."</TD>" ;
									echo "<TD WIDTH=30 ALIGN=center>".$j."</TD>" ;
									echo "<TD WIDTH=30 ALIGN=center>".$g."</TD>" ;
									echo "<TD WIDTH=30 ALIGN=center>".$n."</TD>" ;
									echo "<TD WIDTH=30 ALIGN=center>".$p."</TD>" ;
									echo "<TD WIDTH=30 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$bp."</TD>" ;
									echo "<TD WIDTH=30 ALIGN=center>".$bc."</TD>" ;
									echo "<TD WIDTH=30 ALIGN=center>".$diff."</TD>" ;
									echo "<TD WIDTH=30 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$nbPen."</TD>" ;
									echo "<TD WIDTH=30 ALIGN=center>".$nbFor."</TD>" ;
							echo "</TR>" ;
							$i++;
						}
						echo "</TABLE>".
						"</td>".
						"</tr>".
						"</table>".
						"</td>" .
						"</tr>".
						"<tr>" ;	
					}
					
					echo"<td>".
						"<table cellpadding=0 cellspacing=0 class='tabDetail' id='".$pou_nom."'>".
						"<tr>" .
						"<th align=center id=".$pou_nom.">".
						"Poule ".$pou_nom.
						"<img src='". IMG ."down.png' alt='down' height='20' width='20'>" .
						"<img src='". IMG ."right.png' alt='right' height='20' width='20' style='display: none;'>" .
						"</th>".
						"</tr>".
						"<tr id='".$pou_nom."_' >".
						"<td>".
						"<table cellpadding=0 cellspacing=0 style='padding:2px' class='tabDetail'>" ;
					$col++ ;
					$idPhase[$x] = $pou_nom ;
					$x++ ;
				}
				
				extract(color($score_1, $score_2)) ;
				
				echo "<tr>" .
					"<td align='left' style='border-left: 5px solid ".$style." ;'>".
						$jour.
					"</td>" .
					"<td align='right' class='".$color1."' style='border:none'>" .
						"<a href='index.php?op=eq&id=".$eqId1."'>".$eq1."</a>" ;
					$sSQLPen1 = "select * from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $eqId1 ;
					$resultPen1 = $mysqli->query($sSQLPen1) ;
					while ($rowPen1 = mysqli_fetch_array($resultPen1)) {
						extract($rowPen1) ;
						echo "<sup>" . $pen_type . "</sup>" ;
					}
				echo "</td>" .
					"<td width='' align='center' style='border:none'>".$score_1." - ".$score_2."</td>" .
					"<td align='left' class='".$color2."' style='border:none'>" .
					"<a href='index.php?op=eq&id=".$eqId2."'>".$eq2."</a>" ;
					$sSQLPen2 = "select pen_type from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $eqId2 ;
					$resultPen2 = $mysqli->query($sSQLPen2) ;
					while ($rowPen2 = mysqli_fetch_array($resultPen2)) {
						extract($rowPen2) ;
						echo "<sup>" . $pen_type . "</sup>" ;
					}
				echo "</td>" .
					"<td align='left' style='border:none'>" .
						"<a href='index.php?op=eq&id=".$arbId."'>".$arb."</a>" ;
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
				echo "</td>" .
						"<td align='left' style='border:none'>".
							$ter_nom.
						"</td>".
					"</tr>" ;
				$poule_prec = $pou_nom ;
			}
			echo "</table>".
			"<TABLE class='tabDetail'>".
			"<TR>".
			"<TH WIDTH=30 ALIGN=center>POS</TH>".
			"<TH WIDTH=200 ALIGN=left>EQUIPE</TH>".
			"<TH WIDTH=30 ALIGN=center>PTS</TH>".
			"<TH WIDTH=30 ALIGN=center>J</TH>".
			"<TH WIDTH=30 ALIGN=center>G</TH>".
			"<TH WIDTH=30 ALIGN=center>N</TH>".
			"<TH WIDTH=30 ALIGN=center>P</TH>".
			"<TH WIDTH=30 ALIGN=center>BP</TH>".
			"<TH WIDTH=30 ALIGN=center>BC</TH>".
			"<TH WIDTH=30 ALIGN=center>Diff.</TH>".
			"<TH WIDTH=30 ALIGN=center>Arb.</TH>".
			"<TH WIDTH=30 ALIGN=center>Forf.</TH>".
			"</TR>" ;
			$sSQLClassement = "select eq_nom, coalesce(sum(sco_points)-(select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id),0) pts, " .
									" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_points is not null and mat_sai_annee = '" . SAISON . "') j, " .
									" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp> sco_bc and mat_sai_annee = '" . SAISON . "') g, " .
									" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp= sco_bc and mat_sai_annee = '" . SAISON . "') n, " .
									" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp< sco_bc and mat_sai_annee = '" . SAISON . "') p, " .
									" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pen_type = 'A') nbPen, " .
									" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pen_type = 'F') nbFor, " .
									" coalesce(sum(sco_bp),0) bp, coalesce(sum(sco_bc),0) bc, coalesce(sum(sco_bp)-sum(sco_bc),0) diff " .

								" from " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_SCORE . ", " . TBL_MATCH . " , " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . " " .
								" where sco_eq_id = eq_id " .
									" and eps_eq_id = eq_id " .
									" and sco_mat_id = mat_id " .
									" and mat_pou_id = pou_id " .
									" and eve_id = pou_eve_id " .
									" and sai_pou_id = pou_id " .
									" and eps_pou_id = pou_id " .
									" and mat_sai_annee = sai_annee " .
									" and eps_sai_annee = sai_annee " .
									" and sai_annee = '" . SAISON . "' " .
									" and pou_nom = '" . $poule_prec . "' " .
									" and eve_id = 3 " .
								" group by eq_nom order by pts desc, diff desc, bp desc, eq_nom " ;
			$resultClassement = $mysqli->query($sSQLClassement) ;
			//echo $sSQLClassement ;
			$i=1 ;
			while ($rowClassement = mysqli_fetch_array($resultClassement)) {
				extract($rowClassement) ;
				echo "<TR id='".$pou_nom."_'>" .
						"<TD WIDTH=30 ALIGN=center style='color: ".$classementColor[$i]."'>".$i."</TD>" .
						"<TD WIDTH=200 ALIGN=left style='border-left: 5px solid ".$classementColor[$i].";'><a href='index.php?op=eq&id=".$eq_id."'>".$eq_nom."</a></TD>" .
						"<TD WIDTH=30 ALIGN=center>".$pts."</TD>" .
						"<TD WIDTH=30 ALIGN=center>".$j."</TD>" .
						"<TD WIDTH=30 ALIGN=center>".$g."</TD>" .
						"<TD WIDTH=30 ALIGN=center>".$n."</TD>" .
						"<TD WIDTH=30 ALIGN=center>".$p."</TD>" .
						"<TD WIDTH=30 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$bp."</TD>" .
						"<TD WIDTH=30 ALIGN=center>".$bc."</TD>" .
						"<TD WIDTH=30 ALIGN=center>".$diff."</TD>" .
						"<TD WIDTH=30 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$nbPen."</TD>" .
						"<TD WIDTH=30 ALIGN=center>".$nbFor."</TD>" .
				"</TR>" ;
				$i++;
			}
			echo "</TABLE></td></tr></table></td>" ;
		?>
			</tr>
		</table>
	</CENTER>
</div>

<?php
	echo "<script>" .
		"$(document).ready(function(){ " ;
		for ($k=0;$k<sizeof($idPhase);$k++) {
			echo "$('#".$idPhase[$k]." th').click(function(){ " .
				"$('#".$idPhase[$k]."_ ').toggle('fast'); " .
				"$('img:visible', this).hide().siblings().show(); " .
			"}); " ;
		}
		echo "}); " .
	"</script> ";
?>
