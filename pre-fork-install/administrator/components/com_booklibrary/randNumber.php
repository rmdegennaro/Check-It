<?php 
/**
*
* @package BookLibrary
* @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com);
* Homepage: http://www.ordasoft.com
* @version: 3.0 Free
* @license GNU General Public license version 2 or later; see LICENSE.txt
**/

$path=$_GET["path"];
//$filename=explode('/',$_GET["file"]);
//$file=$path.$filename[count($filename)-1];
$filename=basename($_GET["file"]);
$file=$path.$filename;

//echo $file;
 if (file_exists($file))
 {	
 	echo "The file with such name already is!";
 }
else
{
	echo "";
	//echo "The file with such name is not present!"; 
}
 
