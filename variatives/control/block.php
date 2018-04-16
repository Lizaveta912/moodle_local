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


$id       = optional_param('id', 0, PARAM_INT);


$PAGE->set_url('/local/variatives/control/blocks.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Block Properties','local_variatives'));
$PAGE->set_heading( get_string('Block Properties','local_variatives'));


$PAGE->requires->js('/local/variatives/control/block.js');



echo $OUTPUT->header();
// -----------------------------------------------------------------------------

$blockInfo =  (array)variatives_block_get($id);
//print_r($blockInfo);
if(!$blockInfo){
    $blockInfo=Array(
        'id'=>'',
        'varblockname'=>'',
        'varblockminstudents'=>'',
        'varblockmaxstudents'=>'',
        'varblocktimestampfrom'=>time(),
        'varblocktimestampto'=>time()+90*24*3600,
        'vargroupyear'=>date('Y'),
        'varformid'=>0,
        'varlevelid'=>0,
        'varblockisarchive'=>0
    );
    
}


echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.css" />';
echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
echo '<link rel="stylesheet" href="'.$CFG->wwwroot.'/local/variatives/control/control.css">';
echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.js"></script>';

echo '
<style type="text/css">
.half{
   display:inline-block;
   vertical-align:top;
   width:50%;
}

.quart{
   display:inline-block;
   vertical-align:top;
   width:25%;
}
.block_form{
   width:600px;
   max-width:100%;
}

.block_form input[type="text"],
.block_form select{
   width:96%;
   padding-left:2%;
   padding-right:2%;
}

.oneCourseFound{
   margin-bottom:5px;
   border-left:3px solid gray;
   padding-left:10px;
}

.oneCourseFound a{
   text-decoration:underline;
}
.oneCourseFound:hover{
   background-color:silver;
}
.wrning{
   color:black;
   padding:5px;
   background-color:orange;
}
</style>
<!-- <p><a href="blocks.php">&lt;&lt;&nbsp;'.get_string('Blocks','local_variatives').'</a></p> -->
<div class="block_form">
    <input type=hidden name=id class=formel value="'.( isset($blockInfo['id'])?(int)$blockInfo['id']:'' ).'">
    <div>
        <div>'.get_string('varblockname','local_variatives').'</div>
        <input type=text name=varblockname class=formel value="'.  htmlspecialchars(isset($blockInfo['varblockname'])?$blockInfo['varblockname']:'').'">
    </div>
    
    <div><!-- 
 --><span class="half">
        <div>'.get_string('varblockminstudents','local_variatives').'</div>
        <input type=text name=varblockminstudents class=formel value="'.  htmlspecialchars(isset($blockInfo['varblockminstudents'])?$blockInfo['varblockminstudents']:'').'">
    </span><!-- 
 --><span class="half">
        <div>'.get_string('varblockmaxstudents','local_variatives').'</div>
        <input type=text name=varblockmaxstudents class=formel value="'.  htmlspecialchars(isset($blockInfo['varblockmaxstudents'])?$blockInfo['varblockmaxstudents']:'').'">
    </span><!-- 
 --></div>
 
    <div><!-- 
 --><span class="half">
        <div>'.get_string('varblocktimestampfrom','local_variatives').' (YYYY-MM-DD)</div>
        <input type=text name=varblocktimestampfrom class=formel value="'.  htmlspecialchars(date('Y-m-d',(isset($blockInfo['varblocktimestampfrom']) && $blockInfo['varblocktimestampfrom'])?$blockInfo['varblocktimestampfrom']:time())).'">
    </span><!-- 
 --><span class="half">
        <div>'.get_string('varblocktimestampto','local_variatives').' (YYYY-MM-DD)</div>
        <input type=text name=varblocktimestampto class=formel value="'.  htmlspecialchars((isset($blockInfo['varblocktimestampto']) && $blockInfo['varblocktimestampto'])?date('Y-m-d',$blockInfo['varblocktimestampto']):'').'">
    </span><!-- 
 --></div>
 
    <div><!-- 
 --><span class="half">
        <div>'.get_string('vargroupyear_ext','local_variatives').'</div>
        <input type=text name=vargroupyear class=formel value="'.  htmlspecialchars(isset($blockInfo['vargroupyear']) ? $blockInfo['vargroupyear'] : '').'">
    </span><!-- 
 --><span class="quart">
        <div>'.get_string('vargroupformname','local_variatives').'</div>
        <select name=varformid class=formel><option value=""></option>'.
         variatives_draw_options(  isset($blockInfo['varformid'])?$blockInfo['varformid']:'' , variatives_form_options())
        .'</select>
    </span><!-- 
 --><span class="quart">
        <div>'.get_string('vargrouplevelname','local_variatives').'</div>
        <select name=varlevelid class=formel><option value=""></option>'.
         variatives_draw_options(isset($blockInfo['varlevelid']) ? $blockInfo['varlevelid']:'', variatives_level_options())
        .'</select>
    </span><!-- 
 --></div>
    <div>
        <label><input type=checkbox name=varblockisarchive class=formel '
        .(  ( isset($blockInfo['varblockisarchive']) && $blockInfo['varblockisarchive'] ) ?'checked=true':'')
        .'> '.get_string('varblockisarchive','local_variatives').'</label>        
    </div>
    
    <div>
        <input type=button id=saveupdates value="'.get_string('saveupdates','local_variatives').'">
    </div>
