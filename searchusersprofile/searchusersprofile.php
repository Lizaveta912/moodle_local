<?php

function searchusersprofile_get($page, $perpage, &$count, $filter, $sort) {
    global $DB, $CFG;

    $start = $page * $perpage;

    $where = [];

    $params = [];

    $where[] = " c.idnumber IS NOT NULL ";

    $where[] = " c.idnumber NOT LIKE '' ";
    if (is_array($filter) && !empty($filter)) {

        if (isset($filter['realname']) && mb_strlen(trim($filter['realname'])) > 0) {
            $where[] = " ( 
	    	     LOCATE( :realname1, u.lastname )
	    	  OR LOCATE( :realname2, u.firstname )
	    	  OR LOCATE( :realname3, u.username )
	    	  OR LOCATE( :realname4 , u.email )
	    	)";
            $params['realname1'] = $params['realname2'] = $params['realname3'] = $params['realname4'] = trim($filter['realname']);
        }

        if (isset($filter['groupp']) && mb_strlen(trim($filter['groupp'])) > 0) {
            $where[] = " ( 
	    	  LOCATE( :groupp1, c.idnumber ) > 0
                  OR LOCATE(:groupp2,  c.description)>0
	    	)";
            $params['groupp1'] = 
            $params['groupp2'] = trim($filter['groupp']);
        }

        if (isset($filter['groupid']) && $filter['groupid'] > 0) {
            $where[] = " ( 
	    	   c.id = :groupid
	    	)";
            $params['groupid'] = $filter['groupid'];
        }


        if (isset($filter['login']) && mb_strlen(trim($filter['login'])) > 0) {
            $where[] = " ( 
	    	  LOCATE( :login1, u.username ) > 0
	    	)";
            $params['login1'] = trim($filter['login']);
        }
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
                    case 'lastname':
                    case 'firstname':
                    case 'username':
                    case 'email':
                    case 'lastlogin':
                        $order[] = "u.{$key} " . $sort[$key];
                        break;

                    case 'groupp':

                    case 'group_id':
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

    $sql = "SELECT SQL_CALC_FOUND_ROWS
                       u.id, u.lastname,  u.firstname, u.username, u.email,
                       FROM_UNIXTIME(u.lastlogin, '%Y-%m-%d %H:%i:%s') AS lastlogin,
                       GROUP_CONCAT( c.id ) AS `group_id`,
                       GROUP_CONCAT(c.idnumber) AS groupp,
                       GROUP_CONCAT(c.description) AS groupp_d
                       
	    FROM mdl_user AS u
	        LEFT JOIN mdl_cohort_members AS c_m ON c_m.userid=u.id
		LEFT JOIN mdl_cohort AS c ON  c.id = c_m.cohortid 
	    $WHERE
	    GROUP BY u.username
	    $ORDER
	    limit {$start},{$perpage}
			;";

    // print(htmlspecialchars($sql));
    $list = array_values($DB->get_records_sql($sql, $params, $limitfrom = 0, $limitnum = 0));
    $found_rows = array_values($DB->get_records_sql("SELECT FOUND_ROWS() AS n;", $params = null, $limitfrom = 0, $limitnum = 0));
    $count = (int) $found_rows[0]->n;
    return $list;
}

