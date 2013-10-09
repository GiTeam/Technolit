<? 
$serverName = $_SERVER['SERVER_NAME'];
echo "
<!doctype html>

<head>
	<meta charset='utf-8'/>
	<meta name='viewport' content='width=device-width, initial-scale=1'/>
	
	<link href='http://".$serverName."/style/StyleSheet.css' type='text/css'  rel='Stylesheet' />
	<link href='http://".$serverName."/style/Fform.css' type='text/css'  rel='Stylesheet' />
	<link href='http://".$serverName."/style/menuvert.css' type='text/css'  rel='Stylesheet' />
	<link rel='stylesheet' type='text/css' href='http://".$serverName."/style/goods.css' />
	
	<link href='http://fonts.googleapis.com/css?family=Lobster&subset=latin,cyrillic-ext,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
	
	<link rel='shortcut icon' type='image/x-icon' href='http://".$_SERVER['SERVER_NAME']."/favicon.ico'>
	
	<!--[if lt IE 9]>
	<script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script>
	<![endif]-->
	
	<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js'></script>
	<script type='text/javascript' src='http://".$serverName."/file/form.js'></script>
	
	<title>Технолит</title>
	
</head>

<body>

 <div class='main'>

    <header class='header'> 

        <div class='logotype'> 

        <a href='index.php'>
			<img width='200px'  alt='TM'  src='http://".$serverName."/SIMG/ServLogo/min30.png'/>
		</a>

        </div>

        <div class='logoText'>

			<a href='index.php'>
				<img width='240px'  alt='Технолит Mаркет'  src='http://".$serverName."/SIMG/ServLogo/Texnolit.png'/></div>
            </a>

        </div>

    </div>  ";
	include('menu.php');

    echo "<div>
            <div style='height:1px;overflow:hidden;clear:both;'></div>
		</div>";
	switch($_SERVER['PHP_SELF']) 
	{
	case '/index.php': case'/contacts.php': case '/price-list.php':
		include('menu_ver.php');
		include('./db/db.php');
		break;
	case '/search.php':
		include('list_cat.php');
		break;
	default : 
		include('list_cat.php'); 
		break;
	}
    
?>