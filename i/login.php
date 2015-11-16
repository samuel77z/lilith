<?php

$userLogin=filter_var($_REQUEST['userLogin'],FILTER_SANITIZE_STRING);
log2web('string: '.$userLogin);
if($userLogin !== $_REQUEST['userLogin']){
 $loginHint='malicious attack detected, your username was not accepted';
 return;
}
$ulLen=strlen($userLogin);
if($ulLen<5 || $ulLen>30){
 $loginHint='username should be between 5 and 30 letters';
 return;
}
log2web('login attempt');

require_once 'i/db.php';

$validUser=false;
$stmt=$dbh->prepare('select passwd from users where user=?');
$stmt->execute(array($userLogin));
$row=$stmt->fetch();
$stmt->closeCursor();
if($row){
	log2web('got a user');
	log2web($row);
	if(password_verify($_REQUEST['userPW'],$row[0]))
	{
	 log2web('password matches');
	 $validUser=true;
	}
	else{
	$loginHint='wrong password';
	}
}else{
	log2web('have a new user');
	require_once 'i/chatParams.php';
	$nnu=getParameter('noNewUsers');
	if($nnu){
		$loginHint='no new users allowed today';
		log2web('no new users today');
	}else {
		$level=0;
		if(file_exists('d/install')){
			log2web('testing for superuser');
			$maybeAdmin=@file_get_contents('d/install');
			$maybeAdmin=trim($maybeAdmin);
			if($userLogin===$maybeAdmin)
			{
				log2web('got a superuser');
				$level=1000;
			}
		}
		$newPw=password_hash($_REQUEST['userPW'],PASSWORD_DEFAULT);
		$stmt=$dbh->prepare('insert into users values(?,?,?,"","UTC");');
		$stmt->execute(array($userLogin,$newPw,$level));
		$stmt->closeCursor();
		if($level==1000)
			unlink('d/install');
		$validUser=true;
	}
}
if($validUser)
{
 $loginHint='Hurra!';
	$stmt=$dbh->prepare('select level, color, timeZone from users where user=?');
	$stmt->execute(array($userLogin));
	$row=$stmt->fetch();
	$stmt->closeCursor();
	$userLevel=$row['level'];
	$userColor=$row['color'];
	$userTZ=$row['timeZone'];
	$newArray=array_merge($_REQUEST,$row,array(time()));
	log2web($newArray);
	$newValue=implode(',',$newArray);
	log2web($newValue);
	$userKey=md5($newValue);
	require_once 'i/cache.php';
	lockCache($userKey);
	keepInCache('userLogin','userLevel','userTZ');
}


