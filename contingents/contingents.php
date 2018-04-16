<?php

function contingents_get($page, $perpage, &$count, $filter, $sort) {
    global $DB, $CFG;
    $start = $page * $perpage;

    $where = [];

    $params = [];

    $where[] = " c.idnumber IS NOT NULL ";

    $where[] = " c.idnumber NOT LIKE '' ";


    if (isset($filter['groupp']) && mb_strlen(trim($filter['groupp'])) > 0) {
        $where[] = " ( 
    	  LOCATE(:groupp1,  c.idnumber)>0
          OR LOCATE(:groupp2,  c.description)>0

    	)";
        $params['groupp1'] = 
        $params['groupp2'] = trim($filter['groupp']);
    }

    if (count($where) > 0) {
        $WHERE = ' WHERE ' . join(' AND ', $where);
    } else {
        $WHERE = '';
    }


    $order = [];

    if (is_array($sort)) {

        foreach ($sort as $key => $value) {

            if ($value == 'ASC' || $value == 'DESC')
                switch ($key) {
                    case 'id':
                        $order[] = "c.{$key} " . $sort[$key];
                        break;

                    case 'groupp':

                    case 'lastlogin':

                    case 'n':

                    case 'n2':
                        $order[] = "{$key} " . $sort[$key];
                        break;
                }
        }
    }

    if (count($order) > 0) {
        $ORDER = ' ORDER BY ' . join(' , ', $order);
    } else {
        $ORDER = '';
    }


    $sql = " SELECT SQL_CALC_FOUND_ROWS c.id, c.description, c.idnumber as groupp , 
                    FROM_UNIXTIME(c.timemodified, '%Y-%m-%d %H:%i:%s') AS lastlogin, 
                    COUNT(c_m.userid) AS n, e.n2
	FROM {$CFG->prefix}cohort AS c
	LEFT JOIN {$CFG->prefix}cohort_members AS c_m
	ON c_m.cohortid=c.id
    left join (SELECT COUNT(e.id) AS n2, e.`customint1`
    FROM `mdl_enrol` AS e
    WHERE e.enrol= 'cohort'
    GROUP BY e.`customint1`) as e
    ON  e.customint1 = c.id
	$WHERE
	GROUP BY(c.idnumber)
	$ORDER
	limit {$start},{$perpage}
	;";


    $list = array_values($DB->get_records_sql($sql, $params, $limitfrom = 0, $limitnum = 0));
    $found_rows = array_values($DB->get_records_sql("SELECT FOUND_ROWS() AS n;", $params = null, $limitfrom = 0, $limitnum = 0));
    $count = (int) $found_rows[0]->n;
    return $list;
}

