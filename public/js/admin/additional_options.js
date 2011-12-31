var additional_options = {
	DEF_GRIDIMG_PATH: '/js/dhtmlx/imgs/',
	NUM_CELL_COMBO: 3,
	NUM_CELL_VALUE: 2, 
	NUM_CELL_NAME: 1,
	NUM_CELL_LABEL: 0,
	
	__construct: function() {
		var self = additional_options;
		
		self.initGrid();
		self.initButtons();
	},
	
	initGrid: function() {
		var self = additional_options;
		
        self.grid = new dhtmlXGridObject('optionsgrid');
        self.grid.setSkin('dhx_blue');
        self.grid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.grid.setHeader(optionsparams.colTitles);
        self.grid.enableSmartRendering(true);
        self.grid.setInitWidths(optionsparams.colWidths);
        self.grid.setColumnHidden(self.NUM_CELL_LABEL, true);
        self.grid.setColTypes(optionsparams.colTypes);
        self.grid.setColAlign(optionsparams.colAlign);
        self.grid.setColSorting(optionsparams.colSorting);
        self.grid.setDateFormat("%d.%m.%Y");
		self.grid.attachEvent("onXLE", self.onXLE); 
        self.grid.init();
		
        self.dp = new dataProcessor(optionsparams.urlData); 
        self.dp.setUpdateMode('off');
		self.dp.attachEvent("onBeforeUpdate", self.beforeUpdate);
        self.dp.init(self.grid);
	},
	
	getOptions: function(page_id) {
		var self = additional_options;
		var href = optionsparams.urlData + page_id + '/';
		
		self.grid.clearAll();
		self.initGrid();
		self.dp.serverProcessor = href;
		self.grid.loadXML(optionsparams.urlData + page_id + '/');
	},
	
	initButtons: function() {
		var self = additional_options;
		
		$('#add_option').click(self.addOption);
		$('#remove_option').click(self.removeOption);
		$('#save_option').click(self.saveOption);
	},
	
	addOption: function() {
		var self = additional_options;
		
		var row_id = self.grid.uid();
		self.grid.addRow(row_id, ",,,,");
		self.grid.setCellExcellType(row_id, self.NUM_CELL_COMBO, "co");
		var combo = self.grid.getCustomCombo(row_id, self.NUM_CELL_COMBO);
		$('#opt_type_values option').each(function(key, value){
			combo.put($(value).val(), $(value).text());
		});
	},
	
	removeOption: function() {
		var self = additional_options;
		var row_id = self.grid.getSelectedId();
		if(row_id)
		{
			self.grid.deleteRow(row_id);
		}
	},
	
	saveOption: function() {
		var self = additional_options;
		self.dp.sendData();
	},
	
	onCellChange: function(row_id, cell_ind) {
		var self = additional_options;
		
		if(cell_ind == self.NUM_CELL_COMBO)
		{
			var type = self.grid.cellById(row_id, self.NUM_CELL_COMBO).getValue();
			if(type)
			{
				self.grid.setCellExcellType(row_id, self.NUM_CELL_VALUE, optionsparams.types[type]);
			}
		}
	},
	
	/**
	 * Данные загружены в grid
	 */
	onXLE: function() {
		var self = additional_options;
		
		self.grid.attachEvent("onCellChanged", self.onCellChange);
	},
	
	beforeUpdate: function(id) {
		var self = additional_options;
		
		var msg = '';
		//if(!self.grid.cellById(id, self.NUM_CELL_LABEL).getValue())
			//msg = "Метка не может быть пустой";
		if(!self.grid.cellById(id, self.NUM_CELL_NAME).getValue())
			msg = "Имя не может быть пустым";
		if(!self.grid.cellById(id, self.NUM_CELL_VALUE).getValue())
			msg = "Значение не может быть пустым";
		if(!self.grid.cellById(id, self.NUM_CELL_COMBO).getValue())
			msg = "Тип не может быть пустым";
			
		if(msg)
		{
			alert(msg);
			return false;
		}
		
		return true;
	}
};

$(document).ready(additional_options.__construct);