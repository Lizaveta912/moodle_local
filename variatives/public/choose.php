<?php

require_once(dirname(__FILE__) . '/../../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot."/local/variatives/locallib.php");


require_login();

if(! has_capability('local/variatives:choose', context_system::instance()) ){
   print_error('badpermissions'); 
}

$userid=$GLOBALS['USER']->id;
if(has_capability('local/variatives:manage', context_system::instance()) && isset($_REQUEST['userid'])){
   $userid=(int)$_REQUEST['userid'];//8737; /// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! <<< замінити після тестів
}





$PAGE->set_url('/local/variatives/public/choose.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Order_by_preference','local_variatives'));
$PAGE->set_heading( get_string('Order_by_preference','local_variatives'));


$PAGE->requires->js('/local/variatives/public/choose.js');

echo $OUTPUT->header();
// -----------------------------------------------------------------------------
//
$blockcourse=variatives_user_courses($userid);
//echo '<pre>'; print_r($blockcourse); echo '</pre>';


$blocksubspeciality = variatives_user_subspeciality_suggestion($userid);

?>
<style type="text/css">
    .onecourse, .onesubspeciality{
        cursor:move;
        margin-bottom:5px;
    }
    .onecourse:hover, .onesubspeciality:hover{
        background-color:#e0e0e0;
    }
    .var_cnt{
        display:inline-block;
        vertical-align:top;
        width:120px;
    }
    .var_cnt_val{
        width:20px;
        display:inline-block;
        vertical-align:top;
        padding:5px 6px;
        margin-right:5px;
        background-color:#F5F5F5;
    }
    .var_cnt_val.defined{
    }
    .var_cnt_val.undefined{
        background-color:orange;
    }
    .var_varblockcoursegroup{
        display:inline-block;
        vertical-align:top;
        width:200px;
        margin-right:5px;
    }
    .var_course_fullname{
        cursor:pointer;
        display:inline-block;
        vertical-align:top;
        width:400px;
    }
    .var_head{
        font-weight:bold;
        background-color:silver;
    }
    .order_btn{
        padding:0 6px;
    }
    .success{
        background-color:green;
        color:white;
        font-weight:bold;
        padding:3px;
    }
    .warning{
        background-color:orange;
        color:white;
        padding:3px;
    }
