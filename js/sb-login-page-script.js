(function($){
    // Hide form error message
    (function(){
        $('.sb-login-form input, .sb-signup-form input, .sb-lost-password-form input').on('keyup', function(e){
            e.preventDefault();
            var that = $(this),
                sb_form = that.closest('form');
            sb_form.find('.errors').removeClass('active');
        });
        $('.sb-user-profile input').on('keyup', function(e){
            e.preventDefault();
            var that = $(this);
            that.parent().find('.notification').html('');
        });
    })();

    // Save button
    (function(){
        $('.sb-user-profile .btn-save').attr('disabled', 'disabled');
        $('.sb-user-profile').on('keyup', function(e){
            e.preventDefault();
            var that = $(this),
                save_button = that.find('.btn-save');
            save_button.removeAttr('disabled');
        });
        $('.sb-user-profile select').on('change', function(e){
            e.preventDefault();
            var that = $(this),
                user_profile = that.closest('div.sb-user-profile'),
                save_button = user_profile.find('.btn-save');
            save_button.removeAttr('disabled');
        });
        $('.sb-user-profile .btn-save').on('click', function(e){
            e.preventDefault();
            var that = $(this),
                content_inner = that.closest('div.content-inner'),
                valid = true,
                user_id = parseInt(that.attr('data-id')),
                data = null;
            if(that.hasClass('save-account')) {
                var email = content_inner.find('.user-email');
                if(!$.trim(email.val())) {
                    email.focus();
                    valid = false;
                }
                if(valid) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: sb_core_ajax.url,
                        data: {
                            'action': 'sb_login_page_change_email',
                            'id': user_id,
                            'email': email.val(),
                            'security': $('#security').val()
                        },
                        success: function(response){
                            var data = response;
                            if(data.updated == true) {
                                window.location.href = window.location.href;
                            } else {
                                email.parent().find('.notification').html(data.message);
                            }
                        }
                    });
                }
            } else if(that.hasClass('btn-save-password')) {
                var current_password = content_inner.find('.user-current-password'),
                    new_password = content_inner.find('.user-password'),
                    re_new_password = content_inner.find('.re-password'),
                    valid = true;
                if(!$.trim(new_password.val())) {
                    new_password.focus();
                    valid = false;
                } else if(!$.trim(new_password.val())) {
                    new_password.focus();
                    valid = false;
                } else if(!$.trim(re_new_password.val()) || re_new_password.val() != new_password.val()) {
                    re_new_password.focus();
                    valid = false;
                }
                if(valid) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: sb_core_ajax.url,
                        data: {
                            'action': 'sb_login_page_change_password',
                            'current_password': current_password.val(),
                            'new_password': new_password.val(),
                            're_new_password': re_new_password.val(),
                            'id': user_id,
                            'security': $('#security').val()
                        },
                        success: function(response){
                            var data = response;
                            if(data.updated == true) {
                                window.location.href = window.location.href;
                            } else {
                                if(data.field == 'current_password') {
                                    current_password.focus();
                                    current_password.parent().find('.notification').html(data.message);
                                } else if(data.field == 'new_password') {
                                    new_password.focus();
                                    new_password.parent().find('.notification').html(data.message);
                                } else if(data.field == 're_new_password') {
                                    re_new_password.focus();
                                    re_new_password.parent().find('.notification').html(data.message);
                                }
                            }
                        }
                    });
                }
            } else if(that.hasClass('save-personal-info')) {
                var user_name = content_inner.find('.user-name'),
                    user_gender = content_inner.find('.user-gender'),
                    user_birth_day = content_inner.find('.user-birth-day'),
                    user_birth_month = content_inner.find('.user-birth-month'),
                    user_birth_year = content_inner.find('.user-birth-year'),
                    user_phone = content_inner.find('.user-phone'),
                    user_identity = content_inner.find('.user-identity'),
                    user_address = content_inner.find('.user-address'),
                    valid = true;
                if(!$.trim(user_name.val())) {
                    user_name.focus();
                    valid = false;
                } else if(!$.trim(user_phone.val())) {
                    user_phone.focus();
                    valid = false;
                } else if(!$.trim(user_identity.val())) {
                    user_identity.focus();
                    valid = false;
                } else if(!$.trim(user_address.val())) {
                    user_address.focus();
                    valid = false;
                }
                if(valid) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: sb_core_ajax.url,
                        data: {
                            'action': 'sb_login_page_change_personal_info',
                            'user_name': user_name.val(),
                            'user_gender': user_gender.val(),
                            'user_birth_day': user_birth_day.val(),
                            'user_birth_month': user_birth_month.val(),
                            'user_birth_year': user_birth_year.val(),
                            'user_phone': user_phone.val(),
                            'user_identity': user_identity.val(),
                            'user_address': user_address.val(),
                            'id': user_id,
                            'security': $('#security').val()
                        },
                        success: function(response){
                            window.location.href = window.location.href;
                        }
                    });
                }
            }
        });
    })();

    // Account page
    (function(){
        $('.sb-user-profile .current-password').on('keyup', function(e){
            var that = $(this),
                current_password = that.find('input.user-current-password'),
                content_inner = that.closest('div.content-inner');
            if(current_password.val().length > 0) {
                content_inner.find('.main-password, .re-password').removeAttr('disabled');
            } else {
                content_inner.find('.main-password, .re-password').attr('disabled', 'disabled');
            }
        })
    })();

    // Sign up form
    (function(){
        $('.sb-signup-form').on('submit', function(e){
            var that = $(this),
                full_name = that.find('.signup-fullname'),
                email = that.find('.signup-email'),
                phone = that.find('.signup-phone'),
                address = that.find('.signup-address'),
                password = that.find('.signup-password'),
                re_password = that.find('.signup-password-2'),
                errors = that.find('.errors'),
                hidden_fields = that.find('.hidden-fields'),
                success_field = that.find('.success-field'),
                valid = true,
                data = null;

            if(full_name.hasClass('must-have') && !$.trim(full_name.val())) {
                full_name.focus();
                valid = false;
            } else if(!$.trim(email.val())) {
                email.focus();
                valid = false;
            } else if(phone.hasClass('must-have') && !$.trim(phone.val())) {
                phone.focus();
                valid = false;
            } else if(address.hasClass('must-have') && !$.trim(address.val())) {
                address.focus();
                valid = false;
            } else if(!$.trim(password.val())) {
                password.focus();
                valid = false;
            } else if(!$.trim(re_password.val())) {
                re_password.focus();
                valid = false;
            } else if(re_password.val() != password.val()) {
                re_password.focus();
                valid = false;
            }
            if(valid) {
                if(!success_field.length || parseInt(success_field.val()) != 1) {
                    e.preventDefault();
                }
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: sb_core_ajax.url,
                    data: {
                        action: 'sb_login_page_signup',
                        email: email.val(),
                        password: password.val(),
                        name: full_name.val(),
                        phone: phone.val(),
                        address: address.val(),
                        security: that.find('#security').val()
                    },
                    success: function(response){
                        var data = response;
                        if(data.valid == 1) {
                            hidden_fields.append(data.success_field);
                            that.submit();
                        } else {
                            errors.html(data.message);
                            errors.addClass('active');
                        }
                    }
                });
            } else {
                e.preventDefault();
            }
        });
    })();

    // Login form
    (function(){
        $('.sb-login-form').on('submit', function(e){
            var that = $(this),
                login_email = that.find('.login-email'),
                login_password = that.find('.login-password'),
                redirect = that.find('.redirect'),
                submit_button = that.find('.login-submit'),
                login_remember = that.find('.login-remember'),
                errors = that.find('.errors'),
                data_valid = true;
            e.preventDefault();
            if(!$.trim(login_email.val())) {
                login_email.focus();
                data_valid = false;
            } else if(!$.trim(login_password.val())) {
                login_password.focus();
                data_valid = false;
            }
            if(data_valid) {
                submit_button.addClass('disabled');
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: sb_core_ajax.url,
                    data: {
                        action: 'sb_login_page_login',
                        email: login_email.val(),
                        password: login_password.val(),
                        remember: login_remember.val(),
                        security: that.find('#security').val()
                    },
                    success: function(response){
                        var data = response;
                        if(data.logged_in == true) {
                            window.location.href = redirect.val();
                        } else {
                            // Fail
                            if($.trim(data.message)) {
                                submit_button.removeClass('disabled');
                                errors.html(data.message);
                                errors.addClass('active');
                                setTimeout(function(){
                                    window.location.href = data.redirect;
                                }, 3000);
                            } else {
                                login_email.focus();
                                submit_button.removeClass('disabled');
                                errors.addClass('active');
                            }
                        }
                        if(data.logged_in == false && data.block_login == true) {
                            sb_core.sb_refresh();
                        }
                    }
                });
            }
        });
    })();

    // Lost password form
    (function(){
        // Send activation code
        $('.sb-lost-password-form').on('submit', function(e){
            var that = $(this),
                login_email = that.find('.login-email'),
                redirect = that.find('.redirect'),
                submit_button = that.find('.login-submit'),
                errors = that.find('.errors'),
                user_id = that.find('.user-id'),
                data_valid = true;
            e.preventDefault();
            if(!$.trim(login_email.val())) {
                login_email.focus();
                data_valid = false;
            }
            if(data_valid) {
                submit_button.addClass('disabled');
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: sb_core_ajax.url,
                    data: {
                        action: 'sb_login_page_lost_password',
                        email: login_email.val(),
                        security: that.find('#security').val()
                    },
                    success: function(response){
                        var data = response;
                        if(data.user_id > 0) {
                            window.location.href = data.redirect;
                        } else {
                            // Fail
                            login_email.focus();
                            submit_button.removeClass('disabled');
                            errors.addClass('active');
                        }
                    }
                });
            }
        });

        // Verify activation code
        $('.sb-lost-password-form.verify').on('submit', function(e){
            var that = $(this),
                redirect = that.find('.redirect'),
                submit_button = that.find('.login-submit'),
                activation_code = that.find('.activation-code'),
                errors = that.find('.errors'),
                user_id = that.find('.user-id'),
                data_valid = true;
            e.preventDefault();
            if(!$.trim(activation_code.val())) {
                activation_code.focus();
                data_valid = false;
            }
            if(data_valid) {
                submit_button.addClass('disabled');
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: sb_core_ajax.url,
                    data: {
                        action: 'sb_login_page_verify_activation_code',
                        code: activation_code.val(),
                        user_id: user_id.val(),
                        security: that.find('#security').val()
                    },
                    success: function(response){
                        var data = response;
                        if(data.valid == true) {
                            window.location.href = data.redirect;
                        } else {
                            // Fail
                            activation_code.focus();
                            submit_button.removeClass('disabled');
                            errors.addClass('active');
                        }
                    }
                });
            }
        });

        // Update new password
        $('.sb-lost-password-form.reset').on('submit', function(e){
            var that = $(this),
                redirect = that.find('.redirect'),
                submit_button = that.find('.login-submit'),
                activation_code = that.find('.query-code'),
                errors = that.find('.errors'),
                user_id = that.find('.user-id'),
                reset_password = that.find('.reset-password'),
                re_reset_password = that.find('.reset-password-2'),
                data_valid = true,
                data = null;
            e.preventDefault();
            if(!$.trim(reset_password.val())) {
                reset_password.focus();
                data_valid = false;
            } else if(!$.trim(re_reset_password.val()) || re_reset_password.val() != reset_password.val()) {
                re_reset_password.focus();
                data_valid = false;
            }
            if(data_valid) {
                submit_button.addClass('disabled');
                data = {
                    action: 'sb_login_page_reset_password',
                    code: activation_code.val(),
                    password: reset_password.val(),
                    user_id: user_id.val(),
                    security: that.find('#security').val()
                };
                $.post(sb_core_ajax.url, data, function(resp){
                    resp = $.parseJSON(resp);
                    if(resp.updated) {
                        window.location.href = resp.redirect;
                    }
                });
            }
        });

        // Check password strength
        $('body').on('keyup', function(e) {
            var that = $(this),
                reset_password_form = that.find('.sb-lost-password-form.reset');
            if(!reset_password_form.length) {
                e.preventDefault();
                return false;
            }
            var reset_password = reset_password_form.find('.reset-password'),
                re_reset_password = reset_password_form.find('.reset-password-2'),
                reset_password_strength = reset_password_form.find('.reset-password-strength'),
                reset_password_submit = reset_password_form.find('.login-submit'),
                reset_password_black_list = ['admin'];
            sb_core.sb_password_strength(reset_password, re_reset_password, reset_password_strength, reset_password_submit, reset_password_black_list);
        });
    })();

    // Verify email
    (function(){
        $('.sb-verify-email-form').on('submit', function(e){
            e.preventDefault();
            var that = $(this),
                activation_code = that.find('.activation-code'),
                valid = true,
                user_id = that.find('.user-id'),
                redirect = that.find('.redirect'),
                data = null;
            if(!$.trim(activation_code.val())) {
                activation_code.focus();
                valid = false;
            }
            if(valid) {
                data = {
                    'action': 'sb_login_page_verify_email',
                    'code': activation_code.val(),
                    'security': that.find('#security').val(),
                    'id': user_id.val()
                };
                $.post(sb_core_ajax.url, data, function(resp){
                    resp = parseInt(resp);
                    if(1 == resp) {
                        window.location.href = redirect.val();
                    } else {
                        activation_code.focus();
                        that.find('.errors').addClass('active');
                    }
                });
            }
        });
    })();

    // Check password strength
    (function(){
        $('body').on('keyup', function(e) {

            var that = $(this),
                main_password = that.find('.main-password'),
                re_password = that.find('.re-password'),
                indicator = that.find('.password-meter'),
                submit_button = that.find('.btn-save-password'),
                black_list = ['admin'];
            sb_core.sb_password_strength(main_password, re_password, indicator, submit_button, black_list);
        });
    })();
})(jQuery);