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


$PAGE->set_url('/local/variatives/control/block.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('Report enrolled','local_variatives'));
$PAGE->set_heading( get_string('Report enrolled','local_variatives'));


echo $OUTPUT->header();



// echo "<h2>".get_string('Report enrolled','local_variatives')."</h2>";

$varblockids=explode(',',$_REQUEST['varblockid']);
$varblocks=Array();
for($i=0, $cnt=count($varblockids); $i<$cnt; $i++){
    $varblockids[$i]=(int)$varblockids[$i];
    if($varblockids[$i]>0){
        $varblock=variatives_block_get($varblockids[$i]);
        
        $varblocks[]=$varblock;
    }else{
        unset($varblockids[$i]);
    }
}
sort($varblockids);




echo "<p>";

echo "<a href='blocks.php'>&lt;&lt;&nbsp;".get_string('Blocks','local_variatives')."</a> ";
if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=='group'){
    echo '<b>'.get_string('Group_by_group','local_variatives').'</b> &nbsp; '
        ."<a href='reportenrolled.php?varblockid=".join(',',$varblockids)."&orderby=course'>" . get_string('Group_by_course','local_variatives').'</a> &nbsp; '
        ."<a href='reportenrolled.php?varblockid=".join(',',$varblockids)."&orderby=table'>" . get_string('Group_by_none','local_variatives').'</a> &nbsp; ' ;
}elseif(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=='table'){
    echo "<a href='reportenrolled.php?varblockid=".join(',',$varblockids)."&orderby=group'>"  . get_string('Group_by_group','local_variatives').'</a> &nbsp; '
        ."<a href='reportenrolled.php?varblockid=".join(',',$varblockids)."&orderby=course'>" . get_string('Group_by_course','local_variatives').'</a> &nbsp; '
        .'<b>'.get_string('Group_by_none','local_variatives').'</b> &nbsp; ';
}else{
    echo "<a href='reportenrolled.php?varblockid=".join(',',$varblockids)."&orderby=group'>" . get_string('Group_by_group','local_variatives').'</a> &nbsp; '
        .' <b>'.get_string('Group_by_course','local_variatives').'</b> &nbsp; '
        ."<a href='reportenrolled.php?varblockid=".join(',',$varblockids)."&orderby=table'>" . get_string('Group_by_none','local_variatives').'</a> &nbsp; ' ;
}
echo "</p>";

foreach($varblocks as $varblock){
    echo "<div>".get_string('Block','local_variatives').": {$varblock->varblockname}</div>";
}


// echo "<h2>".get_string('Block','local_variatives').": {$varblock->varblockname}</h2>";


