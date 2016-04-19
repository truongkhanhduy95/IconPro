<?php
//echo $_SERVER['QUERY_STRING'];


if($vAct=='del' && !empty($vID)) {
	$vSQL_Del = "DELETE FROM menus WHERE ID='$vID'";
	
	$vResult_Del = mysql_query($vSQL_Del);
			
	if($vResult_Del) {
		$vMsg = "Bạn đã xóa một menu thành công!";
		header("Location: ?mod=$vModule&msgstatus=$vMsg");
	}
}

if(isset($_POST["btnSubmit"])) 
{
	if($vAct=='add' || $vAct=='edit') 
	{
		$vParentID  = isset($_POST["cboMenuParent"]) ? $_POST["cboMenuParent"] : 0;
		$vModuleName  = isset($_POST["cboModuleName"]) ? $_POST["cboModuleName"] : '';
		$vMenuName  = isset($_POST["txtMenuName"]) ? $_POST["txtMenuName"] : '';
		$vPath 		= isset($_POST["txtPath"]) ? $_POST["txtPath"] : '';
		$vVisible 	= isset($_POST["chkVisible"]) ? 1 : 0;
		$vPossition = isset($_POST["cboPossition"]) ? $_POST["cboPossition"] : 0;
		$vRoles 	= isset($_POST["chkRoles"]) ? $_POST["chkRoles"] : '';

		if($vAct=='add') {
			$vSQL_Ins = "INSERT INTO menus(ParentID, ModuleName, MenuName, Path, Visible, Possition)
				VALUES($vParentID, '$vModuleName', '$vMenuName', '$vPath', $vVisible, $vPossition)";
				
			$vResult_Ins = mysql_query($vSQL_Ins);
			
			if($vResult_Ins) {
				//Lấy ID mới nhất sau khi thêm mới
				$vSQL_Role_Sel = "SELECT MAX(ID) as MenuID FROM menus";
				$vResult_Role_Sel = mysql_query($vSQL_Role_Sel);
				$rowMenuID = mysql_fetch_array($vResult_Role_Sel);
				//Thêm danh sách vai trò được chọn
				if(is_array($vRoles)) {
					for($i=0; $i<count($vRoles); $i++) {					
						$vSQL_Role_Ins = "INSERT INTO menu_role(MenuID, RoleID) 
							VALUES (".$rowMenuID["MenuID"].", ".$vRoles[$i].")";
						mysql_query($vSQL_Role_Ins);
					}
				}
			
				$vMsg = "Bạn đã thêm mới một menu thành công!";
				header("Location: ?mod=$vModule&msgstatus=$vMsg");
			}
		} 
		elseif($vAct=='edit' && !empty($vID)) 
		{
			$vSQL_Upd = "UPDATE menus 
				SET ParentID = $vParentID,
					ModuleName = '$vModuleName', 
					MenuName = '$vMenuName', 
					Path = '$vPath', 
					Visible = $vVisible, 
					Possition = $vPossition 
				WHERE ID=$vID
			";	

			$vResult_Upd = mysql_query($vSQL_Upd);
			
			if($vResult_Upd) {
				//Xóa tất cả vai trò theo ID menu đang sửa
				$vSQL_Role_Del = "DELETE FROM menu_role WHERE MenuID=$vID";
				//echo $vSQL_Role_Del;
				mysql_query($vSQL_Role_Del);
				//Thêm danh sách vai trò được chọn
				if(is_array($vRoles)) {
					for($i=0; $i<count($vRoles); $i++) {					
						$vSQL_Role_Ins = "INSERT INTO menu_role(MenuID, RoleID)
							VALUES ($vID, ".$vRoles[$i].")";
						mysql_query($vSQL_Role_Ins);
					}
				}

				$vMsg = "Bạn đã chỉnh sửa thông tin menu thành công!";
				header("Location: ?mod=$vModule&msgstatus=$vMsg");
			}
		}
	}
}

$eParentID = 0;
$ePossition = 0;
$eVisible = 0;
if($vAct=='edit') { 
	$vSQL_Edit = "SELECT * FROM menus WHERE ID=$vID";
	$vResult_Edit = mysql_query($vSQL_Edit);
	$vRow_Edit = mysql_fetch_array($vResult_Edit);
	
	$eParentID	= $vRow_Edit["ParentID"];
	$eModuleName= $vRow_Edit["ModuleName"];
	$eMenuName	= $vRow_Edit["MenuName"];
	$ePath		= $vRow_Edit["Path"];
	$eVisible	= (isset($vRow_Edit["Visible"]) || !empty($vRow_Edit["Visible"])) ? 0 : 1;
	$ePossition	= $vRow_Edit["Possition"];
	
	$vSQL_Edit_Role = "SELECT * FROM menu_role WHERE MenuID=$vID";
	$vResult_Edit_Role = mysql_query($vSQL_Edit_Role);
	while($vRow_Edit_Role = mysql_fetch_array($vResult_Edit_Role)) {
		$eRoles[] = $vRow_Edit_Role["RoleID"];
	}
}

