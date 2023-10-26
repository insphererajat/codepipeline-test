<!--https://wallet.csccloud.in/v1/payment-->
<!-- https://payuat.csccloud.in/v1/payment -->
<center><h1>Please do not refresh this page...</h1></center>
<form method="post" action="<?php echo $url . $frac; ?>" name="f1">
    <table border="1">
        <tbody>
        <input type="hidden" name="message" value="<?= $encText; ?>" />
        </tbody>
    </table>

</form>
<script>
    document.f1.submit();
</script>