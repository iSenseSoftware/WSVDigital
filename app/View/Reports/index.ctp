<script>
    var scrollPosition;
    $(document).ready(function(){
        var sorter = new joshPaginator({
            baseUrl: 'reports/stockByAssembly',
            sortBy: 'Part.ModelCode',
            resultCount: 10,
            callback:bindActions
        });
        sorter.initialize();
        $('#printPage').click(function(){

        })
    })
    function showComponents(){
        scrollPosition = $(this).scrollTop();
        var id = $(this).attr('ModelId');
        var rev = $(this).attr('rev');
        var offset = parseInt($(this).attr('offset')) + 3;
        var self = this;
        var rgbOffset = offset * 10;
        if(rgbOffset > 143){
            rgbOffset = rgbOffset % (143);
        }
        var rgb = 255 - rgbOffset
        rgb = rgb.toString(16);
        rgb = '#' + rgb + rgb + rgb;
        $.ajax({
            url:'reports/getComponentRows/' + id + '/' + rev + '/' + offset,
            dataType:'html',
            type:'GET',
            success:function(html){
                if(html != ''){
                    $(self).scrollTop(scrollPosition);
                    $(self).parent().parent().after(html);
                    $(self).unbind('click');
                    $(self).removeClass('assembly');
                    $(self).parent().parent().addClass('expandedAssembly');
                    $(self).parent().parent().css('background-color', rgb);
                    $(self).parent().parent().siblings('.childOf' + id).children('td').css('background-color', rgb);
                    $(self).click(toggleComponents);
                    bindActions();
                }
            }
        })
    }
    
    function toggleComponents(){
        scrollPosition = $(this).scrollTop();
        var id = $(this).attr('ModelId');
        var rev = $(this).attr('rev');
        var offset = parseInt($(this).attr('offset')) + 3;
        var rgbOffset = offset * 10;
        var rgbOffsetOld = (offset - 3) * 10;
        if(rgbOffsetOld > 143){
            rgbOffsetOld = rgbOffsetOld % (143);
        }
        if(rgbOffset > 143){
            rgbOffset = rgbOffset % (143);
        }
        var rgb = 255 - rgbOffset
        var rgbOld = 255 - rgbOffsetOld
        rgb = rgb.toString(16);
        rgb = '#' + rgb + rgb + rgb;
        rgbOld = rgbOld.toString(16);
        rgbOld = '#' + rgbOld + rgbOld + rgbOld;
        if(offset == 3){rgbOld = '#FFFFFF';}
        $(this).parent().parent().parent().find('.childOf' + id).toggle();
        if($(this).parent().parent().hasClass('expandedAssembly')){
            $(this).parent().parent().removeClass('expandedAssembly');
            $(this).parent().parent().addClass('collapsedAssembly');
            $(this).parent().parent().css('background-color', rgbOld);
        }else{
            $(this).parent().parent().addClass('expandedAssembly');
            $(this).parent().parent().removeClass('collapsedAssembly');
            $(this).parent().parent().css('background-color', rgb);
        }
        $(this).scrollTop(scrollPosition);
        calculateTotals();
    }
    
    function bindActions(){
        $('.assembly').each(function(){
            $(this).unbind('click');
            $(this).click(showComponents);   
        })
        calculateTotals();
    }
    function calculateTotals(){
        $('.topRow').each(function(){
            var modelId = $(this).attr('modelId');
            var revision = $(this).attr('rev');
            var totalRel;
            var maxOrder = parseFloat($('#maxFor' + modelId + 'Rev' + revision).text());
            $(this).find('#topFor' + modelId + 'Rev' + revision)
            .children('tbody').children('tr').not('[class*="childOf"]')
            .each(function(){
                if($(this).hasClass('expandedAssembly')){
                    var qty = parseFloat($(this).children().last().text()) + parseFloat(calculateChildTotals(this));
                    //alert(qty);
                    if(qty < totalRel || totalRel == null){
                        totalRel = qty;
                    }
                }else{
                    var qty = parseFloat($(this).children().last().text());
                    if(qty < totalRel || totalRel == null){
                        totalRel = qty;
                    }
                }
            }
        )
            $('#totalQtyFor' + modelId + 'Rev' + revision).text(totalRel);
            var reOrder = maxOrder - totalRel;
            if(reOrder < 0){
                $('#reOrderFor' + modelId + 'Rev' + revision).text('0');
            }else{
                $('#reOrderFor' + modelId + 'Rev' + revision).text(reOrder);
            }
        })
        
        function calculateChildTotals(inputObj){
            var childId = $(inputObj).children('td:first').children('a').attr('modelId');
            var childRev = $(inputObj).children('td:first').children('a').attr('rev');
            var childRelQty;
            $(inputObj).siblings('.childOf' + childId).children('td').children('table')
            .children('tbody').children('tr').not('[class*="childOf"]').each(function(){
                if($(this).hasClass('expandedAssembly')){
                    var qty = parseFloat($(this).children().last().text()) + parseFloat(calculateChildTotals(this));
                    if(qty < childRelQty || childRelQty == null){
                        childRelQty = qty;
                    }
                }else{
                    var qty = parseFloat($(this).children().last().text());
                    //alert(qty);
                    if(qty < childRelQty || childRelQty == null){
                        childRelQty = qty;
                    }
                }
            })
            return childRelQty;
        }
        
        //$('#totalQtyFor' + modelId + 'Rev' + revision).text(totalRel);
    }
</script>
<h1>Stock for Assemblies</h1>

<br/><br/>

<?
echo $this->JoshPaginateImp->contentSpace(array(
    'showColumnSelect' => false
));
?>
<br/>
<br/>