<?php $this->extend('layout') ?>

<?php $this->block('meta') ?>
    <?php echo Meta::render_meta(); ?>

    <meta property="og:title" content="<?php echo htmlentities($this->article->title, ENT_COMPAT, 'UTF-8') ?>"/>
    <meta property="og:type" content="article" />
    <meta property="og:image" content="<?php if(! empty($this->media)): ?>
		<?php echo rtrim(URL::base(TRUE), '/').$this->photo($this->media, '620x400', 'cropg') ?>
	<?php elseif($this->article->photo): ?>
		<?php echo rtrim(URL::base(TRUE), '/').$this->photo($this->article) ?>
	<?php else:?>
		<?php echo URL::base(TRUE) ?>i/logo.png
	<?php endif; ?>" />
    <?php if(! empty($this->media)): ?>
		<link rel="image_src" href="<?php echo rtrim(URL::base(TRUE), '/').$this->photo($this->media, '620x400', 'cropg') ?>" />
    <?php elseif($this->article->photo): ?>
		<link rel="image_src" href="<?php echo rtrim(URL::base(TRUE), '/').$this->photo($this->article) ?>" />
    <?php endif; ?>
    <meta property="og:url" content="<?php echo rtrim(URL::base(TRUE), '/').$this->uri($this->article) ?>" />
    <meta property="fb:app_id" content="<?php echo Kohana::config('oauth.facebook.key') ?>" />
    <meta property="og:description" content="<?php echo Helper::filter($this->article->description) ?>"/>
    <meta property="fb:app_id" content="<?php echo Kohana::config('oauth.facebook.key') ?>"/>
<?php $this->endblock('meta') ?>

<?php $this->block('content') ?>

