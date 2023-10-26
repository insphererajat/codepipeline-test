<title>Payment Gateway</title>
<center>
  <h1>Please do not refresh this page...</h1>
</center>


  <!-- //document.f1.submit(); https://www.phpzag.com/razorpay-payment-gateway-integration-in-php/ -->
<!-- </script> -->

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="success?txnid='<?= $paramList['notes']['transactionId'] ?>" method="POST">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
</form>
<script>
var options = <?php echo json_encode($paramList)?>;
options.handler = function (response){
    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    document.getElementById('razorpay_signature').value = response.razorpay_signature;
    document.razorpayform.submit();
};
options.modal = {
    ondismiss: function() {
      window.location = '/payment/razor-pay/failed?txnid='+<?= $paramList['notes']['transactionId'] ?>+'';
    },
    escape: false,
    backdropclose: false
};
var rzp = new Razorpay(options);
rzp.open();
</script>