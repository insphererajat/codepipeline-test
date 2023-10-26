var UserController = (function ($) {
    return {
        summary: function () {
            UserController.Summary.init();
        },
        permission: function () {
            UserController.Permission.init();
        },
        uploadMedia: function () {

            $('.uploadMedia').uploadFile({
                showGallery: true,
                onSuccess: function (file, response) {
                   if(response.success == "1") {
                        $('.js-userProfile').attr("src", response.media['cdnPath']);
                        $('.js-deleteUserProfileMedia').removeClass('hide');
                        $('.js-userProfile').closest('.cop-form__uploader--placeholder ').removeClass('hide');
                        $('#userform-profile_media_id').val(response.media['orig']);
                   }
                },
                onError: function () {
                    $().General_ShowErrorMessage({message: "Sorry, unable to upload profile picture due to some technical error."});
                }
            });
        },
        deleteMedia : function() { 
            $('.js-deleteUserProfileMedia').on('click', function (e) {
                e.preventDefault();

                var elem = $(this);
                var mediaId = $('#userform-profile_media_id').val();
                var userGuid = $('#userform-guid').val();
               
                if(typeof userGuid === "undefined" || userGuid === "") {
                    return;
                }
                if(typeof mediaId === "undefined" || mediaId === "") {
                    return;
                }
 
                bootbox.confirm({
                    title: "Confirm",
                    message: 'Do you really want to delete this media?',
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'c-button c-button-rounded c-button-success'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'c-button c-button-rounded c-button-inverse'
                        }
                    },
                    callback: function (result) {
                        if (result == true) {
                            $.ajax({
                                url    : baseHttpPath + '/user/delete-media',
                                type   : 'POST',
                                data   : {userGuid: userGuid,  mediaId: mediaId, _csrf: yii.getCsrfToken()},
                                success: function (data) {
                                    if(data.success == "1") {
                                        location.reload(true);
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $().General_ShowErrorMessage({message: jqXHR.responseText});
                                },
                                beforeSend: function (jqXHR, settings) {
                                
                                },
                                complete: function (jqXHR, textStatus) {
                                }
                        });
                    }
                    }
                });
             });
        },
        deleteUser: function () {
            $('.deleteTeamUsers').on('click', function (e) {
                e.preventDefault();
                var elem = $(this);
                var userId = $(elem).data('id');
                var teamGuid = $('#teamform-guid').val();
                if (typeof userId === "undefined" || userId === "") {
                    return;
                }
                if (typeof teamGuid === "undefined" || teamGuid === "") {
                    return;
                }

                bootbox.confirm({
                    title: "Confirm",
                    message: 'Do you really want to revoke this user?',
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'c-button c-button-rounded c-button-success'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'c-button c-button-rounded c-button-inverse'
                        }
                    },
                    callback: function (result) {
                        if (result == true) {
                            $.ajax({type: 'post',
                                url: baseHttpPath + '/api/team-permission/remove-user',
                                dataType: 'json',
                                data: {teamGuid: teamGuid, userId: userId, _csrf: yii.getCsrfToken()},
                                success: function (data, textStatus, jqXHR) {
                                    if (data.success == "1") {
                                        elem.remove();
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

                        }
                    }
                });
            });
        },
  
        individualPermission: function() {
            $('.permission-checkbox').on('change', function (e) {
                e.preventDefault();
                var guid = $("#userform-guid").val();
                if(typeof guid === "undefined" || guid === "") {
                    return;
                }

                $.ajax({type: 'post',
                    url: baseHttpPath + '/api/team-permission/add-permission',
                    dataType: 'json',
                    data: {permission: $(this).val(), guid: guid, type: "user",_csrf: yii.getCsrfToken()},
                    success: function (data, textStatus, jqXHR) {
                        if (data.success == "1") {
    
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
    };
}(jQuery));

UserController.Summary = (function ($) {
    var attachEvents = function () {
        UserController.uploadMedia();
        UserController.deleteMedia();

         
        $('.js-addMoreUserRow').on('click', function(){ 

            var formType = "UserForm";
            var type = "userform";
            if($('#userForm').data('type') == "ldap") {
                type = "ldapuserform";
                formType = 'LdapUserForm';
            }

            var networkId = $('#'+type+'-networks option:selected').val();
            var networkName = $('#'+type+'-networks option:selected').text();
            var roleId = $('#'+type+'-roles option:selected').val();
            var roleName = $('#'+type+'-roles option:selected').text();

            if(typeof networkId === "undefined" || networkId === "") {
                $().General_ShowErrorMessage({message: "Please select a network before adding user role."});
                return;
            }

            if(typeof roleId === "undefined" || roleId === "") {
                $().General_ShowErrorMessage({message: "Please select a role before adding user role."});
                return;
            }

            var hasNewRecord = true;
            $('.js-deleteUserRole').each(function(index , obj) {
                var addedNetworkId = $(obj).data('networkid');
                if(networkId == addedNetworkId) {
                    hasNewRecord = false;
                    $().General_ShowErrorMessage({message: "Sorry,  This network already attach with role."});
                    return;
                }
            });
            
            if(hasNewRecord) {
                var hiddenNetworkInput = "<input type='hidden' name='"+formType+"[networkRoles]["+networkId+"][network_id]' value='"+networkId+"'/>";
                var hiddenRoleInput = "<input type='hidden' name='"+formType+"[networkRoles]["+networkId+"][role_id]' value='"+roleId+"'/>";

                var template = '<tr ><td>'+networkName+""+hiddenNetworkInput+""+hiddenRoleInput+'</td><td>'+roleName+'</td><td><div class="action-bars"><a class="delete action-bars__link js-deleteUserRole" href="javascript:;" title="Deleted" data-networkid="'+networkId+'" data-roleid="'+roleId+'" data-newrecord="1"><i class="far fa-trash-alt"></i></a></div></td> </tr>';

                $('.js-userRoleData').append(template);
                $('.js-userRoleTable').removeClass('hide')
            }
        
        });

        $('.js-userRoleData').on('click', '.js-deleteUserRole', function(e) {
            e.preventDefault();
            var elem = $(this);

            var type = "userform";
            if($('#userForm').data('type') == "ldap") {
                type = "ldapuserform";
            }

            var networkId = elem.data('networkid');
            var roleId = elem.data('roleid');
            var hasNewRecord = elem.data('newrecord');
            var userGuid = $('#'+type+'-guid').val();

            if(typeof networkId === "undefined" || networkId === "" || typeof roleId === "undefined" || roleId === "") {
                $().General_ShowErrorMessage({message: "Sorry, You can not delete this network role."});
                return;
            }
            if(hasNewRecord) {
                elem.closest('tr').remove();
            }
            else {
                bootbox.confirm({
                    title: "Confirm",
                    message: 'Do you really want to revoke this role?',
                    className: "modal__wrapper",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'c-button c-button-rounded c-button-success'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'c-button c-button-rounded c-button-inverse'
                        }
                    },
                    callback: function (result) {
                        if (result == true) {
                            $.ajax({type: 'post',
                            url: baseHttpPath + '/user/delete-role',
                            dataType: 'json',
                            data: {networkId: networkId, roleId: roleId, userGuid: userGuid, _csrf: yii.getCsrfToken()},
                            success: function (data, textStatus, jqXHR) {
                                if (data.success == "1") {
                                    elem.closest('tr').remove();
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

                        }
                    }
                });
                //send ajax request
            }

        });
    };

    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));

UserController.Permission = (function ($) {
    var attachEvents = function () {
        UserController.deleteUser();
    
        $('.js-mulitUsers').SumoSelect({
            selectAll: true,
            okCancelInMulti: true, 
            okButtonCls: 'js-addUsers',
        });

        $(".btnOk").on("click", function() {
          
            var users = [];
            $(".js-mulitUsers option:selected").each(function(i) {
                users.push($(this).val());
                $(".js-mulitUsers")[0].sumo.unSelectItem(i);
            });

            if(users.length <= 0) {
                bootbox.alert("User(s) cannot be empty.");
                return;
            }
            var guid = $("#teamform-guid").val();

            $.ajax({type: 'post',
            url: baseHttpPath + '/api/team-permission/add-user',
            dataType: 'json',
            data: {userIds: users, guid: guid, _csrf: yii.getCsrfToken()},
            success: function (data, textStatus, jqXHR) {
                if (data.success == "1") {
                    if(users.length == data.errors.length ) {
                        var errors = "";
                        if(data.errors.length > 0) {
                            var j= 1;
                            for(var i in data.errors) {
                                errors +="<p style='color:red'>"+j+". "+data.errors[i]+"<p>";
                                j = j+1;
                            }
                        }
                        $.fn.General_ShowErrorMessage({
                            title: "Error",
                            message : errors,
                            eventCallback: function() {
                                location.reload(true);
                            }
                        });
                    }
                    else {
                        var total = users.length;
                        var failed = data.errors.length;
                        var  remain = total-failed;
                        var errors = "<p>"+remain +" Out of  "+total+" user(s) has been added successfully. </p>";
                        if(data.errors.length > 0) {
                            var j = 1;
                            for(var i in data.errors) {
                                errors +="<p style='color:red'>"+j+". "+data.errors[i]+"<p>";
                                j = j+1;
                            }
                        }
                        $.fn.General_ShowErrorMessage({
                            title: "Success",
                            message :errors,
                            eventCallback: function() {
                                location.reload(true);
                            }
                        });
                    }
                    
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $.fn.General_ShowErrorMessage({type: 'error', message: jqXHR.responseText});
            },
            beforeSend: function (jqXHR, settings) {
                $.fn.showScreenLoader();
            },
            complete: function (jqXHR, textStatus) {
                $.fn.hideScreenLoader();
            }
        });
    });
        
        $('.permission-checkbox').on('change', function (e) {
            e.preventDefault();
            var guid = $("#teamform-guid").val();
            $.ajax({type: 'post',
                url: baseHttpPath + '/api/team-permission/add-permission',
                dataType: 'json',
                data: {permission: $(this).val(), guid: guid,  _csrf: yii.getCsrfToken()},
                success: function (data, textStatus, jqXHR) {
                    if (data.success == "1") {

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
    };
    return {
        init: function () {
            attachEvents();
        }
    };
}(jQuery));



