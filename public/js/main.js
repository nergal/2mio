$(function(){
    var __country_cache = { };

    $.tools.dateinput.localize("ru", {
        months:      'Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь',
        shortMonths: 'Янв,Фев,Мар,Апр,Май,Июн,Июл,Авг,Сен,Окт,Ноя,Дек',
        days:        'Воскресение,Понедельник,Вторник,Среда,Четверг,Пятница,Суббота',
        shortDays:   'Вс,Пн,Вт,Ср,Чт,Пт,Сб'
    });

    if ($('input.stars').length > 0) {
        $('input.stars').rating({
            callback: function(value, link){
                var self = this;
                $(self.form).ajaxSubmit({success: function(data) {
                    $('input.stars').rating('readOnly', true);
                    $('.rating-stars .counts').html(data);
                }});
            },
            required: true
        });
    }
    $('#form-edit-form').validate({
        rules: {
            username: {
                required: true,
                minlength: 2,
                maxlength: 32
                // , remote: '/user/validate/username/'
            },
            password: {
                minlength: 6,
                maxlength: 255
            },
            password_confirm: {
                minlength: 6,
                maxlength: 255,
                equalTo: "#user_password"
            },
            email: {
                required: true,
                email: true
                // , remote: '/user/validate/email/'
            },

            firstname: {
                minlength: 2,
                maxlength: 25
            },
            lastname: {
                minlength: 2,
                maxlength: 25
            },
            user_birthday: {
                date: true
            },
            user_interests: {
                minlength: 3,
                maxlength: 255
            },
            user_icq: {
                digits: true,
                minlength: 5,
                maxlength: 10
            },
            user_skype: {
                minlength: 6,
                maxlength: 32
            },
            user_website: {
                url: true
            }
        },
        messages: {
            username: {
                required: 'Поле с логином обязательно для заполнения',
                minlength: 'Имя пользователя должно быть не короче двух символов',
                remote: 'Такое имя пользователя уже присутствует в нашей базе данных',
                maxlength: 'Имя пользователя должно быть не длинее 32 символов'
            },
            password: {
                required: 'Поле с паролем обязательно для заполнения',
                minlength: 'Пароль должен быть не короче шести символов',
                maxlength: 'Пароль должно быть не длинее 255 символов'
            },
            password_confirm: {
                required: 'Поле с подтверждением пароля обязательно для заполнения',
                minlength: 'Пароль должен быть не короче шести символов',
                equalTo: 'Пароли должны быть одинаковыми',
                maxlength: 'Пароль должно быть не длинее 255 символов'
            },
            email: {
                required: 'Поле с почтой обязательно для заполнения',
                email: 'Вы должны ввести корректный email адрес',
                remote: 'Такой почтовый ящик уже присутствует в нашей базе данных'
            },

            firstname: {
                minlength: 'Имя не должно быть короче двух символов',
                maxlength: 'Имя должно быть не длинее 25 символов'

            },
            lastname: {
                minlength: 'Фамилия не должна быть короче двух символов',
                maxlength: 'Фамилия должна быть не длинее 25 символов'
            },
            user_birthday: {
                date: 'Вы должны выбрать корректную дату'
            },
            user_interests: {
                minlength: 'Поле интересов не должно быть короче трех символов',
                maxlength: 'Поле интересов не должно быть длинее 255 символов'
            },
            user_icq: {
                digits: 'В номере ICQ должны быть только цифры',
                minlength: 'Номер ICQ должен быть длинее пяти символов',
                maxlength: 'Номер ICQ должен быть короче десяти символов'
            },
            user_skype: {
                minlength: 'Skype ID должен быть длинее шести символов',
                maxlength: 'Skype ID должен быть короче 32 символов'
            },
            user_website: {
                url: 'Вы должны ввести корректный адрес сайта'
            }
        },
        errorLabelContainer: $('#messageBox'),
        errorElement: 'li',
        submitHandler: function(form) {
            form.submit();
        },
        highlight: function(element, errorClass, validClass) {
            $('#messageBox')
                .parents('div.message.error')
                .removeClass('hidden');

            if (element.type === 'radio') {
                this.findByName(element.name)
                    .addClass(errorClass)
                    .removeClass(validClass);
            } else {
                $(element)
                    .addClass(errorClass)
                    .removeClass(validClass);

                if ( ! $('span.label', $(element).parent('div')).length) {
                    $(element)
                        .after(
                            $('<span>')
                                .addClass(errorClass)
                                .addClass('label')
                        );
                }
            }
        },
        unhighlight: function(element, errorClass, validClass) {
            if ($('#messageBox li:visible').length < 2) {
                $('#messageBox')
                    .parents('div.message.error')
                    .addClass('hidden');
            }

            if (element.type === 'radio') {
                this.findByName(element.name)
                    .removeClass(errorClass)
                    .addClass(validClass);
            } else {
                $(element)
                    .removeClass(errorClass)
                    .addClass(validClass);

                $('span.label.error', $(element).parent('div'))
                    .removeClass(errorClass)
                    .addClass(validClass);
            }
        }
    });
});
