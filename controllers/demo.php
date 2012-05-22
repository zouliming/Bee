<?php
    require_once("config.php");
    ShowUser::model()->getAllUser();
    $dba = dba();
    $userdata = $dba->select("select * from show_user");
    var_dump($userdata);
?>