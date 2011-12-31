/**
 * Подгонка изображение для превьюшек
 * 
 * Требуется: 
 * 	jQuery JavaScript Library v1.6.1,
 * 	jQuery Windows Engine Plugin v 1.7,
 * 	jQuery Resize And Crop (jrac) v 2,
 * 	jQuery UI 1.8.16
 * 
 * @author sokol
 */
var thumbnail = {
	DEF_GRIDIMG_PATH: '/js/dhtmlx/imgs/',
	settings: {
		wnd: {
			//Windows Engine Plugin settings
			id: "wnd_thumbnail",
			title: "Создать превью",
			width: 600,
			height: 700,
			posx: 10,
			posy: 10,
			content: "",
			onDragBegin : null,
			onDragEnd : null,
			onResizeBegin : null,
			onResizeEnd : null,
			onAjaxContentLoaded : null,
			statusBar: true,
			minimizeButton: true,
			maximizeButton: true,
			closeButton: true,
			draggable: true,
			resizeable: true,
			type: "normal", // "normal" or "iframe"
			modal: false,
			
			//my settings
			btn_id: 'thumb_save_btn',
			select_size_id: 'thumb_select_size',
			select_res_type_id: 'thumb_select_res_type',
			disable_sizes: false,
			select_size: '',
			disable_resize_types: false,
			select_resize_types: ''
		},
		jrac: {
			pane_class: 'pane',
			crop_width: 250,
			crop_height: 120,
			crop_resize: false,
			zoom_max: 1000
		},
		preview: {
			/**
			 * @params Array sizes Метрики для превью
			 */
			sizes: new Array(),
			/**
			 * @param Array resize_types Типы обрезки изображения
			 */
			resize_types: new Array(),
			/**
			 * @param string src Url исходного превью
			 */
			src: ''
		},
		/**
		 * @param string url Урл для сохранения превью
		 */
		url: '',
		/**
		 * @param funcion ext_function Внешняя ф-ция, будет вызвана в случае успешного сохранения превью
		 */
		ext_function: ''
	},
	
	getEditor: function(img_src, img_save) {
		var self = thumbnail;
		
		if(self.setWindow(img_src))
			self.setJrac();
	},
	
	setWindow: function(img_src) {
		var self = thumbnail;
		var msg = '';
		
		$.closeAllWindows();
		$.newWindow(self.settings.wnd);
			
		return self.setWindowContent(self.areSettingsInvalid());
	},
	
	/**
	 * Проверяет наличие необходимых настроек
	 * 
	 * return string | fasle
	 */
	areSettingsInvalid: function() {
		var self = thumbnail;
		var msg = new Array()
		
		if(!self.settings.preview.src)
			msg.push('Url исходного изображения не задан');
		if(!self.settings.preview.sizes.length)
			msg.push('Не заданы метрики для превью');
		if(!self.settings.preview.resize_types.length)
			msg.push('Не заданы типы действий');
		if(!self.settings.url)
			msg.push('Не задан Url для сохранения превью');
			
		return (msg.length ? msg.join('<br />') : false);
	},
	
	/**
	 * Содержимое окна
	 * 
	 * var String msg Если не пустая строка, выводится сообщение и возвр. false
	 * return Boolean
	 */
	setWindowContent: function(msg) {
		var self = thumbnail;
		if(!msg)
		{
			html = 
				'<div class="' + self.settings.jrac.pane_class + '"><img src="' + self.settings.preview.src + '" />' +
				'Размер: ' + self.getWindowSelectElem(self.settings.preview.sizes, self.settings.wnd.select_size_id, self.settings.wnd.select_size, self.settings.wnd.disable_sizes) + ' ' +
				'Действие: ' + self.getWindowSelectElem(self.settings.preview.resize_types, self.settings.wnd.select_res_type_id, self.settings.wnd.select_resize_types, self.settings.wnd.disable_resize_types) +
				'<button id="' + self.settings.wnd.btn_id + '" style="margin:5px 0 0 5px;">Сохранить</button></div>';
				
			$.updateWindowContent(self.settings.wnd.id, html);
			
			$('#' + self.settings.wnd.btn_id).click(self.onSaveClick);
			$('#' + self.settings.wnd.select_size_id).click(self.onSizeChange);
			
			return true;
		}
		else
		{
			$.updateWindowContent(self.settings.wnd.id, '<div style="margin:5px;padding:5px;text-align:center;color:red;">' + msg + '</div>');
			
			return false;
		}
	},
	
	getWindowSelectElem: function(data, id, set_selected, disable) {
		var html = '<select id="' + id + '" ' + (disable ? 'disabled="disabled"' : '') + '>';
		for(var i in data)
		{
			html += '<option value="' + data[i]['value'] + '" ' +
				((data[i]['value'] == set_selected) ? 'selected="selected"' : '') + ' >' 
				+ data[i]['label'] + '</option>';
		}
		html += '</select>';
		
		return html;
	},
	
	setJrac: function() {
		var self = thumbnail;
		
		$('#' + self.settings.wnd.id + ' img').jrac({
			'crop_width': self.settings.jrac.crop_width,
			'crop_height': self.settings.jrac.crop_height,
			'crop_resize': self.settings.jrac.crop_resize,
			'zoom_max': self.settings.jrac.zoom_max,
			'viewport_onload': function() {
				self.viewport = this;
				$('#' + self.settings.wnd.id + ' .jrac_viewport').css({width: self.settings.wnd.width - 15, height: self.settings.wnd.height - 50});
				$('#' + self.settings.wnd.id + ' .jrac_zoom_slider').css({width: self.settings.wnd.width - 15});
				self.onSizeChange();
			}
		})	
	},
	
	onSaveClick: function() {
		var self = 	thumbnail;
		var url = self.settings.url;
		var post_data = {
			crop_width: self.viewport.$crop.width(),
			crop_height: self.viewport.$crop.height(),
			crop_left: self.viewport.$crop.position().left,
			crop_top: self.viewport.$crop.position().top,
			image_width: self.viewport.$image.width(),
			image_height: self.viewport.$image.height(),
			image_left: self.viewport.$image.position().left,
			image_top: self.viewport.$image.position().top,
			crop_type: $('#' + self.settings.wnd.select_res_type_id).val(),
			image_url: self.settings.preview.src,
			create_thumbnail: 1
		};
		
		$.ajax({
			type: "POST",
			url: url,
			dataType: "json",
			data: post_data,
			success: function(result){
				if(result.result)
				{
					$.closeAllWindows();
					if(typeof(self.settings.ext_function) == 'function')
					{
						self.settings.ext_function();
					}
				}
				else
					self.setWindowContent(result.msg);
			},
			error: function(){
				self.setWindowContent("Ошибка: не удалось сохранить превью");
			}
		});
	},
	
	onSizeChange: function() {
		var self = thumbnail;
		var metrics = $('#' + self.settings.wnd.select_size_id).val().split('x');
		
		self.viewport.$crop.width(metrics[0]);
		self.viewport.$crop.height(metrics[1]);
	}
};
