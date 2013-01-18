<?
if (isset($data)) {
    error_reporting(E_ALL & ~E_NOTICE);
    require_once("dompdf/dompdf_config.inc.php");
    $purchaseOrder = ($data['PackingList']['PurchaseOrder'] == '')?'N/A':$data['PackingList']['PurchaseOrder'];
    $address = $data['PackingList']['Company'];
    $address .= "<br/>{$data['PackingList']['Address1']}";
    if ($data['PackingList']['Address2'] != '') {
        $address .= "<br/>{$data['PackingList']['Address2']}";
    }
    $address .= "<br/>{$data['PackingList']['City']}, {$data['PackingList']['State']} {$data['PackingList']['ZipCode']}";
    $shipDate = date('d M, Y');
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
        td{
            margin-left:auto;
            margin-right:auto;
            text-align:center;
            font-size:8pt;
        }
        th{
            text-decoration:underline;
        }
        .partNumber{
            white-space:nowrap;
        }
        .colored{
            background-color:AliceBlue;
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
    <h1>Packing List</h1>
</div>
<div id='content'>
<div id='shipToAddress'>
<strong>Ship To:</strong><br/>
$address<br/><br/>
<strong>Purchase Order: </strong>$purchaseOrder<br/>
<strong>Shipped By: </strong>{$data['PackingList']['ShippingMethod']}<br/>
<br/>
<strong>Packaged Items: </strong><br/><br/>    
</div>
<table>
    <tr>
        <th>P/N</th>
        <th>Description</th>
        <th>Batch</th>
        <th>Qty</th>
        <th>UOM</th>
        <th>Comments</th>
    </tr>
EOD;
    $i = 1;
    foreach ($data['PackingList']['PartNumber'] as $key => $lineItem) {
        if (($i % 2) == 0) {
            $html .= "<tr class='colored'><td class='partNumber'>$lineItem</td><td>{$data['PackingList']['Name'][$key]}</td>";
            $html .= "<td>{$data['PackingList']['Batch'][$key]}</td><td>{$data['PackingList']['Qty'][$key]}</td>";
            $html .= "<td>{$data['PackingList']['Uom'][$key]}</td><td>{$data['PackingList']['Notes'][$key]}</td></tr>";
        } else {
            $html .= "<tr><td class='partNumber'>$lineItem</td><td>{$data['PackingList']['Name'][$key]}</td>";
            $html .= "<td>{$data['PackingList']['Batch'][$key]}</td><td>{$data['PackingList']['Qty'][$key]}</td>";
            $html .= "<td>{$data['PackingList']['Uom'][$key]}</td><td>{$data['PackingList']['Notes'][$key]}</td></tr>";
        }
        $i++;
    }
    $html .= "
</table>
</div>
<br/><br/><br/>
<div id='sig'>
<strong>Ship Date: </strong>$shipDate
<br/><br/>
<strong>Shipped By: _______________________</strong>
</div>
</body></html>";
    $pdf = new DOMPDF();
//$pdf->set_paper(array(0, 0, 153, 198), 'landscape');
    $pdf->load_html($html);
    $pdf->render();
    $pdf->stream("Packinglist.pdf", array('Attachment' => 0));
} else {
    ?>
    <!-- Here is where the packing list creation form is rendered-->
    <script>
        $(document).ready(function(){
            // populate parts select element.  Ajax GET request to Inventories/ajaxCurrentStock
            var inventoryId;
            var partId;
            $('#PackingListPackingListForm').validate();
            // Bind the addItem function to the 'Add' button click event
            $('#addItem').click(function(){
                addItem()
            });
            $('#PackingListPackingListForm').submit(function(event){
                event.preventDefault();
                var count = $('#packingListItems tbody tr').length;
                if(count < 1){
                    alert('No items entered for packing list');
                }else{
                    if($('#PackingListPackingListForm').valid()){
                        this.submit();
                    }
                }
            })
            $.ajax({
                type:"GET",
                url:"ajaxCurrentStock/1",
                dataType:"xml",
                success:populateParts
            });
        });
        function populateParts(xml){
            $(xml).find("part").each(function(){
                // add an option element with value = part_id
                // and display = PN +  Name
                $('#part_id').append('<option>' + $(this).find('number').text() + '</option>');
                $('#part_id option').last().attr('value', $(this).find('id').text());
                $('#part_id option').last().attr('uom', $(this).find('uom').text());
            });
            partId = $('#part_id option:selected').attr('value');
            var uom = $('#part_id option:selected').attr('uom');
            $('#lineItemUom').text(uom);
            //$('#lineItemUom').append(uom);
            $.ajax({
                type: "GET",
                url: "ajaxInvCtrls/" + partId,
                dataType: "xml",
                success: populateInvCtrls
            });
            $('#part_id option').click(function(){
                partId = $('#part_id option:selected').attr('value');
                var uom = $('#part_id option:selected').attr('uom');
                $('#lineItemUom').text(uom);
                //$('#lineItemUom').append(uom);
                $.ajax({
                    type: "GET",
                    url: "ajaxInvCtrls/" + partId,
                    dataType: "xml",
                    success: populateInvCtrls
                });
            });
                            
        }

        function populateInvCtrls(xml){
            $('#itemCode').children().remove();
            $(xml).find("Item").each(function(){
                // add an option element with value = part_id
                // and display = PN +  Name
                $('#itemCode').append('<option>' + $(this).find('invCtrl').text()+ '</option>');
                $('#itemCode option').last().attr('value', $(this).find('id').text());
                $('#itemCode option').last().attr('batch', $(this).find('batch').text());
                $('#itemCode option').last().attr('rev', $(this).find('rev').text());
                $('#itemCode option').last().attr('value', $(this).find('id').text());
                $('#itemCode option').last().attr('name', $(this).find('name').text());
            });

        }           

        function addItem(){
            var itemId = $('#itemCode option:selected').val();
            var partNumber = $('#part_id option:selected').text();
            var batch = $('#itemCode option:selected').attr('batch');
            var rev = $('#itemCode option:selected').attr('rev');
            var name = $('#itemCode option:selected').attr('name');
            var uom = $('#lineItemUom').text();
            var qty = $('#lineItemQuantity').val();
            var notes = $('#lineItemNotes').val();
            if(!isNumber(qty) || qty <= 0){
                alert('You must enter a positive number for quantity');
            }else{
                $("#packingListItems tbody").append("<tr><td>" + partNumber + "<input type='hidden' value='" +
                    partNumber +"' name='data[PackingList][PartNumber][]'></td><td>" + rev + "<input type='hidden' value='" +
                    rev + "' name='data[PackingList][Rev][]'></td><td>" + name + "<input type='hidden' value='" +
                    name + "' name='data[PackingList][Name][]'></td><td>" + batch + "<input type='hidden' value='" +
                    batch + "' name='data[PackingList][Batch][]'></td><td>" + qty + "<input type='hidden' value='" +
                    qty + "' name='data[PackingList][Qty][]'></td><td>" + uom + "<input type='hidden' value='" +
                    uom + "' name='data[PackingList][Uom][]'></td><td>" + notes + "<input type='hidden' value='" +
                    notes + "' name='data[PackingList][Notes][]'></td><td><button type='button'>Remove</button></td></tr>");

                $('#packingListItems button').each(function(){
                    $(this).click(function(){
                        $(this).parent().parent().remove();
                    });
                });
                            
                                    
                         
            }
        }
    </script>
    <h2>Create Packing List</h2>
    <?
    echo $this->Form->create('PackingList');
    echo $this->Form->input('PurchaseOrder');
    echo $this->Form->input('ShippingMethod', array('class' => 'required'));
    ?>
    <h1>Ship To:</h1>
    <?
    echo $this->Form->input('Company', array('class' => 'required'));
    echo $this->Form->input('Address1', array('label' => 'Address Line 1', 'class' => 'required'));
    echo $this->Form->input('Address2', array('label' => 'Address Line 2'));
    echo $this->Form->input('City', array('class' => 'required'));
    echo $this->Form->input('State', array('class' => 'required', 'empty' => true));
    echo $this->Form->input('ZipCode', array('class' => 'required'));
    ?>
    <h1>Line Items:</h1>
    <table id="packingListItems">
        <thead>
        <th>P/N</th>
        <th>Rev</th>
        <th>Description</th>
        <th>Batch</th>
        <th>Qty</th>
        <th>UOM</th>
        <th>Notes</th>
    </thead>
    <tbody>
    </tbody>
    </table>
    <br/><br/><br/>
    <h1>Add Line Item:</h1>
    <div style="border:1px solid black;">
        <table id="lineItem">
            <thead>
            <th>P/N</th>
            <th>Inv Ctrl</th>
            <th>Qty</th>
            <th>UOM</th>
            <th>Notes</th>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select id="part_id"></select>
                    </td>
                    <td>
                        <select id="itemCode"></select>
                    </td>
                    <td>
                        <input id="lineItemQuantity">
                    </td>
                    <td id="lineItemUom">

                    </td>
                    <td>
                        <input id="lineItemNotes">
                    </td>    
                </tr>
            </tbody>
        </table>
        <button id="addItem" type="button">Add Item</button>
    </div>
    <?
    echo $this->Form->end('Submit', array('id'=>'submit'));
}
?>