<?php

function variatives_department_list() {
    global $DB;
    $list = $DB->get_records_select('var_department', $where = '', $params = Array(), $sort = 'vardepartmentname ASC');
    return $list;
}

function variatives_department_get($id) {
    global $DB;
    $info = $DB->get_record('var_department', Array('id' => $id));
    return $info;
}

function variatives_department_object($values) {
    global $DB;

    // var_dump($values);

    $info = new stdClass();

    if (isset($values['vardepartmentvisible'])) {
        $info->vardepartmentvisible = $values['vardepartmentvisible'] == 'true' ? 1 : 0;
    }

    if (isset($values['vardepartmentobsolete'])) {
        $info->vardepartmentobsolete = $values['vardepartmentobsolete'] == 'true' ? 1 : 0;
    }

    if (isset($values['vardepartmentcode'])) {
        $info->vardepartmentcode = strip_tags($values['vardepartmentcode']);
    }

    if (isset($values['vardepartmentname'])) {
        $info->vardepartmentname = strip_tags($values['vardepartmentname']);
    }
    //var_dump($info);exit();
    return $info;
}

function variatives_department_update($id, $values) {
    global $DB;

    $info = variatives_department_object($values);
    $info->id = (int) $id;


    if (count((array) $info) > 0) {
        $DB->update_record('var_department', $info);
    }
    return variatives_department_get($id);
}

function variatives_department_create($values) {
    global $DB;

    $info = variatives_department_object($values);

    if (count((array) $info) > 0) {
        $id = $DB->insert_record('var_department', $info, $returnid = true);
    }
    return variatives_department_get($id);
}

function variatives_department_delete($id) {
    global $DB;
    $department = variatives_department_get($id);
    if ($department) {

        $DB->delete_records('var_blockgroup', array('vardepartmentid' => $id));
        $DB->delete_records('var_group', array('vardepartmentid' => $id));

        $varspecialityids = array_keys($DB->get_records('var_speciality', array('vardepartmentid' => $id)));
        foreach ($varspecialityids as $varspecialityid) {
            variatives_speciality_delete($varspecialityid);
        }

        $DB->delete_records('var_department', array('id' => $id));
    }
    return $department;
}

function variatives_department_options() {
    global $DB, $CFG;
    $sql = "SELECT department.id, department.vardepartmentname
            FROM {$CFG->prefix}var_department department
            ORDER BY department.vardepartmentname;";
    $list = $DB->get_records_sql($sql);
    return $list;
}

function variatives_form_options() {
    global $DB, $CFG;
    $sql = "SELECT form.id, form.varformname
            FROM {$CFG->prefix}var_form form
            ORDER BY form.varformname;";
    $list = $DB->get_records_sql($sql);
    return $list;
}

function variatives_level_options() {
    global $DB, $CFG;
    $sql = "SELECT level.id, level.varlevelname
            FROM {$CFG->prefix}var_level level
            ORDER BY level.varlevelname;";
    $list = $DB->get_records_sql($sql);
    return $list;
}

function variatives_speciality_list() {
    global $DB, $CFG;
    $sql = "SELECT speciality.*, department.vardepartmentname, form.varformname,varlevel.varlevelname
            FROM {$CFG->prefix}var_speciality speciality 
                 INNER JOIN {$CFG->prefix}var_department department ON speciality.vardepartmentid=department.id
                 INNER JOIN {$CFG->prefix}var_form form ON speciality.varformid=form.id
                 INNER JOIN {$CFG->prefix}var_level varlevel ON speciality.varlevelid=varlevel.id
            ORDER BY department.vardepartmentname, speciality.varspecialitycode, form.varformname,varlevel.varlevelname;";
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    // $list = $DB->get_records_select('var_department', $where = '', $params = Array(), $sort = 'vardepartmentname ASC');
    return $list;
}

function variatives_speciality_get($id) {
    global $DB, $CFG;
    $sql = "SELECT speciality.*, department.vardepartmentname, form.varformname,varlevel.varlevelname
            FROM {$CFG->prefix}var_speciality speciality 
                 INNER JOIN {$CFG->prefix}var_department department ON speciality.vardepartmentid=department.id
                 INNER JOIN {$CFG->prefix}var_form form ON speciality.varformid=form.id
                 INNER JOIN {$CFG->prefix}var_level varlevel ON speciality.varlevelid=varlevel.id
            WHERE speciality.id=" . ( (int) $id );
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    // $list = $DB->get_records_select('var_department', $where = '', $params = Array(), $sort = 'vardepartmentname ASC');
    return isset($list[0]) ? $list[0] : false;
}

function variatives_speciality_object($values) {
    global $DB;

    // var_dump($values);

    $info = new stdClass();

    if (isset($values['varspecialityvisible'])) {
        $info->varspecialityvisible = $values['varspecialityvisible'] == 'true' ? 1 : 0;
    }

    if (isset($values['varspecialityobsolete'])) {
        $info->varspecialityobsolete = $values['varspecialityobsolete'] == 'true' ? 1 : 0;
    }

    if (isset($values['varspecialitycode'])) {
        $info->varspecialitycode = strip_tags($values['varspecialitycode']);
    }

    if (isset($values['varspecialityname'])) {
        $info->varspecialityname = strip_tags($values['varspecialityname']);
    }

    if (isset($values['vardepartmentid'])) {
        $info->vardepartmentid = (int) $values['vardepartmentid'];
    }


    if (isset($values['varspecialitynotes'])) {
        $info->varspecialitynotes = strip_tags($values['varspecialitynotes']);
    }

    if (isset($values['varspecialityedboid'])) {
        $info->varspecialityedboid = (int) $values['varspecialityedboid'];
    }

    if (isset($values['varformid'])) {
        $info->varformid = (int) $values['varformid'];
    }
    if (isset($values['varlevelid'])) {
        $info->varlevelid = (int) $values['varlevelid'];
    }

    return $info;
}

function variatives_speciality_update($id, $values) {
    global $DB;
    // var_dump($values);
    $info = variatives_speciality_object($values);
    $info->id = (int) $id;
    if (count((array) $info) > 0) {
        $DB->update_record('var_speciality', $info);
    }
    return variatives_speciality_get($id);
}

function variatives_speciality_delete($id) {
    global $DB;
    $speciality = variatives_speciality_get($id);
    if ($speciality) {

        $DB->delete_records('var_group', array('varspecialityid' => $id));
        $DB->delete_records('var_blockgroup', array('varspecialityid' => $id));
        $DB->delete_records('var_subspeciality', array('varspecialityid' => $id));

        $DB->delete_records('var_speciality', array('id' => $id));
    }
    return $speciality;
}

function variatives_speciality_create($values) {
    global $DB;
    $info = variatives_speciality_object($values);
    if (count((array) $info) > 0) {
        $id = $DB->insert_record('var_speciality', $info, $returnid = true);
        return variatives_speciality_get($id);
    }
    return null;
}

function variatives_draw_options($value, $options) {
    $to_return = '';


    foreach ($options as $key => $val) {
        if (is_array($val)) {
            $val = array_values((array) $val);
            if (!isset($val[1])) {
                $val[1] = $val[0];
            }
            if ($val[0] == $value && strlen($val[0]) == strlen($value)) {
                $selected = ' selected ';
            } else {
                $selected = '';
            }
            $to_return.="<option value=\"" . htmlspecialchars(trim($val[0])) . "\" $selected>{$val[1]}</option>\n";
        } elseif (is_object($val)) {
            $val = (array) $val;
            $val = array_values((array) $val);
            if (!isset($val[1])) {
                $val[1] = $val[0];
            }
            if ($val[0] == $value && strlen($val[0]) == strlen($value)) {
                $selected = ' selected ';
            } else {
                $selected = '';
            }
            $to_return.="<option value=\"" . htmlspecialchars(trim($val[0])) . "\" $selected>{$val[1]}</option>\n";
        } else {
            if ($key == $value && strlen($key) == strlen($value)) {
                $selected = ' selected ';
            } else {
                $selected = '';
            }
            $to_return.="<option value=\"" . htmlspecialchars(trim($key)) . "\" $selected>$val</option>\n";
        }
    }
    return $to_return;
}

function variatives_group_list() {
    global $DB, $CFG;

    $sql = "SELECT var_group.*, cohort.name AS cohortname, var_department.vardepartmentname,
                var_speciality.varspecialityname, var_form.varformname,
                var_level.varlevelname
         FROM {$CFG->prefix}var_group AS var_group
              LEFT JOIN {$CFG->prefix}cohort AS cohort ON ( var_group.cohortid=cohort.id AND cohort.visible )
              LEFT JOIN {$CFG->prefix}var_department AS var_department ON ( var_group.vardepartmentid = var_department.id)
              LEFT JOIN {$CFG->prefix}var_speciality  AS var_speciality ON ( var_group.varspecialityid=var_speciality.id)
              LEFT JOIN {$CFG->prefix}var_form AS  var_form  ON var_group.varformid=var_form.id
              LEFT JOIN {$CFG->prefix}var_level AS var_level ON var_group.varlevelid=var_level.id
         ORDER BY var_department.vardepartmentname, var_group.vargroupyear";
    $list = $DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0);
    // $list = $DB->get_records_select('var_department', $where = '', $params = Array(), $sort = 'vardepartmentname ASC');
    return $list;
}

function variatives_cohortid_options() {
    global $DB, $CFG;
    $sql = "SELECT id, name FROM {$CFG->prefix}cohort AS cohort WHERE visible ORDER BY name ASC";
    $list = $DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0);
    // $list = $DB->get_records_select('var_department', $where = '', $params = Array(), $sort = 'vardepartmentname ASC');
    return $list;
}

function variatives_speciality_options() {
    global $DB, $CFG;
    $sql = "SELECT id, varspecialityname FROM {$CFG->prefix}var_speciality WHERE varspecialityvisible ORDER BY varspecialityname ASC";
    $list = $DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0);
    // $list = $DB->get_records_select('var_department', $where = '', $params = Array(), $sort = 'vardepartmentname ASC');
    return $list;
}

function variatives_group_get($id) {
    global $DB, $CFG;

    $sql = "SELECT var_group.*, cohort.name AS cohortname, var_department.vardepartmentname,
                var_speciality.varspecialityname, var_form.varformname,
                var_level.varlevelname
          FROM {$CFG->prefix}var_group AS var_group
              LEFT JOIN {$CFG->prefix}cohort AS cohort ON ( var_group.cohortid=cohort.id AND cohort.visible )
              LEFT JOIN {$CFG->prefix}var_department AS var_department ON ( var_group.vardepartmentid = var_department.id)
              LEFT JOIN {$CFG->prefix}var_speciality  AS var_speciality ON ( var_group.varspecialityid=var_speciality.id)
              LEFT JOIN {$CFG->prefix}var_form AS  var_form  ON var_group.varformid=var_form.id
              LEFT JOIN {$CFG->prefix}var_level AS var_level ON var_group.varlevelid=var_level.id
          WHERE var_group.id=" . ( (int) $id );
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    // $list = $DB->get_records_select('var_department', $where = '', $params = Array(), $sort = 'vardepartmentname ASC');
    return isset($list[0]) ? $list[0] : false;
}

function variatives_group_object($values) {
    $info = new stdClass();

    if (isset($values['cohortid'])) {
        $info->cohortid = (int) $values['cohortid'];
    }
    if (isset($values['vargroupcode'])) {
        $info->vargroupcode = strip_tags($values['vargroupcode']);
    }
    if (isset($values['vardepartmentid'])) {
        $info->vardepartmentid = (int) $values['vardepartmentid'];
    }
    if (isset($values['vargroupyear'])) {
        $info->vargroupyear = (int) $values['vargroupyear'];
    }
    if (isset($values['varspecialityid'])) {
        $info->varspecialityid = (int) $values['varspecialityid'];
    }
    if (isset($values['varformid'])) {
        $info->varformid = (int) $values['varformid'];
    }
    if (isset($values['varlevelid'])) {
        $info->varlevelid = (int) $values['varlevelid'];
    }
    if (isset($values['vargroupedbocode'])) {
        $info->vargroupedbocode = strip_tags($values['vargroupedbocode']);
    }
    if (isset($values['vargroupnotes'])) {
        $info->vargroupnotes = strip_tags($values['vargroupnotes']);
    }

    return $info;
}

function variatives_group_update($id, $values) {
    global $DB;
    // var_dump($values);
    $info = variatives_group_object($values);
    $info->id = (int) $id;
    if (count((array) $info) > 0) {
        $DB->update_record('var_group', $info);
    }
    return variatives_group_get($id);
}

function variatives_group_create($values) {
    global $DB;
    // var_dump($values);
    $info = variatives_group_object($values);
    if (count((array) $info) > 0) {
        $id = $DB->insert_record('var_group', $info, $returnid = true);
        return variatives_group_get($id);
    }
    return null;
}

function variatives_group_delete($id) {
    global $DB;
    $group = variatives_group_get($id);
    if ($group) {
        //var_dump($group);
        $DB->delete_records('var_group', array('id' => $id));
    }
    return $group;
}

function variatives_block_list() {
    global $DB, $CFG;
    $sql = "SELECT var_block.*,
                 var_form.varformname,
                 var_level.varlevelname
          FROM {$CFG->prefix}var_block AS var_block
               LEFT JOIN {$CFG->prefix}var_form AS  var_form  ON var_block.varformid=var_form.id
               LEFT JOIN {$CFG->prefix}var_level AS var_level ON var_block.varlevelid=var_level.id
          ORDER BY var_block.varblockisarchive ASC,var_block.varblockname;";
    $list = $DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0);

    // $list = $DB->get_records_select('var_department', $where = '', $params = Array(), $sort = 'vardepartmentname ASC');
    return $list;
}

function variatives_block_get($id) {
    global $DB, $CFG;
    $sql = "SELECT var_block.*,
                 var_form.varformname,
                 var_level.varlevelname
          FROM {$CFG->prefix}var_block AS var_block
               LEFT JOIN {$CFG->prefix}var_form AS  var_form  ON var_block.varformid=var_form.id
               LEFT JOIN {$CFG->prefix}var_level AS var_level ON var_block.varlevelid=var_level.id
          WHERE var_block.id=" . ( (int) $id );
    //echo $sql;
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    return isset($list[0]) ? $list[0] : false;
}

