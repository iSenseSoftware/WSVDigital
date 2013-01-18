<? if (isset($assemblies)) { ?>
    <script>
        $(document).ready(function(){
            var id = $('#topAssembly').val();
            var topRev = $('#topRevision').val();

            // 1. Populate the component select box
            $.ajax({
                url:'../../../parts/ajaxGetParts/query:/notId:' + id,
                dataType:'xml',
                type:'GET',
                success:populateComponents
            });
            $.ajax({
                url:'../../../assemblies/ajaxGetComponents/' + id + '/' + topRev,
                dataType:'xml',
                type:'GET',
                success:populateExistingComponents
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
                url: '../../../parts/ajaxGetPart/' + $('#component option:selected').val(),
                dataType:'xml',
                type:'GET',
                success:function(xml){
                    $('[for="componentQuantity"]').text('Quantity (' + $(xml).find('uom').text() + ")");
                    $('#componentUom').val($(xml).find('uom').text());
                }
            })
        }

    </script>
    <h1>Edit Assembly</h1>
    <br/>
    <br/>
    <? echo $this->Form->create('Assembly'); ?>
    <strong>Top Assembly: </strong><br/><? echo $assemblies[0]['TopAssembly']['ModelCode'] . ' ' . $assemblies[0]['TopAssembly']['ModelName']; ?>
    <br/>
    <strong>Top Revision: </strong><br/><? echo $assemblies[0]['Assembly']['TopRevision']; ?><br/>
    <strong>UOM: </strong><br/><? echo $assemblies[0]['TopAssembly']['Uom']['UOMCode']; ?>
    <input id='topAssembly' type='hidden' name='data[Assembly][TopID]' value='<? echo $assemblies[0]['Assembly']['TopID']; ?>'>
    <input id='topRevision' type='hidden' name='data[Assembly][TopRevision]' value='<? echo $assemblies[0]['Assembly']['TopRevision']; ?>'>
    <br/><br/>
    <table id="editAssembly" style='border:1px solid black;'>
        <thead>
        <th>Add Component:</th>
        <th>Selected Components:</th>
    </thead>
    <tbody>
        <tr>
            <td style='border:1px solid black;'>
                <label for="component">Component:</label>
                <select id="component">

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
<? } else { ?>
    
<? } ?>