//Trường hợp lấp danh sách sản phẩm
$vSQL_Sel = "SELECT * FROM menus ORDER BY Possition ASC";
$vResult_Sel = mysql_query($vSQL_Sel);

//Lấy danh sách vai trò
$vSQL_Roles = "SELECT * FROM roles ORDER BY RoleName ASC";
$vResult_Roles = mysql_query($vSQL_Roles);

?>

<h3>Quản lý menu chính</h3>

<?php if(!empty($vMsgStatus)) { ?>
<div class="status_message"><?php echo $vMsgStatus; ?></div>
<?php } ?>

<?php if($vAct=='add' || $vAct=='edit') { ?>


<fieldset>
<legend>Thêm mới và điều chỉnh thông tin người dùng</legend>

<form method="post" action="" id="mmenus_Form" enctype="multipart/form-data" >
<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td>Menu cha:</td>
		<td>
			<select name="cboMenuParent">
				<option value="0">--Root--</option>
				<?php echo combobox_menu($eParentID); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Tên module:</td>
		<td>
			<select name="cboModuleName">
				<option value="administrator">administrator</option>
				<?php 
				$arrModule = scandir('modules');
				if(count($arrModule)>=2) {
					for($m=2; $m<count($arrModule); $m++) {
						$selectedModule= '';
						if($arrModule[$m]==$eModuleName) $selectedModule = 'selected="selected"';
						echo '<option value="'.$arrModule[$m].'" '.$selectedModule.'>'.$arrModule[$m].'</option>';
					}
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Tên menu:</td>
		<td><input type="text" name="txtMenuName" id="txtMenuName" style="width:250px;" value="<?php echo isset($eMenuName) ? $eMenuName : ''; ?>" /></td>
	</tr>
	<tr>
		<td>Đường dẫn:</td>
		<td><input type="text" name="txtPath" id="txtPath" style="width:250px;" value="<?php echo isset($ePath) ? $ePath : ''; ?>" /></td>
	</tr>
	<tr>
		<td>Cho phép hiển thị:</td>
		<td><input type="checkbox" name="chkVisible" id="chkVisible" <?php echo $eVisible==1? 'checked="checked"' : ''; ?> /></td>
	</tr>
	<tr>
		<td>Vị trí:</td>
		<td>
			<select name="cboPossition">
				<?php 
				for($i=-10; $i<=20; $i++) {
					$selectedPossition = "";
					if($i==$ePossition) $selectedPossition = 'selected="selected"';
					echo '<option value="'.$i.'" '.$selectedPossition.' >'.$i.'</option>'."\n";
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Vai trò:</td>
		<td>
		<?php 
		while($rowRole = mysql_fetch_array($vResult_Roles)) {
			$checkedRole = '';
			if(isset($eRoles) && is_array($eRoles)) {
				for($r=0; $r < count($eRoles); $r++) {
					if($rowRole["ID"] == $eRoles[$r]) {
						$checkedRole = 'checked="checked"';
						break;
					}
				}
			}
			
			echo '<input type="checkbox" name="chkRoles[]" value="'. $rowRole["ID"]. '" '.$checkedRole.' /> '. $rowRole["RoleName"] ."&nbsp; \n";
		}
		?>
		</td>
	</tr>
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
<legend>Danh sách sản phẩm</legend>
<p><a href="?mod=<?php echo $vModule; ?>&act=add">Thêm mới một sản phẩm</a></p>
<table border="0" cellspacing="0" cellpadding="0" class="table-result">
  <tr>
    <th class="col-first" width="30">STT</th>
	<th>Tên module</th>
	<th>Tên menu</th>
    <th>Đường dẫn</th>
    <th width="60">Cho phép hiển thị</th>
	<th width="50">Vị trí</th>
	<th colspan="2">Hoạt động</th>
  </tr>
  
  <?php 
  $stt = 1;
  while($vRowSel = mysql_fetch_array($vResult_Sel)) { ?>
  <tr>
    <td class="col-first" align="center"><?php echo $stt; ?></td>
	<td><?php echo $vRowSel["ModuleName"]; ?></td>
	<td><?php echo $vRowSel["MenuName"]; ?></td>
    <td><?php echo $vRowSel["Path"]; ?></td>
    <td align="center"><?php echo $vRowSel["Visible"]; ?></td>
	<td align="center"><?php echo $vRowSel["Possition"]; ?></td>
	<td align="center" width="50"><a href="?mod=<?php echo $vModule; ?>&act=edit&id=<?php echo $vRowSel["ID"]; ?>">Sửa</a></td>
	<td align="center" width="50"><a href="?mod=<?php echo $vModule; ?>&act=del&id=<?php echo $vRowSel["ID"]; ?>" onclick="return confirm('Bạn có muốn thực hiện xóa bản ghi này không?')" >Xóa</a></td>
  </tr>
  <?php 
  	$stt++;
  } ?>
</table>

</fieldset>