function variatives_block_object($values) {
    $info = new stdClass();

    if (isset($values['varblockname'])) {
        $info->varblockname = strip_tags($values['varblockname']);
    }
    if (isset($values['vargroupyear'])) {
        $info->vargroupyear = (int) $values['vargroupyear'];
    }
    if (isset($values['varformid'])) {
        $info->varformid = (int) $values['varformid'];
    }
    if (isset($values['varlevelid'])) {
        $info->varlevelid = (int) $values['varlevelid'];
    }
    if (isset($values['varblockisarchive'])) {

        if (in_array($values['varblockisarchive'], Array('true', 'yes', '1', 'on'))) {
            $info->varblockisarchive = 1;
        } else {
            $info->varblockisarchive = 0;
        }
    }
    if (isset($values['varblockminstudents'])) {
        $info->varblockminstudents = (int) $values['varblockminstudents'];
    }
    if (isset($values['varblockmaxstudents'])) {
        $info->varblockmaxstudents = (int) $values['varblockmaxstudents'];
    }

    if (isset($values['varblocktimestampfrom'])) {
        $info->varblocktimestampfrom = strtotime($values['varblocktimestampfrom']);
        if ($info->varblocktimestampfrom === false) {
            $info->varblocktimestampfrom = time();
        }
    }

    if (isset($values['varblocktimestampto'])) {
        $info->varblocktimestampto = strtotime($values['varblocktimestampto']);
        if ($info->varblocktimestampto === false) {
            $info->varblocktimestampto = time() + 90 * 24 * 3600;
        }
    }

    return $info;
}

function variatives_block_update($id, $values) {
    global $DB;

    $info = variatives_block_object($values);
    $info->id = (int) $id;

    // var_dump($info);exit();
    if (count((array) $info) > 0) {
        $DB->update_record('var_block', $info);
    }
    return variatives_block_get($id);
}

function variatives_block_create($values) {
    global $DB;

    $info = variatives_block_object($values);
    //var_dump($info);exit();

    if (count((array) $info) > 0) {
        $id = $DB->insert_record('var_block', $info, $returnid = true);
        return variatives_block_get($id);
    }
    return null;
}

function variatives_block_delete($id) {
    global $DB;
    $info = variatives_block_get($id);
    if ($info) {
        $DB->delete_records('var_blockgroup', array('varblockid' => $id));
        $DB->delete_records('var_blockcourse', array('varblockid' => $id));
        $DB->delete_records('var_userblockcourse', array('varblockid' => $id));
        $DB->delete_records('var_enroll', array('varblockid' => $id));
        $DB->delete_records('var_assignmentqueue', array('varblockid' => $id));

        $DB->delete_records('var_block', array('id' => $id));
    }
    return $info;
}

