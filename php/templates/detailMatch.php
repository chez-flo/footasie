<?php include('templates/bandeau.php') ; ?>

<div class="PagePrincipale">
<?php

	$listeInvite="" ;
	$listeParticipe="" ;
	$listeRefuse="" ;
	$listeConvoque="" ;
	$mailsInvite="" ;
	$mailsParticipe="" ;
	$mailsRefuse="" ;
	$mailsConvoque="" ;
	$mailsTous="" ;
	if(isset($_POST['supprimer'])) {
		extract($_POST) ;
		$sSQL = "SELECT count(*) cpt FROM " . TBL_EVENT . " WHERE eve_id = '" . $idSupp . "' ;" ;
		$result = mysql_query($sSQL) ;
		while ($row = mysql_fetch_array($result)) {
			$cpt = $row['cpt'] ;
		}
		if($cpt==0) {
			$message="Le rendez-vous n'existe pas.<br><br><a href=\"javascript:document.location.href=\"index.php?op=lj&d=".jour()."&m=".mois()."&y=".annee()."\" ;\">Fermer</a>" ;
			include("pages/error.php") ;
		}
		else {
			$sSQL = "DELETE FROM " . TBL_EVENT . " WHERE eve_id = '" . $idSupp . "' ; " ;
			$ret = mysql_query($sSQL) or die(mysql_error());
			$sSQL = "DELETE FROM " . TBL_EVENT_USE . " WHERE aeu_eve_id = '" . $idSupp . "' ; " ;
			$ret = mysql_query($sSQL) or die(mysql_error());
			echo "<script>document.location.href=\"index.php\";</script>" ;
		}
	}
	if(isset($_GET['id'])) {
		$id=$_GET['id'] ;
		
		$sSQL = "SELECT CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
			" date_format(cre_date, ' %d '), " .
			" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
			" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, " .
			" date_format(cre_date, ' %Y')) jour, " .
			" e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, ter_url, mat_id, mat_eq_id_4 arbId2 " .
			" FROM " . TBL_EQUIPE . " e1, " . TBL_EQUIPE . " e2, " . TBL_EQUIPE . " e3, " . TBL_MATCH . ", " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
			" WHERE mat_eq_id_1 = e1.eq_id " .
				" and mat_eq_id_2 = e2.eq_id " .
				" and mat_eq_id_3 = e3.eq_id " .
				" and cre_mat_id = mat_id " .
				" and cre_ter_id = ter_id " .
				" and mat_sai_annee = '" . SAISON . "' " .
				" and mat_id = '" . htmlentities($_GET['id']) . "' " ;
		$result = $mysqli->query($sSQL) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
		}
		$sSQL = "SELECT jou_id, jou_nom, ejm_valid, jou_mail FROM " . TBL_JOUEUR . ", ".TBL_EJM." where ejm_jou_id = jou_id and ejm_mat_id = '".$id."' and ejm_eq_id = '".$_SESSION['eq_id']."' ORDER BY ejm_valid desc, jou_nom" ;
		$result = $mysqli->query($sSQL) ;
		$popup=array('id' => $id) ;
		$nbInvite=0 ;
		$nbParticipe=0 ;
		$nbRefuse=0 ;
		$nbConvoque=0 ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			if($ejm_valid==0) {
				if($jou_id==$_SESSION["id"]) { 
					$listeInvite .= "<b><i>".$jou_nom."&nbsp;</i></b>" ;
				} else {
					$listeInvite .= $jou_nom."&nbsp;" ;
				}
				if(isCapitaine()) {
					$popup=array('idJ' => $jou_id, 'id' => $id) ;
					$listeInvite .= "<img src='img/accepter.png' class='icone main' onClick='".createRedirectStatus("ms","1","dm",$popup)."'/>&nbsp;<img src='img/refuser.png' class='icone main' onClick='".createRedirectStatus("ms","-1","dm",$popup)."'/>" ;
				} else {
					if($jou_id==$_SESSION["id"]) { 
						$listeInvite .= "<img src='img/accepter.png' class='icone main' onClick='".createRedirectStatus("ms","1","dm",$popup)."'/>&nbsp;<img src='img/refuser.png' class='icone main' onClick='".createRedirectStatus("ms","-1","dm",$popup)."'/>" ;
					}
				}
				$listeInvite .= "<br/>" ;
				$mailsInvite .= $jou_mail.";" ;
				$nbInvite++ ;
			}
			elseif($ejm_valid==1) {
				if($jou_id==$_SESSION["id"]) { 
					$listeParticipe .= "<b><i>".$jou_nom."&nbsp;</i></b>" ;
				} else {
					$listeParticipe .= $jou_nom."&nbsp;" ;
				}
				if(isCapitaine()) {
					$popup=array('idJ' => $jou_id, 'id' => $id) ;
					$listeParticipe .= "<img src='img/refuser.png' class='icone main' onClick='".createRedirectStatus("ms","-1","dm",$popup)."'/>" ;
				} else {
					if($jou_id==$_SESSION["id"]) { 
						$listeParticipe .= "<img src='img/refuser.png' class='icone main' onClick='".createRedirectStatus("ms","-1","dm",$popup)."'/>" ;
					}
				}
				$listeParticipe .= "<br/>" ;
				$mailsParticipe .= $jou_mail.";" ;
				$nbParticipe++ ;
			}
			elseif($ejm_valid>1) {
				if($jou_id==$_SESSION["id"]) { 
					$listeConvoque .= "<b><i>".$jou_nom."&nbsp;</i></b>" ;
				} else {
					$listeConvoque .= $jou_nom."&nbsp;" ;
				}
				if(isCapitaine()) {
					$popup=array('idJ' => $jou_id, 'id' => $id) ;
					$listeConvoque .= "<img src='img/refuser.png' class='icone main' onClick='".createRedirectStatus("ms","-1","dm",$popup)."'/>" ;
				} else {
					if($jou_id==$_SESSION["id"]) { 
						$listeConvoque .= "<img src='img/refuser.png' class='icone main' onClick='".createRedirectStatus("ms","-1","dm",$popup)."'/>" ;
					}
				}
				$listeConvoque .= "<br/>" ;
				$mailsConvoque .= $jou_mail.";" ;
				$nbConvoque++ ;
			}
			else {
				if($jou_id==$_SESSION["id"]) { 
					$listeRefuse .= "<b><i>".$jou_nom."&nbsp;</i></b>" ;
				} else {
					$listeRefuse .= $jou_nom."&nbsp;" ;
				}
				if(isCapitaine()) {
					$popup=array('idJ' => $jou_id, 'id' => $id) ;
					$listeRefuse .= "<img src='img/accepter.png' class='icone main' onClick='".createRedirectStatus("ms","1","dm",$popup)."'/>" ;
				} else {
					if($jou_id==$_SESSION["id"]) { 
						$listeRefuse .= "<img src='img/accepter.png' class='icone main' onClick='".createRedirectStatus("ms","1","dm",$popup)."'/>" ;
					}
				}
				$listeRefuse .= "<br/>" ;
				$mailsRefuse .= $jou_mail.";" ;
				$nbRefuse++ ;
			}
			$mailsTous .= $jou_mail.";" ;
		}
		$corpMail="?subject=Invitation Ev&eacute;nement&body=Vous avez &eacute;t&eacute; invit&eacute; &agrave; un &eacute;v&eacute;nement.%0D%0A%0D%0AConnectez-vous &agrave; ".PATH."index.php?op=dm%26id=".$mat_id." pour y acc&eacute;der." ;
	}
	if(isset($_POST['comOk']) && $_POST['commentaire'] != "") {
		$oldComment = "" ;
		$sSQL = "select com_commentaire from ".TBL_COMMENTAIRE." where com_mat_id = '" . $id . "' and com_jou_id = '" . $_SESSION["id"] . "' and com_eq_id = '" . $_SESSION["eq_id"] . "' order by com_id desc LIMIT 0 , 1 ;" ;
		$result = $mysqli->query($sSQL) ;
		while ($row = mysqli_fetch_array($result)) {
			$oldComment = $row['com_commentaire'] ;
		}
		if($oldComment!=$_POST['commentaire']) {
			$sSQL = "INSERT INTO  ".TBL_COMMENTAIRE." (com_id, com_mat_id, com_eq_id, com_jou_id, com_commentaire, com_date) VALUES (NULL ,  '" . $id . "',  '" . $_SESSION["eq_id"] . "',  '" . $_SESSION["id"] . "',  '".str_replace("'", "''",str_replace("\'", "'",creatUrl($_POST['commentaire'])))."', '".date('Y-m-d H:i:s', time())."') ;" ;
			$ret = $mysqli->query($sSQL) ;
		}
	}
	if(isset($_POST['telecharger'])) {
		createCalendar($id, "cal".$id.".ics") ;
	}
