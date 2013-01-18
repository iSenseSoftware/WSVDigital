<div data-role="collapsible" data-collapsed="false" style="border:1px solid black;">
    <h3>Item Summary: </h3>
    <h4>Inv Ctrl: <? echo $data[0][0]['ItemCode']; ?></h4>
    <h4>P/N: <? echo $data[0][0]['ModelCode']; ?></h4>
    <h4>Name: <? echo $data[0][0]['ModelName']; ?></h4>
</div>
<h3>Lots currently in stock:</h3>
<ul data-role="listview" data-inset="true" data-filter="false">
    <? foreach ($data as $item) { ?>
        <li><a href="../items/mobileView/<? echo $item[0]['ItemCode']; ?>"></a><? echo "Inv Ctrl: {$item[0]['ItemCode']} Lot: {$item[0]['TransLineItemUD2']} Batch: {$item[0]['TransLineItemUD3']}"; ?></li>
    <? } ?>
</ul>
