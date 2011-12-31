/**
 * Админка пользователей
 *
 * @package btlady-admin
 * @author  sokol
 */
var user = {

	G_CELL_ID: 0,
	G_CELL_EMAIL: 1,
	G_CELL_LOGIN: 2,
	G_CELL_REGISTRDATE: 3,
	G_CELL_BIRTHDATE: 4,
	G_CELL_LASTLOGIN: 5,
	G_CELL_WHEREFROM: 6,
	G_CELL_ABOUT: 7,
	G_CELL_SIGN: 8,
	G_CELL_INTERESTS: 9,
	G_CELL_AVATAR:10,
	G_CELL_FASEBOOK: 11,
	G_CELL_VKONTAKTE: 12,
	G_CELL_PASSWORD: 13,

	/**
	 * @const
	 */
	urlData: '/admin/users/getallusers/',
	urlAllroles: '/admin/users/getallroles/',
	urlBindroles: '/admin/users/getbindroles/',

	/**
	 * @var dhtmlXGridObject
	 */
	usergrid: null,

	/**
	 * @var dataProcessor
	 */
	dpUser: null,

	/**
	 * Конструктор класса
	 *
	 * @constructor
	 * @return void
	 */
	__construct: function(callback) {
		var self = user;

		self.initGrid();
		self.initActions();
		self.initAllRolesGrid();
		self.initBindRolesGrid();
	},

	/**
	 * Инициализация грида
	 *
	 * @return void
	 */
	initGrid: function() {
		var self = user;

		self.usergrid = new dhtmlXGridObject('userbox');

		self.usergrid.id = 'usergrid';
		self.usergrid.setImagePath(DEF_GRIDIMG_PATH);
		self.usergrid.setSkin("dhx_blue");

		self.usergrid.setHeader('ID,Почта,Логин,Дата регистрации,Дата рождения,Последний вход,Откуда,О себе,Подпись,,,,,');
		self.usergrid.setColTypes('ro,ed,ed,dhxCalendarA,dhxCalendarA,dhxCalendarA,ed,ed,ed,ed,ed,ed,ed,ed');
		self.usergrid.setInitWidths('100,200,*,140,140,100,100,100,0,0,0,0,0,0');
		self.usergrid.attachHeader('#connector_text_filter,#connector_text_filter,#connector_text_filter,,,,#connector_text_filter,#connector_text_filter,na,na,na,na,na,na');
		self.usergrid.setColAlign('left,left,left,left,left,left,left,left,,,,,,');
		self.usergrid.setColSorting(',,,connector,connector,,,,,,,,,');
		self.usergrid.setDateFormat("%Y-%m-%d 00:00:00");
		self.usergrid.enableMultiselect(false);
		self.usergrid.enableSmartRendering(true);
		self.usergrid.attachEvent('onRowSelect', self.onUsergridSelect);
		self.usergrid.init();
		
		self.dpUser = new dataProcessor(self.urlData);
		self.dpUser.setUpdateMode('off');
		self.dpUser.init(self.usergrid);
		
		user.usergrid.load(user.urlData, "xml");
	}, 

	/**
	 * Установка событий
	 *
	 * @return void
	 */
	initActions: function() {
		var self = user;

		$('button#update').click(self.onUpdateAction);
		$('button#delete').click(self.onDeleteAction);
		$('button#hide').click(self.hideExtEditor);
		$('button#save').click(self.updateUserInGrid);
		$('button#ext_info').click(self.showExtEditor);
		$('button#save_user_roles').click(self.saveUserRoles);
		$('button#remove_user_roles').click(self.removeUserRole);

	},
	
	onDeleteAction: function() {
		var self = user;
		alert('delete');
	},

	/**
	 * Событие клика на Сохранить
	 *
	 * @return void
	 */
	onUpdateAction: function() {
		var self = user;
		self.dpUser.sendData();
	},
	
	onUsergridSelect: function(row_id) {
		var self = user;
		
		self.fillExtForm(row_id);
		self.getBindRoles(row_id);
	},
	
    /**
     * Показываем календарь
     */
    showCalendar: function()
    {
        var mCal;
        dhtmlxCalendarLangModules = new Array();
        dhtmlxCalendarLangModules['ru'] = {
            langname: 'ru',
            dateformat: '%Y-%m-%d',
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

        mCal = new dhtmlxCalendarObject('u_birthdate', false, {
            isYearEditable: true
        });
        mCal.loadUserLanguage('ru');
        mCal.setYearsRange(1999, 2050);
        mCal.draw();
        
        mCal2 = new dhtmlxCalendarObject('u_regdate', false, {
            isYearEditable: true
        });
        mCal2.loadUserLanguage('ru');
        mCal2.setYearsRange(1999, 2050);
        mCal2.draw();
        
        mCal3 = new dhtmlxCalendarObject('u_lastlogin', false, {
            isYearEditable: true
        });
        mCal3.loadUserLanguage('ru');
        mCal3.setYearsRange(1999, 2050);
        mCal3.draw();
    },
	
	showExtEditor: function() {
		var self = user;
		var row_id = self.usergrid.getSelectedId();
		
		if(row_id)
		{
			self.fillExtForm(row_id);		
			$('#editor').show();
			self.showCalendar();
		}
	},
	
	hideExtEditor: function() {
		$('#editor').hide();
	},
	
	fillExtForm: function(row_id) {
		var self = user;
		
		$('#u_login').val(self.usergrid.cellById(row_id, self.G_CELL_LOGIN).getValue());
	    $('#u_password').val('');
		$('#u_email').val(self.usergrid.cellById(row_id, self.G_CELL_EMAIL).getValue());
		$('#u_regdate').val(self.usergrid.cellById(row_id, self.G_CELL_REGISTRDATE).getValue());
		$('#u_birthdate').val(self.usergrid.cellById(row_id, self.G_CELL_BIRTHDATE).getValue());
		$('#u_lastlogin').val(self.usergrid.cellById(row_id, self.G_CELL_LASTLOGIN).getValue());
		$('#u_wherefrom').val(self.usergrid.cellById(row_id, self.G_CELL_WHEREFROM).getValue());
		$('#u_about').val(self.usergrid.cellById(row_id, self.G_CELL_ABOUT).getValue());
		$('#u_sign').val(self.usergrid.cellById(row_id, self.G_CELL_SIGN).getValue());
		$('#u_interests').val(self.usergrid.cellById(row_id, self.G_CELL_INTERESTS).getValue());
		$('#u_facebook').val(self.usergrid.cellById(row_id, self.G_CELL_FASEBOOK).getValue());
		$('#u_vkontakte').val(self.usergrid.cellById(row_id, self.G_CELL_VKONTAKTE).getValue());
	},
	
	updateUserInGrid: function() {
		var self = user;
		var row_id = self.usergrid.getSelectedId();
		if(row_id)
		{
			self.usergrid.cellById(row_id, self.G_CELL_LOGIN).setValue($('#u_login').val());		
			self.usergrid.cellById(row_id, self.G_CELL_PASSWORD).setValue($('#u_password').val());	
			self.usergrid.cellById(row_id, self.G_CELL_EMAIL).setValue($('#u_email').val());
			self.usergrid.cellById(row_id, self.G_CELL_REGISTRDATE).setValue($('#u_regdate').val());
			self.usergrid.cellById(row_id, self.G_CELL_BIRTHDATE).setValue($('#u_birthdate').val());
			self.usergrid.cellById(row_id, self.G_CELL_LASTLOGIN).setValue($('#u_lastlogin').val());
			self.usergrid.cellById(row_id, self.G_CELL_WHEREFROM).setValue($('#u_wherefrom').val());
			self.usergrid.cellById(row_id, self.G_CELL_ABOUT).setValue($('#u_about').val());
			self.usergrid.cellById(row_id, self.G_CELL_SIGN).setValue($('#u_sign').val());
			self.usergrid.cellById(row_id, self.G_CELL_INTERESTS).setValue($('#u_interests').val());
			self.usergrid.cellById(row_id, self.G_CELL_FASEBOOK).setValue($('#u_facebook').val());
			self.usergrid.cellById(row_id, self.G_CELL_VKONTAKTE).setValue($('#u_vkontakte').val());
			self.usergrid.setRowTextBold(row_id);
			self.dpUser.setUpdated(row_id, true);
		}
		self.hideExtEditor();
	},
	
	initAllRolesGrid: function() {
		var self = user;
			
		self.allrolesgrid = new dhtmlXGridObject('all_roles');
		self.allrolesgrid.setImagePath(DEF_GRIDIMG_PATH);
		self.allrolesgrid.setSkin("dhx_blue");
		self.allrolesgrid.setHeader('Навание роли');
		self.allrolesgrid.setColTypes('ro');
		self.allrolesgrid.setInitWidths('*');
		self.allrolesgrid.enableMultiselect(false);
		self.allrolesgrid.enableSmartRendering(true);
		self.allrolesgrid.enableDragAndDrop(true);
		self.allrolesgrid.attachEvent("onDrag", function(){return false;});
		self.allrolesgrid.enableMercyDrag(true);
		self.allrolesgrid.init();
		user.allrolesgrid.load(user.urlAllroles, "xml");	
		
		self.dpAllrolesgrid = new dataProcessor(self.urlAllroles);
		self.dpAllrolesgrid.setUpdateMode('off');
		self.dpAllrolesgrid.init(self.allrolesgrid);	
	},
	
	initBindRolesGrid: function() {
		var self = user;	
		
		self.bindrolesgrid = new dhtmlXGridObject('bind_roles');
		self.bindrolesgrid.setImagePath(DEF_GRIDIMG_PATH);
		self.bindrolesgrid.setSkin("dhx_blue");
		self.bindrolesgrid.setHeader('Навание роли');
		self.bindrolesgrid.setColTypes('ro');
		self.bindrolesgrid.setInitWidths('*');
		self.bindrolesgrid.enableMultiselect(false);
		self.bindrolesgrid.enableSmartRendering(true);
		self.bindrolesgrid.enableDragAndDrop(true);
		self.bindrolesgrid.attachEvent('onDrag', self.addUserRole);
		self.bindrolesgrid.init();
		
		self.dpBindrolesgrid = new dataProcessor(self.urlBindroles);
		self.dpBindrolesgrid.setUpdateMode('off');
		self.dpBindrolesgrid.init(self.bindrolesgrid);	
	},
	
	getBindRoles: function(row_id) {
		var self = user;
		var url = self.urlBindroles + row_id + '/';
		
		self.bindrolesgrid.clearAll();		
		self.dpBindrolesgrid.serverProcessor = url;
		self.bindrolesgrid.loadXML(url);
	},
	
	addUserRole: function()	{
		var self = user;
		var row_id = self.usergrid.getSelectedId();
		if(!row_id)
			return false;
			
		return true;
	},
	
	saveUserRoles: function() {
		var self = user;
		self.dpBindrolesgrid.sendData();
	},
	
	removeUserRole:function() {
		var self = user;
		var role_id = self.bindrolesgrid.getSelectedId();
		
		if(role_id)
		{
			self.bindrolesgrid.deleteRow(role_id);	
		}
	}
};

// Инициализация админки
$(document).ready(user.__construct);
