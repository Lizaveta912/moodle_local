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

MYSQLI_QUERY("SET NAMES utf8");
MYSQLI_QUERY("SET CHARACTER SET 'utf8';");

$q_id=(int)$_REQUEST['id'];
// echo $q_id;

$query_dr = "
DROP TABLE IF EXISTS _last_answers;
";
$result_dr = MYSQLI_QUERY($dbconnect, $query_dr);
// var_dump($result_dr);
$query_cr = "
CREATE TEMPORARY TABLE _last_answers(
   userid BIGINT(10) NOT NULL DEFAULT '0',
   courseid  BIGINT(10) NOT NULL DEFAULT '0',
   maxtime  BIGINT(10) NOT NULL DEFAULT '0',
   KEY `mdl_feedcomp_use_ix` (`userid`),
   KEY `mdl_feedcomp_cou_ix` (`courseid`),
   KEY `mdl_feedcomp_tim_ix` (`maxtime`)
);
";
$result_cr = MYSQLI_QUERY($dbconnect, $query_cr);
// var_dump($result_cr);
$query_ins = "
INSERT INTO _last_answers(userid, courseid, maxtime)
SELECT userid, courseid, MAX(timemodified) AS maxtime
FROM mdl_feedback_completed WHERE feedback=1 GROUP BY userid, courseid;
;" ;
$result_ins = MYSQLI_QUERY($dbconnect, $query_ins);
// var_dump($result_ins);
$query_dr2 = "
DROP TABLE IF EXISTS _feedback_completed;
";
$result_dr2 = MYSQLI_QUERY($dbconnect, $query_dr2);
// var_dump($result_dr2);
$query_cr2 = "
CREATE TEMPORARY TABLE _feedback_completed(
   `id` BIGINT(10) NOT NULL AUTO_INCREMENT,
   `userid` BIGINT(10) NOT NULL DEFAULT '0',
   `timemodified` BIGINT(10) NOT NULL DEFAULT '0',
   `courseid` BIGINT(10) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `mdl_feedcomp_use_ix` (`userid`),
   KEY `mdl_feedcomp_cou_ix` (`courseid`),
   KEY `mdl_feedcomp_tim_ix` (`timemodified`)
);
";
$result_cr2 = MYSQLI_QUERY($dbconnect, $query_cr2);
// var_dump($result_cr);
$query_ins2 = "
INSERT INTO _feedback_completed(id, userid, courseid, timemodified)
SELECT fc.id, fc.userid, fc.courseid, fc.timemodified
FROM mdl_feedback_completed AS fc
     INNER JOIN _last_answers AS la
     ON (fc.userid=la.userid AND fc.courseid=la.courseid AND fc.timemodified = la.maxtime);
" ;
$result_ins2 = MYSQLI_QUERY($dbconnect, $query_ins2);
// var_dump($result_ins2);

$query_sel = "
SELECT * FROM _feedback_completed;
";
$result_sel = MYSQLI_QUERY($dbconnect, $query_sel);
// var_dump($result_sel);

$query="
SELECT fv.id, fv.course_id, fv.completed,
FROM_UNIXTIME(fc.timemodified) timemodified,
REPLACE(fv.value,'\n',' ') `value`, fv.item, fi.name,
c_inf.course_name, c_inf.kafedra, c_inf.faculty
FROM mdl_feedback_value fv
INNER JOIN _feedback_completed fc ON fv.completed=fc.id
INNER JOIN mdl_feedback_item AS fi ON fv.item = fi.id
INNER JOIN (
    SELECT c.id AS course_id, c.fullname AS course_name,
           cc.id AS category_id, cc.name AS kafedra,
           pa.id AS parent_category_id, pa.name AS faculty
    FROM mdl_course c
         LEFT JOIN mdl_course_categories cc ON c.category = cc.id
         LEFT JOIN mdl_course_categories pa ON cc.parent = pa.id
    WHERE cc.name<>'archive' AND cc.name <> 'Різне'
) c_inf ON c_inf.course_id = fv.course_id

ORDER BY fv.completed, fv.item
LIMIT 30000;
";
// echo "$query <hr>";

$result = MYSQLI_QUERY($dbconnect, $query);
// var_dump( $result);

$query_item = "SELECT id, `name`
FROM mdl_feedback_item
WHERE feedback = $q_id
order by id
;" ;
$result_item = MYSQLI_QUERY($dbconnect, $query_item);

echo "<table border=1 align=center >\n";
echo "<tr><td><b>id</b></td><td><b>id курса</b></td><td><b>Факультет</b></td><td><b>Кафедра</b></td><td><b>Название курса</b></td><td><b>Дата и время ответа</b></td>";
	while ($row111 = mysqli_fetch_assoc($result_item)) {
		echo "<td class='result_item'><b>".$row111['name'] ."</b>"."</td>";
	}
echo "</tr>";	

	// $myrow = mysqli_fetch_array($result_item);

	// while ($myrow2 = mysqli_fetch_array($result))
	//  {
	//     if ($myrow['id']!==$myrow2['item']) {echo "<td>"."<b>".$myrow['value'] ."</b>"."</td>"; }
	//      }

	$rowus = NULL;

	$completed =-1;
    

	while ($row = mysqli_fetch_assoc($result)) {

         if ($completed != $row["completed"]) {

         	if($completed>0){ echo "</tr>";}
         	echo "<tr>
				<td>".$row['completed'] ."</td>
				<td>".$row['course_id'] ."</td>
				<td>".$row['faculty'] ."</td>
				<td>".$row['kafedra'] ."</td>
				<td>".$row['course_name'] ."</td>
				<td>".$row['timemodified'] ."</td>
         	";

         	$completed =$row["completed"];
         }

         //echo "<td>".$rowus['item']."</td>";
         echo "<td>".$row['value']."</td>";




		// if (($row['id'] != $rowus['id'] || $row['course_id'] != $rowus['course_id']) && $rowus != NULL ) {
		// 	//var_dump($rowus);
		// 	foreach ($result_item as $row111){
		// 		echo "<td>".$rowus[$row111['id']] ."</td>";
		// 	}

		// 	echo "<td>".$rowus['faculty'] ."</td>"."<td>".$rowus['kafedra'] ."</td><td>".$rowus['course_name'] ."</td><td>".$rowus['timemodified'] ."</td>";
		// }
		// if ($row['id'] != $rowus['id'] || $row['course_id'] != $rowus['course_id']) {
		// 	echo "<tr>"."<td>".($row['id']) ."</td>"."<td>".$row['course_id']."</td>";
		// 	$rowus = $row;
			
		// }
		// if ($row['id'] == $rowus['id'] && $row['course_id'] == $rowus['course_id']) { 
		// 	$rowus[$row['item']]=$row['value'];
		// }

	}
	echo "</tr>";

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