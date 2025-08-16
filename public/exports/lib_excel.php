<?php
// exports/lib_excel.php
// Utilidad simple para generar Excel 2003 XML (compatible con Excel) sin dependencias.
function excel_xml_download($filename, $sheetName, $headers, $rows) {
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
    echo ' xmlns:o="urn:schemas-microsoft-com:office:office"';
    echo ' xmlns:x="urn:schemas-microsoft-com:office:excel"';
    echo ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"';
    echo ' xmlns:html="http://www.w3.org/TR/REC-html40">';
    echo '<Worksheet ss:Name="'.htmlspecialchars($sheetName, ENT_QUOTES, 'UTF-8').'"><Table>';
    echo '<Row>';
    foreach ($headers as $h) {
        echo '<Cell><Data ss:Type="String">'.htmlspecialchars($h, ENT_QUOTES, 'UTF-8').'</Data></Cell>';
    }
    echo '</Row>';
    foreach ($rows as $row) {
        echo '<Row>';
        foreach ($row as $cell) {
            if (is_numeric($cell)) {
                echo '<Cell><Data ss:Type="Number">'.$cell.'</Data></Cell>';
            } else {
                echo '<Cell><Data ss:Type="String">'.htmlspecialchars((string)$cell, ENT_QUOTES, 'UTF-8').'</Data></Cell>';
            }
        }
        echo '</Row>';
    }
    echo '</Table></Worksheet></Workbook>';
}
?>