<!--основной текст-->
				<?php if (empty($this->no_right)):  ?>
				<div class="maintext">
					<div class="breadcrumbs-container">
				<?php else: ?>
				<div class="maintext wide">
					<div class="breadcrumbs-container-wide">
				<?php endif; ?>
					<div class="breadcrumbs">
						<?php
							$section = $this->article->section;
							$breadcrumbs = array('<span typeof="v:Breadcrumb">'.$this->article->title.'</span>');
							$breadcrumbs[] = $this->link_bread($section);
							
							while ($section->parent_id !== NULL) {
								$section = $section->parent;
								$breadcrumbs[] = $this->link_bread($section);
							}

							$breadcrumbs[] = '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.URL::base(TRUE).'">ХОЧУ!ua</a></span>';
							$breadcrumbs = array_reverse($breadcrumbs);
							
							echo '<div xmlns:v="http://rdf.data-vocabulary.org/#">';
							echo implode(' / ', $breadcrumbs);
							echo '</div>';
							
							if ($this->article->section->parent->loaded()) {
							    $section = $this->article->section;
							    $section = $section->parent;
							}
						?>
					</div>
					</div>
					<div class="bread-fade"></div>

					<h1 class="top bold"><?php echo Helper::filter($this->article->title); ?></h1>
					<div class="data">
					
						<?php if (Auth::instance()->logged_in() AND isset($this->favorite)): ?>
							<div class="viewcom viewcom_top" style="width:150px;height:20px;">
							<?php if ( ! $this->favorite->loaded()): ?>
								<a class="right notepad tonotepad" name="<?php echo $this->article->id; ?>"><span> + сохранить в блокнот</span></a>
							<?php else: ?>
								<a class="right notepad fromnotepad" name="<?php echo $this->article->id; ?>"><span> - из блокнота</span></a>
							<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php echo $this->get_blocks(array('like' => array('short' => True, 'title' => $this->article->title, 'url' => $this->uri($this->article), 'order' => 1)), 'inner'); ?>
						
					
						<div class="viewcom viewcom_top" style="padding-right:12px;">
									<?php if (isset($this->article->views_count) AND isset($this->article->comments_count)): ?>
										<?php if ($this->article->views_count > 0): ?><a class="view"><?php echo Helper::space_digit($this->article->views_count); ?></a>&nbsp;<?php endif; ?>
										<?php if ($this->article->comments_count > 0): ?><a class="comment"><?php echo Helper::space_digit($this->article->comments_count); ?></a><?php endif; ?>
									<?php endif ?>
						</div>
						<span class="date"><?php echo Date::formatted_time($this->article->date, 'd.m.Y H:i') ?></span>
						
						
						<div class="clear"></div>	
					</div>

					<?php if (isset($this->social_comments) AND $this->social_comments === TRUE): ?>
						<div class="article-content wide rel">
					<?php else: ?>
						<div class="article-content rel">
					<?php endif; ?>

                                        <?php if ( ! empty($this->media)): ?>
                                            <div id="scroll-table">
                                                <?php    // Брендирование фотографий под Samsung  
                                                $img=getimagesize(trim($this->media->name,'/'));
                                                if ($img[0] > $img[1]) {?>
                                                <style>
                                                    .camera-gallery
                                                    {
                                                        position: relative;
                                                        display: block;
                                                        z-index: 1;
                                                        width: 454px;
                                                        height: 424px;
                                                        margin: auto;
                                                        background: url("/i/gallery__bg.png") no-repeat;
                                                    }
                                                    .camera-gallery__i
                                                    {
                                                        position: absolute;
                                                        top: 163px;
                                                        left: 25px;
                                                        display: block;
                                                        z-index: 2;
                                                        width: 353px;
                                                        height: 203px;
                                                        background: #f3f3f3;
                                                    }
                                                    .camera-gallery__i img
                                                    {
                                                        max-width: 100%;
                                                        max-height: 100%;
                                                        display: block;
                                                        margin: auto;
                                                    }
                                                    #scroll-table #image_wrap #addcontrols
                                                    {
                                                        top: 100% !important;
                                                        margin-top: 30px;
                                                        height: 0!important;
                                                    }
                                                    #gallery_lbox
                                                    {
                                                        margin-top: 60px !important;
                                                    }
                                                </style>
                                                <a class="camera-gallery" href="http://ad.adriver.ru/cgi-bin/click.cgi?sid=1&ad=316921&bt=21&pid=741179&bid=1494433&bn=1494433&rnd=1442990340" target="_blank"></a>
                                                    <?php 
                                                    if($photo_pager->current_page > 1) {
                                                       $urlprev = ' href="'.$this->uri($this->article).'photo-'.$photo_pager->previous_page.'/"';
                                                    } else
                                                       $urlprev = '';

                                                    if($photo_pager->current_page != $photo_pager->total_items) {
                                                       $urlnext = ' href="'.$this->uri($this->article).'photo-'.$photo_pager->next_page.'/"';
                                                    } else
                                                       $urlnext = '';
                                                    ?>
                                                <a class="camera-gallery__i"<?php echo $urlnext; ?>>
                                                    <img src="<?php echo $this->photo($this->media, '620x400', 'cropg'); ?>" alt="<?php echo Helper::filter($this->media->description) ?>">
                                                </a>
                                                <div id="image_wrap">
                                                    <div id="addcontrols">
                                                    <?php if(!empty($urlprev)) {?>
                                                        <a class="backward prev"<?php echo $urlprev; ?>>prev</a>
                                                    <?php } ?>
                                                    <?php if(!empty($urlnext)) {?>
                                                        <a class="forward next"<?php echo $urlnext; ?>>next</a>
                                                    <?php } ?>
                                                    </div>
                                                </div>
                                                <img src="http://ad.adriver.ru/cgi-bin/rle.cgi?sid=1&ad=316921&bt=21&pid=741179&bid=1494433&bn=1494433&rnd=1442990340" border=0 width=1 height=1 />
                                            <?php } else { ?>
                                                <style>
                                                    #scroll-table #image_wrap #addcontrols
                                                    {
                                                        top: 100% !important;
                                                        margin-top: 30px;
                                                        height: 0!important;
                                                    }
                                                    #gallery_lbox
                                                    {
                                                        margin-top: 60px !important;
                                                    }
                                                </style>
                                                <div id="image_wrap" style="min-height:200px;">
                                                    <?php 
                                                    if($photo_pager->current_page > 1) {
                                                       $urlprev = ' href="'.$this->uri($this->article).'photo-'.$photo_pager->previous_page.'/"';
                                                    } else
                                                       $urlprev = '';

                                                    if($photo_pager->current_page != $photo_pager->total_items) {
                                                       $urlnext = ' href="'.$this->uri($this->article).'photo-'.$photo_pager->next_page.'/"';
                                                    } else
                                                       $urlnext = '';
                                                    ?>
                                                    <a<?php echo $urlnext; ?>><img src="<?php echo $this->photo($this->media, '620x400', 'cropg'); ?>" alt="<?php echo Helper::filter($this->media->description) ?>" /></a>
                                                    <div id="addcontrols">
                                                    <?php if(!empty($urlprev)) {?>
                                                        <a class="backward prev"<?php echo $urlprev; ?>>prev</a>
                                                    <?php } ?>
                                                    <?php if(!empty($urlnext)) {?>
                                                        <a class="forward next"<?php echo $urlnext; ?>>next</a>
                                                    <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                                <div id="pager">
                                                <?php echo $photo_pager; ?>

                                                <?php
                                                        if(file_exists(".".$this->photo($this->media, NULL, 'cropg'))) {
                                                                $originalPhotoSize = getimagesize(".".$this->photo($this->media, NULL, 'cropg'));
                                                                if($originalPhotoSize[0] > 460) {
                                                ?>
                                                <span id="gallery_lbox"><a href="<?php echo $this->photo($this->media, NULL, 'cropg'); ?>"><img src="/i/zoom_in.png" alt="Увеличить" align="middle" /></a></span>
                                                <script type="text/javascript"> 
                                                $(function() { 
                                                        $('#gallery_lbox a').lightBox({
                                                                imageLoading:			'/i/lightbox/lightbox-ico-loading.gif',		
                                                                imageBtnPrev:			'/i/lightbox/lightbox-btn-prev.gif',			
                                                                imageBtnNext:			'/i/lightbox/lightbox-btn-next.gif',			
                                                                imageBtnClose:			'/i/lightbox/lightbox-btn-close.gif',		
                                                                imageBlank:				'/i/lightbox/lightbox-blank.gif'
                                                        });
                                                        }); 
                                                </script>
                                                <?php 
                                                                } 
                                                        } 
                                                ?>

                                                </div>

                                                <p class="description"><?php echo Helper::filter($this->media->description) ?></p>

                                                <div class="liker">
								
<?php 
	$soc_title = 'Мне понравилась фотография на портале "Хочу"';
	$soc_summary = $this->article->title; // 'Мне понравилась фотография на '.URL::Base(TRUE); //Helper::filter($this->media->description);
	$soc_url = rtrim(URL::Base(TRUE), '/').$this->uri($article).'photo-'.$photo_pager->current_page.'/';
	$soc_image = rtrim(URL::base(TRUE), '/').$this->photo($this->media, '620x400', 'cropg');
	
	$facebook_sharer = 'http://www.facebook.com/sharer.php?s=100&p[title]='.rawurlencode($soc_title)
	.'&p[summary]='.rawurlencode($soc_summary)
	.'&p[url]='.rawurlencode($soc_url)
	.'&p[images][0]='.rawurlencode($soc_image);
	
	$vk_sharer = 'http://vkontakte.ru/share.php?title='.rawurlencode($soc_title)
	.'&description='.rawurlencode($soc_summary)
	.'&url='.rawurlencode($soc_url);

	$klass_sharer = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl='.rawurlencode($soc_url);
?>											
										<div style="margin-bottom:6px;">Понравилась фотография? Жми!</div>
			<a rel="nofollow" target="_blank" href="<?php echo $facebook_sharer; ?>"><img src="/i/facebooksmall.png"></a>
			<a rel="nofollow" target="_blank" href="<?php echo $vk_sharer; ?>"><img src="/i/vkontaktesmall.png"></a>
			<a rel="nofollow" target="_blank" href="<?php echo $klass_sharer; ?>"><img src="/i/odnoklsmall.png"></a>
										</div>
                                            </div>
						<?php else: ?>
							<?php if ($this->article->photo): ?>
							<div class="picture">
								<img src="<?php echo $this->photo($this->article).'?'.md5(date('YmdH')) ?>" alt="<?php echo Helper::filter($this->article->title); ?>" />
								<link rel="image_src" href="<?php echo $this->photo($this->article) ?>" />
							</div>
							<?php endif ?>
						<?php endif ?>

						<?php echo $this->article->body ?>
					</div>
					
					<p><?php echo $this->banner('article-informer'); ?></p>

                                        <div class="rel">
                                            <?php if ((isset($this->article->author) AND ! empty($this->article->author)) OR $this->article->user_id): ?>
                                            <p class="small">Автор: <?php echo $this->article->get_user_link() ?></p>
                                            <?php endif ?>

                                            <?php if ((isset($this->article->source) AND ! empty($this->article->source))): ?>
                                            <p class="small">Источник: <?php echo $this->article->source ?></p>
                                            <?php endif ?>

                                            <?php if ((isset($this->article->copy_photo) AND ! empty($this->article->copy_photo))): ?>
                                            <p class="small">Фото: <?php echo $this->article->copy_photo ?></p>
                                            <?php endif ?>

                                            <?php if ((isset($this->article->copy_video) AND ! empty($this->article->copy_video))): ?>
                                            <p class="small">Видео: <?php echo $this->article->copy_video ?></p>
                                            <?php endif ?>

                                            <?php $tags = $this->render_tags($this->article); ?>
                                            <?php if ( ! empty($tags)): ?>
                                                    <p class="small">Теги: <?php echo $tags; ?></p>
                                            <?php endif ?>
                                                    
                                            <?php if (Auth::instance()->logged_in() AND isset($this->favorite)): ?>
							<div class="viewcom viewcom_bottom small" style="margin-left: 40px">
							<?php if ( ! $this->favorite->loaded()): ?>
								<a class="notepad tonotepad" name="<?php echo $this->article->id; ?>"><span> + сохранить в блокнот</span></a>
							<?php else: ?>
								<a class="notepad fromnotepad" name="<?php echo $this->article->id; ?>"><span> - из блокнота</span></a>
							<?php endif; ?>
							</div>
                                            <?php endif; ?>
                                        </div>

					<div class="data rel">
                                                <div class="middle_line"></div><br />
						<?php echo $this->get_blocks(array('like' => array('title' => $this->article->title, 'url' => $this->uri($this->article), 'order' => 2)), 'inner'); ?>
					</div>
                                        <br />

                                        <?php echo $this->get_blocks(array('similar' => array('page' => $this->article), 'subscribe'), 'inner'); ?>

					<?php if (isset($this->social_comments) AND $this->social_comments === TRUE): ?>
						<?php echo $this->get_blocks(array('socialcomments' => array('page' => $this->article)), 'inner'); ?>
					<?php else: ?>
						<?php echo $this->get_blocks('social', 'double'); ?>
                                                <br />
						<?php echo $comments; ?>
					<?php endif; ?>
					
                                        <?php if(isset($this->zpixel) && $this->zpixel != ''): ?>
                                        <?php echo $this->zpixel; ?>
                                        <?php endif; ?>

				</div>
<!--Конец основного текста-->

<?php $this->block('sidebar') ?>
<!--Маленькая левая колонка-->
				<div class="minileft">
<!--sidemenu-->
				<h2 class="top"> &nbsp; <?php echo Helper::filter($section->name, Helper::TITLE) ?></h2>
				<div class="sidemenu">
					<ul>
						<?php
						    $items = $section->section->where('sections.showhide', '=', 1)->order_by('order')->find_all();
						    foreach ($items as $item): ?>
							<?php $class = ($item->id == $this->article->section->id) ? array('class' => 'active') : NULL; ?>
							<li><?php echo $this->link($item, NULL, $class) ?></li>
						<?php endforeach ?>
					</ul>
					<div class="bott_sm"></div>
				</div>
<!--end sidemenu-->

<!--Здоровье, фитнес, отдых, шоппинг-->
				<div class="rel">
					<?php // echo $this->get_blocks(array('section' => array('id' => '106'), 'section' => array('id' => '106')), 'left') ?>
				</div>
<!--Конец Здоровье, фитнес, отдых, шоппинг-->
				<?php echo $this->get_blocks('news', 'left'); ?>
				<?php echo $this->get_blocks(array('cosmetic' => array('type' => 'small')), 'left'); ?>
<?php $this->endblock('sidebar') ?>
				</div>
<?php $this->endblock('content') ?>
