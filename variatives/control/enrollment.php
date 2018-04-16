<?php
require_once(dirname(__FILE__) . '/../../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot."/local/variatives/locallib.php");


require_login();

if(! has_capability('local/variatives:manage', context_system::instance()) ){
   print_error('badpermissions'); 
}


$PAGE->set_url('/local/variatives/control/enrollment.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Enrollments','local_variatives'));
$PAGE->set_heading( get_string('Enrollments','local_variatives'));


$PAGE->requires->js('/local/variatives/control/enrollment.js');

echo $OUTPUT->header();
// -----------------------------------------------------------------------------

// + action addEnrollment
        // -- `user`.id userid, 
        // -- var_group.id vargroupid,
        // -- var_group.vardepartmentid,
        // -- var_group.varformid, 
        // -- var_group.varlevelid, 
        // -- var_group.varspecialityid , 
        // -- var_block.varblockname,
        // -- var_enroll.courseid, 

// 0 action  // edit|delete
// 1 `user`.lastname userlastname, `user`.firstname userfirstname,  userfullname
// 2 var_block.varblockname,
// 2 var_group.vargroupcode, 
// 3 var_group.vargroupyear,
// 4 var_department.vardepartmentname,
// 5 var_form.varformname,
// 6 var_level.varlevelname,
// 7 var_speciality.varspecialityname,
// 8 var_blockcourse.varblockcoursegroup ,
// 9 course.fullname AS course_fullname

$colHeaders=Array(
    "",
    get_string('enroll_userfullname','local_variatives'),       // 1 `user`.lastname userlastname, `user`.firstname userfirstname,  userfullname
    get_string('enroll_varblockname','local_variatives'),       // 1.1 enroll_varblockname,
    get_string('enroll_vargroupcode','local_variatives'),       // 2 var_group.vargroupcode,
    get_string('enroll_vargroupyear','local_variatives'),       // 3 var_group.vargroupyear,
    get_string('enroll_varblockcoursegroup','local_variatives'),// 8 var_blockcourse.varblockcoursegroup ,
    get_string('enroll_course_fullname','local_variatives'),    // 9 course.fullname AS course_fullname
    get_string('enroll_vardepartmentname','local_variatives'),  // 4 var_department.vardepartmentname,
    get_string('enroll_varformname','local_variatives'),        // 5 var_form.varformname,
    get_string('enroll_varlevelname','local_variatives'),       // 6 var_level.varlevelname,
    get_string('enroll_varspecialityname','local_variatives')   // 7 var_speciality.varspecialityname,
);
$PAGE->requires->data_for_js('colHeaders', $colHeaders);


$colWidths=Array(
     70,// action
    250,// 1 `user`.lastname userlastname, `user`.firstname userfirstname,  userfullname
    100,// 1.1 enroll_varblockname,
     50,// 2 var_group.vargroupcode,
     40,// 3 var_group.vargroupyear,
    150,// 8 var_blockcourse.varblockcoursegroup ,
    300,// 9 course.fullname AS course_fullname
    100,// 4 var_department.vardepartmentname,
    100,// 5 var_form.varformname,
    100,// 6 var_level.varlevelname,
    200,// 7 var_speciality.varspecialityname,
);
$PAGE->requires->data_for_js('colWidths', $colWidths);

echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.css" />';
echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
echo '<link rel="stylesheet" href="'.$CFG->wwwroot.'/local/variatives/control/control.css">';
echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.js"></script>';



?>
    <style type="text/css">
        .filtercomp{
            display:inline-block;
            vertical-align:top;
            width:195px;
            margin-right:5px;
        }
        .filtercomp.mini{
            width:95px;
            margin-right:5px;
        }
        .filtercomp .filter{
            width:100%;
        }
        .filtercomp input.filter[type="text"]{
            width:90%;
        }
        
        .entityselector{
            display:inline-block;
            width:100%;
            background-color:#ddddff;
            padding:0 0 0 10px;
            line-height:200%;
            border:1px solid #ddddff;
            border-radius:5px;
        }
        .entityselector:before{
            content: "∇";
            display:inline-block;
            padding:0 10px 0 10px;
            background-color:#bbbbff;
            float:right;
        }
        .entityselector_suggestions{
            padding:5px;
            background-color:#e0e0e0;
            border:1px solid gray;
            margin-bottom:10px;
        }
        .wrning{
            background-color:orange;
            color:white;
            padding:0 10px 0 10px;
        }
        .oneRow{
            margin-bottom:3px;
            border-left:2px solid green;
            padding-left:3px;
        }
        .oneRow:hover{
            background-color:silver;
        }
        .pgnum{
            display:inline-block;
            padding:0 10px;
            margin-right:5px;
            vertical-align:top;
            background-color:#e0e0e0;
        }
        .pgnum:hover{
            background-color:#e0e0ff;
        }
        .pgnum.active{
            background-color:#a0a0ff;
        }
    </style>
<?php
    
