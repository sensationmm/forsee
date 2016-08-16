<?php
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';


    $course = $_POST["course"];
    $broker = $_POST["broker"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $optin = $_POST["optin"];

    $user_id = get_current_user_id();

    $data_array = array("cea_course_name" => $course, "cea_broker" => $broker, "cea_opt_in" => $optin, "cea_email" => $email, "cea_phone" => $phone);


    $field_key = "field_57abf2728abbe";
    $value = get_field($field_key, 'user_'.$user_id);
    $value[] = $data_array;
    update_field( $field_key, $value, 'user_'.$user_id );   
?>