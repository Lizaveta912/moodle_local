<?php

$string['pluginname'] = "Variative Courses";
$string['variatives:choose'] = "Choose Courses";
$string['variatives:manage'] = "Manage Variative Courses";


$string['Variatives']="Variative Courses";
$string['Choose variative courses']="Choose variative courses";
$string['Departments']="Manage Departments";
$string['Specialities']="Specialities";
$string['Groups']="Groups";
$string['Enrollments']="Enrollments";
$string['Blocks']="Blocks";
$string['Report Waiting']="Report Waiting Students";
$string['Report Enrolled']="Report Enrolled Students";



$string['id']='#';
$string['vardepartmentvisible']='Visible';
$string['vardepartmentobsolete']='Obsolete';
$string['vardepartmentcode']='Code';
$string['vardepartmentname']='Title';

$string['varformname']='Form';
$string['varlevelname']='Level';
$string['varspecialitycode']='Code';
$string['varspecialityname']='Title';
$string['varspecialityedboid']='EDBO Code';
$string['varspecialityvisible']='Visible';
$string['varspecialityobsolete']='Obsolete';
$string['varspecialitydepartmentname']='Department';

$string['varspeciality_edit']='Edit speciality';

$string['saveupdates']='Save updates';


$string['Groups']='Learning groups';
$string['vargroupcode']='Group code';

$string['vargroupdepartmentname']='Department';
$string['vargroupformname']='Form';
$string['vargrouplevelname']='Level';
$string['vargroupspecialityname']='Speciality';
$string['vargroupedbocode']='EDBO Code';
$string['cohortname']='Cohort';
$string['createGroup']='Add Group';
$string['vargroupyear']='Year';
$string['vargroup_edit']='Edit Group';
$string['vargroupnotes']='Notes';


$string['varblockname']='Block Name';
$string['varblocktimestampfrom']='Visible from';
$string['varblocktimestampto']='Visible to';
$string['varblockisarchive']='Archive';
$string['createBlock']='Create Block';
$string['Block Properties']='Block Properties';
$string['varblockminstudents']='Minimal student number';
$string['varblockmaxstudents']='Maximal student number';
$string['varblocktimestampfrom']='Show from date';
$string['varblocktimestampto']='Show to date';
$string['vargroupyear_ext']='For groups created in year';

$string['block_groups']='Block groups';
$string['varblockgroupnumcourses']='Number of courses';

$string['block_courses']='Block courses';
$string['varblockcourserating']='Priority';
$string['varblockcoursegroup']='Course group';
$string['varblockcoursetitle']='Moodle course';
$string['varblockcourseselect']='Select course';
$string['type_course_name']='Type course title';
$string['tooManyCourses']='Too many courses found. Please refine your query.';
$string['noCoursesFound']='Courses not found. Please change your query.';


$string['enroll_userfullname']="Full name";
$string['enroll_vargroupcode']='Group code';
$string['enroll_vargroupyear']='Group year';
$string['enroll_vardepartmentname']='Department';
$string['enroll_varformname']='Form';
$string['enroll_varlevelname']='Level';
$string['enroll_varspecialityname']='Speciality';
$string['enroll_varblockcoursegroup']='Course';
$string['enroll_course_fullname']='Moodle course';
$string['createEnrollment']='Create Enrollment';
$string['enroll_varformname_min']='Form';
$string['enroll_varblockname']='Block';

$string['enrollment_edit']='Edit enrollment';


$string['tooManyBlocks']='Too many blocks found. Please refine your query';
$string['blocksNotFound']='Blocks not found';
$string['tooManyUsers']='Too many users found. Please refine your query';
$string['usersNotFound']='Users not found';


$string['Report waiting']='Users which have not chosen variatives';

$string['Block']='Block';
$string['Department']='Department';
$string['Group']='Group';

$string['Group_by_course']='Group by course';
$string['Group_by_group']='Group by group';
$string['Report enrolled']='Student course enrollments';



$string['Order_by_preference']='Order courses';
$string['Order_by_preference_manual']='Drag rows with mouse to place the most favorite ones at the top';

$string['You_have_not_courses_to_choose']='You have no courses to choose';
$string['createSpeciality']='Add Speciality';



$string['Auto_assignment_stage_1']='Auto enrollment. Stage 1';



