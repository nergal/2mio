/**
* Редактор блогов
* @sokol, 2011
*/

var blogs = {
        
    COLNUM_BLOGS_TITLE: 0,
    COLNUM_BLOGS_BODY: 1,
	COLNUM_BLOGS_MODER: 2,
	COLNUM_COMBO_CELL:3,

    COLNUM_BLOG_TITLE: 0,
    COLNUM_BLOG_BODY: 1,
    
    COLNUM_COMNT_USER: 0,    
    COLNUM_COMNT_DATE: 1,    
    COLNUM_COMNT_BODY: 2, 
    
    ERR_POST_CONTENT: 'Ошибка сохранения в БД!',
    
    MSG_SHURE_DELETE: 'Удалить?',
    
    DEF_GRIDIMG_PATH: '/js/dhtmlx/imgs/',
	
	tabbar: {},
        
    /**
     * Инициализация класса
     */
    __construct: function()    {
    
        var self = blogs;
        
        //список доступных блогов
        self.idBlog = null;
        self.blogsgrid= new dhtmlXGridObject('blogridbox');
        self.blogsgrid.id = 'blogsgrid';
        self.blogsgrid.iniParams = blogsparms;
        self.blogsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.blogsgrid.setHeader(blogsparms.colTitles);
        self.blogsgrid.attachHeader(blogsparms.colFilters);
        self.blogsgrid.enableSmartRendering(true);
        self.blogsgrid.setInitWidths(blogsparms.colWidths);
        //self.blogsgrid.setColumnHidden(self.COLNUM_BLOGS_BODY, true);
        self.blogsgrid.setColumnHidden(self.COLNUM_COMBO_CELL, true);
        self.blogsgrid.setColTypes(blogsparms.colTypes);
        self.blogsgrid.setColAlign(blogsparms.colAlign);
        self.blogsgrid.setColSorting(blogsparms.colSorting);
        self.blogsgrid.enableDragAndDrop(true);
		self.blogsgrid.setSkin("dhx_blue");
        self.blogsgrid.init();
		
		var combo = self.blogsgrid.getCombo(self.COLNUM_COMBO_CELL);
		$('#blog_types option').each(function(key, value){
			combo.put($(value).val(), $(value).text());
		});
		
        self.blogsgrid.loadXML(blogsparms.urlData);
        self.dpBlogs = new dataProcessor(blogsparms.urlData); 
        self.dpBlogs.setUpdateMode('off');
        self.dpBlogs.init(self.blogsgrid);

        
        //Список тем выбранного блога
        self.blogitemsgrid= new dhtmlXGridObject('blogitemsgridbox');
        self.blogitemsgrid.id = 'blogitemsgrid';
        self.blogitemsgrid.iniParams = blogitemsparms;
        self.blogitemsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.blogitemsgrid.setHeader(blogitemsparms.colTitles);
        //self.blogitemsgrid.attachHeader(blogitemsparms.colFilters);
        self.blogitemsgrid.enableSmartRendering(true);
        self.blogitemsgrid.setInitWidths(blogitemsparms.colWidths);
        self.blogitemsgrid.setColumnHidden(self.COLNUM_BLOG_BODY, true);
        self.blogitemsgrid.setColTypes(blogitemsparms.colTypes);
        self.blogitemsgrid.setColAlign(blogitemsparms.colAlign);
        self.blogitemsgrid.setColSorting(blogitemsparms.colSorting);
        self.blogitemsgrid.enableDragAndDrop(true);
		self.blogitemsgrid.setSkin("dhx_blue");
        self.blogitemsgrid.init();
        self.dpBlogItems = new dataProcessor(bindSlashParam(blogitemsparms.urlData, 'id', 0)); 
        self.dpBlogItems.setUpdateMode('off');
        self.dpBlogItems.init(self.blogitemsgrid);
        
        //Список комментариев
        self.commentsgrid= new dhtmlXGridObject('commentsgridbox');
        self.commentsgrid.id = 'commentsgrid';
        self.commentsgrid.iniParams = commentsparms;
        self.commentsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.commentsgrid.setHeader(commentsparms.colTitles);
        //self.commentsgrid.attachHeader(commentsparms.colFilters);
        self.commentsgrid.enableSmartRendering(true);
        self.commentsgrid.setInitWidths(commentsparms.colWidths);
        self.commentsgrid.setColumnHidden(self.COLNUM_COMNT_BODY, true);
        self.commentsgrid.setColTypes(commentsparms.colTypes);
        self.commentsgrid.setColAlign(commentsparms.colAlign);
        self.commentsgrid.setColSorting(commentsparms.colSorting);
        self.commentsgrid.enableDragAndDrop(true);
		self.commentsgrid.setSkin("dhx_blue");
        self.commentsgrid.init();
        self.dpComments = new dataProcessor(bindSlashParam(commentsparms.urlData, 'id', 0)); 
        self.dpComments.setUpdateMode('off');
        self.dpComments.init(self.commentsgrid);
		
        //список всех пользователей
        self.allusersgrid= new dhtmlXGridObject('all_users');
        self.allusersgrid.id = 'allusersgrid';
        self.allusersgrid.iniParams = allusersparams;
        self.allusersgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.allusersgrid.setHeader(allusersparams.colTitles);
        self.allusersgrid.attachHeader(allusersparams.colFilters);
        self.allusersgrid.enableSmartRendering(true);
        self.allusersgrid.setInitWidths(allusersparams.colWidths);
        self.allusersgrid.setColTypes(allusersparams.colTypes);
        self.allusersgrid.setColAlign(allusersparams.colAlign);
        self.allusersgrid.setColSorting(allusersparams.colSorting);
        self.allusersgrid.enableDragAndDrop(true);
		self.allusersgrid.enableMercyDrag(true);
		self.allusersgrid.attachEvent("onDrag", function(){return false;});
        self.allusersgrid.setSkin("dhx_blue");
        self.allusersgrid.init();
        self.allusersgrid.loadXML(allusersparams.urlData);
        self.dpAllUsers = new dataProcessor(allusersparams.urlData); 
        self.dpAllUsers.setUpdateMode('off');
        self.dpAllUsers.init(self.allusersgrid);
		
        //автор блога
        self.authorgrid= new dhtmlXGridObject('blog_author');
        self.authorgrid.id = 'authorgrid';
        self.authorgrid.iniParams = authorparams;
        self.authorgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.authorgrid.setHeader(authorparams.colTitles);
        self.authorgrid.enableSmartRendering(true);
        self.authorgrid.setInitWidths(authorparams.colWidths);
        self.authorgrid.setColTypes(authorparams.colTypes);
        self.authorgrid.setColAlign(authorparams.colAlign);
        self.authorgrid.enableDragAndDrop(true);
		self.authorgrid.attachEvent("onDrag", function(){return self.changeAuthor();});
        self.authorgrid.setSkin("dhx_blue");
        self.authorgrid.init();
        self.dbAuthor = new dataProcessor(authorparams.urlData); 
        self.dbAuthor.setUpdateMode('off');
        self.dbAuthor.init(self.authorgrid);
		
        //все тэги
        self.alltagsgrid= new dhtmlXGridObject('alltagsgrid');
        self.alltagsgrid.id = 'alltagsgrid';
        self.alltagsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.alltagsgrid.setHeader(alltagsparms.colTitles);
        self.alltagsgrid.enableSmartRendering(true);
        self.alltagsgrid.attachHeader(alltagsparms.colFilters);
        self.alltagsgrid.setInitWidths(alltagsparms.colWidths);
        self.alltagsgrid.setColTypes(alltagsparms.colTypes);
        self.alltagsgrid.setColAlign(alltagsparms.colAlign);
        self.alltagsgrid.setColSorting(alltagsparms.colSorting);
        self.alltagsgrid.enableDragAndDrop(true);
        self.alltagsgrid.attachEvent('onDrag', function(){return false;});
        self.alltagsgrid.enableMercyDrag(true);
        self.alltagsgrid.setSkin("dhx_blue");
        self.alltagsgrid.init();
        self.alltagsgrid.loadXML(alltagsparms.urlData);
        self.dpAlltags = new dataProcessor(alltagsparms.urlData); 
        self.dpAlltags.init(self.alltagsgrid);
        
        //привязаные тэги
        self.bindtagsgrid= new dhtmlXGridObject('bindtagsgrid');
        self.bindtagsgrid.id = 'bindtagsgrid';
        self.bindtagsgrid.setImagePath(self.DEF_GRIDIMG_PATH);
        self.bindtagsgrid.setHeader(bindtagsparams.colTitles);
        self.bindtagsgrid.enableSmartRendering(true);
        self.bindtagsgrid.attachHeader(bindtagsparams.colFilters);
        self.bindtagsgrid.setInitWidths(bindtagsparams.colWidths);
        self.bindtagsgrid.setColTypes(bindtagsparams.colTypes);
        self.bindtagsgrid.setColAlign(bindtagsparams.colAlign);
        self.bindtagsgrid.setColSorting(bindtagsparams.colSorting);
        self.bindtagsgrid.enableDragAndDrop(true);
        self.bindtagsgrid.enableMercyDrag(true);
        self.bindtagsgrid.setSkin("dhx_blue");
        self.bindtagsgrid.init();
        self.dpBindTags = new dataProcessor(bindtagsparams.urlData); 
        self.dpBindTags.init(self.bindtagsgrid);
        
        var editor = CKEDITOR.replace('blog_content', {skin : 'v2', toolbar : [['Source','-','Bold','Italic','Underline','Strike','-','Link']]});
        CKEDITOR.instances.blog_content.config.height = '160px';

		CKEDITOR.on( 'dialogDefinition', function( ev )
		{
			var dialogName = ev.data.name;
			var dialogDefinition = ev.data.definition;
		 
			if ( dialogName == 'link' )
			{
				dialogDefinition.removeContents( 'advanced' );
				dialogDefinition.removeContents( 'target' );
			}
		 
			if ( dialogName == 'image' )
			{
				dialogDefinition.removeContents( 'advanced' );
				dialogDefinition.removeContents( 'Link' );
			}
		});
		
        var editor = CKEDITOR.replace('theme_content', {skin : 'v2', 
			toolbar : [
                ['Source'],
                ['Cut','Copy','Paste','PasteText','PasteFromWord','-'],
                ['Undo','Redo','-'],
                '/',
                ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
                ['NumberedList','BulletedList'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                ['Link','Unlink','Anchor'],
                [/*'Image','Flash',*/'Table'],
                '/',
                ['Format','Font','FontSize']
			],
			filebrowserImageUploadUrl : typeof(cke_image_upl_src) != 'undefined' ? cke_image_upl_src : '',
			resize_enabled: false
		});

        CKEDITOR.instances.theme_content.config.height = '190px';

        var editor = CKEDITOR.replace('comment_content', {skin : 'v2', toolbar : [['Source','-','Bold','Italic','Underline','Strike','-','Link']]});
        CKEDITOR.instances.comment_content.config.height = '190px';
        
        self.blogsgrid.attachEvent('onRowSelect', self.selectBlog);
        self.blogitemsgrid.attachEvent('onRowSelect', self.selectBlogItem);
        self.commentsgrid.attachEvent('onRowSelect', self.selectComment);
		
        self.tabbar = new dhtmlXTabBar('tabbar', 'top');
        self.tabbar.setSkin('dhx_skyblue');
        self.tabbar.setImagePath(self.DEF_GRIDIMG_PATH);
        self.tabbar.addTab('tab1', 'Темы блога', '100px');
        self.tabbar.addTab('tab2', 'Автор', '100px');
		self.tabbar.addTab('tab3', 'Тэги', '100px');
        self.tabbar.setContent('tab1', 'blog_themes');
        self.tabbar.setContent('tab2', 'author');
		self.tabbar.setContent('tab3', 'tags');
        self.tabbar.setTabActive('tab1');

        /**
         * Инициализация компонентов после загрузки страницы
         */
        $('#butblogupd').click(function(){
            self.updateBlog(); 
        }
        );
        $('#butblogdel').click(function(){
            if(confirm(self.MSG_SHURE_DELETE))
            {
                self.blogsgrid.deleteSelectedRows(); 
                self.dpBlogs.sendData(); 
                self.clearBlogControls();
            }
        }
        );
		$('#butthemcrt').click(function() {
			self.createBlogItem();
		});
        $('#buthemeupd').click(function(){
            self.updateBlogItem(); 
        }
        );
        $('#buthemedel').click(function(){
            if(confirm(self.MSG_SHURE_DELETE))
            {
                self.blogitemsgrid.deleteSelectedRows(); 
                self.dpBlogItems.sendData(); 
                self.clearBlogItemControls();
            }
        }
        );
        $('#butcommentupd').click(function(){
            self.updateComment(); 
        }
        );
        $('#butcommentdel').click(function(){
            if(confirm(self.MSG_SHURE_DELETE))
            {
                self.commentsgrid.deleteSelectedRows(); 
                self.dpComments.sendData(); 
                self.clearCommentsControls();
            }
        });
		$('#butblogcreate').click(function() {
            var id = self.blogsgrid.uid(); 
            self.blogsgrid.addRow(id,',,1,1'); 
            self.blogsgrid.showRow(id);
		});
		$('#saveauthor').click(function() {
			var blog_id = self.blogsgrid.getSelectedId();
			
            self.dbAuthor.serverProcessor = authorparams.urlData + blog_id + '/';
			self.dbAuthor.sendData();
		});
        $('#add_tag').click(function() {
            var id = self.alltagsgrid.uid(); 
            self.alltagsgrid.addRow(id,''); 
            self.alltagsgrid.showRow(id);
		});
        $('#remove_tag').click(function() {
			var selected_id = self.alltagsgrid.getSelectedId();
			
			if(selected_id)
			{
				if(confirm('Удалить?'))
				{
					self.alltagsgrid.deleteRow(selected_id);
				}
			}
		});
        $('#remove_bind_tag').click(function() {
			var selected_id = self.bindtagsgrid.getSelectedId();
			if(selected_id)
			{
				if(confirm('Удалить?'))
					self.bindtagsgrid.deleteRow(selected_id);
			}	
		});
    },

    /**
    * Обработчик выбора строки в гриде новостей
    * @param id integer Id - компании
    */
    selectBlog: function (rowId)
    {
        var self = blogs;
        
        self.idBlog = rowId;

        //определяем существует ли запись
        if(parseInt(rowId) > 0)
        {
            self.blogitemsgrid.clearAll();
            self.blogitemsgrid.loadXML(bindSlashParam(blogitemsparms.urlData, 'id', rowId));
            
            CKEDITOR.instances.blog_content.setData(self.blogsgrid.cells(rowId, self.COLNUM_BLOGS_BODY).getValue());
            
            self.clearBlogItemControls();
			self.getBlogAuthor(rowId);
			self.getBindTags(rowId);
            
        }
    },
	
	getBlogAuthor: function(row_id)
	{
		var self = blogs;
		
		self.authorgrid.clearAll();
		self.authorgrid.loadXML(authorparams.urlData + String(row_id) + '/');
	},

    getBindTags: function(row_id)
    {
		var self =  blogs;
		var tagUrl = bindtagsparams.urlData + row_id + '/';
		
		self.bindtagsgrid.clearAll();
		self.bindtagsgrid.loadXML(tagUrl);
		self.dpBindTags.serverProcessor = tagUrl;
	},
	
    /**
     * Редактировать блог
     */
    updateBlog:  function ()
    {
        var self = blogs;
        var sel_id = self.blogsgrid.getSelectedId();
		
		if(sel_id)
		{
			self.blogsgrid.cells(self.idBlog, self.COLNUM_BLOG_BODY).setValue(CKEDITOR.instances.blog_content.getData());
			
			self.dpBlogs.setUpdated(self.idBlog, true);
		}
        
        self.dpBlogs.sendData();

        return true; 
    },
	
	changeAuthor:function()
	{
		var self = blogs;
		var sel_id = self.blogsgrid.getSelectedId();

		if(sel_id && self.authorgrid.dragContext.sobj.id == 'allusersgrid')
		{
			self.authorgrid.clearAll();
			return true;
		}
		
		return false;
	},
    
    /**
     * Обработчик выбора строки в гриде новостей
     * @param id integer Id - компании
     */
     selectBlogItem: function (rowId)
     {
         var self = blogs;
         
         self.idBlogItem = rowId;

         //определяем существует ли запись
         if(self.blogitemsgrid.cells(rowId, self.COLNUM_BLOG_TITLE).getValue().length > 1)
         {
             self.commentsgrid.clearAll();
             self.commentsgrid.loadXML(bindSlashParam(commentsparms.urlData, 'id', rowId));
             
             CKEDITOR.instances.theme_content.setData(self.blogitemsgrid.cells(rowId, self.COLNUM_BLOGS_BODY).getValue());
             
             self.clearCommentsControls();
             
         } else
         {
//             self.clearNewsControls();
         }
     },
    
	/**
	 * Создать тему блога
	 */
	createBlogItem: function ()
	{
		var self = blogs;
		var blog_id = self.blogsgrid.getSelectedId();
		
		if(blog_id)
		{
            var id = self.blogitemsgrid.uid(); 
            self.blogitemsgrid.addRow(id,',,'); 
            self.blogitemsgrid.showRow(id);
			
			CKEDITOR.instances.theme_content.setData(' ');
		}
	},
	
     /**
      * Редактировать тему выбранного блога
      */
     updateBlogItem:  function ()
     {
         var self = blogs;
		 var blog_id = self.blogsgrid.getSelectedId();
		 
		 if(blog_id)
		 {
			self.dpBlogItems.serverProcessor = bindSlashParam(blogitemsparms.urlData, 'id', blog_id);
		 }
         
         self.blogitemsgrid.cells(self.idBlogItem, self.COLNUM_BLOG_BODY).setValue(CKEDITOR.instances.theme_content.getData());
         
         self.dpBlogItems.setUpdated(self.idBlogItem, true);
         
         self.dpBlogItems.sendData();

         return true; 
     },

     /**
      * Обработчик выбора строки в гриде новостей
      * @param id integer Id - компании
      */
      selectComment: function (rowId)
      {
          var self = blogs;
          
          self.idComment = rowId;

          //определяем существует ли запись
          if(self.commentsgrid.cells(rowId, self.COLNUM_COMNT_DATE).getValue().length > 1)
          {
              CKEDITOR.instances.comment_content.setData(self.commentsgrid.cells(rowId, self.COLNUM_COMNT_BODY).getValue());
          } else
          {
//              self.clearNewsControls();
          }
      },
    
      /**
       * Редактировать тему выбранного блога
       */
      updateComment:  function ()
      {
          var self = blogs;
          
          self.commentsgrid.cells(self.idComment, self.COLNUM_COMNT_BODY).setValue(CKEDITOR.instances.comment_content.getData());
          
          self.dpComments.setUpdated(self.idComment, true);
          self.dpComments.serverProcessor = bindSlashParam(commentsparms.urlData, 'id', self.blogitemsgrid.getSelectedId())
          self.dpComments.sendData();

          return true; 
      },
      
    /**
     * Очищаем все контролы блога
     * @return
     */
    clearBlogControls: function () 
    {
        var self = blogs;
        
        CKEDITOR.instances.blog_content.setData('');
    },
    
    /**
     * Очищаем все контролы темы блога
     * @return
     */
    clearBlogItemControls: function () 
    {
        var self = blogs;
        
        CKEDITOR.instances.theme_content.setData('');
    },

    /**
     * Очищаем все контролы коммента
     * @return
     */
    clearCommentsControls: function () 
    {
        var self = blogs;
        
        CKEDITOR.instances.comment_content.setData('');
    },
    
    
    isChecked: function(check)
    {
        if($(check).is(':checked')) 
            return 1;
        else
            return 0;
    }

}


$(document).ready(blogs.__construct);
