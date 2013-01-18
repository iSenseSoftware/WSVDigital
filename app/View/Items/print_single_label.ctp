    <?

    error_reporting(E_ALL & ~E_NOTICE);
    require_once("dompdf/dompdf_config.inc.php");
    $lotBatch;
    if ($item['Item']['TransLineItemUD2'] != "") {
        if ($item['Item']['TransLineItemUD3'] != "") {
            $lotBatch = $item['Item']['TransLineItemUD2'] . ' | ' . $item['Item']['TransLineItemUD3'];
        } else {
            $lotBatch = $item['Item']['TransLineItemUD2'];
        }
    } else {
        if ($item['Item']['TransLineItemUD3'] != "") {
            $lotBatch = $item['Item']['TransLineItemUD3'];
        } else {
            $lotBatch = "N/A";
        }
    }
    $expDate = (strtotime($item['Item']['TransLineItemUD6']) != 0) ? date('d M Y', strtotime($item['Item']['TransLineItemUD6'])) : 'N/A';
    $rev = ($item['Item']['TransLineItemUD1'] != "") ? $item['Item']['TransLineItemUD1'] : 'N/A';
    $html = <<<EOD
<html>
<head>
<title></title>
        <style>
        @page {
            margin: 0in 0in 0in 0in;
            width:2.75in;
            height:2.125in;
        }
        body {
            margin: 0in;
            padding: 0in;
            padding-left:0.125in;
            padding-right:0.125in;
            padding-top:0.1in;
            font-size:8pt;
            font-family: Arial, Sans-Serif;
        }
        div {
            float:left;
            text-align:center;
            /*width:2.75in;*/
            
            /*height:2.125in;*/
            margin:0in;
        }

        div#title {
            text-align:center;
            width:100%;
            padding:0;
            margin:0;
        }
        img{
            height:75px;
            width:75px;
        }
        table{
            margin-left:auto;
            margin-right:auto;
            width:100%;
            text-align:center;
        }
    </style>
</head>
<body>
<div id='title'>
<strong>P/N: {$item['Part']['ModelCode']} Rev $rev</strong>
    <br/>
    {$item['Part']['ModelName']}
    <br/>
    <table>
        <tr>
            <td><strong>Lot: </strong>{$item['Item']['TransLineItemUD2']}</td>
            <td><strong>Batch: </strong>{$item['Item']['TransLineItemUD3']}</td>
        </tr>
        <tr>
            <td><strong>Expires: </strong>$expDate</td>
            <td><strong>PO: </strong>{$item['Item']['TransLineItemUD4']}</td>
        </tr>
        <tr>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
        </tr>
    </table>

    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
</body></html>
EOD;
    $pdf = new DOMPDF();
    $pdf->set_paper(array(0, 0, 153, 198), 'landscape');
    $pdf->load_html($html);
    $pdf->render();
    $pdf->stream("{$item['Part']['ModelName']}_{$item['Item']['ItemCode']}.pdf", array('Attachment' => 0));