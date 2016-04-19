<?php

/*for($i=0;$i<=300;$i++)
	{
	$vSQL_Ins = "INSERT INTO products(ProductTypeID, ProductName, SKU, Price, Image, Description)
				VALUES(1,'SanPham-Nokia-$i','SKU-$i',0,'','')";
	$vResult_Ins = mysql_query($vSQL_Ins);
	}
*/


//echo $_SERVER["QUERY_STRING"];

//1. xác định số hàng hiển thị
$sohanghienthi = 10;

//2. xác định trang hiện hành
$tranghienhanh = isset($_GET["page"]) ? ($_GET["page"]>0 ? $_GET["page"] : 1  ) : 1;
if($tranghienhanh<1) header("Location: ?mod=$vModule&page=1");

//3. xác định tổng số bảng ghi
$vSQL_1 = "SELECT a.*, b.ProductTypeName FROM products as a
	INNER JOIN products_type as b ON b.ID = a.ProductTypeID";
    $tongsobanghi = mysql_num_rows(mysql_query($vSQL_1));
    
//4. Xác định tổng số trang (chia lấy cận trên)
$tongsotrang = ceil($tongsobanghi / $sohanghienthi);

//5. Xác định bản ghi bắt đầu
$banghibatdau = ($tranghienhanh - 1) * $sohanghienthi;



//Tìm kiếm
$vSQL_Search = "";
if(isset($_POST["btnSearch"])) {
    $searchtext = $_POST["txtSearchText"];
    $vSQL_Search = "WHERE (a.ProductName LIKE '%$searchtext%')
	    		    or (a.Price LIKE '%$searchtext%')
       				or (a.SKU LIKE '%$searchtext%')
        			or (a.Description LIKE '%$searchtext%')
     ";
} 


//Trường hợp lấp danh sách sản phẩm (theo phân trang)
$vSQL_Sel = "SELECT a.*, b.ProductTypeName FROM products as a
	INNER JOIN products_type as b ON b.ID = a.ProductTypeID 
    $vSQL_Search
	ORDER BY ProductName ASC
    LIMIT $banghibatdau, $sohanghienthi";
$vResult_Sel = mysql_query($vSQL_Sel);






$vSQL_PL = "SELECT * FROM products_type ORDER BY Possition ASC";
$vResult_PL = mysql_query($vSQL_PL);


// Phần Quản trị nội dung
if($vAct=='del' && !empty($vID)) {
	$vSQL_Del = "DELETE FROM products WHERE ID='$vID'";
	
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
		$vProductTypeID = isset($_POST["cboProductType"]) ? $_POST["cboProductType"] : 0;
		$vProductName  = isset($_POST["txtProductName"]) ? $_POST["txtProductName"] : '';
		$vSKU 	= isset($_POST["txtSKU"]) ? $_POST["txtSKU"] : '';
		$vPrice 	= isset($_POST["txtPrice"]) ? $_POST["txtPrice"] : 0;
		//$vImage 	= isset($_POST["txtImage"]) ? $_POST["txtImage"] : '';
		$vDescription 	= isset($_POST["txtDescription"]) ? $_POST["txtDescription"] : '';
		
		//Kiem tra va nghien cuu them truong hop Upload khong thanh cong (vi du: khong cho luu tru)
		$vImage = "";
		if($_FILES["fileImage"]["error"]==0 && $_FILES["fileImage"]["size"]>0) {
			$path_tmp = $_FILES["fileImage"]["tmp_name"];
			$path_luu = "../uploaded_files/" . $_FILES["fileImage"]["name"];
			$resultUpload = move_uploaded_file($path_tmp, $path_luu);
			if ($resultUpload){
				$vImage = "uploaded_files/" . $_FILES["fileImage"]["name"];
			}
		}

		if($vAct=='add') {
			$vSQL_Ins = "INSERT INTO products(ProductTypeID, ProductName, SKU, Price, Image, Description)
				VALUES($vProductTypeID, '$vProductName', '$vSKU', $vPrice, '$vImage', '$vDescription')";
				
			$vResult_Ins = mysql_query($vSQL_Ins);
			
			if($vResult_Ins) {
				$vMsg = "Bạn đã thêm mới một sản phẩm thành công!";
				header("Location: ?mod=$vModule&msgstatus=$vMsg");
			}
		} 
		elseif($vAct=='edit' && !empty($vID)) 
		{
			$vSQL_Upd = "UPDATE products 
				SET ProductTypeID = $vProductTypeID, 
					ProductName = '$vProductName',
					SKU = '$vSKU',
					Price = $vPrice,
					Image = '$vImage',
					Description = '$vDescription'
				WHERE ID=$vID
			";			

			$vResult_Upd = mysql_query($vSQL_Upd);
			
			if($vResult_Upd) {
				$vMsg = "Bạn đã chỉnh sửa thông tin sản phẩm thành công!";
				header("Location: ?mod=$vModule&msgstatus=$vMsg");
			}
		}
	}
}

$eActive = 0;
if($vAct=='edit') { 
	$vSQL_Edit = "SELECT * FROM products WHERE ID=$vID";
	$vResult_Edit = mysql_query($vSQL_Edit);
	$vRow_Edit = mysql_fetch_array($vResult_Edit);
	
	$eProductTypeID = $vRow_Edit["ProductTypeID"];
	$eProductName	= $vRow_Edit["ProductName"];
	$eSKU		 	= $vRow_Edit["SKU"];
	$ePrice		 	= $vRow_Edit["Price"];
	$eImage 		= $vRow_Edit["Image"];
	$eDescription	= $vRow_Edit["Description"];
}



