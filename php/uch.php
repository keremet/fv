<?php
session_start();
$id = isset($_GET['id'])?$_GET['id']:null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Участник</title>
<script src="dates.js"></script>	
<script>
function checkDelUch(){
	if(confirm('Действительно удалить участника?')){
		document.getElementById('oper_type').value='delete';
		return true;
	}
	return false;
}

function saveUch()
{
	var arr_err = [];
	
	document.getElementById("ofv_uch_birthday_cor").value = form_and_check_std_dat(document.getElementById("ofv_uch_birthday").value, arr_err);
	if(arr_err.length>0){
		alert("Ошибка в дате рождения: "+arr_err[0])
		document.getElementById("ofv_uch_birthday").focus();
		document.getElementById("ofv_uch_birthday").select();
		return false;
	}
	
	document.getElementById("ofv_uch_pasp_date_cor").value = form_and_check_std_dat(document.getElementById("ofv_uch_pasp_date").value, arr_err);
	if(arr_err.length>0){
		alert("Ошибка в дате выдачи паспорта: "+arr_err[0])
		document.getElementById("ofv_uch_pasp_date").focus();
		document.getElementById("ofv_uch_pasp_date").select();
		return false;
	}
	return true;
}

</script>	
</head>
<body>


<table style="page-break-before: always;" width="262" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
		<td>
			<pre style="text-align: left;"><a href="index.php">
                            <font face="Liberation Mono, monospace"><font size="2">Участники</font></font></a></pre>
		</td>	
<?php
if($id != null){
?>
		<td>
			<pre style="text-align: left;"><a href="acc_list.php?uch_id=<?=$id?>">
                            <font face="Liberation Mono, monospace"><font size="2">Счета</font></font></a></pre>
		</td>	
<!--                <td>
			<pre style="text-align: left;"><a href="transact_list.php?uch_id=<=$id?>">
                            <font face="Liberation Mono, monospace"><font size="2">Транзакции</font></font></a></pre>
		</td>-->
<?php
}
?>		
		<td>
			<pre style="text-align: left;"><a href="exit.php">
                            <font face="Liberation Mono, monospace"><font size="2">Выход</font></font></a></pre>
		</td>		
	</tr>
</table>
<?php	
include 'connect.php';
if($id != null){
	$stmt = $db->prepare(
		"SELECT id, name, address, DATE_FORMAT(birthday, '%d%m%Y') as birthday 
                    ,LPAD(pasp_ser, 4, '0') as pasp_ser, LPAD(pasp_num, 6, '0') as pasp_num
                    ,DATE_FORMAT(pasp_date, '%d%m%Y') as pasp_date, pasp_who
		 FROM ofv_uch
		 WHERE id = ?");
	$stmt->execute(array($id));
	$uch = $stmt->fetch();
}
?>

<form id="main_form" action="uch_save.php" method="post">
<table border="0" cellpadding="0" cellspacing="2">
<tr><td>ФИО/Название<td><input id="ofv_uch_name"  name="ofv_uch_name" size="30" 
                        type="text" value="<?=(($id!=null)?$uch['name']:'')?>">
<tr><td>Адрес<td><input id="ofv_uch_address"  name="ofv_uch_address" size="30" 
                        type="text" value="<?=(($id!=null)?$uch['address']:'')?>">
<tr><td>Дата рождения<td><input id="ofv_uch_birthday"  name="ofv_uch_birthday" size="8" 
                        type="text" value="<?=(($id!=null)?$uch['birthday']:'')?>" 
                        maxlength="8" onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
<tr><td>Серия паспорта<td><input id="ofv_uch_pasp_ser"  name="ofv_uch_pasp_ser" size="4" 
                        type="text" value="<?=(($id!=null)?$uch['pasp_ser']:'')?>" 
                        maxlength="4" onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
<tr><td>Номер паспорта<td><input id="ofv_uch_pasp_num"  name="ofv_uch_pasp_num" size="6"  maxlength="6" 
                        type="text" value="<?=(($id!=null)?$uch['pasp_num']:'')?>" onkeyup="return proverka_dat(this);" 
                        onchange="return proverka_dat(this);">
<tr><td>Кем выдан паспорт<td><input id="ofv_uch_pasp_who"  name="ofv_uch_pasp_who" size="30" 
                        type="text" value="<?=(($id!=null)?$uch['pasp_who']:'')?>">
<tr><td>Дата выдачи паспорта<td><input id="ofv_uch_pasp_date"  name="ofv_uch_pasp_date" size="8"  maxlength="8" 
                        type="text" value="<?=(($id!=null)?$uch['pasp_date']:'')?>" onkeyup="return proverka_dat(this);" 
                        onchange="return proverka_dat(this);">
</table>

<br><input value="<?=(($id==null)?"Создать участника":"Сохранить")?>" type="submit"  onclick="return saveUch();">
<input value="Удалить участника" type="submit" onclick="return checkDelUch();">
<input type="hidden" id="oper_type" name="oper_type" value="update">
<input type="hidden" id="id" name="id" value="<?=$id?>">
<input type="hidden" id="ofv_uch_birthday_cor" name="ofv_uch_birthday_cor">
<input type="hidden" id="ofv_uch_pasp_date_cor" name="ofv_uch_pasp_date_cor">
</form>
</body>
</html> 