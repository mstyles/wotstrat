<?php
require_once 'helpers.php';

connectDb();

$sql = "
    INSERT INTO feedback
    SET
        type = 'test',
        email_from = 'test',
        message = 'test'
";

queryInsert($sql);
?>