?>

<h3>Quản lý sản phẩm</h3>

<?php if(!empty($vMsgStatus)) { ?>
<div class="status_message"><?php echo $vMsgStatus; ?></div>
<?php } ?>

<?php if($vAct=='add' || $vAct=='edit') { ?>

<fieldset>
<legend>Thêm mới và điều chỉnh thông tin người dùng</legend>

<form method="post" action="" id="mproducts_Form" enctype="multipart/form-data" >
<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td>Loại sản phẩm:</td>
		<td>
			<select name="cboProductType">
				<?php 
				while($rowPL = mysql_fetch_array($vResult_PL)) { 
					$selectedPL = "";
					if($rowPL["ID"]==$eProductTypeID) {
						$selectedPL = 'selected="selected"';
					}
					echo '<option value="'.$rowPL["ID"].'" '.$selectedPL.'>'.$rowPL["ProductTypeName"].'</option>'."\n";
				} 
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Tên sản phẩm:</td>
		<td><input type="text" name="txtProductName" id="txtProductName" style="width:250px;" value="<?php echo isset($eProductName) ? $eProductName : ''; ?>" /></td>
	</tr>
	<tr>
		<td>SKU:</td>
		<td><input type="text" name="txtSKU" id="txtSKU" style="width:250px;" value="<?php echo isset($eSKU) ? $eSKU : ''; ?>" /></td>
	</tr>
	<tr>
		<td>Giá:</td>
		<td><input type="text" name="txtPrice" id="txtPrice" style="width:250px;" value="<?php echo isset($ePrice) ? $ePrice : ''; ?>" /></td>
	</tr>
	<tr>
		<td>Hình ảnh:</td>
		<td><input type="file" name="fileImage" id="txtImage" style="width:250px;" />
		<?php 
		if(isset($eImage) && !empty($eImage)) {
			echo '<div><img src="../'.$eImage.'" width="200" border="0" alt="" /></div>';
		}
		?>
		</td>
	</tr>
	<tr>
		<td>Mô tả:</td>
		<td><textarea name="txtDescription" id="txtDescription" rows="10" cols="65"><?php echo isset($eDescription) ? $eDescription : ''; ?></textarea></td>
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
<form method="post" action="" name="frmSearch">
<p>
<label>Search</label><input type="text" name="txtSearchText" value="" />
<input type="submit" name="btnSearch" value="Tìm Kiếm" /></p>
</form>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-result">
  <tr>
    <th class="col-first" width="30">STT</th>
	<th>Loại sản phẩm</th>
    <th>Tên sản phẩm</th>
    <th>SKU</th>
	<th>Giá</th>
    <th>Hình ảnh</th>
	<th colspan="2">Hoạt động</th>
  </tr>
  
  <?php 
  $stt = 1;
  while($vRowSel = mysql_fetch_array($vResult_Sel)) { ?>
  <tr>
    <td class="col-first" align="center"><?php echo $stt + $banghibatdau; ?></td>
	<td><?php echo $vRowSel["ProductTypeName"]; ?></td>
    <td><?php echo $vRowSel["ProductName"]; ?></td>
    <td><?php echo $vRowSel["SKU"]; ?></td>
	<td><?php echo $vRowSel["Price"]; ?></td>
    <td><?php echo $vRowSel["Image"]; ?></td>
	<td align="center"><a href="?mod=<?php echo $vModule; ?>&act=edit&id=<?php echo $vRowSel["ID"]; ?>">Sửa</a></td>
	<td align="center"><a href="?mod=<?php echo $vModule; ?>&act=del&id=<?php echo $vRowSel["ID"]; ?>" onclick="return confirm('Bạn có muốn thực hiện xóa bản ghi này không?')" >Xóa</a></td>
  </tr>
  <?php 
  	$stt++;
  } ?>
</table>


<div class="page">
<?php 
    
    
    $trangbatdau = 1;
    $trangketthuc = $sohanghienthi;
    
    //$trangbatdau = $tranghienhanh - ceil($sohanghienthi / 2);
    if($tranghienhanh >= ceil($sohanghienthi / 2)) {
        $trangbatdau = $tranghienhanh - ceil($sohanghienthi / 2) + 1;
        $trangketthuc = $tranghienhanh + ceil($sohanghienthi / 2);
        }
        if($tranghienhanh > $tongsotrang - ceil($sohanghienthi / 2)) {
            $trangketthuc = $tongsotrang;
    }
    
    //$trangketthuc = $trangketthuc + ceil($sohanghienthi / 2);
    
    
    
    echo "[<a href=\"?mod=$vModule&page=1\" >Đầu</a>]";
    echo "[<a href=\"?mod=$vModule&page=".($tranghienhanh - 1)."\">Trước</a>]";
    for($i=$trangbatdau; $i<=$trangketthuc; $i++) {
        $phancach = "";
        if($i>$trangbatdau) $phancach = " | ";
        
        if($i==$tranghienhanh) {
            echo "$phancach <b>$i</b> | ";
        }else{
            echo "$phancach <a href=\"?mod=$vModule&page=$i\" > $i </a> | ";
        }
        
    }
    echo "[<a href=\"?mod=$vModule&page=".($tranghienhanh + 1)."\">Sau</a>]";
    echo "[<a href=\"?mod=$vModule&page=$tongsotrang\">Cuối</a>]";
    
    
    echo "- Tổng số $tongsotrang";

?>
</div>







</fieldset>