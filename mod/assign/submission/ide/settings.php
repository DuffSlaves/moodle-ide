<?php

/**
 * This file defines the admin settings for this plugin
 *
 * @package assignsubmission_ide
 *
 */


// Note: This is on by default.
$settings->add(new admin_setting_configcheckbox('assignsubmission_ide/default',
    new lang_string('default', 'assignsubmission_ide'),
    new lang_string('default_help', 'assignsubmission_ide'), 1));
