
<h1>Issue Stock Inventory</h1>
<br/><br/>
<? if (isset($item)) { ?>
    <script>
        $(document).ready(function(){
            $('#ItemIssueForm').validate();
        });
    </script>
    <div id="idSpecified" style="display:none;">yes</div>
    <div id="ItemSummary">
        <strong>Inventory Control #: </strong><? echo $item['Item']['ItemCode']; ?><br/>
        <strong>P/N: </strong><? echo $item['Part']['ModelCode']; ?>
        <strong>Rev </strong><? echo $item['Item']['TransLineItemUD1']; ?><br/>
        <strong>Name: </strong><? echo $item['Part']['ModelName']; ?><br/>
        <strong>Lot: </strong><? echo $item['Item']['TransLineItemUD2']; ?>
        <strong>Batch: </strong><? echo $item['Item']['TransLineItemUD3']; ?><br/>
        <strong>PO: </strong><? echo $item['Item']['TransLineItemUD4']; ?><br/>
        <strong>Location: </strong><? echo $item['Location']['LocationCode']; ?></br>
        <strong>Exp Date: </strong><? echo $item['Item']['TransLineItemUD6']; ?><br/>
        <strong>Quantity in stock: </strong><? echo (float) $item['Item']['Quantity'] . ' ' . $item['Part']['Uom']['UOMCode']; ?><br/>
        <strong>Status: </strong><? echo $item['Location']['LocationType']['LocationTypeName']; ?>
    </div>
    <br/>
    <?
    echo $this->Form->create('Item');
    echo $this->Form->input('ItemID', array(
        'value' => $item['Item']['ItemID'],
        'type' => 'hidden'
    ));
    echo $this->Form->input('Quantity', array('class' => 'required number'));
    echo $this->Form->input('Notes');
    echo $this->Form->end('Submit');
} else {
    ?>
    <script>
        $(document).ready(function(){
            // populate parts select element.  Ajax GET request to Inventories/ajaxCurrentStock
            var inventoryId;
            var partId;
            // Populate the parts select dropdown if the id was not specified
            $.ajax({
                type: "GET",
                url: "ajaxCurrentStock",
                dataType: "xml",
                success: populateParts
            });

            // Bind the addItem function to the 'Add' button click event
            $('#addItem').click(function(){
                addItem()
            });
                          
            $('#InventoryIssueForm').submit(function(event){
                //event.preventDefault()
                if(validateForm()){
                    return true;
                }else{
                    return false;
                }
            })
        });
        function populateParts(xml){
            $(xml).find("part").each(function(){
                // add an option element with value = part_id
                // and display = PN +  Name
                $('#part_id').append('<option>' + $(this).find('number').text() + ': ' + 
                    $(this).find('name').text() + '</option>');
                $('#part_id option').last().attr('value', $(this).find('id').text());
            });
            partId = $('#part_id option:selected').attr('value');
            $.ajax({
                type: "GET",
                url: "ajaxView/" + partId,
                dataType: "xml",
                success: populateItems
            });
            $('#part_id option').click(function(){
                partId = $('#part_id option:selected').attr('value');
                $.ajax({
                    type: "GET",
                    url: "ajaxView/" + partId,
                    dataType: "xml",
                    success: populateItems
                });
            });
                                        
        }

        function populateItems(xml){
            $('#existingInventory tbody').children('tr').remove();
            $(xml).find('Item').each(function(){
                id = $(this).find('id').text();
                quantity = $(this).find('quantity').text();
                uom = $(this).find('uom').text();
                rev = $(this).find('rev').text();
                status = $(this).find('status').text();
                lot = $(this).find('lot').text();
                batch = $(this).find('batch').text();
                invCtrl = $(this).find('invCtrl').text();
                partNumber = $(this).find('partNumber').text();
                locationCode = $(this).find('locationCode').text();
                if(!itemAdded(id)){
                    $('#existingInventory tbody').append("<tr quantity='" + quantity + "' itemId='" + id + "'><td>" + invCtrl + "</td><td>" + partNumber + "</td><td>" + rev + "</td><td>" +
                        lot + "</td><td>" + batch + "</td><td>" + locationCode + "</td><td>" + parseFloat(quantity).toString() + " " + uom + "</td><td>" +
                        "<button type='button' class='addRow'>Add</button></td><input type='hidden' name='totalQty' value='" + quantity + 
                        "'><input type='hidden' name='itemId' value='" + id +"'></tr>");
                }
                })
            $('.addRow').click(function(){
                    id = $(this).parent().parent().find("[name='itemId']").val()
                    $(this).parent().parent().find("[name='itemId']").attr('name','data[Item][ItemID][]')
                    added = $(this).parent().parent().detach();
                    added.children('td:last').remove();
                    added.append("<td><input id='quantity" + id +"' name='data[Item][Quantity][]' class='required number'></td><td><input name=data[Item][Notes][]></td>");
                    added.append("<td><button type='button' class='removeRow'>Remove</button></td>");
                    $('#issueItemsTable tbody').append(added);
                    $('.removeRow').each(function(){
                        $(this).click(function(){
                            $(this).parent().parent().remove();
                            partId = $('#part_id option:selected').attr('value');
                            $.ajax({
                                type: "GET",
                                url: "ajaxView/" + partId,
                                dataType: "xml",
                                success: populateItems
                            });
                    })
                })
            })
        }
                    
        function itemAdded(id){
            wasAdded = false
            $('#issueItemsTable tr').each(function(){ 
                if($(this).find("[name='data[Item][ItemID][]']").val() == id){
                    wasAdded = true
                }
            })
            return wasAdded
        }
                    
        function validateForm(){
            formValidates = true
            if($('#issueItemsTable tbody tr').length < 1){
                alert('No items selected to issue');
                formValidates = false;
            }      
            $('#issueItemsTable tbody tr').each(function(){
                errors = ''
                rowValidates = true
                issueQuantity = $(this).find("[name='data[Item][Quantity][]']").val()
                totalQuantity = parseFloat($(this).attr('quantity'))
                //id = $(this).find("[name='data[Item][ItemID][]']").val()
                id = $(this).attr('itemId')
                if(issueQuantity == ''){
                    errors = errors + 'Enter a quantity'
                    rowValidates = false
                }else{
                    if(!isNumber(issueQuantity)){
                        errors = errors + 'Must be a number'
                        rowValidates = false
                    }else{
                        issueQuantity = parseFloat(issueQuantity)
                        if(issueQuantity > totalQuantity){
                            errors = errors + 'Qty exceeds available<br/>'
                            rowValidates = false
                        }
                        if(issueQuantity <= 0){
                            errors = errors + 'Qty must be positive<br/>'
                            rowValidates = false
                        }
                    } 
                }
                if(!rowValidates){
                    formValidates = false
                    $(this).find("label").remove()
                    $('#quantity' + id).removeClass('error')
                    //$(this).children('#quantity' + id).remove()
                    $('#quantity' + id).after("<label for='quantity"+id+"' class='error'>" + errors + "</label>")
                    $('#quantity' + id).addClass('error')
                }else{
                    $(this).find("label").remove()
                    $('#quantity' + id).removeClass('error')
                }
                        
            })
            return formValidates
        }

    </script>
    <div id="idSpecified" style="display:none;">no</div><?
    echo $this->Form->create('Inventory');
    ?>
    <table id="issueTable">
        <thead>
        <th>Select Item</th>
        <th>Items to be issued</th>
    </thead>
    <tbody>
    <td>
        <div id="chooseItem">
            <label for="part_id">Select Part:</label>
            <!-- dropdown list will show only parts currently in stock -->
            <select id="part_id" name="part_id">

            </select>
            <table id="existingInventory">
                <thead>
                <th>Inv Ctrl #</th>
                <th>P/N</th>
                <th>Rev</th>
                <th>Lot</th>
                <th>Batch</th>
                <th>Location</th>
                <th>Qty Avail</th>
                <th></th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </td>
    <td>
        <? //echo $this->Form->create('Item'); ?>
        <table id="issueItemsTable">
            <thead>
            <th>Inv Ctrl #</th>
            <th>P/N</th>
            <th>Rev</th>
            <th>Lot</th>
            <th>Batch</th>
            <th>Location</th>
            <th>Qty Avail</th>
            <th>Issue Qty</th>
            <th>Notes</th>
            <th></th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </td>
    </tbody>
    </table>
    <?
    echo $this->Form->end('Submit');
}
?>