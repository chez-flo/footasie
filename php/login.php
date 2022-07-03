<?php 
	session_start() ;
	include("conf.php") ;
	include("bdd.php") ;
	$login = mysqli_real_escape_string($mysqli,$_POST["login"]) ;
	//$login = addcslashes($login, '%_');
	$pass = md5($_POST["pass"]) ;
	$query = "SELECT jou_mail, dro_nom, dro_validate, ec_eq_id, jou_id FROM " . TBL_JOUEUR . ", " . TBL_DROIT . ", " . TBL_EQUIPE_CORRESP . " WHERE jou_dro_id = dro_id and ec_jou_id = jou_id and jou_mail = '$login' AND jou_password = '$pass'" ;
	$result = $mysqli->query($query) ;
	$i=0 ;
	$eq_id_corresp=0 ;
	if($row = mysqli_fetch_array($result)) {
		extract($row) ;
		$_SESSION["id"] = $jou_id ;
		$_SESSION["mail"] = $jou_mail ;
		$_SESSION["tab_eq_id"][$i] = $ec_eq_id ;
		$_SESSION["tab_droit"][$i] = $dro_nom ;
		$_SESSION["tab_validate"][$i] = $dro_validate ;
		$eq_id_corresp = $ec_eq_id ;
		$i++ ;
	} 
	$query = "SELECT jou_mail, ejt_eq_id, jou_id FROM " . TBL_JOUEUR . ", " . TBL_EJT . " WHERE ejt_jou_id = jou_id and jou_mail = '$login' AND jou_password = '$pass' and ejt_sai_annee = '".SAISON."' order by ejt_typ_id ;" ;
	$result = $mysqli->query($query) ;
	while($row = mysqli_fetch_array($result)) {
		extract($row) ;
		if($ejt_eq_id!=$eq_id_corresp) {
			$_SESSION["id"] = $jou_id ;
			$_SESSION["mail"] = $jou_mail ;
			$_SESSION["tab_eq_id"][$i] = $ejt_eq_id ;
			$_SESSION["tab_droit"][$i] = "Joueur" ;
			$_SESSION["tab_validate"][$i] = "ok_jou" ;
			$i++ ;
		}
	} 
	if($i>0) {
		$_SESSION["nb_eq"] = $i ;
		$_SESSION["utilisateur"] = $login ;
		$_SESSION["droit"] = $_SESSION["tab_droit"][0] ;
		$_SESSION["eq_id"] = $_SESSION["tab_eq_id"][0] ;
		$_SESSION["validate"] = $_SESSION["tab_validate"][0] ;		
		$queryU = "update " . TBL_JOUEUR . " set jou_nb_conn = jou_nb_conn+1 where jou_id = '".$_SESSION["id"]."'" ;
		$mysqli->query($queryU) ;
		if($_SESSION["validate"] == "ok_admin") {
			header("Location: index.php") ;
			exit();
		} else {
			header("Location: index.php?op=eq&id=".$_SESSION["eq_id"]) ;
			exit();
		}
	} else {
		$_SESSION["validate"] = "ko_erreur" ;
		header("Location: index.php?op=id") ;
		exit ;
	}
?>	