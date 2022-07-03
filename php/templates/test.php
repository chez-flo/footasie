<?php include('templates/bandeau.php') ; ?>

<div class = "PagePrincipale">
<CENTER>
<?php
	
//	print_r($_FILES) ;
	$repertoireDestination = dirname(__FILE__)."/../tmp/";
	$nomDestination = date("YmdHis") . "fichier.txt";
	if(isset($_FILES["monfichier"])) {
		if (is_uploaded_file($_FILES["monfichier"]["tmp_name"])) {
			if (rename($_FILES["monfichier"]["tmp_name"],
						   $repertoireDestination.$nomDestination)) {
			//	echo "Le fichier temporaire ".$_FILES["monfichier"]["tmp_name"].
				//		" a été déplacé vers ".$repertoireDestination.$nomDestination;
			} else {
			//	echo "Le déplacement du fichier temporaire a échoué".
			//			" vérifiez l'existence du répertoire ".$repertoireDestination;
			}          
		} else {
			//echo "Le fichier n'a pas été uploadé (trop gros ?)";
		}
		$nbTotal = 0 ;
		$nbInsert = 0 ;
		$nbUpdate = 0 ;
		$nbNonConcerne = 0 ;
		//echo "<br/>" ;
		$fichier = fopen($repertoireDestination.$nomDestination, 'r') ;
		while(!feof($fichier)) {
			$ligne = str_replace("\"","",fgets($fichier)) ;
			$tabLigne=explode(",",$ligne) ;
			//print_r($tabLigne) ;
			//echo "<br/>" ;
			if(sizeof($tabLigne)>1) {
				$nbTotal++ ;
				if($tabLigne[8]=="Football Masculin") {
					$sql = "select jou_id from " . TBL_JOUEUR . " where (lower(jou_nom) = '" . mb_strtolower(str_replace("'", "''", $tabLigne[4] . " " . $tabLigne[3]), 'UTF-8') . "') or (lower(jou_mail) = '" . strtolower($tabLigne[6]). "') ;" ;
					$result = $mysqli->query($sql) ;
					if(mysqli_num_rows($result)==0) {
						$sqlInsert = "insert into " . TBL_JOUEUR . " (jou_nom, jou_mail, jou_tel, jou_dro_id, jou_nb_conn, jou_password) values " .
							 " ('".ucwords(mb_strtolower(str_replace("'", "''", $tabLigne[4] . " " . $tabLigne[3]), 'UTF-8'))."', '".$tabLigne[6]."', '".$tabLigne[5]."', '3', '0', '') ;" ;
						$resultInsert = $mysqli->query($sqlInsert) ;
						$nbInsert++ ;
					} else {
						while ($row = mysqli_fetch_array($result)) {
							extract($row) ;
							$sqlUpdate = "update " . TBL_JOUEUR . " set jou_nom = '".ucwords(mb_strtolower(str_replace("'", "''", $tabLigne[4] . " " . $tabLigne[3]), 'UTF-8'))."', ".
											" jou_mail = '".$tabLigne[6]."', " .
											" jou_tel = '".$tabLigne[5]."' " .
										" where jou_id = '".$jou_id."' ;" ;
							$resultUpdate = $mysqli->query($sqlUpdate) ;
							$nbUpdate++ ;
						}
					}
				} else {
					$nbNonConcerne++ ;
				}
			}
		}
		fclose($fichier) ;
		echo "Nombre Total d'adh&eacute;rents : " . $nbTotal . "<br/>" ;
		echo "Nombre de joueurs ins&eacute;r&eacute;s : " . $nbInsert . "<br/>" ;
		echo "Nombre de joueurs mis &agrave; jour : " . $nbUpdate . "<br/>" ;
		echo "Nombre d'adh&eacute;rents non concern&eacute;s : " . $nbNonConcerne . "<br/>" ;
	}
?>
<form enctype="multipart/form-data" action="#" method="post">
      <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
      Transfère le fichier <input type="file" name="monfichier" />
      <input type="submit" />
    </form>
</CENTER>
</div>

