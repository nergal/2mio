<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
    <head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Service Temporarily Unavailable</title>
	<style type="text/css">
	    .wrapper {position: absolute;top: 20%;left: 50%;}
	    .center {width: 600px;height: 410px;background: #fff url(/i/under_construction.jpg) no-repeat bottom center;position: relative;left: -50%;}
	    .center p {text-align: center;font: 1.75em 'Lucida Grande',Verdana,sans-serif;color: #999;text-shadow: 1px 1px 1px #000;}
	</style>
    </head>
    <body>
	    <?php
		if (Kohana::$errors === TRUE) {
		    require_once SYSPATH."views/kohana/error.php";
		} else {
	    ?>
	    <div class="wrapper">
		<div class="center"><p>Ведутся технические работы</p></div>	    
	    </div>
	    <?php } ?>
    </body>
</html>
