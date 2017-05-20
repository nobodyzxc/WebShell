<?php if(!isset($_COOKIE["login"])){
        header("Location:./login.php");
        }
    else{
	}
?>
<html>
<!-- <script src="jquery.js"></script> -->
<!-- jQuery JavaScript Library v1.7.2 http://jquery.com/ -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<!-- <script src="jquery.form.js"></script> -->
<!-- version: 3.51.0-2014.06.20 https://github.com/malsup/form -->
<script src="https://cdn.jsdelivr.net/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>

<script src="inputCtrl.js"></script>

<?php
//normal setting
require "./shrc.php";
//post setting
$pwd = $_POST['pwd'];
if ($pwd == null || !$pwd) $pwd = substr($homeDir , 0 , strlen($homeDir) - 1);
$command = ($_POST['command']);
$account = $_POST['account'];
$firstUse = false;
if ($account == null || !$account){
    $firstUse = true;
    $account = $defaultACT;
}
?>


<head>
    <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
</head>
<body style="background-color:black">
    <div id="stdout" class="stdout">
<?php

if($command == "cls" || $command == "clear" || $command == "l") system('> '.$showFile);//cat /dev/null > tmp.txt || echo -n '' > xxx
else if($command !== null && $command){
    //    $command = 'cd '.$pwd.' && ('.$setPath.' && HOME='.$homeDir.' && '.$command.' && pwd > '.$pwdFile.')';
    $command = str_replace("ls" , "ls -C -F -x" , $command);

    //$command = str_replace(' $' , ' \$' , $command); if add , will echo $HOME instead of env HOME , but something wired on show long long command
    $command = "cd ".$pwd." && (".$setHome.' '.$setPath.' '.$setCol.$cntCmd.$command." && pwd > ".$pwdFile.")";
    if($account == 'nobody'){
        $cmd = $command.' >> '.$showFile.' 2>&1';
    }
    else{
        $cmd = 'echo "'.str_replace(' $' , '\$' , $command).'" | '.$compRoute.$account.' >> '.$showFile.' 2>&1';
    }

    //system('echo "$ '.$command.'" >> '.$showFile);//all command

    //abbrev home path
    $pspwd = str_replace($tilde , "~" , $pwd);
    system('echo "<span style=\"color:#38FF41\">'.$account.'@'.$host.' </span><span style=\"color:#AAB7ED\">'.$pspwd.' </span></span><span style=\"color:#AAB7ED\">$</span>&nbsp<span style=\"color:white\">'.str_replace(' $' , ' \$' , $_POST['command']).'</span>" >> '.$showFile);


    system($cmd);
    //    system('echo "" >> '.$showFile);

    //reset pwd
    if(is_file($pwdFile)){
        $myfile = fopen($pwdFile , 'r');
        $pwd =  fgets($myfile);
        fclose($myfile);
    }
    else{
        $pwd = $homeDir;
    }

}

function replace_spaces($string)
{
    for( $i = 0, $in_tag = false; $i < strlen($string); $i++ )
    {
        if(( $string{$i} == ' ' ) && !$in_tag )
            $string = substr_replace($string, '&nbsp;', $i, 1);

        else if( $string{$i} == '<' ) $in_tag = true;
        else if( $string{$i} == '>' ) $in_tag = false;
    }

    return $string;
}
function strendpos ($haystack, $needle) {
        $pos = strpos($haystack, $needle);
            if ($pos) {
                        $pos += strlen($needle);
                            }
            return $pos;
}

function check_replace($line , $ch){
    $rtn = strpos($line , $ch.' ');
    if($rtn)
        return $rtn;
    $rtn = strpos($line , $ch.'\n');
    if($rtn)
        return $rtn;
    if(substr($line , -2) == $ch.chr(10))
        return strlen($line);//chr(10) == \n

    return 0;
}

function lsColor($line , $mark , $clr){
    while($pos = check_replace($line , $mark)){
        $line = substr_replace($line, "</span>" , $pos + 1 , 0);
        while($pos != 0 && $line[$pos] != ' ') $pos -= 1;
        $line = substr_replace($line, '<span style="color:'.$clr.'">' , $pos , 0);
    }
    return $line;
}

