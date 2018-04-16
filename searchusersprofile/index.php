<?php

include '../../config.php';

require_login();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>moodle-user-password-change</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
<body>

<h1>Admin's password updater</h1>

<?php

require_capability('local/searchusersprofile:manage', get_context_instance(CONTEXT_SYSTEM, SITEID));
//phpinfo();


// connect to LDAP
$ldapconn = ldap_connect($ldaphost, $ldapport) or die("Die $ldaphost");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
if (!$ldapconn) {
    exit('Cannot connet to LDAP server');
}
$ldapbind = ldap_bind($ldapconn, $ldaplogin, $ldappass);


// connect to mysql
$mysqli = new mysqli($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);
if ($mysqli->connect_errno) {
    echo "DB connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}
$mysqli->query("SET NAMES utf8");



// update password
if(isset($_REQUEST['op']) && $_REQUEST['op']=='updatepassword'){
   $updates=Array("userpassword" => $_REQUEST['userpassword']);
   if (ldap_modify($ldapconn, $_REQUEST['dn'], $updates)) {
       echo "<div>SUCCESS</div>";
   }else{
       echo "<div>ERROR</div>";
   }
}


// find user
if(isset($_REQUEST['username'])){
   $uid=$_REQUEST['username'];
echo "<br>";
   // search person
   $filter = "(|(uid=$uid))";
   $sr = ldap_search($ldapconn, $ldapdnsearch, $filter);
   $info = ldap_get_entries($ldapconn, $sr);

  // echo "Data for " . $info["count"] . " items returned:<p>";
   error_reporting(E_ALL);
   for ($i=0; $i<$info["count"]; $i++) {
        //print_r($info[$i]);
        // serach in moodle database
        $sql="SELECT id, auth, lastlogin,lastaccess FROM mdl_user WHERE username='{$info[$i]['uid'][0]}'";
        //echo $sql;
        $res=$mysqli->query($sql);
        while ($user = $res->fetch_assoc()) {
          echo "<h2>moodle user found<br>";
          $muser=$user;
        }
        //print_r($muser);
        echo "<form action=index.php method=post>";
        echo "<input type=hidden name=op value='updatepassword'>";
        echo "<input type=hidden name=dn value='".htmlspecialchars($info[$i]["dn"])."'>";
        echo "<input type=hidden name=username value='".htmlspecialchars($uid)."'>";
        echo "<table>";
        echo "<tr><td><b>lastaccess: </b></td><td>".( $muser?date('d.m.Y H:i:s',$muser['lastaccess']):'moodle user not found' )."</td></tr>";
        echo "<tr><td><b>displayname: </b></td><td>{$info[$i]['displayname'][0]}</td></tr>";
        echo "<tr><td><b>dn: </b></td><td>" . $info[$i]["dn"] . "</td></tr>";
        echo "<tr><td><b>password </b></td><td><input type=text id=userpassword  name=userpassword value='".$info[$i]["userpassword"][0]."'><input type=submit value='update'> <a href=\"#\" onclick=\"generatePass()\">generate</a></td></tr>";
        echo "</table></form><hr/></h2>";
   }
   
   
}

ldap_close($ldapconn);

?>
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
</body>
</html>

<style type="text/css">
thead{
  border: 0;
}
table{
  border:0;
}
td {
  font-size: 12pt;
  padding-left: 30px;
}
#n{
  word-wrap: break-word;
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