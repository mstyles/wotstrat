<?php
    $foo = 'you&#39;re my friend!!! &#34;don&#39;';
    $bar = html_entity_decode($foo);
    echo $bar;
?>