// TODO add subspeciality
function variatives_block_copy($id) {
    global $DB;
    $info = variatives_block_get($id);
    if ($info) {
        $DB->execute("SET @oldblockid=" . ( (int) $id ));

        $DB->execute("CREATE TEMPORARY TABLE `t_b` (
            `id` BIGINT(10),
            `varblockname` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
            `varblockminstudents` BIGINT(10) NOT NULL,
            `varblockmaxstudents` BIGINT(10) NOT NULL,
            `varblocktimestampfrom` BIGINT(10) NOT NULL,
            `varblocktimestampto` BIGINT(10) NOT NULL,
            `vargroupyear` BIGINT(10) NOT NULL,
            `varformid` BIGINT(10) NOT NULL,
            `varlevelid` BIGINT(10) NOT NULL,
            `varblockisarchive` SMALLINT(4) NOT NULL
          ) ENGINE=memory DEFAULT CHARSET=utf8 COLLATE=utf8_bin
        ");

        $DB->execute("INSERT INTO t_b SELECT * FROM mdl_var_block WHERE id=@oldblockid");
        $DB->execute("UPDATE t_b SET id=NULL WHERE id=@oldblockid");
        $DB->execute("INSERT INTO mdl_var_block SELECT * FROM t_b");
        $DB->execute("SET @new_block_id=(SELECT LAST_INSERT_ID())");
        $DB->execute("DROP TABLE IF EXISTS t_b");
        $DB->execute("CREATE TEMPORARY TABLE t_bc SELECT @new_block_id, courseid, varblockcoursegroup FROM mdl_var_blockcourse WHERE varblockid=@oldblockid");
        $DB->execute("INSERT INTO mdl_var_blockcourse (varblockid, courseid, varblockcoursegroup) SELECT * FROM t_bc");
        $DB->execute("DROP TABLE IF EXISTS t_bc");
        $DB->execute("CREATE TEMPORARY TABLE t_bg
                      SELECT @new_block_id varblockid, vardepartmentid, varspecialityid, varblockgroupnumcourses
                      FROM mdl_var_blockgroup WHERE varblockid=@oldblockid
                     ");
        $DB->execute("INSERT INTO mdl_var_blockgroup (varblockid, vardepartmentid, varspecialityid, varblockgroupnumcourses) SELECT * FROM t_bg");
        $DB->execute("DROP TABLE IF EXISTS t_bg");

        $newBlockInfo = $DB->get_record_sql("SELECT @new_block_id as id");

        $info = variatives_block_get($newBlockInfo['id']);
    }
    return $info;
}

function variatives_blockgroup_list($varblockid) {
    global $DB, $CFG;
    $sql = "SELECT blockgroup.*, 
                   department.vardepartmentname,
                   speciality.varspecialityname,
                   subspeciality.varsubspecialitytitle
          FROM {$CFG->prefix}var_blockgroup blockgroup
               LEFT JOIN {$CFG->prefix}var_department department ON blockgroup.vardepartmentid=department.id
               LEFT JOIN {$CFG->prefix}var_speciality speciality ON speciality.id=blockgroup.varspecialityid
               LEFT JOIN {$CFG->prefix}var_subspeciality subspeciality ON subspeciality.id=blockgroup.varsubspecialityid
          WHERE varblockid=" . ( (int) $varblockid ) . "
          ORDER BY department.vardepartmentname, speciality.varspecialityname, subspeciality.varsubspecialitytitle";
    //echo $sql;
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    return $list;
}

function variatives_blockgroup_get($id) {
    global $DB, $CFG;
    $sql = "SELECT blockgroup.*, 
                department.vardepartmentname, 
                speciality.varspecialityname,
                subspeciality.varsubspecialitytitle
          FROM {$CFG->prefix}var_blockgroup blockgroup
               LEFT JOIN {$CFG->prefix}var_department department ON blockgroup.vardepartmentid=department.id
               LEFT JOIN {$CFG->prefix}var_speciality speciality ON speciality.id=blockgroup.varspecialityid
               LEFT JOIN {$CFG->prefix}var_subspeciality subspeciality ON subspeciality.id=blockgroup.varsubspecialityid
          WHERE blockgroup.id=" . ( (int) $id );
    //echo $sql;
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    return isset($list[0]) ? $list[0] : false;
}

function variatives_blockgroup_object($values) {
    $info = new stdClass();

    if (isset($values['varblockid'])) {
        $info->varblockid = (int) $values['varblockid'];
    }

    if (isset($values['vardepartmentid'])) {
        $info->vardepartmentid = (int) $values['vardepartmentid'];
    }

    if (isset($values['varspecialityid'])) {
        $info->varspecialityid = (int) $values['varspecialityid'];
    }
    if (isset($values['varsubspecialityid'])) {
        $info->varsubspecialityid = (int) $values['varsubspecialityid'];
    }
    if (isset($values['varblockgroupnumcourses'])) {
        $info->varblockgroupnumcourses = (int) $values['varblockgroupnumcourses'];
    }

    return $info;
}

function variatives_blockgroup_update($id, $values) {
    global $DB;

    $info = variatives_blockgroup_object($values);
    $info->id = (int) $id;

    //var_dump($info);exit();
    if (count((array) $info) > 0) {
        $DB->update_record('var_blockgroup', $info);
    }
    return variatives_blockgroup_get($id);
}

function variatives_blockgroup_create($values) {
    global $DB;

    $info = variatives_blockgroup_object($values);
    // var_dump($info);exit();

    if (count((array) $info) > 0) {
        $id = $DB->insert_record('var_blockgroup', $info, $returnid = true);
        return variatives_blockgroup_get($id);
    }
    return null;
}

function variatives_blockgroup_delete($id) {
    global $DB;
    $info = variatives_blockgroup_get($id);
    if ($info) {
        //var_dump($group);
        $DB->delete_records('var_blockgroup', array('id' => $id));
    }
    return $info;
}

function variatives_blockcourse_list($varblockid) {
    global $DB, $CFG;
    $sql = "SELECT  blockcourse.*,
                    course.shortname course_shortname,
                    course.fullname course_fullname
         FROM {$CFG->prefix}var_blockcourse blockcourse
                 LEFT JOIN {$CFG->prefix}course course ON ( blockcourse.courseid=course.id AND course.visible)
         WHERE blockcourse.varblockid=" . ( (int) $varblockid ) . " 
         ORDER BY blockcourse.varblockcoursegroup ASC, course.fullname ASC ";
    //echo $sql;
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    return $list;
}

function variatives_blockcourse_get($id) {
    global $DB, $CFG;
    $sql = "SELECT  blockcourse.*,
                    course.shortname course_shortname,
                    course.fullname course_fullname
         FROM {$CFG->prefix}var_blockcourse blockcourse
              LEFT JOIN {$CFG->prefix}course course ON ( blockcourse.courseid=course.id AND course.visible)
         WHERE blockcourse.id=" . ( (int) $id ) . " ";
    //echo $sql;
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    return isset($list[0]) ? $list[0] : false;
}

function variatives_blockcourse_object($values) {
    $info = new stdClass();

    if (isset($values['varblockid'])) {
        $info->varblockid = (int) $values['varblockid'];
    }

    if (isset($values['courseid'])) {
        $info->courseid = (int) $values['courseid'];
    }

    if (isset($values['varblockcourserating'])) {
        $info->varblockcourserating = (int) $values['varblockcourserating'];
    }
    if (isset($values['varblockcoursegroup'])) {
        $info->varblockcoursegroup = strip_tags($values['varblockcoursegroup']);
    }

    return $info;
}

function variatives_blockcourse_update($id, $values) {
    global $DB;

    $info = variatives_blockcourse_object($values);
    $info->id = (int) $id;

    // var_dump($info);exit();
    if (count((array) $info) > 0) {
        $DB->update_record('var_blockcourse', $info);
    }
    return variatives_blockcourse_get($id);
}

function variatives_blockcourse_create($values) {
    global $DB;

    $info = variatives_blockcourse_object($values);
    // var_dump($info);exit();

    if (count((array) $info) > 0) {
        $id = $DB->insert_record('var_blockcourse', $info, $returnid = true);
        return variatives_blockcourse_get($id);
    }
    return null;
}

function variatives_blockcourse_delete($id) {
    global $DB;
    $info = variatives_blockcourse_get($id);
    if ($info) {
        //var_dump($group);
        $DB->delete_records('var_blockcourse', array('id' => $id));
    }
    return $info;
}

function variatives_blockcourse_find($substr) {
    global $DB, $CFG;
    // echo $substr.'<hr>';
    $sql = "SELECT  course.id,
                  course.shortname course_shortname,
                  course.fullname course_fullname
         FROM {$CFG->prefix}course course
         WHERE course.visible AND ( LOCATE(?,course.fullname)>0 OR LOCATE(?,course.shortname)>0 ) ";
    // echo $sql; 
    $list = array_values($DB->get_records_sql($sql, $params = Array($substr, $substr), $limitfrom = 0, $limitnum = 20));
    // print_r($list);
    // exit();
    return $list;
}

function variatives_enroll_list($filter) {
    global $DB, $CFG;
    // Вибираємо призначені студентам курси

    $where = Array();
    $param = Array();
    if (isset($filter['username']) && strlen($filter['username']) > 0) {
        $where[] = " ( locate(:username1, `user`.firstname)>0 OR locate(:username2, `user`.lastname)>0 )";
        $param['username1'] = $filter['username'];
        $param['username2'] = $filter['username'];
    }
    if (isset($filter['varblockname']) && strlen($filter['varblockname']) > 0) {
        $where[] = " locate(:varblockname, var_block.varblockname)>0 ";
        $param['varblockname'] = $filter['varblockname'];
    }
    if (isset($filter['varblockid'])) {
        if(is_array($filter['varblockid']) && count($filter['varblockid'])>0){
            $where[] = " var_block.id IN(".join(',',$filter['varblockid']).") ";
            //$param['varblockid'] = $filter['varblockid'];
        }elseif($filter['varblockid']>0){
            $where[] = " var_block.id=:varblockid ";
            $param['varblockid'] = (int)$filter['varblockid'];
        }
    }
    if (isset($filter['vargroupcode']) && strlen($filter['vargroupcode']) > 0) {
        $where[] = " locate(:vargroupcode, var_group.vargroupcode)>0 ";
        $param['vargroupcode'] = $filter['vargroupcode'];
    }
    if (isset($filter['vargroupyear']) && $filter['vargroupyear'] > 0) {
        $where[] = " var_group.vargroupyear=:vargroupyear ";
        $param['vargroupyear'] = $filter['vargroupyear'];
    }
    if (isset($filter['vardepartmentid']) && $filter['vardepartmentid'] > 0) {
        $where[] = " var_group.vardepartmentid = :vardepartmentid ";
        $param['vardepartmentid'] = $filter['vardepartmentid'];
    }
    if (isset($filter['varformid']) && $filter['varformid'] > 0) {
        $where[] = " var_group.varformid = :varformid ";
        $param['varformid'] = $filter['varformid'];
    }
    if (isset($filter['varlevelid']) && $filter['varlevelid'] > 0) {
        $where[] = " var_group.varlevelid = :varlevelid ";
        $param['varlevelid'] = $filter['varlevelid'];
    }
    if (isset($filter['varspecialityid']) && $filter['varspecialityid'] > 0) {
        $where[] = " var_group.varspecialityid = :varspecialityid ";
        $param['varspecialityid'] = $filter['varspecialityid'];
    }
    if (isset($filter['course_fullname']) && strlen($filter['course_fullname']) > 0) {
        $where[] = " locate(:course_fullname, course.fullname)>0 ";
        $param['course_fullname'] = $filter['course_fullname'];
    }
    if (isset($filter['varblockcoursegroup']) && strlen($filter['varblockcoursegroup']) > 0) {
        $where[] = " locate(:varblockcoursegroup, var_blockcourse.varblockcoursegroup)>0 ";
        $param['varblockcoursegroup'] = $filter['varblockcoursegroup'];
    }

    if (count($where) > 0) {
        $where_sql = " WHERE " . join(' AND ', $where);
    } else {
        $where_sql = '';
    }
    if (isset($filter['orderby']) && $filter['orderby'] == 'group') {
        $orderby = "ORDER BY var_department.vardepartmentname, var_form.varformname, var_level.varlevelname , var_speciality.varspecialityname, var_group.vargroupcode, userfullname";
    } elseif (isset($filter['orderby']) && $filter['orderby'] == 'user') {
        $orderby = "ORDER BY userfullname, var_blockcourse.varblockcoursegroup, course_fullname ";
    } else {
        $orderby = "ORDER BY var_blockcourse.varblockcoursegroup, course_fullname, userfullname";
    }


    $sql = "
        SELECT SQL_CALC_FOUND_ROWS var_enroll.id as enrollid, `user`.id userid, 
        `user`.lastname userlastname, `user`.firstname userfirstname,
        concat_ws(' ', `user`.lastname, `user`.firstname) as userfullname,
        var_group.id vargroupid, var_group.vargroupcode, var_group.vargroupyear,
        var_group.vardepartmentid, var_department.vardepartmentname,
        var_group.varformid, var_form.varformname,
        var_group.varlevelid, var_level.varlevelname,
        var_group.varspecialityid , var_speciality.varspecialityname,
        var_block.varblockname,
        var_enroll.courseid, var_blockcourse.varblockcoursegroup ,course.fullname AS course_fullname
        FROM {$CFG->prefix}user `user`
             INNER JOIN {$CFG->prefix}cohort_members cohort_members ON `user`.id=cohort_members.userid
             INNER JOIN {$CFG->prefix}var_group var_group ON var_group.cohortid=cohort_members.cohortid
             INNER JOIN {$CFG->prefix}var_department var_department ON var_department.id=var_group.vardepartmentid
             INNER JOIN {$CFG->prefix}var_form var_form ON var_form.id=var_group.varformid
             INNER JOIN {$CFG->prefix}var_level var_level ON var_level.id=var_group.varlevelid
             INNER JOIN {$CFG->prefix}var_speciality var_speciality ON var_group.varspecialityid=var_speciality.id
             INNER JOIN {$CFG->prefix}var_enroll var_enroll ON `user`.id=var_enroll.userid
             INNER JOIN {$CFG->prefix}course course ON course.id=var_enroll.courseid
             INNER JOIN {$CFG->prefix}var_block var_block ON var_block.id=var_enroll.varblockid
             INNER JOIN {$CFG->prefix}var_blockcourse var_blockcourse ON (    var_blockcourse.varblockid=var_block.id AND var_blockcourse.courseid = course.id)
        {$where_sql}  
        {$orderby}
    ";
    // 
    // echo $sql;
    // print_r($param);
    //  
    $limitfrom = isset($filter['start']) ? abs((int) $filter['start']) : 0;
    $limitnum = isset($filter['rows_per_page']) ? abs((int) $filter['rows_per_page']) : 100;

    $list = array_values($DB->get_records_sql($sql, $param, $limitfrom, $limitnum));

    $n_records = array_values($DB->get_records_sql("SELECT FOUND_ROWS() as n_records"));
    // print_r($n_records);

    return Array('n_records' => $n_records[0]->n_records, 'filter' => $filter, 'start' => $limitfrom, 'rows_per_page' => $limitnum, 'rows' => $list);
}

function variatives_block_find($keyword) {
    global $DB, $CFG;


    if (strlen($keyword) > 0) {
        $sql = "SELECT var_block.*,
                     var_form.varformname,
                     var_level.varlevelname
              FROM {$CFG->prefix}var_block AS var_block
                   LEFT JOIN {$CFG->prefix}var_form AS  var_form  ON var_block.varformid=var_form.id
                   LEFT JOIN {$CFG->prefix}var_level AS var_level ON var_block.varlevelid=var_level.id
              WHERE locate(:keyword, var_block.varblockname)
              ORDER BY var_block.varblockisarchive ASC,var_block.varblockname
              ";
        $list = array_values($DB->get_records_sql($sql, Array('keyword' => $keyword), $limitfrom = 0, $limitnum = 20));
    } else {
        $list = Array();
    }
    // $list = $DB->get_records_select('var_department', $where = '', $params = Array(), $sort = 'vardepartmentname ASC');
    return $list;
}

function variatives_enroll_user_find($varblockid, $keyword = '', $id = 0) {
    global $DB, $CFG;

    $where = Array();
    $param = Array();

    $param['varblockid'] = $varblockid;
    $where[] = " var_block.id=:varblockid ";

    if (strlen($keyword) > 0) {
        $where[] = "( locate(:keyword1, `user`.lastname)>0 OR  locate(:keyword2, `user`.firstname)>0 )";
        $param['keyword1'] = $keyword;
        $param['keyword2'] = $keyword;
    }

    if (strlen($id) > 0 && $id > 0) {
        $where[] = " `user`.id=:userid ";
        $param['userid'] = $id;
    }

    if (count($where) > 1) {
        $sql = "  SELECT `user`.id userid, `user`.lastname userlastname, `user`.firstname userfirstname,
                var_group.id vargroupid, var_group.vargroupcode, var_group.vargroupyear,
                var_group.vardepartmentid, var_department.vardepartmentname,
                var_group.varformid, var_form.varformname,
                var_group.varlevelid, var_level.varlevelname,
                var_group.varspecialityid , var_speciality.varspecialityname,
                var_block.id AS varblockid
                FROM {$CFG->prefix}user `user`
                     INNER JOIN {$CFG->prefix}cohort_members cohort_members ON `user`.id=cohort_members.userid
                     INNER JOIN {$CFG->prefix}var_group var_group ON var_group.cohortid=cohort_members.cohortid
                     INNER JOIN {$CFG->prefix}var_department var_department ON var_department.id=var_group.vardepartmentid
                     INNER JOIN {$CFG->prefix}var_form var_form ON var_form.id=var_group.varformid
                     INNER JOIN {$CFG->prefix}var_level var_level ON var_level.id=var_group.varlevelid
                     INNER JOIN {$CFG->prefix}var_speciality var_speciality ON var_group.varspecialityid=var_speciality.id
                     INNER JOIN {$CFG->prefix}var_block var_block 
                           ON (    var_block.varformid=var_group.varformid 
                               AND var_block.varlevelid=var_group.varlevelid
                               AND var_block.vargroupyear=var_group.vargroupyear)
                     INNER JOIN {$CFG->prefix}var_blockgroup var_blockgroup 
                           ON (     var_blockgroup.varblockid=var_block.id
                               AND (var_blockgroup.vardepartmentid is null OR var_blockgroup.vardepartmentid=0 OR var_blockgroup.vardepartmentid=var_department.id)
                               AND (var_blockgroup.varspecialityid is null OR var_blockgroup.varspecialityid=0 OR var_blockgroup.varspecialityid=var_speciality.id)
                               )
                WHERE " . join(' AND ', $where) . "
                ORDER BY userlastname, var_department.vardepartmentname, var_form.varformname, var_level.varlevelname , var_speciality.varspecialityname
                 ";
        // echo $sql; exit('###');
        $list = array_values($DB->get_records_sql($sql, $param, $limitfrom = 0, $limitnum = 20));
    } else {
        $list = Array();
    }
    return $list;
}

function variatives_enroll_course_find($varblockid, $keyword = '', $courseid = 0) {
    global $DB, $CFG;


    $where = Array();
    $param = Array();

    $param['varblockid'] = $varblockid;
    $where[] = " var_blockcourse.varblockid=:varblockid ";

    if (strlen($keyword) > 0) {
        $where[] = " locate(:keyword1, course.fullname)>0 ";
        $param['keyword1'] = $keyword;
    }
    if ($courseid > 0) {
        $where[] = " var_blockcourse.courseid=:courseid ";
        $param['courseid'] = $courseid;
    }
    if (count($where) > 1) {
        $sql = "  SELECT var_blockcourse.courseid, var_blockcourse.varblockcoursegroup,
                       course.fullname AS course_fullname,  var_blockcourse.varblockcourserating
                FROM {$CFG->prefix}var_blockcourse var_blockcourse
                     INNER JOIN {$CFG->prefix}course course ON course.id=var_blockcourse.courseid
                WHERE " . join(' AND ', $where) . "
                ORDER BY varblockcourserating, course_fullname
                 ";
        //echo $sql; exit('###');
        $list = array_values($DB->get_records_sql($sql, $param, $limitfrom = 0, $limitnum = 20));
    } else {
        $list = Array();
    }
    return $list;
}

function variatives_enroll_get($id) {
    global $DB, $CFG;
    $sql = "
        SELECT  var_enroll.id as enrollid, `user`.id userid, `user`.lastname userlastname, `user`.firstname userfirstname,
                var_group.id vargroupid, var_group.vargroupcode, var_group.vargroupyear,
                var_group.vardepartmentid, var_department.vardepartmentname,
                var_group.varformid, var_form.varformname,
                var_group.varlevelid, var_level.varlevelname,
                var_group.varspecialityid , var_speciality.varspecialityname,
                var_block.varblockname, var_enroll.varblockid,
                var_enroll.courseid, var_blockcourse.varblockcoursegroup ,course.fullname AS course_fullname
        FROM {$CFG->prefix}user `user`
             INNER JOIN {$CFG->prefix}cohort_members cohort_members ON `user`.id=cohort_members.userid
             INNER JOIN {$CFG->prefix}var_group var_group ON var_group.cohortid=cohort_members.cohortid
             INNER JOIN {$CFG->prefix}var_department var_department ON var_department.id=var_group.vardepartmentid
             INNER JOIN {$CFG->prefix}var_form var_form ON var_form.id=var_group.varformid
             INNER JOIN {$CFG->prefix}var_level var_level ON var_level.id=var_group.varlevelid
             INNER JOIN {$CFG->prefix}var_speciality var_speciality ON var_group.varspecialityid=var_speciality.id
             INNER JOIN {$CFG->prefix}var_enroll var_enroll ON `user`.id=var_enroll.userid
             INNER JOIN {$CFG->prefix}course course ON course.id=var_enroll.courseid
             INNER JOIN {$CFG->prefix}var_block var_block ON var_block.id=var_enroll.varblockid
             INNER JOIN {$CFG->prefix}var_blockcourse var_blockcourse ON (    var_blockcourse.varblockid=var_block.id AND var_blockcourse.courseid = course.id)
        WHERE var_enroll.id=" . ( (int) $id ) . "
    ";
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    return isset($list[0]) ? $list[0] : false;
}

function variatives_enroll_object($values) {
    global $DB, $CFG;

    $info = new stdClass();

    if (isset($values['varblockid'])) {
        $info->varblockid = (int) $values['varblockid'];
    }

    if (isset($values['courseid'])) {
        $info->courseid = (int) $values['courseid'];
    }

    if (isset($values['userid'])) {
        $info->userid = (int) $values['userid'];
    }

    if (isset($values['varblockcoursegroup'])) {
        $info->varblockcoursegroup = $values['varblockcoursegroup'];
    }



    return $info;
}

function variatives_enroll_update($id, $values) {
    global $DB;

    $info = variatives_enroll_object($values);
    $info->id = (int) $id;

    $previousinfo = variatives_enroll_get($id);
    // var_dump($info);exit();

    if (count((array) $info) > 0) {
        $DB->update_record('var_enroll', $info);
        if ($previousinfo && ( $previousinfo->courseid != $info->courseid || $previousinfo->userid != $info->userid )) {
            variatives_unenroll_user($previousinfo->courseid, $previousinfo->userid);
        }
        variatives_enroll_to_course($info->courseid, $info->userid);
    }
    return variatives_enroll_get($id);
}

function variatives_enroll_create($values) {
    global $DB;

    $info = variatives_enroll_object($values);
    // var_dump($info);exit();

    if (count((array) $info) > 0) {
        $id = $DB->insert_record('var_enroll', $info, $returnid = true);

        variatives_enroll_to_course($info->courseid, $info->userid);
        return variatives_enroll_get($id);
    }
    return null;
}

function variatives_enroll_delete($id) {
    global $DB;
    $info = variatives_enroll_get($id);
    if ($info) {
        variatives_unenroll_user($info->courseid, $info->userid);
        $DB->delete_records('var_enroll', array('id' => $id));
    }
    return $info;
}

// enroll student to course (roleid = 5 is student role)
function variatives_enroll_to_course($courseid, $userid, $roleid = 5, $extendbase = 3, $extendperiod = 0) {
    global $DB;

    $instance = $DB->get_record('enrol', array('courseid' => $courseid, 'enrol' => 'manual'), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $instance->courseid), '*', MUST_EXIST);
    $today = time();
    $today = make_timestamp(date('Y', $today), date('m', $today), date('d', $today), 0, 0, 0);

    if (!$enrol_manual = enrol_get_plugin('manual')) {
        throw new coding_exception('Can not instantiate enrol_manual');
    }
    switch ($extendbase) {
        case 2:
            $timestart = $course->startdate;
            break;
        case 3:
        default:
            $timestart = $today;
            break;
    }
    if ($extendperiod <= 0) {
        $timeend = 0;
    }   // extendperiod are seconds
    else {
        $timeend = $timestart + $extendperiod;
    }
    $enrolled = $enrol_manual->enrol_user($instance, $userid, $roleid, $timestart, $timeend);
    add_to_log($course->id, 'course', 'enrol', '../enrol/users.php?id=' . $course->id, $course->id);

    return $enrolled;
}

// enroll student to course (roleid = 5 is student role)
function variatives_unenroll_user($courseid, $userid) {
    global $DB;

    $instance = $DB->get_record('enrol', array('courseid' => $courseid, 'enrol' => 'manual'), '*', MUST_EXIST);

    $course = $DB->get_record('course', array('id' => $instance->courseid), '*', MUST_EXIST);


    if (!$enrol_manual = enrol_get_plugin('manual')) {
        throw new coding_exception('Can not instantiate enrol_manual');
    }

    $enrolled = $enrol_manual->unenrol_user($instance, $userid);
    add_to_log($course->id, 'course', 'unenrol', '../enrol/users.php?id=' . $course->id, $course->id);

    return $enrolled;
}

