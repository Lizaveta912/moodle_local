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


$PAGE->set_url('/local/variatives/control/subspecialityblocks.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('Report subspeciality enrolled','local_variatives'));
$PAGE->set_heading( get_string('Report subspeciality enrolled','local_variatives'));


echo $OUTPUT->header();

$varsubspecialityblockid=(int)$_REQUEST['varsubspecialityblockid'];
$subspecialityblock=  variatives_subspecialityblock_get($varsubspecialityblockid);

echo "<p>";

echo "<a href='subspecialityblocks.php'>&lt;&lt;&nbsp;".get_string('SubspecialityBlocks','local_variatives')."</a> ";
if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=='group'){
    echo '<b>'.get_string('Group_by_group','local_variatives').'</b> '
        ."<a href='subspecialityreportenrolled.php?varsubspecialityblockid={$varsubspecialityblockid}&orderby=subspeciality'>" . get_string('Group_by_subspeciality','local_variatives').'</a> '
        ."<a href='subspecialityreportenrolled.php?varsubspecialityblockid={$varsubspecialityblockid}&orderby=table'>" . get_string('Group_by_none','local_variatives').'</a> ' ;
}elseif(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=='table'){
    echo "<a href='subspecialityreportenrolled.php?varsubspecialityblockid={$varsubspecialityblockid}&orderby=group'>"  . get_string('Group_by_group','local_variatives').'</a> '
        ."<a href='subspecialityreportenrolled.php?varsubspecialityblockid={$varsubspecialityblockid}&orderby=subspeciality'>" . get_string('Group_by_subspeciality','local_variatives').'</a> '
        .'<b>'.get_string('Group_by_none','local_variatives').'</b>';
}else{
    echo "<a href='subspecialityreportenrolled.php?varsubspecialityblockid={$varsubspecialityblockid}&orderby=group'>" . get_string('Group_by_group','local_variatives').'</a>'
        . ' <b>'.get_string('Group_by_subspeciality','local_variatives').'</b>'
        ."<a href='subspecialityreportenrolled.php?varsubspecialityblockid={$varsubspecialityblockid}&orderby=table'>" . get_string('Group_by_none','local_variatives').'</a> ' ;
}
echo "</p>";


echo "<h2>".get_string('subspecialityblock','local_variatives').": {$subspecialityblock->varsubspecialityblockname}</h2>";









if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=='group'){
        
    $list=variatives_subspeciality_enroll_list(Array(
        'varsubspecialityblockid'=>$varsubspecialityblockid,
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
            $userlastname_h="<b>{$row->lastname} {$row->firstname}</b>";
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
        echo "<div style=\"padding-left: 25px;\">{$row->varsubspecialitytitle}</div>";
        
    }


    
}elseif(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=='table'){
    $list=variatives_subspeciality_enroll_list(Array(
        'varsubspecialityblockid'=>$varsubspecialityblockid,
        'orderby'=>'subspeciality',
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
              <th>".get_string('varsubspecialitytitle','local_variatives')."</th>
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
              <td>{$row->varsubspecialitytitle}</td>
              <td>{$row->userfullname}</td>
              <td>{$row->vargroupcode}</td>
              <td>{$row->vardepartmentname}</td>
              <td>{$row->varformname}</td>
              <td>{$row->varlevelname}</td>
              <td>{$row->varspecialityname}</td>
              </tr>";
    }
    echo "</table>";
    
}else{

    $list=variatives_subspeciality_enroll_list(Array(
        'varsubspecialityblockid'=>$varsubspecialityblockid,
        'orderby'=>'subspeciality',
        'rows_per_page'=>50000
    ));
    // print_r($list);

    $varsubspecialitytitle=''; 
    $course_fullname='';

    echo "<ol>";
    foreach($list['rows'] as $row){
        // print_r($row);
        if( $varsubspecialitytitle!=$row->varsubspecialitytitle ){
            $varsubspecialitytitle_h="<h3>{$row->varsubspecialitytitle}</h3>";
            $varsubspecialitytitle=$row->varsubspecialitytitle;
            $course_fullname='';
        }else{
            $varsubspecialitytitle_h="";
        }


        $header="{$varsubspecialitytitle_h}";
        if(strlen($header)>0){
            echo "</ol><div>$header</div><ol>";
        }
        echo "<li><b>{$row->userfullname}</b>"
               . "<br>{$row->vargroupcode} , {$row->vardepartmentname}"
               . "{$row->varformname} , {$row->varlevelname},{$row->varspecialityname}</li>";

    }
    echo "</ol>";
    
}


echo $OUTPUT->footer();