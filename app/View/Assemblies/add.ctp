<script>
    $(document).ready(function(){
        $('#openComponents').click(function(){
            if($('#topRevision').val() != ''){
                // check to see if assembly already exists
                var id = $('#topAssembly option:selected').val();
                var topRev = $('#topRevision').val();
                $('#topAssembly').hide().before("<span>" + $('#topAssembly option:selected').text() + "</span>");
                $('#topRevision').hide().after("<span>" + topRev + "</span>");
                $(this).hide();
                $('#addAssembly').show(); 
                // 1. Populate the component select box
                $.ajax({
                    url:'../parts/ajaxGetParts/query:/notId:' + id,
                    dataType:'xml',
                    type:'GET',
                    success:populateComponents
                });
                $.ajax({
                    url:'../assemblies/ajaxGetComponents/' + id + '/' + topRev,
                    dataType:'xml',
                    type:'GET',
                    success:populateExistingComponents
                })
                // 2. Populate existing components with matching revision
                
            }else{
                alert('You must enter a revision');
            }
            
        })
        $('#addComponent').click(addComponent);
        
        
    })
    
    function populateComponents(xml){
        $(xml).find('part').each(function(){
            $('#component').append("<option value='" + $(this).find('id').text() +  "'>" + $(this).find('partNumber').text() + ' ' + $(this).find('name').text() + "</option>");
        })
        $('#component option').each(function(){
            $(this).click(setUom);
        });
        setUom();
    }
    
    function partHasBeenAdded(id){
        var added = false;
        $('.aComponent').each(function(){
            if($(this).val() == id){
                added = true;
            }
        });
        return added;
    }
    
    function populateExistingComponents(xml){
        $(xml).find('component').each(function(){
            $('#selectedComponents tbody').append("<tr><input class='aComponent' name='data[Assembly][ComponentID][]' value='" + 
                $(this).find('id').text() + "' type='hidden'>" +
            "<input name='data[Assembly][ComponentRevision][]' type='hidden' value='" + $(this).find('revision').text() + "'>" +
            "<input name='data[Assembly][QtyPerAssembly][]' type='hidden' value='" + $(this).find('quantity').text() + "'>" +
            "<td>" + $(this).find('partNumber').text() + " " + $(this).find('name').text() + "</td><td>" + $(this).find('revision').text() +
            "</td><td>" + parseFloat($(this).find('quantity').text()).toString() + ' ' + $(this).find('uom').text() + "</td><td><button type='button' class='removeComponent'>Remove</button></td></tr>");
        })
        bindRemoves();
    }
    
    function addComponent(){
        var componentId = $('#component option:selected').val();
        var componentRevision = $('#componentRevision').val();
        var componentQuantity = $('#componentQuantity').val();
        var uom = $('#componentUom').val();
        if(partHasBeenAdded(componentId)){
            alert('Part is already in assembly!');
            return false;
        }
        if(componentRevision == '' || componentQuantity == ''){
            alert('Please fill out all required fields');
            return false;
        }else{
            if(!isNumber(componentQuantity)){
                alert('Quantity must be a number');
                return false;
            }else{
                if(componentQuantity <=0){
                    alert('Quantity must be positive');
                    return false;
                }else{
                    // add the component row
                    $('#selectedComponents tbody').append("<tr><input class='aComponent' name='data[Assembly][ComponentID][]' value='" + componentId + "' type='hidden'>" +
            "<input name='data[Assembly][ComponentRevision][]' type='hidden' value='" + componentRevision + "'>" +
            "<input name='data[Assembly][QtyPerAssembly][]' type='hidden' value='" + componentQuantity + "'>" +
            "<td>" + $('#component option:selected').text() + "</td><td>" + componentRevision +
            "</td><td>" + parseFloat(componentQuantity).toString() + ' ' + uom + "</td><td><button type='button' class='removeComponent'>Remove</button></td></tr>")
                }
            }
        }
        bindRemoves()
    }
    function bindRemoves(){
        $('#selectedComponents button').each(function(){
            $(this).click(function(){
                $(this).parent().parent().remove();
            })
        })
    }
    function setUom(){
        $.ajax({
            url: '../parts/ajaxGetPart/' + $('#component option:selected').val(),
            dataType:'xml',
            type:'GET',
            success:function(xml){
                $('[for="componentQuantity"]').text('Quantity (' + $(xml).find('uom').text() + ")");
                $('#componentUom').val($(xml).find('uom').text());
            }
        })
    }
    function cleanSelects(){
        
    }
</script>
<h1>Create New Assembly</h1>
<br/>
<br/>
<? echo $this->Form->create('Assembly'); ?>
<label for="topAssembly">Top Assembly:</label>
<select id="topAssembly" name="data[Assembly][TopID]">
    <?
    foreach ($parts as $part) {
        $part['Part']['ModelName'] = substr($part['Part']['ModelName'], 0, 40);
        echo "<option value='{$part['Part']['ModelID']}'>{$part['Part']['ModelCode']} {$part['Part']['ModelName']}</option>";
    }
    ?>
</select>
<label for="topRevision">Top Assembly Revision:</label>
<input id="topRevision" name="data[Assembly][TopRevision]" style="width:4em;">
<br/><br/>
<button type="button" id="openComponents">Edit Components</button>
<br/>
<br/><br/>
<table id="addAssembly" style='border:1px solid black;display:none;'>
    <thead>
    <th>Add Component:</th>
    <th>Selected Components:</th>
</thead>
<tbody>
    <tr>
        <td style='border:1px solid black;'>
            <label for="component">Component:</label>
            <select id="component">
                <?
                //foreach ($parts as $part) {
                //    $part['Part']['ModelName'] = substr($part['Part']['ModelName'], 0, 40);
                //    echo "<option id='{$part['Part']['ModelID']}'>{$part['Part']['ModelCode']} {$part['Part']['ModelName']}</option>";
                //}
                ?>
            </select>
            <label for="componentRevision">Revision:</label>
            <input id="componentRevision" style="width:4em;">
            <label for="componentQuantity">Quantity</label>
            <input id="componentQuantity" style="width:4em;">
            <input id="componentUom" type="hidden">
            <br/><br/>
            <button type="button" id="addComponent">Add</button>
        </td>  
        <td style='border:1px solid black;'>
            <table id="selectedComponents">
                <thead>
                <th>Component</th>
                <th>Rev</th>
                <th>Qty/Assy</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </td>
    </tr>
</tbody>
</table>
<br/>
<? echo $this->Form->end('Submit'); ?>