require_once(dirname(__FILE__) . '/../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot . "/local/contingents/lib.php");


require_login();

$canManage = has_capability('local/contingents:manage', context_system::instance());
$canView   = has_capability('local/contingents:view', context_system::instance());

if ( !$canManage && !$canView ) {
    print_error('badpermissions');
}


$PAGE->set_url('/local/contingents/contingents.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('base');
$PAGE->set_pagelayout('standard');
//$PAGE->navigation->add(get_string('cohort2'), new moodle_url('contingents.php'));
$PAGE->set_title(get_string('Contingents Group Search', 'local_contingents'));
$PAGE->set_heading(get_string('Contingents Group Search', 'local_contingents'));


echo $OUTPUT->header();


$sort = optional_param_array('sort', array(), PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 30, PARAM_INT);        // how many per page
$filter = optional_param_array('filter', array(), PARAM_RAW);

if (is_array($filter) && !empty($filter)) {
    foreach ($filter as $key => $value) {
        if (empty($value)) {
            unset($filter[$key]);
        }
    }
}



$count_contingents = 0;

$contingents_search = contingents_get($page, $perpage, $count_contingents, $filter, $sort);


$strParamFilter = (empty($filter) ? '' : '&' . http_build_query(array('filter' => $filter)));
$strParamSort = (empty($sort) ? '' : '&' . http_build_query(array('sort' => $sort)));

$baseurl = new moodle_url('/local/contingents/contingents.php', array('perpage' => $perpage)) . $strParamSort . $strParamFilter;
$page_bar = $OUTPUT->paging_bar($count_contingents/* countRows */, $page, $perpage, $baseurl);
echo $page_bar;
echo "<br/>";

//-------------------------------------------------------------------
$url = new moodle_url('/local/contingents/contingents.php');

echo "<form action = '{$url}'>";
foreach ($sort as $key => $value) {
    echo "<input name='sort[{$key}]' type='hidden' value='{$value}'>";
}


$filter_groupp = isset($filter['groupp']) ? $filter['groupp'] : '';
echo <<<strHTML
<div id='fgroup_id_realname_grp' class='fitem fitem_fgroup '>
	<label class='accesshide' for='id_idnumber'>значення Номер групи</label>
	<input name='filter[groupp]' type='text' id='id_idnumber' value="{$filter_groupp}">
		<input value='Пошук' type='submit' id='id_addfilter'>
		<a href='$url'>Очистити фільтр та упорядкування</a>
</div>
</form>
strHTML;


echo "<table border=2 align=center class='admintable generaltable'>\n";

$headTable = [
    'id' => 'id группи',
    'groupp' => 'Номер групи',
    'description' => 'Назва групи',
    'lastlogin' => 'Час зміни',
    'n' => 'Кількість осіб',
    'n2' => 'Кількість курсів',
];

echo "<thead><tr>";

foreach ($headTable as $key => $value) {

    if (!empty($sort) && isset($sort[$key])) {
        $buffValue = $sort[$key];
        switch ($sort[$key]) {
            case 'ASC':
                $sort[$key] = 'DESC';
                $iconsort = 'sort_asc';
                break;
            case 'DESC':
                unset($sort[$key]);
                $iconsort = 'sort_desc';
                break;
            default:
                $sort[$key] = 'ASC';
        }
    } else {
        $sort[$key] = 'ASC';
        unset($iconsort);
    }

    $img = isset($iconsort) ? "<img class='iconsort' src='" . $OUTPUT->pix_url("t/{$iconsort}") . "' alt=''>" : '';

    $url = new moodle_url('/local/contingents/contingents.php', array('perpage' => $perpage, 'page' => $page)) . '&' . http_build_query(array('sort' => $sort)) . $strParamFilter;

    echo "<th class='header'><b><a href={$url}>{$value}</a></b>{$img}</th>";


    if (isset($buffValue)) {
        $sort[$key] = $buffValue;
    } else {
        unset($sort[$key]);
    }
}
echo "<th class='header'>Дії</th>";
echo "</tr></thead>";
$img1 = "<img class='iconsmall' src='" . $OUTPUT->pix_url("t/delete") . "' alt='Видалити'>";
$img2 = "<img class='iconsmall' src='" . $OUTPUT->pix_url("t/edit") . "' alt='Редагування'>";
$img3 = "<img class='iconsmall' src='" . $OUTPUT->pix_url("i/users") . "' alt='Список студентів'>";
$img4 = "<img class='iconsmall' src='" . $OUTPUT->pix_url("t/hide") . "' alt='Сховати'>";
//	<a title='Сховати' href = '../../cohort/edit.php?id={$row->id}&returnurl=%2Fcohort%2Findex.php%3Fpage%3D0'>{$img4}</a>
foreach ($contingents_search AS $row) {





    $mnu=[];
    if($canManage) $mnu[]="<a title='Видалити' href = '../../cohort/edit.php?id={$row->id}&returnurl=%2Fcohort%2Findex.php%3Fpage%3D0&delete=1'>{$img1}</a>";
    if($canManage) $mnu[]="<a title='Редагування' href = '../../cohort/edit.php?id={$row->id}&returnurl=%2Fcohort%2Findex.php%3Fpage%3D0'>{$img2} </a>";
    if($canManage) $mnu[]="<a title='Список студентів' href = '../../cohort/assign.php?id={$row->id}&returnurl=%2Fcohort%2Findex.php%3Fpage%3D0'>{$img3}</a>";
    if($canManage) $mnu[]="<a title='Студенти' href = '../searchusersprofile/searchusersprofile.php?filter[groupid]={$row->id}'>Студенти</a>";
    if($canManage || $canView ) $mnu[]="<a title='Курси' href = 'viewcontingents.php?customint1={$row->id}'>Курси</a>";

    echo "<tr><td>{$row->id}</td><td>" . htmlspecialchars($row->groupp) . "</td><td>{$row->description}</td><td>{$row->lastlogin}</td>
	<td>{$row->n }</td><td>{$row->n2 }</td>
	<td> ".join(" ",$mnu)."</td>
       </tr>";
}
echo "</table>\n";
echo "<br/>";
echo $page_bar;
echo $OUTPUT->footer();
?>
<style type="text/css">
    thead{
        border: 0;
    }
    table{
        border:0;
    }
    td {

        padding-left: 20px;
        padding-right: 10px;
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