</div>
';


// draw container for block groups
echo "<h3 class=grps>".get_string('block_groups','local_variatives')."</h3>";
echo "<div id=blockgroups class=grps></div>";

$headers=Array(
    "",
    // get_string('id','local_variatives'),
    get_string('vargroupdepartmentname','local_variatives'),
    get_string('vargroupspecialityname','local_variatives'),
    get_string('Subspeciality','local_variatives'),
    get_string('varblockgroupnumcourses','local_variatives')
);
$PAGE->requires->data_for_js('blockgroupColHeaders', $headers);

$departmentOptions=variatives_department_options();
$cnt=array_keys($departmentOptions);
foreach($cnt as $key){
    $departmentOptions[$key]=$departmentOptions[$key]->vardepartmentname;
}
$departmentOptions['0']='-';

$PAGE->requires->data_for_js('departmentOptions', $departmentOptions);

$specialityOptions=  variatives_speciality_options();

$cnt=array_keys($specialityOptions);
foreach($cnt as $key){
    $specialityOptions[$key]=$specialityOptions[$key]->varspecialityname;
}
$specialityOptions['0']='-';
$PAGE->requires->data_for_js('specialityOptions', $specialityOptions);






$subspecialityOptions=  variatives_subspeciality_options();

$cnt=array_keys($subspecialityOptions);
foreach($cnt as $key){
    $subspecialityOptions[$key]=$subspecialityOptions[$key]->varsubspecialitytitle;
}
$subspecialityOptions['0']='-';
$PAGE->requires->data_for_js('subspecialityOptions', $subspecialityOptions);









$headers=Array(
    "",
    // get_string('id','local_variatives'),
    get_string('varblockcourserating','local_variatives'),
    get_string('varblockcoursegroup','local_variatives'),
    get_string('varblockcoursetitle','local_variatives')
);
$PAGE->requires->data_for_js('blockcourseColHeaders', $headers);

// draw container for block courses
echo "<h3 class=crss>".get_string('block_courses','local_variatives')."</h3>";
echo "<div id=blockcourses class=crss></div>";

$PAGE->requires->data_for_js('i18n', Array(
    'tooManyCourses'=>get_string('tooManyCourses','local_variatives'),
    'noCoursesFound'=>get_string('noCoursesFound','local_variatives')
));
echo '
<div id="dialog" title="'.  htmlspecialchars(get_string('varblockcourseselect','local_variatives')).'" style="display:none;">
    <input type=hidden name=rowid class=formel value="">
    <input type=hidden name=id class=formel value="">
    <div>
        '.get_string('type_course_name','local_variatives').'
        <input type=text id=course_name_part class=formel value="">
    </div>
    <div id="found_courses" style="height:300px;overflow:scroll;">&nbsp;</div>
</div>
';
// -----------------------------------------------------------------------------

echo $OUTPUT->footer();