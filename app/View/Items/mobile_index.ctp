<script>
    $(document).bind("pagebeforechange", function(e, data){
        //       // populate the listview element for the page being transitioned to
        var modelCode = data.toPage;//.attr('modelCode');
        if(typeof modelCode == 'string'){
            m = modelCode.split("#part");
            console.debug(m);
            $.ajax({
                type:'GET',
                url:'../items/fetchByPart/' + m[1],
                dataType:'html',
                success:function(html){
                    $("#part" + m[1]).children(':jqmData(role=content)').append(html);
                    $("#part" + m[1] + ' ul').listview();
                    $("#part" + m[1]).children(':jqmData(role=collapsible)').collapsible();
                }
            });
        }else{
            // do nothing
        }

    });
</script>
<div data-role="page">
    <div data-role="content">
        <ul data-role="listview" data-inset="true" data-filter="true">
            <? foreach ($results as $item) { ?>
                <li><a href="#part<? echo $item[0]['ModelCode']; ?>"><? echo $item[0]['ModelCode'] . "  Qty: " .
            (float) $item[0]['TotalQty'] . ' ' . $item[0]['UOMCode'];
                ?></a></li>
<? } ?>
        </ul>
    </div>
</div>
<? foreach ($results as $item) { ?>
    <div data-role="page" id="part<? echo $item[0]['ModelCode']; ?>" modelCode="<? echo $item[0]['ModelCode']; ?>">
        <div data-role="content">
            
        </div>
    </div>
<? } ?>
