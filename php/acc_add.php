<?php
//session_start();
include 'connect.php';
if (isset($_GET['acc_id'])) {
	$acc_id = $_GET['acc_id'];
	$stmt = $db->prepare(
		"SELECT type_id, uch_id, DATE_FORMAT(creat_date, '%d%m%Y') as creat_date
                ,DATE_FORMAT(clos_date, '%d%m%Y') as clos_date
                ,remark
		 FROM ofv_acc
		 WHERE id = ?");
	$stmt->execute(array($acc_id));
	$acc = $stmt->fetch();
	$uch_id = $acc['uch_id'];
} else {
	$acc_id = null;
	$uch_id = isset($_GET['uch_id'])?$_GET['uch_id']:null;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Счёт</title>
</head>
<body>
<script src="dates.js"></script>	
<script>
function checkDelAcc(){
	if(confirm('Действительно удалить счёт?')){
		document.getElementById('oper_type').value='delete';
		return true;
	}
	return false;
}

function saveAcc(){
	var arr_err = [];
	
	document.getElementById("ofv_acc_creat_date_cor").value = 
		form_and_check_std_dat(document.getElementById("ofv_acc_creat_date").value, arr_err);
    if(arr_err.length>0){
		alert("Ошибка в дате создания: "+arr_err[0]);
		document.getElementById("ofv_acc_creat_date").focus();
		document.getElementById("ofv_acc_creat_date").select();
		return false;
    }
<?php if ($acc_id!=null) { ?>  
	var v_ofv_acc_clos_date = document.getElementById("ofv_acc_clos_date").value;
	if ( v_ofv_acc_clos_date != '' ) {
		document.getElementById("ofv_acc_clos_date_cor").value = 
			form_and_check_std_dat(v_ofv_acc_clos_date, arr_err);
		if(arr_err.length>0){
			alert("Ошибка в дате закрытия: "+arr_err[0]);
			document.getElementById("ofv_acc_clos_date").focus();
			document.getElementById("ofv_acc_clos_date").select();
			return false;
		}
	}
<?php } ?>  
    return true;
}

</script>	
<table style="page-break-before: always;" width="262" border="0" cellpadding="0" cellspacing="0">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="index.php">Участники</a>
	<td align="left"><a href="acc_list.php?uch_id=<?=$uch_id?>">Счета</a>
<?php if ($acc_id!=null) { ?>
	<td align="left"><a href="ent_list.php?acc_id=<?=$acc_id?>">Движение по счету</a>
<?php } ?>
</table>
<br/>
<form id="main_form" action="acc_save.php" method="post">
<table border="0" cellpadding="0" cellspacing="2">
    
<tr><td>Примечание<td><input id="ofv_acc_remark"  name="ofv_acc_remark" size="30" type="text" 
value="<?=(($acc_id!=null)?$acc['remark']:'')?>">    
        
<tr><td>Дата открытия<td><input id="ofv_acc_creat_date"  name="ofv_acc_creat_date" size="8" type="text" 
value="<?=(($acc_id!=null)?$acc['creat_date']:'')?>" maxlength="8" 
onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">

<?php if ($acc_id!=null){?>     
	
<tr><td>Дата закрытия<td><input id="ofv_acc_clos_date"  name="ofv_acc_clos_date" size="8" type="text" 
value="<?=(($acc_id!=null)?$acc['clos_date']:'')?>" maxlength="8" 
onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
        

<?php }else{?>	        
	
<tr><td>Тип счёта<td><select name="ofv_acc_type" id="ofv_acc_type">
            
<?php
    foreach($db->query(
    	"SELECT id, name
	     FROM ofv_acc_type
		 ORDER BY id"
    ) as $row){
         echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }             
}
?>
</table>

<br><input value="<?=(($acc_id==null)?"Создать счёт":"Сохранить")?>" type="submit"  onclick="return saveAcc();">

<?php if ($acc_id!=null) { ?>
<input value="Удалить счёт" type="submit" onclick="return checkDelAcc();">
<input type="hidden" id="ofv_acc_clos_date_cor" name="ofv_acc_clos_date_cor">
<?php } ?>

<input type="hidden" id="oper_type" name="oper_type" value="update">
<input type="hidden" id="uch_id" name="uch_id" value="<?=$uch_id?>">
<input type="hidden" id="acc_id" name="acc_id" value="<?=$acc_id?>">
<input type="hidden" id="ofv_acc_creat_date_cor" name="ofv_acc_creat_date_cor">

</form>
</body>
</html> 
