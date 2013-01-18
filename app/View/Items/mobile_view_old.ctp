<script>
    $(document).ready(function(){
        $('.itemButton').each(function(){
            $(this).mouseenter(function(){
                var itemId = $(this).attr('itemId');
                // clear all existing context menus
                $('.itemActionButton').remove();
                // add the context menu for the selection to the DOM
                $(this).parent().append(
                "<div class='itemActionButton issueAction itemButton' itemId='" + itemId + "'>Issue</div>" +
                "<div class='itemActionButton moveAction itemButton' itemId='" + itemId + "'>Move</div>" +
                "<div class='itemActionButton adjustAction itemButton' itemId='" + itemId + "'>Adjust</div>"
            )
                $('.issueAction').click(function(){
                    window.location.href ="http://huswivc0219/cake_test/items/issue/" + itemId;
                });
                $('.moveAction').click(function(){
                    window.location.href = "http://huswivc0219/cake_test/items/move/" + itemId;
                });
                $('.adjustAction').click(function(){
                    window.location.href = "http://huswivc0219/cake_test/items/adjust/" + itemId;
                });
            })
            $(this).mouseleave(function(){
                var itemId = $(this).attr('itemId');
                // clear all existing context menus
                $('.itemActionButton').remove();
            })
        })
    });
</script>
<h1>Item Summary</h1>
<br/>
<strong>P/N: </strong><? echo $items[0]['Part']['ModelCode']; ?><br/>
<strong>Name: </strong><? echo $items[0]['Part']['ModelName']; ?><br/>
<strong>Inv Ctrl: </strong><? echo $items[0]['Item']['ItemCode']; ?><br/>
<strong>Lot: </strong><? echo $items[0]['Item']['TransLineItemUD2']; ?><br/>
<strong>Batch: </strong><? echo $items[0]['Item']['TransLineItemUD3']; ?><br/>
<strong>Exp Date: </strong><? echo (strtotime($items[0]['Item']['TransLineItemUD6']) == 0) ? 'N/A' : date('M Y', strtotime($items[0]['Item']['TransLineItemUD6'])); ?><br/>
<strong>PO: </strong><? echo $items[0]['Item']['TransLineItemUD4']; ?><br/>
<strong>Supplier: </strong><? echo $items[0]['Item']['TransLineItemUD5']; ?><br/>
<br/>
<h2>Stocked Locations</h2>

<? foreach ($items as $item) { ?>
    <div class="autosize">
        <div class="itemButton" itemId='<? echo $item['Item']['ItemID']; ?>'><br/>
            <strong>Location: </strong><? echo $item['Location']['LocationCode']; ?><br/>
            <strong>Status: </strong><? echo $item['Location']['LocationType']['LocationTypeName']; ?><br/>
            <strong>Qty: </strong><? echo (float) $item['Item']['Quantity'] . ' ' . $item['Part']['Uom']['UOMCode']; ?>
        </div>
    </div>
    <br/>
<? } ?>

