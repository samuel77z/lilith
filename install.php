<?php 
// ***** output general headers ******
require_once 'v/headers.php';

// ***** this time we are logging directly to browser *****
function log2web($str) {
	if(is_string($str)) {
		$str=htmlspecialchars($str);
		echo'<li>'.$str.'</li>';
	}else {
		$str=print_r($str,true);
		$str=htmlspecialchars($str);
		echo'<li><pre>'.$str.'</pre></li>';
	}
	flush();
}

// ***** Headline *****
?><h2>installation proceeding</h2><ul>
<?php

// ***** let them follow immediately *****
while(0<$l=ob_get_level()){
 log2web('flushing level '.$l);
 ob_end_flush();
}

if(!is_dir('d')){
 log2web('creating directory "d"');
 mkdir('d',0777);
 chmod('d',0777);
}

// ***** require a file with the name of the first admin *****
$doInst=@file_get_contents('d/install');
if(!$doInst){
	die('<li>d/install not found. Add this file with the name of the first administrator');
}
$doInst=trim($doInst);
if(strlen($doInst)<1){
	die('<li>got an empty d/install');
}

log2web('future admin: '.$doInst);

// ***** checking a number of things first *****
if(!is_dir('c')){
	die('<li> create a directory "c" for caching sessions');
}
if(!is_writable('c')){
	die('<li> directory "c" must be writable for server');
}
if(!is_writable('d')){
	die('<li> directory "d" must be writable for server');
}
if(!file_exists('d/pipe') || !is_writable('d/pipe') || !is_readable('d/pipe')
 || (filetype('d/pipe')!=='fifo')){
 die('<li>fifo "d/pipe" does not exists');
 }
for($i=0;$i<4;++$i)
{
 if(!is_writable('d/lock'.$i)){
 die('<li> file d/lock'.$i.' is not writable');
 }
}

// ***** opening database *****
require_once 'i/db.php';

$installSQL= <<<EOD


create table if not exists users(user char(32) primary key, passwd char(255), level int, 
 color char(64), timeZone char(8));

create table if not exists chatParams(key char(32) primary key on conflict replace, value char(255));

create table if not exists events(kind char(8), data text);

insert into chatParams values('noNewUsers','0');

EOD;


foreach(explode(";",$installSQL) as $stmt)
{
	$stmt=trim($stmt);
	if(strlen($stmt)==0) 
		continue;
	$stmt .=';';
	log2web($stmt);
	$stmt=$dbh->prepare($stmt);
	if($stmt){
		$stmt->execute();
		$stmt->closeCursor();
	}
}

chmod('d/chat.db',0666);

// log2web(DateTimeZone::listIdentifiers());

date_default_timezone_set('Europe/Berlin');
