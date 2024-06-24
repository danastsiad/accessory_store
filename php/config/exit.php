<?php 
	session_start();
	session_destroy();
	header("Location: /accessory_store/index.php");
?>
