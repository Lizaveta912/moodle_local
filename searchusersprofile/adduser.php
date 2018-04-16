<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $(".js-example-basic-single").select2();
});
</script>

<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet"/>

<?php

require_once(dirname(__FILE__) . '/../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot . "/local/searchusersprofile/lib.php");
include '/../../_ldap/ldap.php';

require_login();

if (!has_capability('local/searchusersprofile:manage', context_system::instance())) {
    print_error('badpermissions');
}

$mysqli = new mysqli($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);
if ($mysqli->connect_errno) {
    echo "DB connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}
$mysqli->query("SET NAMES utf8");
//$mysqli->query("SET CHARACTER SET 'utf8';");
//$mysqli->query("set character_set_client='utf8'");
//$mysqli->query("set character_set_results='utf8'");
//$mysqli->query("set collation_connection='utf81_general_ci'");

function searchcohort_get() {
    global $DB, $CFG;
$sql = "SELECT `name` FROM `mdl_cohort`;";
$list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
return $list;
    }


$PAGE->set_url('/local/searchusersprofile/searchusersprofile.php', array(/* 'id' => 11111 */));
$PAGE->set_pagelayout('base');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Create New User', 'local_searchusersprofile'));
$PAGE->set_heading(get_string('Create New User', 'local_searchusersprofile'));

echo $OUTPUT->header();

$filter_lastname = isset($filter['realname']) ? $filter['realname'] : '';
$filter_name = isset($filter['realname']) ? $filter['realname'] : '';
$filter_faculty = isset($filter['realname']) ? $filter['realname'] : '';
$filter_password = isset($filter['realname']) ? $filter['realname'] : '';
$filter_login = isset($filter['login']) ? $filter['login'] : '';
$filter_group = isset($filter['groupp']) ? $filter['groupp'] : '';
$filter_year= isset($filter['groupid']) ? $filter['groupid'] : '';




$ldapconn = ldap_connect($ldaphost, $ldapport) or die("Die $ldaphost");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
if (!$ldapconn) {
    exit('Cannot connet to LDAP server');
}



$sr = ldap_search($ldapconn, $ldapsearchfaculty, "ou=*");
$info = ldap_get_entries($ldapconn, $sr);


$departments = array_map(function($xxx){
return [
'ou'=>$xxx['ou'][0],
'dn'=>$xxx['dn'],
'sort'=>join(',',array_reverse(explode(',',$xxx['dn'])))
];

}, $info);
unset($departments['count']);
usort($departments, function($a,$b){return strcmp($a["sort"], $b["sort"]);});
// var_dump($departments);

function faculty($ldapsearchfaculty,$departments){
 $res='';

$shift=count(explode(',',$ldapsearchfaculty));
foreach ($departments as $key => $mass) {
		 if ($key !==0){
           // var_dump($mass);
            $value= substr($mass['dn'], 0, strlen($mass['dn'])-strlen($ldapsearchfaculty)-1);
           // var_dump($value);
		$res = $res."<option value='{$value}'>".str_repeat ( "___" , count(explode(',',$mass['dn'])) - $shift -1 )."<b>{$mass['ou']}</b></option>";			
		}	
}

return $res;
 };



$faculty='faculty';

$searchcohort = searchcohort_get();

function searchcohort_show($searchcohort){
	$group='';
foreach ($searchcohort AS $row) {	
	$group =$group."<option>".$row->name."</option>";
}
return $group;
}

$searchcohort_show ='searchcohort_show';


echo "
<form action=\"adduser_form.php\" method=\"post\">

<h2 >

<div id=\"fgroup_id_realname_grp\" >
	<div class=\"fitemtitle\">
		<div class=\"fgrouplabel\">
		<label><b><h4>Прізвище</h4> </b></label>
		</div>
	</div>
	<fieldset class=\"felement fgroup\" id=\"\">
		
		<input name=\"lastname\" id=\"id_lastname\">
	</fieldset>
</div>

<div id=\"fgroup_id_realname_grp\" >
	<div class=\"fitemtitle\">
		<div class=\"fgrouplabel\">
		<label><b><h4>Имя, По батькові</h4> </b></label>
		</div>
	</div>
	<fieldset class=\"felement fgroup\" id=\"nnn\">
		
		<input name=\"firstname\"  id=\"id_firstname\">
	</fieldset>
</div>

<div id=\"fgroup_id_realname_grp\" >
	<div class=\"fitemtitle\">
		<div class=\"fgrouplabel\">
		<label><b><h4>Група(Контингент)</h4> </b></label>
		</div>
	</div>
	<fieldset class=\"felement fgroup\" id=\"nnn\">
		
		<h5><select name=\"cohort\" class=\"js-example-basic-single\">
		<option> </option>
		{$searchcohort_show($searchcohort)}
		</select></h5>
	</fieldset>
</div>

<div id=\"fgroup_id_realname_grp\" >
	<div class=\"fitemtitle\">
		<div class=\"fgrouplabel\">
		<label><b><h4>Факультет</h4> </b></label>
		</div>
	</div>
	<fieldset class=\"felement fgroup\" id=\"nnn\">
		
		<select name=\"department\" class=\"formel\">
	  	{$faculty($ldapsearchfaculty,$departments)}
		</select>
	</fieldset>
</div>

<div id=\"fgroup_id_realname_grp\" >
	<div class=\"fitemtitle\">
		<div class=\"fgrouplabel\">
		<label><b><h4>Логін</h4> </b></label>
		</div>
	</div>
	<fieldset class=\"felement fgroup\" id=\"nnn\">
		
		<input name=\"login\"  id=\"id_realname\">
	</fieldset>
</div>

<div id=\"fgroup_id_realname_grp\" >
	<div class=\"fitemtitle\">
		<div class=\"fgrouplabel\">
		<label><b><h4>Пароль</h4> </b></label>
		</div>
	</div>
	<fieldset  id=\"nnn\">
		
		 <h3><input  id=userpassword  name=userpassword value=''> <a href=\"#\" onclick=\"generatePass()\">generate</a></h3>
	</fieldset>
</div>

<div id=\"fgroup_id_realname_grp\" >
    <div class=\"fitemtitle\">
        <div class=\"fgrouplabel\">
        <label><b><h4>E-mail</h4> </b></label>
        </div>
    </div>
    <fieldset class=\"felement fgroup\" id=\"nnn\">
        
        <input name=\"mail\" id=\"id_mail\">
    </fieldset>
</div>

<div id=\"fgroup_id_realname_grp\" >
    <div class=\"fitemtitle\">
        <div class=\"fgrouplabel\">
        <label><b><h4>Телефон</h4> </b></label>
        </div>
    </div>
    <fieldset class=\"felement fgroup\" id=\"nnn\">
        
        <input name=\"mobile\" id=\"id_mobile\">
    </fieldset>
</div>
<br>
<div id='fitem_id_addfilter' class='fitem fitem_actionbuttons fitem_fsubmit ' style=\"clear:both;\">
	<div class='felement fsubmit'>
		<input value='Створити користувача' type='submit' id='id_addfilter'>
	</div>
</div>


</h2>
</form>
";


// $strSQLuser = "INSERT INTO mdl_user(lastname,firstname) values('" . $_POST["lastname"] . "," . $_POST["firstname"] . " ')";

// mysql_query($strSQLuser) or die(mysql_error());
// $result = mysqli_query($strSQLuser);
// if (!$result) {
//     die('Неверный запрос: ' . mysqli_error());
// }

// strHTML;
ldap_close($ldapconn);
echo $OUTPUT->footer();
?>



<style type="text/css">
    thead{
        border: 0;
    }
    table{
        border:0;
    }
    select{
    	height: 30px;
    line-height: 30px;
    display: inline-block;
    padding: 4px 6px;
    margin-bottom: 10px;
    font-size: 14px;
    color: #555;
    border-radius: 4px;
    vertical-align: middle;
    }

    td {

        padding-left: 20px;
        padding-right: 10px;
    }
    #n{
        height: 30px;
    }	

    tbody {
        border:0;
        margin: 0;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 20px;
        color: #333;
        background-color: #fff;
    }
</style>



<script>
function makeRand(max){
        // Generating random number from 0 to max (argument)
        return Math.floor(Math.random() * max);
}

function makeRandArray(){
return Math.random()-0.5;
}

function generatePass(){
        // password Lenght
        var length_b_s = 2;
    var length_s_s = 3;
        var length_n = 2;
        var length_s = 1;
        var result1 = [], result2 = [], result3 = [],result4 = [];
        // allowed characters
        var big_symbols = new Array(
                                'A','B','C','D','E','F','G','H','I','J',
                'K','L','M','N','P','Q','R','S',
                'T','U','V','W','X','Y','Z'   
                   );
        var small_symbols = new Array(
                    'a','b','c','d','e','f','g','h','i','j',
                'k','m','n','p','q','r','s',
                't','u','v','w','x','y','z'
                  );
        var numbers = new Array(2,3,4,5,6,7,8,9,0);
        var symbols = new Array('.',',',';',':','!','@','#','$','%','^','&','*','_','-','+','/','~');

        for (i = 0; i < length_b_s; i++){
                result1[i] = big_symbols[makeRand(big_symbols.length)];
        }
        for (i = 0; i < length_s_s; i++){
                result2[i] = small_symbols[makeRand(small_symbols.length)];
        }
        for (i = 0; i < length_n; i++){
                result3[i] = numbers[makeRand(numbers.length)];
        }
        for (i = 0; i < length_s; i++){
                result4[i] = symbols[makeRand(symbols.length)];
        }
//        alert( result1);
        var result = result1.concat(result2,result3,result4);

//        alert(typeof result);
//        var res = result.split('');
        result.sort(makeRandArray);
                console.log(result);
        // var res = result.toString();
        // res = res.match(/)
 //       for (var i=0; i<result.length;i++){}
        document.getElementById('userpassword').value = result.join('');
}
</script>


