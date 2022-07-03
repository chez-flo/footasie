<?php
	include(__DIR__."/bdd.php") ;
	
	$sSQLDate = "SELECT date_format(date_add(now(), INTERVAL 1 WEEK), '%v') semChoix from dual " ;
	$resultDate = $mysqli->query($sSQLDate) ;
	while($rowDate = mysqli_fetch_array($resultDate)) {
		extract($rowDate) ;
	}
	
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
		//echo $eq_id . "<br/>" ;
		$sSQLSemaine = "SELECT CONCAT(CASE dayofweek(cre_old.cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
			" date_format(cre_old.cre_date, ' %d '), " .
			" CASE month(cre_old.cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
			" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour_old, " .
			" CONCAT(CASE dayofweek(cre_new.cre_date) WHEN 2 THEN 'Lundi' WHEN 3 THEN 'Mardi' WHEN 4 THEN 'Mercredi' WHEN 5 THEN 'Jeudi' WHEN 6 THEN 'Vendredi' ELSE 'autre' END, " .
			" date_format(cre_new.cre_date, ' %d '), " .
			" CASE month(cre_new.cre_date) WHEN 1 THEN 'Janvier' WHEN 2 THEN 'F&eacute;vrier' WHEN 3 THEN 'Mars' WHEN 4 THEN 'Avril' WHEN 5 THEN 'Mai' WHEN 6 THEN 'Juin' " .
			" WHEN 7 THEN 'Juillet' WHEN 8 THEN 'Ao&ucirc;t' WHEN 9 THEN 'Septembre' WHEN 10 THEN 'Octobre' WHEN 11 THEN 'Novembre' WHEN 12 THEN 'D&eacute;cembre' ELSE 'autre' END) jour_new, " .
			" e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_new.ter_nom ter_new, ter_old.ter_nom ter_old " .
			" FROM " . TBL_REPORT . ", " . TBL_EQUIPE . " e1, " . TBL_EQUIPE . " e2, " . TBL_EQUIPE . " e3, " . TBL_MATCH . ", " . TBL_CRENEAU . " cre_old, " . TBL_TERRAIN . " ter_old, " . TBL_CRENEAU . " cre_new, " . TBL_TERRAIN . " ter_new " .
			" WHERE rep_mat_id = mat_id " .
				" and rep_eq_id_dem = e1.eq_id " .
				" and rep_eq_id_rec = e2.eq_id " .
				" and mat_eq_id_3 = e3.eq_id " .
				" and cre_old.cre_mat_id = mat_id " .
				" and cre_old.cre_ter_id = ter_old.ter_id " .
				" and cre_new.cre_id = rep_cre_id " .
				" and cre_new.cre_ter_id = ter_new.ter_id " .
				" and mat_sai_annee = '" . SAISON . "' " .
				" and rep_eq_id_rec = '" . $eq_id  . "' " .
				" and rep_reponse = '0' " .
			" ORDER BY rep_id " ;
		//echo $sSQLSemaine . "<br/>" ;
		$flagReport = false ;
		$r=0;
		$report = array() ;
		$resultSemaine = $mysqli->query($sSQLSemaine) ;
		while($rowSemaine = mysqli_fetch_array($resultSemaine)) {
			extract($rowSemaine) ;
			$texte = "<li>Match du " . $jour_old . " contre " . $eq1 . " arbitr&eacute; par " . $arb . " &agrave; " . $ter_old . " report&eacute; au " . $jour_new . " &agrave; " . $ter_new . ".</li>" ;
			$report[$r++] = $texte ;
			$flagReport = true ;
		}
		if($flagReport) {
			$textMail = "Bonjour,<br/><br/>Voici le(s) match(s) report&eacute;(s) en attente de r&eacute;ponse de votre part." ;
			$textMail .= "<ul>" ;
			for($i=0;$i<sizeof($report);$i++) {
				$textMail .= $report[$i];
			}
			$textMail .= "</ul>" ;
			$textMail .= "Vous pouvez d&eacute;sormais vous connecter sur le site, aller sur la page de votre &eacute;quipe et accepter ou refuser la demande le cas &eacute;ch&eacute;ant. La mise &agrave; jour se fera automatiquement." ;
			
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
			
			$sujet = "[Foot Asie] " . $eq_nom . " - Reports en attente de réponse" ; 
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