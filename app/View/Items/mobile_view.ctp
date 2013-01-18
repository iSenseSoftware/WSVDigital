<script>
// later add script to load location select boxes dynamically as this could be a big resource hog on initial load
$(document).unbind("pagebeforechange");
</script>
<div data-role="page" id="main">
    <div data-role="header">
        <? echo $this->Session->flash();?>  
    </div>
    <div data-role="content">
        
        <div data-role="collapsible" data-collapsed="false">
            <h2>Item Detail:</h2>
            <h3>Inv Ctrl: <? echo $items[0]['Item']['ItemCode'];?></h3>
            <h3>P/N: <? echo $items[0]['Part']['ModelCode'];?></h3>
            <h3>Name: <? echo $items[0]['Part']['ModelName'];?></h3>
            <h3>Lot: <? echo $items[0]['Item']['TransLineItemUD2'];?></h3>
            <h3>Batch: <? echo $items[0]['Item']['TransLineItemUD3'];?></h3>
            <h3>Exp Date: <? echo $items[0]['Item']['TransLineItemUD6'];?></h3>
            <h3>PO#: <? echo $items[0]['Item']['TransLineItemUD4'];?></h3>
            <h3>Supplier: <? echo $items[0]['Item']['TransLineItemUD5'];?></h3>
        </div>
        <div data-role="content">
        <h2>Stocked Locations:</h2>
        </div>
        <ul data-role="listview" data-inset="true" data-filter="true">
            <? foreach($items as $item){ ?>
            <li><a href="#item<? echo $item['Item']['ItemID'];?>"><? echo "{$item['Location']['LocationCode']} ({$item['Location']['LocationType']['LocationTypeCode']})"; ?></a></li>
            <? } ?>
        </ul>
    </div>
    <div data-role="footer">
    <h2><a href="../mobileIndex">WSV Inventory Home</a></h2>
</div>
</div>

<? foreach($items as $item){ ?>
<div data-role="page" id="<? echo "item{$item['Item']['ItemID']}";?>">
    <div data-role="header">
        <? echo $this->Session->flash();?>
        Item Detail:
    </div>
    <div data-role="content">
        <div data-role="collapsible" data-collapsed="false">
            <h3>Inv Ctrl: <? echo $item['Item']['ItemCode'];?></h3>
            <h3>P/N: <? echo $item['Part']['ModelCode'];?></h3>
            <h3>Name: <? echo $item['Part']['ModelName'];?></h3>
            <h3>Lot: <? echo $item['Item']['TransLineItemUD2'];?></h3>
            <h3>Batch: <? echo $item['Item']['TransLineItemUD3'];?></h3>
            <h3>Exp Date: <? echo $item['Item']['TransLineItemUD6'];?></h3>
            <h3>PO#: <? echo $item['Item']['TransLineItemUD4'];?></h3>
            <h3>Supplier: <? echo $item['Item']['TransLineItemUD5'];?></h3>
            <h3>Location: <? echo $item['Location']['LocationCode'];?></h3>
            <h3>Qty: <? echo (float)$item['Item']['Quantity'] . ' ' . $item['Part']['Uom']['UOMCode'];?></h3>
            <h3>Status: <? echo $item['Location']['LocationType']['LocationTypeName'];?></h3>
        </div>
        <ul data-role="listview" data-inset="true">
            <li><a href="#issueItem<? echo $item['Item']['ItemID'];?>">Issue</a></li>
            <li><a href="#moveItem<? echo $item['Item']['ItemID'];?>">Move</a></li>
            <li><a href="#adjustItem<? echo $item['Item']['ItemID'];?>">Adjust</a></li>
        </ul>
    </div>
    <div data-role="footer">
    <h2><a href=../>mobileIndex">WSV Inventory Home</a></h2>
</div>
</div>
<? } ?>

