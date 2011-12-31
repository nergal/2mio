(function($) {
	$.fn.rianTabs = function(options, callback) {
		if (typeof(options) == 'function') {
			callback = options;
			options = {};
		}

        options = $.extend({
			targetPrefix: 'show',
			activeClass: 'active',
			preventEvent: true,
			closeOpenedTab: false,
			innerElement:	true
        }, options);		
			
		$(this).click(function (e) {
			if (options.preventEvent) {
				e.preventDefault();
			}

			if(options.innerElement){
				var tabElement = $(e.target);
			}else{
				var tabElement = $(this);
			}
			
			var regExp = new RegExp(options.targetPrefix + ':([a-z-]+)', 'i');
			var target = tabElement.attr('class').match(regExp);
			target = (target !== null) ? target[1] : '';
			
			if (!target){
				return;
			}

			var aClass = options.activeClass;

			if (options.closeOpenedTab && tabElement.hasClass(aClass)) {
				tabElement.removeClass(aClass);
				$('#' + target).hide();
				return;
			}
			
			$('#' + target)
				.show()
				.siblings().hide();

			tabElement
				.siblings().removeClass(aClass)
				.end().addClass(aClass);

			if (callback) {
				callback.call(e, tabElement, target);
			}
		});

		return this;
	}
})(jQuery);
