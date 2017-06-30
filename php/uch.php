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
	
	var ofv_uch_birthday = document.getElementById("ofv_uch_birthday").value;
	if (ofv_uch_birthday != '' ) {
		document.getElementById("ofv_uch_birthday_cor").value = form_and_check_std_dat(ofv_uch_birthday, arr_err);
		if(arr_err.length>0){
			alert("Ошибка в дате рождения: "+arr_err[0])
			document.getElementById("ofv_uch_birthday").focus();
			document.getElementById("ofv_uch_birthday").select();
			return false;
		}
	}
	
	var ofv_uch_pasp_date = document.getElementById("ofv_uch_pasp_date").value;
	if (ofv_uch_pasp_date != '' ) {
		document.getElementById("ofv_uch_pasp_date_cor").value = form_and_check_std_dat(ofv_uch_pasp_date, arr_err);
		if(arr_err.length>0){
			alert("Ошибка в дате выдачи паспорта: "+arr_err[0])
			document.getElementById("ofv_uch_pasp_date").focus();
			document.getElementById("ofv_uch_pasp_date").select();
			return false;
		}
	}
	return true;
}

</script>	
</head>
<body>
<table style="page-break-before: always;" width="262" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="index.php">Участники</a>
<?php if($id != null){ ?>
	<td align="left"><a href="acc_list.php?uch_id=<?=$id?>">Счета</a>
        <td align="left"><a href="loan_agr_list.php?uch_id=<?=$id?>">Займы</a>
<?php } ?>
</table>
<br/>
<?php	
include 'connect.php';
if($id != null){
	$stmt = $db->prepare(
		"SELECT id, name, address, address_fact, pol_m, to_char(birthday, 'ddmmyyyy') as birthday 
                    ,pasp_ser, pasp_num
                    ,to_char(pasp_date, 'ddmmyyyy') as pasp_date, pasp_who
		 FROM uch
		 WHERE id = ?");
	$stmt->execute(array($id));
	$uch = $stmt->fetch();
	$pol = $uch['pol_m'];
}
?>

<form id="main_form" action="uch_save.php" method="post">
<table border="0" cellpadding="0" cellspacing="2">
<tr><td>ФИО/Название<td><input id="ofv_uch_name"  name="ofv_uch_name" size="30" 
                        type="text" value="<?=(($id!=null)?$uch['name']:'')?>">
<tr><td>Адрес регистрации<td><input id="ofv_uch_address"  name="ofv_uch_address" size="30" 
                        type="text" value="<?=(($id!=null)?$uch['address']:'')?>">
<tr><td>Адрес фактический<td><input id="ofv_uch_address_fact"  name="ofv_uch_address_fact" size="30" 
                        type="text" value="<?=(($id!=null)?$uch['address_fact']:'')?>">
<tr><td>Пол<td><select name="ofv_uch_pol" id="ofv_uch_pol">
<option value="" <?=((is_null($id)||is_null($pol))?'selected="selected"':'')?>></option>
<option value="1" <?=(((!is_null($id))&&($pol))?'selected="selected"':'')?>>М</option>
<option value="0" <?=(((!is_null($id))&&(!is_null($pol))&&(!$pol))?'selected="selected"':'')?>>Ж</option>
</select>
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
<?php if($id != null){ ?>
<input value="Удалить участника" type="submit" onclick="return checkDelUch();">
<?php } ?>
<input type="hidden" id="oper_type" name="oper_type" value="update">
<input type="hidden" id="id" name="id" value="<?=$id?>">
<input type="hidden" id="ofv_uch_birthday_cor" name="ofv_uch_birthday_cor">
<input type="hidden" id="ofv_uch_pasp_date_cor" name="ofv_uch_pasp_date_cor">
</form>
</body>
</html> 