if(is_file($showFile)){
    echo '<p class="terminal">';
    $myfile = fopen($showFile , 'r');

    $isLs = 0;
    while(!feof($myfile)) {
        $line =  fgets($myfile);
        $isPs = strpos($line , "span") && strpos($line , 'style="color:#AAB7ED"') &&
            strpos($line , 'style="color:#38FF41"') && strpos($line , "@") && strpos($line , "$");
        if($isPs){//if is prompt
            echo $line.'<br>';
           // echo '^^^ps<br>';
            $isLs = strpos($line , "ls");
           // if($isLs) echo 'vvvv Ls<br>';
            if(!strpos($line , '">ls') && (!strpos($line , " ls"))) $isLs = 0;
        }
        else if(strcmp($line , "")){
            if($isLs){
                $pos = 0;
                // span / #AAB7ED
                $line = lsColor($line , '/' , '#AAB7ED');
                // span * #38FF41
                $line = lsColor($line , '*' , '#38FF41');
                // span @ #2EFFFF
                $line = lsColor($line , '@' , '#2EFFFF');
                // span = #FF40FF
                $line = lsColor($line , '=' , '#FF40FF');
                // span | pipe
                $line = lsColor($line , '|' , '#BFBF00');
                // span > door

            }
//            $line = preg_replace('/>(.*)</', '&nbsp;', $line);
//            $line = str_replace(" ","&nbsp",$line);//mv space to &nbsp //location ...
            $line = replace_spaces($line);
            echo $line;
            echo '<br>';
        }
    }
    fclose($myfile);
    $pspwd = str_replace($tilde , "~" , $pwd);
    echo '<span id="psUsr" style="color:#38FF41">'.$account.'@'.$host.'</span>&nbsp<span id="pspwd"style="color:#AAB7ED">'.$pspwd.' </span></span><span style="color:#AAB7ED">$ </span><span id="pscmd" style=\"color:white\"></span>';
    echo '</p>';
}
?>
    </div><!-- end of div.stdout-->
<script>
function saveReport() {
    var parser , doc;
    $("#tcl").ajaxSubmit(function(message){
        doc = document.createElement('html');
        doc.innerHTML = message;
        if(doc.getElementsByClassName("pwd").length == 0){
            location.href = "../login.php?redir=shell"
        }
        $("#pwd").attr("value" , doc.getElementsByClassName("pwd")[0].value);
        $("#f").attr("value" , doc.getElementsByClassName("f")[0].value);
        $("#stdout").html($(doc.getElementsByClassName("stdout")).html());
        $("html, body").animate({ scrollTop: $(document).height() }, 0);
    });
    return false;//if rtn false -> submit form but not reload page
}
</script>

<div class="fixed" style="background-color:#BDBDBD">
    <form id="tcl" action="<?php echo basename(getenv('SCRIPT_NAME'))?>" method="post" onsubmit="return saveReport();">

    l,cls,clear to clean<br>

<?php
    echo '&nbsp&nbsppwd:&nbsp<input id="pwd" name="pwd" class="pwd" size=50 value="'.$pwd.'">';
?>

    <button id="home" type="button">Home</button>
    <select id="chooseUsr" class="chooseUsr" name="account">
<?php
    $compFiles = glob($compRoute."*");
    foreach($compFiles as $compF){
        $compF = pathinfo($compF , PATHINFO_BASENAME);
        if($compF != "readme.md"){
            echo '<option value="'.$compF.'"';
            if(($account) == $compF)
                echo ' selected';
            echo '>'.$compF.'</option>';
        }
    }
?>
    </select>
    <br>
    prompt:<input  id="f" class="f" type="text" name="command" size=50 autocomplete="off" autofocus>
    <?php $ac = $_POST['autocom']; ?>
    <input id='cbox' type="checkbox" name="autocom" value="1" <?php if($ac == "1") echo 'checked'?>><label for='cbox'>ATCOM</label>
    <?php $cf = $_POST['cursorFix']; ?>
    <input id='cursorFix' type="checkbox" name="cursorFix" value="1" <?php if($cf == "1" || $firstUse) echo 'checked'?>><label for='cursorFix'>CURFIX</label>
    <button id='tclEnter' type="submit hidden" tabindex="-1">Submit</button>
</form>
</div>
<div style="height:55px"></div>
</body>
