<?php session_start() ; 
	
	require_once "ical/zapcallib.php";

	// Fonction testée le 31/10/2007 : Permet de récupérer l'extension du fichier passé en paramètre
	function isAdmin() {
		if(isset($_SESSION["validate"]) and $_SESSION["validate"] == "ok_admin") {
			return true ;
		}
		else {
			return false ;
		}
	}
	
	// Fonction testée le 31/10/2007 : Permet de récupérer l'extension du fichier passé en paramètre
	function isCapitaine() {
		if(isAdmin()) {
			return true ;
		}
		if(isset($_SESSION["validate"]) and $_SESSION["validate"] == "ok_cap") {
			return true ;
		}
		else {
			return false ;
		}
	}
	
	// Fonction testée le 31/10/2007 : Permet de récupérer l'extension du fichier passé en paramètre
	function isJoueur() {
		if(isCapitaine()) {
			return true ;
		}
		if(isset($_SESSION["validate"]) and $_SESSION["validate"] == "ok_jou") {
			return true ;
		}
		else {
			return false ;
		}
	}

	// Fonction testée le 31/10/2007 : Permet de récupérer l'extension du fichier passé en paramètre
	function extension($fic) {
		$extension = explode(".", $fic) ;
		$ext = strtolower($extension[count($extension)-1]) ;
		return $ext ;
	}
	
	// Fonction testée le 31/10/2007 : Permet de donner la taille du fichier passé en paramètre
	function taille_fichier($fic) {
		if(filesize($fic) < 1024)
			$taille = filesize($fic) . " octets" ;
		elseif((filesize($fic) >= 1024) && (filesize($fic) < 1048576))
			$taille = round(filesize($fic)/1024,2). " Ko" ;
		elseif((filesize($fic) >= 1048576) && (filesize($fic) < 1073741824))
			$taille = round(filesize($fic)/1048576,2) . " Mo" ;
		elseif((filesize($fic) >= 1073741824))
			$taille = round(filesize($fic)/1073741824,2) . " Go" ;
		return $taille ;
	}
	
	// Fonction testée le 10/12/2007 : Permet de changer les caractère spéciaux par leur valeur héxadécimale
	function carac_spec_hexa($string) {
		return urlencode($string) ;
	}

	// Fonction testée le 10/12/2007 : Permet de changer les caractère spéciaux par leur valeur html
	function carac_spec_html($string) {
		return htmlentities($string);
	}
	
	// Fonction testée le 10/12/2007 : Permet de changer les valeur html en caractère spéciaux
	function html_carac_spec($string) {
		return html_entity_decode($string);
	}
	
	// Fonction testée le 10/12/2007 : Permet de récupérer la liste des sous-dossiers et le nom du répertoire parent actif
	function liste_dossier($folder) {
		$folder = str_replace("\'","'",$folder) ;
		$dossier = opendir($folder) ;
		
		$repParent = substr($folder,0,strrpos($folder,'/')) ;
		$repParent = str_replace(" ","%20",$repParent) ;
		$repParent = str_replace("&","%26",$repParent) ;
		
		if($repParent != '') {
			$i = 1 ;
			$k = 1 ;
			$sousRep[0] = '..' ;
			$cheminSousRep[0] = $repParent ;
		}
		else {
			$k = 0 ;
			$i = 0 ;
		}
		
		while ($fic = readdir($dossier)) {
			if ($fic != "." && $fic != ".." && $fic != "thumbs") {
				if(is_dir($folder."/".$fic)) {
					$sousRep[$i] = $fic ;
					$i++ ;
				}
			}
		}
		for($j=$k; $j<$i; $j++) {
			$nom = $sousRep[$j] ;
			$rep = $folder."/".$nom ;
			$dir[$j] = $rep ;
			$rep = str_replace(" ","%20",$rep) ;
			$rep = str_replace("&","%26",$rep) ;
			$cheminSousRep[$j] = $rep ;
		}
		if(isset($sousRep)) sort($sousRep) ; 
		if(isset($cheminSousRep)) sort($cheminSousRep) ; 
		
		$retour = array('repParent' => $repParent, 
						'sousRep' => isset($sousRep) ? $sousRep : '' , 
						'cheminSousRep' => isset($cheminSousRep) ? $cheminSousRep : '' ) ;
		return $retour ;
	}
	
	// Fonction testée le 11/12/2007 : Permet de récupérer le nom du fichier sans son extension
	function nomFichier($fichier) {
		$fichier = explode("/", $fichier) ;
		$fichier = $fichier[count($fichier)-1] ;
		$fichier = explode(".", $fichier) ;
		return $fichier[0] ;
	}
	
	// Fonction testée le 11/12/2007 : Permet de supprimer les accent d'une chaine de caractère
	function chaineSansAccent($string) {
		$noAccent = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn" ;
		$accent = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
		$string = strtr($string, $accent, $noAccent );
	return $string; 
	
	}
	
	// Fonction testée le 11/12/2007 : Permet de lire une vidéo passée en paramètre
	function lirePlayer($fichier, $iDPlayer) {
		// var so = new SWFObject(swf, id, width, height, version, background-color [, quality, xiRedirectUrl, redirectUrl, detectKey]);
		
		if(extension($fichier) == 'flv') {
			$code = '<script type="text/javascript" src="' . PATH . 'swfobject.js"></script>' .
					'<script type="text/javascript">' .
						'var lecteur = new SWFObject("' . PATH . 'flvplayer.swf","playlist","360","280","7");'.
						'lecteur.addParam("allowfullscreen","true");' . // Autorisation du plein écran
						'lecteur.addVariable("file","' . $fichier . '");' . // Chemin du fichier par rapport au player
						'lecteur.addVariable("displayheight","260");' . // Hauteur de l'écran
						'lecteur.addVariable("backcolor","0x000000");' . // Couleur de font
						'lecteur.addVariable("frontcolor","0xCCCCCC");' . // Couleur des affichages
						'lecteur.addVariable("lightcolor","0x557722");' . // Couleur du passage de souris
						'lecteur.addVariable("title","' . nomFichier($fichier) . '");' . // Titre de la vidéo
						'lecteur.write("' . $iDPlayer . '");' . // Id dans lequel le player se met
					'</script>' ;
		}
		else {
			$code = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
						codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" 
						width="200" 
						height="20" 
						id="dewplayer" 
						align="middle">
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="movie" value="dewplayer.swf?mp3=' . $fichier . '&amp;autostart=1&amp;&amp;bgcolor=000000" />
						<param name="quality" value="high" />
						<param name="bgcolor" value="000000" />
						<embed src="dewplayer.swf?mp3=' . $fichier . '&amp;autostart=1&amp;&amp;bgcolor=000000" 
							quality="high" 
							bgcolor="000000" 
							width="200" 
							height="20" 
							name="dewplayer"  
							align="middle" 
							allowScriptAccess="sameDomain" 
							type="application/x-shockwave-flash" 
							pluginspage="http://www.macromedia.com/go/getflashplayer">
						</embed>
					</object>' ;
		}
		echo $code ;
		return 0 ;
	}
	
	// Fonction testée le 11/12/2007 : Permet de convertir une date en entier
	function convDateInt($jour, $mois, $annee, $heure = 0,$min = 0, $sec = 0) {
		return mktime($heure, $min, $sec, $mois, $jour, $annee) ;
	}
	
	function convDate($timestamp) {
		return date('d/m/Y',$timestamp) ;
	}
	
	//
	function convDateJour($timestamp) {
		return date('d',$timestamp) ;
	}
	
	//
	function convDateMois($timestamp) {
		return date('m',$timestamp) ;
	}
	
	//
	function convDateAnnee($timestamp) {
		return date('Y',$timestamp) ;
	}
	
	//
	function convMoisIntChar($int) {
		$mois = array(1 => 'Janvier',
						'F&eacute;vrier',
						'Mars',
						'Avril',
						'Mai',
						'Juin',
						'Juillet',
						'Ao&ucirc;t',
						'Septembre',
						'Octobre',
						'Novembre',
						'D&eacute;cembre') ;
		return $mois[$int] ;
	}
	
	function formatTel($numTel) {
		$i=0;
		$j=0;
		$formate = "";
		while ($i<strlen($numTel)) { //tant qu il y a des caracteres
			if ($j < 2) {
				if (preg_match('/^[0-9]$/', $numTel[$i])) { //si on a bien un chiffre on le garde
					$formate .= $numTel[$i];
					$j++;
				}
			$i++;
			}
			else { //si on a mis 2 chiffres a la suite on met un espace
				$formate .= " ";
				$j=0;
			}
		}
		return $formate;
	}
	
	function validEmail($email) {
		$atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
		$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
		$regex = '/^' . $atom . '+' . '(\.' . $atom . '+)*' . '@' .	'(' . $domain . '{1,30}\.)+' . $domain . '{2,10}$/i';
		if (preg_match($regex, $email)) return "ok" ;
		else return "ko" ;
	}
	
	function envoiMail($verif, $adresse_dest, $login = '', $mdp = '') {
		$sujet = "Inscription sur nouach.free.fr" ;
		if($verif == "ok") 
			$texte = "Votre inscription a été réalisée avec succés. (login = '" . $login . "' et mot de passe : '" . $mdp . "')." ;
		else $texte = "Il y a eu un problème dans l'inscription, veuillez contacter l'administrateur." ;
		$adresse_exp = "nouach@free.fr" ;
		mail($adresse_dest,$sujet,$texte,"From: $adresse_exp\nreplyTo: $adresse_exp");
	}
	
	function createThumb($name, $filename, $thumb_x = 100, $thumb_y = 75) {
		if (!file_exists("album/thumbs/" . $filename)){
			$img_in = imagecreatefromjpeg($name);
			$img_out = imagecreatetruecolor($thumb_x, $thumb_y);
			imagecopyresampled($img_out, $img_in, 0, 0, 0, 0, imagesx($img_out), imagesy($img_out), imagesx($img_in), imagesy($img_in));
			imagejpeg($img_out, "album/thumbs/" . $filename);
			imagedestroy($img_out);
			imagedestroy($img_in);
		}
	}
	
	function lienMap($nom, $adresse) {
		$url = "<a href=\"http://maps.google.fr/maps?f=q&hl=fr&geocode=&q=" . str_replace(' ', '+', $adresse) . "\" target='_blank'>" . $nom . "</a>" ;
		return $url ;
	}
	
	function redirect($url) {
		if (!headers_sent())
		{    
			header('Location: '.$url);
			exit;
			}
		else
			{  
			echo '<script type="text/javascript">';
			echo 'window.location.href="'.$url.'";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
			echo '</noscript>'; exit;
		}
	}
	
	function color($score1, $score2, $pen1="", $pen2="") {
		$color1="" ;
		$color2="" ;
		if($score1==$score2) {
			if($pen1!="") {
				if($pen1>$pen2) {
					$color1="gagne" ;
					$color2="perdu" ;
				} elseif($pen1<$pen2) {
					$color1="perdu" ;
					$color2="gagne" ;
				}
			}
			else {
				if($score1!="") {
					$color1="nul" ;
					$color2="nul" ;
				}
			}
		} elseif($score1>$score2) {
				$color1="gagne" ;
				$color2="perdu" ;
		} elseif($score1<$score2) {
				$color1="perdu" ;
				$color2="gagne" ;
		}
		return array("color1" => $color1, "color2" => $color2) ;
	}
	
	function classement($classement,$nbEquipe) {
		if($classement==1) {
			$classement.="<sup>er</sup> sur ".$nbEquipe ;
		} else {
			$classement.="<sup>&egrave;me</sup> sur ".$nbEquipe ;
		}
		return $classement ;
	}
	
	function infoEquipe($info) {
		if($info!="") {
			return $info ;
		}
		else {
			return "Non renseign&eacute;" ;
		}
	}
	
	function telCorresp($num) {
		if(isCapitaine() || isJoueur()) {
			if($num!="") {
				if(isMobile()) {
					return "<a href='tel:".str_replace(' ', '', $num)."'>".$num."</a>" ;
				} else {
					return $num ;
				}
			}
			else {
				return "Non renseign&eacute;" ;
			}
		} else {
			return "<a href='index.php?op=id'>Connectez vous</a>" ;
		}
	}
	
	function mailCorresp($mail) {
		if(isCapitaine() || isJoueur()) {
			if($mail!="") {
				return "<a href='mailto:".$mail."'>".$mail."</a>" ;
			}
			else {
				return "Non renseign&eacute;" ;
			}
		} else {
			return "<a href='index.php?op=id'>Connectez vous</a>" ;
		}
	}
	
	function createCalendar($eq_id) {
		global $mysqli ;
		$sSQLEquipe = "select eq_nom from " . TBL_EQUIPE . " where eq_id = '" . $eq_id ."' " ;
		$resultEquipe = $mysqli->query($sSQLEquipe) ;
		$rowEquipe = mysqli_fetch_array($resultEquipe) ;
		extract($rowEquipe) ;
		//$v = new vcalendar( array( 'unique_id' => $eq_nom ));// initiate new CALENDAR
		$v = new ZCiCal();
		
		$sSQLCal = "SELECT date_format(cre_date, '%Y') annee, " .
			" date_format(cre_date, '%m') mois, " .
			" date_format(cre_date, '%d') jour, " .
			" e1.eq_id eqId1, e2.eq_id eqId2, e3.eq_id arbId, e1.eq_nom eq1, e2.eq_nom eq2, e3.eq_nom arb, ter_nom, CONCAT(eve_nom, ' : ', pou_nom) pou_nom " .
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
				" and (e1.eq_id = '" . $eq_id . "' or e2.eq_id = '" . $eq_id . "' or e3.eq_id = '" . $eq_id . "') " .
			" ORDER BY cre_date " ;
		$resultCal = $mysqli->query($sSQLCal) ;
		while ($rowCal = mysqli_fetch_array($resultCal)) {
			extract($rowCal) ;
			//$e = & $v->newComponent( 'vevent' );           // initiate a new EVENT
			$arbId==$eq_id ? $categorie = "Arbitrage" : $categorie = "Match" ;
			//$e->setProperty( 'categories'
			//			   , $categorie );                   // catagorize
			//$e->setProperty( 'summary'
			//			   , $categorie . ' ' . $pou_nom . ' - ' . $eq1 . ' vs. ' . $eq2  );
			//$e->setProperty( 'dtstart'
			//			   ,  $annee, $mois, $jour, 12, 30, 00 );  // 24 dec 2006 19.30
			//$e->setProperty( 'duration'
			//			   , 0, 0, 1,30 );                    // 1h30 hours
			//$e->setProperty( 'description'
			//			   , $eq1 . ' vs. ' . $eq2 . ' (' . $arb . ')' );    // describe the event
			//$e->setProperty( 'location'
			//			   , $ter_nom );                     // locate the event
			$e = new ZCiCalNode("VEVENT", $v->curnode);
			$e->addNode(new ZCiCalDataNode("categories:".$categorie));
			$e->addNode(new ZCiCalDataNode("SUMMARY:".$categorie . ' ' . $pou_nom . ' - ' . $eq1 . ' vs. ' . $eq2));
			$e->addNode(new ZCiCalDataNode("DTSTART:" . $annee.$mois.$jour."T120000"));
			$e->addNode(new ZCiCalDataNode("DTEND:" . $annee.$mois.$jour."T140000"));
			$uid = $annee . "-" . $mois . "-" . $jour . "-" . $eq_nom;
			$e->addNode(new ZCiCalDataNode("UID:" . $uid));
			//$e->addNode(new ZCiCalDataNode("DTSTAMP:" . ZCiCal::fromSqlDateTime()));
			$e->addNode(new ZCiCalDataNode("description:" . $eq1 . ' vs. ' . $eq2 . ' (' . $arb . ')'));
			$e->addNode(new ZCiCalDataNode("location:" . $ter_nom));
			
		}
		//$v->saveCalendar(); // save calendar to file
		//echo $v->export();
		//redirect("index.php?op=ics&file=".$v->getConfig("FILENAME")) ;
		//return $v->getConfig("FILENAME") ;
		
		$iCalFile = @fopen( "ical/files/".$uid.".ics", 'w' );
		if( $iCalFile ) {
		  if( FALSE === fwrite( $iCalFile, $v->export() ))
			return FALSE;
		  fclose( $iCalFile );
		  return "ical/files/".$uid.".ics";
		}
		else
		  return "";
		
	}
	
	function searchEquipeByPoule($pou_id=0) {
		global $mysqli ;
		if($pou_id!=0) {
			$sSql = "SELECT eq_id, eq_nom, pou_eve_id " .
				" FROM " . TBL_EQUIPE . ", " . TBL_EPS . ", " . TBL_POULE . 
				" WHERE eps_eq_id = eq_id " .
					" and eps_pou_id = pou_id " .
					" and eps_sai_annee = '" . SAISON . "' " .
					" and eps_pou_id = '" . $pou_id . "' " .
					" ORDER BY eq_nom" ;
			$result=$mysqli->query($sSql) ;
			$listeOptions="" ;
			while ($row = mysqli_fetch_array($result)) {
				extract($row) ;
				$listeOptions .= "<option value='" . $eq_id . "'>" . carac_spec_html($eq_nom) . "</option>" ;
			}
			$sSqlArb = "SELECT eq_id, eq_nom " .
				" FROM " . TBL_EQUIPE . ", " . TBL_EPS . 
				" WHERE eps_eq_id = eq_id " .
					" and eps_sai_annee = '" . SAISON . "' " .
					" and eps_pou_id = (select sai_arb_pou_id from " . TBL_SAISON . " where sai_pou_id = '" . $pou_id . "' and sai_annee = '" . SAISON . "') " .
					" ORDER BY eq_nom" ;
			//echo $sSqlArb ;
			$resultArb=$mysqli->query($sSqlArb);
			$listeOptionsArb="" ;
			while ($rowArb = mysqli_fetch_array($resultArb)) {
				extract($rowArb) ;
				$listeOptionsArb .= "<option value='" . $eq_id . "'>" . carac_spec_html($eq_nom) . "</option>" ;
			}
			$aff="<input type='hidden' name='eve_id' id='eve_id' value='".$pou_eve_id."'>" .
				"<table>" .
					"<tr>" .
						"<th>&Eacute;quipe 1 :</th>" .
						"<th>&Eacute;quipe 2 :</th>" .
						"<th>Arbitre :</th>" .
					"</tr>" .
					"<tr>" .
						"<td><select id='eq1' name='eq1'>".$listeOptions."</select></td>" .
						"<td><select id='eq2' name='eq2'>".$listeOptions."</select></td>" .
						"<td><select id='arb' name='arb'>".$listeOptionsArb."</select></td>" .
					"</tr>" .
				"</table>" ;
			return $aff ;
		}
		else {
			echo "Veuillez s&eacute;lectionner une poule" ;
		}
	}
	
	function remplirChampsEquipe($eq) {
		global $mysqli ;
		$aff="" ;
		if($eq!="") {
			$sSQLSocId = "select soc_id soc, eq_ter_id ter, eq_jour, eq_ami_id ami, eq_coupe, eq_couleur, eq_couleur_ext " .
					" from " . TBL_SOCIETE . ", " . TBL_EQUIPE . 
					" where eq_soc_id = soc_id " .
					" and eq_id = '".$eq."' " .
					" order by soc_nom " ;
			$resultSocId = $mysqli->query($sSQLSocId) ;
			while ($rowSocId = mysqli_fetch_array($resultSocId)) {
				extract($rowSocId) ;
			}
			$aff .= "<table align='center'/>" .
				"<tr>" .
					"<td align='right' valign='top'>Soci&eacute;t&eacute;* : </td>" .
					"<td>" .
						"<select name='soc_id' id='soc_id' onchange='changeSoc(this) ;'>" .
							"<option value='0'>Nouvelle soci&eacute;t&eacute;</option>" ;
				$sSQLSoc = "select soc_id, soc_nom " .
					" from " . TBL_SOCIETE .  
					" order by soc_nom " ;
			$resultSoc = $mysqli->query($sSQLSoc) ;
			while ($rowSoc = mysqli_fetch_array($resultSoc)) {
				extract($rowSoc) ;
				if($soc_id==$soc) {
					$aff .= "<option value='".$soc_id."' selected>".$soc_nom."</option>" ;
				} else {
					$aff .= "<option value='".$soc_id."'>".$soc_nom."</option>" ;
				}
			}
			$aff .= "</select>" .
					"</td>" .
				"</tr>" .
				"<tr id='soc_tr' style='display: none;'>" .
					"<td align='right'>Nom de la soci&eacute;t&eacute;* : </td>" .
					"<td><input type='text' name='societe' id='societe' size='40'/></td>" .
				"</tr>" .
				"<tr>" .
					"<td align='right'>Terrain &agrave; &eacute;viter : </td>" .
					"<td>" .
						"<select name='ter_id' id='ter_id'>" .
							"<option value=''>Aucun</option>" ;
							$sSQLTerrain = "select ter_id, ter_nom " .
									" from " . TBL_TERRAIN . 
									" order by ter_nom " ;
							$resultTerrain = $mysqli->query($sSQLTerrain) ;
							while ($rowTerrain = mysqli_fetch_array($resultTerrain)) {
								extract($rowTerrain) ;
								if($ter_id==$ter) {
									$aff .= "<option value='".$ter_id."' selected>".$ter_nom."</option>" ;
								} else {
									$aff .= "<option value='".$ter_id."'>".$ter_nom."</option>" ;
								}
							}
				$aff .= "</select>" .
					"</td>" .
				"</tr>" .
				"<tr>" .
					"<td align='right'>Jour &agrave; &eacute;viter : </td>" .
					"<td>" .
						"<select name='jour' id='jour'>" .
							"<option value='aucun'>Aucun</option>" ;
							$eq_jour=="lundi" ? $aff .= "<option value='lundi' selected>Lundi</option>" : $aff .= "<option value='lundi'>Lundi</option>" ;
							$eq_jour=="mardi" ? $aff .= "<option value='mardi' selected>Mardi</option>" : $aff .= "<option value='mardi'>Mardi</option>" ;
							$eq_jour=="mercredi" ? $aff .= "<option value='mercredi' selected>Mercredi</option>" : $aff .= "<option value='mercredi'>Mercredi</option>" ;
							$eq_jour=="jeudi" ? $aff .= "<option value='jeudi' selected>Jeudi</option>" : $aff .= "<option value='jeudi'>Jeudi</option>" ;
							$eq_jour=="vendredi" ? $aff .= "<option value='vendredi' selected>Vendredi</option>" : $aff .= "<option value='vendredi'>Vendredi</option>" ;
						$aff .= "</select>" .
					"</td>" .
				"</tr>" .
				"<tr>" .
					"<td align='right'>&Eacute;quipe amie : </td>" .
					"<td>" .
						"<select name='eq_ami_id' id='eq_ami_id'>" .
							"<option value=''>Aucune</option>" ;
							$sSQLAmi = 	"select eq_id, eq_nom" .
										" from " . TBL_EPS . ", " . 
											TBL_EQUIPE . 
										" where eps_eq_id = eq_id " .
											" and eq_id not in (1,230)" .
											" and eps_sai_annee = '".SAISON."' " .
											" and eps_pou_id = 2 " .
										" order by eq_nom " ;
							$resultAmi = $mysqli->query($sSQLAmi) ;
							while ($rowAmi = mysqli_fetch_array($resultAmi)) {
								extract($rowAmi) ;
								if($eq_id==$ami) {
									$aff .= "<option value='".$eq_id."' selected>".$eq_nom."</option>" ;
								} else {
									$aff .= "<option value='".$eq_id."'>".$eq_nom."</option>" ;
								}
							}
				$aff .= "</select>" .
					"</td>" .
				"</tr>" .
				"<tr>" .
					"<td align='right'>Participation &agrave; la coupe ELOCAR* : </td>" .
					"<td>" .
						"<select name='coupe' id='coupe'>" ;
							$eq_coupe=="1" ? $aff .= "<option value='1' selected>oui</option>" : $aff .= "<option value='1'>oui</option>" ;
							$eq_coupe=="0" ? $aff .= "<option value='0' selected>non</option>" : $aff .= "<option value='0'>non</option>" ;
						$aff .= "</select>" .
					"</td>" .
				"</tr>" .
				"<tr>" .
					"<td align='right'>Couleur Maillot 1* : </td><td><input type='text' name='couleur' id='couleur' value='".$eq_couleur."' size='40'/></td>" .
				"</tr>" .
				"<tr>" .
					"<td align='right'>Couleur Maillot 2 : </td><td><input type='text' name='couleur_ext' id='couleur_ext' value='".$eq_couleur_ext."' size='40'/></td>" .
				"</tr>" .
			"</table>" .
			"</td>" .
				"</tr>" .
				"<tr>" .
					"<td align='center'>" .
						"<table>" .
							"<tr>" ;
								$sSQLCor = "select jou_nom " .
										" from " . TBL_EQUIPE_CORRESP . ", " .
										TBL_JOUEUR .
										" where ec_eq_id = '".$eq."' " .
											" and ec_jou_id = jou_id ;" ;
								$resultCor = $mysqli->query($sSQLCor) ;
								$flagCor = false ;
								$corresp = "" ;
								while ($rowCor = mysqli_fetch_array($resultCor)) {
									extract($rowCor) ;
									if($flagCor) { $corresp .= " ou " ; }
									$corresp .= $jou_nom ;
									$flagCor = true ;
								}
								$aff .= "<th colspan='4'>Responsables (si diff&eacute;rent de ".$corresp.")</th>" .
							"</tr>" .
							"<tr>" .
								"<td align='right'>Pr&eacute;nom Nom Resp. 1 : </td><td><input type='text' name='nom_1' id='nom_1' size='30'/></td>" .
								"<td align='right'>Mail Resp. 1 : </td><td><input type='text' name='mail_1' id='mail_1' size='30'/></td>" .
							"</tr>" .
							"<tr>" .
								"<td align='right'>Pr&eacute;nom Nom Resp. 2 : </td><td><input type='text' name='nom_2' id='nom_2' size='30'/></td>" .
								"<td align='right'>Mail Resp. 2 : </td><td><input type='text' name='mail_2' id='mail_2' size='30'/></td>" .
							"</tr>" .
						"</table>" .
					"<td>" .
				"</tr>" .
				"<tr>" .
					"<td align='center'>" .
						"<table>" .
							"<tr>" .
								"<th>Commentaires</th>" .
							"</tr>" .
							"<tr>" .
								"<td><input type='text'  name='commentaire' id='commentaire' size='100'/></td>" .
							"</tr>" .
						"</table>" ; 
		}
		return $aff ;
	}
	
	function createRedirect($op,$tab=0) {
		$lib = "javascript:document.location.href=\"".PATH."index.php?op=".$op ;
		if($tab!=0) {
			foreach($tab as $cle=>$valeur) { 
				$lib.= "&".$cle.'='.$valeur; 
			} 
		}
		$lib .= "\"" ;
		return $lib ;
	}
	
	function optionRedirect($op,$tab=0) {
		$lib = PATH."index.php?op=".$op ;
		if($tab!=0) {
			foreach($tab as $cle=>$valeur) { 
				$lib.= "&".$cle.'='.$valeur; 
			} 
		}
		return $lib ;
	}

	function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	
	// Génération d'une chaine aléatoire
	function chaine_aleatoire($nb_car, $chaine = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
	{
		$nb_lettres = strlen($chaine) - 1;
		$generation = '';
		for($i=0; $i < $nb_car; $i++)
		{
			$pos = mt_rand(0, $nb_lettres);
			$car = $chaine[$pos];
			$generation .= $car;
		}
		return $generation;
	}
	
	function validMdp($mdp) {
		$regex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/';
		if (preg_match($regex, $mdp)) return true ;
		else return false ;
	}
	
	function secureArray($array_sec){
		foreach ($array_sec as $key => $value) {
			if(is_array($value)) {
				$array_sec[$key] = secureArray($value);
			}
			else {
				$array_sec[$key] = htmlentities($value, ENT_QUOTES);
			}
		}
		return $array_sec;
	}
	
	function securite_bdd($string) {
		global $mysqli ;
		// On regarde si le type de string est un nombre entier (int)
		if(ctype_digit($string))
		{
			$string = intval($string);
		}
		// Pour tous les autres types
		else
		{
			$string = mysqli_real_escape_string($mysqli,$string);
			$string = addcslashes($string, '%_');
		}
		
		return $string;
	}
	
	function selectJour($jour="aucun") {
		$option = "" ;
		$tabJour = array('aucun',
						'lundi',
						'mardi',
						'mercredi',
						'jeudi',
						'vendredi') ;
		for($i=0;$i<sizeof($tabJour);$i++) {
			if($jour==$tabJour[$i]) {
				$option .= "<option value='".$tabJour[$i]."' selected>".ucfirst($tabJour[$i])."</option>" ;
			} else {
				$option .= "<option value='".$tabJour[$i]."'>".ucfirst($tabJour[$i])."</option>" ;
			} 
		}
		return $option ;
	}
	
	function selectTerrain($terrain="0") {
		global $mysqli ;
		$option = "" ;
		$option .= "<option value='0' selected>Aucun</option>" ;
		$sql = "select ter_id, ter_nom from " . TBL_TERRAIN . " order by ter_nom" ;
		$result = $mysqli->query($sql) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			if($ter_id==$terrain) {
				$option .= "<option value='".$ter_id."' selected>".ucfirst($ter_nom)."</option>" ;
			} else {
				$option .= "<option value='".$ter_id."'>".ucfirst($ter_nom)."</option>" ;
			} 
		}
		return $option ;
	}
	
	function selectAmi($ami="0") {
		global $mysqli ;
		$option = "" ;
		$option .= "<option value='0' selected>Aucune</option>" ;
		$sql = 	"select eq_id, eq_nom" .
				" from " . TBL_EPS . ", " . 
					TBL_EQUIPE . 
				" where eps_eq_id = eq_id " .
					" and eq_id not in (1,230)" .
					" and eps_sai_annee = '".SAISON."' " .
					" and eps_pou_id = 2 " .
				" order by eq_nom " ;
		$result = $mysqli->query($sql) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			if($eq_id==$ami) {
				$option .= "<option value='".$eq_id."' selected>".ucfirst($eq_nom)."</option>" ;
			} else {
				$option .= "<option value='".$eq_id."'>".ucfirst($eq_nom)."</option>" ;
			} 
		}
		return $option ;
	}
	
	function selectOuiNon($jour="non") {
		$option = "" ;
		$tabJour = array('non',
						'oui') ;
		for($i=0;$i<sizeof($tabJour);$i++) {
			if($jour==$i) {
				$option .= "<option value='".$i."' selected>".$tabJour[$i]."</option>" ;
			} else {
				$option .= "<option value='".$i."'>".$tabJour[$i]."</option>" ;
			} 
		}
		return $option ;
	}
	
	function bouton($id, $value, $type, $class, $onclick="", $style="") {
		$text = "" ;
		if($onclick!="") {
			$text.= " onClick='".$onclick."' " ;
		}
		if($style!="") {
			$text.= " style='".$style."' " ;
		}
		return "<input type='".$type."' class='".$class."' id='".$id."' name='".$id."' value='".$value."' ".$text.">" ;
	}
	
	function boutonSubmit($id, $value, $onclick="", $style="") {
		$text = "" ;
		if($onclick!="") {
			$text.= " onClick='".$onclick."' " ;
		}
		if($style!="") {
			$text.= " style='".$style."' " ;
		}
		return "<input type='submit' class='boutonValider' id='".$id."' name='".$id."' value='".$value."' ".$text.">" ;
	}
	
	function boutonRetour() {
		return "<input type='button' class='boutonRetour' name='retour' value='Retour' onclick='javascript:history.go(-1)'>" ;
	}
	
	// Gestion des matchs
	function createOptionsJoueurs($id,$search="") {
		global $mysqli ;
		$list = "" ;
		$listPrec = "" ;
		$list .= "<option class='optionGroup1' onClick=\"selectAllOptions('joueurs[]');\" value='all'>Tout le monde **</option>" ;
		$sSqlDejaInvites = "SELECT ejm_jou_id FROM " .  TBL_EJM . " where ejm_eq_id = '" . $_SESSION["eq_id"] . "' and ejm_mat_id = '".$id."'" ;
		$sSqlGrpSelect = "SELECT jou_id, jou_nom, ejt_typ_id, typ_nom " ;
		$sSqlGrp = " FROM " . TBL_EJT . ", " . TBL_JOUEUR . ", " . TBL_TYPE  ;
		$sSqlGrp .= " WHERE jou_id = ejt_jou_id and ejt_typ_id = typ_id "  ;
		$sSqlGrp .= " and ejt_sai_annee = '" . SAISON . "' " ;
		$sSqlGrp .= " and ejt_eq_id = '" . $_SESSION["eq_id"] . "' " ;
		$sSqlGrp .= " and jou_id not in (".$sSqlDejaInvites.") " ;
		$sSqlGrpOrder = " ORDER BY ejt_typ_id, jou_nom" ;
		//echo $sSqlGrpSelect.$sSqlGrp.$sSqlGrpOrder ;
		$result = $mysqli->query($sSqlGrpSelect.$sSqlGrp.$sSqlGrpOrder) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			if($listPrec!=$ejt_typ_id) {
				$list .= "<option class='optionGroup' onClick=\"selectAllOptionsGroup('joueurs[]', '".$ejt_typ_id."');\">".$typ_nom." **</option>" ;
				$listPrec = $ejt_typ_id ;
			}
			$list .= "<option class='optionChild' value='".$ejt_typ_id."_".$jou_id."'>".carac_spec_html($jou_nom)."</option>" ;
		}
		$list .= "<optgroup id='reti' label='Retir&eacute;s'>" ;
		$list .= "</optgroup>" ;
		return $list ;
	}
	
	function createOptionsInvites($id) {
		global $mysqli ;
		$list = "" ;
		$id_use = "" ;
		$sSqlDejaInvites = "SELECT ejm_jou_id FROM " .  TBL_EJM . " where ejm_eq_id = '" . $_SESSION["eq_id"] . "' and ejm_mat_id = '".$id."'" ;
		$sSqlGrpSelect = "SELECT jou_id, jou_nom, ejt_typ_id, typ_nom " ;
		$sSqlGrp = " FROM " . TBL_EJT . ", " . TBL_JOUEUR . ", " . TBL_TYPE  ;
		$sSqlGrp .= " WHERE jou_id = ejt_jou_id and ejt_typ_id = typ_id "  ;
		$sSqlGrp .= " and ejt_sai_annee = '" . SAISON . "' " ;
		$sSqlGrp .= " and ejt_eq_id = '" . $_SESSION["eq_id"] . "' " ;
		$sSqlGrp .= " and jou_id in (".$sSqlDejaInvites.") " ;
		$sSqlGrpOrder = " ORDER BY ejt_typ_id, jou_nom" ;
		$result = $mysqli->query($sSqlGrpSelect.$sSqlGrp.$sSqlGrpOrder) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$list .= "<option class='optionChild' value='".$ejt_typ_id."_".$jou_id."' disabled>".carac_spec_html($jou_nom)."</option>" ;
		}
		return $list ;
	}
	
	function createOptionsConvoques($id, $valid) {
		global $mysqli ;
		$list = "" ;
		$id_use = "" ;
		if($valid=='1') {
			$list .= "<option class='optionGroup1' onClick=\"selectAllOptions('presents[]');\" value='all'>Tout le monde **</option>" ;
		}
		$sSqlDejaInvites = "SELECT ejm_jou_id FROM " .  TBL_EJM . " where ejm_eq_id = '" . $_SESSION["eq_id"] . "' and ejm_mat_id = '".$id."' and ejm_valid='".$valid."'" ;
		$sSqlGrpSelect = "SELECT jou_id, jou_nom, ejt_typ_id, typ_nom " ;
		$sSqlGrp = " FROM " . TBL_EJT . ", " . TBL_JOUEUR . ", " . TBL_TYPE  ;
		$sSqlGrp .= " WHERE jou_id = ejt_jou_id and ejt_typ_id = typ_id "  ;
		$sSqlGrp .= " and ejt_sai_annee = '" . SAISON . "' " ;
		$sSqlGrp .= " and ejt_eq_id = '" . $_SESSION["eq_id"] . "' " ;
		$sSqlGrp .= " and jou_id in (".$sSqlDejaInvites.") " ;
		$sSqlGrpOrder = " ORDER BY ejt_typ_id, jou_nom" ;
		$result = $mysqli->query($sSqlGrpSelect.$sSqlGrp.$sSqlGrpOrder) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			if($valid=='1') {
				$list .= "<option class='optionChild' value='".$ejt_typ_id."_".$jou_id."'>".carac_spec_html($jou_nom)."</option>" ;
			} else {
				$list .= "<option class='optionChild' value='".$ejt_typ_id."_".$jou_id."' disabled>".carac_spec_html($jou_nom)."</option>" ;
			}
		}
		return $list ;
	}
	
	// Equipes pour mails
	function createOptionsEquipes($search="") {
		global $mysqli ;
		$list = "" ;
		$listPrec = "" ;
		$list .= "<option class='optionGroup1' onClick=\"selectAllOptions('equipes[]');\" value='all'>Tout le monde **</option>" ;
		$sSqlGrpSelect = "SELECT eq_id, pou_id, CONCAT(eve_nom, ' ', pou_nom) nom, eq_nom " ;
		$sSqlGrp = " FROM " . TBL_EPS . ", " . TBL_EQUIPE . ", " . TBL_EVENEMENT . ", " . TBL_POULE  ;
		$sSqlGrp .= " WHERE pou_id = eps_pou_id " ;
		$sSqlGrp .= " and eps_eq_id = eq_id "  ;
		$sSqlGrp .= " and eps_sai_annee = '" . SAISON . "' " ;
		$sSqlGrp .= " and pou_eve_id = eve_id " ;
		$sSqlGrp .= " and pou_eve_id > 1 " ;
		//$sSqlGrp .= " and eq_id < '1000000' " ;
		if($search<>"") {
			$sSqlGrp .= " and eq_nom like '%".$search."%' " ;
		}
		$sSqlGrpOrder = " ORDER BY pou_id, eq_nom" ;
		//echo "<option>".$sSqlGrpSelect.$sSqlGrp.$sSqlGrpOrder."</option>" ;
		$result = $mysqli->query($sSqlGrpSelect.$sSqlGrp.$sSqlGrpOrder) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			if($listPrec!=$pou_id) {
				$list .= "<option class='optionGroup' onClick=\"selectAllOptionsGroup('equipes[]', '".$pou_id."');\">".$nom." **</option>" ;
				$listPrec = $pou_id ;
			}
			$list .= "<option class='optionChild' value='".$pou_id."_".$eq_id."'>".carac_spec_html($eq_nom)."</option>" ;
		}
		$list .= "<optgroup id='reti' label='Retir&eacute;s'>" ;
		$list .= "</optgroup>" ;
		return $list ;
	}
	
	// Gestion des matchs
	function createOptionsAjoutJoueurs($search="") {
		global $mysqli ;
		$list = "" ;
		$listPrec = "" ;
		$sSqlGrp = "select jou_id, jou_nom " .
					" from " . TBL_JOUEUR .  
					" where not exists (select jou_id from " . TBL_EJT . " where ejt_sai_annee = '".SAISON."' and ejt_typ_id = 1 and ejt_jou_id = jou_id) " ;
		if($search<>"") {
			$sSqlGrp .= " and jou_nom like '%".$search."%' " ;
		}
		$sSqlOrder = " order by jou_nom " ;
		//echo $sSqlGrpSelect.$sSqlGrp.$sSqlGrpOrder ;
		$result = $mysqli->query($sSqlGrp.$sSqlOrder) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$list .= "<option class='optionChild' value='".$jou_id."'>".carac_spec_html($jou_nom)."</option>" ;
		}
		$list .= "<optgroup id='reti' label='Retir&eacute;s'>" ;
		$list .= "</optgroup>" ;
		return $list ;
	}
	
	// Gestion des matchs
	function createOptionsJoueursEquipe() {
		global $mysqli ;
		$list = "" ;
		$listPrec = "" ;
		$sSQLJoueur = "select jou_id, jou_nom " .
					" from " . TBL_JOUEUR . ", " . TBL_EJT . 
					" where ejt_jou_id = jou_id " .
					" and ejt_sai_annee = '".SAISON."' " .
					" and ejt_typ_id = 1 " .
					" and ejt_eq_id = '" . $_SESSION["eq_id"] . "'" .
					" order by jou_nom " ;
		//echo $sSqlGrpSelect.$sSqlGrp.$sSqlGrpOrder ;
		$result = $mysqli->query($sSQLJoueur) ;
		while ($row = mysqli_fetch_array($result)) {
			extract($row) ;
			$list .= "<option class='optionChild' value='".$jou_id."'>".carac_spec_html($jou_nom)."</option>" ;
		}
		$list .= "</optgroup>" ;
		return $list ;
	}
	
	function checkMatch($id) {
		global $mysqli ;
		$sSQL = "select * from " . TBL_MATCH . " where mat_id = '" . $id . "' and " .
			" (mat_eq_id_1 = '" . $_SESSION['eq_id'] . "' or " .
			" mat_eq_id_2 = '" . $_SESSION['eq_id'] . "' or " .
			" mat_eq_id_3 = '" . $_SESSION['eq_id'] . "' or " .
			" mat_eq_id_4 = '" . $_SESSION['eq_id'] . "')" ;
		$result = $mysqli->query($sSQL) ;
		if(mysqli_num_rows($result)==0) {
			return false ;
		}
		return true ;
	}
	
	function createPopup($op,$tab=0) {
		$lib = "window.open(\"index.php?op=".$op ;
		if($tab!=0) {
			foreach($tab as $cle=>$valeur) { 
				$lib.= "&".$cle.'='.$valeur; 
			} 
		}
		$lib .= "&pop\",\"\",\"wclose,width=800,height=600,toolbar=no,status=no,left=20,top=30\")" ;
		return $lib ;
	}
	
	function createPopupStatus($op,$status,$opPrecedent,$tab=0) {
		$lib = "window.open(\"index.php?op=".$op ;
		$lib.= "&opPrecedent=".$opPrecedent ;
		$lib.= "&status=".$status; 
		if($tab!=0) {
			foreach($tab as $cle=>$valeur) { 
				$lib.= "&".$cle.'='.$valeur; 
			} 
		}
		$lib .= "&pop\",\"\",\"wclose,width=1,height=1,toolbar=no,status=no,left=20,top=30\")" ;
		return $lib ;
	}
	
	function createRedirectStatus($op,$status,$opPrecedent,$tab=0) {
		$lib = "javascript:document.location.href=\"index.php?op=".$op ;
		$lib.= "&opp=".$opPrecedent ;
		$lib.= "&status=".$status; 
		if($tab!=0) {
			foreach($tab as $cle=>$valeur) { 
				$lib.= "&".$cle.'='.$valeur; 
			} 
		}
		$lib .= "\"" ;
		return $lib ;
	}
	
	function createPopupCommentaire($id) {
		$lib = "window.open(\"index.php?op=com&id=".$id; 
		$lib .= "&pop\",\"\",\"wclose,width=1,height=1,toolbar=no,status=no,left=20,top=30\")" ;
		return $lib ;
	}

	function creatUrl($string) {
		$lien = "" ;
		$links=array() ;
		$i=0 ;
		while($i<strlen($string)) {
			if(strpos($string, "http://") > -1) {
				if(strpos($string, " ", strpos($string, "http://")) > -1) {
					$lien = substr($string, strpos($string, "http://"), strpos($string, " ", strpos($string, "http://"))-strpos($string, "http://")) ;
				}
				else {
					$lien = substr($string, strpos($string, "http://"), strlen($string)) ;
				}
			}
			if(strpos($string, "https://") > -1) {
				if(strpos($string, " ", strpos($string, "https://")) > -1) {
					$lien = substr($string, strpos($string, "https://"), strpos($string, " ", strpos($string, "https://"))-strpos($string, "https://")) ;
				}
				else {
					$lien = substr($string, strpos($string, "https://"), strlen($string)) ;
				}
			}
			if($lien != "") {
				$links[sizeof($links)]=$lien ;
				$lien = "" ;
			}
			$i++ ;
		}
		for($i=0; $i<sizeof($links); $i++) {
			$string = str_replace($links[$i], "<a href='".$links[$i]."' target='_blank'>".$links[$i]."</a>", $string);
		}
		return $string ;
	}
	
	function deuxiemeArbitre($mat_id) {
		global $mysqli ;
		$arb=array() ;
		$arbFlag=false ;
		$sSQLArb2 = "select eq_id arbId2, eq_nom arb2 from " . TBL_MATCH . ", " . TBL_EQUIPE . " where mat_id = " . $mat_id . " and mat_eq_id_4 = eq_id ;" ;
		$resultArb2 = $mysqli->query($sSQLArb2) ;
		while ($rowArb2 = mysqli_fetch_array($resultArb2)) {
			extract($rowArb2) ;
			$arb[0]=$arbId2 ;
			$arb[1]=$arb2 ;
			$arbFlag=true ;
		}
		return $arb ;
	}
	
	function checkSociete($soc) {
		global $mysqli ;
		$aff="" ;
		$sSQLVerif = "select soc_id, soc_nom from " . TBL_SOCIETE . " where trim(lower(soc_nom)) = trim(lower('" . $soc ."')) ;" ;
		$resultVerif = $mysqli->query($sSQLVerif) ;
		if(mysqli_num_rows($resultVerif)>0) {
			$aff = "Cette soci&eacute;t&eacute; existe d&eacute;j&agrave;" ;
		} 
		return $aff ;
	}
	
	function checkEquipe($eq_nom) {
		global $mysqli ;
		$aff="" ;
		$sSQLVerif = "select eq_id, eq_nom from " . TBL_EQUIPE . ", " . TBL_EPS . " where eq_id = eps_eq_id and eps_sai_annee = '".SAISON_INS."' and trim(lower(eq_nom)) = trim(lower('" . $eq_nom ."')) ;" ;
		$resultVerif = $mysqli->query($sSQLVerif) ;
		if(mysqli_num_rows($resultVerif)>0) {
			$aff = "Cette &eacute;quipe est d&eacute;j&agrave; inscrite" ;
		} 
		return $aff ;
	}
	
	function lpad($txt, $nb, $char=" ") {
		if($txt!="") {
			while(strlen($txt)<$nb) {
				$txt=$char.$txt ;
			}
		}
		return $txt ;
	}
	
	include("mail.php") ;
?>
