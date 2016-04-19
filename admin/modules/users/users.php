<?php

if($vAct=='del' && !empty($vID)) {
	$vSQL_Del = "DELETE FROM users WHERE username='$vID'";
	
	$vResult_Del = mysql_query($vSQL_Del);
			
	if($vResult_Del) {
		$vMsg = "Bạn đã xóa một người dùng thành công!";
		header("Location: ?mod=$vModule&msgstatus=$vMsg");
	}
}

if(isset($_POST["btnSubmit"])) 
{
	if($vAct=='add' || $vAct=='edit') 
	{
		$vUsername  = isset($_POST["txtUsername"]) ? $_POST["txtUsername"] : '';
		$vPassword  = isset($_POST["txtPassword"]) ? $_POST["txtPassword"] : '';
		$vFirstname = isset($_POST["txtFirstname"]) ? $_POST["txtFirstname"] : '';
		$vLastname  = isset($_POST["txtLastname"]) ? $_POST["txtLastname"] : '';
		$vEmail 	= isset($_POST["txtEmail"]) ? $_POST["txtEmail"] : '';
		$vPhone 	= isset($_POST["txtPhone"]) ? $_POST["txtPhone"] : '';
		$vActive	= isset($_POST["chkActive"]) ? 1 : 0;
		
		if(!empty($vUsername) && !empty($vEmail)) 
		{
			if($vAct=='add') {
				$vSQL_Ins = "INSERT INTO users(username, password, firstname, lastname, email, phone, active, create_date, create_user)
					VALUES('$vUsername', '$vPassword', '$vFirstname', '$vLastname', '$vEmail', '$vPhone',$vActive, CURRENT_TIMESTAMP(), '')";
					
				$vResult_Ins = mysql_query($vSQL_Ins);
				
				if($vResult_Ins) {
					$vMsg = "Bạn đã thêm mới một người dùng thành công!";
					header("Location: ?mod=$vModule&msgstatus=$vMsg");
				}
			} 
			elseif($vAct=='edit' && !empty($vID)) {
				if($vID=='admin' && $_SESSION['user']!='admin') {
					$vMsg = "Bạn không có quyền sửa thông tin của '$vID'!";
					header("Location: ?mod=$vModule&msgstatus=$vMsg");
				}
				else
				{
					$vSQL_Upd="";
					if($vPassword=='') {
						$vSQL_Upd = "UPDATE users 
						SET firstname='$vFirstname',
							lastname='$vLastname',
							email='$vEmail',
							phone='$vPhone',
							active='$vActive',
							modify_date=CURRENT_TIMESTAMP(),
							modify_user='' 
						WHERE username='$vID'
					";
					} 
					else 
					{
						$vSQL_Upd = "UPDATE users 
							SET password='$vPassword', 
								firstname='$vFirstname',
								lastname='$vLastname',
								email='$vEmail',
								phone='$vPhone',
								active='$vActive',
								modify_date=CURRENT_TIMESTAMP(),
								modify_user='' 
							WHERE username='$vID'
						";
					}
					
					
					$vResult_Upd = mysql_query($vSQL_Upd);
					
					if($vResult_Upd) {
						$vMsg = "Bạn đã chỉnh sửa thông tin người dùng thành công!";
						header("Location: ?mod=$vModule&msgstatus=$vMsg");
					}
				}
			}
		}
	}
}

$eActive = 0;
if($vAct=='edit') { 
	$vSQL_Edit = "SELECT * FROM users WHERE username='$vID'";
	$vResult_Edit = mysql_query($vSQL_Edit);
	$vRow_Edit = mysql_fetch_array($vResult_Edit);
	
	$eUsername 		= $vRow_Edit["username"];
	$eFirstname 	= $vRow_Edit["firstname"];
	$eLastname 		= $vRow_Edit["lastname"];
	$eEmail		 	= $vRow_Edit["email"];
	$ePhone 		= $vRow_Edit["phone"];
	$eActive 		= $vRow_Edit["active"];
	$eCreateDate 	= $vRow_Edit["create_date"];
	$eCreateUser 	= $vRow_Edit["create_user"];
	$eModifyDate 	= $vRow_Edit["modify_date"];
	$eModifyUser 	= $vRow_Edit["modify_user"];
}

//Trường hợp lấp danh sách người dùng
$vSQL_Sel = "SELECT * FROM users ORDER BY Lastname, Firstname ASC";
$vResult_Sel = mysql_query($vSQL_Sel);

?>

<h3>Quản lý người dùng</h3>

<?php if(!empty($vMsgStatus)) { ?>
<div class="status_message"><?php echo $vMsgStatus; ?></div>
<?php } ?>

