var answers = {
	imgPath: '/js/dhtmlx/imgs/',
	
	CEL_NUM_CHECK:	0,
	CEL_NUM_ID:		1,
	CEL_NUM_ANS:	2,
	CEL_NUM_SPEC:	3,
	CEL_NUM_AUTHOR:	4,
	CEL_NUM_TITLE:	5,
	CEL_NUM_BODY:	6,
	CEL_NUM_DATE:	7,
	CEL_NUM_RATING:	8,
	CEL_NUM_LINK:	9,
	CEL_NUM_SHOW:	10,
	CEL_NUM_PHOTO:	11,
	CEL_NUM_LINK:	12,
	CEL_NUM_DOCID:	13,
	CEL_NUM_ISANS:	14,
	
	CEL_ADV_CHKBX:	0,
	CEL_ADV_NAME:	1,
	CEL_ADV_EMAIL:	2,
	CEL_ADV_SPEC:	3,
	
	selectedQuestions: new Array(),
	selectedAdvForMail: new Array(),
	
	__construct: function() {
		var self = answers;
		
		self.initLinks();	
		self.initCalendar();
		self.initButtons();
		self.getQuestions();
		self.initTabbar();
		self.initMailCKEditor();
		self.getAdvisers();
		self.getAnsAdvisers();
		self.initArrays();
	},
	
	getQuestions: function() {
		var self = answers;
		var url = self.getTreeUrl(false);

		self.tree = new dhtmlXGridObject('question_grid');	
		self.tree.setImagePath(self.imgPath);
		self.tree.setSkin('dhx_blue');
		self.tree.setHeader(tree_params.colTitles);
		self.tree.setColTypes(tree_params.colTypes);
		self.tree.setInitWidths(tree_params.colWidths);
		self.tree.attachHeader(tree_params.colFilters);
		self.tree.setColAlign(tree_params.colAlign);
		self.tree.setColSorting(tree_params.colSorting);
		self.tree.attachEvent("onCheck", self.onTreeCheck);
        self.tree.attachEvent("onXLE", function(){$('#load_circle').hide(); return true;}); 
        self.tree.attachEvent("onRowSelect", self.onTreeSelect);
		self.tree.kidsXmlFile = tree_params.urlData;
		self.tree.enableSmartRendering(true);
		self.tree.kidsXmlFile = tree_params.kidsUrlData;
		self.tree.init();
		
		self.dpTree = new dataProcessor(url);
		self.dpTree.setUpdateMode('off');
		self.dpTree.init(self.tree);
		
		self.tree.load(url, "xml"); 
	},
	
	getTreeUrl: function(bChild) {
		return tree_params.urlData + 'df/' + $('#date_from').val() + 
			'/dt/' + $('#date_to').val() + '/sp/' + $('#speciality').val() + '/st/' + 
			$('#status').val() + '/';
	},
	
	initButtons: function() {
		var self = answers;
		
		$('#submit_filter').click(self.onFilter);
		$('#save_btn').click(self.onSaveClick);
		$('#send_mail').click(self.sendQuestionByMail);
		$('#add_answer').click(self.addAnswer);
		$('#btn_edit').click(self.onEdit);
		$('#upload_photo').click(self.uploadPhoto);
		$('#remove_btn').click(self.removeFromTree);
	},
	
	onFilter: function() {
		var self = answers;
		var url = self.getTreeUrl(false);

		self.tree.clearAll();
		self.dpTree.serverProcessor = url;
		self.tree.loadXML(url);
		$('#load_circle').show();
		
		self.initArrays();
		self.fillEmailForm();
	},
	
	initLinks: function() {
		var self = answers;
		
		$('#filter').click(function() {
			$('#filter_body').toggle();
		});	
	},
	
	onTreeCheck: function(row_id, cell_id, state) {
		var self = answers;
		
		if(self.CEL_NUM_CHECK == cell_id)
		{
			self.dpTree.setUpdated(row_id, false);
			
			if(!self.isAnswer(row_id))
			{
				self.manageSelected(self.selectedQuestions, row_id, !state);
				self.fillEmailForm();
			}
		}
		
	},
	
	initCalendar: function() {
		var self = answers;
		
        dhtmlxCalendarLangModules = new Array();
        dhtmlxCalendarLangModules['ru'] = {
            langname: 'ru',
            dateformat: '%d-%m-%Y',
            monthesFNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            monthesSNames: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
            daysFNames: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
            daysSNames: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
            weekend: [0, 6],
            weekstart: 1,
            msgClose: "Закрыть",
            msgMinimize: "Свернуть",
            msgToday: "Сегодня"
        };
		
        var startDate = new dhtmlxCalendarObject('date_from', false, {
            isYearEditable: true
        });
        startDate.loadUserLanguage('ru');
        startDate.setYearsRange(1999, 2050);
        startDate.draw();	
        		
        var endDate = new dhtmlxCalendarObject('date_to', false, {
            isYearEditable: true
        });
        endDate.loadUserLanguage('ru');
        endDate.setYearsRange(1999, 2050);
        endDate.draw();			
	},
	
	initTabbar: function() {
		var self = answers;
		
		self.tabbar = new dhtmlXTabBar('tabbar', 'top');
		self.tabbar.setSkin('dhx_skyblue');
		self.tabbar.setImagePath(self.imgPath);
		self.tabbar.addTab('tab1', 'Текст', '100px');
		self.tabbar.setContent('tab1', 'tab_title_body');
		self.tabbar.addTab('tab2', 'Отправить вопрос', '125px');
		self.tabbar.setContent('tab2', 'tab_send_quest');
		self.tabbar.addTab('tab3', 'Добавить ответ');
		self.tabbar.setContent('tab3', 'tab_add_answer');
		self.tabbar.addTab('tab4', 'Фотографии');
		self.tabbar.setContent('tab4', 'tab_photo');
		self.tabbar.setTabActive('tab1');
	},
	
	initMailCKEditor: function() {
		var self = answers;
		var editor_height = '120px';
		var editor_width = '570px';
		var editor_settings = 
			[
                ['Undo','Redo','RemoveFormat'],
                ['Bold','Italic','Underline'],
                ['NumberedList','BulletedList'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                ['Link','Unlink'],
                ['SpecialChar'],
                '/',
                ['Format','Font','FontSize'],
                ['TextColor','BGColor'],
                ['Maximize', '-','About']
			];
		
		self.mailEditor = CKEDITOR.replace('quest_mail_text', {skin : 'v2', toolbar : editor_settings });
		self.mailEditor.config.height = editor_height;
		self.mailEditor.config.width = editor_width;
		self.mailEditor.config.resize_enabled = false;
		
		/*self.answerEditor = CKEDITOR.replace('add_ans_editor', {skin : 'v2', toolbar : editor_settings });
		self.answerEditor.config.height = editor_height;
		self.answerEditor.config.width = editor_width;
		self.answerEditor.config.resize_enabled = false;*/
	},
	
	getAdvisers: function() {
		var self = answers;

		self.advisers = new dhtmlXGridObject('quest_mail_adv');
		self.advisers.setSkin('dhx_blue');
		self.advisers.setImagePath(self.imgPath);
        self.advisers.setHeader(advisers_params.colTitles);
		self.advisers.attachHeader(advisers_params.colFilters);
        self.advisers.enableSmartRendering(true);
		self.advisers.setInitWidths(advisers_params.colWidths);
        self.advisers.setColTypes(advisers_params.colTypes);
        self.advisers.setColAlign(advisers_params.colAlign);
        self.advisers.setColSorting(advisers_params.colSorting);
        self.advisers.attachEvent("onCheck", self.onAdvisersCheck);
		self.advisers.init();
		
        self.dpAdvisers = new dataProcessor(advisers_params.urlData); 
        self.dpAdvisers.setUpdateMode('off');
        self.dpAdvisers.init(self.advisers);
        
        self.advisers.loadXML(advisers_params.urlData);
	},
	
	getAnsAdvisers: function() {
		var self = answers;

		self.ansAdvisers = new dhtmlXGridObject('add_ans_advisers');
		self.ansAdvisers.setSkin('dhx_blue');
		self.ansAdvisers.setImagePath(self.imgPath);
        self.ansAdvisers.setHeader(advisers_params.colTitles);
		self.ansAdvisers.attachHeader(advisers_params.colFilters);
        self.ansAdvisers.enableSmartRendering(true);
		self.ansAdvisers.setInitWidths(advisers_params.colWidths);
        self.ansAdvisers.setColTypes(advisers_params.colTypes);
        self.ansAdvisers.setColAlign(advisers_params.colAlign);
        self.ansAdvisers.setColSorting(advisers_params.colSorting);
        self.ansAdvisers.attachEvent("onRowSelect", self.onSelectAnsAdv);
        self.ansAdvisers.setColumnHidden(self.CEL_ADV_CHKBX, true);
		self.ansAdvisers.init();
		
        self.dpAnsAdvisers = new dataProcessor(advisers_params.urlData); 
        self.dpAnsAdvisers.setUpdateMode('off');
        self.dpAnsAdvisers.init(self.ansAdvisers);
        
        self.ansAdvisers.loadXML(advisers_params.urlData);
	},
	
	onSelectAnsAdv: function(row_id) {
		var self = answers;
		
		$('#adviser_name').val(self.ansAdvisers.cellById(row_id, self.CEL_ADV_NAME).getValue());
	},
	
	onAdvisersCheck: function(row_id, cell_id, state) {
		var self = answers;
		
		self.dpAdvisers.setUpdated(row_id, false);
		self.manageSelected(self.selectedAdvForMail, row_id, !state);
	},
	
	onTreeSelect: function(row_id) {
		var self = answers;	
		
		if(self.isAnswer(row_id))
		{
			$('#text_title').attr('disabled', 'disabled');
			$('#a_for_q_codes').html('');
		}
		else
		{
			$('#text_title').removeAttr('disabled');
			$('#a_for_q_codes').html(row_id);
		}
		
		$('#text_title').val(self.tree.cellById(row_id, self.CEL_NUM_TITLE).getValue());
		$('#text_body').val(self.tree.cellById(row_id, self.CEL_NUM_BODY).getValue());
		
		self.getPhoto();
	},
	
	onSaveClick: function() {
		var self = answers;
		
		self.dpTree.sendData();
	},
	
	onEdit: function() {
		var self = answers;
		var row_id = self.tree.getSelectedId();
		
		if(row_id)
		{
			self.tree.cellById(row_id, self.CEL_NUM_TITLE).setValue($('#text_title').val());
			self.tree.cellById(row_id, self.CEL_NUM_BODY).setValue($('#text_body').val());
			self.dpTree.setUpdated(row_id, true);
		}
	},
	
	isAnswer: function(row_id) {
		var self = answers;	
		
		if(!parseInt(row_id))
			return true;
			
		return false;
	},
	
	manageSelected: function(array, value, remove) {
		var self = answers;
		value = parseInt(value);
		
		if(!remove)
		{
			array.push(value);	
		}
		else
		{
			for (var i in array)
			{
				if(array[i] == value)
				{
					array.splice(i, 1);	
				}
			}	
		}
	},
	
	fillEmailForm: function() {
		var self = answers;
		var mail_text = '';
		
		if(self.selectedQuestions.length)
		{
			mail_text = 'Вопросы:<br />';	
			for(var i in self.selectedQuestions)
			{
				var link = self.tree.cellById(self.selectedQuestions[i], self.CEL_NUM_LINK).getValue();
				mail_text += link + '<br />';
			}
		}
		
		self.mailEditor.setData(mail_text);
	},
	
	initArrays: function() {
		var self = answers;
		
		self.selectedQuestions = new Array();
	},
	
	sendQuestionByMail: function() {
		var self = answers;
		var title = $.trim($('#quest_mail_title').val());
		var body = self.mailEditor.getData();
		var msg = '';
		
		if(!self.selectedQuestions.length)
			msg = "Вопросы не выбраны\n";
		if(!self.selectedAdvForMail.length)
			msg += "Консультанты не выбраны\n";
		if(!title)
			msg += "Тема письма не указана";
		if(body.length < 10)
			msg += "Письмо не может быть пустым";
			
		if(msg)
		{
			alert(msg);
		}
		else
		{
			$.ajax({
				type: "POST",
				url: params.urlMail,
				dataType: "json",
				data: {title: title, body: body, to: self.selectedAdvForMail.join(',')},
				success: function(result){
					if(result)
						alert('Письма успешно отправлены');
					else
						alert('Ошибка! Не удалось отправить писмо');
				},
				error: function(){
					alert("Ошибка! Не удалось отправить письмо");
				}
			});
		}
			
	},
	
	addAnswer: function() {
		var self = answers;
		var usr_id = self.ansAdvisers.getSelectedId();
		var q_id = self.tree.getSelectedId();
		var ansText = $.trim($('#ans_text').val());
		var msg = '';
		
		if(self.isAnswer(q_id) || !q_id)
			msg = "Вопрос не выбран\n";
		if(!usr_id)
			msg += "Консультант не выбран\n";
		if(!ansText)
			msg += "Тест ответа не заполнен";
			
		if(msg)
		{
			alert(msg);
		}
		else
		{
			var id = self.tree.uid();
			self.tree.addRow(id, '', null, q_id);
			self.tree.cellById(id, self.CEL_NUM_ID).setValue(q_id);
			self.tree.cellById(id, self.CEL_NUM_ANS).setValue('-');
			self.tree.cellById(id, self.CEL_NUM_SPEC).setValue('');
			self.tree.cellById(id, self.CEL_NUM_AUTHOR).setValue(self.ansAdvisers.cellById(usr_id, self.CEL_ADV_NAME).getValue());
			self.tree.cellById(id, self.CEL_NUM_TITLE).setValue('');
			self.tree.cellById(id, self.CEL_NUM_BODY).setValue(ansText);
			self.tree.cellById(id, self.CEL_NUM_DATE).setValue('now');
			self.tree.cellById(id, self.CEL_NUM_RATING).setValue(0);
			self.tree.cellById(id, self.CEL_NUM_LINK).setValue('-');
			self.tree.cellById(id, self.CEL_NUM_DOCID).setValue(usr_id);
			self.tree.cellById(id, self.CEL_NUM_SHOW).setValue(1);
			self.tree.cellById(id, self.CEL_NUM_ISANS).setValue(1);
			self.tree.openItem(q_id);
			self.dpTree.setUpdated(id, true);
		}
	},
	
	uploadPhoto: function() {
		var self = answers;
		var row_id = self.tree.getSelectedId();
		
		if(row_id)
		{
			var bAnswer = self.isAnswer(row_id);
			if(bAnswer)
				row_id = self.getAnswerId(row_id);
			
			$('#photo_form').ajaxSubmit({
				url: params.urlUploadPhoto,
				type: 'POST',
				dataType: 'json',
				data: {item_id: row_id, ans: (bAnswer ? 1 : 0)},
				success: function(result) {
					if(result.result)
						self.getPhoto();
					else
						alert(result.msg);
				},
				error: function() {
					self.showMessage('Ошибка: не удалось загрузить фото');
				}
			});
		}
	},
	
	getPhoto: function() {
		var self = answers;
		var row_id = self.tree.getSelectedId();
		
		if(row_id)
		{
			var bAnswer = self.isAnswer(row_id);
			if(bAnswer)
				row_id = self.getAnswerId(row_id);
				
			$.ajax({
				type: "POST",
				url: params.urlGetPhoto,
				dataType: "json",
				data: {item_id: row_id, ans: (bAnswer ? 1 : 0)},
				success: function(photo){
					self.dispalyPhoto(photo);
				},
				error: function(){
					alert("Ошибка: не удалось получить фото вопроса/ответа");
				}
			});
		}	
	},
	
	getAnswerId: function(row_id) {
		var self = answers;
		
		return parseInt(row_id.replace('a', ''));
	},
	
	dispalyPhoto: function(photo) {
		var self = answers;	
		var html = '';
		if(photo)
		{
			var html = '<div class="preview"><img src="' + params.urlPhotoPreview +
				photo.substr(0, 2) + '/' + photo.substr(2, 4) + '/cropr_122x122/' + photo +
				'" /><button>Удалить</button></div>';
		}
		
		$('#photo_preview').html(html);
		$('#photo_preview  button').click(self.onDeletePhoto);
	},
	
	onDeletePhoto: function() {
		var self = answers;
		var row_id = self.tree.getSelectedId();
		var bAnswer = self.isAnswer(row_id);
		if(bAnswer)
			row_id = self.getAnswerId(row_id);
		
		if(row_id && confirm('Удалить фото?'))
		{
			$.ajax({
				type: "POST",
				url: params.urlRemovePhoto,
				dataType: "json",
				data: {item_id: row_id, ans: (bAnswer ? 1 : 0)},
				success: function(result){
					if(result.result)
						self.dispalyPhoto(false);
					else
						alert(result.msg);
				},
				error: function(){
					alert("Ошибка: не удалось удалить фото");
				}
			});
		}
	},
	
	removeFromTree: function() {
		var self = answers;
		var row_id = self.tree.getSelectedId();
		
		if(row_id)
			self.tree.deleteRow(row_id);
	}
}

$(document).ready(answers.__construct);
