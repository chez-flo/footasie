<?php
	$file=$_GET['file'];
	header("Content-Description: File Transfer");
	header( "Content-type: application/force-download" ) ;
	header('Content-Disposition: attachment; filename="'.str_replace("ical/files/", "", $file).'"');
	readfile($file);
	unlink($file);
	exit();
?>