<?
$item = $item[0][0];
error_reporting(E_ALL & ~E_NOTICE);
require_once("dompdf/dompdf_config.inc.php");
//$expDate = (date('M Y', strtotime($item['Item']['TransLineItemUD6'])) != 0) ? date('M Y', strtotime($item['Item']['TransLineItemUD6'])) : 'N/A';
//$rev = ($item['Item']['TransLineItemUD1'] != "") ? $item['Item']['TransLineItemUD1'] : 'N/A';
$html = <<<EOD
<html>
<head>
<title></title>
        <style>
        @page {
            margin: 0in 0in 0in 0in;
            width:8.5in;
            height:11in;
        }
        body {
            margin: 1in;
            padding: 0in;
            padding-left:0in;
            padding-right:0in;
            padding-top:0in;
            font-size:12pt;
            font-family: Arial, Sans-Serif;
        }
        h1{
            font-size:24pt;
        }
        h2{
            font-size:18pt;
        }
        div#header {
            text-align:center;
            width:100%;
            padding:0;
            margin:0;
        }
        #header1{
            float:left;
            width:50%;
            vertical-align:bottom;
        }
        #header2{
            float:right;
            font-size:10pt;
            width:50%;
            vertical-align:bottom;
        }
        #clearer{
            clear:both;
        }
        div#title {
            text-align:center;
            width:100%;
            padding:0;
            margin:0;
        }
        table{
            margin-left:auto;
            margin-right:auto;
            width:100%;
            text-align:center;
        }
        img{
            height:50px;
            width:225px;
            vertical-align:text-bottom;
        }
    </style>
</head>
<body>
<div id='header'>
<div id='header1'>
<img src='img/bayer-logo.png' alt='logo' />
</div>
<div id='header2'>
<p>
27700 SW 95th Ave Suite 100<br/>
Wilsonville, OR 97070<br/>
(503)783-5050 FAX: (503)783-5092<br/>
</p>
</div>
<div id='clearer' style='clear:both;'></div>
</div>
<br/><br/><br/>
<div id='title'>
    <h1>Certificate of Conformance</h1>
</div>
<div id='content'>
<h2>Material Identification</h2>
<strong>Bayer P/N: </strong>{$item['ModelCode']} <strong>Rev: </strong>{$item['TransLineItemUD1']}<br/>
<strong>Description: </strong>{$item['ModelName']}<br/>
<strong>Inventory Control #: </strong>{$item['ItemCode']}<br/>
<strong>Lot: </strong>{$item['TransLineItemUD2']}<br/>
<strong>Batch: </strong>{$item['TransLineItemUD3']}<br/>
<strong>Qty: </strong>{$data['Item']['Quantity']} {$item['UOMCode']}<br/>
<strong>Furnished per PO: </strong>{$data['Item']['CustomerPO']}<br/>
<strong>Additional Info: </strong>{$data['Item']['Comments']}<br/>
</div>
<br/><br/><br/>
<div id='certAndSig'>
<h3>Certification Statement:</h3>
<p>
The materials furnished for the purchase order above are certified to be in compliance with 
Bayer specification {$item['ModelCode']} Rev {$item['TransLineItemUD1']}.
</p>
<strong>Date: _______________________</strong>
<br/><br/>
<strong>Certified By: _______________________</strong>
</div>
</body></html>
EOD;
$pdf = new DOMPDF();
//$pdf->set_paper(array(0, 0, 153, 198), 'landscape');
$pdf->load_html($html);
$pdf->render();
$pdf->stream("{$item['Part']['ModelName']}_{$item['Item']['ItemCode']}.pdf", array('Attachment' => 0));