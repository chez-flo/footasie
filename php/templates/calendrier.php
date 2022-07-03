<?php include('templates/bandeau.php') ; ?>

<?php 
	$sSQLColor = "select sai_nb_equipe, sai_nb_montee, sai_nb_descente from " . TBL_SAISON . ", " . TBL_POULE . " where sai_pou_id = pou_id and sai_annee = '" . SAISON . "' and pou_nom = '" . $nomSerie . "' and pou_eve_id = 2 " ;
	$resultColor = $mysqli->query($sSQLColor) ;
	//echo $sSQLColor	 ;
	if (mysqli_num_rows($resultColor)==0) {
		//echo $sSQLColor	 ;
		redirect(PATH . "index.php?op=cal") ;
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
		<TABLE CELLPADDING=0 CELLSPACING=0>	
			<TR>
				<TD style='border-left : 5px solid <?php echo COLOR_PROGRAMME ; ?>'>&nbsp;Match &agrave; venir&nbsp;&nbsp;&nbsp;</TD>
				<TD style='border-left : 5px solid <?php echo COLOR_ATTENTE ; ?>'>&nbsp;En attente du r&eacute;sultat&nbsp;&nbsp;&nbsp;</TD>
				<TD style='border-left : 5px solid <?php echo COLOR_REPORT ; ?>'>&nbsp;Attention : match modifi&eacute;&nbsp;&nbsp;&nbsp;</TD>
				<TD style='border-left : 5px solid <?php echo COLOR_ARBITRAGE ; ?>'>&nbsp;Modification d'arbitrage</TD>
			</TR>
		</TABLE>
		<table style="margin-top: 10px">
			<tr>
				<td width='50%' align=center valign=top>
					<table>
						<tr>
							<td>
								<TABLE cellpadding=5 cellspacing=0 class="tabDetail">
									<TR>
										<TH WIDTH=15 ALIGN=center>POS</TH>
										<TH WIDTH=200 ALIGN=left>EQUIPE</TH>
										<TH WIDTH=15 ALIGN=center>PTS</TH>
										<TH WIDTH=15 ALIGN=center>J</TH>
										<TH WIDTH=15 ALIGN=center>G</TH>
										<TH WIDTH=15 ALIGN=center>N</TH>
										<TH WIDTH=15 ALIGN=center>P</TH>
										<TH WIDTH=15 ALIGN=center>BP</TH>
										<TH WIDTH=15 ALIGN=center>BC</TH>
										<TH WIDTH=15 ALIGN=center>Diff.</TH>
										<TH WIDTH=15 ALIGN=center>Arb.</TH>
										<TH WIDTH=15 ALIGN=center>Rep.</TH>
										<TH WIDTH=15 ALIGN=center>Forf.</TH>
									</TR>
									<?php
										$sSQLClassement = "select eq_id, eq_nom, coalesce(sum(sco_points)-(select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id),0) pts, " .
													" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_points is not null and mat_sai_annee = '" . SAISON . "') j, " .
													" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp> sco_bc and mat_sai_annee = '" . SAISON . "') g, " .
													" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp= sco_bc and mat_sai_annee = '" . SAISON . "') n, " .
													" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp< sco_bc and mat_sai_annee = '" . SAISON . "') p, " .
													" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pen_type = 'A') nbPen, " .
													" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pen_type = 'F') nbFor, " .
													" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pen_type = 'R') nbRep, " .
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
													" and pou_nom ='" . $nomSerie . "' " .
													" and eve_id = 2 " .
												" group by eq_nom order by pts desc, diff desc, bp desc, eq_nom " ;
										$resultClassement = $mysqli->query($sSQLClassement) ;
										//echo $sSQLClassement ;
										$i=1 ;
										while ($rowClassement = mysqli_fetch_array($resultClassement)) {
											extract($rowClassement) ;
											if($diff>0) { $diff = "+".$diff ; }
											echo "<TR>" ;
												echo "<TD WIDTH=15 ALIGN=center style='color: ".$classementColor[$i]."; font-weight: bold;'>".$i."</TD>" ;
												echo "<TD WIDTH=200 ALIGN=left style='border-left: 5px solid ".$classementColor[$i].";'><a href='index.php?op=eq&id=".$eq_id."'>".$eq_nom."</a></TD>" ;
												echo "<TD WIDTH=15 ALIGN=center>".$pts."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center>".$j."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center>".$g."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center>".$n."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center>".$p."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$bp."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center>".$bc."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center>".$diff."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$nbPen."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center>".$nbRep."</TD>" ;
												echo "<TD WIDTH=15 ALIGN=center>".$nbFor."</TD>" ;
											echo "</TR>" ;
											$i++;
										}
									echo "</TABLE>" ;
								echo "<td/>" ;
							echo "</tr>" ;
							echo "<tr>" ;
								echo "<td>" ;
									echo "<table cellpadding=5 cellspacing=0 class='tabDetail' id='stat'>" ;
										echo "<tr>" ;
											echo "<th align=center colspan='3'>" ;
												echo "Statistiques" .
													"<img src='". IMG ."down.png' alt='down' height='20' width='20' style='display: none;'>" .
													"<img src='". IMG ."right.png' alt='right' height='20' width='20'>" ;
											echo "</th>" ;
										echo "</tr>" ;
										$color = "#84AD09" ;
										$j=0;
										$sSQLEquipe = "SELECT distinct(eq_id), eq_nom " .
												" FROM " . TBL_POULE . ", " . TBL_EPS . ", " . TBL_EQUIPE . 
												" WHERE eps_eq_id = eq_id " .
													" and eps_pou_id = pou_id " .
													" and pou_nom ='" . $nomSerie . "' " .
													" and eps_sai_annee = '" . SAISON . "' " .
												" ORDER BY pou_id " ;
										//echo $sSQLEquipe ;
										$resultEquipe = $mysqli->query($sSQLEquipe) ;
										while($rowEquipe = mysqli_fetch_array($resultEquipe)) {
											extract($rowEquipe) ;
											$nbVictoire = 0 ;
											$nbVictoireTot = 0 ;
											$equipeVictoire = "" ;
											$nbPasDefaite = 0 ;
											$nbPasDefaiteTot = 0 ;
											$equipePasDefaite = "" ;
											$sSQLStat = " SELECT mat_journee, sco_points " .
												" FROM f_creneau, f_match, f_score, f_poule " .
												" where sco_mat_id = mat_id ".
													" and cre_mat_id = mat_id ".
													" and mat_pou_id = pou_id ".
													" and mat_sai_annee = '" . SAISON . "' " .
													" and pou_nom ='" . $nomSerie . "' " .
													" and sco_eq_id = '".$eq_id."' ".
												" order by mat_journee" ;
											//echo $sSQLStat . "<br/>";
											$resultStat = $mysqli->query($sSQLStat) ;
											while ($rowStat = mysqli_fetch_array($resultStat)) {
												extract($rowStat) ;
												if($sco_points==4) {
													$nbVictoire++ ;
													$nbPasDefaite++ ;
												} elseif($sco_points==2) {
													$nbPasDefaite++ ;
													if($nbVictoire>$nbVictoireTot) {
														$nbVictoireTot = $nbVictoire ;
													}
													$nbVictoire = 0 ;
												}
												else {
													if($nbVictoire>$nbVictoireTot) {
														$nbVictoireTot = $nbVictoire ;
													}
													if($nbPasDefaite>$nbPasDefaiteTot) {
														$nbPasDefaiteTot = $nbPasDefaite ;
													}
													$nbVictoire = 0 ;
													$nbPasDefaite = 0 ;
												}
											}
											
											$arrayTab[$i][$j++] = array(
													"equipe" => $eq_nom,
													"victoire" => $nbVictoireTot,
													"pasDefaite" => $nbPasDefaiteTot
											);
										}
										//var_dump($arrayTab) ;
										$meilleurVictoire = 0 ;
										$meilleurEquipeVictoire = 0 ;
										$meilleurPasDefaite = 0 ;
										$meilleurEquipePasDefaite = 0 ;
										for($k=0;$k<sizeof($arrayTab[$i]);$k++) {
											if($arrayTab[$i][$k]["victoire"]>$meilleurVictoire) {
												$meilleurVictoire = $arrayTab[$i][$k]["victoire"] ;
												$meilleurEquipeVictoire = $arrayTab[$i][$k]["equipe"] ;
											} elseif($arrayTab[$i][$k]["victoire"]==$meilleurVictoire) {
												$meilleurEquipeVictoire .= ", " . $arrayTab[$i][$k]["equipe"] ;
											}
											if($arrayTab[$i][$k]["pasDefaite"]>$meilleurPasDefaite) {
												$meilleurPasDefaite = $arrayTab[$i][$k]["pasDefaite"] ;
												$meilleurEquipePasDefaite = $arrayTab[$i][$k]["equipe"] ;
											} elseif($arrayTab[$i][$k]["pasDefaite"]==$meilleurPasDefaite) {
												$meilleurEquipePasDefaite .= ", " . $arrayTab[$i][$k]["equipe"] ;
											}
											//echo $k . " - " . $arrayTab[$i][$k]['equipe'] ;
										}
										echo "<tr id='stat_'>" ;
											echo "<td style='color: ".$color."; font-weight: bold;' align='center'>" . $meilleurVictoire . "</td>" ;
											echo "<td>Victoires consécutives</td>" ;
											echo "<td>" . $meilleurEquipeVictoire . "</td>" ;
										echo "</tr>" ;
										echo "<tr id='stat_'>" ;
											echo "<td style='color: ".$color."; font-weight: bold;' align='center'>" . $meilleurPasDefaite . "</td>" ;
											echo "<td>Matchs sans défaite</td>" ;
											echo "<td>" . $meilleurEquipePasDefaite . "</td>" ;
										echo "</tr>" ;
										$sSQLPour = "select eq_id, eq_nom, coalesce(sum(sco_bp),0) bp " .
												" from " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_SCORE . ", " . TBL_MATCH . " , " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . " " .
												" where sco_eq_id = eq_id " .
													" and eps_eq_id = eq_id " .
													" and sco_mat_id = mat_id " .
													" and mat_pou_id = pou_id " .
													" and eve_id = pou_eve_id " .
													" and sai_pou_id = pou_id " .
													" and eps_pou_id = pou_id " .
													" and pou_nom ='" . $nomSerie . "' " .
													" and mat_sai_annee = sai_annee " .
													" and eps_sai_annee = sai_annee " .
													" and sai_annee = '" . SAISON . "' " .
													" and eve_id = 2 " .
												" group by eq_nom " .
												" order by bp desc, eq_nom " ;
										$resultPour = $mysqli->query($sSQLPour) ;
										//echo $sSQLClassement ;
										$i=1 ;
										$i_tmp=1 ;
										$bp_prec = 0 ;
										$bp_pour = 0 ;
										$eq_pour = "" ;
										$flag=true ;
										while ($rowPour = mysqli_fetch_array($resultPour)) {
											extract($rowPour) ;
											if($bp_prec>0 && $bp_prec!=$bp) { 
												break ; 
											}
											else { 
												if($flag) {
													$eq_pour .= $eq_nom ; 
												} else {
													$eq_pour .= ", " . $eq_nom ;
												}
												$bp_pour = $bp ;
												$flag=false ;
											}
											$bp_prec = $bp ;
										}
										echo "<TR id='stat_'>" ;
											echo "<TD style='color: ".$color."; font-weight: bold;' align='center'>".$bp_pour."</TD>" ;
											echo "<TD>Buts marqués</TD>" ;
											echo "<TD>".$eq_pour."</TD>" ;
										echo "</TR>" ;
										$sSQLContre = "select eq_id, eq_nom, coalesce(sum(sco_bc),0) bc " .
												" from " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_SCORE . ", " . TBL_MATCH . " , " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . " " .
												" where sco_eq_id = eq_id " .
													" and eps_eq_id = eq_id " .
													" and sco_mat_id = mat_id " .
													" and mat_pou_id = pou_id " .
													" and eve_id = pou_eve_id " .
													" and sai_pou_id = pou_id " .
													" and eps_pou_id = pou_id " .
													" and pou_nom ='" . $nomSerie . "' " .
													" and mat_sai_annee = sai_annee " .
													" and eps_sai_annee = sai_annee " .
													" and sai_annee = '" . SAISON . "' " .
													" and eve_id = 2 " .
												" group by eq_nom " .
												" order by bc, eq_nom " ;
										$resultContre = $mysqli->query($sSQLContre) ;
										//echo $sSQLContre ;
										$i=1 ;
										$i_tmp=1 ;
										$bc_prec = 0 ;
										$bc_contre = 0 ;
										$eq_contre = "" ;
										$flag=true ;
										while ($rowContre = mysqli_fetch_array($resultContre)) {
											extract($rowContre) ;
											if($bc_prec>0 && $bc_prec!=$bc) { 
												break ; 
											}
											else { 
												if($flag) {
													$eq_contre .= $eq_nom ; 
												} else {
													$eq_contre .= ", " . $eq_nom ;
												}
												$bc_contre = $bc ;
												$flag=false ;
											}
											$bc_prec = $bc ;
										}
										echo "<TR id='stat_'>" ;
											echo "<TD style='color: ".$color."; font-weight: bold;' align='center'>".$bc_contre."</TD>" ;
											echo "<TD>Buts encaissés</TD>" ;
											echo "<TD>".$eq_contre."</TD>" ;
										echo "</TR>" ;
										$sSQLGA = "select eq_id, eq_nom, coalesce(sum(sco_bp)-sum(sco_bc),0) diff " .
												" from " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_SCORE . ", " . TBL_MATCH . " , " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . " " .
												" where sco_eq_id = eq_id " .
													" and eps_eq_id = eq_id " .
													" and sco_mat_id = mat_id " .
													" and mat_pou_id = pou_id " .
													" and eve_id = pou_eve_id " .
													" and sai_pou_id = pou_id " .
													" and eps_pou_id = pou_id " .
													" and pou_nom ='" . $nomSerie . "' " .
													" and mat_sai_annee = sai_annee " .
													" and eps_sai_annee = sai_annee " .
													" and sai_annee = '" . SAISON . "' " .
													" and eve_id = 2 " .
												" group by eq_nom " .
												" order by diff desc, eq_nom " ;
										$resultGA = $mysqli->query($sSQLGA) ;
										//echo $sSQLGA ;
										$i=1 ;
										$i_tmp=1 ;
										$diff_prec = 0 ;
										$b_diff = 0 ;
										$eq_diff = "" ;
										$flag=true ;
										while ($rowGA = mysqli_fetch_array($resultGA)) {
											extract($rowGA) ;
											if($diff_prec>0 && $diff_prec!=$diff) { 
												break ; 
											}
											else { 
												if($flag) {
													$eq_diff .= $eq_nom ; 
												} else {
													$eq_diff .= ", " . $eq_nom ;
												}
												$b_diff = $diff ;
												$flag=false ;
											}
											$diff_prec = $diff ;
										}
										echo "<TR id='stat_'>" ;
											echo "<TD style='color: ".$color."; font-weight: bold;' align='center'>".$b_diff."</TD>" ;
											echo "<TD>Différence de but</TD>" ;
											echo "<TD>".$eq_diff."</TD>" ;
										echo "</TR>" ;
									echo "</TABLE>" ;
								echo "<td/>" ;
							echo "</tr>" ;
						echo "<td/>" ;
					echo "</tr>" ;
				echo "</TABLE>" ;
					if(isMobile()) {
						echo "</td></tr><tr>" ;
					} else {
						echo "</td>" ;
					}
						echo "<td valign=top>" ;
						echo "<table width='100%'>" ;
						echo "<tr>" .
							"<td align='center' id='closeAll' style='cursor:pointer'>" .
								"Inverser le d&eacute;veloppement" .
							"</td>" . 
						"</tr>" ;
			$journee = 0 ;
			$sSQLJournee = "SELECT min(mat_journee) journee " .
				" FROM " . TBL_EVENEMENT . ", " . TBL_POULE . ", " . TBL_MATCH . 
				" WHERE eve_id = pou_eve_id " .
					" and mat_pou_id = pou_id " .
					" and eve_id = 2 " .
					" and mat_statut in (0, 3) " .
					" and mat_sai_annee = '" . SAISON . "' " .
					" and pou_nom ='" . $nomSerie . "'" ;
			$resultJournee = $mysqli->query($sSQLJournee) ;
			while ($rowJournee = mysqli_fetch_array($resultJournee)) {
				extract($rowJournee) ;
			}

			$sSQL = "SELECT mat_journee, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
				" date_format(cre_date, ' %d '), " .
				" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
				" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
				" mat_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, ter_nom, sco1.sco_bp score_1, sco2.sco_bp score_2, mat_statut, " .
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
					" and eve_id = 2 " .
					" and pou_nom ='" . $nomSerie . "'" .
				" ORDER BY mat_journee, cre_date " ;
			$result = $mysqli->query($sSQL) ;
			//echo $sSQL ;
			$mat_journee_prec = "" ;
			$j=0;
			$col = 0 ;
			$flag_journee=false ;
			while ($row = mysqli_fetch_array($result)) {
				extract($row) ;
				extract(color($score_1, $score_2)) ;
				if(($col++%2)==0) {
					$bgColor = "bgColorPair" ;
				} else {
					$bgColor = "bgColorImpair" ;
				}	
				if($mat_journee!=$mat_journee_prec) {
					if($mat_journee!="1") {
							echo "</table>" . 
						"</td>" . 
					"</tr>" ;
					}
					echo "<tr>" .
						"<td align=center>" .
							"<table cellpadding=0 cellspacing=0 style='margin-bottom:1px ;' class='tabDetail' id='j".$mat_journee."'>" .
								"<tr>" .
									"<th align=center >" .
										"Journ&eacute;e ".$mat_journee.
										"<img src='". IMG ."down.png' alt='down' height='20' width='20' style='display: none;'>" .
										"<img src='". IMG ."right.png' alt='right' height='20' width='20'>" .
									"</th>" .
								"</tr>" ;
							$idPhase[$j] = $mat_journee ;
							$j++;
						}
						if($journee==$mat_journee) {
							echo "<tr bgcolor='".$style."' id='j".$mat_journee."_'><td align='center'><table width='100%' cellpadding=2 cellspacing=0>" ;
						} else {
							echo "<tr bgcolor='".$style."' id='j".$mat_journee."_' style='display: none;'><td align='center'><table width='100%' cellpadding=2 cellspacing=0>" ;
						}
											echo "<tr class='".$bgColor."'>" ;
								
												echo 	"<td align=center width='40%' style='border-left: 5px solid ".$style.";' class='".$color1."' style='font-size:15px;'><a href='index.php?op=eq&id=".$eqId1."'>".$eq1."</a>" ;
													$sSQLPen1 = "select * from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $eqId1 ;
													$resultPen1 = $mysqli->query($sSQLPen1) ;
													while ($rowPen1 = mysqli_fetch_array($resultPen1)) {
														extract($rowPen1) ;
														echo "<sup>" . $pen_type . "</sup>" ;
													}
												echo "</td>" .
												"<td align=center width='20%' style='font-weight: bold'>" . 
													$score_1." - ".$score_2 . 
												"</td>" .
												"<td align=center width='40%' class='".$color2."' style='font-size:15px; border-right: 5px solid ".$style.";'>" .
													"<a href='index.php?op=eq&id=".$eqId2."'>".$eq2."</a>" ;
														$sSQLPen2 = "select pen_type from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $eqId2 ;
														$resultPen2 = $mysqli->query($sSQLPen2) ;
														while ($rowPen2 = mysqli_fetch_array($resultPen2)) {
															extract($rowPen2) ;
															echo "<sup>" . $pen_type . "</sup>" ;
														}
												echo "</td>" .
											"</tr>" .
											"<tr>" .
												"<td align=center colspan='3' style='border-left: 5px solid ".$style."; border-right: 5px solid ".$style.";'>" .
													"&agrave; ".$ter_nom." le ".$jour ;
														if($arb != "") {
															echo "&nbsp;(<a href='index.php?op=eq&id=".$arbId."'>".$arb."</a>" ;
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
															echo ")" ;
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
												echo "</td>" . 
											"</tr>" . 
										"</table>" . 
									"</td>" . 
								"</tr>" ;
				
				$mat_journee_prec = $mat_journee ;
				$arbId2="";
				$arb2="";	
			}
		?>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</CENTER>
</div>

<?php
	echo "<script>" .
		"$(document).ready(function(){ " ;
		for ($k=0;$k<sizeof($idPhase);$k++) {
			echo "$('#j".$idPhase[$k]." th').click(function(){ " .
				"$('#j".$idPhase[$k]."_ ').toggle('fast'); " .
				"$('img:visible', this).hide().siblings().show(); " .
			"}); " ;
		}
		echo "$('#closeAll').click(function(){ " ;
			for ($k=0;$k<sizeof($idPhase);$k++) {
				echo "$('#j".$idPhase[$k]."_ ').toggle('fast'); " ;
			}
		echo"}); " .
			"}); " .
		    "$('#stat th').click(function(){ " .
        "$('#stat_	').toggle('fast'); " .
		"$('img:visible', this).hide().siblings().show();" .
    "});".
	"</script> ";
?>