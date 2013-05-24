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
$API_KEY = "AIzaSyCImBgkm97rEIbZM1ESvueMrBsOxKDdUw0";

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

while(($data = fgetcsv($fh))!=false){
    $name = $data[0];
    $uid= $data[1];
    $site = "Logos";
    $url = "https://www.googleapis.com/plus/v1/people/$uid?key=$API_KEY";
    if(is_null($show) || (strcmp($site,$show) ==0)){

    $url = "https://plus.google.com/u/0/".$uid;
    $img="https://www.google.com/s2/photos/profile/$uid?sz=$img_size\"";

    if($cols==0) echo "<tr>";
    if(strcmp($uid,"null")!=0){
        $person = json_decode(file_get_contents("https://www.googleapis.com/plus/v1/people/$uid?key=$API_KEY"),true);
        foreach ($person['organizations'] as $orgi){ $org = $orgi['name']; $title = $orgi['title'];}

        echo "<td>
                 <a href = \"$url\">
                 <div class = \"photo\">
                     <img src =\"$img\" onerror=\"this.src='nogoogle.jpg';\">
                 </div>
                 <div class = \"info\"><strong>$name</strong><br/>$title</div></a>
            </td>";
        $org = "";
        $title = "";
    }

    if(strcmp($uid,"null")==0){
        echo "<td>
                 <a href = \"$url\">
                 <div class = \"photo\">
                 <img src =\"$img\" onerror=\"this.src='nogoogleaccount.jpg';\">
                 </div>
                 <div class = \"info\"><strong>$name</strong><br/>$title</div></a>
            </td>";
    }

    if($cols==($lim-1)) echo "</tr>";
    $cols++;$cols%=$lim;
    }
}
fclose($fh);
?>
</table>
</html>