</style>
<?php
if(count($blockcourse)>0 || count($blocksubspeciality)>0) {
    
    $PAGE->requires->data_for_js('i18n', Array(
        'Saved'=>get_string('Priorities_saved','local_variatives'),
        'Updated'=>get_string('Priorities_updated','local_variatives')
    ));
    
    
    echo "<div id=feedback></div>";
    $markUndefinedRating="<span class=UndefinedRating></span>";
    
    if(count($blockcourse)>0){
        foreach($blockcourse as $block){
            // var_dump($block);
            echo "
                  <h2>{$block['varblockname']}</h2>
                  <p>".get_string('Deadline','local_variatives')."  <b>".date('d.m.Y',$block['varblocktimestampto'])."</b>, "
                      . str_replace('{n}',( floor( ($block['varblocktimestampto']-time() )/86400) ),get_string('Remaining_days','local_variatives'))
                      .".<br>              "
                      .get_string('choose_varblockminstudents','local_variatives')."  <b>".$block['varblockminstudents']."</b>.<br>
                  ".str_replace('{n}',$block['varblockgroupnumcourses'],get_string('choose_varblockgroupnumcourses','local_variatives'))." 
                  </p>
                   ";
            echo  '<p style="text-align:left;">'.get_string('Order_by_preference_manual','local_variatives').'</p>';


            echo "<div class='var_head'><span class=\"var_cnt\">".get_string('varblockcourserating','local_variatives')."</span>"
                      ."<span class=\"var_course_fullname\">".get_string('varblockcoursetitle','local_variatives')."</span>"
                      ."<span class=\"var_varblockcoursegroup\">".get_string('varblockcoursegroup','local_variatives')."</span>"
                ."</div>";
            echo "<div class='sortablecourses' id='block{$block['varblockid']}' data-userid=\"{$userid}\">";
            $cnt=0;
            foreach($block['var_blockcourse'] as $crs){
                $cnt++;
                echo "<div class='onecourse' data-varblockid=\"{$block['varblockid']}\" data-courseid=\"{$crs->courseid}\">"
                    . "<span class=\"var_cnt\">"
                        . "<span class=\"var_cnt_val ".($crs->varuserblockcourserating?'defined':'undefined')."\">".($crs->varuserblockcourserating?$crs->varuserblockcourserating:'&nbsp;')."</span>"
                        . "<input type=button class=\"order_btn order_btn_down\" value=\"V\" data-varblockid=\"{$block['varblockid']}\">"
                        . "<input type=button class=\"order_btn order_btn_up\" value=\"&Lambda;\" data-varblockid=\"{$block['varblockid']}\">"
                    . "</span>"
                    . "<a  class=\"var_course_fullname\" href=\"../../../course/view.php?id={$crs->courseid}\" target=_blank>{$crs->course_fullname}"
                    . "<div style='font-size:90%; font-style:italic;'>{$crs->tutorname}</div>"
                    . "</a>"
                    . "<span class=\"var_varblockcoursegroup\">{$crs->varblockcoursegroup}</span>"
                    . "</div>";
            }
            echo "</div>";

            echo "<input type=button value=\"".get_string('save_rating','local_variatives')."\" onclick=\"window.postRatingBtn({$block['varblockid']})\">";
        }

    }
    

    // ===================== subspeciality = begin =============================
    if(count($blocksubspeciality)>0){
        foreach($blocksubspeciality as $block){
            // var_dump($block);
            echo "
                  <h2>{$block['varsubspecialityblockname']}</h2>
                  <p>
                  ".get_string('enroll_vargroupcode','local_variatives')." <b>{$block['vargroupcode']}</b>;
                  ".get_string('enroll_vargroupyear','local_variatives')." <b>{$block['vargroupyear']}</b>;
                  ".get_string('enroll_vardepartmentname','local_variatives')." <b>{$block['vardepartmentname']}</b>;
                  ".get_string('enroll_varformname','local_variatives')." <b>{$block['varformname']}</b>;
                  ".get_string('enroll_varlevelname','local_variatives')." <b>{$block['varlevelname']}</b>;<br>
                  ".get_string('enroll_varspecialityname','local_variatives')." <b>{$block['varspecialityname']}</b>;
                  </p>
                  <p> ".get_string('Deadline','local_variatives')."  <b>".date('d.m.Y',$block['varsubspecialityblocktimemax'])."</b>, "
                      . str_replace('{n}',( floor( ($block['varsubspecialityblocktimemax']-time() )/86400) ),get_string('Remaining_days','local_variatives'))
                      .".<br>              "
                      .get_string('choose_varblockminstudents','local_variatives')."  <b>".$block['varsubspecialityblockminstud']."</b>.<br>
                  </p>

                   ";
            echo  '<p style="text-align:left;">'.get_string('Order_by_preference_manual_subspec','local_variatives').'</p>';


            echo "<div class='var_head'><span class=\"var_cnt\">".get_string('varblockcourserating','local_variatives')."</span>"
                      ."<span class=\"var_course_fullname\">".get_string('varsubspecialitytitle','local_variatives')."</span>"
                ."</div>";
            echo "<div class='sortablesubspeciality' id='varsubspecialityblockid{$block['varsubspecialityblockid']}' data-varsubspecialityblockid='{$block['varsubspecialityblockid']}' data-userid=\"{$userid}\">";
            $cnt=0;
            foreach($block['varspeciality'] as $crs){
                $cnt++;
                echo "<div class='onesubspeciality' data-varsubspecialityblockid=\"{$block['varsubspecialityblockid']}\" data-varsubspecialityid=\"{$crs->id}\">"
                    . "<span class=\"var_cnt\">"
                        . "<span class=\"var_cnt_val ".(is_numeric($crs->usersubspecialityblockrating)?'defined':'undefined')."\">".(is_numeric($crs->usersubspecialityblockrating)?$crs->usersubspecialityblockrating:'&nbsp;')."</span>"
                        . "<input type=button class=\"order_btn subspec_btn_down\" value=\"V\" data-varblockid=\"{$block['varblockid']}\">"
                        . "<input type=button class=\"order_btn subspec_btn_up\" value=\"&Lambda;\" data-varblockid=\"{$block['varblockid']}\">"
                    . "</span>"
                    . "<a  class=\"var_course_fullname\" href=\"{$crs->varsubspecialityurl}\" target=_blank>{$crs->varsubspecialitytitle}</a>"
                    . "</div>";
            }
            echo "</div>";

            echo "<input type=button value=\"".get_string('save_rating','local_variatives')."\" onclick=\"window.postSubspecRatingBtn({$block['varsubspecialityblockid']})\">";

        }
        
    }
    // ===================== subspeciality = end ===============================

    
}else{
    echo "<h3>".get_string('You_have_nothing_to_choose','local_variatives')."</h3>";
}
// -----------------------------------------------------------------------------




// echo '<pre>'; print_r($block); echo '</pre>'; exit();

echo $OUTPUT->footer();