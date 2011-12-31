/**
 * Общая логика Javascript для админки
 */

var DEF_GRIDIMG_PATH = '/js/dhtmlx/imgs/';

/**
* установить значения фильтров
*/
function setFilterValues(gridname) 
{
    if(tugrid_f_data.length > 1) {
        var filters = $(gridname+'box').getElementsByClassName(' filter');
        var len = tugrid_f_data.length;
        for(var i = 0; i < len; i++) {
            filters[i].getElementsByTagName("INPUT")[0].value = tugrid_f_data[i];
        }
    }
}

/**
* переключить состояние фильтров
* включаем/выключаем поля фильтра
*/
function switchFilters(gridname,flag) 
{
    var filters = $(gridname+'box').getElementsByClassName(' filter');
    if(filters.length > 0) {
        eval('var len = '+gridname+'.getColumnCount()');
        for(var i = 0; i < len; i++) {
            filters[i].getElementsByTagName("INPUT")[0].disabled = !flag;
        }
    }
}

/**
* восстанавливаем значения фильтров из куков
*/
function getFilters() 
{
    eval("tugrid_f_ind  = ["+unescape(getCookie(obj+'_f_ind' ))+"]");
    eval("tugrid_f_data = ["+unescape(getCookie(obj+'_f_data'))+"]");
}

/**
 * Транслитерация кириллической строки
 * 
 * @param val
 * @return
 */
function translitStr(val)
{
    A = new Array();
    A["Ё"]="yo";A["Й"]="i";A["Ц"]="ts";A["У"]="U";A["К"]="k";A["Е"]="e";A["Н"]="n";A["Г"]="g";A["Ш"]="sh";A["Щ"]="sch";A["З"]="z";A["Х"]="h";A["Ъ"]="";
    A["ё"]="yo";A["й"]="i";A["ц"]="ts";A["у"]="u";A["к"]="k";A["е"]="e";A["н"]="n";A["г"]="g";A["ш"]="sh";A["щ"]="sch";A["з"]="z";A["х"]="h";A["ъ"]="";
    A["Ф"]="f";A["Ы"]="i";A["В"]="v";A["А"]="a";A["П"]="p";A["Р"]="r";A["О"]="o";A["Л"]="l";A["Д"]="d";A["Ж"]="zh";A["Э"]="e";
    A["ф"]="f";A["ы"]="y";A["в"]="v";A["а"]="a";A["п"]="p";A["р"]="r";A["о"]="o";A["л"]="l";A["д"]="d";A["ж"]="zh";A["э"]="e";
    A["Я"]="ya";A["Ч"]="ch";A["С"]="s";A["М"]="m";A["И"]="i";A["Т"]="t";A["Ь"]="'";A["Б"]="b";A["Ю"]="yu";
    A["я"]="ya";A["ч"]="ch";A["с"]="s";A["м"]="m";A["и"]="i";A["т"]="t";A["ь"]="j";A["б"]="b";A["ю"]="yu";A[" "]="-";
    A["Ї"]="yi";A["ї"]="yi";A["і"]="i";A["І"]="yi";
    
    new_val = String(val).replace(/([\u0020\u0410-\u0451])/g,
        function (str,p1,offset,s) {
            if (A[str] != 'undefined'){return A[str];}
        }
    );
    
    return new_val;
}

/**
 * Присвоить значение по шаблону в урле переменной
 * @param url
 * @param name
 * @param val
 * @return
 */
function bindSlashParam (url, name, val)
{           
    var re = eval('/{' + name + '}/');
    var ret = url.replace(re, val);
    return ret;
}

/**
 * Определение в переменной массив ли
 * @param obj
 * @return
 */
function isArray(obj)
{
    return (!!obj && obj instanceof Array);
}


/**
 * Получить номер порта из текущего урла
 * @return
 */
function getPort()
{
    var port = /:(\d+)\//.exec(window.location.href);
    if(typeof port == 'object' && typeof port[1] != 'undefined')
        return ':' + port[1];
    else
        return '';
}     

/**
 * Простой шаблонизатор строк
 * Требует jQuery
 * @param tpl шаблон, символ подстановки параметра %s
 * @param params парметр или массив параметров ( ... %s ... %s ... )
 */
function template(tpl, params) 
{
    var ret = '';
    
    if(isArray(params))
    {
        $.each(params,
            function (ind, param) {
                if(String(param).length > 0)
                    tpl = tpl.substring(0,tpl.indexOf('%s')) + param + tpl.substring(tpl.indexOf('%s')+2);
            }
        );
    } else
        tpl = tpl.replace('%s', params);
        
    return tpl;
}

/**
 * Получаем и форматируем текущую дату в Mysql формат
 * @return
 */
function getMysqlDate ()
{
    var curDate = new Date();
    var curMonth = curDate.getMonth() + 1;
    curMonth = (String(curMonth).length < 2) ? '0' + curMonth : curMonth;
    var curDay = curDate.getDate();
    curDay = (String(curDay).length < 2) ? '0' + curDay : curDay;
    
    return curDate.getFullYear() + '-' + curMonth + '-' + curDay;
}

function getMysqlDateTime (taim)
{
    var taim2 = (typeof taim == 'undefined') ? '02:00:00' : taim;
    
    return getMysqlDate() + ' ' + taim2;
}

/**
 * Получение теущего времени
 * @return
 */
function getCurTime()
{
    var currentDate = new Date();
    
    var hours = currentDate.getHours();
    var mins = currentDate.getMinutes();
    var secs = currentDate.getSeconds();

    return ((hours < 10)?'0'+hours:hours) + ':' + ((mins < 10)?'0'+mins:mins) + ':' + ((secs < 10)?'0'+secs:secs);    
}
