<!--Блок Подпишись на новости-->
<script>

$(function() {
    $("#e-mail_link").click(function () {
        $("#e-mail_div").toggle();
        return false;
    });

    $('#sub-submit').click(function() {
        $(this).parent('form').submit();
    });
});
</script>

<div class="get_mess">
	<h3>подпишись на топ новости</h3>
	<a href="/page-rss/" id="rss_link" class="right"><img src="/i/rss_link.png" alt="через RSS" /></a>
	<a href="#" id="e-mail_link" class="right"><img src="/i/mail_link.png" alt="по e-mail" /></a>

        <div id="e-mail_div" style="display:none;" class="form2">
            <form action="" method="post">
                    Будьте добры, выберите те разделы, новости из которых хотите получать по электронной почте:
                    <table width="100%" class="paddtable">
                            <tbody>
                                    <tr>
                                        <td width="60%">
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="301" id="ch1"></span>
                                            <label for="ch1">Красота </label>
                                        </td>
                                        <td width="40%">
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="307" id="ch2"></span>
                                            <label for="ch2">Шоппинг</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="302" id="ch3"></span>
                                            <label for="ch3">Звезды</label>
                                        </td>
                                        <td>
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="371" id="ch4"></span>
                                            <label for="ch4">Распродажи</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="303" id="ch5"></span>
                                            <label for="ch5">Стиль и мода</label>
                                        </td>
                                        <td width="50%">
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="491" id="ch6"></span>
                                            <label for="ch6">Дом</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="304" id="ch9"></span>
                                            <label for="ch9">Здоровье и фитнес</label>
                                        </td>
                                        <td width="50%">
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="308" id="ch10"></span>
                                            <label for="ch10">Дети</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="305" id="ch11"></span>
                                            <label for="ch11">Секс и отношения</label>
                                        </td>
                                        <td width="50%">
                                            <span class="ch checkch"><input type="checkbox" checked="checked" name="sections[]" value="494" id="ch12"></span>
                                            <label for="ch12">Стиль жизни</label>
                                        </td>
                                    </tr>
                            </tbody>
                    </table>
                    <div class="form">
                            <label for="user_e-mail">E-mail:<span>Для рассылки</span></label>
                            <input type="text" name="email" value="" id="user_e-mail" />
                    </div>
                    <div class="form">
                            <label for="user_name">Имя:<span>Для общения</span></label>
                            <input type="text" name="username" value="<?php ( ! isset($this->user)) ?: print($this->user->get_user_title()) ?>" id="user_name" />
                    </div>
                    <a class="right" href="#submit" id="sub-submit"><img src="/i/add_subscr.png" alt="Подписаться на рассылку"></a>
                    <div class="clear"></div>
            </form>
	</div>

	<div class="clear"></div>
</div>

<!--Конец Блок Подпишись на новости-->
<br />
