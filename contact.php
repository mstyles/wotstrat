<?php
require_once('header.php');
echo '<!--'.php_uname('n').'-->';
?>
<script type="text/javascript">
    page = 'contact';
</script>
<!--Begin Content-->
<div id="content-wrapper-inner">
<div id="main-content">
<p>Have some feedback or a suggestion? Let me know!</p>
<table border="0">
    <tr>
        <td>
            <span>Email:</span>
        </td>
        <td>
            <input type="text" id="email_address"/><span style="color:gold">*optional</span>
        </td>
    </tr>
    <tr>
        <td>
            <span>Message:</span>
        </td>
        <td>
            <textarea id="feedback" name="feedback" rows="4" cols="100"></textarea>
        </td>
    </tr>
</table>
<input type="hidden" id="feedback_type" value="suggestion"/>
<input type="submit" id="submit_btn" value="submit" />
</div>
<!--End Main Content-->
</div>
<!--End Content Wrapper-->
<?php require_once('footer.php') ?>
