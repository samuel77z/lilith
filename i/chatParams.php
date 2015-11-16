<?php
require_once 'i/db.php';

function getParameter($key) {
	global $dbh;
	$stmt=$dbh->prepare('select value from chatParams where key=?;');
	$stmt->execute(array($key));
	$row=$stmt->fetch();
	$stmt->closeCursor();
	if($row){
		return $row[0];
	} else {
		return false;
	}
}

function storeParameter($key,$value){
 global $dbh;
 $stmt=$dbh->prepare('insert into chatParams values(?,?);');
 $stmt->execute(array($key,$value));
}
