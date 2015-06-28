	$.fn.exists = function() {
		return $(this).length;
	}
	var clickEvent = (('ontouchstart' in window) || (window.DocumentTouch && document instanceof DocumentTouch)) ?
					'touchstart' :
					'click';

	$().ready(function() {

	if ($('.search-form').exists()) {			
			formFx($('.search-form'));
		}
	});
	
function formFx(form_class) {	
	var form = 'object' == typeof form_class ? form_class : $(form_class),
		inputs = form.find('.form-control, .radio-group');
	
	// submit
	$('.submit', form).on(clickEvent, function(e) {
		if (checkInputs())
			submitForm();
		else
			e.preventDefault();
	});
	
	// search text
	$('input[name="type"]').on(clickEvent, function() {
		if (this.checked && 'type3' == this.id) {
			$('#text').closest('.form-group').removeClass('hidden');
		} else {
			$('#text').val('').closest('.form-group').addClass('hidden');

			if ($('#text').hasClass('empty')) {
				$('#text').removeClass('empty')
					.closest('.form-group').removeClass('has-error');
				checkInputs();				
			}
		}
	});
	
	$('input[name="type"]:checked').trigger(clickEvent);	

	function checkInputs() {
		var ret = true;
		inputs.each(function() {
			if ($(this).is(':hidden')) {
				return;
			}
			if (this.tagName != 'INPUT' && this.tagName != 'TEXTAREA') {
				var real = $(this).siblings('input[type="hidden"]')[0] ? $(this).siblings('input[type="hidden"]') : $(this).find('input[type="checkbox"]');
					fict = $(this);
			} else {
				var real = fict = $(this);
			}
			
			if (fict.hasClass('required') && '' == real.val()) {
				ret = false;
				fict.addClass('empty').closest('.form-group').addClass('has-error');
			} else if (fict.hasClass('radio-group')) {
				var radio_check = false;
				$('input[type="radio"]', fict).each(function() {
					if (this.checked) {
						radio_check = true;
					}
				});
				if (!radio_check) {
					$('input[type="radio"]', fict).first().trigger(clickEvent);
				}
			} else {
				fict.removeClass('empty').closest('.form-group').removeClass('has-error');
			}
		});
		(!ret) ?
			manageError('Заполнены не все поля') :
			manageError();

		return ret;	
	}
	
	function manageError(msg) {
		var obj = $('.error', form);
		if (!msg) {
			obj.animate({opacity:0}, 400).empty();
		} else {
			obj.html(msg).animate({opacity:1}, 400);
		}
	}
	
	function manageAjaxMsg(status, msg) {
		var className = -1 == status ? 'alert alert-danger' : 'alert alert-success',
			h1 = $('h1');
		
		if (h1.next('.alert').exists()) {
			h1.next('.alert').remove();
		}
		
		var alertDiv = $('<div/>').addClass(className).html(msg).insertAfter(h1);
	}	

	inputs.on('blur', function() {
		if ($(this).hasClass('empty') && '' != $(this).val()) {
			$(this).removeClass('empty').closest('.form-group').removeClass('has-error');
			checkInputs();
		}
	});
	
	function submitForm() {
		var query = {
			link: $("#link").val(),
			type: $('[name="type"]:checked', form).val(),
			text: $("#text").val(),
		};
		$.ajax({
			type:'post',
			url:'/search/',
			dataType:'json',
			data:query,
			cache:true,
			success:function(data) {
				loading(false);
				if (0 == data.status) {
					for (key in data.content) {
						if (null == data.content[key] && $('[name="'+key+'"]').is(':visible')) {
							if ($('[name="'+key+'"]').attr('type') == 'radio') {
								$('[name="'+key+'"]').each(function() {
									this.checked = false;
								});
							} else {
								$('[name="'+key+'"]').val('');
							}
						}
					}
					checkInputs();
				} else {
					manageAjaxMsg(data.status, data.content);
				}
			},
			error:function(e, s) {
				loading(false);
				manageAjaxMsg(-1, 'Script error: ' + s);
			},
			beforeSend:function() {
				loading(true);
			}
		});
	}
	function loading(action) {
		if (true == action) {
			$("<div />").addClass('loading').prependTo('body').animate({opacity: 1}, 200);
		} else {
			$(".loading").animate({opacity: 0}, 200, function() {$(this).remove()});
		}
	}
}	