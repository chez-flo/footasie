<?php
	include("function.php") ;
	if(isset($_POST['ajoutMatch']) || 
			isset($_POST['report']) || 
			isset($_POST['razScore']) || 
			isset($_POST['modifArb'])) {
		if(!isAdmin()) {
			session_destroy() ;
			header('Location: index.php') ;
			exit();
		}
	}
	if(isset($_POST['majScore']) || 
			isset($_POST['annuleMatch']) || 
			isset($_POST['envScore'])) {
		if(!isAdmin() && !isCapitaine()) {
			session_destroy() ;
			header('Location: index.php') ;
			exit();
		}
	}
?>