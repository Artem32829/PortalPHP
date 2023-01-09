<?php
include 'menu.php';
echo ' <h6>Обмен файлами</h6>';

//Предисловие
echo '<details><summary>О сервисе</summary><i>Данный сервис разработан для быстрого обмена файлами между пользователями (ограничение по размеру файлов - 200Mb).<br>
!!! Не храните здесь файлы, они будут удаляться автоматически через несколько дней.<br></i></details>';

if (isset($_POST['load_file'])) {
    // Configure upload directory and allowed file types
    $upload_dir = 'cloud' . DIRECTORY_SEPARATOR;
    $allowed_types = array('jpg', 'png', 'jpeg', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'rar', 'zip', 'exe', 'ppt', 'pptx', 'mp4', 'avi', 'rdp');

    // Define maxsize for files i.e 50MB
    $maxsize = 200 * 1024 * 1024;

    // Checks if user sent an empty form
    if (!empty(array_filter($_FILES['files']['name']))) {

        // Loop through each file in files[] array
        foreach ($_FILES['files']['tmp_name'] as $key => $value) {

            $file_tmpname = $_FILES['files']['tmp_name'][$key];
            $file_name = $_FILES['files']['name'][$key];
            $file_size = $_FILES['files']['size'][$key];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

            // Set upload file path
            $filepath = $upload_dir . $file_name;

            // Check file type is allowed or not
            if (in_array(strtolower($file_ext), $allowed_types)) {

                // Verify file size - 2MB max
                if ($file_size > $maxsize) {
                    echo "<p style='color:#6385A1'>Файл слишком большой (ограничение 50Мb)<p>";
                }

                // If file with name already exist then append time in
                // front of name of the file to avoid overwriting of file
                if (file_exists($filepath)) {
                    $filepath = $upload_dir . time() . $file_name;

                    if (move_uploaded_file($file_tmpname, $filepath)) {
                        // echo "{$file_name} successfully uploaded <br />";
                    } else {
                        echo "<p style='color:#6385A1'>Ошибка загрузки <i>{$file_name}</i></p><br />";
                    }
                } else {

                    if (move_uploaded_file($file_tmpname, $filepath)) {
                        // echo "{$file_name} successfully uploaded <br />";
                    } else {
                        echo "<p style='color:#6385A1'>Ошибка загрузки <i>{$file_name}</i><p><br/>";
                    }
                }
            } else {

                // If file extension not valid
                echo "<p style='color:#6385A1'>Ошибка загрузки файлы <i>{$file_name}</i>(файлы <b>.{$file_ext}</b> нельзя загружать)</p><br/>";
            }
        }
    } else {

        // If no files selected
        echo "<i>Выберите сначала файлы</i>";
    }
}

if (isset($_POST['delete_file'])) {
    $files = scandir('cloud');
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            rename($_SERVER['DOCUMENT_ROOT'] . "/cloud/" . $file, $_SERVER['DOCUMENT_ROOT'] . "/cloud_delete/" . $file);
        }

    }

}
//{rename($_SERVER['DOCUMENT_ROOT']."/cloud/".$_POST['delete_file'], $_SERVER['DOCUMENT_ROOT']."/cloud_delete/".$_POST['delete_file']); }

echo '<div class="form-control div-okrugl">';
$files = scandir('cloud');
sort($files);
foreach ($files as $file) {
    if ($file != "." && $file != "..") {$ext = pathinfo($file, PATHINFO_EXTENSION);
        echo '<form method="post">
	        <img src="/img//' . $ext . '.svg" width="32" height="32"><a name=' . $file . ' class= link href="cloud\\' . $file . '" class="product">' . $file . '</a>
	        <br>
	        </form>  ';
    }}
echo '</div><br>';

echo '
	<div class="form-control div-okrugl">
	<form method="post" enctype="multipart/form-data"><br>
    <input type="file" name="files[]" multiple><br><br>
    <input class="btn btn-success" type="submit" value="Загрузить файлы" name="load_file"><br><br>
	<input class="btn btn-danger" type="submit" value="Удалить файлы" name="delete_file"><br>
    </form>
	</div>
	<br><br><i style="color:#6385A1">*Не храните здесь файлы, они будут удаляться автоматически через несколько дней.<br></i>';

include 'footer.php'; //<input type="submit" value="'.$file.'" name="delete_file" src="/img//del.svg" width="20" height="20">
