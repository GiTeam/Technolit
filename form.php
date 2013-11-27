<? echo"<div style=' display:block; width: 100%;'>
        <form class='form-container' action='excelwrite.php' method='Post'>
            <div class='form-title'><h2>Обратная связь</h2></div>

            <div class='form-title'>Ф.И.О.</div>
                <input class='form-field' type='text' name='fullname' /><br />

            <div class='form-title'>Телефон</div>
                <input class='form-field' type='text' name='phone' /><br />

            <div class='form-title'>Список заказываемых товаров</div>
                <ol id='itemBox'>
					<li><input class='form-field' type='text' name='goods1' /></li>
				</ol>
				<input id='plus' type='button' value='+Добавить строку' />
				<input id='minus' class='minus' type='button' value='-' />
                <br /><br />

            <div class='submit-container'>
                <input class='submit-button' type='submit' value='Отправить' />
            </div>
        </form>
    
    </div>

	"; ?>
