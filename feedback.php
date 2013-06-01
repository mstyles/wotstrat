<?php

if(isset($_REQUEST['email_address'])){
    $email_address = filter_var($_REQUEST['email_address'], FILTER_SANITIZE_EMAIL);
    if(!filter_var($_REQUEST['email_address'], FILTER_VALIDATE_EMAIL)){
        //maybe notify user?
    }
}
$type = $_REQUEST['type'];

$mail_to = 'mstyleshk@gmail.com';
$subject = 'WotStrat '.$type;
$message  = 'From: ' . $email_address . "\n\n";

if($type == 'Suggestion'){
    if(isset($_REQUEST['feedback'])){
        $whole_feedback = filter_var($_REQUEST['feedback'], FILTER_SANITIZE_STRING);
        $feedback = wordwrap($whole_feedback);
    }
    $message .= "Message:\n" . $feedback . "\n\n";
} else if($type == 'Bug'){
    if(isset($_REQUEST['bugged_tank'])){
        $bugged_tank = filter_var($_REQUEST['bugged_tank'], FILTER_SANITIZE_STRING);
    }
    if(isset($_REQUEST['bugged_attribute'])){
        $bugged_attribute = filter_var($_REQUEST['bugged_attribute'], FILTER_SANITIZE_STRING);
    }
    $message .= "Tank: " . $bugged_tank . "\n\n";
    $message .= "Attribute: " . $bugged_attribute . "\n\n";
}

mail($mail_to, $subject, $message);
echo 'Thanks!';
?>