/**
 * Перелік студентів, які вибрали / не вибрали курси
 */
function variatives_report_sql($varblockid) {
    global $DB, $CFG;


    $sql = [
        'sql',
        [ 'select',
            ['as', "concat(`user`.id,'-',var_group.id)", 'k'],
            ['as', 'var_block.id', 'varblockid'],
            ['as', 'var_block.varblockname', 'varblockname'],
            ['as', '`user`.id', 'userid'],
            ['as', '`user`.lastname', 'userlastname'],
            ['as', '`user`.firstname', 'userfirstname'],
            ['as', 'var_group.id', 'vargroupid'],
            ['as', 'var_group.vargroupcode', 'vargroupcode'],
            ['as', 'var_group.vargroupyear', 'vargroupyear'],
            ['as', 'var_group.vardepartmentid', 'vardepartmentid'],
            ['as', 'var_department.vardepartmentname', 'vardepartmentname'],
            ['as', 'var_group.varformid', 'varformid'],
            ['as', 'var_form.varformname', 'varformname'],
            ['as', 'var_group.varlevelid', 'varlevelid'],
            ['as', 'var_level.varlevelname', 'varlevelname'],
            ['as', 'var_speciality.varspecialityname', 'varspecialityname'],
            ['as', 'var_group.varspecialityid', 'varspecialityid'],
            ['as', 'var_subspecialityenroll.varsubspecialityid', 'varsubspecialityid'],
            ['as', 'var_subspeciality.varsubspecialitytitle', 'varsubspecialitytitle'],
            ['as', '( COUNT(var_userblockcourse.id)>0 )', 'rating_exists']
        ],
        ['from',
            ['as', "{$CFG->prefix}user", '`user`'],
            ['innerJoin',
                ['as', "{$CFG->prefix}cohort_members", "cohort_members"],
                ['on', ['and', "`user`.id=cohort_members.userid"]]
            ],
            ['innerJoin',
                ['as', "{$CFG->prefix}var_group", "var_group"],
                ['on', ['and', "var_group.cohortid=cohort_members.cohortid"]]
            ],
            ['innerJoin',
                ['as', "{$CFG->prefix}var_department", "var_department"],
                ['on', ['and', "var_department.id=var_group.vardepartmentid"]]
            ],
            ['innerJoin',
                ['as', "{$CFG->prefix}var_form", "var_form"],
                ['on', ['and', "var_form.id=var_group.varformid"]]
            ],
            ['innerJoin',
                ['as', "{$CFG->prefix}var_level", "var_level"],
                ['on', ['and', "var_level.id=var_group.varlevelid"]]
            ],
            ['innerJoin',
                ['as', "{$CFG->prefix}var_speciality", "var_speciality"],
                ['on', ['and', "var_group.varspecialityid=var_speciality.id"]]
            ],
            ['innerJoin',
                ['as', "{$CFG->prefix}var_block", "var_block"],
                ['on', ['and', "var_block.varformid=var_group.varformid", "var_block.varlevelid=var_group.varlevelid", "var_block.vargroupyear=var_group.vargroupyear"]]
            ],
            ['innerJoin',
                ['as', "{$CFG->prefix}var_blockgroup", "var_blockgroup"],
                ['on',
                    ['and',
                        "var_blockgroup.varblockid=var_block.id",
                        ['or',
                            "var_blockgroup.vardepartmentid is null",
                            "var_blockgroup.vardepartmentid=0",
                            "var_blockgroup.vardepartmentid=var_department.id"
                        ],
                        ['or',
                            "var_blockgroup.varspecialityid is null",
                            "var_blockgroup.varspecialityid=0",
                            "var_blockgroup.varspecialityid=var_speciality.id"
                        ]
                    ]
                ]
            ],
            ['innerJoin',
                ['as', "{$CFG->prefix}var_blockcourse", "var_blockcourse"],
                ['on', ['and', "var_block.id=var_blockcourse.varblockid"]]
            ],
            ['leftJoin',
                ['as', "{$CFG->prefix}var_userblockcourse", "var_userblockcourse"],
                ['on',
                    ['and',
                        "var_block.id=var_userblockcourse.varblockid",
                        "var_blockcourse.courseid=var_userblockcourse.courseid",
                        "`user`.id=var_userblockcourse.userid"
                    ]
                ]
            ],
            ['leftJoin',
                ['as', "{$CFG->prefix}var_subspecialityenroll", "var_subspecialityenroll"],
                ['on',
                    ['and',
                        "var_subspecialityenroll.userid=`user`.id",
                        "var_subspecialityenroll.vargroupid=var_group.id"]]
            ],
            ['leftJoin',
                ['as', "{$CFG->prefix}var_subspeciality", "var_subspeciality"],
                ['on', ['and', "var_subspecialityenroll.varsubspecialityid=var_subspeciality.id"]]
            ]
        ],
        ['where',
            ['and', "var_block.id=" . ( (int) $varblockid )]
        ],
        ['groupBy', 'userid', 'vargroupid'],
        ['having'],
        ['orderBy',
            'varblockid', 'vardepartmentname', 'varformname',
            'varlevelname', 'varspecialityname',
            'vargroupcode', 'userlastname'
        //'var_block.id', 'var_department.vardepartmentname', 'var_form.varformname',
        //'var_level.varlevelname' , 'var_speciality.varspecialityname', 
        //'var_group.vargroupcode' , 'userlastname'
        ]
    ];


    //
    //    $sql = "  
    //        SELECT concat(`user`.id,'-',var_group.id) k,
    //        
    //        var_block.id AS varblockid, var_block.varblockname,
    //        
    //       `user`.id userid, `user`.lastname userlastname,`user`.firstname userfirstname,
    //       
    //        var_group.id vargroupid, var_group.vargroupcode, var_group.vargroupyear,
    //        var_group.vardepartmentid, var_department.vardepartmentname,
    //        var_group.varformid, var_form.varformname,
    //        var_group.varlevelid, var_level.varlevelname,
    //        var_group.varspecialityid , var_speciality.varspecialityname,
    //        
    //        var_subspecialityenroll.varsubspecialityid,var_subspeciality.varsubspecialitytitle,
    //        
    //        ( COUNT(var_userblockcourse.id)>0 )  rating_exists
    //
    //        FROM {$CFG->prefix}user `user`
    //             INNER JOIN {$CFG->prefix}cohort_members cohort_members ON `user`.id=cohort_members.userid
    //             INNER JOIN {$CFG->prefix}var_group var_group ON var_group.cohortid=cohort_members.cohortid
    //                 
    //             INNER JOIN {$CFG->prefix}var_department var_department ON var_department.id=var_group.vardepartmentid
    //             INNER JOIN {$CFG->prefix}var_form var_form ON var_form.id=var_group.varformid
    //             INNER JOIN {$CFG->prefix}var_level var_level ON var_level.id=var_group.varlevelid
    //             INNER JOIN {$CFG->prefix}var_speciality var_speciality ON var_group.varspecialityid=var_speciality.id
    //                 
    //
    //             INNER JOIN {$CFG->prefix}var_block var_block 
    //                   ON (    var_block.varformid=var_group.varformid 
    //                       AND var_block.varlevelid=var_group.varlevelid
    //                       AND var_block.vargroupyear=var_group.vargroupyear)
    //
    //             INNER JOIN {$CFG->prefix}var_blockgroup var_blockgroup 
    //                   ON (     var_blockgroup.varblockid=var_block.id
    //                       AND (var_blockgroup.vardepartmentid is null OR var_blockgroup.vardepartmentid=0 OR var_blockgroup.vardepartmentid=var_department.id)
    //                       AND (var_blockgroup.varspecialityid is null OR var_blockgroup.varspecialityid=0 OR var_blockgroup.varspecialityid=var_speciality.id)
    //                       )
    //
    //             INNER JOIN {$CFG->prefix}var_blockcourse var_blockcourse ON var_block.id=var_blockcourse.varblockid
    //             LEFT  JOIN {$CFG->prefix}var_userblockcourse var_userblockcourse 
    //                  ON(
    //                     var_block.id=var_userblockcourse.varblockid
    //                     AND var_blockcourse.courseid=var_userblockcourse.courseid
    //                     AND `user`.id=var_userblockcourse.userid
    //                  )
    //                  
    //             LEFT JOIN {$CFG->prefix}var_subspecialityenroll var_subspecialityenroll
    //             ON (var_subspecialityenroll.userid=`user`.id AND var_subspecialityenroll.vargroupid=var_group.id)
    //
    //             LEFT JOIN {$CFG->prefix}var_subspeciality var_subspeciality
    //             ON (var_subspecialityenroll.varsubspecialityid=var_subspeciality.id)
    //
    //        WHERE var_block.id=" . ( (int) $varblockid ) . "
    //        GROUP BY userid, vargroupid
    //        ORDER BY var_block.id, var_department.vardepartmentname, var_form.varformname, var_level.varlevelname , var_speciality.varspecialityname, var_group.vargroupcode, userlastname;
    //        ";
    //echo $sql;
    //$list = $DB->get_records_sql($sql);
    //print_r($list);
    return $sql;
}

class sqlBuilder {

    private $functions = [];

    public function __construct() {

        $excludeEmpty = function($x) {
            return $x == '' ? false : true;
        };

        $this->functions['sql'] = function($list) {

            $select = '';
            foreach ($list as $ls) {
                $ls = trim($ls);
                if (preg_match('/^SELECT /', $ls)) {
                    $select = $ls;
                    break;
                }
            }

            $from = '';
            foreach ($list as $ls) {
                $ls = trim($ls);
                if (preg_match('/^FROM /', $ls)) {
                    $from = $ls;
                    break;
                }
            }


            $where = '';
            foreach ($list as $ls) {
                $ls = trim($ls);
                if (preg_match('/^WHERE /', $ls)) {
                    $where = $ls;
                    break;
                }
            }

            $groupBy = '';
            foreach ($list as $ls) {
                $ls = trim($ls);
                if (preg_match('/^GROUP /', $ls)) {
                    $groupBy = $ls;
                    break;
                }
            }

            $having = '';
            foreach ($list as $ls) {
                $ls = trim($ls);
                if (preg_match('/^HAVING /', $ls)) {
                    $having = $ls;
                    break;
                }
            }

            $orderBy = '';
            foreach ($list as $ls) {
                $ls = trim($ls);
                if (preg_match('/^ORDER /', $ls)) {
                    $orderBy = $ls;
                    break;
                }
            }
            return "$select $from $where $groupBy $having $orderBy";
        };


        $this->functions['csv'] = function($args) {
            return join(', ', $args);
        };
        $this->functions['select'] = function($args) {
            return "SELECT " . join(', ', $args);
        };
        $this->functions['as'] = function($args) {
            return " {$args[0]} AS {$args[1]} ";
        };
        $this->functions['from'] = function($args) {
            return "FROM " . join(' ', $args) . ' ';
        };
        $this->functions['innerJoin'] = function($args) {
            return "INNER JOIN {$args[0]} {$args[1]} ";
        };
        $this->functions['leftJoin'] = function($args) {
            return "LEFT JOIN {$args[0]} {$args[1]} ";
        };
        $this->functions['on'] = function($args) {
            return "ON {$args[0]}";
        };

        $this->functions['and'] = function($args) use($excludeEmpty) {
            $list = array_filter($args, $excludeEmpty);
            if (count($list) == 0) {
                return '';
            }
            return '(' . join(') AND (', $list) . ')';
        };
        $this->functions['or'] = function($args) use($excludeEmpty) {
            $list = array_filter($args, $excludeEmpty);
            if (count($list) == 0) {
                return '';
            }
            return '(' . join(') OR (', $list) . ')';
        };
        $this->functions['not'] = function($args) use($excludeEmpty) {
            $list = array_filter($args, $excludeEmpty);
            if (count($list) == 0) {
                return '';
            }
            return "NOT ($args[0]) ";
        };
        $this->functions['where'] = function($args) use($excludeEmpty) {
            $list = array_filter($args, $excludeEmpty);
            if (count($list) == 0) {
                return '';
            }
            return "WHERE (" . join(') AND (', $list) . ')';
        };
        $this->functions['groupBy'] = function($args) use($excludeEmpty) {
            $list = array_filter($args, $excludeEmpty);
            if (count($list) == 0) {
                return '';
            }
            return "GROUP BY " . join(', ', $list);
        };
        $this->functions['having'] = function($args) use($excludeEmpty) {
            $list = array_filter($args, $excludeEmpty);
            if (count($list) == 0) {
                return '';
            }
            return "HAVING  (" . join(') AND (', $list) . ')';
        };
        $this->functions['orderBy'] = function($args) use($excludeEmpty) {
            $list = array_filter($args, $excludeEmpty);
            if (count($list) == 0) {
                return '';
            }
            return "ORDER BY " . join(', ', $list);
        };
        //        $this->functions[''] = function($args) {
        //            
        //        };
        //        $this->functions[''] = function($args) {
        //            
        //        };
    }

    public function build($tree) {
        return $this->buildPart($tree);
    }

    private function get($fname, $list) {
        foreach ($list as $el) {
            if ($el[0] == $fname) {
                return $el;
            }
        }
        return null;
    }

    private function buildPart($list) {
        if (!isset($this->functions[$list[0]])) {
            echo "ERROR: unknown function {$list[0]};<br>";
            return null;
        }
        $values = [];
        for ($i = 1, $cnt = count($list); $i < $cnt; $i++) {
            if (is_array($list[$i])) {
                $values[] = $this->buildPart($list[$i]);
            } else {
                $values[] = $list[$i];
            }
        }
        $res = $this->functions[$list[0]]($values);
        return $res;
    }

    //$tree2=$sqlBuilder->addTo($sqlTree, ['having'], 'rating_exists');
    //var_dump($tree2);
    public function addTo($tree, $path, $element) {
        $clone = json_decode(json_encode($tree), true);
        $elm = &$clone;
        foreach ($path as $pat) {
            $elmFound = false;
            $cnt = count($elm);
            for ($i = 0; $i < $cnt; $i++) {
                if ($elm[$i][0] == $pat) {
                    $elm = &$elm[$i];
                    $elmFound = true;
                    break;
                }
            }
            if (!$elmFound) {
                $elm[$cnt] = [$pat];
                $elm = &$elm[$cnt];
            }
        }
        $elm[] = $element;
        return $clone;
    }

}

