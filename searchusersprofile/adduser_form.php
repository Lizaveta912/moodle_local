<?php
 if (file_exists('../../config.php'))
    require_once('../../config.php');
else
    die;
require_login();
require_once($CFG->dirroot . "/local/searchusersprofile/lib.php");


if (!has_capability('local/searchusersprofile:view', context_system::instance())) {
    print_error('badpermissions');
}


$dbconnect = mysqli_connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass ,$CFG->dbname);
if (!$dbconnect) 
{
  echo( "<P>В настоящий момент сервер базы данных не доступен, нет соединения,
  поэтому корректное отображение страницы невозможно.</P>" .mysqli_connect_error());
  exit();
}
if (!mysqli_select_db($dbconnect, $CFG->dbname)) 
{
  echo( "<P>В настоящий момент база данных не доступна, поэтому
            корректное отображение страницы невозможно.</P>" );
  exit();
}
// mysqli_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbconnect);
mysqli_query($dbconnect,"SET NAMES utf8");


$ldapconn = ldap_connect($ldaphost, $ldapport) or die("Die $ldaphost");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
if (!$ldapconn) {
    exit('Cannot connet to LDAP server');
}



// $sr = ldap_search($ldapconn, $ldapsearchfaculty, "ou=*");
// $infoldap = ldap_get_entries($ldapconn, $sr);


$PAGE->set_url('/local/searchusersprofile/searchusersprofile.php', array(/* 'id' => 11111 */));
$PAGE->set_pagelayout('base');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Create New User', 'local_searchusersprofile'));
$PAGE->set_heading(get_string('Create New User', 'local_searchusersprofile'));

echo $OUTPUT->header();


$lastname = $_REQUEST['lastname'];
$firstname = $_REQUEST['firstname'];
$cohort = $_REQUEST['cohort'];
$department = $_REQUEST['department'];
$login = $_REQUEST['login'];
$mobile = $_REQUEST['mobile'];
$mail = $_REQUEST['mail'];
$userpassword = $_REQUEST['userpassword'];

if(!preg_match("/ou=20[0-2][0-9],ou=([a-z]+)/", $department)) {
    exit ("Не вірний факультет");
}

// проверяем существует ли переменная логин
if (!isset($_POST['login'])) {
    exit ("Логин не передан");
}
else {  $login = $_POST['login'];   }

if (!isset($_POST['userpassword'])) {
    exit ("Пароль не передан");
}
else {  $userpassword = $_POST['userpassword']; }

if (empty($login)) {
    exit ("Відсутній логін, поверніться та заповніть це поле.");
}

if(strlen($login) < 3 or strlen($login) > 15) {
    exit("Логін повинен містити не меньш ніж 3 та не більше 15 символів");
}
if (preg_match('/[^а-яА-Я\s]+/msi', $login)) {
    exit("Логін повинен містити тільки ангійські літери та не містити пробілу"); }

// если логин и пароль введены,то обрабатываем их
$login = stripslashes($login);  // удаляем обратные слеши
$login = htmlspecialchars($login);  // преобразование спецсимволов
$userpassword = stripslashes($userpassword);
$userpassword = htmlspecialchars($userpassword);
// удаляем лишние пробелы
$login = trim($login);
$userpassword = trim($userpassword);
// проверяем заполнен ли пароль
if (empty($userpassword)) {
    exit ("Відсутній пароль, поверніться та заповніть це поле");
}
// проверяем длину пароля
if(strlen($userpassword) < 8 or strlen($userpassword) > 15) {
    exit("Пароль повинен містити не меньш ніж 8 та не більше 15 символів");
}
 
if (empty($lastname)) {
    exit ("Відсутнє ім'я, поверніться та заповніть це поле.");
}
if (empty($firstname)) {
    exit ("Відсутнє прізвище, поверніться та заповніть це поле.");
}
if (empty($mail)) {
    exit ("Відсутній e-mail, поверніться та заповніть це поле.");
}
// проверка на существование пользователя с таким же логином
// вернем все поля столбца id, значение поля которых равно $login;
$result = MYSQLI_QUERY($dbconnect,"SELECT id FROM mdl_user WHERE username='$login'");
$myrow = mysqli_fetch_array($result);
if (!empty($myrow['id'])) {
    exit ("Вибачте, але введений вами логін вже зареєстрований. Введіть інший логін.");
echo "<br>";
echo $login." ";
echo $userpassword." ";
}

$dat=date(time());
$lastname = mysqli_real_escape_string($dbconnect, $lastname);
// echo $dat, $lastname;
$firstname = mysqli_real_escape_string($dbconnect,$firstname);
$cohort = mysqli_real_escape_string($dbconnect,$cohort);
$department = mysqli_real_escape_string($dbconnect,$department);
$login = mysqli_real_escape_string($dbconnect,$login);
$mobile = mysqli_real_escape_string($dbconnect,$mobile);
$mail = mysqli_real_escape_string($dbconnect,$mail);
$userpassword = mysqli_real_escape_string($dbconnect,$userpassword);

// записываем данные в  таблицу базі
$result1 = MYSQLI_QUERY($dbconnect, "INSERT INTO `mdl_user` ( auth, confirmed,  policyagreed, deleted,  suspended, mnethostid,  username,  password, idnumber, firstname, lastname,  email,  emailstop,  icq,  skype,  yahoo, aim, msn,  phone1, phone2, institution, department,  address,  city,  country, lang,  calendartype,  theme,  timezone, firstaccess,  lastaccess,  lastlogin,  currentlogin, lastip,  secret, picture,  url, description,  descriptionformat,  mailformat,   maildigest,  maildisplay,  autosubscribe, trackforums,  timecreated,  timemodified, trustbitmask, imagealt,  lastnamephonetic, firstnamephonetic, middlename,  alternatename) VALUES('ldap', '1', '0', '0', '0', '1','{$login}', 'not cached', 'NULL', '{$firstname}', '{$lastname}', '{$mail}', '0',  ' ', ' ', ' ', ' ', ' ', '{$mobile}', 'NULL', 'ЗНУ','{$department}', ' ', 'Запоріжжя', 'UA', 'uk', 'gregorian', ' ',  '2.0', '0', '0', '0', '0', ' ', ' ', '0', ' ', '{$department}', '1', '1', '0', '2', '1', '0', '{$dat}', '0', '0', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL') " ) ;

$result2 = MYSQLI_QUERY($dbconnect, "REPLACE `mdl_cohort_members` (cohortid, userid, timeadded)
SELECT c.id, u.id, u.timecreated FROM mdl_user AS u
LEFT JOIN mdl_cohort AS c ON  c.name = '{$cohort}'
WHERE u.username = '{$login}';
  ");
  
if ($result1 = 'true' && $result2 = 'true'){
        echo "Користувача додано до бази даних<br>";
    }else{
        echo "Користувача не додано до бази даних<br>";
    }

  ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
  $r= ldap_bind($ldapconn, $ldaplogin, $ldappass) or ">>Could not bind to $ldap_host to add user<<";
  // var_dump($r);
  
 searchuserprofile_add($ldapconn, ['lastname'=>$lastname, 'firstname'=>$firstname, 'department'=>$department, 'login'=>$login, 'mobile' => $mobile, 'mail' => $mail, 'userpassword'=>$userpassword]);


ldap_close($ldapconn);
echo $OUTPUT->footer();





function searchuserprofile_add($ldapconn, $data){

$lastname = $data['lastname'];
$firstname = $data['firstname'];
$department = $data['department'];
$login = $data['login'];
$userpassword = $data['userpassword'];
$mobile = $data['mobile'];
$mail = $data['mail'];
// если логин и пароль введены,то обрабатываем их
$login = stripslashes($login);  // удаляем обратные слеши
$login = htmlspecialchars($login);  // преобразование спецсимволов
$userpassword = stripslashes($userpassword);
$userpassword = htmlspecialchars($userpassword);
// удаляем лишние пробелы
$login = trim($login);
$userpassword = trim($userpassword);


 $infoldap=[];
  $dn="uid={$login},{$department},ou=student,dc=znu,dc=edu,dc=ua";
  // $infoldap["dn"] = "UID={$login},OU=2013,OU=student,DC=znu,DC=edu,DC=ua";
  // $infoldap["ou"][0] ="student";
  // $infoldap["ou"][1] ="2013";
  $infoldap["cn"] = "{$lastname} {$firstname}";
  $infoldap["displayname"] = "{$lastname} {$firstname}";
  $infoldap["givenname"] = $firstname;
  $infoldap["mail"] =  $mail;
  $infoldap["mobile"] = $mobile;
  //$infoldap["objectclass"][1] =  "organizationalUnit";
  $infoldap["objectclass"][0] =  "inetOrgPerson";
  $infoldap["objectclass"][1]=  "top";
  $infoldap["sn"] =  $lastname;
  $infoldap["title"] = "TESTTEST";
  $infoldap["userpassword"] = $userpassword;
// var_dump($infoldap);
// var_dump($dn);
  $r=ldap_add($ldapconn, $dn, $infoldap) or ">>Not able to load user <<";
  // var_dump($r);

}


?>