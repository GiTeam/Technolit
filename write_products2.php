<?
include('db/db.php');
//////////////////////////////////////начало класса
class WriteProducts
{
    public $total = 0;//кол-во страниц, для какой-нибудь подкатегории
	public $counter = 0;// кол-во товара
	public $page = 1;// номер текущей страницы
	public $catid = 1;// номер категории
	public $numberSub = 0;//номер подкатегории
	public $cat = '';//название категории 
	public $subcat = '';//название подкатегории
	public $start = 0; //начальное число товаров
	public $num = 10;//сколько товаров будет выводиться на страницу
	public $domain = '';//домен
	public $namepage = 'productorder.php';
	public $path_picture_product = 'ItemPicture/';// оттуда берем картинки	для товаров
	public $path_picture_category = 'Categories/';// оттуда берем картинки для категорий
//////посылаем запрос - получаем результат запроса
	public function select($query)
	{
		$result = mysql_query($query);
		if(!$result) 
		{
			echo "<p>База данных не доступна<br> <strong>Ошибка: </strong></p>";
			exit(mysql_error());
		}
		else return $result;
	}
/////// изменение размеров картинки
public function changeImageSize($path, $id_picture)
		{
			$templink = $path.$id_picture.".jpg";
			if (file_exists($templink)) 
				{
						$size = getimagesize ($templink);
						if (($size[0] > 150) && ($size[0]>= $size[1])) $width = "150";
						else $width = "";
						if (($size[1] > 150) && ($size[1]> $size[0])) $height = "150";
						else $height = "";
				}
			else
				{
					$width = "";
					$height = "";
					$templink = $this->path_picture_product."list.png";
				}
			if ($width != "") $width = "width='".$width."'";
			if ($height != "") $height = "height='".$height."'";
			$picture = $templink;
			return array($width, $height,$picture);
		}
/////// вывод пользователю информации
public function outputInformation ($temp_array, $category)
{
	if($category == 'product') 
				{
					list($width, $height, $picture) = $this->changeImageSize($this->path_picture_product,$temp_array['id']);				
					echo $page = "<div class='item' id='".$temp_array['id']."'>
						<div class='name'>".$temp_array['name']."</div>
						<div class='itemPicture'>
							<img src='".$this->domain.$picture."' ".$width." ".$height." align='middle'>
						</div>
						<div class='submit-c'>
							<input class='submit-butt' type='submit' value='Заказать' />
						</div>

						<div class='citemtxt'>

							<p class='way'>".$this->cat.">>".$this->subcat."</p>
							<p class='optxt'>".$temp_array['description']."</p>

						</div>

						<div><div style='height:1px;overflow:hidden;clear:both;'></div></div>

					</div>";	
				}
				else 
				{
					$id_picture = $this->catid."-".$temp_array['numberSub'];// номер картинки для подкатегории
					list($width, $height, $picture) = $this->changeImageSize($this->path_picture_category, $id_picture);
					echo $page = "<a href=".$this->namepage."?catid=".$this->catid."&numberSub=".$temp_array['numberSub']."&page=1>
								<div class='category'>
								<div class='categoryPicture'>
									<img src=".$this->domain.$picture." ".$width." ".$height." align='middle'>
								</div>
								<div class='name'>".$temp_array['nameSubCat']."</div>
								</div></a>";
				}
	}				
////////// Вывод подкатегорий, если нет товаров
/////////// Вывод товаров, если есть товары
	public function writeProduct($category)
  {
    $c = 0;
	$this->domain = "http://".$_SERVER['SERVER_NAME']."/"; //имя домена
    switch($category)
	{
	case 'category':
		 $title = '';
		 $query = "SELECT * FROM subcat WHERE catid='$this->catid' ORDER by numberSub";
		break;
	case 'product':
		if (($this->numberSub)!= 0)
		{
			$title = "<div class='nameSubCat'> Подкатегория: ".$this->subcat." </div>";
		}
		 $query = "SELECT * FROM product WHERE catid='$this->catid' and numberSub='$this->numberSub' ORDER by id DESC LIMIT $this->start,$this->num";
	 break;
	 default: break;
	 }
	echo $title;
	$result = $this->select($query);
	$temp_array = mysql_fetch_assoc($result);	// Подкатегория class='nameSubCat'
	if ( ($array = mysql_num_rows($result)) < 10)
		$numeric = ceil($array / 2);
	else
		$numeric = ceil($this->num / 2);
	echo "<div class='collLeft'>";
	do{ 
		$c++;
		if($temp_array ) 
		{ 
			$this->outputInformation ($temp_array, $category);
		}
	} while($temp_array = mysql_fetch_assoc($result) and ($c < $numeric));
	echo "</div>";  
	echo "<div class='collRight'>";
	do{ 
		$c++;
		if($temp_array) 
		{
			$this->outputInformation ($temp_array, $category);
		}
	} while($temp_array = mysql_fetch_assoc($result));
	echo "</div>";
	echo "</div></BR>";
	echo "<div><div style='height:1px;overflow:hidden;clear:both;'></div></div>";
	if ($category == 'product')
	{
		$this->pagelink();
		echo "<div><div style='height:1px;overflow:hidden;clear:both;'></div></div>";
		include('structure/form.php');
	}
  }
 ////////////// возвращает число продуктов в данной категории и подкатегории ,или если не указана подкатегория
// то число подкатегорий
  public function numberProduct($catid, $numberSub, $flag)
  {
   if ($numberSub != 0 && !$flag) //если подкатегория существует и не равна 0, то проверяем на наличие товаров
	   {
		   $query = "SELECT * FROM product WHERE catid='$catid' and numberSub='$numberSub'";
		   
			if (($result = mysql_num_rows($this->select($query))) > 0)
			{
				$this->counter = $result;
				$this->init();
				$this->writeProduct("product");
			}
			else
			{	
				echo"В данной подкатегории нет товаров. Пожалуйста выберите другую подкатегорию<BR/>";
				$query = "SELECT * FROM subcat WHERE catid='$catid'";
				if (($result = mysql_num_rows($this->select($query))) > 0)
				{
					echo "<div style='font-size: 8px; color: #006688 width:200px; margin-left:40%;'>В данном разделе :".$this->writeWordSubCat($this->counter = $result)." </div>";
					$this->init();
					$this->writeProduct("category");
				}
				else
				{
					echo "подкатегорий в данном разделе нет. Пожалуйста выберите другой раздел </div>";
				}
			}
		}
	else 
		{
			$query = "SELECT * FROM subcat WHERE catid='$catid'";
			if (($result = mysql_num_rows($this->select($query))) > 0)
			{
			echo "<div style='font-size: 8px; color: #006688 width:200px; margin-left:40%;'>В данной категории :".$this->writeWordSubCat($this->counter = $result)."</div>";
			$this->init();
			$this->writeProduct("category");
			}
			else
			{
			$query = "SELECT * FROM category WHERE catid='$catid' and isCat = '1'"; // если есть товары в подкатегории
					if (($result = mysql_num_rows($this->select($query))) > 0)
					{
						$this->init();
						$this->numberSub = 0;
						$this->writeProduct("product");
					}
					else
						 echo "подкатегорий в данном разделе нет. Пожалуйста выберите другой раздел </div>";
			}
		}
  }
 ////////// выбор категории . Возвращает название
public function selectCat($catid)
{
	$query = "SELECT * FROM category WHERE catid='$catid'";
	$result = $this->select($query);
	if (mysql_num_rows($result) > 0) 
	{
		$array = mysql_fetch_array($result);
		$this->cat = $array["cat"];
		echo "<div style='width:350px; margin-left:35%;'><h3>".$this->cat." </h3></div>\n";                        //категория class='nameCategory'
		$this->selectsubCat($this->catid, $this->numberSub);
	}
	else
	{
		echo "такой категории нет </div> ";
	}

}
 ////////// выбор подкатегории . Возвращает название
public function selectsubCat($catid, $numberSub)
	{
		$flag = false;
		if ($numberSub != 0)
		{
			$query = "SELECT * FROM subcat WHERE catid='$catid' and numberSub='$numberSub'";
			$result = $this->select($query);
			if (mysql_num_rows($result) > 0) 
			{
				$array = mysql_fetch_array($result);
				$this->subcat = $array["nameSubCat"];
				$this->numberProduct($this->catid, $this->numberSub,$flag);
			}
			else
			{ 
				echo "такой подкатегории не существует. Выберите из списка подкатегорий";
				$flag = true;
				$this->numberProduct($this->catid, $this->numberSub,$flag);
			}
		}
		else 
		{
			$this->numberProduct($this->catid, $this->numberSub,$flag);
		}
	}
//////////////////////////////функция, которая возвращает правильное окончание слова товар
public function writeWordTovar($num)
{
  $number = substr($num, -2);
  if($number > 10 and $number < 15)
    {
        $term = "ов";
    }
    else
    { 
     $number = substr($number, -1);
        if($number == 0) {$term = "ов";}
        if($number == 1 ) {$term = "";}
        if($number > 1 ) {$term = "а";}
        if($number > 4 ) {$term = "ов";}
    }
     
    return "$num товар$term";
}
//////////////////////////////функция, которая возвращает правильное окончание слова подкатегория
public function writeWordSubCat($num)
{
  $number = substr($num, -2);
  if($number > 10 and $number < 15)
    {
        $term = "ий";
    }
    else
    { 
     $number = substr($number, -1);
        if($number == 0) {$term = "ий";}
        if($number == 1 ) {$term = "ия";}
        if($number > 1 ) {$term = "ии";}
        if($number > 4 ) {$term = "ий";}
    }
     
    return "$num подкатегор$term";
}
/////////////////////////////////////////////////////////////////////////////////
public function init() 
	{
		if(isset($_GET['page'])) $page = (int) mysql_real_escape_string($_GET['page']);
		$total = intval(($this->counter - 1)/ ($this->num)) + 1;
		$this->total = $total;
		$page = intval($page);

		if(empty($page) or ($page < 0)) $page = 1;
		if($page > $total) $page = $total;
		$this->page = $page;
		
	// вычисляем начиная с какого номера надо выводить товары
		$start = $this->num * $this->page - $this->num;
		if($start < 0) $start = 0;
		$this->start = $start;
	}
///   устанавливаем ссылки
public function pagelink()
	{ 
		$namepage = $this->namepage;
		$total = $this->total;
		$page = $this->page;
		$catid = $this->catid;
		if ($this->numberSub != 0) 
			$text = "&numberSub=$this->numberSub";
		else
			$text = '';

		$firstpage = '<li><a href='.$namepage.'?catid='.$catid.''.$text.'&page=1> 1.. </a></li>';
		$lastpage = '<li><a href='.$namepage.'?catid='.$catid.''.$text.'&page='.$total.'>  ..'.$total.' </a></li>';

		// проверяем нужны ли стрелки назад
		if($page - 1 > 0) $prevpage1 = '<li><a href='.$namepage.'?catid='.$catid.''.$text.'&page='.($page-1).'> <'.($page-1).' </a></li>';
		if($page - 2 > 0) $prevpage2 = '<li><a href='.$namepage.'?catid='.$catid.''.$text.'&page='.($page-2).'> <'.($page-2).' </a></li>';


		// проверяем нужны ли стрелки вперед
		if($page+1 <= $total) $nextpage1 = '<li><a href='.$namepage.'?catid='.$catid.''.$text.'&page='.($page+1).'> '.($page+1).'></a></li>';
		if($page+2 <= $total) $nextpage2 = '<li><a href='.$namepage.'?catid='.$catid.''.$text.'&page='.($page+2).'> '.($page+2).'></a></li>';
		if($total>1) 
		echo "<div class='lables'><ul>".$firstpage.$prevpage2.$prevpage1."  <li class='current-Lab'>  ".$page."  </li>".$nextpage1.$nextpage2.$lastpage."<ul></div>";
	}
}
////////////////////////////////////////////////////////////////////////////////////// конец класса
//////основное тело страницы
{ 
// проверяем на существование 
	if (isset($_GET['catid']) && ($_GET['catid'] > 0)) 
	{
		$catid = trim($_GET['catid']); 
		$catid= mysql_real_escape_string($catid);
		$catid = (int) htmlspecialchars($catid);
	}
	if (!isset($_GET['catid'])) {$catid = 1;}
}
{
// проверяем на существование
	if (isset($_GET['numberSub']) && ($_GET['numberSub'] > 0)) 
	{
		$numberSub = trim($_GET['numberSub']); 
		$numberSub = mysql_real_escape_string($numberSub);
		$numberSub = (int) htmlspecialchars($numberSub);
	}
	if (!isset($_GET['numberSub'])) {$numberSub = 0;}
}
	echo "<div>";
//// создаем объект страницы
	$objectPage = new WriteProducts();
	$objectPage->catid = $catid;
	$objectPage->numberSub = $numberSub;
	$objectPage->selectCat($catid, $numberSub);
	mysql_close($link);
?>