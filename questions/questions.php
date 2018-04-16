<?php
set_time_limit(10000);
if (file_exists('../../config.php'))
    require_once('../../config.php');
else
    die;
require_login();
require_once($CFG->dirroot . "/local/questions/lib.php");


if (!has_capability('local/questions:view', context_system::instance())) {
    print_error('badpermissions');
}



/* $hostname = "localhost";
$username = "root";
$password = "";
$dbName = "moodle";
 */ 

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



$q_id=(int)$_REQUEST['id'];

	
$query_ggg = "
CREATE TEMPORARY TABLE ggg
SELECT f_c.userid,f_c.courseid, f_v.item, MAX(f_v.id) AS max_item_id
FROM mdl_feedback_completed AS f_c, mdl_feedback_value AS f_v
WHERE f_c.courseid=f_v.course_id
GROUP BY f_c.userid,f_c.courseid, f_v.item
";
//echo "$query_ggg <hr>";
$result_ggg = MYSQLI_QUERY($dbconnect, $query_ggg);

$query_item = "SELECT id, `name`
FROM mdl_feedback_item
WHERE feedback = $q_id
order by id
;" ;
$result_item = MYSQLI_QUERY($dbconnect, $query_item);

/*
$query = 
"SELECT f_c.userid, f_c.courseid, f_v.item, f_v.value, 
		c_c1.name AS dep, c_c2.name AS kaf, c.fullname, c_h.`name`, 
		FROM_UNIXTIME(f_c.timemodified, '%Y %D %M %h:%i:%s') AS time_ans
FROM mdl_feedback_completed AS f_c,
     mdl_feedback_item AS f_i,
     mdl_feedback_value AS f_v,
     ggg, mdl_feedback AS f,
	 mdl_course AS c,
	 mdl_course_categories AS c_c1,
	 mdl_course_categories AS c_c2,
	 mdl_cohort AS c_h,
	 mdl_cohort_members AS c_m
WHERE f.id=$q_id 
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
"; */

$years=join(',',[date('Y'),date('Y')-1]);
$query="
SELECT DISTINCT 
       f_c.userid, f_c.courseid, c.fullname, c_c2.name AS kaf,c_c1.name AS dep,
       GROUP_CONCAT( DISTINCT c_h.`name`) AS `name`,
       f_v.item, MAX(f_v.value) AS  `value`,  
       FROM_UNIXTIME(f_c.timemodified, '%Y-%m-%d %H:%i:%s') AS time_ans 
FROM            mdl_feedback_completed   f_c
     INNER JOIN mdl_course            AS c    ON c.id=f_c.courseid
     INNER JOIN mdl_course_categories AS c_c2 ON c.category=c_c2.id
     INNER JOIN mdl_feedback_value    AS f_v  ON f_v.course_id=f_c.courseid
     INNER JOIN ggg                           ON ( f_v.item=ggg.item AND f_v.id=ggg.max_item_id )
     LEFT JOIN mdl_course_categories AS c_c1 ON c_c2.parent=c_c1.id 
     LEFT JOIN mdl_cohort_members    AS c_m  ON c_m.userid = f_c.userid 
     LEFT JOIN mdl_cohort            AS c_h  ON c_h.id = c_m.cohortid
WHERE f_c.feedback=$q_id
      AND FROM_UNIXTIME(f_c.timemodified, '%Y') IN ($years)
GROUP BY f_c.userid, f_c.courseid, f_c.timemodified, f_v.item
ORDER BY f_c.userid, f_c.courseid, f_v.item 
;";
// echo "$query <hr>";

$result = MYSQLI_QUERY($dbconnect, $query);

echo "<table border=1 align=center >\n";

	echo "<tr><td><b>id</b></td><td><b>id курса</b></td><b> ";
	foreach ($result_item as $row){
		echo "<td>"."<b>".$row['name'] ."</b>"."</td>";
	}
	echo "</b><td><b>Факультет</b></td><td><b>Кафедра</b></td><td><b>Название курса</b></td><td><b>группа студента</b></td><td><b>Дата и время ответа</b></td></tr>";
	$rowus = NULL;
	while ($row = mysqli_fetch_assoc($result)) {
		if (($row['userid'] != $rowus['userid'] || $row['courseid'] != $rowus['courseid']) && $rowus != NULL ) {
			//var_dump($rowus);
			foreach ($result_item as $row111){
				echo "<td>".$rowus[$row111['id']] ."</td>";
			}
			echo "<td>".$rowus['dep'] ."</td>"."<td>".$rowus['kaf'] ."</td>"."<td>".$rowus['fullname'] ."</td><td>".$rowus['name'] ."</td><td>".$rowus['time_ans'] ."</td></tr>";
		}
		if ($row['userid'] != $rowus['userid'] || $row['courseid'] != $rowus['courseid']) {
			echo "<tr>"."<td>".md5($row['userid']) ."</td>"."<td>".$row['courseid']."</td>";
			$rowus = $row;
			
		}
		if ($row['userid'] == $rowus['userid'] && $row['courseid'] == $rowus['courseid']) { 
			$rowus[$row['item']]=$row['value'];
		}
	}

	
	foreach ($result_item as $row111){
		echo "<td>".$rowus[$row111['id']] ."</td>";
	}
	echo "<td>".$rowus['dep'] ."</td>"."<td>".$rowus['kaf'] ."</td>"."<td>".$rowus['fullname'] ."</td>"."<td>".$rowus['name'] ."</td><td>".$rowus['time_ans'] ."</td></tr>";
	
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
