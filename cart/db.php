<? 
   $host='localhost';
	$user='cp135438_cp13548';
	$pass='W&by&k)3@Gbz';
	$name='cp135438_productDB';
	
   /* Устанвливаем соединение с сервером*/
	$link = mysql_connect($host,$user,$pass);
	if (!$link) (mysql_error() . "\n\n");
	mysql_query("SET NAMES utf8", $link);
	/*Выбираем базу данных*/
	mysql_select_db($name)
	or die("Нет соединения с БД" .mysql_error());
	mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'"); 
    mysql_query("SET CHARACTER SET 'utf8'");
?>