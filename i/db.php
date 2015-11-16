<?php

log2web('opening database');
try {
	$dbh=new PDO('sqlite:d/chat.db');
}catch(PDOException $e) {
	log2web($e->getMessage());
}