function variatives_report_waiting($varblockid) {
    global $DB, $CFG;

    // get block info
    $blockInfo = variatives_block_get($varblockid);
    //var_dump($blockInfo);
    // get block groups
    $blockGroups = variatives_blockgroup_list($varblockid);
    //var_dump($blockGroups);
    // get list of students
    $sqlBuilder = new sqlBuilder();
    $sqlTree = variatives_report_sql($varblockid);
    // var_dump($sqlTree);
    //$tree2=$sqlBuilder->addTo($sqlTree, ['having'], 'rating_exists');
    //var_dump($tree2);

    $sql = $sqlBuilder->build($sqlTree);
    // echo $sql;
    $rawList = $DB->get_records_sql($sql);
    //var_dump($rawList);


    $result = [];
    foreach ($blockGroups as $bg) {
        foreach ($rawList as $student) {
            $accept = true;
            if ($bg->varformid && $student->varformid != $blockInfo->varformid) {
                $accept = false;
            }
            if ($bg->varlevelid && $student->varlevelid != $blockInfo->varlevelid) {
                $accept = false;
            }
            if ($bg->vargroupyear && $student->vargroupyear != $blockInfo->vargroupyear) {
                $accept = false;
            }
            if ($bg->vargroupyear && $student->vargroupyear != $blockInfo->vargroupyear) {
                $accept = false;
            }

            if ($bg->vardepartmentid && $student->vardepartmentid != $bg->vardepartmentid) {
                $accept = false;
            }
            if ($bg->varspecialityid && $student->varspecialityid != $bg->varspecialityid) {
                $accept = false;
            }
            if ($bg->varsubspecialityid && $student->varsubspecialityid != $bg->varsubspecialityid) {
                $accept = false;
            }
            if ($accept) {
                $result[] = $student;
            }
        }
    }
    return $result;
}

/**
 * Список курсов, которые предлагаются студенту
 */
function variatives_user_courses($userid) {
    global $DB, $CFG;
    /**
     * Вибираємо запропоновані студентам курси
     * використовуємо у формі призначення курсу для одного студента
     */
    $sql = "
        SELECT CONCAT(var_block.id,'-',var_blockcourse.courseid,'-',cohort_members.userid) AS k, 
                cohort_members.userid,var_block.varblockminstudents,
                var_block.id AS varblockid, var_block.varblockname,
                var_block.varblocktimestampfrom, var_block.varblocktimestampto,
                var_blockcourse.courseid,var_blockcourse.varblockcoursegroup, course.fullname AS course_fullname,
                var_userblockcourse.varuserblockcourserating, var_blockcourse.varblockcourserating,
                ifnull(var_userblockcourse.varuserblockcourserating, var_blockcourse.varblockcourserating) as rating,
                var_blockgroup.varblockgroupnumcourses
        FROM {$CFG->prefix}cohort_members cohort_members
             INNER JOIN {$CFG->prefix}var_group var_group ON var_group.cohortid=cohort_members.cohortid
             INNER JOIN {$CFG->prefix}var_block var_block 
                   ON (    var_block.varformid=var_group.varformid 
                       AND var_block.varlevelid=var_group.varlevelid
                       AND var_block.vargroupyear=var_group.vargroupyear)
             INNER JOIN {$CFG->prefix}var_blockgroup var_blockgroup 
                   ON (     var_blockgroup.varblockid=var_block.id
                       AND (var_blockgroup.vardepartmentid IS NULL OR var_blockgroup.vardepartmentid=0 OR var_blockgroup.vardepartmentid=var_group.vardepartmentid)
                       AND (var_blockgroup.varspecialityid IS NULL OR var_blockgroup.varspecialityid=0 OR var_blockgroup.varspecialityid=var_group.varspecialityid)
                       )
             INNER JOIN {$CFG->prefix}var_blockcourse var_blockcourse ON var_block.id=var_blockcourse.varblockid
             INNER JOIN {$CFG->prefix}course course ON course.id=var_blockcourse.courseid
             LEFT JOIN {$CFG->prefix}var_userblockcourse var_userblockcourse 
                  ON(
                     var_block.id=var_userblockcourse.varblockid
                     AND course.id=var_userblockcourse.courseid
                     AND cohort_members.userid=var_userblockcourse.userid
                  )
        WHERE cohort_members.userid=" . ( (int) $userid ) . "
              AND var_block.varblocktimestampfrom<=" . time() . "
              AND " . time() . "<=var_block.varblocktimestampto
        ORDER BY var_block.varblockname,
                 var_userblockcourse.varuserblockcourserating,
                 var_blockcourse.varblockcourserating;
        ";
    // echo $sql;exit();

    $list = array_values($DB->get_records_sql($sql));

    //print_r($list); exit();

    $block = Array();
    foreach ($list as $li) {
        if (!isset($block[$li->varblockid])) {
            $block[$li->varblockid] = Array(
                'varblockid' => $li->varblockid,
                'varblockname' => $li->varblockname,
                'varblocktimestampfrom' => $li->varblocktimestampfrom,
                'varblocktimestampto' => $li->varblocktimestampto,
                'varblockminstudents' => $li->varblockminstudents,
                'varblockgroupnumcourses' => $li->varblockgroupnumcourses,
                'var_blockcourse' => Array()
            );
        }
        $block[$li->varblockid]['var_blockcourse'][] = $li;
    }
    // print_r($block); 

    return $block;
}

function variatives_rating_update($varblockid, $userid, $courseids) {
    global $DB, $CFG;

    $DB->delete_records('var_userblockcourse', array('varblockid' => $varblockid, 'userid' => $userid));

    foreach ($courseids as $key => $courseid) {
        $record = new stdClass();
        $record->varblockid = (int) $varblockid;
        $record->courseid = (int) $courseid;
        $record->userid = (int) $userid;
        $record->varuserblockcourserating = $key + 1;
        $DB->insert_record('var_userblockcourse', $record, $returnid = true);
    }
}

class variatives_assignment {

    public $varblockids;
    public $assignment;
    private $prevAssignments;

    public function __construct() {
        
    }

    function initiate($varblockids) {

        global $DB, $CFG;

        $this->varblockids = $varblockids;
        sort($this->varblockids);

        $DB->delete_records('var_assignmentqueue', array('varblockid' => join(',', $this->varblockids)));

        $this->initiate_load();
        $this->initiate_assignment();
        //
        $this->prevAssignments=false;
        $this->toqueue($this->assignment);
        // exit();
        //echo '<pre>'; print_r($this->assignment); echo '</pre>';  exit();
    }

    /**
     * Вибираємо рейтинги курсів, виставлені студентами.
     * Якщо студент рейтинг не задав, то використовується 
     * середній рейтинг, обчислений у межах блока
     */
    function initiate_load() {

        global $DB, $CFG;


        // ----------------- get average course rating - begin -----------------
        $sql = "SELECT 
                CONCAT(var_blockcourse.varblockid,'-',var_blockcourse.courseid) k,
                var_blockcourse.varblockid, var_blockcourse.courseid,
                AVG(var_userblockcourse.varuserblockcourserating) AS rating

                FROM {$CFG->prefix}var_blockcourse var_blockcourse
                     LEFT JOIN {$CFG->prefix}var_userblockcourse var_userblockcourse
                     ON (    var_blockcourse.varblockid=var_userblockcourse.varblockid 
                         AND var_blockcourse.courseid=var_userblockcourse.courseid)
                WHERE var_blockcourse.courseid IS NOT NULL
                      AND var_blockcourse.varblockid IN(" . join(',', $this->varblockids) . ")
                GROUP BY var_blockcourse.varblockid, var_blockcourse.courseid
                ORDER BY var_blockcourse.varblockid, rating ASC
        ";
        $rows = array_values($DB->get_records_sql($sql));
        $varblockcourserating = [];
        foreach ($rows as $row) {
            if (!isset($varblockcourserating[$row->varblockid])) {
                $varblockcourserating[$row->varblockid] = [];
            }
            $varblockcourserating[$row->varblockid][] = $row->courseid;
        }
        // echo "<pre>"; echo htmlspecialchars($sql); echo "</pre>";
        // var_dump($varblockcourserating);    
        //exit();
        // ----------------- get average course rating - end -------------------
        // ----------------- save average course rating - begin ----------------
        foreach ($varblockcourserating as $varblockid => $blk) {
            foreach ($blk as $defaultrating => $courseid) {
                $sql = "UPDATE {$CFG->prefix}var_blockcourse 
                      SET varblockcourserating=" . ( (int) $defaultrating )
                        . " WHERE varblockid=" . ( (int) $varblockid )
                        . "   AND courseid=" . ( (int) $courseid );
                $DB->execute($sql);
            }
        }



        $default_rating_keys = [];
        $default_rating_vals = [];

        foreach ($varblockcourserating as $varblockid => $blk) {
            $default_rating_keys[$varblockid] = [];
            $default_rating_vals[$varblockid] = [];
            foreach ($blk as $defaultrating => $courseid) {
                $default_rating_keys[$varblockid][] = $courseid;
                $default_rating_vals[$varblockid][] = $defaultrating;
            }
        }
        // var_dump($default_rating_keys);
        // var_dump($default_rating_vals);
        // exit();
        // ----------------- save average course rating - end ------------------

        $sqlTree = [
            'sql',
            [ 'select',
                ['as', "CONCAT(var_block.id,'-',var_blockcourse.courseid,'-',cohort_members.userid)", 'k'],
                ['as', 'var_block.id', 'varblockid'],
                ['as', 'var_blockcourse.courseid', 'courseid'],
                ['as', 'cohort_members.userid', 'userid'],
                ['as', 'var_userblockcourse.varuserblockcourserating', 'varuserblockcourserating'],
                ['as', 'var_blockgroup.varblockgroupnumcourses', 'varblockgroupnumcourses'],
                ['as', 'var_block.varblockname', 'varblockname'],
                ['as', 'var_blockcourse.varblockcoursegroup', 'varblockcoursegroup'],
                ['as', 'course.fullname', 'course_fullname'],
                ['as', 'var_group.vargroupcode', 'vargroupcode']
            ],
            ['from',
                ['as', "{$CFG->prefix}cohort_members", 'cohort_members'],
                ['innerJoin',
                    ['as', "{$CFG->prefix}var_group", "var_group"],
                    ['on', ['and', "var_group.cohortid=cohort_members.cohortid"]]
                ],
                ['innerJoin',
                    ['as', "{$CFG->prefix}var_block", "var_block"],
                    ['on', ['and',
                            "var_block.varformid=var_group.varformid",
                            "var_block.varlevelid=var_group.varlevelid",
                            "var_block.vargroupyear=var_group.vargroupyear"
                        ]
                    ]
                ],
                ['innerJoin',
                    ['as', "{$CFG->prefix}var_blockgroup", "var_blockgroup"],
                    ['on', ['and',
                            "var_blockgroup.varblockid=var_block.id",
                            ['or', 'var_blockgroup.vardepartmentid is null', 'var_blockgroup.vardepartmentid=0', 'var_blockgroup.vardepartmentid=var_group.vardepartmentid'],
                            ['or', 'var_blockgroup.varspecialityid is null', 'var_blockgroup.varspecialityid=0', 'var_blockgroup.varspecialityid=var_group.varspecialityid']
                        ]
                    ]
                ],
                ['innerJoin',
                    ['as', "{$CFG->prefix}var_blockcourse", "var_blockcourse"],
                    ['on', ['and', "var_block.id=var_blockcourse.varblockid"]]
                ],
                ['innerJoin',
                    ['as', "{$CFG->prefix}course", "course"],
                    ['on', ['and', "course.id=var_blockcourse.courseid"]]
                ],
                ['leftJoin',
                    ['as', "{$CFG->prefix}var_userblockcourse", "var_userblockcourse"],
                    ['on',
                        ['and',
                            "var_block.id=var_userblockcourse.varblockid",
                            "var_blockcourse.courseid=var_userblockcourse.courseid",
                            "cohort_members.userid=var_userblockcourse.userid"
                        ]
                    ]
                ],
                ['leftJoin',
                    ['as', "{$CFG->prefix}var_subspecialityenroll", "var_subspecialityenroll"],
                    ['on',
                        ['and',
                            "cohort_members.userid=var_subspecialityenroll.userid",
                            "var_subspecialityenroll.vargroupid=var_group.id",
                            "var_subspecialityenroll.varsubspecialityid=var_blockgroup.varsubspecialityid"
                        //
                        ]
                    ]
                ],
            ],
            ['where',
                ['and',
                    "var_block.id IN(" . join(',', $this->varblockids) . ')',
                    ['or', 'var_blockgroup.varsubspecialityid is null', 'var_blockgroup.varsubspecialityid=0', "var_subspecialityenroll.varsubspecialityid=var_blockgroup.varsubspecialityid"]
                ]
            ],
            ['groupBy', 'var_block.id', 'var_blockcourse.courseid', 'cohort_members.userid'],
            ['having'],
            ['orderBy', 'userid', 'courseid', 'varblockid']
        ];

        $sqlBuilder = new sqlBuilder();
        //var_dump($sqlTree);
        $sql = $sqlBuilder->build($sqlTree);

        $list = array_values($DB->get_records_sql($sql));
        // echo "<pre>"; echo htmlspecialchars($sql); echo "</pre>";
        // var_dump($list);
        // exit('001');
        // 
        // load block properties
        $sql = "SELECT var_block.*,
                 var_form.varformname,
                 var_level.varlevelname
          FROM {$CFG->prefix}var_block AS var_block
               LEFT JOIN {$CFG->prefix}var_form AS  var_form  ON var_block.varformid=var_form.id
               LEFT JOIN {$CFG->prefix}var_level AS var_level ON var_block.varlevelid=var_level.id
          WHERE var_block.id IN(" . join(',', $this->varblockids) . ")";
        //echo $sql;
        //$blocks = array_pop(array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0)));
        $blocks = $DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0);


        $blockIds = array_keys($blocks);
        foreach ($blockIds as $varblockid) {
            $blocks[$varblockid]->courses = array_combine($default_rating_keys[$varblockid], $default_rating_vals[$varblockid]);
        }

        $this->assignment = [
            'users' => [],
            'courses' => [],
            'blocks' => $blocks
        ];

