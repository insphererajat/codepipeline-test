var LocationController = (function ($) {
    return {
        state: function () {
            LocationController.State.init();
        },
        district: function () {
            LocationController.District.init();
        },
        tehsil: function () {
            LocationController.Tehsil.init();
        },
        getState: function () {
            $('.country').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var countrycode = elem.val();
                if (countrycode === "") {
                    $(".district,.state").val('');
                    $(".district,.state").trigger("chosen:updated");
                    return;
                }

                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/location/get-state',
                    dataType: 'json',
                    data: {countrycode: countrycode, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $('.state').html(data.template);
                            $(".state").trigger("chosen:updated");
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
        getDistrict: function () {
            $('.stateCode').on('change', function (e) {

                e.preventDefault();
                var elem = $(this);
                var code = elem.val();

                if (code === "") {

                    $(".districtCode").val('');
                    $(".districtCode").trigger("chosen:updated");
                    return;
                }

                $.ajax({
                    type: 'post',
                    url: baseHttpPath + '/common/location/get-district',
                    dataType: 'json',
                    data: {statecode: code, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $(".districtCode").html(data.template);
                            $(".districtCode").trigger("chosen:updated");
 
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        //$.fn.ShowFlashMessages({type: 'error', message: jqXHR.responseText});
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
        getBlock: function () {
            $('.district').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var districtCode = elem.val();
                if (districtCode === "") {
                    $(".block").val('');
                    $(".block").trigger("chosen:updated");
                    return;
                }

                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/location/get-block',
                    dataType: 'json',
                    data: {districtCode: districtCode, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $(".block").html(data.template);
                            $(".block").trigger("chosen:updated");
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
        getVillage: function () {
            $('.block').on('change', function (e) {
                e.preventDefault();
                var elem = $(this);
                var blockCode = elem.val();
                if (blockCode === "") {
                    $(".village").val('');
                    $(".village").trigger("chosen:updated");
                    return;
                }

                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/location/get-village',
                    dataType: 'json',
                    data: {blockCode: blockCode, _csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
                            $(".village").html(data.template);
                            $(".village").trigger("chosen:updated");
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
        fromDatePicker: function () {
            $(".from__date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            }).on('changeDate', function (selected) {
                var startDate = new Date(selected.date.valueOf());
                $('.to__date').datepicker('setStartDate', startDate);
            }).on('clearDate', function (selected) {
                $('.to__date').datepicker('setStartDate', null);
            });
        },
        toDatePicker: function () {
            $(".to__date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            }).on('changeDate', function (selected) {
                var endDate = new Date(selected.date.valueOf());
                $('.from__date').datepicker('setEndDate', endDate);
            }).on('clearDate', function (selected) {
                $('.from__date').datepicker('setEndDate', null);
            });
        }
    };
}(jQuery));
LocationController.State = (function ($) {
    var attachEvents = function () {

        createState();
        updateState();
        deleteState();
        updateStatus();

        $(document).on('pjax:complete', function (event, xhr, textStatus, options) {
            createState();
            updateState();
            deleteState();
            updateStatus();
        });

    };


    var createState = function () {

        $('body').off().on('submit', '#newstateForm', function (e) {
            e.preventDefault();
            var $modal = $('#newStateModal');
            saveAjaxForm($(this), $modal, 0, 'mststate');
        });
    };

    var updateState = function () {
        $('.updateState').on('click', function (e) {
            
            e.preventDefault();
            var elem = $(this);
            var url = elem.data('url');
            if (typeof url === 'undefined' || url === "") {
                return;
            }

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function (data) {
                    if (data.success == '1') {

                        var $modal = $('#editStateModal');
                        $modal.html(data.template);
                        $modal.modal('show');
                        $('.chzn-select').chosen().trigger("chosen:updated");
                        $('body').off().on('submit', '#editstateForm', function(e) {
                        
                            e.preventDefault();
                            saveAjaxForm($(this), $modal, 1 ,'mststate');
                            return false;
                        });
                        
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    elem.find('.fa').addClass('fa-pencil').removeClass('fa-spin fa-spinner');
                    //$().General_ShowErrorMessage({message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    elem.find('.fa').removeClass('fa-pencil').addClass('fa-spin fa-spinner');
                },
                complete: function (jqXHR, textStatus) {
                    elem.find('.fa').addClass('fa-pencil').removeClass('fa-spin fa-spinner');
                }
            });
        });
    };

    var saveAjaxForm = function (elem, $modal, isUpdate ,formId) {
        
        var url = elem.attr("action");
        
        var postData = elem.serialize();

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
                    var message = (isUpdate) ? 'Record updated successfully.' : 'Record inserted successfully.';
                    $.fn.ShowFlashMessages({message: message, type: 'success'});
                    $(elem).trigger('reset');
                    $modal.modal('hide');
                    $.pjax.reload({container: '#dataList', timeout: false});
                } else {

                    $.each(data.errors, function (key, val) {
                        $(".field-" + formId + "-" + key).removeClass('has-success');
                        $(".field-" + formId + "-" + key).addClass('has-error').find('p').text(val);
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('.submitButton').prop('disabled', false).html('Save');
                $().General_ShowErrorMessage({message: jqXHR.responseText});
            },
            beforeSend: function (jqXHR, settings) {
                $('.submitButton').prop('disabled', true).html('Please Wait...');
            },
            complete: function (jqXHR, textStatus) {
                $('.submitButton').prop('disabled', false).html('Save');
            }
        });
    };

    var deleteState = function () {
        $('.deleteState').on('click', function (e) {
            e.preventDefault();
            var elem = $(this);
            var url = elem.data('url');
            if (typeof url === 'undefined' || url === '') {
                return;
            }

            bootbox.confirm({
                title: "Confirm",
                message: "Do you really want to delete this state. (s)?",
                className: "modal__wrapper",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'button blue'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'button grey'
                    }
                },
                callback: function (result) {
                    if (result === true) {
                        $.ajax({
                            type: 'post',
                            url: url,
                            dataType: 'json',
                            success: function (data, textStatus, jqXHR) {
                                if (data.success == "1") {
                                     $.fn.ShowFlashMessages({message : 'Record deleted successfully' , type : 'success'});
                                    $.pjax.reload({container: '#dataList', timeout: false});
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                elem.find('.fa').addClass('fa-trash').removeClass('fa-spin fa-spinner');
                                $().General_ShowErrorMessage({message: jqXHR.responseText});
                            },
                            beforeSend: function (jqXHR, settings) {
                                elem.find('.fa').removeClass('fa-trash').addClass('fa-spin fa-spinner');
                            },
                            complete: function (jqXHR, textStatus) {
                                elem.find('.fa').removeClass('fa-spin fa-spinner').addClass('fa-trash');
                            }
                        });
                    }
                }
            });
        });
    };

    var updateStatus = function (e) {
        $('.updateStatus').on('click', function (e) {
            e.preventDefault();
            var elem = $(this);
            var url = elem.data('url');
            if (typeof url === 'undefined' || url === '') {
                return;
            }

            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.success == "1") {
                        elem.data('status', data.status);
                        var html = (data.status == "1") ? "<a href='javascript:;' title='Active'><span class='badge badge-success'>Active</span><i class='fa fa-spin fa-spinner hide'></i></a>" : "<a href='javascript:;' title='Inactive'><span class='badge badge-danger'>Inactive</span><i class='fa fa-spin fa-spinner hide'></i></a>";
                        elem.html(html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    elem.find('.badge').removeClass('hide');
                    elem.find('.fa').addClass('hide');
                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    elem.find('.badge').addClass('hide');
                    elem.find('.fa').removeClass('hide');
                },
                complete: function (jqXHR, textStatus) {
                    elem.find('.badge').removeClass('hide');
                    elem.find('.fa').addClass('hide');
                }
            });
        });
    };


    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));
LocationController.District = (function ($) {
    var attachEvents = function () {

        $('#mstdistrict-state_code').on('change', function (e) {
            //$('#mstdistrict-code').val($(this).val());
        });
        
        createDistrict();
        updateDistrict();
        deleteDistrict();
        updateStatus();

        $(document).on('pjax:complete', function (event, xhr, textStatus, options) {
            createDistrict();
            updateDistrict();
            deleteDistrict();
            updateStatus();
        });

    };


    var createDistrict = function () {

        $('body').off().on('submit', '#newdistrictForm', function (e) {
            e.preventDefault();
            var $modal = $('#newDistrictModal');
            saveAjaxForm($(this), $modal, 0, 'mstdistrict');
        });

    };

    var updateDistrict = function () {
        $('.updateDistrict').on('click', function (e) {
            
            e.preventDefault();
            var elem = $(this);
            var url = elem.data('url');
            if (typeof url === 'undefined' || url === "") {
                return;
            }

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function (data) {
                    if (data.success == '1') {

                        var $modal = $('#editDistrictModal');
                        $modal.html(data.template);
                        $modal.modal('show');
                        $('.chzn-select').chosen().trigger("chosen:updated");
                        $('body').off().on('submit', '#editdistrictForm', function(e) {
                        
                            e.preventDefault();
                            saveAjaxForm($(this), $modal, 1 ,'mstdistrict');
                            return false;
                        });
                        
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    elem.find('.fa').addClass('fa-pencil').removeClass('fa-spin fa-spinner');
                    //$().General_ShowErrorMessage({message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    elem.find('.fa').removeClass('fa-pencil').addClass('fa-spin fa-spinner');
                },
                complete: function (jqXHR, textStatus) {
                    elem.find('.fa').addClass('fa-pencil').removeClass('fa-spin fa-spinner');
                }
            });
        });
    };

    var saveAjaxForm = function (elem, $modal, isUpdate ,formId) {
        
        var url = elem.attr("action");
        
        var postData = elem.serialize();

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
                    var message = (isUpdate) ? 'Record updated successfully.' : 'Record inserted successfully.';
                     $.fn.ShowFlashMessages({message : message , type : 'success'});
                     $(elem).trigger('reset');
                     $modal.modal('hide');
                } else {

                    $.each(data.errors, function (key, val) {
                        $(".field-" + formId + "-" + key).addClass('has-error').find('p').text(val);
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('.submitButton').prop('disabled', false).html('Save');
                $().General_ShowErrorMessage({message: jqXHR.responseText});
            },
            beforeSend: function (jqXHR, settings) {
                $('.submitButton').prop('disabled', true).html('Please Wait...');
            },
            complete: function (jqXHR, textStatus) {
                $('.submitButton').prop('disabled', false).html('Save');
                 $.pjax.reload({container: '#dataList', timeout: false});
            }
        });
    };

    var deleteDistrict = function () {
        $('.deleteDistrict').on('click', function (e) {
            e.preventDefault();
            var elem = $(this);
            var url = elem.data('url');
            if (typeof url === 'undefined' || url === '') {
                return;
            }

            bootbox.confirm({
                title: "Confirm",
                message: "Do you really want to delete this district .(s)?",
                className: "modal__wrapper",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'button blue'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'button grey'
                    }
                },
                callback: function (result) {
                    if (result === true) {
                        $.ajax({
                            type: 'post',
                            url: url,
                            dataType: 'json',
                            success: function (data, textStatus, jqXHR) {
                                if (data.success == "1") {
                                  
                                    $.fn.ShowFlashMessages({message : 'Record deleted successfully' , type : 'success'});
                                    $.pjax.reload({container: '#dataList', timeout: false});
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                elem.find('.fa').addClass('fa-trash').removeClass('fa-spin fa-spinner');
                                $().General_ShowErrorMessage({message: jqXHR.responseText});
                            },
                            beforeSend: function (jqXHR, settings) {
                                elem.find('.fa').removeClass('fa-trash').addClass('fa-spin fa-spinner');
                            },
                            complete: function (jqXHR, textStatus) {
                                elem.find('.fa').removeClass('fa-spin fa-spinner').addClass('fa-trash');
                            }
                        });
                    }
                }
            });
        });
    };

    var updateStatus = function (e) {
        $('.updateStatus').on('click', function (e) {
            e.preventDefault();
            var elem = $(this);
            var url = elem.data('url');
            if (typeof url === 'undefined' || url === '') {
                return;
            }

            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.success == "1") {
                        elem.data('status', data.status);
                        var html = (data.status == "1") ? "<a href='javascript:;' title='Active'><span class='badge badge-success'>Active</span><i class='fa fa-spin fa-spinner hide'></i></a>" : "<a href='javascript:;' title='Inactive'><span class='badge badge-danger'>Inactive</span><i class='fa fa-spin fa-spinner hide'></i></a>";
                        elem.html(html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    elem.find('.badge').removeClass('hide');
                    elem.find('.fa').addClass('hide');
                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    elem.find('.badge').addClass('hide');
                    elem.find('.fa').removeClass('hide');
                },
                complete: function (jqXHR, textStatus) {
                    elem.find('.badge').removeClass('hide');
                    elem.find('.fa').addClass('hide');
                }
            });
        });
    };


    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));
LocationController.Tehsil = (function ($) {
    var attachEvents = function () {

        $('#mstdistrict-state_code').on('change', function (e) {
            //$('#mstdistrict-code').val($(this).val());
        });
        
        createTehsil();
        updateTehsil();
        deleteTehsil();
        updateStatus();

        $(document).on('pjax:complete', function (event, xhr, textStatus, options) {
            createTehsil();
            updateTehsil();
            deleteTehsil();
            updateStatus();
        });

    };


    var createTehsil = function () {

        $('body').off().on('submit', '#newtehsilForm', function (e) {
            e.preventDefault();
            var $modal = $('#newTehsilModal');
            saveAjaxForm($(this), $modal, 0, 'msttehsil');
        });

    };

    var updateTehsil = function () {
        $('.updateTehsil').on('click', function (e) {
            
            e.preventDefault();
            var elem = $(this);
            var url = elem.data('url');
            if (typeof url === 'undefined' || url === "") {
                return;
            }

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function (data) {
                    if (data.success == '1') {

                        var $modal = $('#editTehsilModal');
                        $modal.html(data.template);
                        $modal.modal('show');
                        $('.chzn-select').chosen().trigger("chosen:updated");
                        $('body').off().on('submit', '#editdistrictForm', function(e) {
                        
                            e.preventDefault();
                            saveAjaxForm($(this), $modal, 1 ,'mstdistrict');
                            return false;
                        });
                        
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    elem.find('.fa').addClass('fa-pencil').removeClass('fa-spin fa-spinner');
                    //$().General_ShowErrorMessage({message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    elem.find('.fa').removeClass('fa-pencil').addClass('fa-spin fa-spinner');
                },
                complete: function (jqXHR, textStatus) {
                    elem.find('.fa').addClass('fa-pencil').removeClass('fa-spin fa-spinner');
                }
            });
        });
    };

    var saveAjaxForm = function (elem, $modal, isUpdate ,formId) {
        
        var url = elem.attr("action");
        
        var postData = elem.serialize();

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
                    var message = (isUpdate) ? 'Record updated successfully.' : 'Record inserted successfully.';
                    $(elem).trigger('reset');
                    $modal.modal('hide');
                    $.fn.ShowFlashMessages({message: message, type: 'success'});

                } else {

                    $.each(data.errors, function (key, val) {
                        $(".field-" + formId + "-" + key).addClass('has-error').find('p').text(val);
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('.submitButton').prop('disabled', false).html('Save');
                $().General_ShowErrorMessage({message: jqXHR.responseText});
            },
            beforeSend: function (jqXHR, settings) {
                $('.submitButton').prop('disabled', true).html('Please Wait...');
            },
            complete: function (jqXHR, textStatus) {
                $('.submitButton').prop('disabled', false).html('Save');
                $.pjax.reload({container: '#dataList', timeout: false});
            }
        });
    };

    var deleteTehsil = function () {
        $('.deleteTehsil').on('click', function (e) {
            e.preventDefault();
            var elem = $(this);
            var url = elem.data('url');
            if (typeof url === 'undefined' || url === '') {
                return;
            }

            bootbox.confirm({
                title: "Confirm",
                message: "Do you really want to delete this district .(s)?",
                className: "modal__wrapper",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'button blue'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'button grey'
                    }
                },
                callback: function (result) {
                    if (result === true) {
                        $.ajax({
                            type: 'post',
                            url: url,
                            dataType: 'json',
                            success: function (data, textStatus, jqXHR) {
                                if (data.success == "1") {
                                  
                                    $.fn.ShowFlashMessages({message : 'Record deleted successfully' , type : 'success'});
                                    $.pjax.reload({container: '#dataList', timeout: false});
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                elem.find('.fa').addClass('fa-trash').removeClass('fa-spin fa-spinner');
                                $().General_ShowErrorMessage({message: jqXHR.responseText});
                            },
                            beforeSend: function (jqXHR, settings) {
                                elem.find('.fa').removeClass('fa-trash').addClass('fa-spin fa-spinner');
                            },
                            complete: function (jqXHR, textStatus) {
                                elem.find('.fa').removeClass('fa-spin fa-spinner').addClass('fa-trash');
                            }
                        });
                    }
                }
            });
        });
    };

    var updateStatus = function (e) {
        $('.updateStatus').on('click', function (e) {
            e.preventDefault();
            var elem = $(this);
            var url = elem.data('url');
            if (typeof url === 'undefined' || url === '') {
                return;
            }

            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data.success == "1") {
                        elem.data('status', data.status);
                        var html = (data.status == "1") ? "<a href='javascript:;' title='Active'><span class='badge badge-success'>Active</span><i class='fa fa-spin fa-spinner hide'></i></a>" : "<a href='javascript:;' title='Inactive'><span class='badge badge-danger'>Inactive</span><i class='fa fa-spin fa-spinner hide'></i></a>";
                        elem.html(html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    elem.find('.badge').removeClass('hide');
                    elem.find('.fa').addClass('hide');
                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                },
                beforeSend: function (jqXHR, settings) {
                    elem.find('.badge').addClass('hide');
                    elem.find('.fa').removeClass('hide');
                },
                complete: function (jqXHR, textStatus) {
                    elem.find('.badge').removeClass('hide');
                    elem.find('.fa').addClass('hide');
                }
            });
        });
    };


    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));
