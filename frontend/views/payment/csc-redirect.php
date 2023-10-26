<center><h1>Please do not refresh this page...</h1></center>
<form method="post" action="https://wallet.csccloud.in/v1/payment/<?php echo $frac;?>" name="f1">
    <table border="1">
        <tbody>
            <input type="hidden" name="message" value="<?=$encText;?>" />
        </tbody>
    </table>
    
</form>
<script>
	document.f1.submit();
</script>