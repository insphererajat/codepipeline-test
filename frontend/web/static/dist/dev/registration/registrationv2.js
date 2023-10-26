var genrerateOTPCounter = 0;
var validateOTPCounter = 0;
var RegistrationV2Controller = (function ($) {
    return {
        createUpdate: function () {
            RegistrationV2Controller.CreateUpdate.init();
        },
        deleteQualification: function () {
            $('.js-deleteQualification').off('click').on('click', function (e) {
                e.preventDefault();
                var elem = $(this);
                var id = elem.data('id');
                if (typeof id === undefined || id === "") {
                    $().General_ShowErrorMessage({message: 'Error: Invalid click.'});
                    return false;
                }
    
                bootbox.confirm({
                    closeButton: false,
                    title: "Confirmation",
                    message: "Do you really want to delete this record?",
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button'
                        },
                        cancel: {
                            label: "Cancel",
                            className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey theme-button'
                        }
                    },
                    callback: function (result) {
                        if (result === true) {
                            $.ajax({
                                type: 'post',
                                url: baseHttpPath + '/api/registration/delete-qualification',
                                dataType: 'json',
                                data: {id: id, _csrf: yii.getCsrfToken()},
                                success: function (data, textStatus, jqXHR) {
                                    if (data.success == 1) {
                                        location.reload();
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $().hideScreenLoader();
                                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                                },
                                beforeSend: function (jqXHR, settings) {
                                    $().showScreenLoader();
                                },
                                complete: function (jqXHR, textStatus) {
                                    $().hideScreenLoader();
                                }
                            });
                        }
                    }
                });
            });
        },
        deleteEmployment: function () {
            $('.js-deleteEmployment').off('click').on('click', function (e) {
                e.preventDefault();
                var elem = $(this);
                var id = elem.data('id');
                if (typeof id === undefined || id === "") {
                    $().General_ShowErrorMessage({message: 'Error: Invalid click.'});
                    return false;
                }
    
                bootbox.confirm({
                    closeButton: false,
                    title: "Confirmation",
                    message: "Do you really want to delete this record?",
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button'
                        },
                        cancel: {
                            label: "Cancel",
                            className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey theme-button'
                        }
                    },
                    callback: function (result) {
                        if (result === true) {
                            $.ajax({
                                type: 'post',
                                url: baseHttpPath + '/api/registration/delete-employment',
                                dataType: 'json',
                                data: {id: id, _csrf: yii.getCsrfToken()},
                                success: function (data, textStatus, jqXHR) {
                                    if (data.success == 1) {
                                        location.reload();
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $().hideScreenLoader();
                                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                                },
                                beforeSend: function (jqXHR, settings) {
                                    $().showScreenLoader();
                                },
                                complete: function (jqXHR, textStatus) {
                                    $().hideScreenLoader();
                                }
                            });
                        }
                    }
                });
            });
        },
        employmentDocuments: function (id) {

            $('a.uploadEmploymentDocument-' + id).uploadFile({
                cloudUpload: true,
                addRemoveLinks: false,
                maxImage: 1,
                acceptedFiles: ".jpg,.jpeg,.png",
                maxFilesize: 0.1,
                containerId: id,
                onSuccess: function (file, response) {
                    if (response.success == "1") {
                        var media = response.media;

                        var list = ['jpg', 'jpeg', 'png'];
                        var image = (list.indexOf(response.extension) != -1) ? 1 : 0;
                        var pdf = (response.extension === "pdf") ? 1 : 0;
                        var doc = (response.extension === "doc") ? 1 : 0;
                        var docx = (response.extension === "docx") ? 1 : 0;
                        var xls = (response.extension === "xls") ? 1 : 0;
                        var source = $("#single-upload-media-template").html();
                        var template = Handlebars.compile(source);
                        var html = template({media: response.cdnPath, id: media.orig, guid: media.guid, file: response.fileName, image: image, pdf: pdf, doc: doc, docx: docx, xls: xls});

                        $('.uploadEmploymentDocumentContainer-' + id).addClass('hide');
                        $('.uploadedEmploymentDocumentContainer-' + id).removeClass('hide');
                        $('.uploadedEmploymentDocumentContainer-' + id).html(html);
                        $('.inputQualificationDocument-' + id).val(media.orig);
                        $('#uploadImageModal').modal('hide');
                        RegistrationV2Controller.deleteMedia();

                    } else {
                        $().General_ShowErrorMessage({message: 'While save file in remote cdn error.'});
                    }
                }
            });
        },
        deleteMedia: function (formSelector) {

            $('.trash').off('click').on('click', function (e) {
                e.preventDefault();
                var elem = $(this);
                var guid = elem.data('guid');
                var id = elem.data('id');
                //var applicantPostId = elem.data('applicant-post');
                var applicantPostGuid = $(".js-applicantPostGuid").data('guid');
                if (typeof id === undefined || id === "" || typeof guid === undefined || guid === "" || typeof applicantPostGuid === undefined || applicantPostGuid === "") {
                    $().General_ShowErrorMessage({message: 'Error: Invalid click.'});
                    return false;
                }
    
                bootbox.confirm({
                    closeButton: false,
                    title: "Confirmation",
                    message: "Do you want to delete?",
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: "Yes",
                            className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular green theme-button'
                        },
                        cancel: {
                            label: "Cancel",
                            className: 'button-v2 button-v2--primary u-radius4 u-pad8_20 fs14__regular grey theme-button'
                        }
                    },
                    callback: function (result) {
                        if (result === true) {
                            $.ajax({
                                type: 'post',
                                url: baseHttpPath + '/api/registration/remove-media',
                                dataType: 'json',
                                data: {id: id, guid: guid, applicantPostGuid: applicantPostGuid, _csrf: yii.getCsrfToken()},
                                success: function (data, textStatus, jqXHR) {
                                    if (data.success == "2") {
                                        location.reload();
                                    } else if (data.success == "1") {
                                        elem.closest('div.js-uploadedContainer').addClass('hide');
                                        var inputClass = elem.closest('div.js-uploadedContainer').data('input');
                                        $("." + inputClass).val('');
                                        elem.closest('div.js-uploadedContainer').prev().removeClass('hide');
                                        elem.closest('div.js-uploadedContainer').html('');
                                        $("#documentDetailsForm").submit();
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $().hideScreenLoader();
                                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                                },
                                beforeSend: function (jqXHR, settings) {
                                    $().showScreenLoader();
                                },
                                complete: function (jqXHR, textStatus) {
                                    $().hideScreenLoader();
                                }
                            });
                        }
                    }
                });
            });
        },
        formUnloadPrompt: function (formSelector) {

            var formA = $(formSelector).serialize(), formB, formSubmit = false;

            // Detect Form Submit
            $(formSelector).submit(function () {
                formSubmit = true;
            });

            // Handle Form Unload    
            window.onbeforeunload = function () {
                if (formSubmit)
                    return;
                formB = $(formSelector).serialize();
                if (formA != formB)
                    return "Your changes have not been saved.";
            };

            // Enable & Disable Submit Button
            var formToggleSubmit = function () {
                formB = $(formSelector).serialize();
                $(formSelector + ' [type="submit"]').attr("disabled", formA == formB);
            };

            formToggleSubmit();
            $(formSelector).change(formToggleSubmit);
            $(formSelector).keyup(formToggleSubmit);
        },
        employmentDetail: function () {

            $('.emp_from__date,.emp_to__date').datetimepicker({
                format: 'DD-MM-YYYY',
                useCurrent: false,
                maxDate: $.now()
            });
            $('.emp_from__date').datetimepicker().on('dp.change', function (e) {
                var incrementDay = moment(new Date(e.date));
                incrementDay.add(1, 'days');
                $('.emp_to__date').data('DateTimePicker').minDate(incrementDay);
                $(this).data("DateTimePicker").hide();
            });

            $('.emp_to__date').datetimepicker().on('dp.change', function (e) {
                var decrementDay = moment(new Date(e.date));
                decrementDay.subtract(1, 'days');
                $('.emp_from__date').data('DateTimePicker').maxDate(decrementDay);
                $(this).data("DateTimePicker").hide();
            });
        },
        otherDetails: function () {

            $('.other_from__date,.other_to__date').datetimepicker({
                format: 'DD-MM-YYYY',
                useCurrent: false,
                //minDate: $.now()
                //minDate: moment()
            });
            $('.other_from__date').datetimepicker().on('dp.change', function (e) {
                var incrementDay = moment(new Date(e.date));
                incrementDay.add(1, 'days');
                $('.other_to__date').data('DateTimePicker').minDate(incrementDay);
                $(this).data("DateTimePicker").hide();
            });

            $('.other_to__date').datetimepicker().on('dp.change', function (e) {
                var decrementDay = moment(new Date(e.date));
                decrementDay.subtract(1, 'days');
                $('.other_from__date').data('DateTimePicker').maxDate(decrementDay);
                $(this).data("DateTimePicker").hide();
            });

            $("#registrationform-have_ncc_nss").on("change", function () {
                var value = $(this).val();

                $(".nccb").addClass('hide');
                $(".nccc").addClass('hide');
                $(".nssb").addClass('hide');
                $(".nssc").addClass('hide');
            });
        },
        qualificationConsil: function () {
            $(".qualificationType").on("change", function () {
                var type = $(this).val();

                $('.attrSubject1').empty();
                $('.attrSubject2').empty();
                $('.attrSubject3').empty();
                $('.attrSubject4').empty();
                $('.attrSubject5').empty();
                $(".js-subject2").addClass("hide");
                $(".js-subject3").addClass("hide");
                $(".js-subject4").addClass("hide");
                $(".js-subject5").addClass("hide");

                $(".js-mphil_phd").addClass("hide");
                $(".js-net_set").addClass("hide");
                if (type == '22' || type == '23') {
                    $(".js-mphil_phd").removeClass("hide");
                }
                if (type == '24') {
                    $(".js-net_set").removeClass("hide");
                }
            });
        },
        tehsilCascade: function () {
            $(".js-tehsilCasecade").on("change", function () {
                var tehsil = $(this).val();

                $(".js-tehsilSecion").addClass("hide");
                if (tehsil == '0') {
                    $(".js-tehsilSecion").removeClass("hide");
                } else {
                    $(".js-tehsilName").val('');
                }
            });
        },
        motherTongueCascade: function () {
            $(".js-motherTongueCascade").on("change", function () {
                var tongue = $(this).val();

                $(".js-motherTongueSecion").addClass("hide");
                if (tongue == '3') {
                    $(".js-motherTongueSecion").removeClass("hide");
                } else {
                    $(".js-motherTongueName").val('');
                }
            });
        },
        categoryDetail: function () {
            $('.js-dswroUptoDate').datetimepicker({
                format: 'DD-MM-YYYY'
            });
        },
        isDomicile: function () {

            setTimeout(checkIfPageAlreadyLoaded, 2000);

            function checkIfPageAlreadyLoaded() {
                $(".js-isDomicile").on("change", function () {
                    var elem = $(this);

                    if (typeof elem.val() === "undefined" || elem.val() === "") {
                        return;
                    }

                    $(".js-exServiceChildSection").removeClass("hide");
                    if (elem.val() == "0") {
                        $("#registrationform-domicile_no").val('');
                        $("#registrationform-domicile_issue_date").val('');
                        $("#registrationform-domicile_issue_district").val('');
                        $("#registrationform-domicile_issue_district").trigger("chosen:updated");
                        $(".js-exServiceChildSection").addClass("hide");
                        if ($("#registrationform-gender").val() == 'FEMALE') {
                            $(".js-ukWoman").attr("placeholder", "Yes");
                        } else {
                            $(".js-ukWoman").attr("placeholder", "No");
                        }
                    }

                    $.ajax({
                        url: baseHttpPath + '/api/registration/get-category-and-disability-list',
                        method: 'post',
                        async: false,
                        data: {is_domcile: elem.val()},
                        success: function (response) {
                            if (response.success == "1") {
                                $('.js-socialCategory').html(response.categoryTemplate);
                                $(".js-socialCategory").trigger("chosen:updated");
                                $(".js-socialCategory").trigger("change");
                                $('.js-disabilityId').html(response.distabilityTemplate);
                                $(".js-disabilityId").trigger("chosen:updated");
                                $(".js-disabilityId").trigger("change");
                                $('.exservicemanselectAttr').html(response.exServiceTemplate);
                                $(".exservicemanselectAttr").trigger("chosen:updated");
                                $(".exservicemanselectAttr").trigger("change");
                                $('.isDependentFreedomFighterselectAttr').html(response.dffTemplate);
                                $(".isDependentFreedomFighterselectAttr").trigger("chosen:updated");
                                $(".isDependentFreedomFighterselectAttr").trigger("change");
                                RegistrationV2Controller.showHideInputs('exserviceman');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            elem.val('');
                            $().General_ShowErrorMessage({type: 'error', message: jqXHR.responseText});
                        },
                        beforeSend: function (jqXHR, settings) {
                            $().showScreenLoader();
                        },
                        complete: function (jqXHR, textStatus) {
                            $().hideScreenLoader();
                        }
                    });
                });
            }


            $("#registrationform-is_high_school_passed_from_uttarakhand").on("change", function () {
                var elem = $(this);
                if (elem.val() == "1") {
                    $(".js-highSchoolPassed").addClass("hide");
                } else {
                    $(".js-highSchoolPassed").removeClass("hide");
                }
            });
        },
        classifiedSamajKalyanAdhikari: function () {

            $('#js-sanvikshak').on('change', function () {
                $('.section-sanvikshak').addClass('hide');
                if (this.checked) {
                    $('.section-sanvikshak').removeClass('hide');
                }
            });

            $('#js-deo').on('change', function () {
                $('.section-deo').addClass('hide');
                if (this.checked) {
                    $('.section-deo').removeClass('hide');
                }
            });

            $('#js-gpdo').on('change', function () {
                $('.section-gpdo').addClass('hide');
                if (this.checked) {
                    $('.section-gpdo').removeClass('hide');
                }
            });

            $('#js-supervisor').on('change', function () {
                $('.section-supervisor').addClass('hide');
                if (this.checked) {
                    $('.section-supervisor').removeClass('hide');
                }
            });

            var errorMsg = "Sorry, you are not eligible for this post.";

            $('#criteriaForm').on('beforeSubmit', function (e) {

                var flag = true;
                var elem = $(this);

                if (elem.find('.has-error').length) {
                    $.fn.General_ShowNotification({message: "Fill your form.", type: "danger"});
                    return false;
                }

                if (flag == false) {
                    $.fn.General_ShowErrorMessage({message: errorMsg});
                    return false;
                }

                var elem = $('#criteriaForm');
                var url = elem.attr("action");
                var postData = elem.serialize();
                var formId = elem.attr("id");

                if (elem.find('.has-error').length) {
                    return false;
                }

                $.ajax({
                    url: url,
                    type: "POST",
                    data: postData,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success == '1') {
                            window.location.href = data.url;
                        } else {
                            $.each(data.errors, function (key, val) {
                                //$(".field-registrationform-" + key).addClass('has-error').find('p').text(val);
                                $.fn.General_ShowNotification({message: val, type: "danger"});
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('.submitClass').prop('disabled', false).html('Submit');
                        $().General_ShowErrorMessage({message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $('.submitClass').prop('disabled', true).html('Please Wait...');
                        $().showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $('.submitClass').prop('disabled', false).html('Submit');
                        $().hideScreenLoader();

                    }
                });
            }).on('submit', function (e) {
                e.preventDefault();
            });

        },
        personalDetails: function () {
            RegistrationV2Controller.PersonalDetails.init();
        },
        disabilityDetails: function () {
            RegistrationV2Controller.DisabilityDetails.init();
        },
        uploadDocuments: function () {
            RegistrationV2Controller.UploadDocuments.init();
        },
        calculatePercentage: function () {
            $('#registrationform-total_marks').on('input', function () {
                RegistrationV2Controller.calculate();
            });
            $('#registrationform-obtained_marks').on('input', function () {
                RegistrationV2Controller.calculate();
            });

            $('#registrationform-obtained_marks').on('blur', function () {
                var obtained = $(this).val();
                var total = $('#registrationform-total_marks').val();
                if (typeof obtained === "undefined" || obtained === "" || typeof total === "undefined" || total === "") {
                    return;
                }

                if (parseInt(obtained) > parseInt(total)) {
                    $.fn.General_ShowNotification({message: 'Marks Obtained must be less than or equal to "Out Of".', type: "danger"});
                    $(this).val('');
                }
            });
            $('#registrationform-total_marks').on('blur', function () {
                var total = $(this).val();
                var obtained = $('#registrationform-obtained_marks').val();
                if (typeof obtained === "undefined" || obtained === "" || typeof total === "undefined" || total === "") {
                    return;
                }

                if (parseInt(obtained) > parseInt(total)) {
                    $.fn.General_ShowNotification({message: 'Marks Obtained must be less than or equal to "Out Of".', type: "danger"});
                    $(this).val('');
                }
            });
        },
        calculate: function calculate() {
            var obtainedMarks = parseInt($('#registrationform-obtained_marks').val());
            var maxMarks = parseInt($('#registrationform-total_marks').val());
            var perc = "";
            if (isNaN(obtainedMarks) || isNaN(maxMarks)) {
                perc = " ";
            } else {
                perc = ((obtainedMarks / maxMarks) * 100).toFixed(3);
            }
            $('#registrationform-percentage').val(parseInt(perc));
            $('#registrationform-percentage').prop("readonly", true);
        },
        copyTypeAdress: function () {

            var input = ['house_no', 'street', 'area', 'pincode', 'state_code', 'district_code', 'tehsil_code', 'tehsil_name', 'pincode', 'premises_name', 'village_name', 'landmark'];
            $.each(input, function (index, item) {
                if ($('.copyPresentAddress').prop("checked") == true) {
                    $('#registrationform-present_address_' + item).bind("change keyup", function (event) {

                        if ($('.copyPresentAddress').prop("checked") == false) {
                            return false;
                        }

                        var source, source_id, target_id;
                        source = $(this);
                        source_id = source.attr('id');
                        target_id = source_id.replace('registrationform-present_address_', 'registrationform-permanent_address_');
                        $('#' + target_id).val(source.val());

                        if (item == 'district_code' || item == 'tehsil_code') {
                            // district value
                            var html = '';
                            var item_val = $("#" + source_id + " option:selected").val();
                            var item_text = $("#" + source_id + " option:selected").text();
                            html = ('<option value=' + item_val + '>' + item_text + '</option>');
                            $('#' + target_id).empty().append(html);
                        }
                        $('#' + target_id).trigger("chosen:updated");
                    });
                }
            });

        },
        copypresentAddress: function () {

            $('.copyPresentAddress').change(function () {
                if (this.checked) {
                    $(".js-correspondence").addClass("hide");
                    $("#registrationform-permanent_address_house_no").val($("#registrationform-present_address_house_no").val());
                    $('#registrationform-permanent_address_house_no').prop("readonly", true);

                    $("#registrationform-permanent_address_premises_name").val($("#registrationform-present_address_premises_name").val());
                    $('#registrationform-permanent_address_premises_name').prop("readonly", true);

                    $("#registrationform-permanent_address_street").val($("#registrationform-present_address_street").val());
                    $('#registrationform-permanent_address_street').prop("readonly", true);

                    $("#registrationform-permanent_address_area").val($("#registrationform-present_address_area").val());
                    $('#registrationform-permanent_address_area').prop("readonly", true);

                    $("#registrationform-permanent_address_landmark").val($("#registrationform-present_address_landmark").val());
                    $('#registrationform-permanent_address_landmark').prop("readonly", true);

                    $("#registrationform-permanent_address_state_code").val($("#registrationform-present_address_state_code").val());
                    $("#registrationform-permanent_address_state_code").prop('readonly', true).trigger("chosen:updated");

                    $("#registrationform-permanent_address_village_name").val($("#registrationform-present_address_village_name").val());
                    $('#registrationform-permanent_address_village_name').prop("readonly", true);

                    $("#registrationform-permanent_address_tehsil_name").val($("#registrationform-present_address_tehsil_name").val());
                    $('#registrationform-permanent_address_tehsil_name').prop("readonly", true);

                    $("#registrationform-permanent_address_pincode").val($("#registrationform-present_address_pincode").val());
                    $('#registrationform-permanent_address_pincode').prop("readonly", true);

                    // district value
                    var district_codehtml = '';
                    var disctrict_val = $("#registrationform-present_address_district_code option:selected").val();
                    var disctrict_text = $("#registrationform-present_address_district_code option:selected").text();
                    district_codehtml = ('<option value=' + disctrict_val + '>' + disctrict_text + '</option>');
                    $("#registrationform-permanent_address_district_code").empty().append(district_codehtml);


                    // tehsil value
                    var tehsil_codehtml = '';
                    var tehsil_val = $("#registrationform-present_address_tehsil_code option:selected").val();

                    var tehsil_text = $("#registrationform-present_address_tehsil_code option:selected").text();
                    tehsil_codehtml = ('<option value=' + tehsil_val + '>' + tehsil_text + '</option>');
                    $("#registrationform-permanent_address_tehsil_code").empty().append(tehsil_codehtml);

                    $("#registrationform-permanent_address_district_code").prop('readonly', true).trigger("chosen:updated");
                    $("#registrationform-permanent_address_tehsil_code").prop('readonly', true).trigger("chosen:updated");


                } else {
                    $(".js-correspondence").removeClass("hide");
                    $("#registrationform-permanent_address_house_no").val('');
                    $("#registrationform-permanent_address_premises_name").val('');
                    $("#registrationform-permanent_address_street").val('');
                    $("#registrationform-permanent_address_area").val('');
                    $("#registrationform-permanent_address_landmark").val('');
                    $("#registrationform-permanent_address_pincode").val('');
                    $("#registrationform-permanent_address_tehsil_name").val('');
                    $("#registrationform-permanent_address_village_name").val('');
                    $('#registrationform-permanent_address_house_no').prop("readonly", false);
                    $('#registrationform-permanent_address_premises_name').prop("readonly", false);
                    $('#registrationform-permanent_address_street').prop("readonly", false);
                    $('#registrationform-permanent_address_area').prop("readonly", false);
                    $('#registrationform-permanent_address_landmark').prop("readonly", false);
                    $('#registrationform-permanent_address_pincode').prop("readonly", false);
                    $('#registrationform-permanent_address_tehsil_name').prop("readonly", false);
                    $('#registrationform-permanent_address_village_name').prop("readonly", false);

                    $("#registrationform-permanent_address_state_code").val('');
                    $("#registrationform-permanent_address_state_code").prop('readonly', false).trigger("chosen:updated");
                    $("#registrationform-permanent_address_district_code").find('option').remove();
                    $("#registrationform-permanent_address_district_code").prop('readonly', false).trigger("chosen:updated");
                    $("#registrationform-permanent_address_tehsil_code").find('option').remove();
                    $("#registrationform-permanent_address_tehsil_code").prop('readonly', false).trigger("chosen:updated");
                }

            });

        },
        priority: function () {
            $('#registrationform-priority').on('change', function (e) {
                var elem = $(this);
                var priority = $(elem).val();

                var guid = $('#registrationform-guid').val();
                if (typeof guid === "undefined" || guid === "") {
                    return;
                }

                if (priority !== "") {
                    $.ajax({
                        url: baseHttpPath + '/api/registration/validate-priority',
                        method: 'post',
                        async: false,
                        data: {guid: guid, priority: priority},
                        success: function (data) {
                            if (data.success == "0") {
                                $().General_ShowErrorMessage({type: 'error', message: "Please select valid type of Institute."});
                                $('#registrationform-priority').val('');
                                $('.chznSearchSingle').chosen().trigger("chosen:updated");
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            elem.val('');
                            $().General_ShowErrorMessage({type: 'error', message: jqXHR.responseText});
                        },
                        beforeSend: function (jqXHR, settings) {
                            $().showScreenLoader();
                        },
                        complete: function (jqXHR, textStatus) {
                            $().hideScreenLoader();
                        }
                    });
                }
            });
        },
        checkStudyCenter: function () {
            $('#registrationform-study_centre_code').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var studyCenterCode = $(elem).val();

                if (studyCenterCode !== "") {
                    $.ajax({
                        url: baseHttpPath + '/api/registration/study-centre',
                        method: 'post',
                        async: false,
                        data: {studyCenterCode: studyCenterCode},
                        success: function (data) {
                            if (data.success == "1") {

                                $('#registrationform-name').val(data.studyCentre['name']);
                                $('#registrationform-school_address').val(data.studyCentre['address1'] + ',' + data.studyCentre['address2']);
                                $('#registrationform-country_code').val(data.studyCentre['country_code']);
                                $('#registrationform-state_code').val(data.studyCentre['state_code']);
                                $('#registrationform-district_code').val(data.studyCentre['district_code']);
                                $('#registrationform-pincode').val(data.studyCentre['pincode']);

                                $('.chznSearchSingle').chosen().trigger("chosen:updated");
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            elem.val('');
                            $().General_ShowErrorMessage({type: 'error', message: jqXHR.responseText});
                        },
                        beforeSend: function (jqXHR, settings) {
                            $().showScreenLoader();
                        },
                        complete: function (jqXHR, textStatus) {
                            $().hideScreenLoader();
                        }
                    });
                }
            });
        },
        sendOtp: function () {
            $('#generateOTP').on('click', function () {
                var elem = $(this);
                if(genrerateOTPCounter >= 5) {
                    $.fn.General_ShowNotification({message: 'Please try again after sometime.'});
                    return;
                }
                $.ajax({
                    url: baseHttpPath + '/registration/validate',
                    method: 'post',
                    async: true,
                    data: $('#basicDetailsForm').serialize(),
                    success: function (data) {
                        var error = false;
                        $.each(data, function (index, obj) {
                            $('.field-' + index).addClass('has-error').removeClass('has-success');
                            $('.field-' + index).find('.help-block').addClass('help-block-error');
                            $('.field-' + index).find('.help-block').html(obj[0]);
                            error = true;
                        });
                        if (!error) {
                            var name = $('#registrationform-name').val();
                            var mobile = $('#registrationform-mobile').val();
                            var email = $('#registrationform-email').val();

                            $.ajax({
                                url: baseHttpPath + '/api/registration/send-otp',
                                method: 'post',
                                async: false,
                                data: {name: name, email: email, mobile: mobile, _csrf: yii.getCsrfToken()},
                                success: function (data) {
                                    if (data.status == 1) {
                                        genrerateOTPCounter = genrerateOTPCounter + 1;
                                        var $modal = $('#otpModal');
                                        $('#globalLoader').addClass('hide');
                                        $modal.html(data.template);
                                        $modal.modal('show');

                                        var time_in_minutes = $('#collapseOne').data('time');
                                        var time_in_miliseconds = time_in_minutes * 60 * 1000;
                                        setTimeout(function () {
                                            $("#resendMobile").removeClass('d-none');
                                        }, time_in_miliseconds);
                                        RegistrationV2Controller.otpClock();
                                        $.fn.formSanitization();

                                        $('#verifyotpform').on('beforeSubmit', function (e) {
                                            var message = 'Comment Added Successfully!';
                                            RegistrationV2Controller.sectionAjaxForm($(this), $modal, message);
                                        }).on('submit', function (e) {
                                            e.preventDefault();
                                        });

                                        $.fn.General_ShowNotification({message: 'OTP sent on your email and mobile.'});
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                                },
                                beforeSend: function (jqXHR, settings) {
                                    elem.prop('disabled', true).html('Please Wait...');
                                    $().showScreenLoader();
                                },
                                complete: function (jqXHR, textStatus) {
                                    elem.prop('disabled', true).html('Generate OTP');
                                    $().hideScreenLoader();
                                }
                            });
                        }
                    }
                });
            });

            RegistrationV2Controller.resendOtp();
        },

        sectionAjaxForm: function (elem, $modal, message) {

            var url = elem.attr("action");
            var postData = elem.serializeArray();
            var formId = elem.attr("id");

            if (elem.find('.has-error').length) {
                return false;
            }

            if(validateOTPCounter >= 5) {
                $.fn.General_ShowNotification({message: 'Please try again after sometime.'});
                return;
            }

            postData[1].value = CryptoJS.AES.encrypt(postData[1].value, encriptionKey).toString();
            postData[2].value = CryptoJS.AES.encrypt(postData[2].value, encriptionKey).toString();

            $.ajax({
                url: url,
                type: "POST",
                data: postData,
                dataType: 'json',
                success: function (data) {
                    if(validateOTPCounter >= 5) {
                        $.fn.General_ShowNotification({message: 'Please try again after sometime.'});
                        return;
                    }

                    if (data.success == '1') {
                        var chkData1 = data.hasOwnProperty("encString");
                        var chkData2 = data.hasOwnProperty("timestamp");
                        var chkData3 = data.hasOwnProperty("otpValue");
                        
                        if(!chkData1 || !chkData2 || !chkData3) {
                            $().General_ShowErrorMessage({type: 'error', message: 'Invalid Request'});
                            return;
                        }
                        var encString = data.encString;
                        var randomNo = data.timestamp;
                        var checkStr = atob(encString);
                        var checkOtpId = atob(data.otpValue);

                        // if(checkOtpId != postData.mobileOtpId) {
                        //     $().General_ShowErrorMessage({type: 'error', message: 'Invalid Request'});
                        //     return;
                        // }

                        var current = Math.floor(new Date().getTime() / 1000);
                        var fiveMinAgo = current - 100;

                        if(randomNo < fiveMinAgo || randomNo != checkStr) {
                            $().General_ShowErrorMessage({type: 'error', message: 'Invalid Request'});
                            return;
                        }
                        else {
                            $('.resend__otp').addClass('d-none');
                            $modal.modal('hide');
                            RegistrationV2Controller.saveBasicDetail();
                        }

                    } else {
                        $.each(data.errors, function (key, val) {
                            $(".field-" + formId + "-" + key).addClass('has-error').find('p').text(val);
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('.submitClass').prop('disabled', false).html('Submit');
                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    $('.submitClass').prop('disabled', true).html('Please Wait...');
                    $().showScreenLoader();

                    if(validateOTPCounter >= 5) {
                        $.fn.General_ShowNotification({message: 'Please try again after sometime.'});
                        jqXHR.abort();
                    }
                },
                complete: function (jqXHR, textStatus) {
                    $('.submitClass').prop('disabled', false).html('Submit');
                    $().hideScreenLoader();

                }
            });
        },

        saveBasicDetail: function () {

            var form = $('#basicDetailsForm');
            var action = form.attr('action');

            $.ajax({
                url: baseHttpPath + action,
                method: 'post',
                async: true,
                data: form.serialize(),
                success: function (data) {
                    if (data.emailValidated == "1") {
                        $('#emailOtp').closest('div.form-group').addClass('disabled');
                        $('#registrationform-email').closest('div.form-group').addClass('disabled');
                        $('#registrationform-is_email_verified').val(1);
                        $('#resendEmail').addClass('d-none');
                    } else {
                        $('#emailOtp').closest('div.form-group').removeClass('has-success').addClass('has-error');
                        $('#emailMessage').addClass('help-block-error').html('Email OTP validated Failed .');
                    }

                    if (data.mobileValidated == "1") {
                        $('#mobileOtp').closest('div.form-group').addClass('disabled');
                        $('#registrationform-mobile').closest('div.form-group').addClass('disabled');
                        $('#registrationform-is_mobile_verified').val(1);
                        $('#resendMobile').addClass('d-none');
                    } else {
                        $('#mobileOtp').closest('div.form-group').removeClass('has-success').addClass('has-error');
                        $('#mobileMessage').addClass('help-block-error').html('Mobile OTP validated Failed.');
                    }

                    if ($('#emailOtp').closest('div.form-group').hasClass('disabled') && $('#mobileOtp').closest('div.form-group').hasClass('disabled')) {
                        $("#otpModal").modal('hide');
                        $("#generateOTP").closest('.button--block').addClass('d-none');
                        $('#submitButton').html('Save & Next');
                    }
                    $('.form-group.disabled').children('input').attr('readonly', 'true');
                }
            });

        },

        validateOtp: function () {

            $('#otpModal').on('click', '#ModalSubmit', function (e) {
                e.preventDefault();
                var mobileOtp = $('#mobileOtp').val();
                var mobileOtpId = $('#otpModal').data('mobile');

                if (!mobileOtp) {
                    return;
                }

                $.ajax({
                    url: baseHttpPath + '/api/registration/validate-otp',
                    method: 'post',
                    data: {mobileOtp: mobileOtp, mobileOtpId: mobileOtpId},
                    success: function (response) {
                        if (response.success == 1) {
                            $("#otpModal").modal('hide');
                            $("#generateOTP").closest('.button--block').addClass('d-none');
                            $('#submitButton').html('Save & Next');
                        } else {
                            $.each(response.errors, function (key, val) {
                                $(".field-verifyotpform-" + key).addClass('has-error').find('p').text(val);
                            });
                        }
                    }
                });
            });
        },
        resendOtp: function () {
            $('#otpModal').on('click', '.resend__otp', function () {

                var name = $('#registrationform-name').val();
                var data = {};
                data.name = name;
                var email = $('#registrationform-email').val();
                data.email = email;
                var mobile = $('#registrationform-mobile').val();
                data.mobile = mobile;

                $.ajax({
                    url: baseHttpPath + '/api/registration/re-send-otp',
                    method: 'post',
                    data: data,
                    success: function (data) {
                        if (data.status == 1) {
                            if (data.mobileOtpId) {
                                $('#mobileOtpId').val(data.mobileOtpId);
                                $('#mobileOtp').closest('div.form-group').removeClass('has-error').addClass('has-success');
                                $('#mobileOtp').val('');
                                $('#mobileMessage').removeClass('help-block-error').html('OTP has been resended successfully');
                                $("#resendMobile").addClass('d-none');
                            }
                            RegistrationV2Controller.otpClock();
                            $.fn.formSanitization();

                            $.fn.General_ShowNotification({message: 'OTP resent successfully.'});
                        } else {
                            $().General_ShowErrorMessage({type: 'error', message: data.message});
                        }
                    }
                });

            });
        },
        otpClock: function () {
            if ($('#clockdiv').length > 0) {
                // 10 minutes from now
                var time_in_minutes = $('#collapseOne').data('time');
                var current_time = Date.parse(new Date());
                var deadline = new Date(current_time + time_in_minutes * 60 * 1000);
                function time_remaining(endtime) {
                    var t = Date.parse(endtime) - Date.parse(new Date());
                    var seconds = Math.floor((t / 1000) % 60);
                    var minutes = Math.floor((t / 1000 / 60) % 60);
                    var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
                    var days = Math.floor(t / (1000 * 60 * 60 * 24));
                    return {'total': t, 'days': days, 'hours': hours, 'minutes': minutes, 'seconds': seconds};
                }
                function run_clock(id, endtime) {
                    var clock = document.getElementById(id);
                    function update_clock() {
                        var t = time_remaining(endtime);
                        clock.innerHTML = t.minutes + ':' + t.seconds + ' minutes';
                        if (t.total <= 0) {
                            clearInterval(timeinterval);
                        }
                    }
                    update_clock(); // run function once at first to avoid delay
                    var timeinterval = setInterval(update_clock, 1000);
                }
                run_clock('clockdiv', deadline);
            }
        },
        getState: function (prefix) {
            $('.' + prefix + 'country').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var countrycode = elem.val();
                if (countrycode === "") {
                    $('.' + prefix + 'district, .' + prefix + 'state').val('');
                    $('.' + prefix + 'district, .' + prefix + 'state').trigger("chosen:updated");
                    return;
                }

                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/location/get-state',
                    dataType: 'json',
                    data: {countrycode: countrycode, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $('.' + prefix + 'state').html(data.template);
                            $('.' + prefix + 'state').trigger("chosen:updated");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $().ShowFlashMessages({type: 'error', message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $().showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $().hideScreenLoader();
                    }
                });
            });
        },
        getDistrict: function (prefix) {

            $('.' + prefix + 'state').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var statecode = elem.val();
                if (statecode === "") {
                    $('.' + prefix + 'district').val('');
                    $('.' + prefix + 'district').trigger("chosen:updated");
                    return;
                }

                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/location/get-district',
                    dataType: 'json',
                    data: {statecode: statecode, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $('.' + prefix + 'district').html(data.template);
                            $('.' + prefix + 'district').trigger("chosen:updated");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $.fn.ShowFlashMessages({type: 'error', message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $.fn.showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $.fn.hideScreenLoader();
                    }
                });
            });
        },
        getTehsil: function (prefix) {
            $('.' + prefix + 'district').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var districtcode = elem.val();
                if (districtcode === "") {
                    $('.' + prefix + 'tehsil').val('');
                    $('.' + prefix + 'tehsil').trigger("chosen:updated");
                    return;
                }

                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/location/get-tehsil',
                    dataType: 'json',
                    data: {districtcode: districtcode, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $('.' + prefix + 'tehsil').html(data.template);
                            $('.' + prefix + 'tehsil').trigger("chosen:updated");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $.fn.ShowFlashMessages({type: 'error', message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $.fn.showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $.fn.hideScreenLoader();
                    }
                });
            });
        },
        geoLocation: function () {
            $('#captureGeoLocation').on('click', function (e) {
                e.preventDefault();
                var elem = $(this);
                var geoAddress = "";
                var address = $('#basicdetailform-centre_address').val();
                if (typeof address !== "undefined" && address !== "") {
                    geoAddress += address;
                }
                var country = $('#basicdetailform-centre_country :selected').text();
                if (typeof country !== "undefined" && country !== "") {
                    geoAddress += ' ' + country;
                }
                var state = $('#basicdetailform-centre_state :selected').text();
                if (typeof state !== "undefined" && state !== "") {
                    geoAddress += ' ' + state;
                }
                var district = $('#basicdetailform-centre_district :selected').text();
                if (typeof district !== "undefined" && district !== "") {
                    geoAddress += ' ' + district;
                }
                var postal = $('#basicdetailform-centre_pin_code').val();
                if (typeof postal !== "undefined" && postal !== "") {
                    geoAddress += ' ' + postal;
                }

                if (typeof geoAddress == "undefined" || geoAddress === "") {
                    $().General_ShowErrorMessage({message: 'Oops! look like address can not be empty.'});
                    return;
                }

                $(elem).html('Please Wait...');
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    'address': geoAddress
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        $('#basicdetailform-latitude').val(results[0].geometry.location.lat());
                        $('#basicdetailform-longitude').val(results[0].geometry.location.lng());
                        $(elem).html('GEO LOCATE');
                    } else {
                        $().General_ShowErrorMessage({message: 'Latitude & Longitude not found. Please change address and try again.'});
                        $(elem).html('GEO LOCATE');
                    }
                });
            });
        },
        getQualificationSubject: function (prefix) {
            $('.' + prefix + 'degree').on('change', function (e) {

                e.preventDefault();
                var elem = $(this);
                var degreeId = elem.val();
                if (degreeId === "") {
                    $('.' + prefix + 'subject').val('');
                    $('.' + prefix + 'subject').trigger("chosen:updated");
                    return;
                }

                $(".js-course").addClass("hide");
                if (degreeId == 686) {
                    $(".js-course").removeClass("hide");
                }

            });
        },
        getQualificationDegree: function (prefix) {
            $('.' + prefix + 'qualificationType').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var id = elem.val();
                if (id === "") {
                    $('.' + prefix + 'degree').val('');
                    $('.' + prefix + 'degree').trigger("chosen:updated");
                    return;
                }

                $.ajax({
                    type: 'post',
                    url: baseHttpPath + '/api/registration/get-qualification-degree',
                    dataType: 'json',
                    data: {id: id, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $('.' + prefix + 'degree').html(data.template);
                            $('.' + prefix + 'degree').trigger("chosen:updated");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $.fn.ShowFlashMessages({type: 'error', message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $.fn.showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $.fn.hideScreenLoader();
                    }
                });
            });
        },
        getBoardUniversity: function (prefix) {
            $('.' + prefix + 'state').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var statecode = elem.val();
                if (statecode === "") {
                    $('.' + prefix + 'university').val('');
                    $('.' + prefix + 'university').trigger("chosen:updated");
                    return;
                }

                var qualificationType = $(".qualificationType").val();
                $(".js-universitySection").removeClass("hide");
                if (qualificationType == 25) {
                    $(".js-universitySection").addClass("hide");
                }
                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/registration/get-university',
                    dataType: 'json',
                    data: {statecode: statecode, qualificationType: qualificationType, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $('.' + prefix + 'university').html(data.template);
                            $('.' + prefix + 'university').trigger("chosen:updated");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $.fn.ShowFlashMessages({type: 'error', message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $.fn.showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $.fn.hideScreenLoader();
                    }
                });
            });
        },
        qualification: function () {
            $("#registrationform-board_university").off('change').on('change', function (e) {
                var board = $(this).val();
                if (typeof board === "undefined" || board === "") {
                    return;
                }
                $(".js-otherboard").addClass("hide");
                if (board == 1184) {
                    $(".js-otherboard").removeClass("hide");
                }
            });
        },
        getBoardUniversityByQualification: function (prefix) {
            $('.' + prefix + 'Type').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var statecode = elem.val();
                if (statecode === "") {
                    $('.' + prefix + 'university').val('');
                    $('.' + prefix + 'university').trigger("chosen:updated");
                    return;
                }

                var qualificationType = $(".qualificationType").val();
                $(".js-universitySection").removeClass("hide");
                if (qualificationType == 25) {
                    $(".js-universitySection").addClass("hide");
                }
                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/registration/get-university',
                    dataType: 'json',
                    data: {statecode: statecode, qualificationType: qualificationType, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $('.' + prefix + 'university').html(data.template);
                            $('.' + prefix + 'university').trigger("chosen:updated");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $.fn.ShowFlashMessages({type: 'error', message: jqXHR.responseText});
                    },
                    beforeSend: function (jqXHR, settings) {
                        $.fn.showScreenLoader();
                    },
                    complete: function (jqXHR, textStatus) {
                        $.fn.hideScreenLoader();
                    }
                });
            });
        },
        showHideInputs: function (prefix) {
            $('.' + prefix + 'selectAttr').change(function () {
                $(this).find("option:selected").each(function () {
                    var optionValue = $(this).text().toLowerCase();
                    var boxclass = prefix + optionValue;
                    if (optionValue) {
                        $('.' + prefix).not("." + boxclass).addClass('hide');
                        $('.' + prefix).not("." + boxclass).find('input').val('');
                        $('.' + prefix).not("." + boxclass).find('select').val('');
                        $('.' + prefix).not("." + boxclass).find('select').trigger("chosen:updated");
                        $("." + boxclass).removeClass('hide')

                    } else {
                        $("." + boxclass).addClass('hide');
                        $("." + boxclass).find('input').val('');
                        $("." + boxclass).find('select').val('');
                        $("." + boxclass).find('select').trigger("chosen:updated");
                    }
                });
            }).change();
        },
        finalSaveAttendance: function () {
            $('.finalSaveAttendance').on('click', function () {
                var message;
                message = "Do you really want to final save student(s) attendance ?";
                bootbox.confirm({
                    title: "Confirm",
                    message: message,
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'button blue small'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'button grey small'
                        }
                    },
                    callback: function (result) {
                        if (result == true) {
                            $.ajax({
                                type: 'POST',
                                url: baseHttpPath + '/exam/attendance/freeze-attendance',
                                dataType: 'json',
                                data: {_csrf: yii.getCsrfToken()},
                                success: function (data, textStatus, jqXHR) {
                                    if (data.success == "1") {
                                        window.location = window.location;
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                                },
                                beforeSend: function (jqXHR, settings) {
                                    $().showScreenLoader();
                                },
                                complete: function (jqXHR, textStatus) {
                                    $().hideScreenLoader();
                                }
                            });
                        }
                    }
                });
            });
        },
        payment: function () {
            $('#reviewform-preference1').on('change', function () {
                var pref1 = $(this).val();
                if (!pref1) {
                    return;
                }
                var pref2 = $('#reviewform-preference2').val();
                if (pref2 && pref2 == pref1) {
                    $('#reviewform-preference1').val('');
                    $('#reviewform-preference1').chosen().trigger("chosen:updated");
                    bootbox.alert('You have already selected this district in preference 2');
                }
            });

            $('#reviewform-preference2').on('change', function () {
                var pref2 = $(this).val();
                if (!pref2) {
                    return;
                }
                var pref1 = $('#reviewform-preference1').val();
                if (pref1 && pref1 == pref2) {
                    $('#reviewform-preference2').val('');
                    $('#reviewform-preference2').chosen().trigger("chosen:updated");
                    bootbox.alert('You have already selected this district in preference 1');
                }
            });

            $('#payNow').on('click', function (e) {
                if ($('#ReviewDetailForm').find('.has-error').length) {
                    alert('error');
                    return false;
                }

                e.preventDefault();
                var message;
                message = $('.payment__declaration').html();
                bootbox.confirm({
                    title: "Declaration",
                    message: message,
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: 'Agree & Pay',
                            className: 'button blue small'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'button grey small'
                        }
                    },
                    callback: function (result) {
                        if (result == true) {
                            $('#ReviewDetailForm').submit();
                        }
                    }
                });
            });

            $(".f-c__review-section input[name='paymentMethod']").click(function () {
                var value = $(this).val();
                var is_eservice = $("#registrationform-is_eservice").val();
                if (value == "CSC") {
                    $('#ReviewDetailForm').attr('action', '/payment/csc/request');
                    $('.info-csc-wallet').removeClass('hide');
                }
                else if (value == "RAZORPAY") {
                    $('#ReviewDetailForm').attr('action', '/payment/razor-pay/application');
                    $('.info-csc-wallet').addClass('hide');
                }
                else {
                    $('#ReviewDetailForm').attr('action', '/payment/cc-avenue/application');
                    $('.info-csc-wallet').addClass('hide');
                }
            });
        },
    };
}(jQuery));
RegistrationV2Controller.CreateUpdate = (function ($) {
    var attachEvents = function () {

//        RegistrationV2Controller.getState('centre');
//        RegistrationV2Controller.getDistrict('centre');
//        
//        RegistrationV2Controller.getState('sup');
//        RegistrationV2Controller.getDistrict('sup');
//        
//        RegistrationV2Controller.getState('postoffice');
//        RegistrationV2Controller.getDistrict('postoffice');
//        
//        RegistrationV2Controller.getState('');
//        RegistrationV2Controller.getDistrict('');
//        
//        RegistrationV2Controller.getState('bank');
//        RegistrationV2Controller.getDistrict('bank');

        RegistrationV2Controller.sendOtp();

        RegistrationV2Controller.priority();
        RegistrationV2Controller.checkStudyCenter();
        RegistrationV2Controller.geoLocation();
        RegistrationV2Controller.finalSaveAttendance();
        RegistrationV2Controller.payment();

//        $(".exam__date").datepicker({
//            format: 'dd-mm-yyyy',
//            autoclose: true
//        });

        $('.reviewFormBtn').on('click', function (e) {
            var elem = $(this);
            var guid = elem.data('guid');
            if (typeof guid === "undefined" || guid === "") {
                return;
            }

            bootbox.confirm({
                title: "Confirm",
                message: 'Do you really want to submit exam centre form. Make sure you fill correct information at this time otherwise your application will get rejected. You wont be able to update thier application data.',
                className: "modal__wrapper",
                callback: function (result) {
                    if (result == true) {
                        $.ajax({
                            type: 'POST',
                            url: baseHttpPath + '/api/registration/submit',
                            dataType: 'json',
                            data: {guid: guid, _csrf: yii.getCsrfToken()},
                            success: function (data, textStatus, jqXHR) {
                                if (data.success == "1") {
                                    window.location = baseHttpPath + '/registration/thank-you';
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                $().General_ShowErrorMessage({message: jqXHR.responseText});
                            },
                            beforeSend: function (jqXHR, settings) {
                                $().showScreenLoader();
                            },
                            complete: function (jqXHR, textStatus) {
                                $().hideScreenLoader();
                            }
                        });
                    }
                }
            });
        });

        $("#registrationform-is_criminal_case").on("change", function () {
            var value = $(this).val();

            if (value == "0") {
                $(".criminalproceeding").addClass("hide");
            }
        });
    };

    return {
        init: function () {
            attachEvents();

        }
    };
}(jQuery));


RegistrationV2Controller.UploadDocuments = (function ($) {
    var attachEvents = function () {

        uploadfiles();
        RegistrationV2Controller.deleteMedia();

    };
    var uploadfiles = function () {

        $('a.uploadPhoto').uploadFile({
            cloudUpload: true,
            addRemoveLinks: false,
            maxImage: 1,
            acceptedFiles: ".jpg,.jpeg,.png",
            maxFilesize: 0.1,
            containerId: 'uploadPhoto',
            onSuccess: function (file, response) {
                if (response.success == "1") {

//                    setTimeout(function () {
//                        $.get(baseHttpPath + '/api/upload-file/crop-media-modal?mediaId='+media.orig, function (data) {
//                            $('#cropImageModal').html(data).modal('show');
//                        });
//                    }, 500);
                    var media = response.media;

                    var list = ['jpg', 'jpeg', 'png'];
                    var image = (list.indexOf(response.extension) != -1) ? 1 : 0;
                    var pdf = (response.extension === "pdf") ? 1 : 0;
                    var doc = (response.extension === "doc") ? 1 : 0;
                    var docx = (response.extension === "docx") ? 1 : 0;
                    var xls = (response.extension === "xls") ? 1 : 0;
                    var source = $("#single-upload-media-template").html();
                    var template = Handlebars.compile(source);
                    var html = template({media: response.cdnPath, id: media.orig, guid: media.guid, file: response.fileName, image: image, pdf: pdf, doc: doc, docx: docx, xls: xls});

                    $('.uploadPhotoContainer').addClass('hide');
                    $('.uploadedPhotoContainer').removeClass('hide');
                    $('.uploadedPhotoContainer').html(html);
                    $('.inputPhoto').val(media.orig);
                    $('#uploadImageModal').modal('hide');

                    RegistrationV2Controller.deleteMedia();

                } else {
                    $().General_ShowErrorMessage({message: 'While save file in remote cdn error.'});
                }
            },
        });
        $('a.uploadSignature').uploadFile({

            cloudUpload: true,
            addRemoveLinks: false,
            maxImage: 1,
            acceptedFiles: ".jpg,.jpeg,.png",
            maxFilesize: 0.05,
            containerId: 'uploadSignature',
            onSuccess: function (file, response) {

                if (response.success == "1") {
                    var media = response.media;

                    var list = ['jpg', 'jpeg', 'png'];
                    var image = (list.indexOf(response.extension) != -1) ? 1 : 0;
                    var pdf = (response.extension === "pdf") ? 1 : 0;
                    var doc = (response.extension === "doc") ? 1 : 0;
                    var docx = (response.extension === "docx") ? 1 : 0;
                    var xls = (response.extension === "xls") ? 1 : 0;
                    var source = $("#single-upload-media-template").html();
                    var template = Handlebars.compile(source);
                    var html = template({media: response.cdnPath, id: media.orig, guid: media.guid, file: response.fileName, image: image, pdf: pdf, doc: doc, docx: docx, xls: xls});

                    $('.uploadSignatureContainer').addClass('hide');
                    $('.uploadedSignatureContainer').removeClass('hide');
                    $('.uploadedSignatureContainer').html(html);
                    $('.inputSignature').val(media.orig);
                    $('#uploadImageModal').modal('hide');

                    RegistrationV2Controller.deleteMedia();

                } else {
                    $().General_ShowErrorMessage({message: 'While save file in remote cdn error.'});
                }
            }
        });
        $('a.uploadBirth').uploadFile({
            cloudUpload: true,
            addRemoveLinks: false,
            maxImage: 1,
            acceptedFiles: ".jpg,.jpeg,.png",
            maxFilesize: 0.05,
            containerId: 'uploadBirth',
            onSuccess: function (file, response) {
                if (response.success == "1") {
                    var media = response.media;

                    var list = ['jpg', 'jpeg', 'png'];
                    var image = (list.indexOf(response.extension) != -1) ? 1 : 0;
                    var pdf = (response.extension === "pdf") ? 1 : 0;
                    var doc = (response.extension === "doc") ? 1 : 0;
                    var docx = (response.extension === "docx") ? 1 : 0;
                    var xls = (response.extension === "xls") ? 1 : 0;
                    var source = $("#single-upload-media-template").html();
                    var template = Handlebars.compile(source);
                    var html = template({media: response.cdnPath, id: media.orig, guid: media.guid, file: response.fileName, image: image, pdf: pdf, doc: doc, docx: docx, xls: xls});

                    $('.uploadBirthContainer').addClass('hide');
                    $('.uploadedBirthContainer').removeClass('hide');
                    $('.uploadedBirthContainer').html(html);
                    $('.inputBirth').val(media.orig);
                    $('#uploadImageModal').modal('hide');

                    RegistrationV2Controller.deleteMedia();

                } else {
                    $().General_ShowErrorMessage({message: 'While save file in remote cdn error.'});
                }
            }
        });
        $('a.uploadCaste').uploadFile({
            cloudUpload: true,
            addRemoveLinks: false,
            maxImage: 1,
            acceptedFiles: ".jpg,.jpeg,.png",
            maxFilesize: 0.05,
            containerId: 'uploadCaste',
            onSuccess: function (file, response) {
                if (response.success == "1") {
                    var media = response.media;

                    var list = ['jpg', 'jpeg', 'png'];
                    var image = (list.indexOf(response.extension) != -1) ? 1 : 0;
                    var pdf = (response.extension === "pdf") ? 1 : 0;
                    var doc = (response.extension === "doc") ? 1 : 0;
                    var docx = (response.extension === "docx") ? 1 : 0;
                    var xls = (response.extension === "xls") ? 1 : 0;
                    var source = $("#single-upload-media-template").html();
                    var template = Handlebars.compile(source);
                    var html = template({media: response.cdnPath, id: media.orig, guid: media.guid, file: response.fileName, image: image, pdf: pdf, doc: doc, docx: docx, xls: xls});

                    $('.uploadCasteContainer').addClass('hide');
                    $('.uploadedCasteContainer').removeClass('hide');
                    $('.uploadedCasteContainer').html(html);
                    $('.inputCaste').val(media.orig);
                    $('#uploadImageModal').modal('hide');

                    RegistrationV2Controller.deleteMedia();

                } else {
                    $().General_ShowErrorMessage({message: 'While save file in remote cdn error.'});
                }
            }
        });
    }

    return {
        init: function () {
            attachEvents();

        }
    };
}(jQuery));

RegistrationV2Controller.DisabilityDetails = (function ($) {
    var attachEvents = function () {
        $("#registrationform-is_domiciled").on("change", function () {
            var elem = $(this);
            var isDomiciled = elem.val();

            if (isDomiciled == 0) {
                $(".js-DomicileOfUttarakhand-yes").addClass('hide');
                $(".js-DomicileOfUttarakhand-no").removeClass('hide');
                $('#registrationform-disability_id option[value=25]').attr('selected', 'selected');
                //$('#registrationform-disability_id').prop('disabled', 'disabled');
                $('#registrationform-disability_id').chosen().trigger("chosen:updated");
            } else {
                $(".js-DomicileOfUttarakhand-yes").removeClass('hide');
                $(".js-DomicileOfUttarakhand-no").addClass('hide');

                //$('#registrationform-disability_id').prop('disabled', false);
                $('#registrationform-disability_id').chosen().trigger("chosen:updated");
            }
        });

        $("#registrationform-disability_id").on("change", function () {
            var elem = $(this);
            var disabilityId = elem.val();

            if (disabilityId != 25) {
                $(".js-disability-yes").removeClass("hide");
            } else {
                $(".js-disability-yes").addClass("hide");
            }
        });

        $("#registrationform-is_high_school_passed_from_uttarakhand").on("change", function () {
            var elem = $(this);
            var value = elem.val();

            if (value == 1) {
                $(".js-passedHighSchool-yes").removeClass("hide");
            } else {
                $(".js-passedHighSchool-yes").addClass("hide");
            }
        });

        $("#registrationform-is_parents_non_transferable_from_utknd").on("change", function () {
            var elem = $(this);
            var value = elem.val();

            if (value == 1) {
                $(".js-parentsNonTransferable-yes").removeClass("hide");
            } else {
                $(".js-parentsNonTransferable-yes").addClass("hide");
            }
        });

        $("#registrationform-social_category_id").on("change", function () {
            var elem = $(this);
            var value = elem.val();

            $(".js-categoryOBC-yes").addClass("hide");
            $(".js-category-yes").addClass("hide");
            if (value == 15) {
                $(".js-categoryOBC-yes").removeClass("hide");
                $(".js-category-yes").removeClass("hide");
            } else if (value == 12 || value == 13 || value == 14) {
                $(".js-category-yes").removeClass("hide");
            }
        });
    };

    return {
        init: function () {
            attachEvents();

        }
    };
}(jQuery));


RegistrationV2Controller.PersonalDetails = (function ($) {
    var attachEvents = function () {

        $('.js-birthDate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            //startDate: '-58y',
            startDate: new Date(dobStartDate),
            endDate: new Date(dobEndDate),
            //endDate: '-18y',
            //maxDate: new Date($("#dateRange").data("max"))
            maxDate: $.now()
        }).on('changeDate', function (selected) {
            console.log($(this).val());
            getAge($(this).val());
        });

        function getAge(dateVal) {
            $.ajax({
                url: baseHttpPath + '/api/registration/get-calculate-age',
                method: 'post',
                async: false,
                data: {dob: dateVal, date: ageCalculateDate},
                success: function (response) {
                    if (response.success == "1") {
                        $('.js-age').attr('placeholder', response.age);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $().General_ShowErrorMessage({type: 'error', message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    $().showScreenLoader();
                },
                complete: function (jqXHR, textStatus) {
                    $().hideScreenLoader();
                }
            });

        }
    };

    return {
        init: function () {
            attachEvents();

        }
    };
}(jQuery));