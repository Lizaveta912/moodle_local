<?php  
require_once('../../config.php');

function searchuserprofile_add($ldapconn, $data){


$lastname = $data['lastname'];
$firstname = $data['firstname'];
$department = $data['department'];
$login = $data['login'];
$userpassword = $data['userpassword'];
// если логин и пароль введены,то обрабатываем их
$login = stripslashes($login);  // удаляем обратные слеши
$login = htmlspecialchars($login);  // преобразование спецсимволов
$userpassword = stripslashes($userpassword);
$userpassword = htmlspecialchars($userpassword);
// удаляем лишние пробелы
$login = trim($login);
$userpassword = trim($userpassword);

if( $test_dep = preg_match("/ou=20[0-2][0-9],ou=([a-z]+)/", $department)) echo 'Right written department';
var_dump($test_dep);
 $infoldap=[];
  $dn="uid={$login},ou=2013,ou=student,dc=znu,dc=edu,dc=ua";
  // $infoldap["dn"] = "UID={$login},OU=2013,OU=student,DC=znu,DC=edu,DC=ua";
  // $infoldap["ou"][0] ="student";
  // $infoldap["ou"][1] ="2013";
  $infoldap["cn"] = "{$lastname} {$firstname}";
  $infoldap["displayname"] = "{$lastname} {$firstname}";
  $infoldap["givenname"] = $firstname;
  $infoldap["mail"] =  "{$login}@local.znu";
  $infoldap["mobile"] = "222222222";
  //$infoldap["objectclass"][1] =  "organizationalUnit";
  $infoldap["objectclass"][0] =  "inetOrgPerson";
  $infoldap["objectclass"][1]=  "top";
  $infoldap["sn"] =  $lastname;
  //$infoldap["uid"] =  $login;
  $infoldap["title"] = "TESTTEST";
  $infoldap["userpassword"] = $userpassword;

  $r=ldap_add($ldapconn, $dn, $infoldap) or ">>Not able to load user <<";
  var_dump($r);


}


$ldapconn = ldap_connect($ldaphost, $ldapport) or die("Die $ldaphost");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
if (!$ldapconn) {
    exit('Cannot connet to LDAP server');
}

  ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
  $r= ldap_bind($ldapconn, $ldaplogin, $ldappass) or ">>Could not bind to $ldap_host to add user<<";
  var_dump($r);

$lastname = 'a1';
$firstname = 'a2';
$department = 'ou=2013,ou=econom';
$login = 'testusertest3';
$userpassword = 'testusertest';

searchuserprofile_add($ldapconn, ['lastname'=>$lastname, 'firstname'=>$firstname, 'department'=>$department, 'login'=>$login, 'userpassword'=>$userpassword]);




ldap_close($ldapconn);

?>