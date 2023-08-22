<?php include('templates/bandeau.php') ; ?>

<script>

$(document).ready(function(){
    $("#detail th").click(function(){
        $("#detail_	").toggle("fast");
		$('img:visible', this).hide().siblings().show();
    });
	
	$("#match th").click(function(){
        $("#match_ ").toggle("fast");
		$('img:visible', this).hide().siblings().show();
    });
	
	$("#arbitrage th").click(function(){
        $("#arbitrage_ ").toggle("fast");
		$('img:visible', this).hide().siblings().show();
    });
	
	$("#responsables th").click(function(){
        $("#responsables_ ").toggle("fast");
		$('img:visible', this).hide().siblings().show();
    });
	
	$("#joueurs th").click(function(){
        $("#joueurs_ ").toggle("fast");
		$('img:visible', this).hide().siblings().show();
    });
	
	$("#classement th").click(function(){
        $("#classement_ ").toggle("fast");
		$('img:visible', this).hide().siblings().show();
    });
});
</script>

<div class = "PagePrincipale">
	<CENTER>
		<?php 
			if(isAdmin() || isCapitaine()) { 
				$sSQLReport = "select rep_id, eq_nom, c1.cre_date cre_date1, c2.cre_date cre_date2, " .
						" CONCAT(CASE dayofweek(c1.cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
							" date_format(c1.cre_date, ' %d '), " .
							" CASE month(c1.cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
							" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
						" CONCAT(CASE dayofweek(c2.cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
							" date_format(c2.cre_date, ' %d '), " .
							" CASE month(c2.cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
							" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour_dem, " .
						" t1.ter_nom ter_nom, t2.ter_nom ter_nom_dem " .
					" from " . TBL_REPORT . ", " . 
						TBL_SAISON . ", " . 
						TBL_POULE . ", " . 
						TBL_CRENEAU . " c1, " . 
						TBL_CRENEAU . " c2, " . 
						TBL_TERRAIN . " t1, " . 
						TBL_TERRAIN . " t2, " . 
						TBL_EQUIPE . ", " . 
						TBL_MATCH . 
					" where eq_id = rep_eq_id_dem " .
						" and rep_eq_id_rec = '".$_SESSION['eq_id']."' " .
						" and rep_reponse = 0 " .
						" and mat_id = rep_mat_id " .
						" and mat_sai_annee = sai_annee " .
						" and mat_pou_id = pou_id " .
						" and sai_pou_id = pou_id " .
						" and c1.cre_mat_id = mat_id " .
						" and c2.cre_id = rep_cre_id " .
						" and c1.cre_ter_id = t1.ter_id " .
						" and c2.cre_ter_id = t2.ter_id " .
						" and sai_annee = '" . SAISON . "' " .
					" order by rep_dem_d desc " ;
				$resultReport = $mysqli->query($sSQLReport) ;
				if(mysqli_num_rows($resultReport)>0) {
					echo "<table class='tabDetail' width='100%'>" ;
						echo "<tr><th><blink>Demandes de report</blink></th></tr>" ;
							while ($rowReport = mysqli_fetch_array($resultReport)) {
								extract($rowReport) ;
								echo "<tr>" ;
									echo "<td colspan='3' align='center'>" ;
										echo $eq_nom . " demande le report du match prévu le " . $jour . " à " . $ter_nom . " et propose la date du " . $jour_dem . " à " . $ter_nom_dem ;
										echo "<a class='boutonValider' href=\"index.php?op=repA&id=".$rep_id."\";'>Valider</a>" ;
										echo "<a class='boutonRefuserDate' href=\"index.php?op=repRD&id=".$rep_id."\";'>Refuser la date</a>" ;
										echo "<a class='boutonRefuserReport' href=\"index.php?op=repRR&id=".$rep_id."\";'>Refuser le report</a>" ;
									echo "</td>" ;
								echo "</tr>" ;
							}
					echo "</table>" ;
				}
			}
		?>
		<table width="100%">
			<?php
				$sSQLEquipe = "SELECT eq_nom eq_page, eq_id eq_page_id, pou_id, pou_nom, CASE eq_coupe WHEN 0 THEN 'Non' WHEN 1 THEN 'Oui' END eq_coupe, eq_couleur, eq_couleur_ext, eq_jour from " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . ", " . TBL_EVENEMENT . " where eps_eq_id = eq_id and eps_pou_id = pou_id and eps_sai_annee = ".SAISON."  and eq_id = " . $idEquipe . " and pou_eve_id = 2 and pou_eve_id = eve_id ";
				$resultEquipe = $mysqli->query($sSQLEquipe) ;
				//echo $sSQLEquipe ;
				while ($rowEquipe = mysqli_fetch_array($resultEquipe)) {
					extract($rowEquipe) ;
				}
				
				$sSQLColor = "select sai_nb_equipe, sai_nb_montee, sai_nb_descente from " . TBL_SAISON . ", " . TBL_POULE . " where sai_pou_id = pou_id and sai_annee = '" . SAISON . "' and pou_id = '" . $pou_id . "' and pou_eve_id = 2 " ;
				$resultColor = $mysqli->query($sSQLColor) ;
				//echo $sSQLColor	 ;
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
			?>
			<?php
				$sSQLClassement = "select eq_id, eq_nom, coalesce(sum(sco_points)-(select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id),0) pts, " .
							" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_points is not null and mat_sai_annee = '" . SAISON . "') j, " .
							" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp> sco_bc and mat_sai_annee = '" . SAISON . "') g, " .
							" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp= sco_bc and mat_sai_annee = '" . SAISON . "') n, " .
							" (select count(*) from " . TBL_SCORE . ", " . TBL_MATCH . " where sco_eq_id = eq_id and sco_mat_id = mat_id and mat_pou_id = pou_id and sco_bp< sco_bc and mat_sai_annee = '" . SAISON . "') p, " .
							" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . ", " . TBL_POULE . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pou_eve_id = eve_id and pen_type = 'A') nbPen, " .
							" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . ", " . TBL_POULE . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pou_eve_id = eve_id and pen_type = 'F') nbFor, " .
							" (select coalesce(sum(pen_point),0) from " . TBL_PENALITE . ", " . TBL_MATCH . ", " . TBL_POULE . " where pen_eq_id = eq_id and pen_mat_id = mat_id and mat_sai_annee = '" . SAISON . "' and pen_pou_id = pou_id and pou_eve_id = eve_id and pen_type = 'R') nbRep, " .
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
							" and pou_id ='" . $pou_id . "' " .
							" and eve_id = 2 " .
						" group by eq_nom order by pts desc, diff desc, bp desc, eq_nom " ;
				$resultClassement = $mysqli->query($sSQLClassement) ;
				//echo $sSQLClassement ;
				$i=1 ;
				$classement = "" ;
				while ($rowClassement = mysqli_fetch_array($resultClassement)) {
					extract($rowClassement) ;
					if($diff>0) { $diff = "+".$diff ; }
					$eq_nom == $eq_page ? $classEq="equipe" : $classEq="" ;
					/*$classement.= "<TR class='".$classEq."'>" .
						"<TD WIDTH='5%' style='border-left: 5px solid ".$classementColor[$i]."' ALIGN=center>".$i."</TD>" .
						"<TD WIDTH='60%' ALIGN=left><a href='index.php?op=eq&id=".$eq_id."'>".$eq_nom."</a></TD>" .
						"<TD WIDTH='35%' ALIGN=right>".$pts." pts (".$diff.")</TD>" .
					"</TR>" ;*/
					
					$classement.= "<TR class='".$classEq."' id='classement_'>" .
						"<TD WIDTH=15 ALIGN=center style='color: ".$classementColor[$i]."; font-weight: bold;'>".$i."</TD>" .
						"<TD WIDTH=200 ALIGN=left style='border-left: 5px solid ".$classementColor[$i].";'><a href='index.php?op=eq&id=".$eq_id."'>".$eq_nom."</a></TD>" .
						"<TD WIDTH=15 ALIGN=center>".$pts."</TD>" .
						"<TD WIDTH=15 ALIGN=center>".$j."</TD>" .
						"<TD WIDTH=15 ALIGN=center>".$g."</TD>" .
						"<TD WIDTH=15 ALIGN=center>".$n."</TD>" .
						"<TD WIDTH=15 ALIGN=center>".$p."</TD>" .
						"<TD WIDTH=15 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$bp."</TD>" .
						"<TD WIDTH=15 ALIGN=center>".$bc."</TD>" .
						"<TD WIDTH=15 ALIGN=center>".$diff."</TD>" .
						"<TD WIDTH=15 ALIGN=center style='border-left: 2px solid #F0F0FF'>".$nbPen."</TD>" .
						"<TD WIDTH=15 ALIGN=center>".$nbRep."</TD>" .
						"<TD WIDTH=15 ALIGN=center>".$nbFor."</TD>" .
					"</TR>" ;
					
					if($eq_nom == $eq_page) {
						$eq_classement = $i ;
						$eq_point = $pts ;
						$eq_j = $j ;
						$eq_g = $g ;
						$eq_n = $n ;
						$eq_p = $p ;
						$eq_bp = $bp ;
						$eq_bc = $bc ;
						$eq_diff = $diff ;
					}
					$i++;
				}
			?>
			<tr>
				<?php 
					if(isMobile()) { 
						echo "<td align=center valign=top>" ;
					} else {
						echo "<td width='50%' align=center valign=top>" ;
					}
				?>
					<table width="100%">
						<tr>
							<td align=center>
								<table class="tabDetail tabCalendrierEquipe" id="classement">
									<tr id="titre">
										<th colspan=14>
											Classement
											<img src="<?php echo IMG ; ?>down.png" alt="down" height="20" width="20">
											<img src="<?php echo IMG ; ?>right.png" alt="right" height="20" width="20" style="display: none;">
										</th>
									</tr>
									<TR id='classement_'>
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
									<?php echo $classement ; ?>
									<!--tr>
										<td valign=top id='classement_'>
											<TABLE width="100%" cellpadding=2 cellspacing=0 class="tabCalendrierEquipe">
												<?php //echo $classement ; ?>
											</TABLE>
										</td>
									</tr-->
								</table>
							</td>
						</tr>
					</table>
				</td>
				<?php 
					if(isMobile()) { 
						echo "</tr><tr><td align=center valign=top>" ;
					} else {
						echo "<td width='50%' align=center valign=top>" ;
					}
				?>
					<table width='100%'>
						<tr>
							<td align=center>
								<table class="tabDetail" id="detail">
									<tr>
										<th colspan=5>
											D&eacute;tail de l'&eacute;quipe <?php echo $eq_page ; ?>
											<img src="<?php echo IMG ; ?>down.png" alt="down" height="20" width="20">
											<img src="<?php echo IMG ; ?>right.png" alt="right" height="20" width="20" style="display: none;">
										</th>
									</tr>
									<?php 
										echo "<tr id='detail_'>" ;
											echo "<td width=60% colspan=3 align=left>" ;
												echo classement($eq_classement,--$i) . " de la S&eacute;rie " . $pou_nom ;
											echo "</td>" ;
											echo "<td width=20%>" ;
											echo "</td>" ;
											echo "<td width=20% align=right>" ;
												echo $eq_point . " pts (" . $eq_diff . ")" ;
											echo "</td>" ;
										echo "</tr>" ;
										echo "<tr id='detail_'>" ;
											echo "<td width=20%>" ;
											echo "</td>" ;
											echo "<td width=20% align=right>" ;
												echo $eq_g . " matchs gagn&eacute;s" ;
												echo "<br/>" ;
												echo $eq_bp . " buts marqu&eacute;s" ;
											echo "</td>" ;
											echo "<td width=20% align=center valign='top'>" ;
												echo $eq_n . " matchs nuls" ;
											echo "</td>" ;
											echo "<td width=20% align=left>" ;
												echo $eq_p . " matchs perdus" ;
												echo "<br/>" ;
												echo $eq_bc . " buts encaiss&eacute;s" ;
											echo "</td>" ;
											echo "<td width=20%>" ;
											echo "</td>" ;
										echo "</tr>" ;
										echo "<tr id='detail_'>" ;
											echo "<td align=right>" ;
												echo "Couleurs Maillots : " ;
											echo "</td>" ;
											echo "<td align=center colspan=2>" ;
												echo "Domicile : " . infoEquipe($eq_couleur) ;
											echo "</td>" ;
											echo "<td align=center colspan=2>" ;
												echo "Ext&eacute;rieur : " . infoEquipe($eq_couleur_ext) ;
											echo "</td>" ;
										echo "</tr>" ;
										echo "<tr id='detail_'>" ;
											echo "<td align=right>" ;
												echo "Jour Entrainement : " ;
											echo "</td>" ;
											echo "<td align=left colspan=2>" ;
												echo infoEquipe($eq_jour) ;
											echo "</td>" ;
											echo "<td align=center colspan=2>" ;
												echo "Coupe : " . $eq_coupe ;
											echo "</td>" ;
										echo "</tr>" ;
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td align=center>
								<table class="tabDetail" id="responsables">
									<tr>
										<th colspan=3>
											Responsables
											<img src="<?php echo IMG ; ?>down.png" alt="down" height="20" width="20">
											<img src="<?php echo IMG ; ?>right.png" alt="right" height="20" width="20" style="display: none;">
										</th>
									</tr>
									<tr id='responsables_'>
										<td>
											<TABLE width="100%" cellpadding=2 cellspacing=0 class="tabJoueursEquipe">
											<?php
												$sSQLCapitaines = "select jou_nom, jou_mail, jou_tel from " . TBL_EQUIPE_CORRESP . ", " . TBL_JOUEUR . " where ec_jou_id = jou_id and ec_eq_id = " . $idEquipe ;
												$resultCapitaines = $mysqli->query($sSQLCapitaines) ;
												$mails = "" ;
												while ($rowCapitaines = mysqli_fetch_array($resultCapitaines)) {
													extract($rowCapitaines) ;
													echo "<tr>" ;
														echo "<td>".$jou_nom."</td>" ;
														echo "<td><img src='". IMG ."mail.png' alt='mail' height='20'>: " ;
														echo mailCorresp($jou_mail) ;
														echo "</td>" ;
														echo "<td><img src='". IMG ."tel.png' alt='tel' height='20'> : " ; 
														echo telCorresp($jou_tel) ;
														echo "</td>" ;
													echo "</tr>" ;
													$mails .= $jou_mail . ";" ;
												}
											?>
												<tr style="height: 20px">
													<td colspan=3 align=center ><img src="<?php echo IMG ; ?>mail.png" alt="mail" height="20">
														<?php
															if(isCapitaine()) {
																echo "<a href='mailto:".$mails."'>Envoyer un mail &agrave; tous les responsables</a>" ;
															} else {
																echo "<a href='index.php?op=id'>Pour consulter les informations des correspondants, vous devez vous connecter</a>." ;
															}
														?>
													</td>
												</tr>
												<tr style="height: 20px">
													<td colspan=3 align=center ><a href="index.php?op=ics&eq_id=<?php echo $idEquipe ; ?>"><img src="<?php echo IMG ; ?>download_cal.png" alt="cal" height="20">T&eacute;l&eacute;charger le calendrier de l'ann&eacute;e</a></td>
												</tr>
											</TABLE>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td align=center>
								<table class="tabDetail" id="joueurs">
									<tr>
										<th colspan=3>
											<?php
												$sSQLNbJoueurs = "select count(*) nbJoueurs " . 
																	" from " . TBL_EJT . ", " . TBL_JOUEUR . ", " . TBL_TYPE . 
																	" where ejt_jou_id = jou_id " .
																		" and ejt_typ_id = typ_id " .
																		" and ejt_sai_annee = " . SAISON . 
																		" and ejt_eq_id = " . $idEquipe ;
													//echo $sSQLNbJoueurs;
													$resultNbJoueurs = $mysqli->query($sSQLNbJoueurs) ;
													while ($rowNbJoueurs = mysqli_fetch_array($resultNbJoueurs)) {
														extract($rowNbJoueurs) ;
													}
													echo "Joueurs (" . $nbJoueurs . ")" ;
											?>
											<img src="<?php echo IMG ; ?>down.png" alt="down" height="20" width="20" style="display: none;">
											<img src="<?php echo IMG ; ?>right.png" alt="right" height="20" width="20">
										</th>
									</tr>
									<tr id='joueurs_' style='display: none;'>
										<td>
											<TABLE width="100%" cellpadding=2 cellspacing=0 class="tabJoueursEquipe">
												<tr>
												<?php
													$i=0;
													$j=1;
													$sSQLJoueurs = "select typ_id, jou_nom, typ_nom, " .
																		" CASE typ_id WHEN 2 THEN (select eq_nom " . 
																									" from " . TBL_EJT . ", " . TBL_EQUIPE . 
																									" where ejt_eq_id = eq_id " .
																										" and ejt_typ_id = '1' " .
																										" and ejt_sai_annee = " . SAISON . 
																										" and ejt_jou_id = jou_id) " .
																						" else '' end eq_nom, " .
																		" CASE typ_id WHEN 1 THEN '" . COLOR_TITULAIRE . "' " .
																					" WHEN 2 THEN '" . COLOR_JOKER . "' " .
																					" else '' end color_joueur " . 
																	" from " . TBL_EJT . ", " . TBL_JOUEUR . ", " . TBL_TYPE . 
																	" where ejt_jou_id = jou_id " .
																		" and ejt_typ_id = typ_id " .
																		" and ejt_sai_annee = " . SAISON . 
																		" and ejt_eq_id = " . $idEquipe . 
																	" order by typ_id, jou_nom" ;
													//echo $sSQLJoueurs;
													$resultJoueurs = $mysqli->query($sSQLJoueurs) ;
													while ($rowJoueurs = mysqli_fetch_array($resultJoueurs)) {
														extract($rowJoueurs) ;
														if($i++/3==$j) {
															echo "</tr></tr>" ;	
															$j++ ;
														}
														$typ_id==2 ? $nom=$jou_nom . " (" . $eq_nom . ")" : $nom=$jou_nom ;
														echo "<td style='color:".$color_joueur.";'><img src='". IMG ."joueur.png' height='20'>&nbsp;".$nom."</td>" ;
													}
												?>
												</tr>
											</TABLE>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table class="tabDetail" id="match">
									<tr>
										<th align=center>
											Matchs
											<img src="<?php echo IMG ; ?>down.png" alt="down" height="20" width="20" style="display: none;">
											<img src="<?php echo IMG ; ?>right.png" alt="right" height="20" width="20">
										</th>
									</tr>
							<?php
								$sSQL = "SELECT MONTH(cre_date) mois, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
									" mat_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, ter_nom, sco1.sco_bp score_1, sco2.sco_bp score_2, sco1.sco_pen pen_1, sco2.sco_pen pen_2, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, eve_id, " .
									" CASE when mat_statut = 1 then '" . COLOR_JOUE . "' when cre_date < sysdate() then '" . COLOR_ATTENTE . "' when mat_statut = 2 then '" . COLOR_REPORT . "' when mat_statut = 3 then '" . COLOR_ARBITRAGE . "' when cre_date > sysdate() then '" . COLOR_PROGRAMME . "' else '' end style, mat_statut, " .
									" CASE when sysdate() < DATE_ADD(cre_date, INTERVAL -1 DAY) then 'ok' else 'ko' end flagReport " .
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
										" and (e1.eq_id = '" . $idEquipe . "' or e2.eq_id = '" . $idEquipe . "') " .
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
									echo "<tr bgcolor='".$style."' id='match_' style='display: none;'><td align='center'><table width='100%' cellpadding=2 cellspacing=0>" ;
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
									echo "&nbsp;(<a href='index.php?op=eq&id=".$arbId."'>".$arb."</a>" ;
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
											if($flagReport=='ok') {
												echo "<a class='boutonReport' href=\"index.php?op=demRep&id=".$mat_id."&opp=a\";'>Demander Report</a>" ;
											} else {
												echo "<a class='boutonForfait' href=\"index.php?op=forfait&id=".$mat_id."&opp=a\";'>D&eacute;clarer Forfait</a>" ;
											}
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
									$arbId2="";
									$arb2="";	
								}
							?>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table class="tabDetail" id="arbitrage">
									<tr>
										<th align=center>
											Arbitrages
											<img src="<?php echo IMG ; ?>down.png" alt="down" height="20" width="20" style="display: none;">
											<img src="<?php echo IMG ; ?>right.png" alt="right" height="20" width="20">
										</th>
									</tr>
								<?php
									$sSQL = "SELECT MONTH(cre_date) mois, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
										" date_format(cre_date, ' %d '), " .
										" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
										" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
										" mat_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, ter_nom, sco1.sco_bp score_1, sco2.sco_bp score_2, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, eve_id, " .
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
											" and (mat_eq_id_3 = '" . $idEquipe . "' " .
												" or mat_eq_id_4 = '" . $idEquipe . "') " .
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
										echo "<tr bgcolor='".$style."' id='arbitrage_' style='display: none;'><td align='center'><table width='100%' cellpadding=2 cellspacing=0>" ;
										echo "<tr class='".$bgColor."'>" ;
										echo "<td align=center width='40%' class='".$color1."' style='font-size:15px;'><a href='index.php?op=eq&id=".$eqId1."'>".$eq1."</a></td>" ;
										echo "<td align=center width='20%' style='font-weight: bold'>" ;
										if($score_1!="" && $score_1==$score_2 && $eve_id==4) {
											echo $score_1." (".$pen_1.") - (".$pen_2.") ".$score_2 ;
										} else {
											echo $score_1." - ".$score_2 ;
										}
										echo "</td>" ;
										echo "<td align=center width='40%' class='".$color2."' style='font-size:15px;'><a href='index.php?op=eq&id=".$eqId2."'>".$eq2."</a></td>" ;
										echo "</tr><tr class='".$bgColor."'><td align=center colspan='3'>&agrave; ".$ter_nom." le ".$jour ;
										echo "&nbsp;(<a href='index.php?op=eq&id=".$arbId."'>".$arb."</a>" ;
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
										if(isCapitaine() && $style==COLOR_ATTENTE) {
											if($arbId==$_SESSION["eq_id"] || $arbId2==$_SESSION["eq_id"]) {
												echo "<a class='boutonMajScore' href=\"index.php?op=score&id=".$mat_id."&opp=calG\";'>Score</a>" ;
											} else if($eqId1==$_SESSION["eq_id"] || $eqId2==$_SESSION["eq_id"]) {
												echo "<a class='boutonMajScore' href=\"index.php?op=score&id=".$mat_id."&opp=calG\";'>Envoyer Score</a>" ;
											}
										}
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
