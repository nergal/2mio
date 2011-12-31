					<div class="abc">
					    <ul>
							<?php foreach(Kohana::config('abc.ru') as $key => $litera): ?>
								<?php if ($key == $this->litera_num): ?>
									<li><span class="active"><?php echo $litera; ?></span></li>
								<?php elseif($this->active_literas[$key] == 0): ?>
									<li><span class="empty"><?php echo $litera; ?></span></li>
								<?php else: ?>
									<li><a href="<?php echo Url::site(Route::get('wiki')->uri(array('category' => $this->category->name_url, 'order' => 'abc', 'litera' => $key))); ?>"><?php echo $litera; ?></a></li>
								<?php endif; ?>
								<?php if($key == 14): ?>
									</ul>
									<ul>
								<?php endif; ?>
						
							<?php endforeach; ?>							
					    </ul>
					</div>
