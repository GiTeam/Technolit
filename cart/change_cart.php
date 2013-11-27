<?
session_start();
include("db.php");

function json_encode_cyr($str) {
   $arr_replace_utf = array('\u0410', '\u0430','\u0411','\u0431','\u0412','\u0432',
   '\u0413','\u0433','\u0414','\u0434','\u0415','\u0435','\u0401','\u0451','\u0416',
   '\u0436','\u0417','\u0437','\u0418','\u0438','\u0419','\u0439','\u041a','\u043a',
   '\u041b','\u043b','\u041c','\u043c','\u041d','\u043d','\u041e','\u043e','\u041f',
   '\u043f','\u0420','\u0440','\u0421','\u0441','\u0422','\u0442','\u0423','\u0443',
   '\u0424','\u0444','\u0425','\u0445','\u0426','\u0446','\u0427','\u0447','\u0428',
   '\u0448','\u0429','\u0449','\u042a','\u044a','\u042b','\u044b','\u042c','\u044c',
   '\u042d','\u044d','\u042e','\u044e','\u042f','\u044f');
   $arr_replace_cyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е',
   'Ё', 'ё', 'Ж','ж','З','з','И','и','Й','й','К','к','Л','л','М','м','Н','н','О','о',
   'П','п','Р','р','С','с','Т','т','У','у','Ф','ф','Х','х','Ц','ц','Ч','ч','Ш','ш',
   'Щ','щ','Ъ','ъ','Ы','ы','Ь','ь','Э','э','Ю','ю','Я','я');
   $str1 = json_encode($str);
   $str2 = str_replace($arr_replace_utf,$arr_replace_cyr,$str1);
   return $str2;
}

function add_to_cart($id)
{
   $result = select($id);
   $temp_array = mysql_fetch_assoc($result);
	if (($array = mysql_num_rows($result)) > 0)                                 // проверяем, есть ли такой товар в базе
   {  
      $_SESSION['counter'] = (!isset($_SESSION['counter'])) ? 0 : $_SESSION['counter']; 
      $_SESSION['price'] = (!isset($_SESSION['price'])) ? 0 : $_SESSION['price']; 
      
      if(!isset($_SESSION['item']['id'.$id]))                                     // был ли добавлен хотя бы один товар с таким id
      {
         $_SESSION['counter']++;
         $_SESSION['item']['id'.$id]['price'] = (int)$temp_array['price'];
         $_SESSION['item']['id'.$id]['name'] = $temp_array['name'];
         $_SESSION['item']['id'.$id]['counter'] = 1;
         $_SESSION['price'] += $_SESSION['item']['id'.$id]['price'];
         
      }
      else
      {
         $_SESSION['item']['id'.$id]['counter'] += 1;
         $_SESSION['counter'] += 1;
         $_SESSION['price'] += $_SESSION['item']['id'.$id]['price'];
      }
      
   }

   $json_data = array('counter' => $_SESSION['counter'], 'price' => $_SESSION['price'], 'name' => $_SESSION['item']['id'.$id]['name']);
   json_return ($json_data);
}

function delete_to_cart($id)
{
   if(isset($_SESSION['item']['id'.$id]) && ($_SESSION['item']['id'.$id]['counter'] > 0)) 
      {
         $_SESSION['item']['id'.$id]['counter']--;
         $_SESSION['counter']--;
         $_SESSION['price'] -= $_SESSION['item']['id'.$id]['price'];
      }
   else if (isset($_SESSION['item']['id'.$id]) && ($_SESSION['item']['id'.$id]['counter'] == 0)) 
      unset($_SESSION['item']['id'.$id]);

   $json_data = array('counter' => $_SESSION['counter'], 'price' => $_SESSION['price']);
   json_return ($json_data);
}

function get_current_state()
{
   $_SESSION['counter'] = (!isset($_SESSION['counter'])) ? 0 : $_SESSION['counter']; 
   $_SESSION['price'] = (!isset($_SESSION['price'])) ? 0 : $_SESSION['price']; 
   
   $json_data = array('counter' => $_SESSION['counter'], 'price' => $_SESSION['price']);
}

function get_full_info()
{
   $_SESSION['counter'] = (!isset($_SESSION['counter'])) ? 0 : $_SESSION['counter']; 
   $_SESSION['price'] = (!isset($_SESSION['price'])) ? 0 : $_SESSION['price']; 
   
   $json_data = array('counter' => $_SESSION['counter'], 'price' => $_SESSION['price'], 'item' => $_SESSION['item']);
   json_return ($json_data);
}

function select($id)
{
   $query = "SELECT id, name, price  FROM product WHERE id='$id'";
   $result = mysql_query($query);
   if(!$result) 
   {
      echo "<p>База данных не доступна<br> <strong>Ошибка: </strong></p>";
      exit(mysql_error());
   }
   else return $result;
}

function json_return ($json_data)
{
   print_r($json_data);
   echo json_encode_cyr($json_data);
}

if(!empty($_POST['id']) && !empty($_POST['action'])) 
{
   $id = $_POST['id'];
   $action = $_POST['action'];
   switch($action)
   {
      case 'add': 
         add_to_cart($id);
         break;
      case 'del':
         delete_to_cart($id);
         break;
      case 'info':
         get_current_state();
         break;
      case 'fullinfo':
         get_full_info();
         break;
      default: break;   
   }
}
?>