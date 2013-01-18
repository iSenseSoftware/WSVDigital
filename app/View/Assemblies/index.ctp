<style>
    table tr:nth-child(even) {
    background: white;
}
</style>
<script>
    $(document).ready(function(){       
        var sorter = new joshPaginator({
            baseUrl: 'assemblies/fetchPage',
            sortBy: 'Part.ModelCode',
            resultCount: 10,
            callback:bindActions
        });
        sorter.initialize();
    }) 
    function showComponents(){
        var id = $(this).attr('ModelId');
        var rev = $(this).attr('rev');
        var offset = parseInt($(this).attr('offset')) + 3;
        var self = this;
        $.ajax({
            url:'assemblies/getComponentTable/' + id + '/' + rev + '/' + offset,
            dataType:'html',
            type:'GET',
            async:false,
            success:function(html){
                if(html != ''){
                    $(self).parent().parent().after(html);
                    $(self).unbind('click');
                    $(self).removeClass('assembly');
                    $(self).click(toggleComponents);
                    bindActions();
                }
            }
        })
        
    }
    
    function toggleComponents(){
        var id = $(this).attr('ModelId');
        var rev = $(this).attr('rev');
        var offset = parseInt($(this).attr('offset')) + 3;
        $(this).parent().parent().next('.childOf' + id).toggle();
    }
    
    function bindActions(){
        $('.assembly').each(function(){
            $(this).unbind('click');
            $(this).click(showComponents);
        })
    }
</script>
<h1>Assemblies</h1>
<br/><br/>
<h1>
    <?
    echo $this->Html->link('Create Assembly', array(
        'action' => 'add', 'controller' => 'assemblies'
    ));
    ?>
</h1>
<br/><br/>
<? echo $this->JoshPaginateImp->contentSpace(array(
    'showColumnSelect' => false
)); ?>
<br/>
<br/>