<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
Show only:
<a href = "index.php?show=Logos">Logos</a>
<a href = "index.php?show=AHIS">AHIS</a>
<a href = "index.php?show=Admin">Admin</a>
<a href = "index.php?show=V&O">V&O</a>
<a href = "index.php?show=KCIS">KCIS</a>
<a href = "index.php">Everyone</a>
<table>
<?php
require_once("/google-api-php-client/src/Google_Client.php");
require_once("/google-api-php-client/src/contrib/Google_PlusService.php");
require_once '/google-api-php-client/src/cache/Google_Cache.php';
require_once '/google-api-php-client/src/cache/Google_FileCache.php';

$API_KEY = "AIzaSyCImBgkm97rEIbZM1ESvueMrBsOxKDdUw0";
$client = new Google_Client();
$client->setDeveloperKey($API_KEY);
$plus = new Google_PlusService($client);

$cache = new Google_FileCache();
$cache_time = 43200; // 12 hours

if(!empty($_GET['show']))
	$show = $_GET['show'];
else
	$show = null;
if(!empty($_GET['lim']))
	$lim = $_GET['lim'];
else
	$lim = 6;


$fh = fopen("staff.csv","r");
$cols = 0;
$img_size = 120;
$img_url;
$title="";

while(($data = fgetcsv($fh))!=false){
	if($cols==0) echo "<tr>";

	$person = $data['1'];
	$name = $data['0']; //fallback if they don't have Google+
	//assuming they have a Google+ ID
	if(strcmp($person,"null")!=0){

		$img="https://www.google.com/s2/photos/profile/$person?sz=$img_size\"";

		//grab info about people
		if($cache->get($person,$cache_time)){
		    $me = $cache->get($person);
		    $file_status = "cache";
		}
		else{
		    $me = $plus->people->get($person);
		    $cache->set($person,$me);
		    $file_status = "live";
		}

		//make a best effort at determining their title
		if(!empty($me['organizations'])){
			foreach($me['organizations'] as $org){
				if(!empty($org['title']))
					if(strcmp($org['title'],"Asian Hope") == 0)$title = $title." ".$org['title'];
				}
		}

		//output their cards
		echo "<td>
		<a href = \"{$me['url']}\">
		<img src =\"$img\" onerror=\"this.src='nogoogle.jpg';\">
		<br/><br/>
		{$me['displayName']}<br/>$title</a>
		</td>";

		$title = "";
	}
	elseif(strcmp($person,"null")==0){
		echo "<td><img src = \"nogoogleaccount.jpg\"><br/><br/>$name</td>";
	}

	if($cols==($lim-1)) echo "</tr>";
	$cols++;$cols%=$lim;

}

?>
