<?php if(!isset($_COOKIE["login"])){
        header("Location:./login.php");
        }
    else{    
	}
?>
<script src="jquery.min.js"></script>
<?php
//normal setting
require "./shrc.php";
//post setting
$pwd = $_POST['pwd'];
if ($pwd == null || !$pwd) $pwd = substr($homeDir , 0 , strlen($homeDir) - 1);
$command = ($_POST['command']);
$account = $_POST['account'];
$firstUse = false;
if ($account == null || !$account)
    $firstUse = true;
    $account = "nobody";
?>


<script>
$(window).load(function() {
    $("html, body").animate({ scrollTop: $(document).height() }, 0);//1000
});
$(document).ready(function(){

    $('#home').click(function(){
        $('#pwd').val(function(n , c){
            return "/home1/student/stud104/s10410/public_html/shell";
        });
        $('#pspwd').text("~ ");
    });
    $('#chooseUsr').change(function(){
        $('#psUsr').text($('option:selected').text() + $('#psUsr').text().substr($('#psUsr').text().indexOf('@')));
    });
    if($('#cbox').attr('checked')){
        $('#f').attr('autocomplete' , 'on');
    }
    $('#cbox').change(function(){
        if($(this).is(":checked"))
            $('#f').attr('autocomplete' , 'on');
        else
            $('#f').attr('autocomplete' , 'off');
    });
    $('#cursorFix').change(function(){
        if($(this).is(":checked")){//pls identity different between prop , attr
            $('#cursorFix').attr('checked' , true);//set .prop() failed , why?
            $('#cursorFix').attr('value' , 1);
            $('#f').focus();
        }
        else{
            $('#cursorFix').attr('checked' , false);
            $('#cursorFix').attr('value' , 0);
        }
    });
    $('#f').focusout(function(){
        if($('#cursorFix').attr('checked')){
            $('#f').focus();
            //alert("hello");
        }
    });
    $('#f').on('input' , function(){
        $('#pscmd').text($('#f').val());
    });
});
</script>

<head>
    <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
</head>
<body style="background-color:black">
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
    else $pwd = $homeDir;

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
///    if($isLs) echo '<br>';
    $pspwd = str_replace($tilde , "~" , $pwd);
    echo '<span id="psUsr" style="color:#38FF41">'.$account.'@'.$host.'</span>&nbsp<span id="pspwd"style="color:#AAB7ED">'.$pspwd.' </span></span><span style="color:#AAB7ED">$ </span><span id="pscmd" style=\"color:white\"></span>';
    echo '</p>';
}
?>
<div class="fixed" style="background-color:#BDBDBD">
    <form  action="<?php echo basename(getenv('SCRIPT_NAME'))?>" method="post">

    l,cls,clear to clean<br>
    
<?php
    echo '&nbsp&nbsppwd:&nbsp<input id="pwd" name="pwd" size=50 value="'.$pwd.'">';
?>

    <button id="home" type="button">Home</button>
    <select id="chooseUsr" name="account">
<?php
    $compFiles = glob($compRoute."*");
    foreach($compFiles as $compF){
        $compF = pathinfo($compF , PATHINFO_BASENAME);
        echo '<option value="'.$compF.'"';
        if(($account) == $compF)
            echo ' selected';
        echo '>'.$compF.'</option>';
    }
?>
    </select>
    <br>
    prompt:<input  id="f" type="text" name="command" size=50 autocomplete="off" autofocus>
    <?php $ac = $_POST['autocom']; ?>
    <input id='cbox' type="checkbox" name="autocom" value="1" <?php if($ac == "1") echo 'checked'?>><label for='cbox'>ATCOM</label>
    <?php $cf = $_POST['cursorFix']; ?>
    <input id='cursorFix' type="checkbox" name="cursorFix" value="1" <?php if($cf == "1" || $firstUse) echo 'checked'?>><label for='cursorFix'>CURFIX</label>
    <button type="submit hidden" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1">Submit</button>
</form>
</div>
<div style="height:55px"></div>
</body>
