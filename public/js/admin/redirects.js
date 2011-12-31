/**
* Редактор редиректов
*
* @author kolex, 2011
* @package btlady-admin
*/

var redirects = {
        
    COLNUM_REDIRECTS_SOURCE: 0,
    COLNUM_REDIRECTS_DESTINATION: 1,

    ERR_POST_CONTENT: 'Ошибка сохранения в БД!',
    
    MSG_SHURE_SAVE: 'Сохранить?',
    
    MSG_SHURE_DELETE: 'Вы уверены что хотите удалить?',
    
    DEF_GRIDIMG_PATH: '/js/dhtmlx/imgs/',
	
    /**
     * Инициализация класса
     */
    __construct: function()    {
    
        var self = redirects;
        
        //список всех редиректов
        self.redirectsgrid = new dhtmlXGridObject('redirectsgridbox');
        self.redirectsgrid.id = 'redirectsgrid';
        self.redirectsgrid.iniParams = redirectsparms;
        self.redirectsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.redirectsgrid.setHeader(redirectsparms.colTitles);
        self.redirectsgrid.attachHeader(redirectsparms.colFilters);
        self.redirectsgrid.enableSmartRendering(true);
        self.redirectsgrid.setInitWidths(redirectsparms.colWidths);
        self.redirectsgrid.setColTypes(redirectsparms.colTypes);
        self.redirectsgrid.setColAlign(redirectsparms.colAlign);
        self.redirectsgrid.setColSorting(redirectsparms.colSorting);
        self.redirectsgrid.enableDragAndDrop(true);
		self.redirectsgrid.setSkin("dhx_blue");
        self.redirectsgrid.init();
		
        self.redirectsgrid.loadXML(redirectsparms.urlData);
        self.dpRedirects = new dataProcessor(redirectsparms.urlData); 
        self.dpRedirects.setUpdateMode('row');
        self.dpRedirects.init(self.redirectsgrid);
        
		$('#butredirectadd').click(function() {
            var id = self.redirectsgrid.uid(); 
            self.redirectsgrid.addRow(id,',,'); 
            self.redirectsgrid.showRow(id);
		});

        $('#butredirectdel').click(function(){
            if(confirm(self.MSG_SHURE_DELETE))
            {
                self.redirectsgrid.deleteSelectedRows(); 
                self.dpRedirects.sendData(); 
            }
        }
        );
        $('#butredirectupd').click(function(){
            if(confirm(self.MSG_SHURE_SAVE))
            {
                self.dpRedirects.sendData(); 
            }
        }
        );
    }
}


$(document).ready(redirects.__construct);
