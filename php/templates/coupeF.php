<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
<CENTER>
<TABLE CELLPADDING=0 CELLSPACING=0>	
<TR><TD width='800' align=justify>
<center><b>R&eacute;glement des phases finales de la ELOCAR Cup <?php echo NOM_SAISON ; ?></b></center>
<p>Les arbitrages se font désormais à 2 équipes afin qu'il n'y ait plus de phase finale sans arbitre. Tout d&eacute;faut d'arbitrage entra&icirc;nera donc un handicap de 3 buts lors de la rencontre de la même phase.<br><br>
En cas d'&eacute;galit&eacute;, les tirs au but s'appliquent. Un premi&egrave;re phase de 5 tirs au but puis si &eacute;galit&eacute;, un par un.<br>
Le tireur ne peut frapper la balle qu'une seule fois. En particulier, il ne peut reprendre la balle apr&egrave;s rebond sur la barre transversale, un poteau ou sur le gardien.<br>
Les r&egrave;gles classiques des tirs au but s'appliquent <a href="http://fr.wikipedia.org/wiki/Tirs_au_but#Proc.C3.A9dure">Wikipedia</a></P>
<center>
</td></tr>
</table>
<?php
	$sSQL = "SELECT pou_nom, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
		" date_format(cre_date, ' %d '), " .
		" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
		" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
		" mat_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, ter_nom, sco1.sco_bp score1, sco2.sco_bp score2, sco1.sco_pen pen1, sco2.sco_pen pen2, " .
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
			" and eve_id = 4 " .
		" ORDER BY mat_id " ;
	$result = $mysqli->query($sSQL) ;
	//echo $sSQL ;
	$poule_prec = "" ;
	$flagPoule = false ;
	$i=1;
	$j=0 ;
	while ($row = mysqli_fetch_array($result)) {
		extract($row) ;
		extract(color($score1, $score2, $pen1, $pen2)) ;
		// if($pou_nom!=$poule_prec) {
			// echo" <table cellpadding=0 cellspacing=0 style='margin-bottom:10px ;'><tr>" ;
			// echo "<td align=center id=".$pou_nom."><b>Phase Finale ".$pou_nom."</b></td>" ;
			// echo "</tr><tr>" ;
			// echo "<table cellpadding=0 cellspacing=0 style='margin-bottom:10px ;' border=0>" ;
			// $i=1 ;
		// }
		// echo "<tr style='border:none; height:15px ;' bgcolor='".$style."'>" ;
		// echo "<td width='15' align='right' style='border:none; font-weight: bold ; padding-right: 5px;'>".$i."</td>" ;
		// echo "<td width='120' align='left' style='border:none'>".$jour."</td>" ;
		// echo "<td width='200' align='right' style='border:none' class='".$color1."'>".$eq1."</td>" ;
		// if($score1!="" && $score1==$score2) {
			// echo "<td width='100' align='center' style='border:none'>".$score1." (".$pen1.") - (".$pen2.") ".$score2."</td>" ;
		// } else {
			// echo "<td width='100' align='center' style='border:none'>".$score1." - ".$score2."</td>" ;
		// }
		// echo "<td width='200' align='left' style='border:none' class='".$color2."'>".$eq2."</td>" ;
		// echo "<td width='200' align='left' style='border:none'>".$arb ;
			// $sSQLArb = "select * from f_penalite where pen_mat_id = " . $mat_id . " and pen_eq_id = " . $arbId ;
			// $resultArb = $mysqli->query($sSQLArb) ;
			// while ($rowArb = mysqli_fetch_array($resultArb)) {
				// extract($rowArb) ;
				// echo "<sup>" . $pen_type . "</sup>" ;
			// }
		// echo "</td>" ;
		// echo "<td width='70' align='left' style='border:none'>".$ter_nom."</td>" ;
		if($pou_nom=="Finale") {
			$flagPouleAct = false ;
		} else {
			$flagPouleAct = true ;
		}
		if($pou_nom!=$poule_prec) {
			if($flagPoule) {
				echo "</TABLE>" .
								"</td>" .
							"</tr>" ;
			}
			if($flagPouleAct) {
				echo "</table>" .
				"<table cellpadding=0 cellspacing=0 class='tabCoupeF' id='".$j."' style='margin-top: 10px'>" .
					"<tr>" .
						"<th align=center >" .
						"Phase Finale ".$pou_nom .
						"<img src='". IMG ."down.png' alt='down' height='20' width='20' style='display: none;'>" .
								"<img src='". IMG ."right.png' alt='right' height='20' width='20'>" .
								"</th>" .
								"</tr>" .
								"<tr id='".$j."_' style='display: none;'>" .	
									"<td align=center >" .
										"<table cellpadding=2 cellspacing=0 style='margin-bottom:10px ;' border=0 class='tabCalendrierEquipe'>" ;
			}
			else {
				echo "<table cellpadding=0 cellspacing=0 class='tabCoupeF' id='".$j."' style='margin-top: 10px'>" .
					"<tr>" .
						"<th align=center >" .
						"Phase Finale ".$pou_nom .
						"<img src='". IMG ."down.png' alt='down' height='20' width='20'>" .
								"<img src='". IMG ."right.png' alt='right' height='20' width='20' style='display: none;'>" .
								"</th>" .
								"</tr>" .
								"<tr id='".$j."_'>" .	
								"<td align=center >" .
									"<table cellpadding=2 cellspacing=0 style='margin-bottom:10px ;' border=0 class='tabCalendrierEquipe'>" ;
						
			}
			$i=1 ;	
			$flagPoule = true ;
			$idPhase[$j] = $j ;
			$j++;
		}
		echo "<tr style='border:none; height:15px ;'>" .
				"<td width='' align='right' style='border-left: 5px solid ".$style."; font-weight: bold ; padding-right: 5px;'>".$i."</td>" .
				"<td width='' align='left' style='border:none'>".$jour."</td>" .
				"<td width='' align='right' style='border:none' class='".$color1."'><a href='index.php?op=eq&id=".$eqId1."'>".$eq1."</a></td>" ;
		if($score1!="" && $score1==$score2) {
			echo "<td width='' align='center' style='border:none'>".$score1." (".$pen1.") - (".$pen2.") ".$score2."</td>" ;
		} else {
			echo "<td width='' align='center' style='border:none'>".$score1." - ".$score2."</td>" ;
		}
		echo "<td width='' align='left' style='border:none' class='".$color2."'><a href='index.php?op=eq&id=".$eqId2."'>".$eq2."</a></td>" .
				"<td width='' align='left' style='border:none'><a href='index.php?op=eq&id=".$arbId."'>".$arb."</a>" ;
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
				"<td width='' align='left' style='border:none'>".$ter_nom."</td>" ;
			echo "</tr>"  ;
		
		$poule_prec = $pou_nom ;
		$i++ ;
	}
						echo "</TABLE>" .
								"</td>" .
							"</tr>" ;
				echo "</table>" ;
?>
</tr></table></td></tr></table>
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