if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=='group'){
        
    $list=variatives_enroll_report(Array(
        'varblockid'=>$varblockids,
        'orderby'=>'group',
        'rows_per_page'=>50000
    ));    
    // echo '<pre>'; print_r($list);echo '</pre>';
    $vardepartmentname=''; 
    $varformname=''; 
    $varlevelname='';
    $varspecialityname='';
    $vargroupcode='';
    $prevuserid='';


    foreach($list['rows'] as $row){
        // print_r($row);
        if( $vardepartmentname!=$row->vardepartmentname ){
            $vardepartmentname_h="<h2>".get_string('Department','local_variatives').": {$row->vardepartmentname}</h2>";
            $vardepartmentname=$row->vardepartmentname;
            $varformname=''; 
            $varlevelname='';
            $varspecialityname='';
            $vargroupcode='';
            $prevuserid='';
        }else{
            $vardepartmentname_h="";
        }
        if( $varformname!=$row->varformname ){
            $varformname_h=" <b>{$row->varformname}</b>&nbsp; ";
            $varformname=$row->varformname;
            $varlevelname='';
            $varspecialityname='';
            $vargroupcode='';
            $prevuserid='';
        }else{
            $varformname_h="";
        }
        if( $varlevelname!=$row->varlevelname ){
            $varlevelname_h=" <b>{$row->varlevelname}</b>&nbsp; ";
            $varlevelname=$row->varlevelname;
            $varspecialityname='';
            $vargroupcode='';
            $prevuserid='';
        }else{
            $varlevelname_h="";
        }
        if( $varspecialityname!=$row->varspecialityname ){
            $varspecialityname_h=" <b>{$row->varspecialityname}</b>&nbsp; ";
            $varspecialityname=$row->varspecialityname;
            $vargroupcode='';
            $prevuserid='';
        }else{
            $varspecialityname_h="";
        }
        if( $vargroupcode!=$row->vargroupcode ){
            $vargroupcode_h="<div><b>".get_string('Group','local_variatives')." {$row->vargroupcode}</b></div>";
            $vargroupcode=$row->vargroupcode;
            $prevuserid='';
        }else{
            $vargroupcode_h="";
        }
        if( $prevuserid!=$row->userid ){
            $userlastname_h="<b>{$row->userlastname} {$row->userfirstname}</b>";
            $prevuserid=$row->userid;
        }else{
            $userlastname_h="";
        }

        $header="{$vardepartmentname_h}{$varformname_h}{$varlevelname_h}{$varspecialityname_h}{$vargroupcode_h}";
        if(strlen($header)>0){
            echo "<div>$header</div>";
            $counter=0;
        }
        if(strlen($userlastname_h)>0){
            $counter++;
            echo "<div><span style=\"display:inline-block;width:25px;\">$counter.</span>$userlastname_h</div>";
        }
        echo "<div style=\"padding-left: 25px;\">{$row->varblockcoursegroup} , {$row->course_fullname}</div>";
        
    }


    
}elseif(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=='table'){
    $list=variatives_enroll_report(Array(
        'varblockid'=>$varblockids,
        'orderby'=>'course',
        'rows_per_page'=>50000
    ));
    
    echo "
        <style type=\"text/css\">
           .reporttable td, .reporttable th{
                vertical-align:top;
                border-top:1px solid gray;
                border-left:1px solid gray;
                padding:3pt;
            }
           .reporttable td:last-child, .reporttable th:last-child{
                border-right:1px solid gray;
            }
           .reporttable tr:last-child td{
                border-bottom:1px solid gray;
            }
        </style>
        <table class=\"reporttable\">
            <tr>
              <th>".get_string('varblockcoursegroup','local_variatives')."</th>
              <th>".get_string('enroll_course_fullname','local_variatives')."</th>
              <th>".get_string('enroll_userfullname','local_variatives')."</th>
              <th>".get_string('enroll_vargroupcode','local_variatives')."</th>
              <th>".get_string('enroll_vardepartmentname','local_variatives')."</th>
              <th>".get_string('enroll_varformname','local_variatives')."</th>
              <th>".get_string('enroll_varlevelname','local_variatives')."</th>
              <th>".get_string('enroll_varspecialityname','local_variatives')."</th>
              </tr>
           ";
    
    foreach($list['rows'] as $row){
        echo "<tr>
              <td>{$row->varblockcoursegroup}</td>
              <td>{$row->course_fullname}</td>
              <td>{$row->userlastname} {$row->userfirstname}</td>
              <td>{$row->vargroupcode}</td>
              <td>{$row->vardepartmentname}</td>
              <td>{$row->varformname}</td>
              <td>{$row->varlevelname}</td>
              <td>{$row->varspecialityname}</td>
              </tr>";
    }
    echo "</table>";
    
}else{

    $list=variatives_enroll_report(Array(
        'varblockid'=>$varblockids,
        'orderby'=>'course',
        'rows_per_page'=>50000
    ));
    // print_r($list);

    $varblockcoursegroup=''; 
    $course_fullname='';

    echo "<ol>";
    foreach($list['rows'] as $row){
        // print_r($row);
        if( $varblockcoursegroup!=$row->varblockcoursegroup ){
            $varblockcoursegroup_h="<h3>".get_string('enroll_varblockcoursegroup','local_variatives').": {$row->varblockcoursegroup}</h3>";
            $varblockcoursegroup=$row->varblockcoursegroup;
            $course_fullname='';
        }else{
            $varblockcoursegroup_h="";
        }
        if( $course_fullname!=$row->course_fullname ){
            $course_fullname_h=" <h3>".get_string('enroll_course_fullname','local_variatives').": {$row->course_fullname}</h3>";
            $course_fullname=$row->course_fullname;
        }else{
            $course_fullname_h="";
        }

        $header="{$varblockcoursegroup_h}{$course_fullname_h}";
        if(strlen($header)>0){
            echo "</ol><div>$header</div><ol>";
        }
        echo "<li><b>{$row->userlastname} {$row->userfirstname}</b>"
               . "<br>{$row->vargroupcode} , {$row->vardepartmentname}"
               . "{$row->varformname} , {$row->varlevelname},{$row->varspecialityname}</li>";

    }
    echo "</ol>";
    
}


echo $OUTPUT->footer();