        //echo "<pre>"; echo htmlspecialchars($sql); echo "</pre>";
        // var_dump($blocks);
        //exit('001');
        foreach ($list as $row) {
            $key = "{$row->userid}-{$row->varblockid}";
            if (!isset($this->assignment['users'][$key])) {
                $this->assignment['users'][$key] = [
                    'varblockid' => $row->varblockid,
                    'userid' => $row->userid,
                    'vargroupcode' => $row->vargroupcode,
                    'assignedcourses' => [],
                    'varblockgroupnumcourses' => $row->varblockgroupnumcourses,
                    'rating' => [],
                    'defaultRating' => array_combine($default_rating_keys[$row->varblockid], $this->getRandomRating($default_rating_vals[$row->varblockid]))
                ];
            }
            if ($row->rating) {
                $this->assignment['users'][$key]['rating'][$row->courseid] = $row->rating;
            } else {
                $this->assignment['users'][$key]['rating'][$row->courseid] = $this->assignment['users'][$key]['defaultRating'][$row->courseid];
            }

            if (!isset($this->assignment['courses'][$row->courseid])) {
                $this->assignment['courses'][$row->courseid] = [
                    'courseid' => $row->courseid,
                    'varblockcoursegroup' => [],
                    'course_fullname' => $row->course_fullname,
                    'users' => []
                ];
            }
            $this->assignment['courses'][$row->courseid]['varblockcoursegroup'][$row->varblockid] = $row->varblockcoursegroup;
        }

        //echo "<pre>"; echo htmlspecialchars($sql); echo "</pre>";
        //echo "<pre>"; print_r($this->assignment);echo "</pre>"; 
        //exit('001');

        $keys = array_keys($this->assignment['users']);
        foreach ($keys as $key) {
            asort($this->assignment['users'][$key]['rating']);
        }
        // echo "<pre>"; print_r($this->assignment);echo "</pre>"; 
        // exit('002');
        // var_dump($this->assignment['users']);
        // 
        // 
        // 
        // calculate minimal number of students for each course
        foreach ($default_rating_keys as $varblockid => $courseIds) {

            $minStudents = $this->assignment['blocks'][$varblockid]->varblockminstudents;
            foreach ($courseIds as $courseId) {
                if (!isset($this->assignment['courses'][$courseId]['varblockminstudents']) || $this->assignment['courses'][$courseId]['varblockminstudents'] > $minStudents) {
                    $this->assignment['courses'][$courseId]['varblockminstudents'] = $minStudents;
                }
            }
        }
        // -------------- show ratings - begin ---------------------------------
        // collect statictics
        foreach ($this->assignment['blocks'] as $block) {
            $stats = [];
            foreach ($default_rating_keys[$block->id] as $k) {
                $stats[$k] = 0;
            }
            foreach ($keys as $key) {
                if ($this->assignment['users'][$key]['varblockid'] == $block->id) {
                    foreach ($default_rating_keys[$block->id] as $k) {
                        $stats[$k]+=$this->assignment['users'][$key]['rating'][$k];
                    }
                }
            }
            $summa = 0;
            $cnt = array_keys($stats);
            foreach ($cnt as $k) {
                $summa+=$stats[$k];
            }
            foreach ($cnt as $k) {
                $stats[$k]/=$summa;
                $stats[$k] = round($stats[$k] * 100, 2) . '%';
            }

            echo "<h4>Block {$block->varblockname}</h4>";
            print_r($stats);
        }




        // -------------- show ratings - end -----------------------------------
        // exit('<br><br><br>003');
    }

    private function getRandomRating($defaults) {
        $T = 0.5 * count($defaults); // temperature for annealing
        $cnt = count($defaults);
        $arr = range(1, $cnt);

        do {
            shuffle($arr);
            $diff = 0;
            for ($i = 0; $i < $cnt; $i++) {
                $diff += abs($defaults[$i] - $arr[$i]);
            }
            $rnd = 0.001 * rand(0, 1000);
            $edf = exp(-$diff / $T);
            // echo $edf." ".$rnd.'<br>';
        } while ($rnd > $edf);
        return $arr;
    }

    /**
     * Начальное распределение курсов пользователям
     */
    public function initiate_assignment() {
        $keys = array_keys($this->assignment['users']);
        foreach ($keys as $key) {
            foreach ($this->assignment['users'][$key]['rating'] as $courseid => $rating) {
                if ($this->assignment_allowed($key, $courseid)) {
                    // save course to user+block
                    $this->assignment['users'][$key]['assignedcourses'][$courseid] = $courseid;

                    // save user to course
                    $this->assignment['courses'][$courseid]['users'][$this->assignment['users'][$key]['userid']] = $this->assignment['users'][$key]['userid'];
                }
            }
        }

        // remove courses that have not students
        $courseids = array_keys($this->assignment['courses']);
        foreach ($courseids as $courseid) {
            if (count($this->assignment['courses'][$courseid]['users']) == 0) {
                unset($this->assignment['courses'][$courseid]);
            }
        }

        // echo '<pre>'; print_r($this->assignment); echo '</pre>';  exit();
        // echo '<pre>'; print_r(array_keys($this->assignment['courses'])); echo '</pre>';  exit();
    }

    /**
     * Проверяем, можно ли назначить пользователю $userid курс $courseid
     * v2 - OK
     */
    public function assignment_allowed($user_block, $courseid) {
        // if course exists 
        if (!isset($this->assignment['courses'][$courseid])) {
            // echo " $courseid course does not exists ";
            return false;
        }

        // if quota allows to assign
        if (count($this->assignment['users'][$user_block]['assignedcourses']) >= $this->assignment['users'][$user_block]['varblockgroupnumcourses']) {
            //echo " quota ";
            return false;
        }

        // if course already assigned
        if (isset($this->assignment['users'][$user_block]['assignedcourses'][$courseid])) {
            //echo " already assigned ";
            return false;
        }

        $varblockid = $this->assignment['users'][$user_block]['varblockid'];


        // $varblockcoursegroup must be unique for the user
        $varblockcoursegroup = $this->assignment['courses'][$courseid]['varblockcoursegroup'][$varblockid];
        foreach ($this->assignment['users'][$user_block]['assignedcourses'] as $course) {
            if ($course['varblockcoursegroup'] == $varblockcoursegroup) {
                //echo " varblockcoursegroup ";
                return false;
            }
        }


        // check if user was already enrolled to course
        if (!isset($this->prevAssignments) || !is_array($this->prevAssignments)) {
            $this->loadPrevAssignments();
        }
        $userid = $this->assignment['users'][$user_block]['userid'];
        if(isset($this->prevAssignments[$userid])){
            $userPrevAssignments = $this->prevAssignments[$userid];
            foreach ($userPrevAssignments as $item) {
                if ($varblockcoursegroup == $item['varblockcoursegroup'] || $item['courseid']==$courseid) {
                    return false;
                }
            }
        }

        return true;
    }

    private function loadPrevAssignments() {

        global $DB, $CFG;

        $userids = array_map(function($e) {
            return (int) $e['userid'];
        }, $this->assignment['users']);
        $sql = "SELECT * FROM {$CFG->prefix}var_enroll WHERE userid IN(" . join(',', $userids) . ")";
        $list = $DB->get_records_sql($sql);

        $this->prevAssignments = [];
        foreach ($list as $item) {
            if (!isset($this->prevAssignments[$item->userid])) {
                $this->prevAssignments[$item->userid] = [];
            }
            $this->prevAssignments[$item->userid][] = (array) $item; // varblockid
        }
        //echo '<pre>'; print_r($this->prevAssignments); echo '</pre>';  exit();
    }

    /**
     * Сохранить распределение в очередь
     */
    public function toqueue($assignment) {
        global $DB, $CFG;

        $info = new stdClass();
        sort($this->varblockids);
        $info->varblockid = join(',', $this->varblockids);
        $info->varassignmentqueuestatus = 'undefined';
        $info->varassignmentqueuedata = json_encode($assignment);
        $info->varassignmentqueueobjectivefunction = null;
        //echo '<pre>'; print_r($info); echo '</pre>';  exit();
        //exit();
        $DB->insert_record('var_assignmentqueue', $info, $returnid = false);
    }

    /**
     * Сосчитать количество студентов в курсах
     */
    public function count_students() {
        $counter = [];
        foreach ($this->assignment['courses'] as $courseid => $courseInfo) {
            $counter[$courseid] = count($courseInfo['users']);
        }
        return $counter;
    }

    /**
     * Взять одно необработанное распределение из очереди
     */
    public function load_next_from_queue($varblockids) {
        global $DB, $CFG;
        $sql = "SELECT *
              FROM {$CFG->prefix}var_assignmentqueue AS var_assignmentqueue
              WHERE varassignmentqueuestatus='undefined'
                 AND varblockid='" . join(',', $varblockids) . "' 
              ORDER BY varblockid";
        //echo $sql;
        $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 5));
        shuffle($list);
        // print_r($list);

        if (isset($list[0])) {
            $this->assignmentid = $list[0]->id;
            $this->assignment = json_decode($list[0]->varassignmentqueuedata, true);
            $this->varblockids = $varblockids;
            sort($this->varblockids);
            return true;
        } else {
            $this->assignmentid = 0;
            $this->assignment = false;
            $this->varblockid = 0;
            return false;
        }
    }

    /**
     * Взять одно необработанное распределение из очереди
     */
    public function count_queue($varblockids) {
        global $DB, $CFG;
        $sql = "SELECT SUM(varassignmentqueuestatus='undefined') as waiting, count(*) as total
              FROM {$CFG->prefix}var_assignmentqueue
              WHERE varblockid='" . join(',', $varblockids) . "' 
        ";
        // echo "$sql";
        $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 1));
        // print_r(Array('waiting'=> $list[0]->waiting, 'total'=> $list[0]->total )); exit('rrr');
        return Array('waiting' => $list[0]->waiting, 'total' => $list[0]->total);
    }

    public function processassignment() {

        global $DB, $CFG;

        //echo '<pre>'; print_r($this->assignment); echo '</pre>';  exit();

        if ($this->assignment) {
            $number_of_students = $this->count_students();
            //echo '<pre>'; print_r($number_of_students);  echo '</pre>'; // exit('<hr>003');
            //echo '<pre>'; print_r($this->assignment); echo '</pre>';  exit();
            //$varblockminstudents = $this->assignment['block']['varblockminstudents'];
            // get courses which have not enough students
            $courseids = array_keys($number_of_students);
            foreach ($courseids as $courseid) {
                if ($number_of_students[$courseid] >= $this->assignment['courses'][$courseid]['varblockminstudents']) {
                    unset($number_of_students[$courseid]);
                }
            }
            echo '<pre> courses to exclude ';  print_r(count($number_of_students)); echo '</pre>'; //exit('<hr>003');
            echo '<pre>';  print_r($number_of_students); echo '</pre>'; //exit('<hr>003');
            if (count($number_of_students) == 0) {
                echo '<pre> all courses have enough students </pre>';
                // calculate objective function
                // and save it to database
                $info = new stdClass();
                $info->id = $this->assignmentid;
                $info->varassignmentqueuestatus = 'variant';
                $info->varassignmentqueueobjectivefunction = $this->objectivefunction();
                $DB->update_record('var_assignmentqueue', $info);
            } else {
                //echo '<pre>'; print_r($number_of_students); echo '</pre>';  exit();
                echo '<pre>some courses have not enough students, try exclude them</pre>';
                $n_enqueued = 0;
                $courseids = array_keys($number_of_students);
                foreach ($courseids as $courseid) {

                    $newassignment = new variatives_assignment();
                    $newassignment->assignment = unserialize(serialize($this->assignment));


                    $studentsToMove = $newassignment->assignment['courses'][$courseid]['users'];
                    $courseInfo = $newassignment->assignment['courses'][$courseid];
                    unset($newassignment->assignment['courses'][$courseid]);

                    echo "<pre> Excluding course <br>";
                    print_r($courseInfo);
                    echo '</pre>';
                    // echo "<pre>"; print_r($newassignment->assignment['courses']); echo '</pre>';  exit();
                    //exit();
                    // echo '<pre>'; print_r($newassignment); echo '</pre>';  exit();
                    // move students to other courses
                    $all_students_moved = true;
                    $keys = array_keys($newassignment->assignment['users']);
                    foreach ($keys as $key) {

                        // move only students that were assigned to the course
                        if (!isset($studentsToMove[$newassignment->assignment['users'][$key]['userid']])) {
                            continue;
                        }
                        if (!isset($newassignment->assignment['users'][$key]['assignedcourses'][$courseid])) {
                            continue;
                        }
                        unset($newassignment->assignment['users'][$key]['assignedcourses'][$courseid]);
                        // unset($newassignment->assignment['users'][$key]['rating'][$courseid]);


                        $course_found = false;
                        foreach ($newassignment->assignment['users'][$key]['rating'] as $newcourseid => $rating) {
                            // echo "assignment_allowed($key, $newcourseid);<br>";
                            if ($newassignment->assignment_allowed($key, $newcourseid)) {
                                $newassignment->assignment['users'][$key]['assignedcourses'][$newcourseid] = $newcourseid;
                                $newassignment->assignment['courses'][$newcourseid]['users'][$newassignment->assignment['users'][$key]['userid']] = $newassignment->assignment['users'][$key]['userid'];
                                $course_found = true;
                                break;
                            }
                        }
                        if (!$course_found) {
                            $all_students_moved = false;
                            break;
                        }
                    }
                    if ($all_students_moved) {
                        //echo '<pre>'; print_r($newassignment); echo '</pre>';//  exit();
                        echo '<pre>';
                        print_r(join(',', array_keys($newassignment->assignment['courses'])));
                        echo '</pre>'; //  exit();

                        $newassignment->prevAssignments=false;
                        $this->toqueue($newassignment->assignment);
                    }
                }
                // exit('$$$$$$$');
                // mark current assignment as rejected
                // $info = new stdClass();
                // $info->id = $this->assignmentid;
                // $info->varassignmentqueuestatus = 'rejected';
                // $DB->update_record('var_assignmentqueue', $info);
                // echo '<pre>'; print_r($info); echo '</pre>';  exit();
                // or delete current assignment
                $DB->delete_records('var_assignmentqueue', array('id' => $this->assignmentid));
                // echo '<pre>'; print_r($newassignment); echo '</pre>';  exit();
                return true;
            }
        } else {
            // assignment with 'unknown' status not found
            return false;
        }
    }

    public function objectivefunction() {
        $summa = 0;
        $keys = array_keys($this->assignment['users']);
        foreach ($keys as $key) {
            foreach ($this->assignment['users'][$key]['assignedcourses'] as $courseid) {
                //if (isset($this->assignment['courses'][$courseid])) {
                $summa+=$this->assignment['users'][$key]['rating'][$courseid];
                //}
            }
        }

        return $summa;
    }

    /**
     * Взять одно необработанное распределение из очереди
     */
    public function load_best_from_queue($varblockids) {
        global $DB, $CFG;
        $sql = "SELECT *
              FROM {$CFG->prefix}var_assignmentqueue AS var_assignmentqueue
              WHERE varassignmentqueuestatus='variant'
                 AND varblockid='" . join(',', $varblockids) . "' 
              ORDER BY varassignmentqueueobjectivefunction ASC";
        // echo $sql;
        $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 1));
        // print_r($list);

        if (isset($list[0])) {
            $this->assignmentid = $list[0]->id;
            $this->assignment = json_decode($list[0]->varassignmentqueuedata, true);
            $this->varblockids = $varblockids;
            return true;
        } else {
            $this->assignmentid = 0;
            $this->assignment = false;
            $this->varblockids = 0;
            return false;
        }
    }

}

