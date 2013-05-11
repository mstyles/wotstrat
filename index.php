<?php 
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/wotstrat/header.php');
?>
<!--Begin Content-->
<div id="content-wrapper-inner">
<div id="main-content">
<h1 class="page-title">
    <cufon class="cufon cufon-canvas" alt="WoTSTATS" style="width: 183px; height: 35px;">
        <canvas width="195" height="33" style="width: 195px; height: 33px; top: 2px; left: 0px;"></canvas>
        <cufontext>WoTSTATS</cufontext>
    </cufon>
</h1>
<h2>
    <cufon class="cufon cufon-canvas" alt="Tank " style="width: 68px; height: 23px;">
        <canvas width="85" height="22" style="width: 85px; height: 22px; top: 1px; left: 0px;"></canvas>
        <cufontext>Tank </cufontext></cufon>
    <cufon class="cufon cufon-canvas" alt="Comparison" style="width: 157px; height: 23px;">
        <canvas width="163" height="22" style="width: 163px; height: 22px; top: 1px; left: 0px;"></canvas>
        <cufontext>Comparison</cufontext>
    </cufon>
</h2>

<?php require_once(__ROOT__.'/wotstrat/compare.php');?>

</div>
<!--End Main Content-->

<!--Begin Sidebar-->
<div id="sidebar">




</div>
<!--End Sidebar-->


</div>
<!--End Content Wrapper-->

</div>
<!--End Container-->
<?php require_once(__ROOT__.'/wotstrat/footer.php') ?>