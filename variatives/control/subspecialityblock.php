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


$PAGE->set_url('/local/variatives/control/subspecialityblocks.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('subspecialityblock_properties','local_variatives'));
$PAGE->set_heading( get_string('subspecialityblock_properties','local_variatives'));


$PAGE->requires->js('/local/variatives/control/subspecialityblock.js');



echo $OUTPUT->header();
// -----------------------------------------------------------------------------

$blockInfo =  (array)  variatives_subspecialityblock_get($id);
//print_r($blockInfo);
if(!$blockInfo){
    $blockInfo=Array(
        'id'=>'',
        'varsubspecialityblockname'=>'',
        'varsubspecialityblockminstud'=>'',
        'varsubspecialityblockmaxstud'=>'',
        'varsubspecialityblocktimemin'=>time(),
        'varsubspecialityblocktimemax'=>time()+90*24*3600,
        'vargroupyear'=>date('Y'),
        'varformid'=>0,
        'varlevelid'=>0,
        'varsubspecialityblockisarchive'=>0,
        'vardepartmentid'=>0,
        'varspecialityid'=>0
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
                <FIELD NAME="id"                    TYPE="int"  LENGTH="10"  NOTNULL="true" UNSIGNED="true" SEQUENCE="true"  />
                <FIELD NAME=""          TYPE="char" LENGTH="128" NOTNULL="true"                 SEQUENCE="false" />
                <FIELD NAME=""   TYPE="int"  LENGTH="10"  NOTNULL="true" UNSIGNED="true" SEQUENCE="false" />
                <FIELD NAME=""   TYPE="int"  LENGTH="10"  NOTNULL="true" UNSIGNED="true" SEQUENCE="false" />
                <FIELD NAME="" TYPE="int"  LENGTH="10"  NOTNULL="true"                 SEQUENCE="false" />
                <FIELD NAME=""   TYPE="int"  LENGTH="10"  NOTNULL="true"                 SEQUENCE="false" />
                <FIELD NAME=""          TYPE="int"  LENGTH="10"  NOTNULL="true" UNSIGNED="true" SEQUENCE="false" />
                <FIELD NAME="varformid"             TYPE="int"  LENGTH="10"  NOTNULL="true" UNSIGNED="true" SEQUENCE="false" />
                <FIELD NAME="varlevelid"            TYPE="int"  LENGTH="10"  NOTNULL="true" UNSIGNED="true" SEQUENCE="false"  />
                <FIELD NAME=""     TYPE="int"  LENGTH="4"   NOTNULL="true" UNSIGNED="true" SEQUENCE="false" />
                <FIELD NAME="" TYPE="int" LENGTH="10" NOTNULL="true" SEQUENCE="false"/>
                <FIELD NAME=""         TYPE="int"  LENGTH="10"  NOTNULL="false" UNSIGNED="true" SEQUENCE="false" />
<div class="block_form">
    <input type=hidden name=id class=formel value="'.( (int)$blockInfo['id'] ).'">
    <div>
        <div>'.get_string('varsubspecialityblockname','local_variatives').'</div>
        <input type=text name=varsubspecialityblockname class=formel value="'.  htmlspecialchars($blockInfo['varsubspecialityblockname']).'">
    </div>
    
    <div><!-- 
 --><span class="half">
        <div>'.get_string('varsubspecialityblockminstud','local_variatives').'</div>
        <input type=text name=varsubspecialityblockminstud class=formel value="'.  htmlspecialchars($blockInfo['varsubspecialityblockminstud']).'">
    </span><!-- 
 --><span class="half">
        <div>'.get_string('varsubspecialityblockmaxstud','local_variatives').'</div>
        <input type=text name=varsubspecialityblockmaxstud class=formel value="'.  htmlspecialchars($blockInfo['varsubspecialityblockmaxstud']).'">
    </span><!-- 
 --></div>
 
    <div><!-- 
 --><span class="half">
        <div>'.get_string('varsubspecialityblocktimemin','local_variatives').' (YYYY-MM-DD)</div>
        <input type=text name=varsubspecialityblocktimemin class=formel value="'.  htmlspecialchars(date('Y-m-d',$blockInfo['varsubspecialityblocktimemin']?$blockInfo['varsubspecialityblocktimemin']:time())).'">
    </span><!-- 
 --><span class="half">
        <div>'.get_string('varsubspecialityblocktimemax','local_variatives').' (YYYY-MM-DD)</div>
        <input type=text name=varsubspecialityblocktimemax class=formel value="'.  htmlspecialchars($blockInfo['varsubspecialityblocktimemax']?date('Y-m-d',$blockInfo['varsubspecialityblocktimemax']):'').'">
    </span><!-- 
 --></div>
 
    <div><!-- 
 --><span class="half">
        <div>'.get_string('vargroupyear_ext','local_variatives').'</div>
        <input type=text name=vargroupyear class=formel value="'.  htmlspecialchars($blockInfo['vargroupyear']).'">
    </span><!-- 
 --><span class="quart">
        <div>'.get_string('vargroupformname','local_variatives').'</div>
        <select name=varformid class=formel><option value=""></option>'.
         variatives_draw_options($blockInfo['varformid'], variatives_form_options())
        .'</select>
    </span><!-- 
 --><span class="quart">
        <div>'.get_string('vargrouplevelname','local_variatives').'</div>
        <select name=varlevelid class=formel><option value=""></option>'.
         variatives_draw_options($blockInfo['varlevelid'], variatives_level_options())
        .'</select>
    </span><!-- 
 --></div>
 

    <div><!-- 
 --><span class="half">
        <div>'.get_string('vardepartmentname_ext','local_variatives').'</div>
        <select name=vardepartmentid class=formel><option value=""></option>'.
         variatives_draw_options($blockInfo['vardepartmentid'], variatives_department_options())
        .'</select>
    </span><!-- 
 --><span class="half">
        <div>'.get_string('varspecialityname_ext','local_variatives').'</div>
        <select name=varspecialityid class=formel><option value=""></option>'.
         variatives_draw_options($blockInfo['varspecialityid'], variatives_speciality_options())
        .'</select>
    </span><!-- 
 --></div>
 

    <div>
        <label><input type=checkbox name=varsubspecialityblockisarchive class=formel '
        .($blockInfo['varsubspecialityblockisarchive']?'checked=true':'')
        .'> '.get_string('varsubspecialityblockisarchive','local_variatives').'</label>
        
    </div>
    
    <div>
        <input type=button id=saveupdates value="'.get_string('saveupdates','local_variatives').'">
    </div>
</div>
';


// -----------------------------------------------------------------------------

echo $OUTPUT->footer();