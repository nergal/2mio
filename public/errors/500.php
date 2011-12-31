<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Page Not Found :(</title> 
  <style>
	  body { text-align: center;}
	  h1 { font-size: 50px; text-align: center }
	  span[frown] { transform: rotate(90deg); display:inline-block; color: #bbb; }
	  body { font: 20px Constantia, 'Hoefler Text',  "Adobe Caslon Pro", Baskerville, Georgia, Times, serif; color: #999; text-shadow: 2px 2px 2px rgba(200, 200, 200, 0.5); }
	  ::-moz-selection{ background:#FF5E99; color:#fff; }
	  ::selection { background:#FF5E99; color:#fff; } 
	  article {display:block; text-align: left; width: 900px; margin: 0 auto; }
	  img {display:block;margin:0 auto;}
	  
	  a { color: rgb(36, 109, 56); text-decoration:none; }
	  a:hover { color: rgb(96, 73, 141) ; text-shadow: 2px 2px 2px rgba(36, 109, 56, 0.5); }
	  #goog-fixurl {margin:0 auto;}
  </style>
</head>
<body>
     <article>
	  <h1>Всё ВНЕЗАПНО <span frown>поломалось</span>!</h1>
	   <div>
		<?php
		    $files = array(
			'http://www.avariyca.ru/wp-content/uploads/2010/05/%D0%97%D0%BD%D0%B0%D0%BA-%D0%B2%D0%B5%D0%B4%D1%83%D1%82%D1%81%D1%8F-%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D1%8B.jpg',
		    );
		    
		    $file = $files[rand(1, count($files)) - 1];
		?>
		<img src="<?php echo $file ?>" />
	   </div>
     </article>
</body>
</html>