<?php if($vAct=='add' || $vAct=='edit') { ?>

<fieldset>
<legend>Thêm mới và điều chỉnh thông tin người dùng</legend>

<form method="post" action="" id="mUsers_Form" >
<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td>Tên đăng nhập</td>
		<td><input type="text" name="txtUsername" id="Username" style="width:250px;" value="<?php echo isset($eUsername) ? $eUsername : ''; ?>" <?php echo $vAct=='edit' ? 'readonly="readonly"' : ''; ?> /></td>
	</tr>
	<tr>
		<td>Mật khẩu</td>
		<td><input type="password" name="txtPassword" id="Password" style="width:250px;" value="" /></td>
	</tr>
	<tr>
		<td>Xác thực mật khẩu</td>
		<td><input type="password" name="txtPasswordVerify" id="PasswordVerify" style="width:250px;" value="" /></td>
	</tr>
	<tr>
		<td>Họ và tên</td>
		<td><input type="text" name="txtFirstname" id="Firstname" style="width:150px;" value="<?php echo isset($eFirstname) ? $eFirstname : ''; ?>" />
		<input type="text" name="txtLastname" id="Lastname" style="width:91px;" value="<?php echo isset($eLastname) ? $eLastname : ''; ?>" /></td>
	</tr>
	<tr>
		<td>Địa chỉ email</td>
		<td><input type="text" name="txtEmail" id="Email" style="width:250px;" value="<?php echo isset($eEmail) ? $eEmail : ''; ?>" /></td>
	</tr>
	<tr>
		<td>Điện thoại</td>
		<td><input type="text" name="txtPhone" id="Phone" style="width:250px;" value="<?php echo isset($ePhone) ? $ePhone : ''; ?>" /></td>
	</tr>
	<tr>
		<td>Đang sử dụng</td>
		<td><input type="checkbox" name="chkActive" id="Active" value="" <?php echo $eActive==1 ? 'checked="checked"' : ''; ?> /></td>
	</tr>
	
	<?php if($vAct=='edit') { ?>
	<tr>
		<td>Ngày khởi tạo</td>
		<td><?php echo isset($eCreateDate) && !empty($eCreateDate) ? $eCreateDate : '--Chưa thiết lập--'; ?></td>
	</tr>
	<tr>
		<td>Người khởi tạo</td>
		<td><?php echo isset($eCreateUser) && !empty($eCreateUser) ? $eCreateUser : '--Chưa thiết lập--'; ?></td>
	</tr>
	<tr>
		<td>Ngày chỉnh sửa</td>
		<td><?php echo isset($eModifyDate) && !empty($eModifyDate) ? $eModifyDate : '--Chưa thiết lập--'; ?></td>
	</tr>
	<tr>
		<td>Người chỉnh sửa</td>
		<td><?php echo isset($eModifyUser) && !empty($eModifyUser) ? $eModifyUser : '--Chưa thiết lập--'; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="btnSubmit" id="btnSubmit" value="Lưu" />
			<input type="reset" name="btnReset" id="btnReset" value="Nhập lại" />
		</td>
	</tr>

</table>
</form>

</fieldset>

<?php } ?>

<br />

<fieldset>
<legend>Danh sách người dùng</legend>
<p><a href="?mod=<?php echo $vModule; ?>&act=add">Thêm mới một người dùng</a></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-result">
  <tr>
    <th class="col-first" width="30">STT</th>
	<th>Tên đăng nhập</th>
    <th>Họ lót</th>
    <th>Tên</th>
    <th>Email</th>
    <th>Điện thoại</th>
	<th width="50">Sử dụng</th>
	<th colspan="2">Hoạt động</th>
  </tr>
  
  <?php 
  $stt = 1;
  while($vRowSel = mysql_fetch_array($vResult_Sel)) { ?>
  <tr>
    <td class="col-first" align="center"><?php echo $stt; ?></td>
	<td><?php echo $vRowSel["username"]; ?></td>
    <td><?php echo $vRowSel["firstname"]; ?></td>
    <td><?php echo $vRowSel["lastname"]; ?></td>
    <td><?php echo $vRowSel["email"]; ?></td>
    <td><?php echo $vRowSel["phone"]; ?></td>
	<td align="center"><input name="checkbox" type="checkbox" value="checkbox" disabled="disabled" <?php echo ($vRowSel["active"]==1 ? 'checked="checked"' : ''); ?> /></td>
	<td align="center"><a href="?mod=<?php echo $vModule; ?>&act=edit&id=<?php echo $vRowSel["username"]; ?>">Sửa</a></td>
	<td align="center"><a href="?mod=<?php echo $vModule; ?>&act=del&id=<?php echo $vRowSel["username"]; ?>" onclick="return confirm('Bạn có muốn thực hiện xóa bản ghi này không?')" >Xóa</a></td>
  </tr>
  <?php 
  	$stt++;
  } ?>
</table>

</fieldset>