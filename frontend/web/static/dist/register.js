var testing = true;
var base_url = 'http://' + window.location.hostname + '/';
(function () {

    $('#otpButton').on('click',function(e){
        e.preventDefault();

        var data = $('#stepOne').serializeArray();
        data._csrf = yii.getCsrfToken();

        if(validateStepOne(data) && testing ){
            $.ajax({
                type: "post",
                url: base_url + "register/otp",
                data: data,
                dataType: "json",
                success: function (response) {
                    $(".design1").removeClass('has-error')
                    if(response.success == 1){
                        $('#myModal2').modal('show');
                    } else {
                        $.each( response.error, function(key,value){
                            showError(key,value);
                        });
                    }
                }
            });
        }
    });

    $('.js-check-otp').on('click',function(e){
        e.preventDefault();
        var applicantData = $('#stepOne').serializeArray();
        var data = $('#otpForm').serializeArray();
        if(typeof applicantData !== 'undefined'){
            data._csrf = yii.getCsrfToken();
            $.each( applicantData, function(key,value){
                data.push(value);
            });
            $.ajax({
                type: "post",
                url: base_url + "register/reg-otp",
                data: data,
                dataType: "json",
                success: function (response) {
                    $(".design1").removeClass('has-error')
                    if(response.success == 1){

                    } else {

                    }
                }
            });
        }
         
    });

})(jQuery);

function validateStepOne(data){
    if(data.name != '' && data.mobile !=='' && data.email !==''){
        return true;
    }
}

function showError(fieldName,error){
    $(".js-"+fieldName).addClass('has-error');
    $(".js-"+fieldName).find('.help-block-error').html(error);
}

function checkOtp(elem) {
    emailOtp = elem.find('js-email-otp').val();
    mobileOtp = elem.find('js-mobile-otp').val();

    if( typeof mobileOtp !== "undefined" && mobileOtp !== ''){
        return true;
    }

   return true;
}

function copyAddress() {
    var sameAddr = $('#registrationform-same_as_permanent_address').val();
    sameAddr == 1 ? $("#same_as_permanent_address").prop("checked", true) : $("#same_as_permanent_address").prop("checked", false);
    $("#same_as_permanent_address").change(function () {
        var value = $(this).prop("checked") ? true : false;
        $("#registrationform-same_as_permanent_address").val(value ? 1 : 0);
        $(this).val(value);
        if (value) {
            $('#addressform-_house_no').val($('#registrationform-permanent_house_no').val());
            $('#addressform-_house_no').parent('div.form-group').addClass('disabled');

            $('#addressform-_street').val($('#registrationform-permanent_street').val());
            $('#addressform-_street').parent('div.form-group').addClass('disabled');

            $('#addressform-_village').val($('#registrationform-permanent_village').val());
            $('#addressform-_village').parent('div.form-group').addClass('disabled');

            $('#addressform-_state').val($('#registrationform-permanent_state').val());
            $('#addressform-_state').closest('div.form-group').addClass('disabled');
            $("#addressform-_state").trigger("chosen:updated");

            $('#addressform-_district').val($('#registrationform-permanent_district').val());
            $('#addressform-_district').closest('div.form-group').addClass('disabled');
            $("#addressform-_district").trigger("chosen:updated");

            $('#addressform-_pin_code').val($('#registrationform-permanent_pin_code').val());
            $('#addressform-_pin_code').parent('div.form-group').addClass('disabled');

            $('#addressform-_telephone_code').val($('#registrationform-permanent_telephone_code').val());
            $('#addressform-_telephone_code').parent('div.form-group').addClass('disabled');

            $('#addressform-_telephone_no').val($('#registrationform-permanent_telephone_no').val());
            $('#addressform-_telephone_no').parent('div.form-group').addClass('disabled');

        } else {
            $('#addressform-_house_no').val('');
            $('#addressform-_house_no').closest('div.form-group').removeClass('disabled');
            $('#addressform-_house_no').removeAttr("readonly");

            $('#addressform-_area').val('');
            $('#addressform-_area').closest('div.form-group').removeClass('disabled');
            $('#addressform-_area').removeAttr("readonly");

            $('#addressform-_landmark').val('');
            $('#addressform-_landmark').closest('div.form-group').removeClass('disabled');
            $('#addressform-_landmark').removeAttr("readonly");

            $('#addressform-_state').val('');
            $('#addressform-_state').closest('div.form-group').removeClass('disabled');
            $("#addressform-_state").trigger("chosen:updated");
            $('#addressform-_state').removeAttr("readonly");

            $('#addressform-_district').val('');
            $('#addressform-_district').closest('div.form-group').removeClass('disabled');
            $("#addressform-_district").trigger("chosen:updated");
            $('#addressform-_district').removeAttr("readonly");

            $('#addressform-_pincode').val('');
            $('#addressform-_pincode').closest('div.form-group').removeClass('disabled');
            $('#addressform-_pincode').removeAttr("readonly");
        }
    });
    
    $('#registrationform-permanent_pin_code').on('change', function(){
        var  stateCode = $('#registrationform-permanent_state').val();
        var elem = $(this);
        var pincode = elem.val();
        if(!pincode){
            return;
        }
        $.ajax({
           url: 'get-pincode',
           method: 'post',
           data: {stateCode: stateCode},
           success: function(data){
               if(data){
                    var array = data.split(",");
                    var exist = false; //$.inArray( pincode.slice(0, 2), array);var n = str.startsWith("hello");
                    $.each(array, function( index, value ) {
                        exist = pincode.startsWith(value);
                        if(exist){
                            return false;
                        }
                    });  
                    if(!exist){
                        alert('Please enter valid Pincode!!');
                        elem.val('');
                    }   
               }
           }
        });
    });
    $('#registrationform-permanent_pin_code').trigger('change');
    
    $('#registrationform-correspondence_pin_code').on('change', function(){
        var  stateCode = $('#registrationform-correspondence_state').val();
        var elem = $(this);
        var pincode = elem.val();
        if(!pincode){
            return;
        }
        $.ajax({
           url: 'get-pincode',
           method: 'post',
           data: {stateCode: stateCode},
           success: function(data){
               if(data){
                    var array = data.split(",");
                    var exist = false; //$.inArray( pincode.slice(0, 2), array);var n = str.startsWith("hello");
                    $.each(array, function( index, value ) {
                        exist = pincode.startsWith(value);
                        if(exist){
                            return false;
                        }
                    });  
                    if(!exist){
                        alert('Please enter valid Pincode!!');
                        elem.val('');
                    }   
               }
           }
        });
    });
    $('#registrationform-correspondence_pin_code').trigger('change');
    
    $('#registrationform-correspondence_state').on('change', function(){
        var corresState = $('#registrationform-correspondence_state').val();
        $('#registrationform-correspondence_pin_code').val('');
        if (corresState) {
            $.ajax({
                url: 'get-district',
                method: 'post',
                data: {state: corresState},
                success: function (data) {
                    $("select#registrationform-correspondence_district").html(data);
                    $("#registrationform-correspondence_district").trigger("chosen:updated");
                }
            });
        } 
    });
    $('#registrationform-permanent_state').on('change', function(){
        var permanentState = $('#registrationform-permanent_state').val();
        $('#registrationform-permanent_pin_code').val('');
        if (permanentState) {
            $.ajax({
                url: 'get-district',
                method: 'post',
                data: {state: permanentState},
                success: function (data) {
                    $("select#registrationform-permanent_district").html(data);
                    $("#registrationform-permanent_district").trigger("chosen:updated");
                }
            });
        } 
    });
    
}