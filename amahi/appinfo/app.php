<?php

OC::$CLASSPATH['OC_User_Amahi'] = 'apps/user_amahi/lib/amahi_user.php';

OC_User::registerBackend('amahi');
OC_User::useBackend('amahi');

?>