require_once(dirname(__FILE__) . '/../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot . "/local/searchusersprofile/lib.php");



require_login();

if (!has_capability('local/searchusersprofile:manage', context_system::instance())) {
    print_error('badpermissions');
}


$PAGE->set_url('/local/searchusersprofile/searchusersprofile.php', array(/* 'id' => 11111 */));
$PAGE->set_pagelayout('base');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Search Usres Profile', 'local_searchusersprofile'));
$PAGE->set_heading(get_string('Search Usres Profile', 'local_searchusersprofile'));



echo $OUTPUT->header();

$count_searchusersprofile = 0;

//echo "<h2>".get_string('userblockcourse','local_variatives').": {$userblockcourse->varsubspecialityblockname}</h2>";
//----------------------------------------------------------------
// if (!is_array($contingents_search) || empty($contingents_search)) { die(); }

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

$searchusersprofile_search = searchusersprofile_get($page, $perpage, $count_searchusersprofile, $filter, $sort);

$strParamFilter = (empty($filter) ? '' : '&' . http_build_query(array('filter' => $filter)));
$strParamSort = (empty($sort) ? '' : '&' . http_build_query(array('sort' => $sort)));

$baseurl = new moodle_url('/local/searchusersprofile/searchusersprofile.php', array('perpage' => $perpage)) . $strParamSort . $strParamFilter;

$page_bar = $OUTPUT->paging_bar($count_searchusersprofile/* countRows */, $page, $perpage, $baseurl);
echo $page_bar;
echo "<br/>";

//-----------------------------------------------------------------
$url = new moodle_url('/local/searchusersprofile/searchusersprofile.php');

echo "<form action = '{$url}'>";
foreach ($sort as $key => $value) {
    echo "<input name='sort[{$key}]' type='hidden' value='{$value}'>";
}



$filter_realname = isset($filter['realname']) ? $filter['realname'] : '';
$filter_login = isset($filter['login']) ? $filter['login'] : '';
$filter_groupp = isset($filter['groupp']) ? $filter['groupp'] : '';
$filter_groupid= isset($filter['groupid']) ? $filter['groupid'] : '';

echo <<<strHTML

<div>

        
	<div id='fgroup_id_realname_grp' class='fitem fitem_fgroup' style='float:left;'>

		<label><b><h4>Слово</h4> </b></label>
		<input name='filter[realname]' type='text' id='id_realname' value="{$filter_realname}" style="width:150px;">
	</div>


	<div id='fgroup_id_realname_grp' class='fitem fitem_fgroup'  style='float:left; margin-left:20px;'>
		<div class='fitemtitle'>
			<div class='fgrouplabel'>
				<label><b><h4>Логін </h4></b></label>
			</div>
		</div>
		<fieldset class='felement fgroup'>
			<label class='accesshide' for='id_login'>значення Логин</label>
			<input name='filter[login]' type='text' id='id_login' value="{$filter_login}" style="width:150px;">
		</fieldset>
	</div>

	<div id='fgroup_id_realname_grp' class='fitem fitem_fgroup ' style='float:left; margin-left:20px;'>
		<div class='fitemtitle'>
			<div class='fgrouplabel'>
				<label><b><h4>Номер групи</h4> </b></label>
			</div>
		</div
		<fieldset class='felement fgroup'>
			<label class='accesshide' for='id_groupp'>значення Номер групи</label>
			<input name='filter[groupp]' type='text' id='id_groupp' value="{$filter_groupp}" style="width:150px;">
		</fieldset>
	</div>

	<div id='fgroup_id_realname_grp' class='fitem fitem_fgroup ' style='float:left; margin-left:20px;'>
		<div class='fitemtitle'>
			<div class='fgrouplabel'>
				<label><b><h4>ID групи</h4> </b></label>
			</div>
		</div>
		<fieldset class='felement fgroup'>
			<label class='accesshide' for='id_groupp'>значення ID групи</label>
                        <input name='filter[groupid]' type='text'  value="{$filter_groupid}" style="width:40px;">

		</fieldset>
    </div>


</div>
<div id='fitem_id_addfilter' class='fitem fitem_actionbuttons fitem_fsubmit ' style="clear:both;">
	<div class='felement fsubmit'>
		<input value='Пошук' type='submit' id='id_addfilter'>
        <a href='adduser.php' class="c" style="float:right;">Додати користувача</a>
     
		<br>
		<a href='$url'>Очистити фільтр та упорядкування</a>
	</div>
</div>
</form>

strHTML;



echo "<table border=2 align=center class='admintable generaltable'>\n";

$headTable = [
    'lastname' => 'Прізвище',
    'firstname' => 'Ім\'я, По батькові',
    'username' => 'Логін',
    'email' => 'E-mail',
    'lastlogin' => 'Час останнього заходу',
    'group_id' => 'id групи',
    'groupp' => 'Номер групи'
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
    $img1 = "<img class='iconsmall' src='" . $OUTPUT->pix_url("t/delete") . "' alt='Видалити'>";
    $img2 = "<img class='iconsmall' src='" . $OUTPUT->pix_url("t/edit") . "' alt='Редагування'>";
    $img3 = "<img class='iconsmall' src='" . $OUTPUT->pix_url("t/hide") . "' alt='Сховати'>";
    //	<a title='Призупинити діяльність користувача' href = '../../admin/user.php?suspend={$row->id}&sort=name&dir=ASC&perpage=30&page=0&sesskey=6cwFvruCWt'>{$img3}</a>
    $url = new moodle_url('/local/searchusersprofile/searchusersprofile.php', array('perpage' => $perpage, 'page' => $page)) . '&' . http_build_query(array('sort' => $sort)) . $strParamFilter;

    echo "<th class='header'><b><a href={$url}>{$value}</a></b>{$img}</th>";

    if (isset($buffValue))
        $sort[$key] = $buffValue;
    else
        unset($sort[$key]);
}

echo "<th>Редагування</th></tr></thead>";



foreach ($searchusersprofile_search AS $row) {
    echo "<tbody id='yui_3_17_2_1_1479452251387_538'>
	<tr class id='yui_3_17_2_1_1479452251387_546'>
		<td class='centeralign cell c0' style>{$row->lastname }</td>
		<td>{$row->firstname }</td>
		<td>{$row->username}</td><td>{$row->email}</td>
		<td id=time>{$row->lastlogin}</td>
		<td>{$row->group_id }</td></td><td id=n>".str_replace([',','.'],[', ','. '],$row->groupp)."</td>
		<td>
			<a title='Видалити' href = '../../admin/user.php?delete={$row->id}&sort=name&dir=ASC&perpage=30&page=0&sesskey=" . sesskey() . "'>{$img1}</a>
			<a title='Редагування' href = '../../user/editadvanced.php?id={$row->id}&course=1'>{$img2} </a>
                        <a title='Перегляд' href = '../../user/view.php?id={$row->id}&course=1'>{$img3} </a>
			<a href = 'index.php?username={$row->username}'>Пароль</a>
		</td>
	</tr>
	</tbody>";
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
    .c {
    border: 1px solid #333; /* Рамка */
    display: inline-block;
    padding: 5px 15px; /* Поля */
    text-decoration: none; /* Убираем подчёркивание */
    color: #000; /* Цвет текста */
    }
</style>