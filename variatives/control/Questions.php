<?php
    require_once('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
    require_once($CFG->libdir.'/authlib.php');

$hostname = "localhost";
$username = "root";
$password = "";
$dbName = "moodle";


/* создаю соединение с проверкой */
$dbconnect = mysqli_connect($hostname,$username,$password,$dbName);
if (!$dbconnect) 
{
  echo( "<P>В настоящий момент сервер базы данных не доступен, нет соединения,
  поэтому корректное отображение страницы невозможно.</P>" .mysqli_connect_error());
  exit();
}
if (!mysqli_select_db($dbconnect, $dbName)) 
{
  echo( "<P>В настоящий момент база данных не доступна, поэтому
            корректное отображение страницы невозможно.</P>" );
  exit();
} 

$query_ggg = " CREATE TEMPORARY TABLE ggg
SELECT f_c.userid,f_c.courseid, f_v.item, MAX(f_v.id) AS max_item_id
FROM mdl_feedback_completed AS f_c, mdl_feedback_value AS f_v
WHERE f_c.courseid=f_v.course_id
GROUP BY f_c.userid,f_c.courseid, f_v.item
; ";
$result = MYSQLI_QUERY($dbconnect, $query_ggg);

$query = "SELECT f_c.userid, f_c.courseid, f_v.item, f_v.value, c_c1.name AS dep, c_c2.name AS kaf, c.fullname, c_h.`name`, FROM_UNIXTIME(f_c.timemodified, '%Y %D %M %h:%i:%s') AS time_ans
FROM mdl_feedback_completed AS f_c ,mdl_feedback_item AS f_i, mdl_feedback_value AS f_v, ggg, mdl_feedback AS f,
mdl_course AS c, mdl_course_categories AS c_c1, mdl_course_categories AS c_c2, mdl_cohort AS c_h, mdl_cohort_members AS c_m
WHERE f.id=1 
AND f_v.item=ggg.item 
AND f_v.id=ggg.max_item_id 
AND f_i.id = f_v.item 
AND f_c.feedback=f.id
AND f_v.course_id=f_c.courseid 
AND c_h.id = c_m.cohortid 
AND c_m.userid = f_c.userid 
AND f_i.feedback=f.id
AND c.id=f_c.courseid 
AND c_c1.depth=2 
AND c_c2.depth=3 
AND c_c2.parent=c_c1.id 
AND c.category=c_c2.id
HAVING (time_ans)=2016
ORDER BY f_c.userid, f_c.courseid, f_v.item
;";

$result = MYSQLI_QUERY($dbconnect, $query);

	echo "<table border=1 align=center >\n";
	echo "<tr><td><b>userid</b></td><td><b>id курса</b></td><td><b>№ вопроса</b></td><td><b>Ответ</b></td><td><b>Факультет</b></td><td><b>Кафедра</b></td><td><b>Название курса</b></td><td><b>группа студента</b></td></tr>";
while ($row = mysqli_fetch_assoc($result)) {
	//var_dump($row);	
	echo "<tr>"."<td>".$row['userid'] ."</td>"."<td>".$row['courseid']."</td>"."<td>".$row['item']."</td>"."<td>".$row['value']."</td>"."<td>".$row['dep'] ."</td>"."<td>".$row['kaf'] ."</td>"."<td>".$row['fullname'] ."</td>"."<td>".$row['name'] ."</td>"."</tr>";
}	
	echo "</table>\n";
	echo "<table border=2 align=center >\n";
//foreach ($userblockcourse AS $row) {	
//	echo "<tr>"."<td>".$row->id ."</td>"."<td id=fullname>".$row->fullname."</td>"."<td>".$row->n1."</td>"."<td>".$row->n2."</td>"."<td>".$row->n3."</td>"."</tr>";
//}	
	echo "</table>\n";	
?>

<style type="text/css">
TD {
    text-align: center;
}	
#n{
	word-wrap: break-word;
}
#fullname{
	text-align: left;
		width: 65%;
}
	
</style>