?>
	<Form name="form1" method='POST' action='code.php'>
		<input type="hidden" id="mat_id" name="mat_id" value="<?php echo $id ; ?>"/>
		<table align="center" cellpadding='0' cellspacing='0'>
			<tr><td align="center" class="titreDetail">
				<?php 
					if($eqId1!=$_SESSION['eq_id'] && $eqId2!=$_SESSION['eq_id']) {
						echo "Arbitrage de " ; 
					}
					echo $eq1 . " vs. " . $eq2 ; 
				?>
			</td></tr>
			<tr><td align="center" class="sousTitreDetail"><?php echo $jour . " &agrave; " . $ter_nom ; ?></td></tr>
			<?php if(isCapitaine()) { ?>
			<tr><td align="center" style="padding-top: 10px;"><a class='boutonInviteJoueur' href='index.php?op=cj&id=<?php echo $id ;?>'>Convoquer les joueurs pour le match</a></td></tr>
			<?php } ?>
		</table>
		<table align="center">
			<tr valign="top">
				<td>
					<table colspan="4" cellpadding="0" cellspacing="0" align="center" class="tabJoueur">
						<tr>
							<td class="center" colspan="4" <?php if(isCapitaine()) { echo "height='45px'" ; }?>>
								<b>Joueurs Invit&eacute;s&nbsp;<a href='mailto:<?php echo $mailsTous.$corpMail ; ?>'><img src='img/mail_.png' class="icone"></a></b>
								<?php if(isCapitaine()) {
									echo "<b><a class='boutonInviteJoueur' href=\"index.php?op=dj&id=".$id."\";'>+</a></b>" ;
								} ?>
							</td>
						</tr>
						<tr>
							<td class="center bgParticipe"><b>Convoqu&eacute;s (<?php echo $nbConvoque ; ?>)&nbsp;<a href='mailto:<?php echo $mailsConvoque.$corpMail ; ?>'><img src='img/mail_.png' class="icone"></a></b></td>
						</tr>
						<tr>
							<td class="bgParticipe listeJoueur participe"><?php echo $listeConvoque ; ?></td>
						</tr>
						<tr>
							<td class="center bgParticipe"><b>Pr&eacute;sents (<?php echo $nbParticipe ; ?>)&nbsp;<a href='mailto:<?php echo $mailsParticipe.$corpMail ; ?>'><img src='img/mail_.png' class="icone"></a></b></td>
						</tr>
						<tr>
							<td class="bgParticipe listeJoueur participe"><?php echo $listeParticipe ; ?></td>
						</tr>
						<tr>
							<td class="center bgAttente"><b>En attente (<?php echo $nbInvite ; ?>)&nbsp;<a href='mailto:<?php echo $mailsInvite.$corpMail ; ?>'><img src='img/mail_.png' class="icone"></a></b></td>
						</tr>
						<tr>
							<td class="bgAttente listeJoueur attente"><?php echo $listeInvite ; ?></td>
						</tr>
						<tr>
							<td class="center bgRefuse"><b>Absents (<?php echo $nbRefuse ; ?>)&nbsp;<a href='mailto:<?php echo $mailsRefuse.$corpMail ; ?>'><img src='img/mail_.png' class="icone"></a></b></td>
						</tr>
						<tr>
							<td class="bgRefuse listeJoueur refuse"><?php echo $listeRefuse ; ?></td>
						</tr>
					</table>
				</form>
			</td>
		<?php if(isMobile()) { echo "</tr><tr>" ; } ?>
			<td>
				<Form name="form" method='POST' action='#'>
					<input type="hidden" id="comOk" name="comOk" value="ok"/>
					<table align="center" style="margin-bottom: 20px" class="center" width="100%">
						<tr>
							<th colspan="2">Forum</th>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<input type="text" name="commentaire" id="commentaire" onKeyPress="if (event.keyCode == 13) submit();" class="col600" value="<?php echo isset($ejm_commentaire) ? $ejm_commentaire : "" ;?>"/>
							</td>
						</tr>
						<?php 
							$sSQL = $sSQL = "SELECT com_id, jou_id, com_mat_id, jou_nom, com_commentaire, DATE_FORMAT(com_date, '%d/%m/%Y &agrave; %Hh%i') com_date_format " ;
								$sSQL .= " FROM " . TBL_COMMENTAIRE . ", " . TBL_JOUEUR  ;
								$sSQL .= " WHERE com_jou_id = jou_id and com_mat_id = '".$id."' and com_eq_id = '".$_SESSION['eq_id']."' order by com_date desc ;" ;
							$result = $mysqli->query($sSQL) ;
							while ($row = mysqli_fetch_array($result)) { 
								extract($row) ;
								if($com_commentaire!="") {
									echo "<tr valign='top' style='background-color: white;'>" ;
									if($jou_id==$_SESSION["id"]) {
										$popup=array('id' => $id, 'com_id' => $com_id) ;
										echo "<td width='35%' class='left'><img src='img/refuser.png' width='14px' class='main' onClick='".createRedirectStatus("sc","","dm",$popup)."'/>&nbsp;".$jou_nom." : <br/><i>(".$com_date_format.")</i></td>" ;
									} else {
										echo "<td width='35%' class='left'>".$jou_nom." : <br/><i>(".$com_date_format.")</i></td>" ;
									}
									echo "<td width='65%' class='left'>".$com_commentaire."</td>" ;
									echo "</tr>" ;
								}
							}
						?>
					</table>
				</Form>
			</td>
		</tr>
	</table>
</div>