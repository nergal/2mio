var advisers = {
	DEF_GRIDIMG_PATH: '/js/dhtmlx/imgs/',
	
	/* ячейки табл. консультанты */
	MARK_COL_ID:	0,
	ID_COL_ID: 		1,
	USERNAME_COL_ID:2,
	SPNAME_COL_ID:	3,
	FIO_COL_ID:		4,
	REGDATE_COL_ID:	5,
	ANSWNUM_COL_ID:	6,
	RATING_COL_ID:	7,
	CONTACTS_COL_ID:8,
	LINK_COL_ID: 	9,
	SPIDS_COL_ID: 	10,
	SNAME_COL_ID:	11,
	DNAME_COL_ID:	12,
	FNAME_C0L_ID:	13,
	CITY_COL_ID:	14,
	COUNTRY_COL_ID:	15,
	EMAIL_COL_ID:	16,
	PHONE_COL_ID:	17,
	DIPLOMA_COL_ID:	18,
	DOCDESCR_COL_ID:19,
	
	/* Форма с инф. о консультанте */
	HTML_DOCSNAME_ID:	'#dsname',
	HTML_DOCNAME_ID: 	'#dname',
	HTML_DOCLNAME_ID: 	'#dlname',
	HTML_DOCCOUNTRY_ID:	'#dcountry',
	HTML_DOCCITY_ID:	'#dcity',
	HTML_DOCEMAIL_ID:	'#demail',
	HTML_DOCPHONE_ID:	'#dphone',
	HTML_DOCDIPL_ID:	'#ddiploma',
	HTML_CHKBXCONT_ID: 	'#sp_chckbx_cont',
	
	/* Фото консультанта */
	HTML_PHOTOFORM_ID:	'#photo_form',
	HTML_PHOTOFILE_ID:	'#photo_file',
	HTML_UPLPHOTOBTN_ID:'#upload_phote',
	HTML_PHOTOMSG_ID:	'#photo_message',
	HTML_PHOTOCONT_ID: '#adv_photo_cont',
	HTML_PHOTOIMG_ID: '#adv_photo',
	
	/* Фото диплома */
	HTML_DIPLOMFORM_ID: '#diploma_form',
	HTML_DIPLOMFILE_ID: '#diploma_file',
	HTML_DIPLOMUPLBTN_ID: '#upl_diploma_photo',
	HTML_DIPLOMMSG_ID: '#diploma_message',
	HTML_DIPLOMPHCONT_ID: '#diplm_photo_cont',
	
	/* Фильтр */
	HTML_FILTERMONTH_ID: '#date_month',
	HTML_FILTERYEAR_ID: '#date_year',
	HTML_FILTERSPEC_ID: '#speciality',
	HTML_FILTERANS_ID: '#status',
	
	hasFlash: false,
	
	/**
	 * Массив выбраных консультантов
	 * @var Array checkedAdvisers
	 */
	checkedAdvisers: new Array(),
	
	__construct: function() {
		var self = advisers;
		
		self.getAdvisersList();
		self.initAnswers();
		self.initEditors();
		self.initTabs();
		self.initOthers();
		self.initButtons();
		self.initChart();
	},
	
	getAdvisersList: function() {
		var self = advisers;
		
		self.advisers = new dhtmlXGridObject('consult_gird');
		self.advisers.setSkin('dhx_blue');
		self.advisers.setImagePath(self.DEF_GRIDIMG_PATH);
        self.advisers.setHeader(advisers_params.colTitles);
		self.advisers.attachHeader(advisers_params.colFilters);
        self.advisers.enableSmartRendering(true);
		self.advisers.setInitWidths(advisers_params.colWidth);
        self.advisers.setColTypes(advisers_params.colTypes);
        self.advisers.setColAlign(advisers_params.colAlign);
        self.advisers.setColSorting(advisers_params.colSorting);
		self.advisers.setColumnHidden(self.DOCDESCR_COL_ID, true);
		self.advisers.setColumnHidden(self.SPIDS_COL_ID, true);
		self.advisers.attachEvent("onCheck", self.onAdvCheckbox);
		self.advisers.attachEvent("onRowSelect", self.onAdvRowSelect);
		self.advisers.init();
		
        self.dpAdvisers = new dataProcessor(advisers_params.urlData); 
        self.dpAdvisers.setUpdateMode('off');
        self.dpAdvisers.init(self.advisers);
		
		self.advisers.loadXML(advisers_params.urlData);
	},
	
	initAnswers: function() {
		var self = advisers;
		
		self.answers = new dhtmlXGridObject('answers');
		self.answers.setSkin('dhx_blue');
		self.answers.setImagePath(self.DEF_GRIDIMG_PATH);
        self.answers.setHeader(answers_params.colTitles);
		self.answers.attachHeader(answers_params.colFilters);
        self.answers.enableSmartRendering(true);
		self.answers.setInitWidths(answers_params.colWidth);
        self.answers.setColTypes(answers_params.colTypes);
        self.answers.setColAlign(answers_params.colAlign);
        self.answers.setColSorting(answers_params.colSorting);
		self.answers.attachEvent("onEditCell", self.onAnsCellEdit);
		self.answers.init();
		
        self.dpAnswers = new dataProcessor(answers_params.urlData); 
        self.dpAnswers.setUpdateMode('off');
        self.dpAnswers.init(self.answers);
	},
	
	initEditors: function() {
		var self = advisers;
		
		var editorSettings =
            [ 
                ['Source'],
                ['Cut','Copy','Paste','PasteText','PasteFromWord'],
                ['Undo','Redo','-','SelectAll','RemoveFormat'],
                ['Bold','Italic','Underline','Strike'],
                ['NumberedList','BulletedList'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                ['Link','Unlink','Anchor'],
                ['Table','SpecialChar','PageBreak'],
                '/',
                ['Styles','Format','Font','FontSize'],
                ['TextColor','BGColor'],
                ['About']
            ];
		
        self.letterEditor = CKEDITOR.replace('letter_body', {skin : 'v2', toolbar : editorSettings});
		CKEDITOR.instances.letter_body.config.height = '180px';
		
		self.descEditor = CKEDITOR.replace('ddescription', {skin: 'v2', toolbar: editorSettings });
		CKEDITOR.instances.ddescription.config.height = '220px';
	},
	
	initTabs: function() {
		var self = advisers;
		
		self.tabbar = new dhtmlXTabBar('tabbar', 'top');
		self.tabbar.setSkin('dhx_skyblue');
		self.tabbar.setImagePath(self.DEF_GRIDIMG_PATH);
		self.tabbar.addTab('tab4', 'Инфо', '100px');
		self.tabbar.setContent('tab4', 'tab_adv_info');
		self.tabbar.addTab('tab5', 'Специализация', '100px');
		self.tabbar.setContent('tab5', 'tab_specialty');
		self.tabbar.addTab('tab6', 'Фото', '100px');
		self.tabbar.setContent('tab6', 'tab_photo');
		self.tabbar.addTab('tab1', 'Отвыты консультанта', '150px');
		self.tabbar.setContent('tab1', 'answers');
		self.tabbar.addTab('tab2', 'Отправить письмо', '150px');
		self.tabbar.setContent('tab2', 'tab_write_letter');
		self.tabbar.addTab('tab3', 'Активность', '100px');
		self.tabbar.setContent('tab3', 'tab_consult_activity');
		self.tabbar.setTabActive('tab4');
	},
	
	onAdvCheckbox: function(row_id, cell_id, state) {
		var self = advisers;
		self.dpAdvisers.setUpdated(row_id, false);
		self.setCheckedAdvisers(row_id, state);
		self.displayCheckedAdvisers();
	},
	
	setCheckedAdvisers: function(row_id, state) {
		var self = advisers;
		
		if(state)
		{
			self.checkedAdvisers.push(row_id);
		}
		else
		{
			for(var i = 0; i < self.checkedAdvisers.length; i ++)
			{
				if(parseInt(row_id) == parseInt(self.checkedAdvisers[i]))
				{
					self.checkedAdvisers.splice(i, 1);
					break;
				}
			}
		}
	},
	
	onAnsCellEdit: function(stage, row_id) {
		var self = advisers;
		
		if(stage == 2)
		{
			self.dpAnswers.setUpdated(row_id, false);
			return false;
		}
	},
	
	onAdvRowSelect: function(row_id) {
		var self = advisers;
		
		self.answers.clearAll();
		self.answers.loadXML(answers_params.urlData + row_id + '/');
		self.getChartData(row_id);
		self.fillInfoForm(row_id);
		
		self.showMessage(self.HTML_PHOTOMSG_ID, false);
		self.showMessage(self.HTML_DIPLOMMSG_ID, false);
		self.getAdvPhoto();
		self.getDiplomaPhotos();
	},
	
	/**
	 * Отображает коды выбраных консультантов на вкладке "Отправить письмо"
	 */
	displayCheckedAdvisers: function() {
		var self = advisers;
		
		if(self.checkedAdvisers.length)
			$('#consult_codes').text(self.checkedAdvisers.toString());
		else
			$('#consult_codes').text('не выбрано');
	},
	
	initOthers: function() {
		var self = advisers;
		
		$('#filter').click(function() {
			$('#filter_block').toggle();
		});
		
		$(self.HTML_DOCCOUNTRY_ID).change(function() {
			var country_id = $(this).val();
			
			self.getCitiesByCountry(country_id);
		});
	},
	
	initButtons: function() {
		var self = advisers;
		
		$('#send_letter').click(self.sendLetter);
		$('#btn_save').click(self.updateAdvisers);
		$('#upload_phote').click(self.uploadPhoto);
		$('#rm_adv_photo').click(self.rmAdvPhoto);
		$('#upl_diploma_photo').click(self.uplDiplomaPhoto);
		$('#submit_filter_adv').click(self.onFilterClick);
	},
	
	sendLetter: function() {
		var self = advisers;
		var title = $.trim($('#letter_title').val());
		var text = $.trim(self.letterEditor.getData());
		var msg = '';
		
		if(!title)
			msg = 'Отсутствует тема письма';
		if(!self.checkedAdvisers.length)
			msg = 'Консультанты не выбраны';
		if(text.length <= 10)
			msg = 'Отсутствует текст письма';
			
		if(msg)
		{
			alert(msg);
		}
		else
		{
			$.ajax({
				type: "POST",
				url: sendletter_params.urlData,
				dataType: "json",
				data: {title: title, text: text, to: self.checkedAdvisers},
				success: function(result){
					if(result)
						alert('Письма успешно отправлены');
					else
						alert('Ошибка! Не удалось отправить письма');
				},
				error: function(){
					alert("Ошибка! Не удалось отправить письмо");
				}
			});
		}
	},
	
	initChart: function() {
		var self = advisers;
		
		if (navigator.mimeTypes ["application/x-shockwave-flash"])
				self.hasFlash = true;

		if(self.hasFlash)
		{
			self.chart = new FusionCharts("/js/Charts/Column3D.swf", "yearChart", "800", "350", "0", "0");
			self.chart.setTransparent("true");
			self.chart.setJSONUrl('/admin/advisers/yearchart/year/0/sp_id/0/adv_id/0/');
			self.chart.render("chart_year");
		}
	},
	
	getChartData: function(adv_id) {
		var self = advisers;
		var year = $(self.HTML_FILTERYEAR_ID).val();
		var sp_id = $(self.HTML_FILTERSPEC_ID).val();
		
		if(self.hasFlash)
		{
			self.chart.initialDataSet = false;
			self.chart.setJSONUrl('/admin/advisers/yearchart/year/' + year + '/sp_id/' + sp_id + '/adv_id/' + adv_id + '/');
			self.chart.render("chart_year");
		}
	},
	
	fillInfoForm: function(row_id) {
		var self = advisers;
		
		var city_id = parseInt(self.advisers.cellById(row_id, self.CITY_COL_ID).getValue());
		var country_id = parseInt(self.advisers.cellById(row_id, self.COUNTRY_COL_ID).getValue());

		$(self.HTML_DOCSNAME_ID).val(self.advisers.cellById(row_id, self.SNAME_COL_ID).getValue());
		$(self.HTML_DOCNAME_ID).val(self.advisers.cellById(row_id, self.DNAME_COL_ID).getValue());
		$(self.HTML_DOCLNAME_ID).val(self.advisers.cellById(row_id, self.FNAME_C0L_ID).getValue());
		$(self.HTML_DOCEMAIL_ID).val(self.advisers.cellById(row_id, self.EMAIL_COL_ID).getValue());
		$(self.HTML_DOCPHONE_ID).val(self.advisers.cellById(row_id, self.PHONE_COL_ID).getValue());
		$(self.HTML_DOCDIPL_ID).val(self.advisers.cellById(row_id, self.DIPLOMA_COL_ID).getValue());
		self.descEditor.setData(self.advisers.cellById(row_id, self.DOCDESCR_COL_ID).getValue());

		if(country_id)
		{
			$(self.HTML_DOCCOUNTRY_ID).val(country_id);
			self.getCitiesByCountry(country_id, city_id);
		}
		else
		{
			$(self.HTML_DOCCOUNTRY_ID + ' option:selected').removeAttr("selected");
			$(self.HTML_DOCCITY_ID + ' option:selected').removeAttr("selected");
		}
		
		// Установить checkbox'ы специальности
		$(self.HTML_CHKBXCONT_ID + ' input:checkbox').each(function() {$(this).removeAttr('checked')});
		var advSpecialities = self.advisers.cellById(row_id, self.SPIDS_COL_ID).getValue().split(',');
		if(advSpecialities.length)
		{
			
			for(var i in advSpecialities)
			{
				$(self.HTML_CHKBXCONT_ID + ' input:checkbox[value=' + advSpecialities[i] + ']').attr('checked', 'checked');
			}
		}
	},
	
	getCitiesByCountry: function (country_id, setCityId) {
		var self = advisers;
		
		$.ajax({
			type: "GET",
			url: '/admin/advisers/cities_by_country/' + country_id + '/',
			dataType: "json",
			success: function(data){
				var options = '';
				
				for(var value in data)
				{
					options += '<option value="' + data[value].id + '" ' + (setCityId == data[value].id ? ' selected="selected" ' : '') +
						' >' + data[value].name + '</option>';
				}
				
				if(options)
					options = '<option value="0">-- Город --</option>' + options;
				
				$(self.HTML_DOCCITY_ID).html(options);
			},
			error: function(){
				alert("Ошибка! Не получить список городов");
			}
		});
	},
	
	updateAdvisers: function() {
		var self = advisers;
		var row_id = self.advisers.getSelectedId();
		
		if(row_id)
		{
			self.advisers.cellById(row_id, self.SNAME_COL_ID).setValue($(self.HTML_DOCSNAME_ID).val());
			self.advisers.cellById(row_id, self.DNAME_COL_ID).setValue($(self.HTML_DOCNAME_ID).val());
			self.advisers.cellById(row_id, self.FNAME_C0L_ID).setValue($(self.HTML_DOCLNAME_ID).val());
			self.advisers.cellById(row_id, self.EMAIL_COL_ID).setValue($(self.HTML_DOCEMAIL_ID).val());
			self.advisers.cellById(row_id, self.PHONE_COL_ID).setValue($(self.HTML_DOCPHONE_ID).val());
			self.advisers.cellById(row_id, self.DIPLOMA_COL_ID).setValue($(self.HTML_DOCDIPL_ID).val());
			self.advisers.cellById(row_id, self.DOCDESCR_COL_ID).setValue(self.descEditor.getData());
			self.advisers.cellById(row_id, self.CITY_COL_ID).setValue($(self.HTML_DOCCITY_ID).val());
			self.advisers.cellById(row_id, self.COUNTRY_COL_ID).setValue($(self.HTML_DOCCOUNTRY_ID).val());
			
			self.advisers.cellById(row_id, self.FIO_COL_ID).setValue(
				[
					$(self.HTML_DOCSNAME_ID).val(),
					$(self.HTML_DOCNAME_ID).val(), 
					$(self.HTML_DOCLNAME_ID).val()
				].join(' ')
			);
			
			self.advisers.cellById(row_id, self.CONTACTS_COL_ID).setValue(
				[
					$(self.HTML_DOCEMAIL_ID).val(),
					$(self.HTML_DOCPHONE_ID).val()
				].join(', ')
			);
			
			//специальность
			var advSpecialties = new Array();
			$(self.HTML_CHKBXCONT_ID + ' input:checked').each(function() {
				advSpecialties.push($(this).val());
			});
			self.advisers.cellById(row_id, self.SPIDS_COL_ID).setValue(advSpecialties.join(','));
			
			self.dpAdvisers.setUpdated(row_id, true);
			self.dpAdvisers.sendData();
		}
	},
	
	uploadPhoto: function() {
		var self = advisers;
		var row_id = self.advisers.getSelectedId();
		
		if(row_id && $(self.HTML_PHOTOFILE_ID).val())
		{
			$(self.HTML_PHOTOFORM_ID).ajaxSubmit({
				url: '/admin/advisers/uploadphoto/',
				type: 'POST',
				dataType: 'json',
				data: {adv_id: row_id},
				success: function(result) {
					self.showMessage(self.HTML_PHOTOMSG_ID, result.msg);
					if(result.result)
						self.getAdvPhoto();
				},
				error: function() {
					self.showMessage(self.HTML_PHOTOMSG_ID, 'Ошибка: не удалось загрузить фото');
				}
			});
		}
	},
	
	showMessage: function(id, msg) {
		var self = advisers;
		
		if(msg)
		{
			$(id).html(msg);
			$(id).show();
		}
		else
			$(id).hide();
	},
	
	getAdvPhoto: function() {
		var self = advisers;
		var adv_id = self.advisers.getSelectedId();
		
		if(adv_id)
		{
			$.ajax({
				type: "POST",
				url: '/admin/advisers/get_adv_photo/',
				dataType: "json",
				data: {adv_id: adv_id},
				success: function(photo){
					self.displayAdvPhoto(photo);
				},
				error: function(){
					self.showMessage(self.HTML_PHOTOMSG_ID, 'Ошибка: не удалось получить фото');
				}
			});
		}
	},
	
	displayAdvPhoto: function(photo) {
		var self = advisers;
		
		if(photo)
		{
			var src = 'http://' + domain + '/thumbnails/' + photo.substr(0, 2) + '/' + 
				photo.substr(2, 4) + '/cropr_100x100/' + photo;
			$(self.HTML_PHOTOCONT_ID).show();
			$(self.HTML_PHOTOIMG_ID).attr('src', src);
		}
		else
		{
			$(self.HTML_PHOTOCONT_ID).hide();
			$(self.HTML_PHOTOIMG_ID).attr('src', "#");
		}
	},
	
	rmAdvPhoto: function() {
		var self = advisers;
		var adv_id = self.advisers.getSelectedId();
		
		if(adv_id && confirm("Удалить фото?"))
		{
			$.ajax({
				type: "POST",
				url: '/admin/advisers/rm_adv_photo/',
				dataType: "json",
				data: {adv_id: adv_id},
				success: function(result){
					self.showMessage(self.HTML_PHOTOMSG_ID, result.msg);
					if(result.result)
						self.displayAdvPhoto(false);
				},
				error: function(){
					self.showMessage(self.HTML_PHOTOMSG_ID, 'Ошибка: не удалось получить фото');
				}
			});
		}
	},
	
	uplDiplomaPhoto: function() {
		var self = advisers;
		var adv_id = self.advisers.getSelectedId();
		
		if(adv_id)
		{
			$(self.HTML_DIPLOMFORM_ID).ajaxSubmit({
				type: "POST",
				url: '/admin/advisers/upload_diploma_photo/',
				dataType: "json",
				data: {adv_id: adv_id},
				success: function(result){
					self.showMessage(self.HTML_DIPLOMMSG_ID, result.msg);
					if(result.result)
						self.getDiplomaPhotos();
				},
				error: function(){
					self.showMessage(self.HTML_DIPLOMMSG_ID, 'Ошибка: не удалось получить фото');
				}
			});
		}
	},
	
	getDiplomaPhotos: function() {
		var self = advisers;
		var adv_id = self.advisers.getSelectedId();
		
		if(adv_id)
		{
			$.ajax({
				type: "POST",
				url: '/admin/advisers/get_diploma_photos/',
				dataType: "json",
				data: {adv_id: adv_id},
				success: function(photos){
					self.displayDiplomaPhotos(photos);
				},
				error: function(){
					self.showMessage(self.HTML_DIPLOMMSG_ID, 'Ошибка: не удалось получить фото');
				}
			});
		}
	},
	
	displayDiplomaPhotos: function (photos) {
		var self = advisers;
		
		if(photos)
		{
			var str = '';
			for(var i in photos)
			{
				var photo = photos[i];
				
				str += '<div class="dipl_cont">' + 
					'<img src="http://' + domain + '/thumbnails/diplomas/' + photo['photo'].substr(0, 2) + '/' +
					photo['photo'].substr(2, 4) + '/cropr_200x150/' + photo['photo'] + '" />' +
					'<input type="hidden" class="photo_id" value="' + photo['id'] + '" />' +
					'<br /><button class="delete">Удалить</button>' +
					'</div>';
			}
			
			$(self.HTML_DIPLOMPHCONT_ID).html(str);
			$(self.HTML_DIPLOMPHCONT_ID + ' button[class=delete]').each(function() {
				$(this).click(self.rmDiplomaPhoto);
			});
			
		}
		else
		{
			$(self.HTML_DIPLOMPHCONT_ID).html('');
		}
	},
	
	rmDiplomaPhoto: function() {
		var self = advisers;
		var adv_id = self.advisers.getSelectedId();
		var ph_id = $(this).parent().find('input[class=photo_id]').val();
		
		if(ph_id && adv_id)
		{
			if(confirm('Удалить фото?'))
			{
				$.ajax({
					type: "POST",
					url: '/admin/advisers/rm_diploma_photo/',
					dataType: "json",
					data: {adv_id: adv_id, ph_id: ph_id},
					success: function(result){
						self.showMessage(self.HTML_DIPLOMMSG_ID, result.msg);
						
						if(result.result)
							$(self.HTML_DIPLOMPHCONT_ID + ' input[value=' + result.ph_id + ']').parent().remove();	
					},
					error: function(){
						self.showMessage(self.HTML_DIPLOMMSG_ID, 'Ошибка: не удалось удалить фото');
					}
				});
			}
		}
	},
	
	onFilterClick: function() {
		var self = advisers;
		var year = $(self.HTML_FILTERYEAR_ID).val();
		var month = $(self.HTML_FILTERMONTH_ID).val();
		var sp_id = $(self.HTML_FILTERSPEC_ID).val();
		var ans = $(self.HTML_FILTERANS_ID).val();
		
		var url = advisers_params.urlData + 'year/' + year + '/month/' + month + '/sp_id/' + sp_id + '/ans/' + ans;
		
		self.dpAdvisers.serverProcessor = url;
		self.advisers.clearAll();
		self.advisers.loadXML(url);
			
	}
};

$(document).ready(advisers.__construct);
