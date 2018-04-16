<?php
 
defined('MOODLE_INTERNAL') || die;
 
if(!isset($securewwwroot)){
    $securewwwroot='';
}

$ADMIN->add('users', new admin_category('accounts', new lang_string('accounts', 'admin')));
$ADMIN->add('accounts', new admin_externalpage('pluginname', new lang_string('cohorts2','local_contingents'), "$securewwwroot/local/contingents/contingents.php", array('moodle/user:update', 'moodle/user:delete')));