<?php
function pdf_simple_download($filename, $title, $columns, $rows) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=' . $filename);
    $w = 595; $h = 842; $left = 40; $top = 60; $line = 14; $y = $h - $top;

    $content = "BT /F1 12 Tf {$left} {$y} Td (" . pdf_escape($title) . ") Tj ET\n";
    $y -= 28;
    $colLine = implode(" | ", $columns);
    $content .= "BT /F1 10 Tf {$left} {$y} Td (" . pdf_escape($colLine) . ") Tj ET\n";
    $y -= 14;
    $content .= "BT /F1 10 Tf {$left} {$y} Td (" . pdf_escape(str_repeat('-', min(120, strlen($colLine)))) . ") Tj ET\n";
    foreach ($rows as $r) {
        $y -= 14;
        if ($y < 60) { break; }
        $content .= "BT /F1 10 Tf {$left} {$y} Td (" . pdf_escape(implode(' | ', array_map('strval', $r))) . ") Tj ET\n";
    }

    $pdf = "%PDF-1.4\n";
    $offsets = [];
    $add = function($s) use (&$pdf, &$offsets) {
        $offsets[] = strlen($pdf);
        $pdf .= $s;
    };

    $add("1 0 obj <</Type /Catalog /Pages 2 0 R>> endobj\n");
    $add("2 0 obj <</Type /Pages /Count 1 /Kids [3 0 R]>> endobj\n");
    $add("3 0 obj <</Type /Page /Parent 2 0 R /MediaBox [0 0 $w $h] /Contents 4 0 R /Resources <</Font <</F1 5 0 R>>>>>> endobj\n");
    $stream = "4 0 obj <</Length ".strlen($content).">> stream\n".$content."endstream endobj\n";
    $add($stream);
    $add("5 0 obj <</Type /Font /Subtype /Type1 /BaseFont /Helvetica>> endobj\n");

    $xref_pos = strlen($pdf);
    $pdf .= "xref\n0 6\n0000000000 65535 f \n";
    foreach ($offsets as $off) {
        $pdf .= sprintf("%010d 00000 n \n", $off);
    }
    $pdf .= "trailer <</Size 6 /Root 1 0 R>>\nstartxref\n".$xref_pos."\n%%EOF";
    echo $pdf;
}
function pdf_escape($s){ return str_replace(['\\','(',')',"\r","\n"], ['\\\\','\(','\)',' ',' '], $s); }
?>