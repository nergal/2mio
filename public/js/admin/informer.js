/**
 * Админка RSS информеров
 *
 * @package admin
 * @class   informer
 * @author  tretyak
 */
var informer = {
	
	/**
	 * @const
	 */    
	urlData: '/admin/informers/sections/',
	
	/**
	 * @const
	 */	
	urlPageData: '/admin/informers/pages/',
	
	/**
	 * @const
	 */		
	urlRSSData1: '/admin/informers/rss/1',
	
	/**
	 * @const
	 */		
	urlRSSData2: '/admin/informers/rss/2',
	
	/**
	 * @var dhtmlXGridObject
	 */
	sectiongrid: null,
	
	/**
	 * @var dhtmlXGridObject
	 */
	pagegrid: null,
	
	/**
	 * @var dhtmlXGridObject
	 */
	rssgrid1: null,


	/**
	 * @var dhtmlXGridObject
	 */
	rssgrid2: null,
	
	/**
	 * @var dhtmlXGridObject
	 */	
	tabbar: null,

	/**
	 * Конструктор класса
	 *
	 * @constructor
	 * @return void
	 */
	__construct: function(callback) {
		var self = informer;
		self.initSectionGrid();
		self.initPageGrid();
		
		self.initRssGrid1();
		self.initRssGrid2();
		
		self.initButtons();
		
		self.initTab();
	},
	
	/**
	 * Инициализация грида
	 *
	 * @return void
	 */
	initSectionGrid: function() {
		var self = informer;
		
		self.sectiongrid = new dhtmlXGridObject('sectiongridbox');
		self.sectiongrid.setSkin("dhx_blue");
		self.sectiongrid.id = 'sectiongrid';
		
		self.sectiongrid.setImagePath(DEF_GRIDIMG_PATH);
		self.sectiongrid.enableSmartRendering(true);

		self.sectiongrid.setHeader('Название раздела,,,');
		self.sectiongrid.setInitWidths('*,0,0,0');
		
		self.sectiongrid.setColTypes('tree,ro,ro,ro');
		self.sectiongrid.setColAlign('left,,,,');
		self.sectiongrid.setColSorting('connector,na,na,na,na');
		
        self.sectiongrid.attachEvent("onEditCell", function(){return false});
        
		self.sectiongrid.init();
		self.sectiongrid.loadXML(self.urlData);
		self.sectiongrid.kidsXmlFile = self.urlData;
		
        self.dpSec = new dataProcessor(self.urlData);
        self.dpSec.setUpdateMode('off');
        self.dpSec.init(self.sectiongrid);
        
        self.sectiongrid.attachEvent('onRowSelect', self.showPages);
	},
	
	/**
	 * Инициализация грида материалов
	 *
	 * @return void
	 */
	initPageGrid: function() {
		var self = informer;
		
		self.pagesgrid= new dhtmlXGridObject('pagesgridbox');
		self.pagesgrid.setSkin('dhx_blue');
		self.pagesgrid.id = 'pagesgrid';

        self.pagesgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.pagesgrid.enableSmartRendering(true);
		self.pagesgrid.enableDragAndDrop(true);
        
        self.pagesgrid.setHeader('Название,Дата,,,,,,,,,,,,');
        self.pagesgrid.attachHeader('#connector_text_filter,#connector_text_filter,,,,,,,,,,,,');
        
        self.pagesgrid.setInitWidths('*,80,0,0,0,0,0,0,0,0,0,0,0,0');
        self.pagesgrid.setColTypes('ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro');
        self.pagesgrid.setColAlign('left,left,left,left,left,,,,,,,');
        self.pagesgrid.setColSorting('connector,connector,na,na,na,na,na,na,na,na,na');
        
        self.pagesgrid.attachEvent("onEditCell", function(){return false});
        
        self.pagesgrid.init();
        
        self.dpPages = new dataProcessor(self.urlPageData);
        self.dpPages.setUpdateMode('off');
        self.dpPages.init(self.pagesgrid);
	},
	
	/**
	 * Инициализация грида rss материалов 1
	 *
	 * @return void
	 */
	initRssGrid1: function() {
		var self = informer;
		
        self.rssgrid1 = new dhtmlXGridObject('rssbox1');
        self.rssgrid1.id = 'rssgrid1';
        
        self.rssgrid1.setImagePath(self.DEF_GRIDIMG_PATH);
        self.rssgrid1.enableSmartRendering(true);
        self.rssgrid1.enableDragAndDrop(true);
        
        self.rssgrid1.setHeader('Название,Дата,Сортировка');
        //self.rssgrid1.attachHeader('#connector_text_filter,#connector_text_filter,#connector_text_filter');
        
        self.rssgrid1.setInitWidths('*,150,100');
        self.rssgrid1.setColTypes('ed,ro,ed');
        self.rssgrid1.setColAlign('left,center,center');
        self.rssgrid1.setColSorting('na,na,na');
        
		self.rssgrid1.setSkin('dhx_skyblue');
		self.rssgrid1.loadXML(self.urlRSSData1);
		
        self.rssgrid1.init();
        
        self.dpRss1 = new dataProcessor(self.urlRSSData1);
        self.dpRss1.setUpdateMode('off');
        self.dpRss1.init(self.rssgrid1);

        self.rssgrid1.attachEvent("onDrop", function(id){
			self.rssgrid1.cells(id, 2).setValue('0'); // колонка сортировки
			return true;
		});	
	},
	
	/**
	 * Инициализация грида rss материалов 2
	 *
	 * @return void
	 */
	initRssGrid2: function() {
		var self = informer;
		
        self.rssgrid2 = new dhtmlXGridObject('rssbox2');
        self.rssgrid2.id = 'rssgrid2';
        
        self.rssgrid2.setImagePath(self.DEF_GRIDIMG_PATH);
        self.rssgrid2.enableSmartRendering(true);
        self.rssgrid2.enableDragAndDrop(true);
        
        self.rssgrid2.setHeader('Название,Дата,Сортировка');
        //self.rssgrid2.attachHeader('#connector_text_filter,#connector_text_filter,#connector_text_filter');
        
        self.rssgrid2.setInitWidths('*,150,100');
        self.rssgrid2.setColTypes('ro,ro,ed');
        self.rssgrid2.setColAlign('left,center,center');
        self.rssgrid2.setColSorting('na,na,na');
        
		self.rssgrid2.setSkin('dhx_skyblue');
		self.rssgrid2.loadXML(self.urlRSSData2);
		
        self.rssgrid2.init();
        
        self.dpRss2 = new dataProcessor(self.urlRSSData2);
        self.dpRss2.setUpdateMode('off');
        self.dpRss2.init(self.rssgrid2);

        self.rssgrid2.attachEvent("onDrop", function(id){
			self.rssgrid2.cells(id, 2).setValue('0'); // колонка сортировки
			return true;
		});	
	},	
	
    /**
    * Обработчик выбора строки в гриде разделов
    * @param id integer - Id rss категории
    */
    showPages: function (rowId)
    {
        var self = informer;
		
		self.pagesgrid.clearAll();
        self.pagesgrid.loadXML(bindSlashParam(self.urlPageData+'id/{id}/', 'id', rowId));
    },	
    
    initButtons: function()
    {
		var self = informer;
		
		$('#butsaverss').click(function(){
            self.dpRss1.sendData();
            self.dpRss2.sendData();
        });
        
		$('#butdeleterss').click(function(){
			self.rssgrid1.deleteSelectedRows()
            self.dpRss1.sendData(); 

			self.rssgrid2.deleteSelectedRows()
            self.dpRss2.sendData();             
            
        });        
        
	},
    
	/**
	 * Инициализация табов
	 */
	initTab: function() {
		var self = informer;
		
		self.tabbar = new dhtmlXTabBar('tabbar', 'top');
		self.tabbar.setSkin('dhx_skyblue');
		self.tabbar.setImagePath(DEF_GRIDIMG_PATH);
		
		self.tabbar.addTab('tab1', 'mail.ru', '100px');
		self.tabbar.setContent('tab1', 'rsstab1');
		
		self.tabbar.addTab('tab2', 'aif', '100px');
		self.tabbar.setContent('tab2', 'rsstab2');

		self.tabbar.setTabActive('tab1');
	},
}

/**
 * Присвоить значение по шаблону в урле переменной
 * @param url
 * @param name
 * @param val
 * @return
 */
function bindZendParam (url, name, val) 
{
	var re = eval('/{' + name + '}/');
    var ret = url.replace(re, val); /// {id}/
    return ret;
}

// Инициализация админки
$(document).ready(informer.__construct);