<? foreach($items as $item){ ?>
<div data-role="page" id="<? echo "issueItem{$item['Item']['ItemID']}";?>">
    <div data-role="header">
        <? echo $this->Session->flash();?>
        Item Detail:
    </div>
    <div data-role="content">
        <div data-role="collapsible" data-collapsed="false">
            <h3>Inv Ctrl: <? echo $item['Item']['ItemCode'];?></h3>
            <h3>P/N: <? echo $item['Part']['ModelCode'];?></h3>
            <h3>Name: <? echo $item['Part']['ModelName'];?></h3>
            <h3>Lot: <? echo $item['Item']['TransLineItemUD2'];?></h3>
            <h3>Batch: <? echo $item['Item']['TransLineItemUD3'];?></h3>
            <h3>Exp Date: <? echo $item['Item']['TransLineItemUD6'];?></h3>
            <h3>PO#: <? echo $item['Item']['TransLineItemUD4'];?></h3>
            <h3>Supplier: <? echo $item['Item']['TransLineItemUD5'];?></h3>
            <h3>Location: <? echo $item['Location']['LocationCode'];?></h3>
            <h3>Qty: <? echo (float)$item['Item']['Quantity'] . ' ' . $item['Part']['Uom']['UOMCode'];?></h3>
            <h3>Status: <? echo $item['Location']['LocationType']['LocationTypeName'];?></h3>
        </div>
        <?
        echo $this->Form->create('Item', array('action'=>'mobileIssue'));
        echo $this->Form->input('Quantity');
        echo $this->Form->input('ItemID', array('value'=>$item['Item']['ItemID']));
        echo $this->Form->input('Notes');
        echo $this->Form->end('Submit');
        ?>
    </div>
    <div data-role="footer">
    <h2><a href="../mobileIndex">WSV Inventory Home</a></h2>
</div>
</div>
<? } ?>
<? foreach($items as $item){ ?>
<div data-role="page" id="<? echo "adjustItem{$item['Item']['ItemID']}";?>">
    <div data-role="header">
        <? echo $this->Session->flash();?>
        Item Detail:
    </div>
    <div data-role="content">
        <div data-role="collapsible" data-collapsed="false">
            <h3>Inv Ctrl: <? echo $item['Item']['ItemCode'];?></h3>
            <h3>P/N: <? echo $item['Part']['ModelCode'];?></h3>
            <h3>Name: <? echo $item['Part']['ModelName'];?></h3>
            <h3>Lot: <? echo $item['Item']['TransLineItemUD2'];?></h3>
            <h3>Batch: <? echo $item['Item']['TransLineItemUD3'];?></h3>
            <h3>Exp Date: <? echo $item['Item']['TransLineItemUD6'];?></h3>
            <h3>PO#: <? echo $item['Item']['TransLineItemUD4'];?></h3>
            <h3>Supplier: <? echo $item['Item']['TransLineItemUD5'];?></h3>
            <h3>Location: <? echo $item['Location']['LocationCode'];?></h3>
            <h3>Qty: <? echo (float)$item['Item']['Quantity'] . ' ' . $item['Part']['Uom']['UOMCode'];?></h3>
            <h3>Status: <? echo $item['Location']['LocationType']['LocationTypeName'];?></h3>
        </div>
        <?
        echo $this->Form->create('Item', array('action'=>'mobileAdjust'));
        echo $this->Form->input('Quantity', array('label'=>'New Quantity'));
        echo $this->Form->input('ItemID', array('value'=>$item['Item']['ItemID']));
        echo $this->Form->input('Notes');
        echo $this->Form->end('Submit');
        ?>
    </div>
    <div data-role="footer">
    <h2><a href="../mobileIndex">WSV Inventory Home</a></h2>
</div>
</div>
<? } ?>
<? foreach($items as $item){ ?>
<div data-role="page" id="<? echo "moveItem{$item['Item']['ItemID']}";?>">
    <div data-role="header">
        <? echo $this->Session->flash();?>
        Item Detail:
    </div>
    <div data-role="content">
        <div data-role="collapsible" data-collapsed="false">
            <h3>Inv Ctrl: <? echo $item['Item']['ItemCode'];?></h3>
            <h3>P/N: <? echo $item['Part']['ModelCode'];?></h3>
            <h3>Name: <? echo $item['Part']['ModelName'];?></h3>
            <h3>Lot: <? echo $item['Item']['TransLineItemUD2'];?></h3>
            <h3>Batch: <? echo $item['Item']['TransLineItemUD3'];?></h3>
            <h3>Exp Date: <? echo $item['Item']['TransLineItemUD6'];?></h3>
            <h3>PO#: <? echo $item['Item']['TransLineItemUD4'];?></h3>
            <h3>Supplier: <? echo $item['Item']['TransLineItemUD5'];?></h3>
            <h3>Location: <? echo $item['Location']['LocationCode'];?></h3>
            <h3>Qty: <? echo (float)$item['Item']['Quantity'] . ' ' . $item['Part']['Uom']['UOMCode'];?></h3>
            <h3>Status: <? echo $item['Location']['LocationType']['LocationTypeName'];?></h3>
        </div>
        <?
        echo $this->Form->create('Item', array('action'=>'mobileMove'));
        echo $this->Form->input('Quantity', array('label'=>'Quantity to move'));
        echo $this->Form->input('Location');
        echo $this->Form->input('ItemID', array('value'=>$item['Item']['ItemID']));
        echo $this->Form->input('Notes');
        echo $this->Form->end('Submit');
        ?>
    </div>
    <div data-role="footer">
    <h2><a href="../mobileIndex">WSV Inventory Home</a></h2>
</div>
</div>

<? } ?>
