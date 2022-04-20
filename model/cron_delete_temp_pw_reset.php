#!/usr/local/bin/php
<?php
define('ROOT', dirname(__FILE__));

require ROOT . '/model/cron_delete_temp_pw_reset.php';

$db = dbConnect();
$deleteQuery = $db->prepare('DELETE FROM `password_reset_temp` WHERE `exp_date` < (NOW() - INTERVAL 1 DAY)');
$deleteQuery->execute();
$deleteQuery2 = $db->prepare('DELETE FROM `email_update_temp` WHERE `exp_date` < (NOW() - INTERVAL 1 DAY)');
$deleteQuery2->execute();