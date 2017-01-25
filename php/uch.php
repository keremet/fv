<?php
session_start();
$agr_id = isset($_GET['agr_id'])?$_GET['agr_id']:null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Участник</title>
<script src="dates.js"></script>	
<script>
function checkDelAgr(){
	if(confirm('Действительно удалить участника?')){
		document.getElementById('oper_type').value='delete';
		return true;
	}
	return false;
}

function saveAgr()
{
	return true;
}

</script>	
</head>
<body>


<table style="page-break-before: always;" width="262" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
		<td>
			<pre style="text-align: left;"><a href="index.php"><font face="Liberation Mono, monospace"><font size="2">Участники</font></font></a></pre>
		</td>		
		<td>
			<pre style="text-align: left;"><a href="exit.php"><font face="Liberation Mono, monospace"><font size="2">Выход</font></font></a></pre>
		</td>		
	</tr>
</table>
<form id="main_form" action="uch_save.php" method="post">
<table border="0" cellpadding="0" cellspacing="2">
<tr><td>ФИО/Название<td><input id="ofv_uch_name"  name="ofv_uch_name" size="30" type="text">
<tr><td>Адрес<td><input id="ofv_uch_address"  name="ofv_uch_address" size="30" type="text">
<tr><td>Дата рождения<td><input id="ofv_uch_birthday"  name="ofv_uch_birthday" size="8" type="text" maxlength="8" onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
<tr><td>Серия паспорта<td><input id="ofv_uch_pasp_ser"  name="ofv_uch_pasp_ser" size="4" type="text" maxlength="4" onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
<tr><td>Номер паспорта<td><input id="ofv_uch_pasp_num"  name="ofv_uch_pasp_num" size="6"  maxlength="6" type="text" onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
<tr><td>Кем выдан паспорт<td><input id="ofv_uch_pasp_who"  name="ofv_uch_pasp_who" size="30" type="text">
<tr><td>Дата выдачи паспорта<td><input id="ofv_uch_pasp_date"  name="ofv_uch_pasp_date" size="8"  maxlength="8" type="text" onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
<tr><td>Тип участника<td><select name="ofv_uch_type" id="ofv_uch_type">'
<?php	
include 'connect.php';

foreach($db->query(
			"SELECT id, name
   			 FROM ofv_uch_type
			 ORDER BY id"
			 ) as $row){
				echo '<option value="'.$row['id'].'">'.$row['name'].'</option>;';
			 }
			 echo '
			 </select>
</table>

<br><input value="'.(($agr_id==null)?"Создать участника":"Сохранить").'" type="submit"  onclick="return saveAgr();">
<input type="hidden" id="agr_id" name="agr_id" value="'.$agr_id.'">';?>
</form>
</body>
</html> 
