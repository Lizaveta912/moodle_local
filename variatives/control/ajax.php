<?php

require_once(dirname(__FILE__) . '/../../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot . "/local/variatives/locallib.php");


require_login();


$op = required_param('op', PARAM_TEXT);

//$type       = required_param('type', PARAM_INT);
//$courseid   = optional_param('id', 0, PARAM_INT);
//$page       = optional_param('page', 0, PARAM_INT);
//$deactivate = optional_param('lock', 0, PARAM_INT);
//$sortby     = optional_param('sort', 'name', PARAM_ALPHA);
//$sorthow    = optional_param('dir', 'ASC', PARAM_ALPHA);
//$confirm    = optional_param('confirm', false, PARAM_BOOL);
//$delete     = optional_param('delete', 0, PARAM_INT);
//$archive    = optional_param('archive', 0, PARAM_INT);
//$msg        = optional_param('msg', '', PARAM_TEXT);

switch ($op) {
    
    case 'departmentupdate':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $data = $_REQUEST['data'];

        $rows = [];
        foreach ($data as $key => $val) {
            if (isset($val['id']) && $val['id'] > 0) {
                $info = variatives_department_update($val['id'], $val);
            } else {
                $info = variatives_department_create($val);
            }
            $rows[$key] = $info;
        }
        echo json_encode(Array('status' => 'success', 'rows' => $rows));
        break;

    case 'departmentdelete':
        $reply = Array('status' => 'success', 'department' => variatives_department_delete($_REQUEST['departmentid']));
        echo json_encode($reply);
        break;

    case 'specialityupdate':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $data = $_REQUEST['data'];
        $reply = Array('status' => 'success', 'speciality' => Array());
        foreach ($data as $key => $val) {
            if ($val['op'] == 'update') {
                header("m-debug-001: updating");
                $reply['speciality'][] = variatives_speciality_update($val['id'], $val);
            } elseif ($val['op'] == 'create') {
                header("m-debug-001: creating");
                $reply['speciality'][] = variatives_speciality_create($val);
            }
        }
        echo json_encode($reply);
        break;

    case 'specialitydelete':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        header("m-debug-001: deleting speciality {$_REQUEST['data']['id']}");
        $reply = Array('status' => 'success', 'speciality' => variatives_speciality_delete($_REQUEST['data']['id']));
        echo json_encode($reply);
        break;




    case 'groupupdate':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $data = $_REQUEST['data'];
        $reply = Array('status' => 'success', 'group' => Array());
        foreach ($data as $key => $val) {
            if ($val['op'] == 'update') {
                header("m-debug-001: updating");
                $reply['group'][] = variatives_group_update($val['id'], $val);
            } elseif ($val['op'] == 'create') {
                header("m-debug-001: creating");
                $reply['group'][] = variatives_group_create($val);
            }
        }
        echo json_encode($reply);
        break;

    case 'groupdelete':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        header("m-debug-001: deleting group {$_REQUEST['data']['id']}");
        $reply = Array('status' => 'success', 'group' => variatives_group_delete($_REQUEST['data']['id']));
        echo json_encode($reply);
        break;


    case 'blockupdate':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $data = $_REQUEST['data'];
        // print_r($data);
        $reply = Array('status' => 'success', 'block' => Array());
        foreach ($data as $key => $val) {
            if ($val['op'] == 'update') {
                header("m-debug-001: updating");
                $info = variatives_block_update($val['id'], $val);
                $reply['block'][] = $info;
            } elseif ($val['op'] == 'create') {
                header("m-debug-001: creating");
                $info = variatives_block_create($val);
                // print_r($info);
                $reply['block'][] = $info;
            }
        }
        echo json_encode($reply);
        break;

    case 'blockcopy':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $id = (int) $_REQUEST['id'];
        $info = variatives_block_copy($id);
        echo json_encode(Array('status' => 'success', 'blockcourse' => $info));
        break;

    case 'blockdelete':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $id = (int) $_REQUEST['id'];
        $info = variatives_block_delete($id);
        echo json_encode(Array('status' => 'success', 'blockcourse' => $info));
        break;

    case 'blockgrouplist':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $varblockid = (int) $_REQUEST['varblockid'];
        echo json_encode(Array('status' => 'success', 'list' => variatives_blockgroup_list($varblockid)));
        break;

    case 'blockgroupudate':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $data = $_REQUEST['data'];
        
        // var_dump($data);
        
        foreach ($data as $key => $val) {
            if ($val['op'] == 'update') {
                $info = variatives_blockgroup_update($val['id'], $val);
            } elseif ($val['op'] == 'create') {
                $info = variatives_blockgroup_create($val);
            }
        }
        echo json_encode(Array('status' => 'success', 'blockgroup' => $info));
        break;

    case 'blockgroupdelete':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $id = (int) $_REQUEST['id'];
        $info = variatives_blockgroup_delete($id);
        echo json_encode(Array('status' => 'success', 'blockgroup' => $info));
        break;



    case 'blockcourselist':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $varblockid = (int) $_REQUEST['varblockid'];
        echo json_encode(Array('status' => 'success', 'list' => variatives_blockcourse_list($varblockid)));
        break;

    case 'blockcourseupdate':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $data = $_REQUEST['data'];
        foreach ($data as $key => $val) {
            if ($val['op'] == 'update') {
                $info = variatives_blockcourse_update($val['id'], $val);
            } elseif ($val['op'] == 'create') {
                $info = variatives_blockcourse_create($val);
            }
        }
        echo json_encode(Array('status' => 'success', 'blockcourse' => $info));
        break;

    case 'blockcoursedelete':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $id = (int) $_REQUEST['id'];
        $info = variatives_blockcourse_delete($id);
        echo json_encode(Array('status' => 'success', 'blockcourse' => $info));
        break;

    case 'blockcoursefind':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $substr = $_REQUEST['substr'];
        $list = variatives_blockcourse_find($substr);
        echo json_encode(Array('status' => 'success', 'list' => $list));
        break;

    case 'enroll_list':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $list = variatives_enroll_list(isset($_REQUEST['filter']) ? $_REQUEST['filter'] : Array());
        echo json_encode(Array('status' => 'success', 'list' => $list));
        break;

    case 'enroll_block_selector':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $keyword = $_REQUEST['keyword'];
        $list = variatives_block_find($keyword);
        echo json_encode(Array('status' => 'success', 'list' => $list));
        break;


    case 'enroll_user_selector':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $keyword = $_REQUEST['keyword'];
        $varblockid = $_REQUEST['varblockid'];
        $list = variatives_enroll_user_find($varblockid, $keyword);
        echo json_encode(Array('status' => 'success', 'list' => $list));
        break;

    case 'enroll_course_selector':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $keyword = $_REQUEST['keyword'];
        $varblockid = $_REQUEST['varblockid'];
        $list = variatives_enroll_course_find($varblockid, $keyword);
        echo json_encode(Array('status' => 'success', 'list' => $list));
        break;


    case 'enrollmenupdate':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $varblockid = (int) $_REQUEST['varblockid'];
        $varblock = variatives_block_get($varblockid);
        if (!$varblock) {
            echo json_encode(Array('status' => 'error', 'list' => Array(), 'message' => "Block {$varblockid} not found"));
            break;
        }

        $userid = (int) $_REQUEST['userid'];
        $users = variatives_enroll_user_find($varblockid, $keyword = '', $userid);
        if (count($users) == 0) {
            echo json_encode(Array('status' => 'error', 'list' => Array(), 'message' => "User {$userid} not found or has not access to block {$varblockid}"));
            break;
        }

        $courseid = (int) $_REQUEST['courseid'];
        $courses = variatives_enroll_course_find($varblockid, $keyword = '', $courseid);
        if (count($courses) == 0) {
            echo json_encode(Array('status' => 'error', 'list' => Array(), 'message' => "Course {$userid} not found or is not included into block {$varblockid}"));
            break;
        }

        $enrollid = (int) $_REQUEST['enrollid'];
        $enroll = variatives_enroll_get($enrollid);
        
        
        if ($enroll) {
            $enroll = variatives_enroll_update($enrollid, Array('varblockid' => $varblockid, 'userid' => $userid, 'courseid' => $courseid));
        } else {
            // print_r($enroll);exit('123');
            $enroll = variatives_enroll_create(Array('varblockid' => $varblockid, 'userid' => $userid, 'courseid' => $courseid));
        }

        echo json_encode(Array('status' => 'success', 'enroll' => $enroll));
        break;

    case 'enrollmentget':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $id = (int) $_REQUEST['id'];
        $info = variatives_enroll_get($id);
        echo json_encode(Array('status' => 'success', 'enroll' => $info));
        break;

    case 'enrolldelete':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $id = (int) $_REQUEST['id'];
        $info = variatives_enroll_delete($id);
        echo json_encode(Array('status' => 'success', 'enroll' => $info));
        break;


    case 'var_update_rating':
        if (!has_capability('local/variatives:choose', context_system::instance())) {
            print_error('badpermissions');
        }

        $courseids = array_map(function($y) {
            return (int) $y;
        }, explode(',', $_REQUEST['courseids']));

        $userid = $GLOBALS['USER']->id;
        if(has_capability('local/variatives:manage', context_system::instance()) && isset($_REQUEST['userid'])){
           $userid=(int)$_REQUEST['userid'];//8737; /// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! <<< замінити після тестів
        }

        $varblockid = (int) $_REQUEST['varblockid'];
        $info = variatives_rating_update($varblockid, $userid, $courseids);
        echo json_encode(Array('status' => 'success', 'rating' => Array('varblockid' => $varblockid, 'userid' => $userid, 'courseids' => $courseids)));
        break;






    case 'subspecialityupdate':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $data = $_REQUEST['data'];

        $rows = [];
        foreach ($data as $key => $val) {
            if (isset($val['id']) && $val['id'] > 0) {
                $info = variatives_subspeciality_update($val['id'], $val);
            } else {
                $info = variatives_subspeciality_create($val);
            }
            $rows[$key] = $info;
        }
        echo json_encode(Array('status' => 'success', 'rows' => $rows));
        break;
    case 'subspecialitydelete':
        $reply = Array('status' => 'success', 'subspeciality' => variatives_subspeciality_delete($_REQUEST['id']));
        echo json_encode($reply);
        break;


    case 'subspecialityblockupdate':
        if (!has_capability('local/variatives:manage', context_system::instance())) {
            print_error('badpermissions');
        }
        $data = $_REQUEST['data'];
        // print_r($data);
        $reply = Array('status' => 'success', 'block' => Array());
        foreach ($data as $key => $val) {
            if ($val['op'] == 'update') {
                //header("m-debug-001: updating");
                $info = variatives_subspecialityblock_update($val['id'], $val);
                $reply['block'][] = $info;
            } elseif ($val['op'] == 'create') {
                //header("m-debug-001: creating");
                $info = variatives_subspecialityblock_create($val);
                // print_r($info);
                $reply['block'][] = $info;
            }
        }
        echo json_encode($reply);
        break;

    case 'subspecialityblockdelete':
        $reply = Array('status' => 'success', 'subspeciality' => variatives_subspecialityblock_delete($_REQUEST['id']));
        echo json_encode($reply);
        break;



    case 'updatesubspecialityrating':
        if (!has_capability('local/variatives:choose', context_system::instance())) {
            print_error('badpermissions');
        }
        $varsubspecialityids = array_map(function($y) {
            return (int) $y;
        }, explode(',', $_REQUEST['varsubspecialityids']));

        $userid = $GLOBALS['USER']->id;
        if (has_capability('local/variatives:manage', context_system::instance()) && isset($_REQUEST['userid'])) {
            $userid = (int) $_REQUEST['userid'];
        }

        $varsubspecialityblockid = (int) $_REQUEST['varsubspecialityblockid'];

        updatesubspecialityrating($varsubspecialityblockid, $userid, $varsubspecialityids);
        // $info= variatives_rating_update($varblockid,$userid,$courseids );
        echo json_encode(Array('status' => 'success', 'rating' => Array('varsubspecialityblockid' => $varsubspecialityblockid, 'userid' => $userid, 'varsubspecialityids' => $varsubspecialityids)));
        break;
}