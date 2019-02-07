<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'tcpdf.php';
$tcpdf = new TCPDF("Portrait");
$tcpdf->AddPage();
$tcpdf->SetFont("kozminproregular", "", 10);
$html = <<< EOF
<style>
  h1 {
    font-size: 16px;
    color: #333;
  }
  ul {
    list-style-type: none;
  }
  ul li {
    text-decoration: underline;
  }
</style>
<h1>バシャログ。</h1>
<ul>
    <li>スマホサイト制作</li>
    <li>HTML/CSS</li>
    <li>JavaScript</li>
    <li>WordPress</li>
    <li>PHP</li>
    <li>gulp</li>
</ul>
EOF;

$tcpdf->writeHTML($html);
$tcpdf->Output("test.pdf");
?>