<?php

$this->extend('layout');

$this->block('content');
?>
<link type="text/css" rel="stylesheet" href="/css/bt_lady_old.css" />
<script>
                        function open_form(id){
                                if (document.getElementById(id).style.display == 'none'){
                                        document.getElementById(id).style.display = 'block';
                                } else {
                                        document.getElementById(id).style.display = 'none';
                                }
                        }
                        </script>
<div class="content-wrapper">
<?php echo $this->data; ?>
</div></div></div></div>
</div></div>
</div>
<?php $this->endblock('content') ?>
