<?php
$master = "edit_users.php";
include ("blocks/default.php");
include ("blocks/lock.php");
include ("blocks/db_connect.php"); /*Подключаемся к базе*/
if (isset ($_GET['id'])) {$id = $_GET['id'];}
$user = $_SERVER['PHP_AUTH_USER'];
$info = '';
$get_user_language = FALSE;
$get_user_language = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT language FROM userlist WHERE user='$user';");
if (!$get_user_language) {
	if (($err = mysqli_errno($GLOBALS["___mysqli_ston"])) == 1054) {
		$info = "<p align=\"center\" class=\"table_error\">Your version of Pure-FTPd WebUI users table is not currently supported by current version, please upgrade your database to use miltilanguage support.</p>";
	}
	$language = "english";
	include("lang/english.php");
}
else {
	$language_row = mysqli_fetch_array($get_user_language);
	$language = $language_row['language'];
	if ($language == '') {
		$language = "english";
	}
	include("lang/$language.php");
}

echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"");
echo("\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
echo("<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en-US\" xml:lang=\"en-US\">");
echo("<head>");
echo("<title>$um_title</title>");
echo("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />");
?>
<link rel='shortcut icon' href='img/favicon.ico' />
<link href="media/css/stile.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_page.css" rel="StyleSheet" type="text/css">
<link href="media/css/demo_table_jui.css" rel="StyleSheet" type="text/css">
<link href="media/css/jquery-ui-1.7.2.custom.css" rel="StyleSheet" type="text/css">
<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
<?php echo("
<script type=\"text/javascript\" charset=\"utf-8\">
            $(document).ready(function() {
                $('#users_table').dataTable( {
                    \"oLanguage\": {
                        \"sUrl\": \"media/dataTables.$language.txt\"
                    },
					\"bJQueryUI\": true,
					\"sPaginationType\": \"full_numbers\"
                } );
            } );
        </script> ");?>
</head>
<body id="dt_example" class="ex_highlight_row">
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="main_border">
  <tbody>
<?php include("blocks/header.php"); ?>
  <tr>
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
               <td valign="top">
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <?php include("blocks/menu.php"); ?>
    </tr>
		</table></br><?php echo("$info"); ?></br>
				<?php
					// Эта часть используется, если была нажата кнопка "Добавить пользователя"
					if($_POST['add_user']) {
						echo"
							<FORM name='add' method='post' action='" . $_SERVER['PHP_SELF'] . "'>
								<p>
									<label>$um_userform_login</br>
									<INPUT type='text' name='User' id='User'>
									</label>
								</p>
								<p>
									<label>$um_userform_status</br>
									<select name='status'>
									<option value='0'>inactive</option>
									<option value='1'>active</option>
									</select>
									</label>
								</p>
								<p>
									<label>$um_userform_pwd</br>
									<INPUT type='password' name='Password' id='Password'>
									</label>
								</p>
								<p>
									<label>$um_userform_uid</br>
									<INPUT type='text' name='Uid' id='Uid' value='$ftp_uid'>
									</label>
								</p>
								<p>
									<label>$um_userform_gid</br>
									<INPUT type='text' name='Gid' id='Gid' value='$ftp_gid'>
									</label>
								</p>
								<p>
									<label>$um_userform_folder</br>
									<INPUT type='text' name='Dir' id='Dir' value='$ftp_dir'>
									</label>
								</p>
								<p>
									<label>$um_userform_ullimit</br>
									<INPUT type='text' name='ULBandwidth' id='ULBandwidth' value='$upload_speed'>
									</label>
								</p>
								<p>
									<label>$um_userform_dllimit</br>
									<INPUT type='text' name='DLBandwidth' id='DLBandwidth' value='$download_speed'>
									</label>
								</p>
								<p>
									<label>$um_userform_permip</br>
									<INPUT type='text' name='ipaccess' id='ipaccess' value='$permitted_ip'>
									</label>
								</p>
								<p>
									<label>$um_userform_quotasize</br>
									<INPUT type='text' name='QuotaSize' id='QuotaSize' value='$quota_size'>
									</label>
								</p>
								<p>
									<label>$um_userform_quotafiles</br>
									<INPUT type='text' name='QuotaFiles' id='QuotaFiles' value='$quota_files'>
									</label>
								</p></br>
								<p>
									<label>
									<INPUT type='submit' name='add' id='add' value='$um_add_addbutton'>
									</label>
								</p>
								<p>
									<label>
									<INPUT type='submit' name='users' id='users' value='$um_add_backbutton'>
									</label>
								</p>
							</FORM>";
					}

					// Эта часть используется, если была нажата кнопка "Добавить"
					elseif($_POST['add']) {
						echo "</br></br></br>";

						// Проверяем были ли заполнены поля, если поля не были заполнены - выставляем переменную равной пустому полю
						if (isset ($_POST['User'])) {$User = $_POST['User']; if ($User == '') {unset ($User);}}
						if (isset ($_POST['status'])) {$status = $_POST['status']; if ($status == '') {unset ($status);}}
						if (isset ($_POST['Password'])) {$Password = $_POST['Password']; if ($Password == '') {unset ($Password);}}
						if (isset ($_POST['Dir'])) {$Dir = $_POST['Dir']; if ($Dir == '') {unset ($Dir);}}
						if (isset ($_POST['Uid'])) {$Uid = $_POST['Uid']; if ($Uid == '') {unset ($Uid);}}
						if (isset ($_POST['Gid'])) {$Gid = $_POST['Gid']; if ($Gid == '') {unset ($Gid);}}
						if (isset ($_POST['ULBandwidth'])) {$ULBandwidth = $_POST['ULBandwidth']; if ($ULBandwidth == '') {unset ($ULBandwidth);}}
						if (isset ($_POST['DLBandwidth'])) {$DLBandwidth = $_POST['DLBandwidth']; if ($DLBandwidth == '') {unset ($DLBandwidth);}}
						if (isset ($_POST['ipaccess'])) {$ipaccess = $_POST['ipaccess']; if ($ipaccess == '') {unset ($ipaccess);}}
						if (isset ($_POST['QuotaSize'])) {
							$quotasize = $_POST['QuotaSize']; if ($QuotaSize == '') {
								unset ($QuotaSize);
							}
						}
						if (isset ($_POST['QuotaFiles'])) {
							$QuotaFiles = $_POST['QuotaFiles']; if ($QuotaFiles == '') {
								unset ($QuotaFiles);
							}
						}

						// Uid management
						if ($Uid == '') {
							$Uid = $ftp_uid;}

						// Gid management
						if ($Gid == '') {
							$Gid = $ftp_gid;}

						// Если папка не была задана - выставляем значение по умолчанию
						if ($Dir == '') {
							$Dir = $ftp_dir;}

						// Если ограничение скорости аплода не было задано - выставляем значение по умолчанию
						if ($ULBandwidth == '') {
							$ULBandwidth = $upload_speed;}

						// Если ограничение скорости даунлода не было задано - выставляем значение по умолчанию
						if ($DLBandwidth == '') {
							$DLBandwidth = $download_speed;}

						// Если разрешённый IP-адрес не был задан - выставляем значение по умолчанию
						if ($ipaccess == '') {
							$ipaccess = $permitted_ip;}
						
						// Если размер квоты не был задан - выставляем значение по умолчанию
						if ($QuotaSize == '') {
							$QuotaSize = $quota_size;
						}

						// Если размер квоты не был задан - выставляем значение по умолчанию
						if ($QuotaFiles == '') {
							$QuotaFiles = $quota_files;
						}

						// Если все нужные поля заполнены, добавляем пользователя в базу pureftpd
						if (isset ($User) && isset($status) && isset($Password) && isset ($Uid) && isset ($Gid) && isset ($Dir) && isset ($DLBandwidth) && isset ($ULBandwidth) && isset ($ipaccess) && isset ($QuotaSize) && isset ($QuotaFiles)) {
							$result = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO ftpd (User,status,Password,Uid,Gid,Dir,ULBandwidth,DLBandwidth,ipaccess,QuotaSize,QuotaFiles) VALUES ('$User','$status',md5('$Password'),'$Uid','$Gid','$Dir','$ULBandwidth','$DLBandwidth','$ipaccess','$QuotaSize','$QuotaFiles')");
							if ($result == 'true') {echo "<p><strong>$um_add_presultok</strong></p>";}
							else {echo "<p><strong>$um_add_presulterror</strong></p>";}
						}

						else {echo "<p><strong>$um_add_checkfields</strong></p>";}

						echo "</br>
							<form name='to_list' method='post' action='" . $_SERVER['PHP_SELF'] . "'>
								<p>
									<label>
									<input type='submit' name='users' id='users' value='$um_add_checkfieldsback'>
									</label>
								</p>
							</form>";
					}

					// Эта часть используется, если была нажата кнопка "Сохранить изменения"
					elseif($_POST['edit']) {
						echo "</br></br></br>";

						// Проверяем были ли заполнены поля, если поля не были заполнены - выставляем переменную равной пустому полю
						if (isset ($_POST['User'])) {$User = $_POST['User']; if ($User == '') {unset ($User);}}
						if (isset ($_POST['status'])) {$status = $_POST['status']; if ($status == '') {unset ($status);}}
						if (isset ($_POST['Password'])) {$Password = $_POST['Password']; if ($Password == '') {unset ($Password);}}
						if (isset ($_POST['Dir'])) {$Dir = $_POST['Dir']; if ($Dir == '') {unset ($Dir);}}
						if (isset ($_POST['ULBandwidth'])) {$ULBandwidth = $_POST['ULBandwidth']; if ($ULBandwidth == '') {unset ($ULBandwidth);}}
						if (isset ($_POST['DLBandwidth'])) {$DLBandwidth = $_POST['DLBandwidth']; if ($DLBandwidth == '') {unset ($DLBandwidth);}}
						if (isset ($_POST['ipaccess'])) {$ipaccess = $_POST['ipaccess']; if ($ipaccess == '') {unset ($ipaccess);}}
						if (isset ($_POST['QuotaSize'])) {
							$QuotaSize = $_POST['QuotaSize']; if ($QuotaSize == '') {
								unset ($QuotaSize);
							}
						}
						if (isset ($_POST['QuotaFiles'])) {
							$QuotaFiles = $_POST['QuotaFiles']; if ($QuotaFiles == '') {
								unset ($QuotaFiles);
							}
						}
						if (isset ($_POST['id'])) {$id = $_POST['id']; if ($id == '') {unset ($id);}}

						// Запрашиваем из БД настройки пользователя
						$result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ftpd WHERE id=$id");
						$array = mysqli_fetch_array($result);

						// Проверяем были ли внесены какие-то изменения
						if (($Uid != $array[Uid]) || ($Gid != $array[Gid]) || ($Dir != $array[Dir]) || ($User != $array[User]) || ($status != $array[status]) || (isset ($Password)) || ($ULBandwidth != $array[ULBandwidth]) || ($DLBandwidth != $array[DLBandwidth]) || ($ipaccess != $array[ipaccess]) || ($QuotaSize != $array[QuotaSize]) || ($QuotaFfiles != $array[QuotaFiles])) {

							// Uid
							if (($Uid != $array[Uid]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET Uid='$Uid' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_uidok</strong></p>";}
								else {echo "<p><strong>$um_edit_uiderror</strong></p>";}
							}

							// Gid
							if (($Gid != $array[Gid]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET Gid='$Gid' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_gidok</strong></p>";}
								else {echo "<p><strong>$um_edit_giderror</strong></p>";}
							}

							// Если изменена папка пользователя, вносим изменения в базу
							if (($Dir != $array[Dir]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET Dir='$Dir' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_folderok</strong></p>";}
								else {echo "<p><strong>$um_edit_foldererror</strong></p>";}
							}

							// Если изменено имя пользователя, вносим изменения в базу
							if (($User != $array[User]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET User='$User' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_loginok</strong></p>";}
								else {echo "<p><strong>$um_edit_loginerror</strong></p>";}
							}

							// Если изменён статус пользователя, вносим изменения в базу
							if (($status != $array[status]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET status='$status' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_statusok</strong></p>";}
								else {echo "<p><strong>$um_edit_statuserror</strong></p>";}
							}

							// Если изменён пароль пользователя, вносим изменения в базу
							if (isset ($Password)) {$Password = md5($Password);
								if (($Password != $array[Password]) && isset ($id)) {
									$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET Password='$Password' WHERE id='$id'");
									if ($result == 'true') {echo "<p><strong>$um_edit_passwdok</strong></p>";}
									else {echo "<p><strong>$um_edit_passwderror</strong></p>";}}
							}

							// Если изменено ограничение скорости загрузки, вносим изменения в базу
							if (($ULBandwidth != $array[ULBandwidth]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET ULBandwidth='$ULBandwidth' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_ullimitok</strong></p>";}
								else {echo "<p><strong>$um_edit_ullimiterror</strong></p>";}
							}

							// Если изменено ограничение скорости скачивания, вносим изменения в базу
							if (($DLBandwidth != $array[DLBandwidth]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET DLBandwidth='$DLBandwidth' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_dllimitok</strong></p>";}
								else {echo "<p><strong>$um_edit_dllimiterror</strong></p>";}
							}
							// Если изменён разрешенный IP адрес, вносим изменения в базу
							if (($ipaccess != $array[ipaccess]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET ipaccess='$ipaccess' WHERE id='$id'");
								if ($result == 'true') {echo "<p><strong>$um_edit_permipok</strong></p>";}
								else {echo "<p><strong>$um_edit_permiperror</strong></p>";}
							}
							// Если изменён размер квоты, вносим изменения в базу
							if (($QuotaSize != $array[QuotaSize]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET QuotaSize='$QuotaSize' WHERE id='$id'");
								if ($result == 'true') {
									echo "<p><strong>$um_edit_quotasizeok</strong></p>";
							}
							else {echo "<p><strong>$um_edit_quotasizeerror</strong></p>";}
							}
							// Если изменён размер квоты, вносим изменения в базу
							if (($QuotaFiles != $array[QuotaFiles]) && isset ($id)) {
								$result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ftpd SET QuotaFiles='$QuotaFiles' WHERE id='$id'");
								if ($result == 'true') {
									echo "<p><strong>$um_edit_quotafilesok</strong></p>";
							}
							else {echo "<p><strong>$um_edit_quotafileserror</strong></p>";
							}
							}
						}
						else {echo"<p><strong>$um_edit_nochanges</strong></p>";}

					echo"</br>
							<form name='to_list' method='post' action='" . $_SERVER['PHP_SELF'] . "'>
								<p>
									<label>
									<input type='submit' name='users' id='users' value='$um_edit_nochangesback'>
									</label>
								</p>
							</form>";
					}
					else {
						if ((!isset ($id)) || (isset ($_POST['users']))) {
						// Шапка таблицы
						echo("
							<p class='text_title' align='center'><strong>$um_t_title</strong></p><div id='container'>
							<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"display\" id=\"users_table\">
								<thead>
									<tr>
										<th>$um_t_th1</th>
										<th>$um_t_th2</th>
										<th>$um_t_th3</th>
										<th>$um_t_th4</th>
										<th>$um_t_th5</th>
										<th>$um_t_th6</th>
										<th>$um_t_th7</th>
										<th>$um_t_th8</th>
										<th>$um_t_th9</th>
										<th>$um_t_th10</th>
									</tr>
								</thead><tbody>");
						$result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ftpd");
						$myrow = mysqli_fetch_array($result);
						do {
							// Выводим список пользователей
							printf ("<tr>
										<td align='center'></a><a href='edit_users.php?id=%s'>%s</a></td>
										<td align='center'>$myrow[status]</td>
										<td>$myrow[Uid]</td>
										<td>$myrow[Gid]</td>
										<td>$myrow[Dir]</td>
										<td align='center'>$myrow[ULBandwidth]</td>
										<td align='center'>$myrow[DLBandwidth]</td>
										<td align='center'>$myrow[ipaccess]</td>
										<td align='center'>$myrow[QuotaSize]</td>
										<td align='center'>$myrow[QuotaFiles]</td>
									</tr>",$myrow ["id"],$myrow ["User"]);
						}
					while ($myrow = mysqli_fetch_array($result));

						echo("	</tbody></table>");
											

						echo("</br></br><table width='95%' border='0' align='center' cellpadding='0' cellspacing='0'><tr><td align='right'>
								<form name='edit' method='post' action='" . $_SERVER['PHP_SELF'] . "'>
									<p>
										<label>
										<input type='submit' name='add_user' id='add_user' value='$um_adduserbutton'>
										</label>
									</p>
								</form></tr><td></table>");
					}
					// Эта часть используется, если выбран пользователь для редактирования
					else {
						$result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ftpd WHERE id=$id");
						$myrow = mysqli_fetch_array($result);
						if ($myrow[status] == 0) {
							$select = "<option value='0' selected='selected'>inactive</option><option value='1'>active</option>";
						}
						else {
							$select = "<option value='0'>inactive</option><option value='1' selected='selected'>active</option>";
						}
						print("
							<form name=\"form1\" method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">
								<p>
									<label>$um_userform_login</br>
									<INPUT value=\"$myrow[User]\" type=\"text\" name=\"User\" id=\"User\">
									</label>
								</p>
								<p>
									<label>$um_userform_status</br>
									<select name='status'>
									$select
									</select>
									</label>
								</p>
								<p>
									<label>$um_userform_pwd</br>
									<INPUT value=\"\" type=\"password\" name=\"Password\" id=\"Password\">
									</label>
								</p>
								<p>
									<label>$um_userform_uid</br>
									<INPUT value=\"$myrow[Uid]\" type=\"text\" name=\"Uid\" id=\"Uid\">
									</label>
								</p>
								<p>
									<label>$um_userform_gid</br>
									<INPUT value=\"$myrow[Gid]\" type=\"text\" name=\"Gid\" id=\"Gid\">
									</label>
								</p>
								<p>
									<label>$um_userform_folder</br>
									<INPUT value=\"$myrow[Dir]\" type=\"text\" name=\"Dir\" id=\"Dir\">
									</label>
								</p>
								<p>
									<label>$um_userform_ullimit</br>
									<INPUT value=\"$myrow[ULBandwidth]\" type=\"text\" name=\"ULBandwidth\" id=\"ULBandwidth\">
									</label>
								</p>
								<p>
									<label>$um_userform_dllimit</br>
									<INPUT value=\"$myrow[DLBandwidth]\" type=\"text\" name=\"DLBandwidth\" id=\"DLBandwidth\">
									</label>
								</p>
								<p>
									<labe>$um_userform_permip</br>
									<INPUT value=\"$myrow[ipaccess]\" type=\"text\" name=\"ipaccess\" id=\"ipaccess\">
									</label>
								</p>
								<p>
									<labe>$um_userform_quotasize</br>
									<INPUT value=\"$myrow[QuotaSize]\" type=\"text\" name=\"QuotaSize\" id=\"QuotaSize\">
									</label>
								</p>
								<p>
									<labe>$um_userform_quotafiles</br>
									<INPUT value=\"$myrow[QuotaFiles]\" type=\"text\" name=\"QuotaFiles\" id=\"QuotaFiles\">
									</label>
								</p></br>
									<INPUT name=\"id\" type=\"hidden\" value=\"$myrow[id]\">
								<p>
									<label>
									<INPUT type=\"submit\" name=\"edit\" id=\"edit\" value=\"$um_edit_savebutton\">
									</label>
								</p>
								<p>
									<label>
									<INPUT type=\"submit\" name=\"users\" id=\"users\" value=\"$um_edit_backbutton\">
									</label>
								</p>
							</form>
							");
						}
				}
				?>
		</td></tr></table>
	</td></tr>
<?php include("blocks/footer.php"); ?>
</tbody></table>
</body>
</html>
