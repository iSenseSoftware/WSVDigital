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
            width:8.5in;
            height:11in;
            
        }
        body {
            margin: 0in;
            font-size:8pt;
            font-family: Arial, Sans-Serif;
        }
        div {
            position:absolute;
            text-align:center;
            width:3.5in;
            height:1.55in;
            padding:0.25in;
            margin:0px;
        }
        #label1{
            position:absolute;
            top:0.3in;
            left:0in;
        }
        #label2{
            position:absolute;
            top:0.3in;
            left:4.05in;
        }
        #label3{
            position:absolute;
            top:2.35in;
            left:0in;
        }
        #label4{
            position:absolute;
            top:2.35in;
            left:4.05in;
        }
        #label5{
            position:absolute;
            top:4.4in;
            left:0in;
        }
        #label6{
            position:absolute;
            top:4.4in;
            left:4.05in;
        }
        #label7{
            position:absolute;
            top:6.45in;
            left:0in;
        }
        #label8{
            position:absolute;
            top:6.45in;
            left:4.05in;
        }
        #label9{
            position:absolute;
            top:8.5in;
            left:0in;
        }
        #label10{
            position:absolute;
            top:8.5in;
            left:4.05in;
        }
        .clear {
            clear:both;
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
<div id='label1'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
<div id='label2'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
<div id='label3'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
<div id='label4'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
<div id='label5'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
<div id='label6'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
<div id='label7'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
<div id='label8'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
<div id='label9'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
<div id='label10'>
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
            <td><strong>Inv Ctrl: </strong>{$item['Item']['ItemCode']}</td>
            <td><strong>Quantity: </strong>$quantity {$item['Part']['Uom']['UOMCode']}</td>
        </tr>
    </table>
    <img src="img/qr{$item['Item']['ItemCode']}.png" />
</div>
</body></html>
EOD;
    $pdf = new DOMPDF();
    //$pdf->set_paper(array(0, 0, 153, 198), 'landscape');
    $pdf->load_html($html);
    $pdf->render();
    $pdf->stream("{$item['Part']['ModelName']}_{$item['Item']['ItemCode']}.pdf", array('Attachment' => 0));