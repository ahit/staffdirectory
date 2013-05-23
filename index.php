<html>
<table>
<?php
$fh = fopen("staff.csv","r");
$cols = 0;
$lim = 4;

while(($data = fgetcsv($fh))!=false){
    $name = $data[0];
    $uid= $data[1];

    if($cols==0) echo "<tr>";
    if(strcmp($uid,"null")!=0){
         ?>
        <td><iframe src="http://www.google.com/s2/u/0/widgets/ProfileCard?uid=<?php echo $uid?>" scrolling="no" height="125" style="border:0px solid"></iframe></td>
    <?php
    }

    if(strcmp($uid,"null")==0){
        echo "<td>$name has no profile</td>";
    }

    if($cols==($lim-1)) echo "</tr>";
    $cols++;$cols%=$lim;
}
?>
</table>
</html>
