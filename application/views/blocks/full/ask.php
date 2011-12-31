<div id="ask_subscript">
    <div class="contentWrap" id="ask_step1">
        <div class="popup">
            <form id="askform" action="" class="askform">
                <span class="popup-close"></span>
                <span class="contentwrap-tit">Привет! Расскажи немного о себе :-)</span>
                <div class="line">
                        <div class="gender">
                            <span class="gender-tit">Ты:</span>
                            <div class="gender-check-container">
                                <div class="gender-item">
                                    <div class="gender-item__i">
                                        <span class="askradio active"><input type="radio" name="gender" id="gender-female" value="0" checked="checked"/></span>
                                        <label for="gender-female">Женщина</label>
                                    </div>
                                </div>
                                <div class="gender-item">
                                    <div class="gender-item__i">
                                        <span class="askradio"><input type="radio" name="gender" id="gender-male" value="1"/></span>
                                        <label for="gender-male">Мужчина</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="contentwrap-tit">Какие темы тебе интересны?</span>
                    <div class="line">
                    <table width="100%" class="topics">
                        <tr>
                            <td width="33%">
                                <span class="ch"><input type="checkbox" class="askchk" id="beauty" value="301"/></span>
                                <label for="beauty">Красота</label>
                            </td>
                            <td width="33%">
                                <span class="ch"><input type="checkbox" class="askchk" id="shoping" value="307"/></span>
                                <label for="shoping">Шоппинг</label>
                            </td>
                            <td width="33%">
                                <span class="ch"><input type="checkbox" class="askchk" id="home" value="491"/></span>
                                <label for="home">Дом</label>
                            </td>
                        </tr>
                        <tr>
                            <td width="33%">
                                <span class="ch"><input type="checkbox" class="askchk" id="stars" value="302"/></span>
                                <label for="stars">Звезды</label>
                            </td>
                            <td width="33%">
                                <span class="ch"><input type="checkbox" class="askchk" id="body" value="304"/></span>
                                <label for="body">Здоровье и фитнес</label>
                            </td>
                            <td width="33%">
                                <span class="ch"><input type="checkbox" class="askchk" id="chidren" value="308"/></span>
                                <label for="chidren">Дети</label>
                            </td>
                        </tr>
                        <tr>
                            <td width="33%">
                                <span class="ch"><input type="checkbox" class="askchk" id="style" value="303"/></span>
                                <label for="style">Стиль и мода</label>
                            </td>
                            <td width="33%">
                                <span class="ch"><input type="checkbox" class="askchk" id="sex" value="305"/></span>
                                <label for="sex">Секс и отношения</label>
                            </td>
                            <td width="33%">
                                <span class="ch"><input type="checkbox" class="askchk" id="lifestyle" value="494"/></span>
                                <label for="lifestyle">Стиль жизни</label>
                            </td>
                        </tr>
                    </table>
                    <div class="popup-submit-container">
                        <input id="submit_ask_step1" class="popup-submit" type="button" value="Ответить">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="contentWrap" id="ask_step2">
        <div class="popup">
            <span class="popup-close"></span>
            <span class="contentwrap-tit">
                Спасибо за ответы. Предлагаем подписаться на почтовую рассылку<br />
                с анонсами наиболее интересных материалов <span id="asksections">нашего сайта</span>
            </span>
            <div class="subscribe">
                <form action="" class="askforms" id="askformemail">
                    <label for="subscribe">E-mail:</label>
                    <input type="text" name="subscribe" id="subscribe_email">
                    <div class="popup-submit-container">
                        <input id="submit_ask_step2" class="popup-submit" type="button" value="Подписаться">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="contentWrap" id="ask_step3">
        <div class="popup">
            <div class="completion">
                <span class="popup-close"></span>
                <span class="contentwrap-tit">
                    Ты успешно <span id="ask_gender">подписалась</span> на почтовую рассылку.
                </span>
                <form action="" class="askforms">
                    <div class="popup-submit-container">
                        <input id="submit_ask_step3" class="popup-submit" type="button" value="Продолжить пользоваться сайтом">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
