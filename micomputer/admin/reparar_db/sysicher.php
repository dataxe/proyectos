<?php
//-------- start compress function ----------------------------------
function gzip($src, $level = 5, $dst = false)
{
 if($dst == false)
 {
  $dst = $src.".gz"; //echo $dst."<BR>";
 }
 if(file_exists($src))
 {
  $filesize = filesize($src);
  $src_handle = fopen($src, "r");
  if(!file_exists($dst))
  {
   $dst_handle = gzopen($dst, "w$level");
   while(!feof($src_handle))
   {
    $chunk = fread($src_handle, 2048);
    gzwrite($dst_handle, $chunk);
   }
   fclose($src_handle);
   gzclose($dst_handle);
   return $dst;
  }
  else
  {
   error_log("$dst already exists");
  }
 }
 else
 {
 error_log("$src doesn't exist"); echo "none<BR>";
 }
return $dst;
}

//-------- end compress function ------------------------------------

include("config.php");

$lang = "en";                 // Sprache  de / en
$use_date = 1;                // Backupdateiname mit Datum versehen 0 = nein / 1 = ja
$path="../";						

$conn_id = mysql_connect($host,$user,$pw) or die(mysql_error());
$database = $_POST["db_namesi"];
$zaehler = 0;
$start=0;

echo "<table width=520 cellspacing=5 cellpadding=5 border=1 bordercolor=\"#EFEFEF\">";
echo "<TR><td>";

//------------- generate file suffix if it should be used -----------
if($use_date == 1)
{
	$datum = "_".date(mdY);	
}
else
{
	$datum = "";	
}

 $file_name = $path.$database.$datum.".sql";
 $screen_name = $database.$datum.".sql";
 $file_old = $path.$database.".old";
 $aktime=date("d-m-Y H:i");
 $db_name = $dump1."$aktime ";
 $db_name.= $dump2."DATABASE $database \r\n";
 $strcomm = "//------- ";
 if (file_exists($file_name)){unlink($file_name);}  
 $fd = fopen($file_name,"a+");
 fwrite($fd, $strcomm.$db_name); 
 fclose($fd);     
 
//----------------- read table name array ---------------------------

$tbl_array = array(); $c = 0;
$result2 = mysql_list_tables($database);
for($x=0; $x<mysql_num_rows($result2); $x++) 
{ 	
 $tabelle = mysql_tablename($result2,$x);
 if ($tabelle <>"")
 {
//  echo $tabelle."<BR>";
  $tbl_array[$c] = mysql_tablename($result2,$x); $c++;$zaehler++;
 }
}								 
flush();
//---------------- start output and calculation ---------------------
for ($y = 0; $y < $c; $y++){  
	$tabelle=$tbl_array[$y];

//------------- read table structure --------------------------------

    $def = "";
    $def .= "DROP TABLE IF EXISTS $tabelle; \n";
    $def .= "CREATE TABLE $tabelle (\n"; 
    $result3 = mysql_db_query($database, "SHOW FIELDS FROM $tabelle",$conn_id);
    while($row = mysql_fetch_array($result3)) {
        $def .= "    $row[Field] $row[Type]";
        if ($row["Default"] != "") $def .= " DEFAULT '$row[Default]'";
        if ($row["Null"] != "YES") $def .= " NOT NULL";
       	if ($row[Extra] != "") $def .= " $row[Extra]";
        	$def .= ",\n";
     }
     $def = ereg_replace(",\n$","", $def);
     $result3 = mysql_db_query($database, "SHOW KEYS FROM $tabelle",$conn_id);
     while($row = mysql_fetch_array($result3)) {
          $kname=$row[Key_name];
          if(($kname != "PRIMARY") && ($row[Non_unique] == 0)) $kname="UNIQUE|$kname";
          if(!isset($index[$kname])) $index[$kname] = array();
          $index[$kname][] = $row[Column_name];
     }
     while(list($xy, $columns) = @each($index)) {
          $def .= ",\n";
          if($xy == "PRIMARY") $def .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
          else if (substr($xy,0,6) == "UNIQUE") $def .= "   UNIQUE ".substr($xy,7)." (" . implode($columns, ", ") . ")";
          else $def .= "   KEY $xy (" . implode($columns, ", ") . ")";
     }

     $def .= "\n); \n";
     
//-------------------- end structure module -------------------------

$db = @mysql_select_db($database,$conn_id); 

$tabelle="".$tabelle; 
$ergebnis=array();
$tbl_name = $dump3."$tabelle \r\n"; 
$strcomm = "//------- TABLE ";
$fd = fopen($file_name,"a+"); 
fwrite($fd, $strcomm.$tbl_name.$def); 
fclose($fd);

	unset($data);
if ($tabelle>""){  
    $ergebnis[]=@mysql_select_db($database,$conn_id); 
    $result=mysql_query("select * from $tabelle"); 
        $anzahl= mysql_num_rows ($result); 
    $spaltenzahl = mysql_num_fields($result); 
        for ($i=0;$i<$anzahl;$i++) { 
                $zeile=mysql_fetch_array($result); 
        
                $data.="INSERT INTO $tabelle ("; 
        for ($spalte = 0; $spalte < $spaltenzahl;$spalte++) { 
              $feldname = mysql_field_name($result, $spalte); 
              if($spalte == ($spaltenzahl - 1)) 
          { 
            $data.= $feldname; 
          } 
          else 
          { 
            $data.= $feldname.","; 
          } 
        }; 
        $data.=") VALUES ("; 
                for ($k=0;$k < $spaltenzahl;$k++){ 
          if($k == ($spaltenzahl - 1)) 
          { 
                        $data.="'".addslashes($zeile[$k])."'"; 
                  } 
          else 
          { 
                        $data.="'".addslashes($zeile[$k])."',"; 
                  } 
        } 
                $data.= ");\n"; 
        } 
$data.= "\n";
echo $tabelle."<BR>";
} 
else 
{ 
      $ergebnis[]= $err; 
} 

$zeit = (date("d_m_Y")); 
$fd = fopen($file_name,"a+"); 
$zeit = time() - $start;
$speed = $speed+$zeit;

//----------------------- write SQL file ----------------------------

for ($i3=0;$i3<count($ergebnis);$i3++){ 

		fwrite($fd, $data); 
        fclose($fd);	
} 
}
echo "</td></tr>";
echo "</table>";

//------------- compress file ---------------------------------------
$gzip_name = gzip($file_name);

//-------------------- table output ---------------------------------
$groesse = filesize($file_name) / 1024;
$place =  $place+$groesse;
echo "<table width=520 cellspacing=5 cellpadding=5 border=1 bordercolor=\"#EFEFEF\">";
echo "<TR><td><font color=#6F6969><B>Database</B></font></td><td>".$database."</td></tr>";
echo "<TR><td><font color=#6F6969><B>Tables</B></font></td><td>".$zaehler."</td></tr>";
echo "<TR><td><font color=#6F6969><B>File Name</B></font></td><td>".$screen_name."</td></tr>";
echo "<TR><td><font color=#6F6969><B>Location</B></font></td><td>/public_html/backup/</td></tr>";
echo "<TR><td><font color=#6F6969><B>Total File Size</B></font></td><td>".number_format($groesse,2)." KB</td></tr>";
echo "<TR><td><font color=#6F6969><B>Status</B></font></td><td><font color=red><b>OK</b></font></td></tr>";
echo "<TR><td><font color=#6F6969><B>Download</B></font></td><td><a href=\"".$file_name."\">View / Download SQL</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$gzip_name."\">Download ZIP</a></td></tr>";
echo "</table><BR><BR><BR><BR><BR>";
?>
