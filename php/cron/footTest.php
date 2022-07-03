<?php
	include(__DIR__."/bdd.php") ;
				
	$sSQLMail = "select eq_nom, jou_mail from ". TBL_EQUIPE .", ". TBL_EQUIPE_CORRESP .", ". TBL_JOUEUR .
			" where ec_jou_id = jou_id " .
			" and ec_jou_id = jou_id " .
			" and jou_dro_id = 1 " .
			" and ec_eq_id = eq_id " ;
			//echo $sSQLMail ;
	$dest = "" ;
	$resultMail = $mysqli->query($sSQLMail) ;
	while ($rowMail = mysqli_fetch_array($resultMail)) {
		extract($rowMail) ;
		$dest .= $jou_mail . ";" ;
	}
	
	$sujet = "Ceci est un test - " . $eq_nom ; 
	$textMail = "C'est un test pour " . $eq_nom ;
	$adresse_exp = ADR_MAIL ;
	$copie = ADR_MAIL ;
	$frontiere = "---~".mt_rand()."~";
	$headers = "From: $adresse_exp\n";
	$headers.= "Reply-to: $adresse_exp\n";
	$headers.= "Cc: $copie\n";
	$headers.= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"";
	echo $dest . "<br/>" ;
	echo $sujet . "<br/>" ;
	echo $textMail . "<br/>" ;
	echo $headers . "<br/><br/>" ;
	mail($dest,$sujet,$textMail,$headers);
?>