$string['Auto_assignment_stage_1_pre_assign']='Initiating';
$string['Auto_assignment_stage_2_check_variants']='Checking variants';
$string['Auto_assignment_stage_3_finish']='Selecting the best variant';
$string['Solution not found']='Sorry, solution not found';
$string['Deadline']='Deadline is';
$string['Auto_assignment']='Auto enrollment';

$string['Delete block']='Delete block';


$string['enroll_select_course_fullname']='Select course';
$string['enroll_select_userfullname']='Select user';
$string['enroll_select_varblockname']='Select block';


$string['type_block_name']='Type block title';
$string['type_user_name']='Type user name';

$string['Group_by_none']='Plain Table';
$string['choose_varblockminstudents']='Minimal number of students in course:';
$string['choose_varblockgroupnumcourses']='You will be assigned to <b>{n}</b> different courses';


$string['notification_reminder_subject']='Variative courses reminder';
$string['notification_reminder_body']='Dear {username}'
        . '<p>You should before {deadlineDate} open page '
        . '<a href="{chooseURL}">{chooseURL}</a> '
        . 'and set your preferences in variative courses. '
        . 'Otherwise you will be enrolled authomatically using preferences '
        . 'of other students</p>'
        . '<p>With best regards,'
        . 'administrator of {siteName}</p>';
$string['notification_reminder_body_small']='Dear {username}'
        . '<p>You should before {deadlineDate} set your preferences '
        . 'in variative courses.</p>';


$string['assigned_courses_subject']='Variative courses enrollment';
$string['assigned_courses_long']='Dear {username}'
        . 'You are enrolled to courses '
        . '{courses} '
        . 'With best regards,'
        . 'administrator of {siteName}';
$string['assigned_courses_short']='Dear {username}'
        . 'You are enrolled to courses '
        . '{courses} '
        . 'With best regards,'
        . 'administrator of {siteName}';


$string['save_rating']='Save rating';

$string['Remaining_days']="<b>{n} days</b> remaining";

$string['Priorities_saved']="Priorities saved";
$string['Priorities_updated']="Priorities updated but not saved";


$string['Subspecialities']="Sub-specialities";
$string['varsubspeciality_varspecialityid']='Speciality';
$string['varsubspecialitytitle']='Sub-speciality title';
$string['DeletesubSpeciality']='Delete Sub-speciality';
$string['varsubspecialityurl']='Sub-speciality web-page';
$string['Subspeciality']="Subspeciality";


$string['subspecialityblock']='Sub-speciality Suggestion';
$string['subspecialityblock_properties']='Sub-speciality Suggestions';
$string['varsubspecialityblockname']='Suggestion title';

$string['varsubspecialityblockminstud']='min students';
$string['varsubspecialityblockmaxstud']='max students';
$string['varsubspecialityblocktimemin']='time from';
$string['varsubspecialityblocktimemax']='time to';
$string['varsubspecialityblockisarchive']='is archived';

$string['vardepartmentname_ext']='Department';
$string['varspecialityname_ext']='Speciality';
$string['SubspecialityBlocks']='Subspeciality Suggestions';
$string['createSubspecialityBlock']='New Subspeciality Suggestions';

$string['Report subspeciality waiting']='Users which have not chosen subspeciality';

$string['subspeciality_reminder_subject']='Subspeciality reminder';
$string['notification_subspecialityreminder_body']='Dear {username}'
        . '<p>You should before {deadlineDate} open page '
        . '<a href="{chooseURL}">{chooseURL}</a> '
        . 'and set your preferences in subspecilities. '
        . 'Otherwise you will be enrolled authomatically using preferences '
        . 'of other students</p>'
        . '<p>With best regards,'
        . 'administrator of {siteName}</p>';
$string['notification_subspecialityreminder_body_small']='Dear {username}'
        . '<p>You should before {deadlineDate} set your preferences '
        . 'in subspecilities.</p>';
$string['Report subspeciality enrolled']='Report on assigned subspecilities';
$string['Delete subspeciality block']='Delete suggestion';
$string['Auto_subspeciality_assignment']='Auto subspeciality assignment';
$string['Group_by_subspeciality']='Group by subspeciality';

$string['Order_by_preference_manual_subspec']='Drag rows with mouse to place the most favorite ones at the top';
$string['You_have_nothing_to_choose']='You have nothing to choose.';

$string['block_create_copy']='Create copy.';

$string['StartEnrollment']='Start Enrollment in Selected Blocks';

$string['Course_stats']='Course stats';
