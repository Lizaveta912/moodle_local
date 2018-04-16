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
$PAGE->set_title(get_string('Report subspeciality waiting','local_variatives'));
$PAGE->set_heading( get_string('Report subspeciality waiting','local_variatives'));


echo $OUTPUT->header();


$varsubspecialityblockid=(int)$_REQUEST['varsubspecialityblockid'];
$subspecialityblock=  variatives_subspecialityblock_get($varsubspecialityblockid);
echo "<h2>".get_string('subspecialityblock','local_variatives').": {$subspecialityblock->varsubspecialityblockname}</h2>";




$list=array_values(variatives_subspeciality_report_waiting($varsubspecialityblockid));
// print_r($list);
$vardepartmentname=''; 
$varformname=''; 
$varlevelname='';
$varspecialityname='';
$vargroupcode='';

echo "<ol>";
foreach($list as $row){
    // print_r($row);
    if( $vardepartmentname!=$row->vardepartmentname ){
        $vardepartmentname_h="<h2>".get_string('Department','local_variatives').": {$row->vardepartmentname}</h2>";
        $vardepartmentname=$row->vardepartmentname;
        $varformname=''; 
        $varlevelname='';
        $varspecialityname='';
        $vargroupcode='';
    }else{
        $vardepartmentname_h="";
    }
    if( $varformname!=$row->varformname ){
        $varformname_h=" <b>{$row->varformname}</b>&nbsp; ";
        $varformname=$row->varformname;
        $varlevelname='';
        $varspecialityname='';
        $vargroupcode='';
    }else{
        $varformname_h="";
    }
    if( $varlevelname!=$row->varlevelname ){
        $varlevelname_h=" <b>{$row->varlevelname}</b>&nbsp; ";
        $varlevelname=$row->varlevelname;
        $varspecialityname='';
        $vargroupcode='';
    }else{
        $varlevelname_h="";
    }
    if( $varspecialityname!=$row->varspecialityname ){
        $varspecialityname_h=" <b>{$row->varspecialityname}</b>&nbsp; ";
        $varspecialityname=$row->varspecialityname;
        $vargroupcode='';
    }else{
        $varspecialityname_h="";
    }
    if( $vargroupcode!=$row->vargroupcode ){
        $vargroupcode_h="<div><b>".get_string('Group','local_variatives')." {$row->vargroupcode}</b></div>";
        $vargroupcode=$row->vargroupcode;
    }else{
        $vargroupcode_h="";
    }
    
    
    $header="{$vardepartmentname_h}{$varformname_h}{$varlevelname_h}{$varspecialityname_h}{$vargroupcode_h}";
    if(strlen($header)>0){
        echo "</ol><div>$header</div><ol>";
    }
    echo "<li>{$row->lastname} {$row->firstname}</li>";
    
}
echo "</ol>";

echo $OUTPUT->footer();