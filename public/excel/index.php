<?php

function get_excel(){
    require_once "Classes\PHPExcel.php";

    $path = "sheet.xlsx";
    $reader = PHPExcel_IOFactory::createReaderForFile($path);
    $excel_obj = $reader->load($path);
    $worksheet = $excel_obj->getSheet('0');

//echo $worksheet->getHighestRow(); // get count of rows
//echo $worksheet->getHighestColumn(); // get count of columns
//echo $worksheet->getCell('F3')->getValue(); // get value of ceil

    $rows = $worksheet->getHighestRow();
    for ($i=2;$i<=$rows;$i++){
        $current = $i-1;
        echo "<h1>Row ($current)</h1><br>";
        echo $worksheet->getCell("A$i")->getValue() . "<br>";
        echo $worksheet->getCell("B$i")->getValue() . "<br>";
        echo $worksheet->getCell("C$i")->getValue() . "<br>";
        echo $worksheet->getCell("D$i")->getValue() . "<br>";
        echo $worksheet->getCell("E$i")->getValue() . "<br>";
        echo $worksheet->getCell("F$i")->getValue() . "<br>";
    }
}
?>
