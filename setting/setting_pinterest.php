﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
    <title>Untitled</title>
</head>

<body>

<?php

// This file is for items on setting page.
// Please change "servicename" to your connecting service's name 

$settings->add(new admin_setting_heading('sharedpanel/pinterest',
    get_string("pinterest", 'sharedpanel'), get_string("pinterest", 'sharedpanel')));

// Items
$settings->add(new admin_setting_configtext('sharedpanel/newservice_config1',
    get_string('newservice_config1', 'sharedpanel'), '', ''));


/* Please check sample codes for Twitter
       $settings->add(new admin_setting_configtext('sharedpanel/TWconsumerKey',
        get_string('TWconsumerKey', 'sharedpanel'),
        get_string('TWconsumerKey_help', 'sharedpanel'), ''));
*/

?>

</body>
</html>
