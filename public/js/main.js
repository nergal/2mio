$(function(){
	var __country_cache = { };

	$.tools.dateinput.localize("ru", {
		months:      'Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь',
		shortMonths: 'Янв,Фев,Мар,Апр,Май,Июн,Июл,Авг,Сен,Окт,Ноя,Дек',
		days:        'Воскресение,Понедельник,Вторник,Среда,Четверг,Пятница,Суббота',
		shortDays:   'Вс,Пн,Вт,Ср,Чт,Пт,Сб'
	});

	$("a.reply-button").bind('click', function() {
		var comment = $(this)
			.parents('div.text')
			.clone();

		var name = $('p:eq(1)', comment).text().replace(/"/, "''");

		comment = comment.find('.comment-body');
		$('.citata', comment).remove();
		comment = $.trim(comment.text());

		text = '[quote="'+name+'"]'+comment+'[/quote]\n';
		// WTF?! Почему не работает?
		// $("#comment_form textarea#body").focus().text(text);
		
		$("#comment_form textarea#body").replaceWith($('<textarea rows="4" name="body" id="body" class="fancy" />').text(text));

		return false;
	});

	if ($("input.date-field").length > 0) {
		var cal_clicked = function(event) {
    		$('.calweek:odd').addClass('odd');
    		$('#caldays span:gt(4)').addClass('weekend');
	    };

	    $("input.date-field").dateinput({
			format: 'mm/dd/yyyy',
			selectors: true,
			lang: 'ru',
			yearRange: [-100, 1],
			firstDay: 1,
			min: -(365 * 80),
			max: 1,
			onShow: cal_clicked,
			onBefore: cal_clicked
	    });
	}

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

	$('#auth-button').overlay({
		mask: '#000',
		fixed: true,
		left: "center",
		top: '20%',
		onBeforeLoad: function() {
			var wrap = this.getOverlay().find(".contentWrap");
			wrap.load(this.getTrigger().attr("href"));
		}
	});

	if ($('#add-comment').length > 0) {
		$('#add-comment').bind('click', function() {
		    $('div.hidden', $(this).hide().parent()).slideDown();
		});
	}

	if ($('#user_country').length > 0) {
		$('#user_country')
			.change(function(event) {
				$.ajax({
					type: 'POST',
					url: '/user/getcountry/',
					data: {id: $(this).val()},
					success: function(data) {
						if (data.status == 'success') {
							var source = $('select#user_from');
							source.empty();

							var hook = '', html = '';

							var svalue = [];
							$.each(data.result, function(skey, sval) {
								svalue.push({key: skey, value: sval});
							});

							svalue.sort(function(a, b) {
								if (''.localeCompare) {
									return a.value.localeCompare(b.value);
								} else {
									if (a.value > b.value) return 1;
									if (a.value < b.value) return -1;
									if (a.value == b.value) return 0;
								}
							});
							$.each(svalue, function(skey, item) {
								var key = parseInt(item.key);
								var value = item.value;

								text = '<option value="'+key+'">'+value+'</option>';
								if (key == 2533) { hook = text; } else { html+= text; }
							});

							source.append(hook + html);
						}
					},
					dataType: 'json'
				});
			});
	}

    if (!$.browser.opera) {
        $('select.chzn-select').each(function(){
            var title = $(this).attr('title');
            if ($('option:selected', this).val() != '') {
	            title = $('option:selected',this).text();
	        }

            $(this)
                .css({
	                'z-index': 10,
	                'opacity': 0,
	                '-webkit-appearance': 'none',
	                '-moz-appearance': 'none'
	           	})
	           	//.focus(function() { $(this).next().find('.pip').css('background-position-y', '100%') })
	           	//.focusout(function() { $(this).next().find('.pip').css('background-position-y', '0%') })
                .after('<span class="select">'+title+'<span class="pip"></span></span>')
                .change(function() {
                    var val = $('option:selected',this).text();
                    $(this)
                    	.next()
                    	.html(val+'<span class="pip"></span>');
                });
        });
    };

	if ($('#user_from').length > 0) {
		$('#user_from')
			.change(function(event) {
				$('#city_id').val($(this).val());
				var fancy_name = $('#user_country option:selected').html() + ', ' + $('#user_from option:selected').html();
				$('#user_from_hidden').val(fancy_name);
			});
	}

	$('span.ch:not(.disch) > input:checkbox').change(function(){ $(this).parent().toggleClass('checkch'); });
	$('span.ch:not(.disch):not(.checkch) > input:checkbox:checked').each(function(){
		$(this).parent().toggleClass('checkch');
	});

	$('ul.blogul').quickPager();

	$(".subcontainer ul.tabs").tabs("div.panes > div", {
		current: 'active',
		tabs: 'li'
	});

	$('.contentnews a.notepad').click(function() {
		var page_id = parseInt($(this).attr('name'));
		var selector = '.favorite-'+page_id;
		$(selector).hide('slide', function() {
			if ($('#video .scroll').length) {
				if ($('#video .scroll :nth-child:visible').length < 1) {
					$('#video .scroll').append($('<p>').html('У меня еще нет закладок'));
				}
			}
		});
	});

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


	/***********************************************/
	/* Опрос */
	/***********************************************/
	var ask_was_asked = getCookie('ask_was_asked');
	if(ask_was_asked == null)
	{
	var ask_was_enter = getCookie('ask_was_enter');
	if(ask_was_enter == null)
	{
	  //setCookie('ask_was_enter', 1);
	  $.post('/ask/setcookie/', { name: 'enter' });
	} else 
	if(parseInt(ask_was_enter) == 1) {

	  //setCookie('ask_was_asked', 1);
	  $.post('/ask/setcookie/', { name: 'asked' });

	  moveAsk();
	  
	  // показываем окно опроса
	  $('#ask_subscript').fadeIn(500);

	  $(window).scroll(function(){
	    setTimeout("moveAsk()",1000);
	  });

		// обработчик перехода с 1-го на 2-й шаг
		$('#submit_ask_step1').click(function(){
	    
		    // подготавливаем данные для передачи на сервер
		    var askradioval = ($('.askradio input:radio:checked').val().length) ? $('.askradio input:radio:checked').val() : null;

		    var askcheckvals = sections = sep = '';
		    var sepsec = 'на темы: ';
		    $.each($('.askchk:checked'), function(skey, sval) {
		      askcheckvals += sep + sval.value;
		      sep = ',';
		      // формируем текст с выбранными разделами
		      sections += sepsec + '"' + $(this).parent().next().text() + '"';
		      sepsec = ',\n';
		    });

		    $('#asksections').text(sections);

		    // сохраняем секции
		    $.ajax({
		      type: 'POST',
		      url: '/ask/setsections/',
		      data: {radio: askradioval, checks: askcheckvals},
		      success: function(data) {
		        if (data.status == 'success') {
		          if(typeof data.result.id != 'undefined') {
		            window.idAsk = parseInt(data.result.id);
		          }
		        }
		      },
		      dataType: 'json'
		    });

		    $('#ask_step1').hide();
		    $('#ask_step2').show();
		    $('#subscribe_email').focus();

		    $("#askformemail").bind("submit", function() { return false; })

			$('#subscribe_email').bind('keydown', function(e) {
		        if(e.keyCode==13){
		            $('#submit_ask_step2').click();
		        }
			});
	  });

	  // обработчик перехода со 2-го на 3-й шаг
	  $('#submit_ask_step2').click(function(){
	    var val = $('#subscribe_email').val();
	    // валидация почты
	    var isvalid = val.match(/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})$/);
	    if(isvalid != null && val.length > 4)
	    {
	    	$.post('/ask/setemail/', { id: window.idAsk, email: val });

			// если мужик, то меняем род
			if(parseInt($('.askradio input:radio:checked').val())) {
				$("#ask_gender").text('подписался');
			}

	        $('#ask_step2').hide();
	        $('#ask_step3').show();
	    } else {
	      $('#subscribe_email').focus();
	    }

	  });

	  // обработчик выхода из 3-го шага
	  $('#submit_ask_step3').click(function(){
	    $('#ask_subscript').fadeOut(500);
	  });

	  $('.popup-close').click(function(){
	    $('#ask_subscript').fadeOut(500);
	  });

	  // смена фонов радиокнопок
	  $('#gender-female').change(function(){
	      $('#gender-female').parent().addClass('active');
	      $('#gender-male').parent().removeClass('active');
	  });
	  $('#gender-male').change(function(){
	      $('#gender-male').parent().addClass('active');
	      $('#gender-female').parent().removeClass('active');
	  });
	}
	}


});
