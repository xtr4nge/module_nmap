<? 
/*
	Copyright (C) 2013-2014  xtr4nge [_AT_] gmail.com

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
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWifi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

<script>
function OnChangeType (obj){
    var scan;
    var target;
    
    target = document.getElementById("target").value;

    if (obj.value == 0) { 
        scan = "nmap " + target;
    } else if (obj.value == 1) { 
        scan = "nmap -T4 -A -v " + target;
    } else if (obj.value == 2) { 
        scan = "nmap -sn " + target;
    } else if (obj.value == 3) { 
        scan = "nmap -T4 -F " + target;
    } else if (obj.value == 4) { 
        scan = "nmap -sn --traceroute " + target;
    }
    
    form1.command.value = scan;

}

function OnChangeTarget (obj) {
    var target;
    var scan_type;
    //alert(document.getElementById("scan_type").value);
    
    target = document.getElementById("target").value;
    scan_type = document.getElementById("scan_type").value;
    
    //alert(obj.value);
    
    if (scan_type == 1) { 
        //alert(1);
        scan = "nmap -F " + target;
        //document.getElementById("command").value = scan;
        //document.getElementById("command").value = scan;
        form1.command.value = scan;
    }

    if (scan_type == 0) { 
        scan = "nmap " + target;
    } else if (scan_type == 1) { 
        scan = "nmap -T4 -A -v " + target;
    } else if (scan_type == 2) { 
        scan = "nmap -sn " + target;
    } else if (scan_type == 3) { 
        scan = "nmap -T4 -F " + target;
    } else if (scan_type == 4) { 
        scan = "nmap -sn --traceroute " + target;
    }

    form1.command.value = scan;


}
</script>

</head>
<body>

<?
include "_info_.php";
include "../../config/config.php";
include "../../login_check.php";
include "../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
}

$logfile = $_GET["logfile"];
$action = $_GET["action"];

// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "rm ".$mod_logs_history.$logfile.".log";
    exec("/usr/share/FruityWifi/bin/danger \"" . $exec . "\"", $dump);
}

?>

<? include "../menu.php"; ?>

<br>

<div class="rounded-top" align="left"> &nbsp; <b>Nmap</b> </div>
<div class="rounded-bottom">
    &nbsp;&nbsp;version <?=$mod_version?><br>
    &nbsp;&nbsp;&nbsp;&nbsp; nmap <font style="color:lime">installed</font>

</div>

<br>

<form id="form1" method="post" autocomplete="off">
    <div id="action" class="module">
        <ul>
            <li><a href="#action-1">General</a></li>
        </ul>
        <div id="action-1">
            Target: <input class="ui-widget" type="text" name="target" id="target" style="width:200px" onchange="OnChangeTarget(1)" onkeypress="OnChangeTarget(1)">
            <select id="scan_type" onchange="OnChangeType(this)">
			<option value=0>Default</option>
			<option value=1>Intense scan</option>
			<option value=2>Ping scan</option>
			<option value=3>Quick scan</option>
			<option value=4>Quick traceroute</option>
        </select>
        </div>
    </div>

    <div id="command" class="ui-widget module" style="width:100%;padding-top:4px; padding-bottom:4px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp 
            <input class="ui-widget" type="text" name="command" id="command" value="" style="width:200px">
            <input type="submit" name="submit" id="submit" value="Scan" class="ui-widget">
    </div>
</form>

<br>

<div id="result" class="module" >
    <ul>
        <li><a href="#result-1">Output</a></li>
        <li><a href="#result-2">History</a></li>
    </ul>
    <div id="result-1">
        <form id="formLogs" name="formLogs" method="POST" autocomplete="off">
        <br>
        <?
            if ($logfile != "" and $action == "view") {
                $filename = $mod_logs_history.$logfile.".log";
            } else {
                $filename = $mod_logs;
            }

            /*
            $fh = fopen($filename, "r"); //or die("Could not open file.");
            $data = fread($fh, filesize($filename)); // or die("Could not read file.");
            fclose($fh);
            */
            
            $data = open_file($filename);
            
            $data_array = explode("\n", $data);
            //$data = implode("\n",array_reverse($data_array));
            $data = implode("\n",$data_array);
            
        ?>
        <textarea id="output" class="module-content"><?=$data?></textarea>
        <input type="hidden" name="type" value="logs">
    </div>
    <div id="result-2">

        <?
        $logs = glob($mod_logs_history.'*.log');
        print_r($a);

        for ($i = 0; $i < count($logs); $i++) {
            $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
            echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete'><b>x</b></a> ";
            echo $filename . " | ";
            echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
            echo "<br>";
        }
        ?>
        
    </div>
</div>

<div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
    Loading...
</div>

<script>
$('#formLogs').submit(function(event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'includes/ajax.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (data) {
            console.log(data);
            $('#output').html('');
            $.each(data, function (index, value) {
                $("#output").append( value ).append("\n");
            });
            
            $('#loading').hide();

        }
    });
    
    $('#output').html('');
    $('#loading').show()

});

$('#loading').hide();
//.ajaxStart(function() {
//    $('#loading').hide();
//});

</script>

<script>
$('#form1').submit(function(event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'includes/ajax.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (data) {
            console.log(data);
            //$('#info1').html(data.msg);
            $('#output').html('');
            $.each(data, function (index, value) {
                //$("#output").append("<div>").append( value ).append("\div");
                //if (value != "") {
                    //$("#output").append( value ).append("<br>");
                    $("#output").append( value ).append("\n");
                //}
            });
            
            $('#loading').hide();
            //.ajaxStart(function() {
            //    $('#loading').hide();
            //});
        }
    });
    
    $('#output').html('');
    $('#loading').show()

});

$('#loading').hide();
//.ajaxStart(function() {
//    $('#loading').hide();
//});

</script>

</body>
</html>