echo '<div>'
   . '<a href="javascript:void(createEnroll())">'.get_string('createEnrollment','local_variatives').'</a><br>'

       . '<span class="filtercomp mini"><span>'.get_string('enroll_vargroupcode','local_variatives').'</span>'
       . '<input id=filter_vargroupcode class=filter  type=text></span>'

       . '<span class="filtercomp mini"><span>'.get_string('enroll_vargroupyear','local_variatives').'</span>'
       . '<input id=filter_vargroupyear class=filter type=text></span>'

       . '<span class="filtercomp mini"><span>'.get_string('enroll_varlevelname','local_variatives').'</span>'
       . '<select id=filter_varlevelid class=filter><option value=""></option>'. variatives_draw_options('', variatives_level_options()).'</select></span>'
        
       . '<span class="filtercomp mini"><span>'.get_string('enroll_varformname_min','local_variatives').'</span>'
       . '<select id=filter_varformid class=filter><option value=""></option>'. variatives_draw_options('', variatives_form_options()) .'</select></span>'

       . '<span class=filtercomp><span>'.get_string('enroll_varspecialityname','local_variatives').'</span>'
       . '<select id=filter_varspecialityid class=filter><option value=""></option>'. variatives_draw_options('', variatives_speciality_options()) .'</select></span>'

       . '<span class=filtercomp><span>'.get_string('enroll_vardepartmentname','local_variatives').'</span>'
       . '<select id=filter_vardepartmentid class=filter><option value=""></option>'. variatives_draw_options('', variatives_department_options()) .'</select></span>'
        

       . '<span class="filtercomp"><span>'.get_string('enroll_varblockname','local_variatives').'</span>'
       . '<input id=filter_varblockname class=filter  type=text></span>'

       . '<span class="filtercomp"><span>'.get_string('enroll_userfullname','local_variatives').'</span>'
       . '<input id=filter_username class=filter  type=text></span>'

       . '<span class=filtercomp><span>'.get_string('enroll_course_fullname','local_variatives').'</span>'
       . '<input id=filter_course_fullname class=filter type=text></span>'

       
       . '<span class=filtercomp><span>'.get_string('enroll_varblockcoursegroup','local_variatives').'</span>'
       . '<input id=filter_varblockcoursegroup class=filter type=text></span>'
 
   . '</div><br>';

echo '<div id=blocktablepaging style="margin-bottom:30px;"></div>';
echo '<div id=blocktable style="margin-bottom:30px;"></div>';




$i18n=Array(
    'tooManyBlocks'=>get_string('tooManyBlocks','local_variatives'),
    'blocksNotFound'=>get_string('blocksNotFound','local_variatives'),
    'tooManyUsers'=>get_string('tooManyUsers','local_variatives'),
    'usersNotFound'=>get_string('usersNotFound','local_variatives'),
    
    
);
$PAGE->requires->data_for_js('i18n', $i18n);

echo '
<div id="dialog" title="'.  htmlspecialchars(get_string('enrollment_edit','local_variatives')).'" style="display:none;height:400px;">
    <input type=hidden name=rowid class=formel value="">
    <input type=hidden id=enrollid name=id class=formel value="">

    <div>
        <div>'.get_string('enroll_varblockname','local_variatives').'</div>
        <input type=hidden id=varblockid class=formel value="">
        <a id=varblockname class=entityselector href="javascript:void(enroll_block_selector())" title="'.  htmlspecialchars(get_string('enroll_select_varblockname','local_variatives')).'">...</a>
        <div id=enroll_block_selector style="display:none;" class="enroll_selector">
        <input type=text id=enroll_block_selector_filter style="width:99%;" placeholder="'.  htmlspecialchars(get_string('type_block_name','local_variatives')).'">
        <div id=enroll_block_selector_variants></div>
        </div>
    </div>

    <div>
        <div>'.get_string('enroll_userfullname','local_variatives').'</div>
        <input type=hidden id=userid class=formel value="">
        <a id=username class=entityselector href="javascript:void(enroll_user_selector())" title="'.  htmlspecialchars(get_string('enroll_select_userfullname','local_variatives')).'">...</a>        
        <div id=enroll_user_selector style="display:none;" class="enroll_selector">
        <input type=text id=enroll_user_selector_filter style="width:99%;" placeholder="'.  htmlspecialchars(get_string('type_user_name','local_variatives')).'">
        <div id=enroll_user_selector_variants></div>
        </div>
    </div>

    <div>
        <div>'.get_string('enroll_course_fullname','local_variatives').'</div>
        <input type=hidden name=courseid id=courseid class=formel value="">
        <a id=course_fullname class=entityselector href="javascript:void(enroll_course_selector())" title="'.  htmlspecialchars(get_string('enroll_select_course_fullname','local_variatives')).'">...</a>
        <div id=enroll_course_selector style="display:none;" class="enroll_selector">
        <input type=text id=enroll_course_selector_filter style="width:99%;" placeholder="'.  htmlspecialchars(get_string('type_course_name','local_variatives')).'">
        <div id=enroll_course_selector_variants></div>
        </div>
    </div>
    
    <div>
        <input type=button id=saveupdates value="'.get_string('saveupdates','local_variatives').'">
    </div>
</div>
';


// -----------------------------------------------------------------------------

echo $OUTPUT->footer();