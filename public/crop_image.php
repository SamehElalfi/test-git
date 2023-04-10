<?php
$image = $_FILES;
$NewImageName = rand(4,10000)."-". basename($_FILES['photo']["name"]);
ini_set("memory_limit","256M");
$destination = realpath('uploads/'.$path_page).'/';
if ($name_tmp == 'images'){
    move_uploaded_file($image[$name_tmp]['tmp_name'][$i], $destination.$NewImageName);
}else{
    move_uploaded_file($image[$name_tmp]['tmp_name'], $destination.$NewImageName);
}
if ($array[count($array) - 1] == 'jpg' or $array[count($array) - 1] == 'jpeg'){
    $image = false;
    $image_data = file_get_contents($destination.$NewImageName);
    try {
//        echo $destination;
//        echo $NewImageName;
        return;
        $image = imagecreatefromstring($destination.$NewImageName);
    } catch (Exception $ex) {
        $image = false;
    }
    $image = imagecreatefromjpeg($destination.$NewImageName);


}elseif ($array[count($array) - 1] == 'png') {
    try {
        $image = imagecreatefrompng($destination.$NewImageName);
    } catch (Exception $ex) {
        $image = imagecreatefromjpeg($destination.$NewImageName);
    }
}
$filename = $destination.$NewImageName;
$width = imagesx($image);
$height = imagesy($image);
$original_aspect = $width / $height;
$thumb_aspect = $thumb_width / $thumb_height;
if ( $original_aspect >= $thumb_aspect )
{
    $new_height = $thumb_height;
    $new_width = $width / ($height / $thumb_height);
}
else
{
    $new_width = $thumb_width;
    $new_height = $height / ($width / $thumb_width);
}
$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
imagecopyresampled($thumb,
    $image,
    0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
    0 - ($new_height - $thumb_height) / 2, // Center the image vertically
    0, 0,
    $new_width, $new_height,
    $width, $height);
imagejpeg($thumb, $filename, 80);
?>

