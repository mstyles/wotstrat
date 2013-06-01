<?php
require_once 'helpers.php';

connectDb();

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

if($type == 'suggestion'){
    if(isset($_REQUEST['feedback'])){
        $whole_feedback = filter_var($_REQUEST['feedback'], FILTER_SANITIZE_STRING);
        $whole_feedback = html_entity_decode($whole_feedback, ENT_QUOTES);
        $feedback = wordwrap($whole_feedback);
    }
    $message .= "Message:\n" . $feedback . "\n\n";
} else if($type == 'bug'){
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

$sql = "
    INSERT INTO feedback
    SET
        type = '$type',
        email_from = '$email_address',
        message = '$whole_feedback',
        bugged_tank = '$bugged_tank',
        bugged_attr = '$bugged_attribute'
";

queryInsert($sql);
echo 'Thanks!';
?>
