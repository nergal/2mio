<?php if ($this->pages): ?>
<!--Блок Читать также-->
<div id="read_it">
    <div class="middle_line"><h3>читай также</h3></div>
    
    <div class="clear"></div>
    <ul>
        <?php foreach ($this->pages as $page): ?>
        <?php
            $route = array(
                'name' => $page['alias'].'-view',
                'params' => array(
                    'category' => $page['name_url'],
                    'id' => $page['id'],
                    'title' => $this->transliterate($page['title']),
                ),
            );
            $url = URL::site(Route::get($route['name'])->uri($route['params']));
        ?>
        <li><a class="none" href="<?php echo $url ?>"><?php echo Helper::filter($page['title']) ?></a></li>
        <?php endforeach ?>
    </ul>
</div>
<!--Блок Читать также-->
<br />
<?php endif ?>