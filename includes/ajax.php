<? 
/*
    Copyright (C) 2013 xtr4nge [_AT_] gmail.com
    
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?php
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

//Put form elements into post variables (this is where you'd sanitize your data)
$field1 = $_POST['field1'];
$field1 = '1';

//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['error'] = false;

//Begin form validation functionality
if (!isset($field1) | empty($field1)){
	$return['error'] = true;
	$return['msg'] .= '<li>Error: Field1 is empty.</li>';
}


$target = $_POST["target"];

if ($target == "") {
    $target = "localhost";
} 

$exec = "/usr/bin/nmap -sS $target -p1-1024";
$exec = "/usr/bin/nmap -sS $target -p1-1024 -oN logs/".gmdate("Ymd-H-i-s").".log";

//exec("$bin_danger \"" . $exec . "\"", $dump); //DEPRECATED
$dump = exec_fruitywifi($exec);
//$dump = implode("\n",$dump);


//Return results (array)
echo json_encode($dump);
?>
