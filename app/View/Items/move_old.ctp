<h1>Move Stock Inventory</h1>
<br/><br/>
<? if (isset($item)) { ?>
    <script>
        $(document).ready(function(){
            $('#ItemMoveForm').validate();
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
    echo $this->Form->input('Quantity', array('class' => 'required'));
    echo $this->Form->input('Location', array('label' => 'Move To', 'class' => 'required number', 'empty' => true));
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
            if($('#idSpecified').text() != 'yes'){
                $.ajax({
                    type: "GET",
                    url: "ajaxCurrentStock",
                    dataType: "xml",
                    success: populateParts
                });
            }
            // Bind the addItem function to the 'Add' button click event
            $('#addItem').click(function(){
                //            var qty = $('#quantity').val();
                //            var itemId = $('#part_id').val();
                //            var locationId = $('#location_id').val();
                addItem()
            })
            $('#InventoryMoveForm').submit(function(){
                if($('#issueItemsTable tr').length == 1){
                    alert('No items selected to move');
                    return false;
                }else{
                    return true;
                }
            })
            // Populate the Move To select box
//            $.ajax({
//                type:'GET',
//                url:'ajaxStockedLocations',
//                dataType:'xml',
//                success:function(xml){
//                    $(xml).find('location').each(function(){
//                        $('#to_location_id').append('<option>' + $(this).find('code').text()+ '</option>');
//                        $('#to_location_id option').last().attr('value', $(this).find('id').text());
//                    })
//                }
//            })
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
                url: "ajaxStockedLocations/" + partId,
                dataType: "xml",
                success: populateLocations
            });
            $('#part_id option').click(function(){
                partId = $('#part_id option:selected').attr('value');
                $.ajax({
                    type: "GET",
                    url: "ajaxStockedLocations/" + partId,
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
            $.ajax({
                type: "GET",
                url: "ajaxView/" + partId + '/' + locationId,
                dataType: "xml",
                success: populateInventory
            });
            $('#location_id option').click(function(){
                locationId = $('#location_id option:selected').attr('value');
                $.ajax({
                    type: "GET",
                    url: "ajaxView/" + partId + '/' + locationId,
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
            var locationId = $('#location_id :selected').val();
            var to_location = $('#to_location_id :selected').text();
            var toLocationId = $('#to_location_id').val();
            var qty = $('#quantity').val();
            var totalQty = parseFloat($('#existingInventory input:checked').attr('totalQty'));
            var invCtrl = $('#invCtrl').val();
            if(!isNumber(qty) || qty <= 0){
                alert('You must enter a positive number for quantity');
            }else{
                if(qty > totalQty){
                    alert('There is not enough available stock for this transaction')
                }else{
                    // check to see if this item has already been added.
                    var alreadyAdded = false;
                    $('#issueItemsTable tr').each(function(){
                        if($(this).children("[name='data[Item][ItemID][]']").val() == itemId){
                            alreadyAdded = true;
                        }
                    });
                    if(alreadyAdded){
                        alert('Item is already being moved');
                    }else{
                        if(locationId == toLocationId){
                            alert('Cannot move to same location');
                        }else{
                            $("#issueItemsTable tbody").append(
                            "<tr><td>" + invCtrl + "</td><td>" + partNumber + "</td><td>" + location + 
                                "</td><td>" + to_location + "</td><td>" + qty + "</td>" + "<td><input name='data[Item][Notes][]'></td><input type='hidden' name='data[Item][ItemID][]' value='" + itemId + 
                                "'/><input type='hidden' name='data[Item][ToLocationId][]' value='" + toLocationId + "'><input type='hidden' name='data[Item][Quantity][]' value ='" + qty + "'/><td><button type='button'>Remove</button></td></tr>"
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
        }
    </script>
    <div id="idSpecified" style="display:none;">no</div><?
    echo $this->Form->create('Inventory');
    ?>
    <table id="issueTable">
        <thead>
        <th>Select Item</th>
        <th>Items to be moved</th>
    </thead>
    <tbody>
    <td>
        <div id="chooseItem">
            <label for="part_id">Select Part:</label>
            <!-- dropdown list will show only parts currently in stock -->
            <select id="part_id" name="part_id">

            </select>
            <label for="location_id">Move From:</label>
            <select id="location_id" name="location_id">
                <!-- populate with locations with the part selected -->
            </select>
            <div id="existingInventory">

            </div>
            <br/>
            <div>
                <label for="to_location_id">Move To:</label>
                <select id="to_location_id" name="to_location_id">
                    <!-- populate with locations with the part selected -->
                    <? foreach($locations as $key => $val){?>
                    <option value="<? echo $key;?>"><? echo $val;?></option>
                    <? } ?>
                </select>
            </div>
            <input id="invCtrl" type="hidden" />
            <input id="totalQty" type="hidden" />
            <?
            echo $this->Form->input('ItemID', array('type' => 'hidden'));
            echo $this->Form->input('quantity', array('label' => 'Move quantity', 'id' => 'quantity'));
            echo $this->Form->button('Add', array('type' => 'button', 'id' => 'addItem'));
            ?>
        </div>
    </td>
    <td>
        <table id="issueItemsTable">
            <thead>
            <th>Inv Ctrl#</th>
            <th>P/N</th>
            <th>Old Location</th>
            <th>New Location</th>
            <th>Qty</th>
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