function variatives_get_courses($_courseIds) {
    global $DB, $CFG;
    $courseIds = array_map(function($a) {
        return (int) $a;
    }, $_courseIds);
    $courseIds[] = 0;
    $sql = "SELECT id,fullname,shortname
          FROM {$CFG->prefix}course AS course
          WHERE id IN (" . join(',', $courseIds) . ")";
    //echo $sql;
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    return $list;
}

function variatives_get_users($_userids) {
    global $DB, $CFG;
    $userids = array_map(function($a) {
        return (int) $a;
    }, $_userids);
    $userids[] = 0;
    $sql = "SELECT id,firstname,lastname
          FROM {$CFG->prefix}user AS user
          WHERE id IN (" . join(',', $userids) . ")";
    //echo $sql;
    $list = $DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0);
    return $list;
}

// subspecialities

function variatives_subspeciality_list($varspecialityid) {
    global $DB;
    $list = $DB->get_records_select(
            'var_subspeciality', ' varspecialityid=? ', //$where = 
            Array((int) $varspecialityid), //$params = 
            $sort = 'varsubspecialitytitle ASC' //
    );
    return $list;
}

function variatives_subspeciality_get($id) {
    global $DB;
    $info = $DB->get_record('var_subspeciality', Array('id' => $id));
    return $info;
}

function variatives_subspeciality_object($values) {
    // global $DB;
    // var_dump($values);

    $info = new stdClass();

    if (isset($values['varspecialityid'])) {
        $info->varspecialityid = (int) $values['varspecialityid'];
    }

    if (isset($values['varsubspecialitytitle'])) {
        $info->varsubspecialitytitle = strip_tags($values['varsubspecialitytitle']);
    }
    if (isset($values['varsubspecialityurl'])) {
        $info->varsubspecialityurl = strip_tags($values['varsubspecialityurl']);
    }

    //var_dump($info);exit();
    return $info;
}

function variatives_subspeciality_update($id, $values) {
    //echo "variatives_subspeciality_update($id, $values)<br>";exit();
    global $DB;
    $info = variatives_subspeciality_object($values);
    $info->id = (int) $id;
    if (count((array) $info) > 0) {
        $DB->update_record('var_subspeciality', $info);
    }
    var_dump($info);
    exit();
    return variatives_subspeciality_get($id);
}

function variatives_subspeciality_create($values) {
    global $DB;

    $info = variatives_subspeciality_object($values);

    if (count((array) $info) > 0) {
        $id = $DB->insert_record('var_subspeciality', $info, $returnid = true);
    }
    return variatives_subspeciality_get($id);
}

function variatives_subspeciality_delete($id) {
    global $DB;
    $subspeciality = variatives_subspeciality_get($id);
    if ($subspeciality) {
        $DB->delete_records('var_subspeciality', array('id' => $id));
    }
    return $subspeciality;
}

function variatives_subspeciality_options() {
    global $DB, $CFG;
    $sql = "SELECT subspeciality.id, subspeciality.varsubspecialitytitle,  subspeciality.varsubspecialityurl
            FROM {$CFG->prefix}var_subspeciality subspeciality
            ORDER BY subspeciality.varsubspecialitytitle ASC;";
    $list = $DB->get_records_sql($sql);
    return $list;
}

function variatives_subspecialityblock_get($id) {
    global $DB, $CFG;
    $sql = "SELECT var_subspecialityblock.*,
                 var_form.varformname,
                 var_level.varlevelname,
                 var_department.vardepartmentname,
                 var_speciality.varspecialityname
          FROM {$CFG->prefix}var_subspecialityblock AS var_subspecialityblock
                LEFT JOIN {$CFG->prefix}var_form AS  var_form  ON var_subspecialityblock.varformid=var_form.id
                LEFT JOIN {$CFG->prefix}var_level AS var_level ON var_subspecialityblock.varlevelid=var_level.id
                LEFT JOIN {$CFG->prefix}var_department AS var_department ON var_subspecialityblock.vardepartmentid=var_department.id
                LEFT JOIN {$CFG->prefix}var_speciality AS var_speciality ON var_subspecialityblock.vardepartmentid=var_speciality.id
          WHERE var_subspecialityblock.id=" . ( (int) $id );

    //echo $sql;
    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    return isset($list[0]) ? $list[0] : false;
}

function variatives_subspecialityblock_list($filter = []) {
    global $DB, $CFG;

    $sql = "SELECT var_subspecialityblock.*,
                 var_form.varformname,
                 var_level.varlevelname,
                 var_department.vardepartmentname,
                 var_speciality.varspecialityname
          FROM {$CFG->prefix}var_subspecialityblock AS var_subspecialityblock
                LEFT JOIN {$CFG->prefix}var_form AS  var_form  ON var_subspecialityblock.varformid=var_form.id
                LEFT JOIN {$CFG->prefix}var_level AS var_level ON var_subspecialityblock.varlevelid=var_level.id
                LEFT JOIN {$CFG->prefix}var_department AS var_department ON var_subspecialityblock.vardepartmentid=var_department.id
                LEFT JOIN {$CFG->prefix}var_speciality AS var_speciality ON var_subspecialityblock.varspecialityid=var_speciality.id
    ";

    $where = Array();
    $param = Array();

    if (strlen($filter['keyword']) > 0) {
        $where[] = " locate(:keyword1, var_subspecialityblock.varsubspecialityblockname)>0 ";
        $param['keyword1'] = $filter['keyword'];
    }
    if ($filter['varlevelid'] > 0) {
        $where[] = " var_subspecialityblock.varspecialityid=:varlevelid ";
        $param['varlevelid'] = $filter['varlevelid'];
    }
    if ($filter['varformid'] > 0) {
        $where[] = " var_subspecialityblock.varformid=:varformid ";
        $param['varformid'] = $filter['varformid'];
    }
    if ($filter['vardepartmentid'] > 0) {
        $where[] = " var_subspecialityblock.vardepartmentid=:vardepartmentid ";
        $param['vardepartmentid'] = $filter['vardepartmentid'];
    }
    if ($filter['varspecialityid'] > 0) {
        $where[] = " var_subspecialityblock.varspecialityid=:varspecialityid ";
        $param['varspecialityid'] = $filter['varspecialityid'];
    }

    if (count($where) > 0) {
        $sql.=' WHERE  ' . join(' AND ', $where);
    }
    $list = array_values($DB->get_records_sql($sql, $param, $limitfrom = 0, $limitnum = 100));
    return $list;
}

function variatives_subspecialityblock_object($values) {
    $info = new stdClass();

    if (isset($values['varsubspecialityblockname'])) {
        $info->varsubspecialityblockname = strip_tags($values['varsubspecialityblockname']);
    }
    if (isset($values['vargroupyear'])) {
        $info->vargroupyear = (int) $values['vargroupyear'];
    }
    if (isset($values['varformid'])) {
        $info->varformid = (int) $values['varformid'];
    }
    if (isset($values['varlevelid'])) {
        $info->varlevelid = (int) $values['varlevelid'];
    }
    if (isset($values['varspecialityid'])) {
        $info->varspecialityid = (int) $values['varspecialityid'];
    }
    if (isset($values['vardepartmentid'])) {
        $info->vardepartmentid = (int) $values['vardepartmentid'];
    }
    if (isset($values['varsubspecialityblockisarchive'])) {
        if (in_array(strtolower($values['varsubspecialityblockisarchive']), Array('true', 'yes', '1', 'on'))) {
            $info->varsubspecialityblockisarchive = 1;
        } else {
            $info->varsubspecialityblockisarchive = 0;
        }
    }

    if (isset($values['varsubspecialityblockminstud'])) {
        $info->varsubspecialityblockminstud = (int) $values['varsubspecialityblockminstud'];
    }
    if (isset($values['varsubspecialityblockmaxstud'])) {
        $info->varsubspecialityblockmaxstud = (int) $values['varsubspecialityblockmaxstud'];
    }

    if (isset($values['varsubspecialityblocktimemin'])) {
        $info->varsubspecialityblocktimemin = strtotime($values['varsubspecialityblocktimemin']);
        if ($info->varsubspecialityblocktimemin === false) {
            $info->varsubspecialityblocktimemin = time();
        }
    }

    if (isset($values['varsubspecialityblocktimemax'])) {
        $info->varsubspecialityblocktimemax = strtotime($values['varsubspecialityblocktimemax']);
        if ($info->varsubspecialityblocktimemax === false) {
            $info->varsubspecialityblocktimemax = time() + 90 * 24 * 3600;
        }
    }

    return $info;
}

function variatives_subspecialityblock_update($id, $values) {
    global $DB;

    $info = variatives_subspecialityblock_object($values);
    $info->id = (int) $id;

    // var_dump($info);exit();
    if (count((array) $info) > 0) {
        $DB->update_record('var_subspecialityblock', $info);
    }
    return variatives_subspecialityblock_get($id);
}

function variatives_subspecialityblock_create($values) {
    global $DB;

    $info = variatives_subspecialityblock_object($values);
    //var_dump($info);exit();

    if (count((array) $info) > 0) {
        $id = $DB->insert_record('var_subspecialityblock', $info, $returnid = true);
        return variatives_subspecialityblock_get($id);
    }
    return null;
}

function variatives_subspecialityblock_delete($id) {
    global $DB;
    $info = variatives_subspecialityblock_get($id);
    if ($info) {
        //        $DB->delete_records('var_blockgroup', array('varblockid'=>$id));
        //        $DB->delete_records('var_blockcourse', array('varblockid'=>$id));
        //        $DB->delete_records('var_userblockcourse', array('varblockid'=>$id));
        //        $DB->delete_records('var_enroll', array('varblockid'=>$id));
        //        $DB->delete_records('var_assignmentqueue', array('varblockid'=>$id));

        $DB->delete_records('var_subspecialityblock', array('id' => $id));
    }
    return $info;
}

function variatives_subspeciality_report_waiting($varsubspecialityblockid) {
    global $DB, $CFG;
    $sql = "
        SELECT concat(`u`.id,'-',var_group.id) k,
               u.id userid, u.lastname, u.firstname, u.username,
               var_group.id  vargroupid, var_group.cohortid, 
               var_group.vargroupcode,  var_group.vardepartmentid, 
               var_group.vargroupyear,     var_group.varspecialityid, 
               var_group.varformid,    var_group.varlevelid, 
               var_department.vardepartmentname, var_form.varformname,
               var_level.varlevelname, var_speciality.varspecialityname,
               var_usersubspecialityrating.id
        FROM {$CFG->prefix}var_group var_group
             INNER JOIN {$CFG->prefix}var_subspecialityblock var_subspecialityblock
             ON (
                  ( var_group.vardepartmentid=var_subspecialityblock.vardepartmentid OR IFNULL(var_subspecialityblock.vardepartmentid,0)=0)
              AND ( var_group.varformid=var_subspecialityblock.varformid OR IFNULL(var_subspecialityblock.varformid,0)=0)
              AND ( var_group.varlevelid=var_subspecialityblock.varlevelid OR IFNULL(var_subspecialityblock.varlevelid,0)=0)
              AND ( var_group.varspecialityid=var_subspecialityblock.varspecialityid OR IFNULL(var_subspecialityblock.varspecialityid,0)=0)
              AND ( var_group.vargroupyear=var_subspecialityblock.vargroupyear OR IFNULL(var_subspecialityblock.vargroupyear,0)=0)
             )
             INNER JOIN {$CFG->prefix}var_department var_department ON (var_group.vardepartmentid=var_department.id)
             INNER JOIN {$CFG->prefix}var_form var_form ON (var_group.varformid=var_form.id)
             INNER JOIN {$CFG->prefix}var_level var_level ON (var_group.varlevelid=var_level.id)
             INNER JOIN {$CFG->prefix}var_speciality var_speciality ON (var_group.varspecialityid=var_speciality.id)
             INNER JOIN {$CFG->prefix}cohort_members cohort_members ON (var_group.cohortid  = cohort_members.cohortid)
             INNER JOIN {$CFG->prefix}user u ON (u.id  = cohort_members.userid)
             LEFT JOIN  {$CFG->prefix}var_usersubspecialityrating var_usersubspecialityrating ON (
                     var_usersubspecialityrating.vargroupid=var_group.id
                 AND var_usersubspecialityrating.userid=u.id
             )
        WHERE var_subspecialityblock.id=" . ( (int) $varsubspecialityblockid ) . " AND var_usersubspecialityrating.id IS NULL
        GROUP BY userid, var_group.id
        ORDER BY var_department.vardepartmentname, var_form.varformname, var_level.varlevelname , var_speciality.varspecialityname, var_group.vargroupcode, u.lastname
    ";
    // echo $sql;
    $list = $DB->get_records_sql($sql);
    //print_r($list);
    return $list;
}

