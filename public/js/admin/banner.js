/**
 * Админка баннерки
 *
 * @package admin
 * @class   banner
 * @author  Nergal
 */
var banner = {
	/**
	 * Индекс скрытого поля с содержанием
	 * @const
	 */
	BODY_CELL: 2,

	/**
	 * Индекс комбобокса
	 * @const
	 */
	COMBO_CELL: 2,

	/**
	 * @const
	 */
	urlData: '/admin/banners/getallbanners/',

	/**
	 * @const
	 */
	urlPlacesData: '/admin/banners/getallplaces/',

	/**
	 * @const
	 */
	urlJoinData: '/admin/banners/getalljoins/',

	/**
	 * Пустой снапшот редактора
	 * @var
	 */
	snapshot: null,

	/**
	 * @var dhtmlXGridObject
	 */
	bannergrid: null,

	/**
	 * @var dhtmlXGridObject
	 */
	placesgrid: null,

	/**
	 * @var dhtmlXGridObject
	 */
	joingrid: null,

	/**
	 * @var dataProcessor
	 */
	dpEvents: null,

	/**
	 * @var dataProcessor
	 */
	dpPlacesEvents: null,

	/**
	 * @var dataProcessor
	 */
	dpJoinEvents: null,

	/**
	 * Конструктор класса
	 *
	 * @constructor
	 * @return void
	 */
	__construct: function(callback) {
		var self = banner;

		self.initPlacesGrid();
		self.initBannerGrid();
		self.initJoinGrid();

		self.initJoinDataprocessor();
		self.initPlacesDataprocessor();
		self.initDataprocessor();

		self.initActions();
	},

	makeDirty: function() {
		$('button#add').attr('disabled', 'disabled');
		$('button#delete').attr('disabled', 'disabled');
		$('button#update').attr('disabled', 'disabled');

		$('button#save').removeAttr('disabled');
		$('button#cancel').removeAttr('disabled');
		$('button#hide').attr('disabled', 'disabled');
	},

	/**
	 * Инициализация грида
	 *
	 * @return void
	 */
	initBannerGrid: function() {
		var self = banner;

		self.bannergrid = new dhtmlXGridObject('bannerbox');

		self.bannergrid.id = 'bannergrid';
		self.bannergrid.setImagePath(DEF_GRIDIMG_PATH);
		self.bannergrid.setSkin("dhx_blue");

		self.bannergrid.setHeader('ID,Имя баннера,Код баннера');
		self.bannergrid.setColTypes('ro,ed,txt');
		self.bannergrid.setInitWidths('40,*,*');
		self.bannergrid.attachHeader('#connector_text_filter,#connector_text_filter,');
		self.bannergrid.setColAlign('right,left,center');
		self.bannergrid.enableSmartXMLParsing(false);

		self.bannergrid.enableDragAndDrop(true);
		self.bannergrid.enableMercyDrag(true);

		self.bannergrid.setColumnHidden(self.BODY_CELL, true);
		self.bannergrid.enableMultiselect(true);

		self.bannergrid.attachEvent("onDragIn", function() {
			return false;
		});

		self.bannergrid.enableSmartRendering(true);

		self.bannergrid.attachEvent("onEditCell", function(stage) {
			if (stage == 2) {
				$('button#reset').removeAttr('disabled');
			}
			return true;
		});

		self.bannergrid.attachEvent("onCheck", function() {
			$('button#reset').removeAttr('disabled');
		});

		self.bannergrid.attachEvent("onRowSelect", function(row_id) {
			$('#new').hide();

			var data = self.bannergrid.cellById(row_id, banner.BODY_CELL).getValue();
			data = Base64.decode(data);

			var title = self.bannergrid.cellById(row_id, banner.BODY_CELL - 1).getValue();

			$('#editor #title').val(title);

			$("#banner_code").text(data);
			$('#editor').slideDown();
		});

		self.bannergrid.init();
		self.loadData();
	},

	/**
	 * Инициализация грида
	 *
	 * @return void
	 */
	initPlacesGrid: function() {
		var self = banner;

		self.placesgrid = new dhtmlXGridObject('placesbox');

		self.placesgrid.id = 'placesgrid';
		self.placesgrid.setImagePath(DEF_GRIDIMG_PATH);
		self.placesgrid.setSkin("dhx_blue");

		self.placesgrid.setHeader('Код зоны,Имя зоны');
		self.placesgrid.setColTypes('tree,ed');
		self.placesgrid.setInitWidths('*,*');
		self.placesgrid.attachHeader(',#connector_text_filter');
		self.placesgrid.setColAlign('left,left');

		self.placesgrid.enableSmartRendering(true);

		self.placesgrid.attachEvent("onEditCell", function(stage) {
			if (stage == 2) {
				$('button#reset').removeAttr('disabled');
			}
			return true;
		});

		self.placesgrid.attachEvent("onCheck", function() {
			$('button#reset').removeAttr('disabled');
		});

		self.placesgrid.attachEvent("onRowSelect", function(row_id) {
			row_id = parseInt(row_id);
			self.joingrid.clearAll();
			self.joingrid.load(self.urlJoinData + "id/" + row_id, "xml");
		});

		self.placesgrid.init();
		self.placesgrid.load(self.urlPlacesData, "xml");
	},


	/**
	 * Инициализация грида
	 *
	 * @return void
	 */
	initJoinGrid: function() {
		var self = banner;

		self.joingrid = new dhtmlXGridObject('joinbox');

		self.joingrid.id = 'joingrid';
		self.joingrid.setImagePath(DEF_GRIDIMG_PATH);
		self.joingrid.setSkin("dhx_blue");

		self.joingrid.setHeader(',Код зоны,Имя зоны');
		self.joingrid.setColTypes('ro,ro,ro');
		self.joingrid.setInitWidths('*,*,*');
		self.joingrid.attachHeader(',,#connector_text_filter');
		self.joingrid.setColAlign(',left,left');

		self.joingrid.enableMultiselect(true);
		self.joingrid.enableSmartRendering(true);

		self.joingrid.enableDragAndDrop(true);

		self.joingrid.setColumnHidden(0, true);

		self.joingrid.gridToGrid = function(rowId, sgrid, tgrid) {
            var z=[];
            for (var i = 0; i < sgrid.getColumnsNum(); i++) {
                z[i] = sgrid.cells(rowId, i).getValue();
            }

            var index = self.placesgrid.getSelectedId();

            col = (typeof index == 'object')
            	? self.placesgrid.cellByIndex(0, 1).getValue()
            	: self.placesgrid.cellById(parseInt(index), 0).getValue();

            z[0] = col;
            z.unshift('');
            return z;
        }

		self.joingrid.attachEvent("onEditCell", function(stage) {
			if (stage == 2) {
				$('button#reset').removeAttr('disabled');
			}
			return true;
		});

		self.joingrid.attachEvent("onCheck", function() {
			$('button#reset').removeAttr('disabled');
		});

		self.joingrid.init();
		self.joingrid.load(self.urlJoinData, "xml");
	},

	/**
	 * Инициализация ДП
	 *
	 * @return void
	 */
	initDataprocessor: function() {
		var self = banner;

		self.dpEvents = new dataProcessor(self.urlData);
		self.dpEvents.setUpdateMode('off');

		self.dpEvents.attachEvent("onAfterUpdate", function(sid, action, tid, xml_node) {
			switch(action) {
				case 'updated':
					self.bannergrid.cells(sid, self.bannergrid.getColumnsNum() - 1).setValue(null);
					break;
				case 'error':
					self.bannergrid.setRowTextStyle(sid, 'background-color:#f5c6cd');
					break;
				default:
					var style = '';
		    }
		});
		self.dpEvents.init(self.bannergrid);
	},

	/**
	 * Инициализация ДП
	 *
	 * @return void
	 */
	initPlacesDataprocessor: function() {
		var self = banner;

		self.dpPlacesEvents = new dataProcessor(self.urlPlacesData);
		self.dpPlacesEvents.setUpdateMode('off');

		self.dpPlacesEvents.attachEvent("onAfterUpdate", function(sid, action, tid, xml_node) {
			if (action == 'error') self.placesgrid.setRowTextStyle(sid, 'background-color:#f5c6cd');
		});
		self.dpPlacesEvents.init(self.placesgrid);
	},


	/**
	 * Инициализация ДП
	 *
	 * @return void
	 */
	initJoinDataprocessor: function() {
		var self = banner;

		self.dpJoinEvents = new dataProcessor(self.urlJoinData);
		self.dpJoinEvents.setUpdateMode('off');

		self.dpJoinEvents.attachEvent("onAfterUpdate", function(sid, action, tid, xml_node) {
			if (action == 'error') self.joingrid.setRowTextStyle(sid, 'background-color:#f5c6cd');
		});
		self.dpJoinEvents.init(self.joingrid);
	},

	/**
	 * Установка событий
	 *
	 * @return void
	 */
	initActions: function() {
		var self = banner;

		$('button#add.banners')    .click(self.onAddAction);
		$('button#add.places')    .click(self.onAddPlaceAction);

		$('button#delete.banners') .click(self.onDeleteAction);
		$('button#delete.places') .click(self.onDeletePlaceAction);
		$('button#delete.joins') .click(self.onDeleteJoinAction);

		$('button#update') .click(self.onUpdateAction);
		$('button#save')   .click(self.onSaveAction);
		$('button#cancel') .click(self.onCancelAction);
		$('button#reset')  .click(self.onResetAction);
		$('button#hide')   .click(self.onHideAction);

		$('#editor #title').keydown(function() {
			self.makeDirty();
		});

		$('#banner_code').keydown(function() {
			self.makeDirty();
		});
	},

	loadData: function() {
		banner.bannergrid.load(banner.urlData, "xml");
	},

	onAddPlaceAction: function() {
		var self = banner;

		var id = self.placesgrid.uid();

		self.placesgrid.addRow(id, ['', '']);
		self.placesgrid.showRow(id);
	},

	/**
	 * Событие клика на Удалить
	 *
	 * @return void
	 */
	onDeletePlaceAction: function() {
		$('button#reset').removeAttr('disabled');
		banner.placesgrid.deleteSelectedRows();
	},

	/**
	 * Событие клика на Удалить
	 *
	 * @return void
	 */
	onDeleteJoinAction: function() {
		$('button#reset').removeAttr('disabled');
		banner.joingrid.deleteSelectedRows();
	},

	/**
	 * Событие клика на Добавление
	 *
	 * @return void
	 */
	onAddAction: function() {
		var self = banner;

		$('#new').show();

		$('button#save').removeAttr('disabled');
		$('button#cancel').removeAttr('disabled');
		$('button#hide').attr('disabled', 'disabled');

		$('#banner_code').empty();
		$('#editor #title').empty();

		$('#grid').slideUp();
		$('#editor').slideDown();
	},

	/**
	 * Событие клика на Сохранить
	 *
	 * @return void
	 */
	onSaveAction: function() {
		var self = banner;
		self.onHideAction();

		$('button#reset').removeAttr('disabled');

		if (!$('#grid:visible').length) {
			var id = self.bannergrid.uid();
			var data = [
			            '',
			            $('#editor #title').val(),
			            Base64.encode($('#banner_code').val())
			];

			self.bannergrid.addRow(id, data, 0);
			self.bannergrid.showRow(id);
		} else {
			var id = self.bannergrid.getSelectedId();

			var title = $('#editor #title').val();
			var titled = self.bannergrid.cellById(id, self.BODY_CELL - 1);
			titled.setValue(title);

			var data = $('#banner_code').val();
			var item = self.bannergrid.cellById(id, self.BODY_CELL);
			item.setValue(Base64.encode(data));

			self.dpEvents.setUpdated(id, true);
			self.dpEvents.setUpdateMode('row');
		}

		$('.buttons button').removeAttr('disabled');
		$('button#save').attr('disabled', 'disabled');
		$('button#cancel').attr('disabled', 'disabled');
		$('button#hide').removeAttr('disabled');

		$('#grid').slideDown();
	},

	/**
	 * Событие клика на Отмену
	 *
	 * @return void
	 */
	onCancelAction: function() {
		var self = banner;

		self.onHideAction();
		$('#grid').slideDown();

		$('button#add').removeAttr('disabled');
		$('button#delete').removeAttr('disabled');
		$('button#update').removeAttr('disabled');
		$('button#save').attr('disabled', 'disabled');
		$('button#cancel').attr('disabled', 'disabled');
		$('button#hide').removeAttr('disabled');
	},

	/**
	 * Событие клика на Обновление
	 *
	 * @return void
	 */
	onUpdateAction: function() {
		$('button#reset').attr('disabled', 'disabled');
		banner.dpEvents.sendData();
		banner.dpPlacesEvents.sendData();
		banner.dpJoinEvents.sendData();
	},

	/**
	 * Событие клика на Удалить
	 *
	 * @return void
	 */
	onDeleteAction: function() {
		$('button#reset').removeAttr('disabled');
		banner.bannergrid.deleteSelectedRows();
	},

	/**
	 * Событие клика на Сбросить
	 *
	 * @return void
	 */
	onResetAction: function() {
		var self = banner;

		$('button#reset').attr('disabled', 'disabled');

		self.bannergrid.clearAll();
		self.placesgrid.clearAll();
		self.joingrid.clearAll();

		self.bannergrid.load(self.urlData, "xml");
		self.placesgrid.load(self.urlPlacesData, "xml");
		self.joingrid.load(self.urlJoinData, "xml");
	},

	/**
	 * Событие клика на Скрыть
	 *
	 * @return void
	 */
	onHideAction: function() {
		$('#editor').slideUp();
	}
};

// Инициализация админки
$(document).ready(banner.__construct);