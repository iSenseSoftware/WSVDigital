<h1>Adjust Stock Inventory</h1>
<br/><br/>
<? if (isset($item)) { ?>
    <script>
        $(document).ready(function(){
            $('#ItemAdjustForm').validate();
        })
    </script>
    <div id="idSpecified" style="display:none;">yes</div>
    <div id="ItemSummary">
        <strong>Inventory Control #: </strong><? echo $item[0][0]['ItemCode']; ?><br/>
        <strong>P/N: </strong><? echo $item[0][0]['ModelCode']; ?>
        <strong>Rev </strong><? echo $item[0][0]['TransLineItemUD1']; ?><br/>
        <strong>Name: </strong><? echo $item[0][0]['ModelName']; ?><br/>
        <strong>Lot: </strong><? echo $item[0][0]['TransLineItemUD2']; ?>
        <strong>Batch: </strong><? echo $item[0][0]['TransLineItemUD3']; ?><br/>
        <strong>PO: </strong><? echo $item[0][0]['TransLineItemUD4']; ?><br/>
        <strong>Location: </strong><? echo $item[0][0]['LocationCode']; ?></br>
        <strong>Exp Date: </strong><? echo $item[0][0]['TransLineItemUD6']; ?><br/>
        <strong>Quantity in stock: </strong><? echo (float) $item[0][0]['Quantity'] . ' ' . $item[0][0]['UOMCode']; ?><br/>
        <strong>Status: </strong><? echo $item[0][0]['LocationTypeCode']; ?>
    </div>
    <br/>
    <?
    echo $this->Form->create('Item');
    echo $this->Form->input('ItemID', array(
        'value' => $item[0][0]['ItemID'],
        'type' => 'hidden'
    ));
    echo $this->Form->input('Quantity', array('label' => 'New Quantity', 'class' => 'required number'));
    echo $this->Form->input('Notes');
    echo $this->Form->end('Submit');
} else {
    ?>
    <script>
        $(document).ready(function(){
            // populate parts select element.  Ajax GET request to Inventories/ajaxCurrentStock
            var inventoryId;
            var partId;
            var allowZero;
            // Populate the parts select dropdown if the id was not specified
            if($('#allowZero').attr('checked')){
                allowZero = 1;
            }else{
                allowZero = 0;
            }
            if($('#idSpecified').text() != 'yes'){
                $.ajax({
                    type: "GET",
                    url: "ajaxCurrentStock/" + allowZero,
                    dataType: "xml",
                    success: populateParts
                });
            }
            // Bind the addItem function to the 'Add' button click event
            $('#addItem').click(function(){
                addItem()
            })
                    
            $('#InventoryAdjustForm').submit(function(){
                if($('#issueItemsTable tr').length == 1){
                    alert('No items selected to adjust.');
                    return false;
                }else{
                    return true;
                }
            })
                    
            $('#allowZero').click(function(){
                if($(this).attr('checked')){
                    allowZero = 1;
                }else{
                    allowZero = 0;
                }
                $.ajax({
                    type: "GET",
                    url: "ajaxCurrentStock/" + allowZero,
                    dataType: "xml",
                    success: populateParts
                });
            })
        });
        function populateParts(xml){
            $('#part_id').children().remove();
            $(xml).find("part").each(function(){
                // add an option element with value = part_id
                // and display = PN +  Name
                        
                $('#part_id').append('<option>' + $(this).find('number').text() + ': ' + 
                    $(this).find('name').text() + '</option>');
                $('#part_id option').last().attr('value', $(this).find('id').text());
            });
            partId = $('#part_id option:selected').attr('value');
            if($('#allowZero').attr('checked') == 'checked'){
                allowZero = 1;
            }else{
                allowZero = 0;
            }
            $.ajax({
                type: "GET",
                url: "ajaxStockedLocations/" + partId + '/' + allowZero,
                dataType: "xml",
                success: populateLocations
            });
            $('#part_id option').click(function(){
                if($('#allowZero').attr('checked') == 'checked'){
                    allowZero = 1;
                }else{
                    allowZero = 0;
                }
                partId = $('#part_id option:selected').attr('value');
                $.ajax({
                    type: "GET",
                    url: "ajaxStockedLocations/" + partId + '/' + allowZero,
                    dataType: "xml",
                    success: populateLocations
                });
            });
                    
        }

        function populateLocations(xml){
            $('#location_id').children().remove();
            $(xml).find("location").each(function(){
                // add an option element with value = part_id
                // and display = PN +  Name
                $('#location_id').append('<option>' + $(this).find('code').text()+ '</option>');
                $('#location_id option').last().attr('value', $(this).find('id').text());
            });
            locationId = $('#location_id option:selected').attr('value');
            if($('#allowZero').attr('checked') == 'checked'){
                allowZero = 1;
            }else{
                allowZero = 0;
            }
            $.ajax({
                type: "GET",
                url: "ajaxView/" + partId + '/' + locationId + '/' + allowZero,
                dataType: "xml",
                success: populateInventory
            });
            $('#location_id option').click(function(){
                locationId = $('#location_id option:selected').attr('value');
                if($('#allowZero').attr('checked')){
                    allowZero = 1;
                }else{
                    allowZero = 0;
                }
                $.ajax({
                    type: "GET",
                    url: "ajaxView/" + partId + '/' + locationId + '/' + allowZero,
                    dataType: "xml",
                    success: populateInventory
                });
            });
        }

        function populateInventory(xml){
            $('#existingInventory').children().remove();
            $('#existingInventory').text('');
            $('#itemId').text('');
            $('#existingInventory').append("<label for='inventory'>Select Item:</label>");
            $(xml).find('Item').each(function(){
                id = $(this).find('id').text();
                quantity = $(this).find('quantity').text();
                uom = $(this).find('uom').text();
                rev = $(this).find('rev').text();
                status = $(this).find('status').text();
                lot = $(this).find('lot').text();
                batch = $(this).find('batch').text();
                invCtrl = $(this).find('invCtrl').text();
                $('#existingInventory').append("<input type='radio' name='inventory' value='" + id +
                    "' invCtrl='" + invCtrl + "' totalQty='" + quantity + "' /><div style='float:left;padding-left:10px;padding-bottom:10px;'><strong>Inv Ctrl: </strong>" + invCtrl + "<br/><strong>Status: </strong>" + status + " <br/><strong>Rev: </strong>" + rev + 
                    " <br/><strong>Lot: </strong>" + lot + " <br/><strong>Batch: </strong>" + batch + "<br/><strong>Qty: </strong>" + parseFloat(quantity).toString() + uom + "</div>");
            });
                    
            $('#existingInventory input').first().attr('checked', 'checked');
            $('#InventoryItemID').val($('#existingInventory input:checked').attr('value'));
            $('#invCtrl').val($('#existingInventory input:checked').attr('invCtrl'));
            $('#existingInventory input').click(function(){
                $('#InventoryItemID').val($('#existingInventory input:checked').attr('value'));
                $('#invCtrl').val($('#existingInventory input:checked').attr('invCtrl'));
            });
        }
                
        function addItem(){
            var itemId = $('#InventoryItemID').val();
            var partNumber = $('#part_id :selected').text();
            var location = $('#location_id :selected').text();
            var qty = $('#quantity').val();
            var totalQty = parseFloat($('#existingInventory input:checked').attr('totalQty'));
            var invCtrl = $('#invCtrl').val();
            if(!isNumber(qty) || qty < 0){
                alert('You must enter a positive number for quantity');
            }else{
                if(qty == totalQty){
                    alert('New quantity cannot be the same as existing quantity');
                }else{
                    // check to see if this item has already been added.
                    var alreadyAdded = false;
                    $('#issueItemsTable tr').each(function(){
                        if($(this).children("[name='data[Item][ItemID][]']").val() == itemId){
                            alreadyAdded = true;
                        }
                    });
                    if(alreadyAdded){
                        alert('Item is already being adjusted');
                    }else{
                        $("#issueItemsTable tbody").append(
                        "<tr><td>" + invCtrl + "</td><td>" + partNumber + "</td><td>" + location + 
                            "</td><td>" + qty + "</td>" + "<td><input name='data[Item][Notes][]'></td><input type='hidden' name='data[Item][ItemID][]' value='" + itemId + 
                            "'/><input type='hidden' name='data[Item][Quantity][]' value ='" + qty + "'/><td><button type='button'>Remove</button></td></tr>"
                    );

                        $('#issueItemsTable button').each(function(){
                            $(this).click(function(){
                                $(this).parent().parent().remove();
                            });
                        });
                    }   
                }           
            }
        }
    </script>
    <div id="idSpecified" style="display:none;">no</div><?
    echo $this->Form->create('Inventory');
    ?>
    <table id="issueTable">
        <thead>
        <th>Select Item</th>
        <th>Items to be adjusted</th>
    </thead>
    <tbody>
    <td>
        <div id="chooseItem">
            <label for="part_id">Select Part:</label>
            <!-- dropdown list will show only parts currently in stock -->
            <select id="part_id" name="part_id">

            </select>
            <label for="location_id">Select Location:</label>
            <select id="location_id" name="location_id">
                <!-- populate with locations with the part selected -->
            </select>
            <div id="existingInventory">

            </div>
            <input id="invCtrl" type="hidden" />
            <input id="totalQty" type="hidden" />
            <?
            echo $this->Form->input('allowZero', array('type' => 'checkbox', 'id' => 'allowZero', 'label' => 'Show Out of Stock'));
            echo $this->Form->input('ItemID', array('type' => 'hidden'));
            echo $this->Form->input('quantity', array('label' => 'New quantity', 'id' => 'quantity'));
            echo $this->Form->button('Add', array('type' => 'button', 'id' => 'addItem'));
            ?>
        </div>
    </td>
    <td>
        <table id="issueItemsTable">
            <thead>
            <th>Inv Ctrl#</th>
            <th>P/N</th>
            <th>Location</th>
            <th>New Qty</th>
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