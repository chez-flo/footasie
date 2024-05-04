<?php
	include(__DIR__."/bdd.php") ;
	
	$sSQLDate = "SELECT date_format(date_add(now(), INTERVAL 1 WEEK), '%v') semChoix, date_format(now(),'%Y') annee from dual " ;
	$resultDate = $mysqli->query($sSQLDate) ;
	while($rowDate = mysqli_fetch_array($resultDate)) {
		extract($rowDate) ;
	}
	
	$timeStampPremierJanvier = strtotime($annee . '-01-01');
	$jourPremierJanvier = date('w', $timeStampPremierJanvier);
	 
	//-- recherche du N° de semaine du 1er janvier -------------------
	$numSemainePremierJanvier = date('W', $timeStampPremierJanvier);
	 
	//-- nombre à ajouter en fonction du numéro précédent ------------
	$decallage = ($numSemainePremierJanvier == 1) ? $semChoix - 1 : $semChoix;
	$nbDecallageLundi = $jourPremierJanvier - 1 ;
	//-- timestamp du jour dans la semaine recherchée ----------------
	$timeStampDate = strtotime('+' . $decallage . ' weeks', $timeStampPremierJanvier);
	$timeStampDate = strtotime('-' . $nbDecallageLundi . ' days', $timeStampDate);
	//-- recherche du lundi de la semaine en fonction de la ligne précédente ---------
	$jourDebutSemaine = date('d/m/Y', strtotime('monday', $timeStampDate));
	$jourFinSemaine = date('d/m/Y',strtotime('friday', $timeStampDate));
	 
	$sSQLEquipe = "SELECT distinct(eq_id), eq_nom " .
		" FROM " . TBL_POULE . ", " . TBL_EPS . ", " . TBL_EQUIPE . 
		" WHERE eps_eq_id = eq_id " .
			" and eps_pou_id = pou_id " .
			" and eps_sai_annee = '" . SAISON . "' " .
		" ORDER BY eq_id " ;
	//echo $sSQLEquipe ;
	$resultEquipe = $mysqli->query($sSQLEquipe) ;
	while($rowEquipe = mysqli_fetch_array($resultEquipe)) {
		extract($rowEquipe) ;
		echo $eq_nom . "<br/>" ;
		$sSQLSemaine = "SELECT cre_date, MONTH(cre_date) mois, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
			" date_format(cre_date, ' %d '), " .
			" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
			" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
			" cre_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, ter_nom, sco1.sco_bp score1, sco2.sco_bp score2, sco1.sco_pen pen1, sco2.sco_pen pen2, CONCAT(eve_nom, ' : ', pou_nom) poule, eve_id, " .
			" '' style, " .
			" mat_id, mat_statut, date_format(cre_date, '%v') cre_date_semaine " .
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
				" and (e1.eq_id = " . $eq_id . " or e2.eq_id = " . $eq_id . " or e3.eq_id = " . $eq_id . ")" .
				" and date_format(cre_date, '%v') = '" . $semChoix . "' " .
				" and (mat_statut <> '1' or eve_id = '1')" .
			" ORDER BY cre_date, ter_nom, cre_id " ;
		//echo $sSQLSemaine . "<br/>" ;
		$resultSemaine = $mysqli->query($sSQLSemaine) ;
		$a=0;
		$m=0;
		$arbitrage = array() ;
		$match = array() ;
		$flagMatch = false ;
		$arb2="" ;
		while($rowSemaine = mysqli_fetch_array($resultSemaine)) {
			extract($rowSemaine) ;
			$flagMatch = true ;
			$sSQLArb2 = "select eq_id arbId2, eq_nom arb2 from " . TBL_MATCH . ", " . TBL_EQUIPE . " where mat_id = " . $mat_id . " and mat_eq_id_4 = eq_id ;" ;
			$resultArb2 = $mysqli->query($sSQLArb2) ;
			while ($rowArb2 = mysqli_fetch_array($resultArb2)) {
				extract($rowArb2) ;
			}
			if($arb2!="") {
				$texte = "<li>" . $jour . " : " . $eq1 . " contre " . $eq2 . " arbitr&eacute; par " . $arb . " et " . $arb2 . " &agrave; " . $ter_nom . ".</li>" ;
			} else {
				$texte = "<li>" . $jour . " : " . $eq1 . " contre " . $eq2 . " arbitr&eacute; par " . $arb . " &agrave; " . $ter_nom . ".</li>" ;
			}
			if($eq_id == $arbId) {
				$arbitrage[$a] = $texte ;
				$a++ ;
			}
			else {
				$match[$m] = $texte ;
				$m++ ;
			}
		}
		
		$sSQLSemaine2 = "SELECT cre_date, MONTH(cre_date) mois, CONCAT(CASE dayofweek(cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
			" date_format(cre_date, ' %d '), " .
			" CASE month(cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
			" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour, " .
			" cre_id, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, e4.eq_nom arb2, e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, e4.eq_id arbId2, ter_nom, sco1.sco_bp score1, sco2.sco_bp score2, sco1.sco_pen pen1, sco2.sco_pen pen2, CONCAT(eve_nom, ' : ', pou_nom) poule, eve_id, " .
			" '' style, " .
			" mat_id, mat_statut, date_format(cre_date, '%v') cre_date_semaine " .
			" FROM " . TBL_EVENEMENT . ", " . TBL_SAISON . ", " . TBL_POULE . ", " . TBL_SCORE . " sco1, " . TBL_SCORE . " sco2, " . TBL_EPS . ", " . TBL_EQUIPE . " e1, " . TBL_EQUIPE . " e2, " . TBL_EQUIPE . " e3, " . TBL_EQUIPE . " e4, " . TBL_MATCH . ", " . TBL_CRENEAU . ", " . TBL_TERRAIN . 
			" WHERE eve_id = pou_eve_id " .
				" and eps_eq_id = e1.eq_id " .
				" and eps_pou_id = pou_id " .
				" and sai_pou_id = pou_id " .
				" and mat_eq_id_1 = e1.eq_id " .
				" and mat_eq_id_2 = e2.eq_id " .
				" and mat_eq_id_3 = e3.eq_id " .
				" and mat_eq_id_4 = e4.eq_id " .
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
				" and e4.eq_id = '" . $eq_id . "' " .
				" and date_format(cre_date, '%v') = '" . $semChoix . "' " .
			" ORDER BY cre_date, ter_nom, cre_id " ;
		$resultSemaine2 = $mysqli->query($sSQLSemaine2) ;
		while($rowSemaine2 = mysqli_fetch_array($resultSemaine2)) {
			extract($rowSemaine2) ;
			$flagMatch = true ;
			$texte = "<li>" . $jour . " : " . $eq1 . " contre " . $eq2 . " arbitr&eacute; par " . $arb . " et " . $arb2 . " &agrave; " . $ter_nom . ".</li>" ;
			if($eq_id == $arbId2) {
				$arbitrage[$a] = $texte ;
				$a++ ;
			}
			else {
				$match[$m] = $texte ;
				$m++ ;
			}
		}
		
		if($flagMatch) {
			$textMail = "Bonjour,<br/><br/>voici les matchs et/ou arbitrages de la semaine pour votre &eacute;quipe.<br/><br/>" ;
			if(!empty($match)) {
				$textMail .= "<b><u>Matchs :</u></b>" ;
				$textMail .= "<ul>" ;
				for($i=0;$i<sizeof($match);$i++) {
					$textMail .= $match[$i];
				}
				$textMail .= "</ul>" ;
			}
			if(!empty($arbitrage)) {
				$textMail .= "<b><u>Arbitrages :</u></b>" ;
				$textMail .= "<ul>" ;
				for($i=0;$i<sizeof($arbitrage);$i++) {
					$textMail .= $arbitrage[$i] ;
				}
				$textMail .= "</ul>" ;
			}
			
			$sSQLMail = "select eq_nom, jou_mail from ". TBL_EQUIPE .", ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
					" where ec_eq_id = '" . $eq_id . "' " .
					" and ec_jou_id = jou_id " .
					" and ec_eq_id = eq_id " ;
			$dest = "" ;
			$resultMail = $mysqli->query($sSQLMail) ;
			while ($rowMail = mysqli_fetch_array($resultMail)) {
				extract($rowMail) ;
				$dest .= $jou_mail . ";" ;
			}
			
			$sujet = "[Foot Asie] Programme du " . $jourDebutSemaine . " au " . $jourFinSemaine . " (semaine " . $cre_date_semaine . ") - " . $eq_nom ; 
			//$texte = "Bonjour,\n\nle match ".$eq1." contre ".$eq2." prévu le ".$jour." à ".$ter_nom." sera arbitré par ".$arb." à la place de ".$arb_old."." ;
			$adresse_exp = ADR_MAIL ;
			$copie = ADR_MAIL ;
			$frontiere = "---~".mt_rand()."~";
			$headers = "From: $adresse_exp\n";
			$headers.= "Reply-to: $adresse_exp\n";
			$headers.= "Cc: $copie\n";
			$headers.= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: text/html; charset=\"utf8\"";
			echo $dest . "<br/>" ;
			echo $sujet . "<br/>" ;
			echo $textMail . "<br/>" ;
			echo $headers . "<br/><br/>" ;
			mail($dest,html_entity_decode($sujet),html_entity_decode($textMail),$headers);
		}
	}
	
?>