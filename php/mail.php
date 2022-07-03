<?php 
	function envoiMailReport($mat_id, $cre_id) {
		global $mysqli ;
		$sSQL = "select distinct(jou_mail) jou_mail from ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3, ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and ec_eq_id in (e1.eq_id, e2.eq_id, e3.eq_id) " .
					" and ec_jou_id = jou_id " .
					" and mat_id = '". $mat_id ."' " ;
		//echo $sSQL . "<br/>" ;
		$dest = "" ;
		$result = $mysqli->query($sSQL) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$dest .= $jou_mail . ";" ;
		}
		//echo $dest . "<br/>" ;
		$sSQLEquipes = "select e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, ".
							"CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jour " .
						" from ". TBL_TERRAIN .", ". TBL_CRENEAU .", ". TBL_MATCH .", ". TBL_POULE .", ". TBL_EVENEMENT .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3 " .
							" where mat_eq_id_1 = e1.eq_id " .
							" and mat_eq_id_2 = e2.eq_id " .
							" and mat_eq_id_3 = e3.eq_id " .
							" and mat_id = cre_mat_id " .
							" and pou_eve_id = eve_id " .
							" and mat_pou_id = pou_id " .
							" and ter_id = cre_ter_id " .
							" and mat_id = '". $mat_id ."' " ;
		//echo $sSQLEquipes . "<br/>" ;
		$resultEquipes = $mysqli->query($sSQLEquipes) ;
		while ($rowEquipes = mysqli_fetch_array($resultEquipes)) {
			extract($rowEquipes) ;
		}
		$sSQLCreneau = "select CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jourAvant, ter_nom terAvant " .
						" from ". TBL_TERRAIN .", ". TBL_CRENEAU .
							" where cre_id = '". $cre_id ."' " .
							" and ter_id = cre_ter_id " ;
		//echo $sSQLCreneau . "<br/>" ;
		$resultCreneau = $mysqli->query($sSQLCreneau) ;
		while ($rowCreneau = mysqli_fetch_array($resultCreneau)) {
			extract($rowCreneau) ;
		}
		
		$sujet = "[Foot Asie] Modification de calendrier - " . $pou_nom ;
		$texte = "Bonjour,<br/><br/>le match ".$eq1." contre ".$eq2.", arbitr&eacute; par ".$arb."<br/>pr&eacute;vu le ".$jourAvant." &agrave; ".$terAvant." est d&eacute;plac&eacute; au ".$jour." &agrave; ".$ter_nom."." ;
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $adresse_exp\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailDemReport($mat_id, $eq_id, $cre_libre, $report_text) {
		global $mysqli ;
		$sSQL = "select distinct(jou_mail) jou_mail from ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3, ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and ec_eq_id in (e1.eq_id, e2.eq_id, e3.eq_id) " .
					" and ec_jou_id = jou_id " .
					" and mat_id = '". $mat_id ."' " ;
		//echo $sSQL . "<br/>" ;
		$dest = "" ;
		$result = $mysqli->query($sSQL) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$dest .= $jou_mail . ";" ;
		}
		
		$sSQLEq = "select eq_nom from ". TBL_EQUIPE .
					" where eq_id = '". $eq_id ."' " ;
		//echo $sSQLEq . "<br/>" ;
		//$dest = "" ;
		$resultEq = $mysqli->query($sSQLEq) ;
		while ($rowEq = mysqli_fetch_array($resultEq)) {
			extract($rowEq) ;
		}
		//echo $dest . "<br/>" ;
		$sSQLEquipes = "select e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, ".
							"CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jour " .
						" from ". TBL_TERRAIN .", ". TBL_CRENEAU .", ". TBL_MATCH .", ". TBL_POULE .", ". TBL_EVENEMENT .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3 " .
							" where mat_eq_id_1 = e1.eq_id " .
							" and mat_eq_id_2 = e2.eq_id " .
							" and mat_eq_id_3 = e3.eq_id " .
							" and mat_id = cre_mat_id " .
							" and pou_eve_id = eve_id " .
							" and mat_pou_id = pou_id " .
							" and ter_id = cre_ter_id " .
							" and mat_id = '". $mat_id ."' " ;
		//echo $sSQLEquipes . "<br/>" ;
		$resultEquipes = $mysqli->query($sSQLEquipes) ;
		while ($rowEquipes = mysqli_fetch_array($resultEquipes)) {
			extract($rowEquipes) ;
		}
		$sujet = "[Foot Asie] Demande de report - " . $pou_nom ;
		$texte = "Bonjour,<br/><br/>".$eq_nom." demande le report du match entre ".$eq1." et ".$eq2.", arbitr&eacute; par ".$arb." pr&eacute;vu le ".$jour." &agrave; ".$ter_nom." pour la raison suivante :<br/>" . $report_text . "<br/><br/>" ;
		if($cre_libre=="0") {
			$texte .= "Aucun cr&eacute;neau n'a &eacute;t&eacute; propos&eacute; pour le moment." ;
			$texte .= "<br/><br/>Merci de prendre en compte la demande." ;
		} else {
			$sSQLCreneau = "select CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jourProp, ter_nom terProp " .
						" from ". TBL_TERRAIN .", ". TBL_CRENEAU .
							" where cre_id = '". $cre_libre ."' " .
							" and ter_id = cre_ter_id " ;
			//echo $sSQLCreneau . "<br/>" ;
			$resultCreneau = $mysqli->query($sSQLCreneau) ;
			while ($rowCreneau = mysqli_fetch_array($resultCreneau)) {
				extract($rowCreneau) ;
			}
			$texte .= "Le cr&eacute;neau suivant a &eacute;t&eacute; propos&eacute; : " . $jourProp . " &agrave; " . $terProp ;
			$texte .= "<br/><br/>Merci de vous connecter &agrave; votre espace personnel afin de valider ou refuser la date (ou la demande le cas &eacute;ch&eacute;ant) sur la page de votre &eacute;quipe." ;
		}
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $adresse_exp\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		/*echo $dest . "<br/>" ;
		echo $sujet . "<br/>" ;
		echo $texte . "<br/>" ;
		echo $headers . "<br/>" ;*/
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailRelance($mat_id, $relance_text) {
		global $mysqli ;
		$sSQL = "select distinct(jou_mail) jou_mail from ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3, ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and ec_eq_id in (e1.eq_id, e2.eq_id, e3.eq_id) " .
					" and ec_jou_id = jou_id " .
					" and mat_id = '". $mat_id ."' " ;
		//echo $sSQL . "<br/>" ;
		$dest = "" ;
		$result = $mysqli->query($sSQL) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$dest .= $jou_mail . ";" ;
		}
		
		//echo $dest . "<br/>" ;
		$sSQLEquipes = "select e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, ".
							"CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jour " .
						" from ". TBL_TERRAIN .", ". TBL_CRENEAU .", ". TBL_MATCH .", ". TBL_POULE .", ". TBL_EVENEMENT .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3 " .
							" where mat_eq_id_1 = e1.eq_id " .
							" and mat_eq_id_2 = e2.eq_id " .
							" and mat_eq_id_3 = e3.eq_id " .
							" and mat_id = cre_mat_id " .
							" and pou_eve_id = eve_id " .
							" and mat_pou_id = pou_id " .
							" and ter_id = cre_ter_id " .
							" and mat_id = '". $mat_id ."' " ;
		//echo $sSQLEquipes . "<br/>" ;
		$resultEquipes = $mysqli->query($sSQLEquipes) ;
		while ($rowEquipes = mysqli_fetch_array($resultEquipes)) {
			extract($rowEquipes) ;
		}
		$sujet = "[Foot Asie] R&eacute;sultats ou Report en attente - " . $pou_nom ;
		$texte = "Bonjour,<br/><br/>Le r&eacute;sultat du match entre ".$eq1." et ".$eq2.", arbitr&eacute; par ".$arb." pr&eacute;vu le ".$jour." &agrave; ".$ter_nom." n'a pas &eacute;t&eacute; communiqu&eacute;.<br/><br/>" ;
		if($relance_text != "") {
			$texte .=  $relance_text . "<br/><br/>" ;
		}
		$texte .= "Pour rappel, il suffit aux responsables des &eacute;quipes qui arbitrent de se connecter et de mettre &agrave; jour le score, les &eacute;quipes joueuses pouvant &eacute;galement me l'envoyer, je le mettrai &agrave; jour.<br/>" ;
		$texte .= "Concernant les reports, les &eacute;quipes demandeuses peuvent le faire directement sur le site en se connectant, sachant que toutes les dates propos&eacute;es sont calcul&eacute;es de mani&egrave;re &agrave; ce que les &eacute;quipes ne jouent pas cette semaine-l&agrave;, que l'arbitre ne joue pas et que personne n'arbitre ce jour-l&agrave;.<br/><br/>" ;
		$texte .= "Merci de bien vouloir r&eacute;gulariser rapidement." ;
		
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $adresse_exp\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}	
	
	function envoiMailAjout($mat_id) {
		global $mysqli ;
		$sSQL = "select distinct(jou_mail) jou_mail from ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3, ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and ec_eq_id in (e1.eq_id, e2.eq_id, e3.eq_id) " .
					" and ec_jou_id = jou_id " .
					" and mat_id = '". $mat_id ."' " ;
		//echo $sSQL . "<br/>" ;
		$result = $mysqli->query($sSQL) ;
		$dest = "" ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$dest .= $jou_mail . ";" ;
		}
		//echo $dest . "<br/>" ;
		$sSQLEquipes = "select e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, ".
							"CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jour " .
						" from ". TBL_EVENEMENT .", ". TBL_POULE .", ". TBL_TERRAIN .", ". TBL_CRENEAU .", ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3 " .
							" where mat_eq_id_1 = e1.eq_id " .
							" and mat_eq_id_2 = e2.eq_id " .
							" and mat_eq_id_3 = e3.eq_id " .
							" and mat_id = cre_mat_id " .
							" and ter_id = cre_ter_id " .
							" and eve_id = pou_eve_id " .
							" and mat_pou_id = pou_id " .
							" and mat_id = '". $mat_id ."' " ;
		//echo $sSQLEquipes . "<br/>" ;
		$resultEquipes = $mysqli->query($sSQLEquipes) ;
		while ($rowEquipes = mysqli_fetch_array($resultEquipes)) {
			extract($rowEquipes) ;
		}
		
		$sujet = "[Foot Asie] Cr&eacute;ation de match - " . $pou_nom ;
		if($arb=="Amical") {
			$texte = "Bonjour,<br/><br/>le match amical entre ".$eq1." et ".$eq2." est pr&eacute;vu pour le ".$jour." &agrave; ".$ter_nom."." ;
		} else {
			$texte = "Bonjour,<br/><br/>le match ".$eq1." contre ".$eq2.", arbitr&eacute; par ".$arb." est pr&eacute;vu pour le ".$jour." &agrave; ".$ter_nom."." ;
		}
		//echo $sujet."<br/>".$texte ;
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $adresse_exp\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailDemandeCreneau($cre_id, $eq_id, $nom, $mail, $raison) {
		global $mysqli ;
		$sSQL = "select eq_nom, jou_mail from ". TBL_EQUIPE .", ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where ec_eq_id = '" . $eq_id . "' " .
					" and ec_jou_id = jou_id " .
					" and ec_eq_id = eq_id " ;
		//echo $sSQL . "<br/>" ;
		$result = $mysqli->query($sSQL) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$mail .= ";" . $jou_mail ;
		}
		
		$sSQLCreneau = "select CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jour, ter_nom" .
						" from ". TBL_TERRAIN .", ". TBL_CRENEAU .
							" where cre_id = '". $cre_id ."' " .
							" and ter_id = cre_ter_id " ;
		//echo $sSQLCreneau . "<br/>" ;
		$resultCreneau = $mysqli->query($sSQLCreneau) ;
		while ($rowCreneau = mysqli_fetch_array($resultCreneau)) {
			extract($rowCreneau) ;
		}
		
		$sujet = "[Foot Asie] Demande de cr&eacute;neau" ;
		$texte = "Bonjour,<br/><br/>" . $nom . " demande pour l'&eacute;quipe '" . $eq_nom . "' le cr&eacute;neau du " . $jour . " &agrave; " . $ter_nom . " pour la raison suivante :<br/>" . $raison ;
		$adresse_exp = ADR_MAIL ;
		$copie = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		//echo $mail. "<br/><br/>" ;
		//echo html_entity_decode($sujet) . "<br/><br/>" ;
		//echo html_entity_decode($texte) . "<br/><br/>" ;
		//echo $headers . "<br/>" ;
		mail($mail,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}

	function envoiMailModifArb($mat_id, $arb_id) {
		global $mysqli ;
		$sSQL = "select eq_nom, jou_mail from ". TBL_EQUIPE .", ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where ec_eq_id = '" . $arb_id . "' " .
					" and ec_jou_id = jou_id " .
					" and ec_eq_id = eq_id " ;
		//echo $sSQL . "<br/>" ;
		$copie = ADR_MAIL ;
		$result = $mysqli->query($sSQL) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$copie .= ";" . $jou_mail ;
			$arb_old = $eq_nom ;
		}
		
		$dest = "" ;
		$sSQLMail = "select distinct(jou_mail) jou_mail from ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3, ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and ec_eq_id in (e1.eq_id, e2.eq_id, e3.eq_id) " .
					" and ec_jou_id = jou_id " .
					" and mat_id = '". $mat_id ."' " ;
		//echo $sSQL . "<br/>" ;
		$resultMail = $mysqli->query($sSQLMail) ;
		while ($rowMail = mysqli_fetch_array($resultMail)) {
			extract($rowMail) ;
			$dest .= $jou_mail . ";" ;
		}
		//echo $dest . "<br/>" ;
		$sSQLEquipes = "select e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, ".
							"CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jour " .
						" from ". TBL_EVENEMENT .", ". TBL_POULE .", ". TBL_TERRAIN .", ". TBL_CRENEAU .", ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3 " .
							" where mat_eq_id_1 = e1.eq_id " .
							" and mat_eq_id_2 = e2.eq_id " .
							" and mat_eq_id_3 = e3.eq_id " .
							" and mat_id = cre_mat_id " .
							" and ter_id = cre_ter_id " .
							" and eve_id = pou_eve_id " .
							" and mat_pou_id = pou_id " .
							" and mat_id = '". $mat_id ."' " ;
		//echo $sSQLEquipes . "<br/>" ;
		$resultEquipes = $mysqli->query($sSQLEquipes) ;
		while ($rowEquipes = mysqli_fetch_array($resultEquipes)) {
			extract($rowEquipes) ;
		}
		
		$sujet = "[Foot Asie] Modification d'arbitrage - " . $pou_nom ; 
		$texte = "Bonjour,<br/><br/>le match ".$eq1." contre ".$eq2." pr&eacute;vu le ".$jour." &agrave; ".$ter_nom." sera arbitr&eacute; par ".$arb." &agrave; la place de ".$arb_old."." ;
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailScore($mat_id, $score1, $score2, $arbFlag, $forfait, $com_text, $pen1="", $pen2="") {
		global $mysqli ;
		$copie = ADR_MAIL ;
		
		$dest = "" ;
		$sSQLMail = "select distinct(jou_mail) jou_mail from ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3, ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and ec_eq_id in (e1.eq_id, e2.eq_id, e3.eq_id) " .
					" and ec_jou_id = jou_id " .
					" and mat_id = '". $mat_id ."' " ;
		//echo $sSQL . "<br/>" ;
		$resultMail = $mysqli->query($sSQLMail) ;
		while ($rowMail = mysqli_fetch_array($resultMail)) {
			extract($rowMail) ;
			$dest .= $jou_mail . ";" ;
		}
		//echo $dest . "<br/>" ;
		$sSQLEquipes = "select e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, ".
							"CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jour, pou_eve_id " .
						" from ". TBL_EVENEMENT .", ". TBL_POULE .", ". TBL_TERRAIN .", ". TBL_CRENEAU .", ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3 " .
							" where mat_eq_id_1 = e1.eq_id " .
							" and mat_eq_id_2 = e2.eq_id " .
							" and mat_eq_id_3 = e3.eq_id " .
							" and mat_id = cre_mat_id " .
							" and ter_id = cre_ter_id " .
							" and eve_id = pou_eve_id " .
							" and mat_pou_id = pou_id " .
							" and mat_id = '". $mat_id ."' " ;
		//echo $sSQLEquipes . "<br/>" ;
		$resultEquipes = $mysqli->query($sSQLEquipes) ;
		while ($rowEquipes = mysqli_fetch_array($resultEquipes)) {
			extract($rowEquipes) ;
		}
		if($pou_eve_id==4) {
			$sSQLMail2 = "select distinct(jou_mail) jou_mail from ". TBL_MATCH .", ". TBL_EQUIPE .", ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where mat_eq_id_4 = eq_id " .
					" and ec_eq_id = eq_id " .
					" and ec_jou_id = jou_id " .
					" and mat_id = '". $mat_id ."' " ;
			$resultMail2 = $mysqli->query($sSQLMail2) ;
			while ($rowMail2 = mysqli_fetch_array($resultMail2)) {
				extract($rowMail2) ;
				$dest .= $jou_mail . ";" ;
			}
		}
		
		$sujet = "[Foot Asie] R&eacute;sultat " . $pou_nom ; 
		if($forfait==0) {
			if($score1>$score2) {
				$txt = "la victoire de " . $eq1 . " sur le score de " . $score1 . " &agrave; " . $score2 ;
			} else if ($score1<$score2) {
				$txt = "la victoire de " . $eq2 . " sur le score de " . $score2 . " &agrave; " . $score1 ;
			} else {
				if($pen1!="") {
					if($pen1>$pen2) {
						$txt = "la victoire aux tirs au but de " . $eq1 . " sur le score de " . $score1 . " (".$pen1.") &agrave; " . $score2 . " (".$pen2.")" ;
					} else {
						$txt = "la victoire aux tirs au but de " . $eq2 . " sur le score de " . $score2 . " (".$pen2.") &agrave; " . $score1 . " (".$pen1.")" ;
					}
				} else {
					$txt = "un match nul sur le score de " . $score2 . " &agrave; " . $score1 ;
				}
				//echo $txt ;
			}
			if($arbFlag==1) {
				if($pou_eve_id==4) {
					$arbTxt = "Les arbitres &eacute;taient pr&eacute;sents." ;
				} else {
					$arbTxt = $arb . " &eacute;tait pr&eacute;sent." ;
				}
			} else {
				if($pou_eve_id==4) {
					$sSQLArbAbs = "select eq_nom arbAbs from " . TBL_EQUIPE . ", " . TBL_PENALITE . " where pen_eq_id = eq_id and pen_type = 'A' and pen_mat_id = '".$mat_id."'" ;
					$resultArbAbs = $mysqli->query($sSQLArbAbs) ;
					$nb=0 ;
					$arbTxt = "" ;
					while ($rowArbAbs = mysqli_fetch_array($resultArbAbs)) {
						extract($rowArbAbs) ;
						$nb++ ;
						if($nb>1) {
							$arbTxt .= " & " . $arbAbs ;
						} else {
							$arbTxt .= $arbAbs ;
						}
					}
					if($nb>1) {
						$arbTxt .= " &eacute;taient absents." ;
					} else {
						$arbTxt .= " &eacute;tait absent." ;
					}
					
				} else {
					$arbTxt = $arb . " &eacute;tait absent." ;
				}
			}
			$texte = "Bonjour,<br/><br/>le match entre ".$eq1." et ".$eq2." du ".$jour." &agrave; ".$ter_nom." s'est sold&eacute; par ".$txt.".<br/><br/>".$arbTxt."<br/><br/>" ;
		} else {
			$sSQLForfait = "select eq_nom from ". TBL_EQUIPE ." where eq_id = '".$forfait."'" ;
			$resultForfait = $mysqli->query($sSQLForfait) ;
			while ($rowForfait = mysqli_fetch_array($resultForfait)) {
				extract($rowForfait) ;
			}
			$texte = "Bonjour,<br/><br/>le match entre ".$eq1." et ".$eq2." du ".$jour." &agrave; ".$ter_nom." s'est sold&eacute; par un forfait de l'&eacute;quipe ".$eq_nom.".<br/><br/>" ;
		}
		$sSQLEqEnv = "select eq_nom from ". TBL_EQUIPE ." where eq_id = '".$_SESSION["eq_id"]."'" ;
		$resultEqEnv = $mysqli->query($sSQLEqEnv) ;
		while ($rowEqEnv = mysqli_fetch_array($resultEqEnv)) {
			extract($rowEqEnv) ;
		}
		if($com_text != "") {
			$texte .=  "Commentaires : " . $com_text . "<br/><br/>" ;
		}
		$texte .= "R&eacute;sultat envoy&eacute; par ".$eq_nom ;
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailInfos($mail) {
		$copie = ADR_MAIL ;
		
		$dest = $mail ;
		$sujet = "Modification des informations personnelles" ; 
		
		$texte = "Bonjour,<br/><br/>Vos informations personnelles ont &eacute;t&eacute; modifi&eacute;es. Si vous n'êtes pas &agrave; l'origine de cette d&eacute;marche, merci d'en informer le responsable de l'ASIE." ;
		
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailInfosSociete($mail) {
		$copie = ADR_MAIL ;
		
		$dest = $mail ;
		$sujet = "Modification des informations société" ; 
		
		$texte = "Bonjour,<br/><br/>Les informations de votre soci&eacute;t&eacute; ont &eacute;t&eacute; modifi&eacute;es. Si vous n'êtes pas &agrave; l'origine de cette d&eacute;marche, merci d'en informer le responsable de l'ASIE." ;
		
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailInfosEquipe($mail) {
		$copie = ADR_MAIL ;
		
		$dest = $mail ;
		$sujet = "Modification des informations de l'&eacute;quipe" ; 
		
		$texte = "Bonjour,<br/><br/>Les informations de votre &eacute;quipe ont &eacute;t&eacute; modifi&eacute;es. Si vous n'êtes pas &agrave; l'origine de cette d&eacute;marche, merci d'en informer le responsable de l'ASIE." ;
		
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}

	function envoiMailMdp($mail, $mdp) {
		$copie = ADR_MAIL ;
		
		$dest = $mail ;
		$sujet = "Mot de passe oubli&eacute;" ; 
		
		$texte = "Bonjour,<br/><br/>Votre mot de passe a &eacute;t&eacute; r&eacute;g&eacute;n&eacute;r&eacute; : ".$mdp."<br/><br/>Si vous n'êtes pas &agrave; l'origine de cette d&eacute;marche, merci d'en informer le responsable de l'ASIE." ;
		
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailAnnule($eq1, $eq2, $eqId1, $eqId2, $jour, $ter_nom) {
		global $mysqli ;
		$copie = ADR_MAIL ;
		
		$dest = "" ;
		$sSQLMail = "select distinct(jou_mail) jou_mail from ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where ec_eq_id in ('".$eqId1."', '".$eqId2."') " .
					" and ec_jou_id = jou_id " ;
		//echo $sSQL . "<br/>" ;
		$resultMail = $mysqli->query($sSQLMail) ;
		while ($rowMail = mysqli_fetch_array($resultMail)) {
			extract($rowMail) ;
			$dest .= $jou_mail . ";" ;
		}
		
		$sujet = "Annulation Match" ; 
		
		$texte = "Bonjour,<br/><br/>Le match amical entre ".$eq1." et ".$eq2." de ".$jour." &agrave; ".$ter_nom." a &eacute;t&eacute; annul&eacute;." ;
		
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailReportRefus($mat_id, $cre_id, $eq_id_dem, $refus) {
		global $mysqli ;
		$copie = ADR_MAIL ;
		
		$sSQL = "select distinct(jou_mail) jou_mail from ". TBL_MATCH .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3, ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and ec_eq_id in (e1.eq_id, e2.eq_id, e3.eq_id) " .
					" and ec_jou_id = jou_id " .
					" and mat_id = '". $mat_id ."' " ;
		//echo $sSQL . "<br/>" ;
		$dest = "" ;
		$result = $mysqli->query($sSQL) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$dest .= $jou_mail . ";" ;
		}
		
		//echo $dest . "<br/>" ;
		$sSQLEquipes = "select e1.eq_nom eq1, e2.eq_nom eq2, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) pou_nom, ".
							"CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jour " .
						" from ". TBL_TERRAIN .", ". TBL_CRENEAU .", ". TBL_MATCH .", ". TBL_POULE .", ". TBL_EVENEMENT .", ". TBL_EQUIPE ." e1, ". TBL_EQUIPE ." e2, ". TBL_EQUIPE ." e3 " .
							" where mat_eq_id_1 = e1.eq_id " .
							" and mat_eq_id_2 = e2.eq_id " .
							" and mat_eq_id_3 = e3.eq_id " .
							" and mat_id = cre_mat_id " .
							" and pou_eve_id = eve_id " .
							" and mat_pou_id = pou_id " .
							" and ter_id = cre_ter_id " .
							" and mat_id = '". $mat_id ."' " ;
		//echo $sSQLEquipes . "<br/>" ;
		$resultEquipes = $mysqli->query($sSQLEquipes) ;
		while ($rowEquipes = mysqli_fetch_array($resultEquipes)) {
			extract($rowEquipes) ;
		}
		$sSQLCreneau = "select CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
									" date_format(cre_date, ' %d '), " .
									" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
									" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END, ".
									" date_format(cre_date, ' %Y')) jourDem, ter_nom terDem " .
						" from ". TBL_TERRAIN .", ". TBL_CRENEAU .
							" where cre_id = '". $cre_id ."' " .
							" and ter_id = cre_ter_id " ;
		//echo $sSQLCreneau . "<br/>" ;
		$resultCreneau = $mysqli->query($sSQLCreneau) ;
		while ($rowCreneau = mysqli_fetch_array($resultCreneau)) {
			extract($rowCreneau) ;
		}
		
		if($eqId1==$eq_id_dem) {
			$eq_dem = $eq1 ;
			$eq_rec = $eq2 ;
		} else {
			$eq_dem = $eq2 ;
			$eq_rec = $eq1 ;
		}
		
		$texte = "Bonjour,<br/><br/>".$eq_dem." a demand&eacute; le report du match opposant ".$eq1." &agrave; ".$eq2.", arbitr&eacute; par ".$arb." pr&eacute;vu le ".$jour." &agrave; ".$ter_nom."." ;
		if($refus=="R") {
			$sujet = "[Foot Asie] Demande de report - Report refus&eacute;e" ; 
			$texte .= "<br/><br/>La demande a &eacute;t&eacute; refus&eacute;e par " . $eq_rec .". Merci de faire le n&eacute;cessaire pour vous pr&eacute;senter au match ou d&eacute;clarer forfait." ;
		} else {
			$sujet = "[Foot Asie] Demande de report - Date refus&eacute;e" ; 
			$texte .= "<br/><br/>La date du report a &eacute;t&eacute; refus&eacute;e par " . $eq_rec . ".<br/>Merci de refaire une demande avec un autre date afin que le report soit accept&eacute;." ;
		}
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $adresse_exp\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		// echo $dest . "<br/>" ;
		// echo $sujet . "<br/>" ;
		// echo $texte . "<br/>" ;
		// echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailDispo($jou_id, $mat_id) {
		global $mysqli ;
		$copie = ADR_MAIL ;
		
		$dest = "" ;
		$sSQLMail = "select distinct(jou_mail) jou_mail from ". TBL_EJT .", ". TBL_JOUEUR .
					" where ejt_eq_id = '" . $_SESSION['eq_id'] . "' " .
					" and jou_id = '" . $jou_id . "' " .
					" and ejt_jou_id = jou_id " ;
		//echo $sSQL . "<br/>" ;
		$resultMail = $mysqli->query($sSQLMail) ;
		while ($rowMail = mysqli_fetch_array($resultMail)) {
			extract($rowMail) ;
			$dest .= $jou_mail . ";" ;
		}
		
		$sSQL = "SELECT CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
				" date_format(cre_date, ' %d '), " .
				" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
				" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
				" e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, mat_id, mat_eq_id_4 arbId2 " .
				" FROM " . TBL_EQUIPE . " e1, " . TBL_EQUIPE . " e2, " . TBL_EQUIPE . " e3, " . TBL_MATCH . ", " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
				" WHERE mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and cre_mat_id = mat_id " .
					" and cre_ter_id = ter_id " .
					" and mat_sai_annee = '" . SAISON . "' " .
					" and mat_id = '" . $mat_id . "' " ;
		$result = $mysqli->query($sSQL) ;
		//echo $sSQL ;
		$mois_prec = "" ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			//print_r($row) ;
			if($arbId2!="") {
				$sSQLArb2 = "select eq_nom arb2 from " . TBL_EQUIPE . " where eq_id = '" . $arbId2 . "' ;" ;
				$resultArb2 = $mysqli->query($sSQLArb2) ;
				while ($rowArb2 = mysqli_fetch_array($resultArb2)) {
					extract($rowArb2) ;
				}
			}
		}
		
		if($_SESSION['eq_id']==$eqId1 || $_SESSION['eq_id']==$eqId2) {
			$sujet = "[Foot Asie] Disponibilit&eacute; Match" ; 
			$action = "jouer" ;
		}
		elseif($_SESSION['eq_id']==$arbId || $_SESSION['eq_id']==$arbId2) {
			$sujet = "[Foot Asie] Disponibilit&eacute; Arbitrage" ; 
			$action = "arbitrer" ;
		} else {
			echo "erreur" ;
		}
		$texte = "Bonjour,<br/><br/>Merci de donner vos disponibilit&eacute;s pour ".$action." le match entre ".$eq1." et ".$eq2." de ".$jour." &agrave; ".$ter_nom." en vous connectant au site du <a href='https://foot.asiesophia.fr'>Foot ASIE</a>." ;
			
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		echo $dest . "<br/>" ;
		echo $sujet . "<br/>" ;
		echo $texte . "<br/>" ;
		echo $headers . "<br/>" ;
		//mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailConv($jou_id, $mat_id) {
		global $mysqli ;
		$copie = ADR_MAIL ;
		
		$dest = "" ;
		$sSQLMail = "select distinct(jou_mail) jou_mail from ". TBL_EJT .", ". TBL_JOUEUR .
					" where ejt_eq_id = '" . $_SESSION['eq_id'] . "' " .
					" and jou_id = '" . $jou_id . "' " .
					" and ejt_jou_id = jou_id " ;
		//echo $sSQL . "<br/>" ;
		$resultMail = $mysqli->query($sSQLMail) ;
		while ($rowMail = mysqli_fetch_array($resultMail)) {
			extract($rowMail) ;
			$dest .= $jou_mail . ";" ;
		}
		
		$sSQL = "SELECT CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
				" date_format(cre_date, ' %d '), " .
				" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
				" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
				" e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, mat_id, mat_eq_id_4 arbId2 " .
				" FROM " . TBL_EQUIPE . " e1, " . TBL_EQUIPE . " e2, " . TBL_EQUIPE . " e3, " . TBL_MATCH . ", " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
				" WHERE mat_eq_id_1 = e1.eq_id " .
					" and mat_eq_id_2 = e2.eq_id " .
					" and mat_eq_id_3 = e3.eq_id " .
					" and cre_mat_id = mat_id " .
					" and cre_ter_id = ter_id " .
					" and mat_sai_annee = '" . SAISON . "' " .
					" and mat_id = '" . $mat_id . "' " ;
		$result = $mysqli->query($sSQL) ;
		//echo $sSQL ;
		$mois_prec = "" ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			//print_r($row) ;
			if($arbId2!="") {
				$sSQLArb2 = "select eq_nom arb2 from " . TBL_EQUIPE . " where eq_id = '" . $arbId2 . "' ;" ;
				$resultArb2 = $mysqli->query($sSQLArb2) ;
				while ($rowArb2 = mysqli_fetch_array($resultArb2)) {
					extract($rowArb2) ;
				}
			}
		}
		
		if($_SESSION['eq_id']==$eqId1 || $_SESSION['eq_id']==$eqId2) {
			$sujet = "[Foot Asie] Convocation Match" ; 
			$action = "jouer" ;
		}
		elseif($_SESSION['eq_id']==$arbId || $_SESSION['eq_id']==$arbId2) {
			$sujet = "[Foot Asie] Convocation Arbitrage" ; 
			$action = "arbitrer" ;
		} else {
			echo "erreur" ;
		}
		$texte = "Bonjour,<br/><br/>Vous avez &eacute;t&eacute; convoqu&eacute; pour ".$action." le match entre ".$eq1." et ".$eq2." de ".$jour." &agrave; ".$ter_nom."." ;
			
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		echo $dest . "<br/>" ;
		echo $sujet . "<br/>" ;
		echo $texte . "<br/>" ;
		echo $headers . "<br/>" ;
		//mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}	
	
	function envoiMailInscription($eq_id) {
		global $mysqli ;
		$copie = ADR_MAIL ;
		
		$dest = "" ;
		$sSQLMail = "select distinct(jou_mail) jou_mail from ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where ec_eq_id = '" . $eq_id . "' " .
					" and ec_jou_id = jou_id " ;
		$resultMail = $mysqli->query($sSQLMail) ;
		while ($rowMail = mysqli_fetch_array($resultMail)) {
			extract($rowMail) ;
			$dest .= $jou_mail . ";" ;
		}
		
		$sSQLEquipe = "select eq_id, eq_commentaire, eq_nom, soc_nom, eq_ter_id, eq_jour, eq_coupe, eq_couleur, eq_couleur_ext " .
					" from ". TBL_EQUIPE .", ". TBL_EPS .", ". TBL_SOCIETE .
					" where eps_eq_id = '" . $eq_id . "' " .
						" and eq_soc_id = soc_id " .
						" and eps_eq_id = eq_id " .
						" and eps_sai_annee = '" . SAISON_INS . "' " ;
		//echo $sSQLEquipe ;
		$resultEquipe = $mysqli->query($sSQLEquipe) ;
		while ($rowEquipe = mysqli_fetch_array($resultEquipe)) {
			extract($rowEquipe) ;
			$sSQLCor = "select jou_nom " .
							" from " . TBL_EQUIPE_CORRESP . ", " .
							TBL_JOUEUR .
							" where ec_eq_id = '".$eq_id."' " .
								" and ec_jou_id = jou_id ;" ;
			$resultCor = $mysqli->query($sSQLCor) ;
			$flagCor = false ;
			$corresp = "" ;
			while ($rowCor = mysqli_fetch_array($resultCor)) {
				extract($rowCor) ;
				if($flagCor) { $corresp .= ", " ; }
				$corresp .= $jou_nom ;
				$flagCor = true ;
			}
			$sSQLTer = "select ter_nom " .
							" from " . TBL_TERRAIN . 
							" where ter_id = '".$eq_ter_id."' ;" ;
			$resultTer = $mysqli->query($sSQLTer) ;
			$ter_nom = "Aucun" ;
			while ($rowTer = mysqli_fetch_array($resultTer)) {
				extract($rowTer) ;
			}
			$eq_coupe==1 ? $coupe = "Oui" : $coupe = "Non" ;
		}
		
		$sujet = "[FootAsie] Inscription " . NOM_SAISON_INS ;
		
		$texte = "Bonjour,<br/><br/>Votre inscription pour la saison " .NOM_SAISON_INS . " a bien &eacute;t&eacute; prise en compte. Ci-dessous, vous trouverez les informations de votre &eacute;quipe que vous pouvez modifier &agrave; tout moment en vous connectant et en allant dans Infos." .
				"<ul>" .
				"<li>Nom d'&eacute;quipe : ".$eq_nom."</li>" .
				"<li>Nom de la soci&eacute;t&eacute; : ".$soc_nom."</li>" .
				"<li>Noms des correspondants : ".ucwords($corresp)."</li>" .
				"<li>Terrain &agrave; &eacute;viter : ".$ter_nom."</li>" .
				"<li>Jour &agrave; &eacute;viter : ".ucfirst($eq_jour)."</li>" .
				"<li>Participation &agrave; la coupe : ".$coupe."</li>" .
				"<li>Couleur Maillot Principal : ".ucwords($eq_couleur)."</li>" .
				"<li>Couleur Maillot Secondaire : ".ucwords($eq_couleur_ext)."</li>" .
				"<li>Commentaire lors de l'inscription : ".$eq_commentaire."</li>" .
				"</ul>" ;
		
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		//echo $dest . "<br/>" ;
		//echo $sujet . "<br/>" ;
		//echo $texte . "<br/>" ;
		//echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
	
	function envoiMailCreationCapitaine($mail, $mdp, $eq_id) {
		global $mysqli ;
		$copie = ADR_MAIL ;
		$dest = $mail ;
		
		$sSQLEquipe = "select eq_nom " .
					" from ". TBL_EQUIPE .
					" where eq_id = '" . $eq_id . "' " ;
		$resultEquipe = $mysqli->query($sSQLEquipe) ;
		while ($rowEquipe = mysqli_fetch_array($resultEquipe)) {
			extract($rowEquipe) ;
		}
		$sujet = "[FootAsie] Cr&eacute;ation Responsable " . $eq_nom ;
		$texte = "Bonjour,<br/><br/>Vous avez &eacute;t&eacute; nomm&eacute; responsable de l'&eacute;quipe " . $eq_nom . "." .
				"<br/>Votre identifiant de connexion est votre mail et le mot de passe est le suivant : " . $mdp .
				"<br/><br/>Veuillez vous connecter à l'adresse suivante <a href='".PATH."index.php?op=id'>".PATH."</a> afin de modifier votre mot de passe (tr&egrave;s fortement conseill&eacute;)." ;
		
		$adresse_exp = ADR_MAIL ;
		$frontiere = "---~".mt_rand()."~";
		$headers = "From: $adresse_exp\n";
		$headers.= "Reply-to: $adresse_exp\n";
		$headers.= "Cc: $copie\n";
		$headers.= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=\"utf8\"";
		//echo $dest . "<br/>" ;
		//echo $sujet . "<br/>" ;
		//echo $texte . "<br/>" ;
		//echo $headers . "<br/>" ;
		mail($dest,html_entity_decode($sujet),html_entity_decode($texte),$headers);
	}
?>
