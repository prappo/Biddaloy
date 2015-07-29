$(function() {
    $('#datepick input').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: "linked",
        clearBtn: true,
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true
    });
    
    //principal's profile --START
    
    //principal's profile --END

    //teachers profile --START
    var action = $('div.btn-group ul[role=menu] li');
    action.on('click', 'a', function(e) {
        var $this     = $(this);
        var elementId = $this.attr('id');
        if(elementId == 'viewTeacherProfile') {
            $('div#viewProfileModal').modal('show');
            var config = {
                'action'   : 'getInfo',
                'table'    : 'scl_teachers',
                'username' : $this.data('username'),
            };

            $.post($this.data('url')+'/lib/manage_profile_info.php', config, function(data) {
                var table = $('table tbody tr');
                function profileInfo() {
                    var profileImg = $('#viewProfile_img');
                    $('div.modal-header h4#viewModalLabel').text(data.full_name+'\'s profile');
                    
                    profileImg.attr('src', $this.data('url')+'/images/teachers/'+data.image);
                    //default image if not set
                    if(data.image == '') {
                        profileImg.attr('src', $this.data('url')+'/images/default_image.png');
                    }
                    //default image if any error
                    profileImg.error(function() {
                        profileImg.attr('src', $this.data('url')+'/images/default_image.png');
                    });

                    table.find('td#viewProfile_username').text(data.username);
                    table.find('td#viewProfile_name').text(data.full_name);
                    table.find('td#viewProfile_gender').text(data.gender);
                    table.find('td#viewProfile_date_of_birth').text(data.date_of_birth);
                    table.find('td#viewProfile_address').text(data.address);
                    table.find('td#viewProfile_mobile').text(data.mobile);
                    table.find('td#viewProfile_email').text(data.email);
                }

                var modalBody = $('div#viewProfileModalBody').hide();
                var modalTable = modalBody.find('table')
                
                $('div#viewModalLoadingImg').fadeOut(function() {
                    profileInfo();
                    $('div#viewModalHeader').removeClass('hide');
                    modalBody.removeClass('hide');
                    $('#viewModalfooter button').removeClass('hide');
                    modalBody.slideDown();
                });
            }, 'json');
        } else if(elementId == 'editTeacherProfile') {
            $('div#editProfileModal').modal('show');
            var config = {
                'action'   : 'getInfo',
                'table'    : 'scl_teachers',
                'username' : $this.data('username')
            };

            $.post($this.data('url')+'/lib/manage_profile_info.php', config, function(data) {
                function editProfileInfo() {
                    var form = $('form#teachers_edit_form');
                    var profileImg = $('#editProfile_img');
                    $('#editModalLabel').text(data.full_name+'\'s profile');
                    
                    profileImg.attr('src', $this.data('url')+'/images/teachers/'+data.image);
                    //default image if not set
                    if(data.image == '') {
                        profileImg.attr('src', $this.data('url')+'/images/default_image.png');
                    }
                    //default image if any error
                    profileImg.error(function() {
                        profileImg.attr('src', $this.data('url')+'/images/default_image.png');
                    });
                    
                    form.find('input#editProfile_username').val(data.username);
                    form.find('input#editProfile_name').val(data.full_name);
                    form.find('select#editProfile_gender optgroup option[value='+data.gender+']')
                        .prop('selected', true);
                    form.find('input#editProfile_date_of_birth').val(data.date_of_birth);
                    form.find('input#editProfile_address').val(data.address);
                    form.find('input#editProfile_mobile').val(data.mobile);
                    form.find('input#editProfile_email').val(data.email);
                }

                var modalBody = $('form div#editModalBody').hide();

                $('form div#editModalLoadingImg').fadeOut(function() {
                    editProfileInfo();
                    $('form div#editModalHeader').removeClass('hide');
                    modalBody.removeClass('hide');
                    $('form div#editModalfooter button').removeClass('hide');
                    modalBody.slideDown();
                });
            }, 'json');
        } else if(elementId == 'editTeacherPassword') {
            $('div#passwordProfileModal').modal('show');
            var config = {
                'action'   : 'getInfo',
                'table'    : 'scl_teachers',
                'username' : $this.data('username')
            };

            $.post($this.data('url')+'/lib/manage_profile_info.php', config, function(data) {
                $('form#changePasswordForm').find('input#password_username').val(data.username);
            }, 'json');
        } else if(elementId == 'deleteTeacherProfile') {
            swal({
              title: "Warning!",
              text: "are you sure you want to delete?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes, delete",
              closeOnConfirm: false
            }, function(){
                var config = {
                    'action'   : 'deleteProfile',
                    'table'    : 'scl_teachers',
                    'username' : $this.data('username')
                };

                $.post($this.data('url')+'/lib/manage_profile_info.php', config, function(data) {
                    config = {
                        'action'   : 'deleteProfile',
                        'table'    : 'vor_admin',
                        'username' : $this.data('username')
                    };
                    if(data.status) {
                        $.post($this.data('url')+'/lib/manage_profile_info.php', config, function(data) {
                            if(data.status) {
                                profileDeleted();
                                setInterval(function() {
                                    location.reload();
                                }, 1400)
                            } else {
                                error();
                            }
                        }, 'json');
                    } else {
                        error();
                    }
                }, 'json');
            });
        }
    });
    //teachers profile --END
});