function variatives_subspeciality_enroll_list($filter) {
    global $DB, $CFG;
    // Вибираємо призначені студентам курси

    $where = Array();
    $param = Array();

    if (isset($filter['varsubspecialityblockid']) && $filter['varsubspecialityblockid'] > 0) {
        $where[] = " var_subspecialityblock.id=:varsubspecialityblockid ";
        $param['varsubspecialityblockid'] = $filter['varsubspecialityblockid'];
    }
    if (isset($filter['username']) && strlen($filter['username']) > 0) {
        $where[] = " ( locate(:username1, `u`.firstname)>0 OR locate(:username2, `u`.lastname)>0 )";
        $param['username1'] = $filter['username'];
        $param['username2'] = $filter['username'];
    }
    if (isset($filter['varblockname']) && strlen($filter['varblockname']) > 0) {
        $where[] = " locate(:varblockname, var_subspecialityblock.varsubspecialityblockname)>0 ";
        $param['varblockname'] = $filter['varblockname'];
    }
    if (isset($filter['vargroupcode']) && strlen($filter['vargroupcode']) > 0) {
        $where[] = " locate(:vargroupcode, var_group.vargroupcode)>0 ";
        $param['vargroupcode'] = $filter['vargroupcode'];
    }
    if (isset($filter['vargroupyear']) && $filter['vargroupyear'] > 0) {
        $where[] = " var_group.vargroupyear=:vargroupyear ";
        $param['vargroupyear'] = $filter['vargroupyear'];
    }
    if (isset($filter['vardepartmentid']) && $filter['vardepartmentid'] > 0) {
        $where[] = " var_group.vardepartmentid = :vardepartmentid ";
        $param['vardepartmentid'] = $filter['vardepartmentid'];
    }
    if (isset($filter['varformid']) && $filter['varformid'] > 0) {
        $where[] = " var_group.varformid = :varformid ";
        $param['varformid'] = $filter['varformid'];
    }
    if (isset($filter['varlevelid']) && $filter['varlevelid'] > 0) {
        $where[] = " var_group.varlevelid = :varlevelid ";
        $param['varlevelid'] = $filter['varlevelid'];
    }
    if (isset($filter['varspecialityid']) && $filter['varspecialityid'] > 0) {
        $where[] = " var_group.varspecialityid = :varspecialityid ";
        $param['varspecialityid'] = $filter['varspecialityid'];
    }
    if (isset($filter['varsubspecialitytitle']) && strlen($filter['varsubspecialitytitle']) > 0) {
        $where[] = " locate(:varsubspecialitytitle, var_subspeciality.varsubspecialitytitle)>0 ";
        $param['varsubspecialitytitle'] = $filter['varsubspecialitytitle'];
    }
    if (count($where) > 0) {
        $where_sql = " WHERE " . join(' AND ', $where);
    } else {
        $where_sql = '';
    }
    if (isset($filter['orderby']) && $filter['orderby'] == 'group') {
        $orderby = "ORDER BY var_department.vardepartmentname, var_form.varformname, "
                . " var_level.varlevelname , var_speciality.varspecialityname, "
                . " var_group.vargroupcode, userfullname";
    } elseif (isset($filter['orderby']) && $filter['orderby'] == 'user') {
        $orderby = "ORDER BY userfullname, var_subspeciality.varsubspecialitytitle ";
    } else {
        $orderby = "ORDER BY var_subspeciality.varsubspecialitytitle, userfullname";
    }

    $sql = "
        SELECT var_subspecialityenroll.id, 
               u.id userid, u.lastname, u.firstname, 
               concat_ws(' ',u.lastname, u.firstname) userfullname, u.username,
               var_group.id  vargroupid, var_group.cohortid, 
               var_group.vargroupcode,  var_group.vardepartmentid, 
               var_group.vargroupyear,     var_group.varspecialityid, 
               var_group.varformid,    var_group.varlevelid, 
               var_department.vardepartmentname, var_form.varformname,
               var_level.varlevelname, var_speciality.varspecialityname,
               var_subspeciality.varsubspecialitytitle,
               var_subspecialityblock.id var_subspecialityblockid
        FROM {$CFG->prefix}var_group var_group
             INNER JOIN {$CFG->prefix}var_subspecialityblock var_subspecialityblock
             ON (
                  ( var_group.vardepartmentid=var_subspecialityblock.vardepartmentid OR IFNULL(var_subspecialityblock.vardepartmentid,0)=0)
              AND ( var_group.varformid=var_subspecialityblock.varformid OR IFNULL(var_subspecialityblock.varformid,0)=0)
              AND ( var_group.varlevelid=var_subspecialityblock.varlevelid OR IFNULL(var_subspecialityblock.varlevelid,0)=0)
              AND ( var_group.varspecialityid=var_subspecialityblock.varspecialityid OR IFNULL(var_subspecialityblock.varspecialityid,0)=0)
              AND ( var_group.vargroupyear=var_subspecialityblock.vargroupyear OR IFNULL(var_subspecialityblock.vargroupyear,0)=0)
             )
             INNER JOIN {$CFG->prefix}var_department var_department ON (var_group.vardepartmentid=var_department.id)
             INNER JOIN {$CFG->prefix}var_form var_form ON (var_group.varformid=var_form.id)
             INNER JOIN {$CFG->prefix}var_level var_level ON (var_group.varlevelid=var_level.id)
             INNER JOIN {$CFG->prefix}var_speciality var_speciality ON (var_group.varspecialityid=var_speciality.id)
             INNER JOIN {$CFG->prefix}cohort_members cohort_members ON (var_group.cohortid  = cohort_members.cohortid)
             INNER JOIN {$CFG->prefix}user u ON (u.id  = cohort_members.userid)
             INNER JOIN {$CFG->prefix}var_subspecialityenroll var_subspecialityenroll ON (
                     var_subspecialityenroll.vargroupid=var_group.id
                 AND var_subspecialityenroll.userid=u.id
             )
        INNER JOIN {$CFG->prefix}var_subspeciality var_subspeciality ON (var_subspeciality.id  = var_subspecialityenroll.varsubspecialityid)

        {$where_sql}  
        {$orderby}
    ";
    // echo $sql;
    // print_r($param);
    //  
    $limitfrom = isset($filter['start']) ? abs((int) $filter['start']) : 0;
    $limitnum = isset($filter['rows_per_page']) ? abs((int) $filter['rows_per_page']) : 100;

    $list = array_values($DB->get_records_sql($sql, $param, $limitfrom, $limitnum));

    $n_records = array_values($DB->get_records_sql("SELECT FOUND_ROWS() as n_records"));
    // print_r($n_records);

    return Array('n_records' => $n_records[0]->n_records, 'filter' => $filter, 'start' => $limitfrom, 'rows_per_page' => $limitnum, 'rows' => $list);
}

/**
 * Список курсов, которые предлагаются студенту
 */
function variatives_user_subspeciality_suggestion($userid) {
    global $DB, $CFG;
    /**
     * Вибираємо запропоновані студентам курси
     * використовуємо у формі призначення курсу для одного студента
     */
    $sql = "
        SELECT var_subspecialityblock.id varsubspecialityblockid ,var_group.id  vargroupid, var_subspecialityblock.*, var_group.cohortid, 
               var_group.vargroupcode,  var_group.vardepartmentid, 
               var_group.vargroupyear,     var_group.varspecialityid, 
               var_group.varformid,    var_group.varlevelid, 
               var_department.vardepartmentname, var_form.varformname,
               var_level.varlevelname, var_speciality.varspecialityname
        FROM {$CFG->prefix}var_group var_group
             INNER JOIN {$CFG->prefix}var_subspecialityblock var_subspecialityblock
             ON (
                  ( var_group.vardepartmentid=var_subspecialityblock.vardepartmentid OR IFNULL(var_subspecialityblock.vardepartmentid,0)=0)
              AND ( var_group.varformid=var_subspecialityblock.varformid OR IFNULL(var_subspecialityblock.varformid,0)=0)
              AND ( var_group.varlevelid=var_subspecialityblock.varlevelid OR IFNULL(var_subspecialityblock.varlevelid,0)=0)
              AND ( var_group.varspecialityid=var_subspecialityblock.varspecialityid OR IFNULL(var_subspecialityblock.varspecialityid,0)=0)
              AND ( var_group.vargroupyear=var_subspecialityblock.vargroupyear OR IFNULL(var_subspecialityblock.vargroupyear,0)=0)
             )
             INNER JOIN {$CFG->prefix}var_department var_department ON (var_group.vardepartmentid=var_department.id)
             INNER JOIN {$CFG->prefix}var_form var_form ON (var_group.varformid=var_form.id)
             INNER JOIN {$CFG->prefix}var_level var_level ON (var_group.varlevelid=var_level.id)
             INNER JOIN {$CFG->prefix}var_speciality var_speciality ON (var_group.varspecialityid=var_speciality.id)
             
        WHERE var_subspecialityblock.varsubspecialityblocktimemin<=" . time() . " AND " . time() . "<=var_subspecialityblock.varsubspecialityblocktimemax
           AND var_group.id IN(
                SELECT var_group.id
                FROM {$CFG->prefix}var_group AS var_group
                INNER JOIN {$CFG->prefix}cohort_members cohort_members ON (var_group.cohortid  = cohort_members.cohortid)
                WHERE cohort_members.userid=" . ( (int) $userid ) . "
             )
        ORDER BY var_subspecialityblock.varsubspecialityblockname
        ";
    //echo '<pre>'.$sql.'</pre>';exit();

    $list = array_values($DB->get_records_sql($sql));

    //echo '<pre>'; print_r($list); echo '</pre>'; exit();
    // get list of specialities
    $varspecialityid = [];
    $varspecialityid[0] = 0;
    foreach ($list as $li) {
        $varspecialityid[$li->varspecialityid] = (int) $li->varspecialityid;
    }
    //echo '<pre>'; print_r($varspecialityid); echo '</pre>'; exit();
    // get subspecialities
    $sql = "SELECT var_subspeciality.* , 
        var_usersubspecialityrating.usersubspecialityblockrating,
        var_usersubspecialityrating.usersubspecialityblockdatetime,
        var_usersubspecialityrating.userid
          FROM {$CFG->prefix}var_subspeciality as var_subspeciality 
               LEFT JOIN {$CFG->prefix}var_usersubspecialityrating  var_usersubspecialityrating
               ON (
                       var_usersubspecialityrating.varsubspecialityid=var_subspeciality.id
                   AND var_usersubspecialityrating.userid=" . ( (int) $userid ) . "
                  )
          WHERE var_subspeciality.varspecialityid IN (" . join(',', $varspecialityid) . ")
          ORDER BY var_usersubspecialityrating.usersubspecialityblockrating ASC";

    //echo '<pre>';  echo htmlspecialchars($sql); echo '</pre>'; exit();
    $tmp = array_values($DB->get_records_sql($sql));
    //echo '<pre>'; print_r($tmp); echo '</pre>'; exit();
    $subspec = Array();
    foreach ($tmp as $tm) {
        if (!isset($subspec[$tm->varspecialityid])) {
            $subspec[$tm->varspecialityid] = Array();
        }
        $subspec[$tm->varspecialityid][] = $tm;
    }
    // echo '<pre>'; print_r($subspec); echo '</pre>'; exit();


    $block = Array();
    foreach ($list as $li) {
        if (!isset($block[$li->varsubspecialityblockid])) {
            $block[$li->varsubspecialityblockid] = Array(
                'vargroupid' => $li->vargroupid,
                'varsubspecialityblockid' => $li->varsubspecialityblockid,
                'varsubspecialityblockname' => $li->varsubspecialityblockname,
                'varsubspecialityblockminstud' => $li->varsubspecialityblockminstud,
                'varsubspecialityblockmaxstud' => $li->varsubspecialityblockmaxstud,
                'varsubspecialityblocktimemin' => $li->varsubspecialityblocktimemin,
                'varsubspecialityblocktimemax' => $li->varsubspecialityblocktimemax,
                'vargroupyear' => $li->vargroupyear,
                'varformid' => $li->varformid,
                'varlevelid' => $li->varlevelid,
                'vardepartmentid' => $li->vardepartmentid,
                'varspecialityid' => $li->varspecialityid,
                'cohortid' => $li->cohortid,
                'vargroupcode' => $li->vargroupcode,
                'vardepartmentname' => $li->vardepartmentname,
                'varformname' => $li->varformname,
                'varlevelname' => $li->varlevelname,
                'varspecialityname' => $li->varspecialityname,
                'varspeciality' => ( isset($subspec[$li->varspecialityid]) ? $subspec[$li->varspecialityid] : Array() )
            );
        }
    }
    //echo '<pre>'; print_r($block); echo '</pre>'; exit();
    return $block;
}

function updatesubspecialityrating($varsubspecialityblockid, $userid, $varsubspecialityids) {
    global $DB, $CFG;

    //mdl_var_usersubspecialityrating
    $sql = "SELECT var_usersubspecialityrating.*
          FROM {$CFG->prefix}var_usersubspecialityrating  var_usersubspecialityrating
          WHERE var_usersubspecialityrating.varsubspecialityblockid=" . ( (int) $varsubspecialityblockid) . "
              AND var_usersubspecialityrating.userid=" . ( (int) $userid) . "
          ";
    $tmp = array_values($DB->get_records_sql($sql));

    // print_r($tmp); exit();
    // to delete or update
    // if $tmp is not in $varsubspecialityids
    foreach ($tmp as $tm) {
        $contains = false;
        $usersubspecialityblockrating = -1;
        foreach ($varsubspecialityids as $key => $id) {
            if ($tm->varsubspecialityid == $id) {
                $contains = true;
                $usersubspecialityblockrating = $key;
            }
        }

        if ($contains) {
            $record = new stdClass();
            $record->id = $tm->id;
            $record->usersubspecialityblockrating = $usersubspecialityblockrating;
            $DB->update_record('var_usersubspecialityrating', $record);
        } else {
            $DB->delete_records('var_usersubspecialityrating', array('id' => $tm->id));
        }
    }


    // 
    // to add
    foreach ($varsubspecialityids as $key => $id) {
        $contains = false;
        foreach ($tmp as $tm) {
            if ($tm->varsubspecialityid == $id) {
                $contains = true;
            }
        }

        if (!$contains) {
            $record = new stdClass();
            $record->varsubspecialityblockid = $varsubspecialityblockid;
            $record->varsubspecialityid = $id;
            $record->usersubspecialityblockrating = $key;
            $record->usersubspecialityblockdatetime = date('Y-m-d H:i:s');
            $record->userid = $userid;
            $DB->insert_record('var_usersubspecialityrating', $record);
        }
    }
}
