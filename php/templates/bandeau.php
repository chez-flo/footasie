<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<!--meta name="viewport" content="width=device-width, initial-scale=1.0"-->
		<meta name='description' content='ASIE Football Sophia-Antipolis'>
		<meta name='keywords' content='ASIE, football, Sophia Antipolis'>
		<link rel='stylesheet' href='<?php echo CSS ; ?>style.css'>
		<script src="<?php echo JS ; ?>ajax.js"></script>
		<script src="<?php echo JS ; ?>function.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<title>ASIE - Football <?php echo NOM_SAISON ; ?> - <?php echo $titre; ?></title>
		<SCRIPT LANGUAGE="javascript"><!--
			win_open = false
		--></SCRIPT>
	</head>
	<body onload="blink_show();">
		<script>
			$(document).ready(function(){
				$("#menuChampionnat li").click(function(){
					$("#menuChampionnat_	").toggle("fast");
					$('img:visible', this).hide().siblings().show();
				});
			});
			$(document).ready(function(){
				$("#menuCoupe li").click(function(){
					$("#menuCoupe_	").toggle("fast");
					$('img:visible', this).hide().siblings().show();
				});
			});
			$(document).ready(function(){
				$("#menuCompte li").click(function(){
					$("#menuCompte_	").toggle("fast");
					$('img:visible', this).hide().siblings().show();
				});
			});
		</script>
		<TABLE WIDTH=100% ALIGN='center' cellpadding=0 cellspacing=0>
			<TR>
				<TD width=15% ALIGN='left' style='position: fixed;'>
					<nav>
						<?php if(isMobile()) { ?>
							<select id="menuSelect" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
								<option value='<?php echo optionRedirect("a"); ?>'>Accueil</option>
								<?php
									$sSQLChampionnats = "select pou_nom from " . TBL_SAISON . ", " . TBL_POULE . " where sai_pou_id = pou_id and sai_annee = '" . SAISON . "' and pou_eve_id = 2 order by pou_nom" ;
									$resultChampionnats = $mysqli->query($sSQLChampionnats) ;
									//echo $sSQLChampionnats	 ;
									if(mysqli_num_rows($result)!=0) {
										echo "<option disabled>Championnat</option>" ;
										while ($rowChampionnats = mysqli_fetch_array($resultChampionnats)) {
											extract($rowChampionnats) ;
											$s=array('s' => $pou_nom) ;
											echo "<option value='".optionRedirect("cal",$s)."'>S&eacute;rie " . $pou_nom . "</option>" ;
										}
									}
									$sSQLCoupe = "select count(*) cptC from " . TBL_SAISON . ", " . TBL_POULE . " where sai_pou_id = pou_id and sai_annee = '" . SAISON . "' and pou_eve_id = 3" ;
									$resultCoupe = $mysqli->query($sSQLCoupe) ;
									//echo $sSQLCoupe	 ;
									$rowCoupe = mysqli_fetch_array($resultCoupe) ;
									extract($rowCoupe) ;
									$sSQLCoupeF = "select count(*) cptF from " . TBL_SAISON . ", " . TBL_POULE . " where sai_pou_id = pou_id and sai_annee = '" . SAISON . "' and pou_eve_id = 4" ;
									$resultCoupeF = $mysqli->query($sSQLCoupeF) ;
									//echo $sSQLCoupeF	 ;
									$rowCoupeF = mysqli_fetch_array($resultCoupeF) ;
									extract($rowCoupeF) ;
									if($cptC>0 || $cptF>0) {
										echo "<option disabled>ELOCAR Cup</option>" ;
										if($cptC>0) {
											echo "<option value='".optionRedirect("coupe")."'>Phases de poule</option>" ;
										}
										if($cptF>0) {
											echo "<option value='".optionRedirect("coupeF")."'>Phases Finales</option>" ;
										}
									}
									$sSQLCal = "select count(*) cptCal from " . TBL_SAISON . " where sai_annee = '" . SAISON . "'" ;
									$resultCal = $mysqli->query($sSQLCal) ;
									//echo $sSQLCal	 ;
									$rowCal = mysqli_fetch_array($resultCal) ;
									extract($rowCal) ;
									if($cptCal>0) {
										echo "<option value='".optionRedirect("cal")."'>Calendrier</option>" ;
									}
								?>
								<option value='<?php echo optionRedirect("reg"); ?>'>R&egrave;glement</option>
								<option value='<?php echo optionRedirect("stade"); ?>'>Stades</option>
								<option value='<?php echo optionRedirect("stat"); ?>'>Statistiques</option>
								<?php 
									if(isAdmin()) {
										echo "<option value='".optionRedirect("em")."'>Mails</option>" ;
									}
									if(!isAdmin() && !isCapitaine() && !isJoueur()) { 
										echo "<option value='".optionRedirect("id")."'>Identification</option>" ;
									} else {
										$sSQLEq = "select eq_nom from " . TBL_EQUIPE . " where eq_id = '" . $_SESSION['eq_id'] . "'" ;
										$resultEq = $mysqli->query($sSQLEq) ;
										//echo $sSQLCal	 ;
										$rowEq = mysqli_fetch_array($resultEq) ;
										extract($rowEq) ;
										$s=array('id' => $_SESSION['eq_id']) ;
										echo "<option disabled>Espace &Eacute;quipe</option>" ;
										echo "<option>".$_SESSION["nb_eq"]."</option>" ;
										echo "<option value='".optionRedirect('eq', $s)."'>".$eq_nom."</option>" ;
										//echo "<option value='".optionRedirect("lm")."'>Liste des matchs</option>" ;
										echo "<option value='".optionRedirect("info")."'>Infos Personnelles</option>" ;
										if(isCapitaine()) {
											echo "<option value='".optionRedirect("infoE")."'>Infos &Eacute;quipe</option>" ;
											echo "<option value='".optionRedirect("infoS")."'>Infos Soci&eacute;t&eacute;</option>" ;
											echo "<option value='".optionRedirect('geq')."'>Gestion des joueurs</option>" ;
										}
										echo "<option value='".optionRedirect("logout")."'>D&eacute;connexion</option>" ;
									}
								?>
							</select>
						<?php } else { ?>
							<ul>
								<li class="menu"><?php if(!$menuFlag || isMobile()) { ?><img src="<?php echo IMG ; ?>menu.png" alt="down" height="30" width="30">Menu<?php } ?>
									<ul class="submenu" <?php if($menuFlag && !isMobile()) { echo "style='display: inline-block ; position: absolute; top: 100%; left: 0px; padding: 0px; z-index: 100000;'" ; } ?>>
									
										<li onclick='<?php echo createRedirect("a"); ?>'><a href="<?php echo PATH."index.php?op=a" ; ?>">Accueil</a></li>
										<?php
											$sSQLChampionnats = "select pou_nom from " . TBL_SAISON . ", " . TBL_POULE . " where sai_pou_id = pou_id and sai_annee = '" . SAISON . "' and pou_eve_id = 2 order by pou_nom" ;
											$resultChampionnats = $mysqli->query($sSQLChampionnats) ;
											//echo $sSQLChampionnats	 ;
											if(mysqli_num_rows($result)!=0) {
												echo "<span id='menuChampionnat'><li><a href='#'>Championnat" ;
												echo "<img src='".IMG."down.png' alt='down' height='20' width='20' style='display: none;'>" ;
												echo "<img src='".IMG."right.png' alt='right' height='20' width='20'></a>" ;
												echo "<ul id='menuChampionnat_' style='display:none ;'	>" ;
												while ($rowChampionnats = mysqli_fetch_array($resultChampionnats)) {
													extract($rowChampionnats) ;
													$s=array('s' => $pou_nom) ;
													echo "<li onclick='".createRedirect("cal",$s)."'><a href='".PATH."index.php?op=cal&s=".$pou_nom."'>S&eacute;rie " . $pou_nom . "</a></li>" ;
												}
												echo "</ul></li></span>" ;
											}
											$sSQLCoupe = "select count(*) cptC from " . TBL_SAISON . ", " . TBL_POULE . " where sai_pou_id = pou_id and sai_annee = '" . SAISON . "' and pou_eve_id = 3" ;
											$resultCoupe = $mysqli->query($sSQLCoupe) ;
											//echo $sSQLCoupe	 ;
											$rowCoupe = mysqli_fetch_array($resultCoupe) ;
											extract($rowCoupe) ;
											$sSQLCoupeF = "select count(*) cptF from " . TBL_SAISON . ", " . TBL_POULE . " where sai_pou_id = pou_id and sai_annee = '" . SAISON . "' and pou_eve_id = 4" ;
											$resultCoupeF = $mysqli->query($sSQLCoupeF) ;
											//echo $sSQLCoupeF	 ;
											$rowCoupeF = mysqli_fetch_array($resultCoupeF) ;
											extract($rowCoupeF) ;
											if($cptC>0 || $cptF>0) {
												echo "<span id='menuCoupe'><li><a href='#'>ELOCAR Cup" ;
												echo "<img src='".IMG."down.png' alt='down' height='20' width='20' style='display: none;'>" ;
												echo "<img src='".IMG."right.png' alt='right' height='20' width='20'></a>" ;
												echo "<ul id='menuCoupe_' style='display:none ;'>" ;
												if($cptC>0) {
													echo "<li onclick='".createRedirect("coupe")."'><a href='".PATH."index.php?op=coupe'>Phases de poule</a></li>" ;
												}
												if($cptF>0) {
													echo "<li onclick='".createRedirect("coupeF")."'><a href='".PATH."index.php?op=coupeF'>Phases Finales</a></li>" ;
												}
												echo "</ul></li></span>" ;
											}
											$sSQLCal = "select count(*) cptCal from " . TBL_SAISON . " where sai_annee = '" . SAISON . "'" ;
											$resultCal = $mysqli->query($sSQLCal) ;
											//echo $sSQLCal	 ;
											$rowCal = mysqli_fetch_array($resultCal) ;
											extract($rowCal) ;
											if($cptCal>0) {
												echo "<li onclick='".createRedirect("cal")."'><a href='".PATH."index.php?op=cal'>Calendrier</a></li>" ;
											}
										?>
										<li onclick='<?php echo createRedirect("ins"); ?>'><a href="<?php echo PATH."index.php?op=ins" ; ?>">Inscriptions</a></li>
										<li onclick='<?php echo createRedirect("reg"); ?>'><a href="<?php echo PATH."index.php?op=reg" ; ?>">R&egrave;glement</a></li>
										<li onclick='<?php echo createRedirect("stade"); ?>'><a href="<?php echo PATH."index.php?op=stade" ; ?>">Stades</a></li>
										<li onclick='<?php echo createRedirect("stat"); ?>'><a href="<?php echo PATH."index.php?op=stat" ; ?>">Statistiques</a></li>
										<?php 
											if(isAdmin()) {
												echo "<li onclick='".createRedirect("em")."'><a href='".PATH."index.php?op=em'>Mails</a></li>" ;
											}
											if(!isAdmin() && !isCapitaine() && !isJoueur()) { 
												echo "<li onclick='".createRedirect("id")."'><a href='".PATH."index.php?op=id'>Identification</a></li>" ;
											} else {
												$sSQLEq = "select eq_nom from " . TBL_EQUIPE . " where eq_id = '" . $_SESSION['eq_id'] . "'" ;
												$resultEq = $mysqli->query($sSQLEq) ;
												//echo $sSQLCal	 ;
												$rowEq = mysqli_fetch_array($resultEq) ;
												extract($rowEq) ;
												$s=array('id' => $_SESSION['eq_id']) ;
												echo "<span id='menuCompte'><li><a href='#'>Espace &Eacute;quipe" ;
												echo "<img src='".IMG."down.png' alt='down' height='20' width='20' style='display: none;'>" ;
												echo "<img src='".IMG."right.png' alt='right' height='20' width='20'></a>" ;
												echo "<ul id='menuCompte_' style='display:none ;'>" ;
												if($_SESSION["nb_eq"]>1) {
													echo "<li onclick='".createRedirect('ch_eq', $s)."'><a href='".PATH."index.php?op=ch_eq&id=".$_SESSION['eq_id']."'>Changer d'&eacute;quipe</a></li>" ;
												}
												echo "<li onclick='".createRedirect('eq', $s)."'><a href='".PATH."index.php?op=eq&id=".$_SESSION['eq_id']."'>".$eq_nom."</a></li>" ;
												//echo "<li onclick='".createRedirect("lm")."'><a href='".PATH."index.php?op=lm'>Liste des matchs</a></li>" ;
												echo "<li onclick='".createRedirect("info")."'><a href='".PATH."index.php?op=info'>Infos Personnelles</a></li>" ;
												if(isCapitaine()) {
													echo "<li onclick='".createRedirect("infoE")."'><a href='".PATH."index.php?op=infoE'>Infos &Eacute;quipe</a></li>" ;
													echo "<li onclick='".createRedirect("infoS")."'><a href='".PATH."index.php?op=infoS'>Infos Soci&eacute;t&eacute;</a></li>" ;
													echo "<li onclick='".createRedirect('geq')."'><a href='".PATH."index.php?op=geq'>Gestion des joueurs</a></li>" ;
												}
												echo "<li onclick='".createRedirect("logout")."'><a href='".PATH."index.php?op=logout'>D&eacute;connexion</a></li>" ;
												echo "</ul></li></span>" ;
											}
										?>
										
									</ul>
								</li>
							</ul>
						<?php } ?>
					</nav>
				</TD>
				<TD width=70% ALIGN='center' VALIGN='middle'>
					<table cellpadding=0 cellspacing=0>
						<tr>
							<td align="left">
								<a class="mailto" href="mailto:foot@asiesophia.fr">
									<img src="<?php echo IMG ; ?>contact.png" style="height:70px;" alt="Contactez-nous"/>
								</a>
							</td>
							<td align="center">
								<FONT SIZE=+3>
									ASIE - Football <?php echo NOM_SAISON ; ?>
									<BR/>
									<?php echo $titre; ?>
								</FONT>
							</td>
							<td align="right">
								<a href="http://www.asiesophia.fr/" target="_blank">
									<img src="<?php echo IMG ; ?>contact.png" style="height:70px;" alt="Contactez-nous"/>
								</a>
							</td>
						</tr>
						<tr>
							<td colspan="3" align="center">
								<iframe src="https://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FChampionnatASIE&amp;width=500&amp;colorscheme=light&amp;show_faces=false&amp;stream=false&amp;header=false&amp;height=70" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:70px;" allowTransparency="true"></iframe>
								<a href="https://www.elocar.fr/" target="_blank">
									<img src="<?php echo IMG ; ?>elocar.jpeg" style="height:68px; border: 1px #EBEDF0 solid;" alt="Elocar"/>
								</a>
							</td>
						</tr>
					</table>	
				</TD>
				<TD width=15% ALIGN='right' VALIGN='top' style='position: absolute;'>
					<a href="http://www.asiesophia.fr/" target="_blank">
						<IMG SRC='<?php echo IMG ; ?>asie.png'>
					</a>
				</TD>
			</TR>
		</TABLE>