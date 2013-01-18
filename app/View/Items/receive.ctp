<?
if(strpos(env('HTTP_USER_AGENT'), 'Firefox/3') || strpos(env('HTTP_USER_AGENT'), 'MSIE 8')){
?>
<script>
    var itemType;
    var uomCode;
    $.validator.setDefaults({ ignore: '' });
    $(document).ready(function(){
        //clear the form
        $('#ItemPart').val('');
        $('#ItemLocation').val('');
        $('#ItemTransLineItemUD1').val('');
        $('#ItemTransLineItemUD2').val('');
        $('#ItemTransLineItemUD3').val('');
        $('#ItemTransLineItemUD4').val('');
        $('#ItemTransLineItemUD5').val('');
        $('#ItemTransLineItemUD6').val('');
        qty = "data[Item][Quantity]"
        $('#ItemReceiveForm').validate();
        $('#ItemPart option').click(function(){
            var partId = $(this).val();
            $.ajax({
                    type:'GET',
                    url:'../parts/ajaxGetPart/' + partId,
                    dataType:'xml',
                    data:{},
                    success:function(xml){
                        uomCode = $(xml).find('uom').text()
                        $("label[for='ItemQuantity']").text("Quantity (" + uomCode + ")");
                        itemType = $(xml).find('typeId').text()
                        if(itemType == 4){
                            $('#ItemTransLineItemUD1').show();
                            $('#ItemTransLineItemUD2').show();
                            $('#ItemTransLineItemUD3').show();
                            $('#ItemTransLineItemUD4').show();
                            $('#ItemTransLineItemUD5').show();
                            $('#ItemTransLineItemUD6').show();
                            $("label[for='ItemTransLineItemUD1']").show();
                            $("label[for='ItemTransLineItemUD2']").show();
                            $("label[for='ItemTransLineItemUD3']").show();
                            $("label[for='ItemTransLineItemUD4']").show();
                            $("label[for='ItemTransLineItemUD5']").show();
                            $("[for='ItemTransLineItemUD6']").show();
                            $('#ItemTransLineItemUD1').attr('class', 'required');
                            $('#ItemTransLineItemUD4').attr('class', 'required');
                            $('#ItemTransLineItemUD5').attr('class', 'required');                            
                        }else{
                            $('#ItemTransLineItemUD1').attr('class', '');
                            $('#ItemTransLineItemUD2').attr('class', '');
                            $('#ItemTransLineItemUD3').attr('class', '');
                            $('#ItemTransLineItemUD4').attr('class', '');
                            $('#ItemTransLineItemUD5').attr('class', '');
                            $('#ItemTransLineItemUD6').attr('class', '');
                            
                            $('#ItemTransLineItemUD1').hide();
                            $('#ItemTransLineItemUD2').hide();
                            $('#ItemTransLineItemUD3').hide();
                            $('#ItemTransLineItemUD4').hide();
                            $('#ItemTransLineItemUD5').hide();
                            $('#ItemTransLineItemUD6').hide();
                            $('#ItemTransLineItemUD1').val('');
                            $('#ItemTransLineItemUD2').val('');
                            $('#ItemTransLineItemUD3').val('');
                            $('#ItemTransLineItemUD4').val('');
                            $('#ItemTransLineItemUD5').val('');
                            $('#ItemTransLineItemUD6').val('');
                            $("label[for='ItemTransLineItemUD1']").hide();
                            $("label[for='ItemTransLineItemUD2']").hide();
                            $("label[for='ItemTransLineItemUD3']").hide();
                            $("label[for='ItemTransLineItemUD4']").hide();
                            $("label[for='ItemTransLineItemUD5']").hide();
                            $("label[for='ItemTransLineItemUD6']").hide();
                        }
                    }
                });
                $.ajax({
                    type:'GET',
                    url:'../parts/ajaxGetSummary/' + partId,
                    data:{},
                    dataType:'html',
                    success:function(html){
                        $('#part_summary').children().remove();
                        $('#part_summary').text('');
                        $('#part_summary').append(html);
                    }
                });
        });
    });

</script>

<h2>Receive new inventory</h2>
<br/><br/>
<? 
echo $this->Form->create('Item');
echo $this->Form->input('Part', array('label'=>'Part Number', 'class'=>'required', 'empty'=>true, 'name'=>"data[Item][ModelID]"));
?><div id="part_summary"></div><?
echo $this->Form->input('Location', array('label'=>'Receive To Location:', 'class'=>'required', 'empty'=>true, 'name'=>"data[Item][LocationIDCurrent]")); 
echo $this->Form->input('Quantity', array('class' => 'required number'));
echo $this->Form->input('TransLineItemUD1', array('label' => 'Revision'));
echo $this->Form->input('TransLineItemUD2', array('label' => 'Lot'));
echo $this->Form->input('TransLineItemUD3', array('label' => 'Batch'));
echo $this->Form->input('TransLineItemUD4', array('label' => 'Purchase Order'));
echo $this->Form->input('TransLineItemUD5', array('label' => 'Supplier'));
echo $this->Form->input('TransLineItemUD6', array('label' => 'Exp Date'));
echo $this->Form->end('Submit');
}else{
    ?>
<script>
    var itemType;
    var uomCode;
    $.validator.setDefaults({ ignore: '' });
    $(document).ready(function(){
        //clear the form
        $('#part_list').val('');
        $('#part_id').val('');
        $('#location_id').val('');
        $('#location_list').val('');
        $('#ItemTransLineItemUD1').val('');
        $('#ItemTransLineItemUD2').val('');
        $('#ItemTransLineItemUD3').val('');
        $('#ItemTransLineItemUD4').val('');
        $('#ItemTransLineItemUD5').val('');
        $('#ItemTransLineItemUD6').val('');
        qty = "data[Item][Quantity]"
        $('#ItemReceiveForm').validate();
        $('#part_list').autocomplete({
            source:function(term, add){
                var output = [];
                $.ajax({
                    url:'../parts/ajaxGetParts/' + term.term,
                    dataType:'xml',
                    success:function(xml){
                        $(xml).find('part').each(function(){
                            output.push({
                                value: $(this).find('id').text(),
                                label: $(this).find('partNumber').text()
                            })
                        });
                        add(output);
                    },
                    type:'GET'
                })
            }, 
            select:function(event, ui){
                // clear error messages
                $('label.error').hide();
                $('input.error').removeClass('error');
                // prevent default event, which puts the VALUE in the input.  We want the label to show
                event.preventDefault();
                //set display value for input field
                $('#part_list').val(ui.item.label);
                // set value of hidden field
                $('#part_id').val(ui.item.value);
                // get the part info and adjust shown and required fields according to the itemType
                // also note the UOM for the receiver
                $.ajax({
                    type:'GET',
                    url:'../parts/ajaxGetPart/' + ui.item.value,
                    dataType:'xml',
                    success:function(xml){
                        uomCode = $(xml).find('uom').text()
                        $("label[for='ItemQuantity']").text("Quantity (" + uomCode + ")");
                        itemType = $(xml).find('typeId').text()
                        if(itemType == 4){
                            $('#ItemTransLineItemUD1').show();
                            $('#ItemTransLineItemUD2').show();
                            $('#ItemTransLineItemUD3').show();
                            $('#ItemTransLineItemUD4').show();
                            $('#ItemTransLineItemUD5').show();
                            $('#ItemTransLineItemUD6').show();
                            $("label[for='ItemTransLineItemUD1']").show();
                            $("label[for='ItemTransLineItemUD2']").show();
                            $("label[for='ItemTransLineItemUD3']").show();
                            $("label[for='ItemTransLineItemUD4']").show();
                            $("label[for='ItemTransLineItemUD5']").show();
                            $("[for='ItemTransLineItemUD6']").show();
                            $('#ItemTransLineItemUD1').attr('class', 'required');
                            $('#ItemTransLineItemUD4').attr('class', 'required');
                            $('#ItemTransLineItemUD5').attr('class', 'required');                            
                        }else{
                            $('#ItemTransLineItemUD1').attr('class', '');
                            $('#ItemTransLineItemUD2').attr('class', '');
                            $('#ItemTransLineItemUD3').attr('class', '');
                            $('#ItemTransLineItemUD4').attr('class', '');
                            $('#ItemTransLineItemUD5').attr('class', '');
                            $('#ItemTransLineItemUD6').attr('class', '');
                            
                            $('#ItemTransLineItemUD1').hide();
                            $('#ItemTransLineItemUD2').hide();
                            $('#ItemTransLineItemUD3').hide();
                            $('#ItemTransLineItemUD4').hide();
                            $('#ItemTransLineItemUD5').hide();
                            $('#ItemTransLineItemUD6').hide();
                            $('#ItemTransLineItemUD1').val('');
                            $('#ItemTransLineItemUD2').val('');
                            $('#ItemTransLineItemUD3').val('');
                            $('#ItemTransLineItemUD4').val('');
                            $('#ItemTransLineItemUD5').val('');
                            $('#ItemTransLineItemUD6').val('');
                            $("label[for='ItemTransLineItemUD1']").hide();
                            $("label[for='ItemTransLineItemUD2']").hide();
                            $("label[for='ItemTransLineItemUD3']").hide();
                            $("label[for='ItemTransLineItemUD4']").hide();
                            $("label[for='ItemTransLineItemUD5']").hide();
                            $("label[for='ItemTransLineItemUD6']").hide();
                        }
                    }
                });
                $.ajax({
                    type:'GET',
                    url:'../parts/ajaxGetSummary/' + ui.item.value,
                    dataType:'html',
                    success:function(html){
                        $('#part_summary').children().remove();
                        $('#part_summary').text('');
                        $('#part_summary').append(html);
                    }
                })
                // set required fields based on 
                
            },
            change:function(event, ui){
                //set display value for input field
                event.preventDefault();
                if(ui.item){
                    $('#part_list').val(ui.item.label);
                    // set value of hidden field
                    $('#part_id').val(ui.item.value);
                }else{
                    $('#part_list').val('');
                    // set value of hidden field
                    $('#part_id').val('');
                }
            },
            focus:function(event, ui){
                //set display value for input field
                event.preventDefault();
                if(ui.item){
                    $('#part_list').val(ui.item.label);
                    // set value of hidden field
                    $('#part_id').val(ui.item.value);
                }else{
                    $('#part_list').val('');
                    // set value of hidden field
                    $('#part_id').val('');
                }
            }
        })
        $('#location_list').autocomplete({
            source:function(term, add){
                var output = [];
                $.ajax({
                    url:'../locations/ajaxGetLocations/' + term.term,
                    dataType:'xml',
                    success:function(xml){
                        $(xml).find('location').each(function(){
                            output.push({
                                value: $(this).find('id').text(),
                                label: $(this).find('locationCode').text()
                            })
                        });
                        add(output);
                    },
                    type:'GET'
                })
            }, 
            select:function(event, ui){
                //set display value for input field
                event.preventDefault();
                $('#location_list').val(ui.item.label);
                // set value of hidden field
                $('#location_id').val(ui.item.value);
            },
            change:function(event, ui){
                //set display value for input field
                event.preventDefault();
                if(ui.item){
                    $('#location_list').val(ui.item.label);
                    // set value of hidden field
                    $('#location_id').val(ui.item.value);
                }else{
                    $('#location_list').val('');
                    // set value of hidden field
                    $('#location_id').val('');
                }
            },
            focus:function(event, ui){
                //set display value for input field
                event.preventDefault();
                if(ui.item){
                    $('#location_list').val(ui.item.label);
                    // set value of hidden field
                    $('#location_id').val(ui.item.value);
                }else{
                    $('#location_list').val('');
                    // set value of hidden field
                    $('#location_id').val('');
                }
            }
        })
    });

</script>

<h1>Receive new inventory</h1>
<br/><br/>
<? echo $this->Form->create('Item'); ?>
<label for="part_list">P/N:</label>
<input id="part_list">
<input id="part_id" name="data[Item][ModelID]" type="hidden" class="required">
<div id="part_summary"></div>
<label for="location_list">Location</label>
<input id="location_list" >
<input id="location_id" name="data[Item][LocationIDCurrent]" type="hidden" class="required">   
<? echo $this->Form->input('Quantity', array('class' => 'required number')); ?>

<?
echo $this->Form->input('TransLineItemUD1', array('label' => 'Revision'));
echo $this->Form->input('TransLineItemUD2', array('label' => 'Lot'));
echo $this->Form->input('TransLineItemUD3', array('label' => 'Batch'));
echo $this->Form->input('TransLineItemUD4', array('label' => 'Purchase Order'));
echo $this->Form->input('TransLineItemUD5', array('label' => 'Supplier'));
echo $this->Form->input('TransLineItemUD6', array('label' => 'Exp Date'));
echo $this->Form->end('Submit');
}
?>