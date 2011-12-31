/**
 * Добавить/удалить страницу из закладок
 *
 * @author sokol
 * @package main
 */
var xo4y_favorites = {
	url: '/articles/set-favorites/',
	add_favorite: 'add',
	rm_favorite: 'remove',
	lbl_add_fav: ' + сохранить в блокнот',
	lbl_rm_fav: ' - из блокнота',
	
	__construct: function() {
		var self = xo4y_favorites;
		
		self.initLinks();
	},
	
	initLinks: function() {
		var self = xo4y_favorites;
		
		$('a.notepad').each(function() {
			$(this).click(self.processLinkClick);
		});
	},
	
	processLinkClick: function() {
		var self = xo4y_favorites;
		var link = $(this);
		
		if(link.hasClass('tonotepad'))
			self.setFavorite(link, self.add_favorite);
		else if(link.hasClass('fromnotepad'))
			self.setFavorite(link, self.rm_favorite);
	},
	
	setFavorite: function(link, action) {
		var self = xo4y_favorites;
		var page_id = parseInt($(link).attr('name'));
		
		self.sendData(page_id, action);
	},
	
	/**
	 * Послать ajax запрос
	 *
	 * @param Number page_id
	 * @param String action add|remove
	 */
	sendData: function(page_id, action) {
		var self = xo4y_favorites;
		
		$.ajax({
			url: self.url,
			data: {page_id: page_id, action: action},
			type:	'POST',
			dataType: 'json',
			success: function (response) {
				self.processAjaxResult(response);
			}
		});
	},
	
	processAjaxResult: function(response) {
		var self = xo4y_favorites;
		
		if(typeof(response) == 'object')
		{
			var page_id = response.page_id;
			var result = response.result;
			var action = response.action;
			if(result)
			{
				self.setLabel(page_id, action);
			}
		}
	},
	
	setLabel: function(page_id, action) {
		var self = xo4y_favorites;
		var rmClass = '';
		var addClass = '';
		var label = '';
		
		if(action == self.add_favorite)
		{
			rmClass = 'tonotepad';
			addClass = 'fromnotepad';
			label = self.lbl_rm_fav;
		}
		else if(action == self.rm_favorite)
		{
			rmClass = 'fromnotepad';
			addClass = 'tonotepad';
			label = self.lbl_add_fav;
		}
		
		$('a.notepad[name="' + page_id + '"]').each(function() {
			var link = $(this);
			link.html('<span>' + label + '</span>');
			link.removeClass(rmClass);
			link.addClass(addClass);
		});
	}
}

$(document).ready(xo4y_favorites.__construct);
