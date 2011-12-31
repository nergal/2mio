/**
 * Редактор материалов
 * 
 * @author kolex, 2011
 * @package btlady
*/

var pages = {
        
    COL_NUM_SECTION: 1,
    COL_NUM_ARTICLE: 2,

    COL_NUM_SEC_TITLE:  0,    
    COL_NUM_SEC_URL:    1,    
    COL_NUM_SEC_SHOW:   2,    
    COL_NUM_SEC_ORDER:  3,  
	COL_NUM_SEC_TYPEID: 4,
	COL_NUM_SEC_VOTES_ACTIVE: 5,
    
    COL_NUM_PAGES_TITLE: 0,
    COL_NUM_PAGES_DATE:  1,
    COL_NUM_PAGES_PHOTO: 2,
    COL_NUM_PAGES_SID:   3,
    COL_NUM_PAGES_URL:   4,
    COL_NUM_PAGES_SHOW:  5,
    COL_NUM_PAGES_FIX:   6,
    
    COL_NUM_PAGES_TYPE:           7,
    COL_NUM_PAGES_ANNOUNCING:     8,
    COL_NUM_PAGES_RSS:            9,
    COL_NUM_PAGES_PHOTO_INFORMER: 10,
    COL_NUM_PAGES_VIDEO_INFORMER: 11,
    COL_NUM_PAGES_RECOMMENDED:    12,
    COL_NUM_PARTNERS_RSS:         13,

    COL_NUM_ALLTAG_NTID: 1,
    COL_NUM_ALLTAG_TGID: 2,
    
    COL_NUM_COMMENT_BODY:  0,
    COL_NUM_COMMENT_TITLE: 3,

    CNM_PHOTOS_PHOTO:   0,
    CNM_PHOTOS_DEFAULT: 1,
    CNM_PHOTOS_DESCR:   2,
    CNM_PHOTOS_PAGE:    3,
	CNM_PHOTOS_TYPEID:	4,
	CNM_PHOTOS_ORDER:	5,

    CNM_SIMILAR_TITLE:  3,
    
    PHOTOS_ROW_HEIGHT: 28,
    
    ERR_PAGE_CONTENT: 'Ошибка сохранения в БД!',
    
    MIN_LEN_TITLE: 3,
    MIN_LEN_BODY:  10,
    
    ID_SEC_NEWS: 321,
    
    MSG_SHURE_DELETE: 'Вы уверены что хотите удалить материал ?',
    MSG_SHURE_DELETE_SEC: 'Вы уверены что хотите удалить раздел ?',
    MSG_SHURE_DELETE_PHOTO: 'Вы уверены что хотите удалить фото ?',
    MSG_SHURE_UPDATE_SEC: 'Вы уверены что хотите изменить название раздела ?',
    MSG_SELECT_SEC: 'Выберите раздел.',
    MSG_SAVE_NEWS: 'Сохранить материал ?',
    MSG_EMPTY_SECTION: '- Не выбран раздел.\n',
    MSG_EMPTY_ID: '- Не находимся в режиме добавления материала (возможно не нажата кнопка "Добавить").\n',
    MSG_EMPTY_TITLE: '- Не заполнено название материала, или его длина меньше %s символов.\n',
    MSG_EMPTY_BODY: '- Не заполнено содержимое материала, или его объем меньше %s символов.\n',
    MSG_EMPTY_DESCR: '- Не заполнено описание материала, или его объем меньше %s символов.\n',
    
    DEF_TIME: '02:00:00',
    
    DEF_GRIDIMG_PATH: '/js/dhtmlx/imgs/',
	
	SEC_VIDEOGALL_ID: 4,
	SEC_PHOTOGALL_ID: 2,
	
	PAGESMEDIA_PHOTO_ID: 1,
	PAGESMEDIA_VIDEO_ID: 2,
	
	PAGE_TYPE_NEWS:		1,
	PAGE_TYPE_ARTICLE:	2,
	PAGE_TYPE_VIDEO:	3,
	PAGE_TYPE_STAT:		4,
	PAGE_TYPE_PHOTO:	5,
	PAGE_TYPE_WIKI:		6,
	PAGE_TYPE_CATALOG:	7,
	PAGE_TYPE_TOPIC:	8,
	
    /**
     * Инициализация класса
     */
    __construct: function()    {
    
        var self = pages;

		self.similarObject = null;
		
        //грид новых разделов новостей/статей
        self.secgrid = new dhtmlXGridObject('secgridbox');
        self.secgrid.setSkin('dhx_blue');
        self.secgrid.id = 'secgrid';
        self.secgrid.iniParams = secparms;
        self.secgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.secgrid.setHeader(secparms.colTitles);
        self.secgrid.enableSmartRendering(true);
        self.secgrid.setInitWidths(secparms.colWidths);
        self.secgrid.setColumnHidden(self.COL_NUM_SEC_URL, true);
        self.secgrid.setColumnHidden(self.COL_NUM_SEC_SHOW, true);
        self.secgrid.setColumnHidden(self.COL_NUM_SEC_ORDER, true);
        self.secgrid.setColTypes(secparms.colTypes);
        self.secgrid.setColAlign(secparms.colAlign);
        self.secgrid.setColSorting(secparms.colSorting);
        self.secgrid.attachEvent("onEditCell", function(){return false});
        self.secgrid.init();
        self.dpSec = new dataProcessor(secparms.urlData); 
        self.dpSec.setUpdateMode('off');
        self.dpSec.init(self.secgrid);
        self.secgrid.kidsXmlFile = secparms.urlData;

        /**
         * Обработчик события до начала загрузки данных в грид
         */
        self.secgrid.attachEvent("onXLS", function(start,count){
        	$('#sec_animation').show();
        	return true;
        });
        /**
         * Обработчик события после получения данных в грид
         */
        self.secgrid.attachEvent("onXLE", function(grid_obj,count){
        	$('#sec_animation').hide();
        	return true;
        }); 
        self.secgrid.loadXML(secparms.urlData);
        
        //грид всех старых доступных тэгов
        self.idPage = null;
        self.pagesgrid= new dhtmlXGridObject('pagesgridbox');
        self.pagesgrid.setSkin('dhx_blue');
        self.pagesgrid.id = 'pagesgrid';
        self.pagesgrid.iniParams = pagesparms;
        self.pagesgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.pagesgrid.setHeader(pagesparms.colTitles);
        self.pagesgrid.attachHeader(pagesparms.colFilters);
        self.pagesgrid.enableSmartRendering(true);
        self.pagesgrid.setInitWidths(pagesparms.colWidths);
        self.pagesgrid.setColTypes(pagesparms.colTypes);
        self.pagesgrid.setColAlign(pagesparms.colAlign);
        self.pagesgrid.setColSorting(pagesparms.colSorting);
        self.pagesgrid.enableDragAndDrop(true);
        self.pagesgrid.enableAlterCss("","");
        self.pagesgrid.init();
        self.dpPages = new dataProcessor(pagesparms.urlData); 
        self.dpPages.setUpdateMode('off');
        self.dpPages.init(self.pagesgrid);
        
        /**
         * Обработчик события до начала загрузки данных в грид
         */
        self.pagesgrid.attachEvent("onXLS", function(start,count){
        	$('#pages_animation').show();
        	return true;
        });
        /**
         * Обработчик события после получения данных в грид
         */
        self.pagesgrid.attachEvent("onXLE", function(grid_obj,count){
        	$('#pages_animation').hide();
        	return true;
        }); 
        
        /**
         * Обработчик события до записи данных материала
         */
        self.dpPages.attachEvent("onBeforeUpdate",function(id, status) 
        {
        	$('#pages_animation').show();
        	
        	return true;
        });
        /**
         * Обработчик события после записи данных материала
         */
        self.dpPages.attachEvent("onAfterUpdate",function(sid,action,tid,xml_node) 
        {
        	$('#pages_animation').hide();
        	
        	switch(action)
        	{
        		case 'inserted':
        			self.idPage = sid;
        			self.setPhoto(sid);
        			return true;
        		case 'updated': 
        			return true;
        		default: 
        			return false;
        	}
        });
    
        //грид всех старых доступных тэгов
        self.alltagsgrid= new dhtmlXGridObject('alltagsgridbox');
        self.alltagsgrid.setSkin('dhx_blue');
        self.alltagsgrid.id = 'alltagsgrid';
        self.alltagsgrid.iniParams = alltagsparms;
        self.alltagsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.alltagsgrid.setHeader(alltagsparms.colTitles);
        self.alltagsgrid.enableSmartRendering(true);
        self.alltagsgrid.attachHeader(alltagsparms.colFilters);
        self.alltagsgrid.setInitWidths(alltagsparms.colWidths);
        self.alltagsgrid.setColTypes(alltagsparms.colTypes);
        self.alltagsgrid.setColAlign(alltagsparms.colAlign);
        self.alltagsgrid.setColSorting(alltagsparms.colSorting);
        self.alltagsgrid.enableDragAndDrop(true);
        self.alltagsgrid.init();
        self.alltagsgrid.loadXML(alltagsparms.urlData);
        self.dpAlltags = new dataProcessor(alltagsparms.urlData); 
        self.dpAlltags.init(self.alltagsgrid);
        
        /**
         *  Обработчик события до начала загрузки данных в грид
         */
        self.alltagsgrid.attachEvent("onXLS", function(start,count){
        	$('#tags_animation').show();
        	return true;
        });
        /**
         *  Обработчик события после получения данных в грид
         */
        self.alltagsgrid.attachEvent("onXLE", function(grid_obj,count){
        	$('#tags_animation').hide();
        	return true;
        });
        /**
         * Обработчик события до записи данных материала
         */
        self.dpAlltags.attachEvent("onBeforeUpdate",function(id, status) 
        {
        	$('#tags_animation').show();
        	return true;
        });
        /**
         * Обработчик события после записи данных материала
         */
        self.dpAlltags.attachEvent("onAfterUpdate",function(sid,action,tid,xml_node) 
        {
        	$('#tags_animation').hide();
        	return true;
        });
    
        //грид тэгов привязанных к новости
        self.pagetagsgrid = new dhtmlXGridObject('pagetagsgridbox');
        self.pagetagsgrid.setSkin('dhx_blue');
        self.pagetagsgrid.id = 'pagetagsgrid';
        self.pagetagsgrid.iniParams = pagetagsparms;
        self.pagetagsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.pagetagsgrid.setHeader(pagetagsparms.colTitles);
        self.pagetagsgrid.enableSmartRendering(true);
        self.pagetagsgrid.attachHeader(pagetagsparms.colFilters);
        self.pagetagsgrid.setInitWidths(pagetagsparms.colWidths);
        self.pagetagsgrid.setColTypes(pagetagsparms.colTypes);
        self.pagetagsgrid.setColAlign(pagetagsparms.colAlign);
        self.pagetagsgrid.setColSorting(pagetagsparms.colSorting);
        self.pagetagsgrid.enableDragAndDrop(true);
        self.pagetagsgrid.init();
        self.dpPagestags = new dataProcessor(pagetagsparms.urlData); 
        self.dpPagestags.init(self.pagetagsgrid); 

        // Обработчик события до начала загрузки данных в грид
        self.pagetagsgrid.attachEvent("onXLS", function(start,count){
        	$('#pagetags_animation').show();
        	return true;
        });
        // Обработчик события после получения данных в грид
        self.pagetagsgrid.attachEvent("onXLE", function(grid_obj,count){
        	$('#pagetags_animation').hide();
        	return true;
        }); 
        /**
         * Обработчик события до записи
         */
        self.dpPagestags.attachEvent("onBeforeUpdate",function(id, status) 
        {
        	$('#pagetags_animation').show();
        	return true;
        });
        /**
         * Обработчик события после записи
         */
        self.dpPagestags.attachEvent("onAfterUpdate",function(sid,action,tid,xml_node) 
        {
        	$('#pagetags_animation').hide();
        	return true;
        });
        
        //грид комментов новости
        self.commentsgrid = new dhtmlXGridObject('commentsgridbox');
        self.commentsgrid.setSkin('dhx_blue');
        self.commentsgrid.id = 'commentsgrid';
        self.commentsgrid.iniParams = commentsparms;
        self.commentsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.commentsgrid.setHeader(commentsparms.colTitles);
        self.commentsgrid.enableSmartRendering(true);
        self.commentsgrid.setInitWidths(commentsparms.colWidths);
        self.commentsgrid.setColTypes(commentsparms.colTypes);
        self.commentsgrid.setColAlign(commentsparms.colAlign);
        self.commentsgrid.setColSorting(commentsparms.colSorting);
        self.commentsgrid.setAwaitedRowHeight(20);
        self.commentsgrid.enableDragAndDrop(true);
        self.commentsgrid.init();
        self.dpComments = new dataProcessor(commentsparms.urlData); 
        //self.dpComments.init(self.commentsgrid);

        // Обработчик события до начала загрузки данных в грид
        self.commentsgrid.attachEvent("onXLS", function(start,count){
        	$('#comments_animation').show();
        	return true;
        });
        // Обработчик события после получения данных в грид
        self.commentsgrid.attachEvent("onXLE", function(grid_obj,count){
        	$('#comments_animation').hide();
        	return true;
        }); 
        /**
         * Обработчик события до записи данных материала
         */
        self.dpComments.attachEvent("onBeforeUpdate",function(id, status) 
        {
        	$('#comments_animation').show();
        	return true;
        });
        /**
         * Обработчик события после записи данных материала
         */
        self.dpComments.attachEvent("onAfterUpdate",function(sid,action,tid,xml_node) 
        {
        	$('#comments_animation').hide();
        	return true;
        });
        
        /**
         * Обработчики, перетягиваем тэг из списка тэгов
         */
        self.alltagsgrid.attachEvent("onBeforeDrag", function(id){
            
            var valid = String(self.validatePages());
            
            if(valid.length > 0) 
            {
                alert(valid);
                return false;
            }
            
            var n_id = parseInt(self.pagesgrid.getSelectedId());
            
            if(isNaN(n_id) || String(n_id).length > 10) 
            {
                if(confirm(self.MSG_SAVE_NEWS)) 
                    return self.updatePage(); 
                else 
                    return false;
            }
            return true;
        });
        
        self.pagetagsgrid.attachEvent("onDrag",function(sid,tid) 
        {
            name = self.alltagsgrid.cells(sid,0).getValue();
            
            var n_id = parseInt(self.pagesgrid.getSelectedId());
            
            self.pagetagsgrid.addRow(sid,[name,n_id,sid]);
            self.dpPagestags.setUpdated(sid, true);
            
            return false;
        });
        
        self.pagetagsgrid.attachEvent("onBeforeDrag",function(sid,tid) 
        {
            return false;
        });

        /**
         * Обработчик, удаление из списка тэгов новости
         */
        self.alltagsgrid.attachEvent("onDrag",function(sid,tid) 
        {
            return false;
        });
        
        //грид фото материала
        self.photosgrid = new dhtmlXGridObject('photosgridbox');
        self.photosgrid.id = 'photosgrid';
        self.photosgrid.iniParams = photosparms;
        self.photosgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.photosgrid.setSkin("dhx_blue");
        self.photosgrid.setHeader(photosparms.colTitles);
        self.photosgrid.enableSmartRendering(true);
        self.photosgrid.setInitWidths(photosparms.colWidths);
        self.photosgrid.setColTypes(photosparms.colTypes);
        self.photosgrid.setColAlign(photosparms.colAlign);
        self.photosgrid.setColSorting(photosparms.colSorting);
        self.photosgrid.setColumnHidden(self.CNM_PHOTOS_PAGE, true);
        self.photosgrid.setAwaitedRowHeight(self.PHOTOS_ROW_HEIGHT);
        self.photosgrid.enableDragAndDrop(true);
        self.photosgrid.init();
		
        self.dpPhotos = new dataProcessor(photosparms.urlData); 
        self.dpPhotos.setUpdateMode('row');
        self.dpPhotos.init(self.photosgrid);
        
        // Обработчик события после получения данных в грид
        self.photosgrid.attachEvent("onXLE", function(grid_obj,count){
        	pages.photosgrid.forEachRow(function(id){
        		pages.setCellHeight(id, pages.CNM_PHOTOS_PHOTO, self.PHOTOS_ROW_HEIGHT);
            });
        	return true;
        }); 

        self.photosgrid.attachEvent("onRowDblClicked", function(grid_obj,count){
        	
        	pages.idPhoto = parseInt(pages.photosgrid.getSelectedId());

        	if(pages.photosgrid.getSelectedCellIndex() == self.CNM_PHOTOS_PHOTO)
        		pages.createPhotoUploadWindow();
        	else
        		return true;
        });
        
        var editor = CKEDITOR.replace('page_content', {skin : 'v2', toolbar : 
            [ 
                ['Source','-','Save','NewPage','Preview','-','Templates'],
                ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
                ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
                '/',
                ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
                ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                ['Link','Unlink','Anchor'],
                ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak', 'Similar'],
                '/',
                ['Styles','Format','Font','FontSize'],
                ['TextColor','BGColor'],
                ['Maximize', 'ShowBlocks','-','About']
            ],
			filebrowserBrowseUrl : '/js/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : '/js/ckfinder/ckfinder.html?Type=Images',
			filebrowserFlashBrowseUrl : '/js/ckfinder/ckfinder.html?Type=Flash',
			filebrowserUploadUrl : '/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
			filebrowserImageUploadUrl : '/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			filebrowserFlashUploadUrl : '/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
			format_div: { element : 'p', attributes : { 'class' : 'normalDiv' }}
		});  
		CKFinder.SetupCKEditor( editor, '/js/ckfinder/' );
        
        CKEDITOR.instances.page_content.config.height = '250px';
        CKEDITOR.replace('page_desc', {skin : 'v2', toolbar : [['Source','-','Bold','Italic','Underline','Strike','-','Link']]});
        CKEDITOR.instances.page_desc.config.height = '140px';
        
        self.secgrid.attachEvent('onRowSelect', self.showPages);
        
        self.pagesgrid.attachEvent('onRowSelect', self.selectPage);
        
        self.showPages(0);
        
        //self.showComments(0);
        
        self.commentsgrid.attachEvent('onRowSelect', self.selectComment);
        
        CKEDITOR.replace('comment_body', {skin : 'v2', toolbar : [['Source','-','Bold','Italic','Underline','Strike','-','Link']]});
        CKEDITOR.instances.comment_body.config.height = '130px';
        
        /**
         * Инициализация компонентов после загрузки страницы
         */
        
        $('#sec_title').change(function(val){
            $('#sec_url').val(translitStr($('#sec_title').val()));
        }
        );
        if($('#page_url').length){//wiki only
            $('#page_title').change(function(val){
                $('#page_url').val(translitStr($(this).val()));
            });
        }
        $('#butsecadd').click(function(){
            var id = self.secgrid.uid(); 
            var pid = self.secgrid.getSelectedId();
			if(pid)
			{
				var sec_type_id = parseInt(self.secgrid.cells(pid, self.COL_NUM_SEC_TYPEID).getValue());
				self.childTypeId = 1;
				if(sec_type_id)
					self.childTypeId = sec_type_id;
					
			}
            self.secgrid.addRow(id, '', null, pid, null, false);
            
            self.secgrid.showRow(id);
            self.secgrid.selectRowById(id);
            $('#sec_title').val('');
            $('#sec_title').focus();
            $('#sec_url').val('');
        }
        );
        $('#butsecupd').click(function(){
            if(confirm(self.MSG_SHURE_UPDATE_SEC))
            {
                self.updateSection(); 
            }
        }
        );
        $('#butsecdel').click(function(){
        	if(confirm(self.MSG_SHURE_DELETE_SEC))
        	{
	            self.secgrid.deleteSelectedRows();
	            self.dpSec.sendData();
        	}
        }
        );
        $('#butagadd').click(function(){
            var id = self.alltagsgrid.uid(); 
            self.alltagsgrid.addRow(id,'',0); 
            self.alltagsgrid.showRow(id);
        }
        );
        $('#butagdel').click(function(){
            self.alltagsgrid.deleteSelectedRows();
        }
        );
        $('#butpagetagdel').click(function(){
            self.pagetagsgrid.deleteSelectedRows(); 
            self.dpPagestags.sendData(); 
        }
        );
           
        $('#butpageupd').click(function(){
            self.updatePage(); 
        }
        );
        $('#butpageadd').click(function(){
            self.addPage(); 
        }
        );
        
        $('#butpagedel').click(function(){
            if(confirm(self.MSG_SHURE_DELETE))
            {
                self.pagesgrid.deleteSelectedRows(); 
                self.dpPages.sendData(); 
                self.clearPagesControls();
            }
        }
        );
        
        $('#butshowall').click(function(){
            self.showPages(0);
            self.showComments(0);
        }
        );
        
        $('#butshowfix').click(function(){
            self.clearPagesControls();
            self.showFixedNews(); 
        }
        );

        $('#butchgsec').click(function(){
            self.createWindow();
        }
        );
        
        //кнопки комментария
        $('#butcommentupd').click(function(){
            self.updateComment();
        }
        );
        $('#butcommentdel').click(function(){
            self.commentsgrid.deleteSelectedRows();
            CKEDITOR.instances.comment_body.setData('');
        }
        );

        $('#page_fix').change(function(){
            if(self.isChecked('#page_fix'))
            {
                $('#page_announcement').attr('checked',true);
            }
        }
        );

        $('#butphotoadd').click(function(){
            var media_type = self.PAGESMEDIA_PHOTO_ID;

			var id = self.photosgrid.uid(); 
            self.photosgrid.addRow(id,['/i/default.gif',0,'',pages.idPage,media_type]);
            self.setCellHeight(id, self.CNM_PHOTOS_PHOTO, self.PHOTOS_ROW_HEIGHT);
            self.dpPhotos.sendData(); 
            self.photosgrid.showRow(id);
        }
        );

        $('#butphotodel').click(function(){
            if(confirm(self.MSG_SHURE_DELETE_PHOTO))
            {
                self.photosgrid.deleteSelectedRows(); 
                self.dpPhotos.sendData(); 
            }
        }
        );

        $('#butsimilar').click(function(){
        		var self = pages;
        		self.createSimilarWindow(parseInt(self.pagesgrid.getSelectedId()));
        }
        );

        $('#butsimilars').click(function(){
                var self = pages;
                self.createSimilarsWindow(parseInt(self.pagesgrid.getSelectedId()));
        }
        );

        $('#butsetsimilar').click(function(){
                var self = pages;
                self.dpSimilar.sendData();

                var rowId = self.similargrid.getCheckedRows(0);
                self.winSimilar.single = self.similargrid.cells(rowId, self.CNM_SIMILAR_TITLE).getValue();

                self.insertSimilar();
                self.winSimilar.hide();
                $('#boxSimilar').hide();
        }
        );

        $('#butsetsimilars').click(function(){
                var self = pages;
                self.dpSimilars.sendData();
                self.winSimilars.hide();
                $('#boxSimilars').hide();
        }
        );

        self.clearPagesControls();

        self.showCalendar();
        
        self.tabbar = new dhtmlXTabBar('tabbar', 'top');
        self.tabbar.setSkin('dhx_skyblue');
        self.tabbar.setImagePath('/js/dhtmlx/imgs/');
        self.tabbar.addTab('tab1', 'Текст', '100px');
        self.tabbar.setContent('tab1', 'tabpages');
		self.tabbar.addTab('tab2', 'Описание', '100px');
		self.tabbar.setContent('tab2', 'tabdescription');
        self.tabbar.addTab('tab3', 'Комменты', '100px');
        self.tabbar.setContent('tab3', 'tabcomments');
		self.tabbar.addTab('tab4', 'Тэги', '100px');
        self.tabbar.setContent('tab4', 'tabtags');
        self.tabbar.addTab('tab_photos', 'Фотографии', '100px');
        self.tabbar.setContent('tab_photos', 'tabphotos');
		self.tabbar.addTab('tab_addition', 'Дополнительно', '110px');
		self.tabbar.setContent('tab_addition', 'tabaddoption');
        self.tabbar.setTabActive('tab1');
        
        self.dhxWins = new dhtmlXWindows();
        self.dhxWins.enableAutoViewport(false);
        self.dhxWins.attachViewportTo('fspage');
        self.dhxWins.setImagePath(pages.DEF_GRIDIMG_PATH);
 
        self.dhxWinSimilar = new dhtmlXWindows();
        self.dhxWinSimilar.enableAutoViewport(false);
        self.dhxWinSimilar.attachViewportTo('fspage');
        self.dhxWinSimilar.setImagePath(pages.DEF_GRIDIMG_PATH);

        self.dhxWinSimilars = new dhtmlXWindows();
        self.dhxWinSimilars.enableAutoViewport(false);
        self.dhxWinSimilars.attachViewportTo('fspage');
        self.dhxWinSimilars.setImagePath(pages.DEF_GRIDIMG_PATH);

        self.dhxWinPhoto = new dhtmlXWindows();
        self.dhxWinPhoto.enableAutoViewport(false);
        self.dhxWinPhoto.attachViewportTo('tabphotos');
        self.dhxWinPhoto.setImagePath(self.DEF_GRIDIMG_PATH);
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
        
        mCal = new dhtmlxCalendarObject('page_date', false, {
            isYearEditable: true
        });
        mCal.loadUserLanguage('ru');
        mCal.setYearsRange(1999, 2050);
        mCal.draw();
        
    },

    /**
     * Добавляем раздел
     * @return
     */
    updateSection: function () 
    {
        var self = pages;
        var sleshpos = null;
        
        var sec_url_old = new String("");
        var sec_url_new = new String("");
        
        var rowId = parseInt(self.secgrid.getSelectedId());
        if(!isNaN(rowId))
        {
            self.secgrid.cells(rowId, self.COL_NUM_SEC_TITLE).setValue($('#sec_title').val());

				// health/woman-health - к редатированию доступен только последний сегмент url				
				sec_url_old += $('#sec_url_old').val();
				sleshpos = sec_url_old.lastIndexOf("/");
								
				if (sleshpos != -1) {
					sec_url_new = sec_url_old.substring(0, sleshpos+1) + $('#sec_url').val(); 	
				} else {
					sec_url_new = $('#sec_url').val();
				}
				self.secgrid.cells(rowId, self.COL_NUM_SEC_URL).setValue(sec_url_new);
            
            var order = $('#sec_order').val();
			order = order ? order : 0;
            if(String(order).length > 0)
                self.secgrid.cells(rowId, self.COL_NUM_SEC_ORDER).setValue(order);
            else
                self.secgrid.cells(rowId, self.COL_NUM_SEC_ORDER).setValue('');
            
            self.secgrid.cells(rowId, self.COL_NUM_SEC_SHOW).setValue(self.isChecked('#sec_show'));
            
            self.secgrid.cells(rowId, self.COL_NUM_SEC_VOTES_ACTIVE).setValue(self.isChecked('#sec_votes_active'));
            
			if(typeof(self.childTypeId) != 'undefined')
				self.secgrid.cells(rowId, self.COL_NUM_SEC_TYPEID).setValue(self.childTypeId);
			
			delete self.childTypeId;

            self.dpSec.setUpdated(rowId,true); 
            self.dpSec.sendData();
			
            return true; 
        }
        return false; 
    },
    
    /**
    * Обработчик выбора строки в гриде разделов
    * @param id integer Id - компании
    */
    showPages: function (rowId)
    {
        var self = pages;
        
        var sec_url_old = new String(""); 
		
        self.clearPagesControls();
        
        if(parseInt(rowId) > 0)
        {
            self.secId = rowId;
            self.pageTypeId = self.getPageType(rowId);
            $('#sec_id').text(((rowId>0)?'#'+rowId:''));
            $('#sec_title').val(self.secgrid.cells(rowId, self.COL_NUM_SEC_TITLE).getValue());

				// health/woman-health - к редатированию доступен только последний сегмент url
				$('#sec_url_old').val(self.secgrid.cells(rowId, self.COL_NUM_SEC_URL).getValue());
				
				sec_url_old += $('#sec_url_old').val();
				
				$('#sec_url').val(sec_url_old);

				sleshpos = sec_url_old.lastIndexOf("/");				
				if (sleshpos != -1) {
					$('#sec_url').val(sec_url_old.substring(sleshpos+1));	
				} else {
					$('#sec_url').val(sec_url_old);
				}
				
            $('#sec_order').val(self.secgrid.cells(rowId, self.COL_NUM_SEC_ORDER).getValue());
            var sec_show = parseInt(self.secgrid.cells(rowId, self.COL_NUM_SEC_SHOW).getValue());
            if(sec_show == 1)
                var flag = true;
            else
                var flag = false;
            $('#sec_show').attr('checked',flag);
            
            var sec_votes_active = parseInt(self.secgrid.cells(rowId, self.COL_NUM_SEC_VOTES_ACTIVE).getValue());
            if(sec_votes_active == 1)
                var flag = true;
            else
                var flag = false;
            $('#sec_votes_active').attr('checked',flag);
            
				var sec_type_id = parseInt(self.secgrid.cells(rowId, self.COL_NUM_SEC_TYPEID).getValue());
				
				// показывать голосовалку только для галарей				
				if (sec_type_id == 2) {
					$('#sec_votes_container').show();
				} else {
					$('#sec_votes_container').hide();
				} 				
				
			var photo_label = 'Фото по-умолчанию';
			switch(sec_type_id)
			{
				case self.SEC_VIDEOGALL_ID:	photo_label = 'Видео'; break;
				case self.SEC_PHOTOGALL_ID:	photo_label = 'Фото'; break;
			}

			$('#media_label').html(photo_label);
        }
        
        self.pagesgrid.clearAll();
        self.pagesgrid.loadXML(bindSlashParam(pagesparms.urlData+'id/{id}/', 'id', rowId));
    },

    /**
     * Обработчик фильтра замороженных статей
     * @param id integer Id - компании
     */
    showFixedNews: function ()
     {
         var self = pages;
         
         self.pagesgrid.clearAll();
         self.pagesgrid.loadXML(bindSlashParam(pagesparms.urlData+'id/{id}/', 'id', -1));
     },
         
    /**
    * Показать комментарии по статье / новости
    * @param id integer Id - компании
    */
    showComments: function (rowId)
    {
    	var self = pages;
        
        self.commentsgrid.clearAll();
        self.commentsgrid.loadXML(bindSlashParam(commentsparms.urlData+'id/{id}/', 'id', rowId));
    },
    
    /**
    * Обработчик выбора строки в гриде материалов
    * @param id integer Id - компании
    */
    selectPage: function (rowId)
    {
        var self = pages;
        self.idPage = rowId;
        
        self.clearPagesControls();

        if(isNaN(parseInt(self.secId))) 
        {
            var sid = parseInt(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_SID).getValue());
            if(!isNaN(sid)) 
            {
                try {
                	self.secId = sid;
                	self.pageTypeId = self.getPageType(sid);
				} catch(err) {}
            }
        }

        $('#page_title').val(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_TITLE).getValue());
        $('#comment_page_title').text(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_TITLE).getValue());

		$.ajax({
			url:     bindSlashParam(pagesparms.urlContent, 'id', rowId),
			data:    'id=' + rowId,
			type:    'post',
			success: function (content) {
				CKEDITOR.instances.page_desc.setData(content.descr);
				CKEDITOR.instances.page_content.setData(content.body);
				$('#page_furl').html(content.url);
			},
			error: function (response) {        
				alert(response.responseText);
			},
			dataType: 'json'
		});
		    
		var data = String(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_DATE).getValue()).substring(0,10);
		var tima = String(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_DATE).getValue()).substring(11);
		if(String(data).indexOf('-') > 0)
		{
			$('#page_date').val(data);
			$('#page_time').val(tima);
		} 
		else
		{
			$('#page_date').val(getMysqlDate());
			$('#page_time').val(self.DEF_TIME);
		}
            
		$('#page_id').text('#'+self.pagesgrid.getSelectedId());
            
			var page_show = parseInt(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_SHOW).getValue());
		if(!isNaN(page_show)){//pages
			if(page_show == 1)
				var flag = true;
			else
				var flag = false;
				$('#page_show').attr('checked', flag);
		}
            
		var page_fix = parseInt(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_FIX).getValue());
		if(page_fix == 1)
			var flag = true;
		else
			var flag = false;
		$('#page_fix').attr('checked',flag);
            
		var page_rss = parseInt(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_RSS).getValue());
		if(page_rss == 1)
			var flag = true;
		else
			var flag = false;
		$('#page_rss').attr('checked',flag);
		
		var partners_rss = parseInt(self.pagesgrid.cells(rowId, self.COL_NUM_PARTNERS_RSS).getValue());
		if(partners_rss == 1)
			var flag = true;
		else
			var flag = false;
		$('#partners_rss').attr('checked',flag);
        
        var page_photo_informer = parseInt(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_PHOTO_INFORMER).getValue());
        $('#page_photo_informer').attr('checked', (page_photo_informer == 1));      
        
        var page_video_informer = parseInt(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_VIDEO_INFORMER).getValue());
        $('#page_video_informer').attr('checked', (page_video_informer == 1));
		
		var page_recommended = parseInt(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_RECOMMENDED).getValue());
		if(page_recommended == 1)
			var flag = true;
		else
			var flag = false;
		$('#page_recommended').attr('checked',flag);		
            
		var announcing = parseInt(self.pagesgrid.cells(rowId, self.COL_NUM_PAGES_ANNOUNCING).getValue());
		$('#page_announcing').attr('checked', ((announcing == 1) ? true : false));
            
		if(typedoc = 'pages') 
			pictureFolder = '/uploads/';
            
		self.pagetagsgrid.clearAll();
		self.pagetagsgrid.loadXML(bindSlashParam(pagetagsparms.urlData, 'id', rowId));
		self.pagetagsgrid.setColumnHidden(self.COL_NUM_ALLTAG_NTID, true);
		self.pagetagsgrid.setColumnHidden(self.COL_NUM_ALLTAG_TGID, true);
				
		self.setPhoto(self.idPage);
            
		self.showComments(rowId);
            
		self.showPhotos(rowId);
			
		if(typeof(additional_options) != 'undefined')
		{
			additional_options.getOptions(rowId);
		}
    },

    /**
     * Показать фотографии материала
     * @param rowId integer Id - материала
     */
    showPhotos: function (rowId)
    {
     	 var self = pages;
     	 
     	 var url = bindSlashParam(photosparms.urlData, 'id', rowId); ///photosparms.urlData + rowId;

         self.photosgrid.clearAll();
         self.photosgrid.loadXML(url); 

         self.dpPhotos.serverProcessor = url;
    },
    
    /**
     * Записать изменения содержимого новости / статьи в БД
     * @return
     */
    updatePage: function () 
    {
        var self = pages;
        
        var valid = self.validatePages();
        if(String(valid).length != 0 ) 
        {
            alert(valid); 
            return false;
        };
        
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_SID        ).setValue(self.secId);
        
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_TITLE).setValue($('#page_title').val());
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_DATE).setValue($('#page_date').val() + ' ' + $('#page_time').val());

        if($('#page_show').length) {//pages
            self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_SHOW    ).setValue(self.isChecked('#page_show'));
        }
        
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_FIX         ).setValue(self.isChecked('#page_fix'));
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_ANNOUNCING	 ).setValue(self.isChecked('#page_announcing'));
        
        if(!self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_PHOTO).getValue().length){
            self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_PHOTO ).setValue(null);
        }
        
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_RSS).setValue(self.isChecked('#page_rss'));
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PARTNERS_RSS).setValue(self.isChecked('#partners_rss'));
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_PHOTO_INFORMER).setValue(self.isChecked('#page_photo_informer'));
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_VIDEO_INFORMER).setValue(self.isChecked('#page_video_informer'));
        
        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_RECOMMENDED         ).setValue(self.isChecked('#page_recommended'));
        
        //сохраняем контент
        $.ajax({
            url: pagesparms.urlSetContent,
            data: {
        	  id: self.idPage,
        	  descr: CKEDITOR.instances.page_desc.getData(),
        	  body: CKEDITOR.instances.page_content.getData()
            },
            type:    'post',
            success: function (content) {
            },
            error: function (response) {        
                alert(response.responseText);
            },
            dataType: 'json'
        });
        
        self.dpPages.setUpdated(self.idPage, true);
        
        self.dpPages.sendData();

        return true; 
    },

    /**
     * Добавляем новость
     * @return
     */
    addPage: function () 
    {
        var self = pages;
        
        var idSec = self.secgrid.getSelectedId();
        
        if(idSec == null || isNaN(parseInt(idSec))) {
            alert(self.MSG_SELECT_SEC);
            return false;
        }
        
        self.secId = idSec;
        
        var id = self.pagesgrid.uid();

        self.clearPagesControls();
        
        self.idPage = id;

    	var curtime = getCurTime();
    	
        var type_id = self.getPageType();
		
        self.pagesgrid.addRow(id,['',getMysqlDate(),'',idSec,'',0,0,type_id]);
        $('#page_time').val(curtime); 
        
        $('#page_date').val(getMysqlDate());
        
        self.pagesgrid.showRow(id);
        self.pagesgrid.selectRowById(id);

        self.setPhoto(id);

        self.dpPages.setUpdated(self.idPage, true);
        self.dpPages.sendData();
        
        return true; 
    },

    setPhoto: function (id)
    {
        var urlPhoto = bindSlashParam(pagesparms.urlUploadPhoto, 'id', id);
    
        $('#if_page_photo').attr('src', urlPhoto);
    },
    
    /**
    * Обработчик выбора строки в гриде новостей
    * @param id integer Id - компании
    */
    selectComment: function (rowId)
    {
        var self = pages;
        
        CKEDITOR.instances.comment_body.idComment = rowId;
        CKEDITOR.instances.comment_body.setData(self.commentsgrid.cells(rowId, self.COL_NUM_COMMENT_BODY).getValue());
        $('#comment_page_title').text(self.commentsgrid.cells(rowId, self.COL_NUM_COMMENT_TITLE).getValue());
    },
    
    /**
     * Редактирование коммента
     */
    updateComment: function()
    {
        var self = pages;
        
        var rowId = parseInt(self.commentsgrid.getSelectedId());
        if(!isNaN(rowId))
        {
            self.commentsgrid.cells(rowId, self.COL_NUM_COMMENT_BODY).setValue(CKEDITOR.instances.comment_body.getData());
            self.dpComments.setUpdated(rowId,true); 
            self.dpComments.sendData();
        }
    },
    
    /**
     * Очищаем все контроли по новости при создании новой
     * @return
     */
    clearPagesControls: function () 
    {
        var self = pages;
        
        $('#sec_title').val('');
        $('#sec_url').val('');
        $('#sec_id').text('');
        $('#page_id').text('');
        $('#page_title').val('');
        $('#page_date').val('');
        $('#page_time').val(self.DEF_TIME);
        //$('#date_event').val('');
        $('#page_show').attr('checked',false);
        $('#page_announcement').attr('checked',true);
        $('#page_menu_anons').attr('checked',false);
        $('#page_announcing').attr('checked',false);
        $('#page_url').val('');
        $('#page_rss').attr('checked',true);
        $('#partners_rss').attr('checked',false);
        $('#page_photo_informer').attr('checked',false);
        $('#page_video_informer').attr('checked',false);
        $('#page_recommended').attr('checked',false);

        CKEDITOR.instances.page_content.setData('');
        CKEDITOR.instances.page_desc.setData('');
        self.pagetagsgrid.clearAll();
    },
    
    isChecked: function(check)
    {
        if($(check).is(':checked')) 
            return 1;
        else
            return 0;
    },
    
    /**
     * Определить тип материала
     */
    getPageType: function(rowId)
    {
        var self = pages;

        if(typeof(rowId) == 'undefined')
			rowId = self.secgrid.getSelectedId();
			
		var sec_type_id = self.secgrid.cells(rowId, self.COL_NUM_SEC_TYPEID).getValue();
		var page_type_id = ((rowId >= self.TYPE_PAGES_SID_BEGIN && rowId < self.TYPE_PAGES_SID_END) ? 1 : 2);
		if(typeof(secid_to_pageid[sec_type_id]) != 'undefined')
		{
			page_type_id = secid_to_pageid[sec_type_id];
		}
		
    	return page_type_id;
    },
    
    
    //показываем окно с разделами, для смены раздела статьи    
    createWindow: function() 
    {
        var self = pages;
        
        if(typeof self.winSec == 'undefined')
        {
            var id = 'winSections';
    
            self.winSec = self.dhxWins.createWindow(id, 390, 0, 400, 300);
            self.winSec.setText('Изменить раздел');
            self.winSec.button("close").disable();
            
            //грид разделов новостей/статей для переназначения разделов статьям
            self.chgsecgrid = new dhtmlXGridObject('chgsecgridbox');
            self.chgsecgrid.id = 'secgrid';
            self.chgsecgrid.iniParams = secparms;
            self.chgsecgrid.setImagePath(self.DEF_GRIDIMG_PATH);
            self.chgsecgrid.setHeader(secparms.colTitles);
            self.chgsecgrid.enableSmartRendering(true);
            self.chgsecgrid.setInitWidths(secparms.colWidths);
            self.chgsecgrid.setColumnHidden(self.COL_NUM_SEC_URL, true);
            self.chgsecgrid.setColTypes(secparms.colTypes);
            self.chgsecgrid.setColAlign(secparms.colAlign);
            self.chgsecgrid.setColSorting(secparms.colSorting);
            self.chgsecgrid.init();
            self.chgsecgrid.kidsXmlFile = secparms.urlData;
            self.chgsecgrid.loadXML(secparms.urlData);
            self.chgsecgrid.attachEvent("onEditCell",function(){
                return false; // will block any edit operation
            });
            
            self.chgsecgrid.attachEvent("onRowSelect",function(){
                var self = pages;
                var oldSecId = self.secgrid.getSelectedId();
                var newSecId = self.chgsecgrid.getSelectedId();
                if(parseInt(newSecId) > 0)
                    if(parseInt(self.idPage) > 0)
                    {
                        self.pagesgrid.cells(self.idPage, self.COL_NUM_PAGES_SID).setValue(newSecId);
                        self.winSec.hide();
                        self.dpPages.setUpdated(self.idPage, true);
                        self.dpPages.sendData();
                        self.clearPagesControls();
                        self.showPages(oldSecId);
                    }
            });
            
            self.winSec.attachObject('chgsecgridbox');
            $('#boxSec').show();
            
        } else
        {
            if(String(self.winSec.style.display).length < 1)
            {
                self.winSec.hide();
                $('#boxSec').hide();
            } else
            {    
                $('#boxSec').show();
                self.winSec.show();
            }
        }
    },
    
    /**
     * 
     */
    createPhotoUploadWindow: function() 
    {
        var self = pages;
        
        if(typeof self.winPhotoUpload != 'undefined')
        {
        	delete self.winPhotoUpload;        	
        }
        
        var id = 'winPhotoUpload';
		var wndUrl = 'photoform/id/'+self.idPage+'/photoid/'+self.idPhoto+'/gallery/1/';
		var wndTitle = 'Залить фото';
		
        self.winPhotoUpload = self.dhxWinPhoto.createWindow(id, 70, 100, 580, 300);

        self.winPhotoUpload.attachURL(wndUrl);

        self.winPhotoUpload.setText(wndTitle);
    },
    
    /**
     * Установка фото в грид материала
     */
    setDefaultPhotoFromForm: function(id, path)
    {
        var self = pages;

        self.pagesgrid.cells(id, self.COL_NUM_PAGES_PHOTO).setValue(path);
        self.dpPages.setUpdated(id, true);
        self.dpPages.sendData();
    },
    
    /**
     * Установка фото в грид и закрытие окна 
     */
    setPhotoFromForm: function(photoid, path)
    {
        var self = pages;

        self.photosgrid.cells(photoid, self.CNM_PHOTOS_PHOTO).setValue(path);
		self.setCellHeight(self.idPhoto, self.CNM_PHOTOS_PHOTO, self.PHOTOS_ROW_HEIGHT);
        self.dpPhotos.setUpdated(photoid, true);
        self.dpPhotos.sendData();
    },	
    
    //показываем окно с похожими материалами  для синглового блока    
    createSimilarWindow: function(rowId) 
    {
        var self = pages;
        
        if(typeof self.winSimilar == 'undefined')
        {
            var id = 'winSimilars';
    
            self.winSimilar = self.dhxWinSimilar.createWindow(id, 5, 20, 550, 250);
            self.winSimilar.setText('Похожие материалы');
            self.winSimilar.button("close").disable();
            
            //грид похожих материалов (блок с одним материалом внутри)
            self.similargrid = new dhtmlXGridObject('similargridbox');
            self.similargrid.id = 'similargrid';
            self.similargrid.iniParams = similarparms;
            self.similargrid.setImagePath(self.DEF_GRIDIMG_PATH);
            self.similargrid.setHeader(similarparms.colTitles);
            self.similargrid.enableSmartRendering(true);
            self.similargrid.setInitWidths(similarparms.colWidths);
            self.similargrid.setColTypes(similarparms.colTypes);
            self.similargrid.setColAlign(similarparms.colAlign);
            self.similargrid.setColSorting(similarparms.colSorting);
            self.similargrid.init();
            self.dpSimilar = new dataProcessor(similarparms.urlData); 
            self.dpSimilar.setUpdateMode('cell');
            self.dpSimilar.init(self.similargrid);
            self.similargrid.attachEvent("onEditCell", function(state,rowId,cellIndex) {
                var self = pages;
                if(cellIndex==0) {
                    self.winSimilar.single = self.similargrid.cells(rowId, self.CNM_SIMILAR_TITLE).getValue();
                }
            });

            self.winSimilar.attachObject('similarbox');
            $('#boxSimilar').show();
            
        } else
        {
            if(String(self.winSimilar.style.display).length < 1)
            {
                self.winSimilar.hide();
                $('#boxSimilar').hide();
            } else
            {    
                $('#boxSimilar').show();
                self.winSimilar.show();
            }
        }

        self.similargrid.clearAll();
        var url = bindSlashParam(similarparms.urlData, 'id', rowId);
        self.similargrid.loadXML(url);
        self.dpSimilar.serverProcessor = url;

    },
	
    //показываем окно с похожими статьями для множ. блока
    createSimilarsWindow: function(rowId) 
    {
        var self = pages;
        
        if(typeof self.winSimilars == 'undefined')
        {
            var id = 'winSimilars';
    
            self.winSimilars = self.dhxWinSimilars.createWindow(id, 5, 20, 550, 250);
            self.winSimilars.setText('Похожие материалы');
            self.winSimilars.button("close").disable();
            
            //грид похожих материалов (нижний блок с несколькими материалами)
            self.similarsgrid = new dhtmlXGridObject('similarsgridbox');
            self.similarsgrid.id = 'similarsgrid';
            self.similarsgrid.iniParams = similarsparms;
            self.similarsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
            self.similarsgrid.setHeader(similarsparms.colTitles);
            self.similarsgrid.enableSmartRendering(true);
            self.similarsgrid.setInitWidths(similarsparms.colWidths);
            self.similarsgrid.setColTypes(similarsparms.colTypes);
            self.similarsgrid.setColAlign(similarsparms.colAlign);
            self.similarsgrid.setColSorting(similarsparms.colSorting);
            self.similarsgrid.enableMultiselect(true);
            self.similarsgrid.init();
            self.dpSimilars = new dataProcessor(similarsparms.urlData); 
            self.dpSimilars.setUpdateMode('cell');
            self.dpSimilars.init(self.similarsgrid);

            self.winSimilars.attachObject('similarsbox');
            $('#boxSimilars').show();
            
        } else
        {
            if(String(self.winSimilars.style.display).length < 1)
            {
                self.winSimilars.hide();
                $('#boxSimilars').hide();
            } else
            {    
                $('#boxSimilars').show();
                self.winSimilars.show();
            }
        }

        self.similarsgrid.clearAll();
        var url = bindSlashParam(bindSlashParam(similarsparms.urlData, 'id', self.idPage), 'single', 0);
        self.similarsgrid.loadXML(url);
        self.dpSimilars.serverProcessor = url;
    },

	insertSimilar: function(){
		var self = pages;
         // вставляем блок в выбранное место
        var txt = 'ЧИТАЙ ТАКЖЕ - '+self.winSimilar.single;
        if(self.similarObject == null) {
         	self.similarObject = CKEDITOR.dom.element.createFromHtml('<div id="block_similar" style="color:blue;">'+txt+'</div>');
        } else {
            self.similarObject.setHtml(txt);
        }

        var ranges = CKEDITOR.instances.page_content.getSelection().getRanges();

        for ( var range, i = 0 ; i < ranges.length ; i++ )
        {
            range = ranges[ i ];

            if (i > 0)
                self.similarObject = self.similarObject.clone(true);
                
            range.splitBlock('p');
            range.insertNode(self.similarObject);
            if (i == ranges.length - 1)
            {
                range.moveToPosition(self.similarObject, CKEDITOR.POSITION_AFTER_END);
                range.select();
            }
        }
	},

    /**
     * Установка высоты ячейки насильно, хак, нужен, например если в ячейке фото большого размера
     */
    setCellHeight: function(rowid,col,height){
    	pages.photosgrid.cells(rowid,col).cell.firstChild.height = height;
	},
	
    /**
     * Проверка на валидность полей материала
     */
    validatePages: function()
    {
        var self = pages;
        var page_type_id = -1;
        var ret = '';
        
        if(self.idPage)
        	page_type_id = self.pagesgrid.cellById(self.idPage, self.COL_NUM_PAGES_TYPE).getValue();
        
        if(isNaN(parseInt(self.secId)) || parseInt(self.secId) <= 0)
        {
            ret = self.MSG_EMPTY_SECTION;
        }

        if(isNaN(parseInt(self.idPage)) || parseInt(self.idPage) <= 0)
        {
            ret = self.MSG_EMPTY_ID;
        }
        
        if(String($('#page_title').val()).length < self.MIN_LEN_TITLE)
        {
			if(page_type_id != self.PAGE_TYPE_PHOTO)
            	ret = ret +  self.template(self.MSG_EMPTY_TITLE, self.MIN_LEN_TITLE);
        }
        
        if(String(CKEDITOR.instances.page_content.getData()).length < self.MIN_LEN_BODY)
        {
            if(page_type_id != self.PAGE_TYPE_PHOTO)
            	ret = ret + self.template(self.MSG_EMPTY_BODY, self.MIN_LEN_BODY);
        }
        
        if(String(CKEDITOR.instances.page_desc.getData()).length < self.MIN_LEN_BODY)
        {
            if(page_type_id != self.PAGE_TYPE_PHOTO)
         		ret = ret + self.template(self.MSG_EMPTY_DESCR, self.MIN_LEN_BODY);
        }        
        
        return ret;
    },

    /**
     * Шаблонизатор строк
     * @param tpl шаблон, символ подстановки параметра %s
     * @param params парметр или массив параметров ( ... %s ... %s ... )
     */
    template: function (tpl, params) 
    {
        var ret = '';
        self.tpl = tpl;
        
        if(isArray(params))
        {
            $.each(params,
                function (ind, param) {
                    if(String(param).length > 0)
                        self.tpl = self.tpl.substring(0,self.tpl.indexOf('%s')) + param + self.tpl.substring(self.tpl.indexOf('%s')+2);
                }
            );
        } else
            self.tpl = tpl.replace('%s', params);
            
        return self.tpl;
    }
    
}

function DeleteNewsPhoto(n_id, n_photo){
    $.ajax({
        url:    pagesparms.urlDeletePhoto,
        data:    'n_photo=' + n_photo,
        type:    'post',
        success: function (responseText) {
            $('#img_n_photo_'+pages.n_photo).html('');
            pages.pagesgrid.cells(n_id, 10).setValue('');
        },
        error: function (response) {        
            alert(response.responseText);
        }
    });
}

$(document).ready(pages.__construct);
