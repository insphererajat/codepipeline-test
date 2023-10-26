<center>
    <h1>Please do not refresh this page...</h1>
</center>
<form method="post" action="<?= $action; ?>" name="f1">
    <table border="1">
        <tbody>
            <input type="hidden" name="encRequest" value="<?=$encryptedData;?>" />
            <input type="hidden" name="access_code" value="<?=$accessCode;?>" />
        </tbody>
    </table>
</form>
<script>
document.f1.submit();
</script>