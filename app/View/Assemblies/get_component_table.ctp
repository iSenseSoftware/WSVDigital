<? if(isset($assemblies)){
   // print_r($assemblies);
    ?>
<tr class="childOf<? echo $assemblies[0]['Assembly']['TopID'];?>">
<td colspan="5" style="border:1px solid black;">
<!--<table style="margin-left:<? echo $offset;?>em;" class="childOf<? echo $assemblies[0]['Assembly']['TopID'];?>">-->
    <table>
    <thead>
    <th>P/N</th>
    <th>Name</th>
    <th>Rev</th>
    <th>Qty</th>
    <th>UoM</th>
    </thead>
    <tbody>
        <? foreach($assemblies as $assembly){ ?>
            <tr>
                <? if($assembly['Component']['IsAssembly']){ ?>
                <td style="margin-left:3em;"><a href="#childOf<? echo $assemblies[0]['Assembly']['TopID'];?>Rev<? echo $assembly['Assembly']['TopRevision'];?>" name="childOf<? echo $assemblies[0]['Assembly']['TopID'];?>Rev<? echo $assembly['Assembly']['TopRevision'];?>" offset="<? echo $offset;?>" class="assembly" modelId="<? echo $assembly['Component']['ModelID'];?>" rev="<? echo $assembly['Assembly']['ComponentRevision'];?>">
                    <? echo $assembly['Component']['ModelCode'];?>
                    </a>
                </td>
                <? }else{ ?>
                <td>
                    <? echo $assembly['Component']['ModelCode'];?>
                </td>
                <? } ?>
                <td>
                    <? echo $assembly['Component']['ModelName'];?>
                </td>
                <td>
                    <? echo $assembly['Assembly']['ComponentRevision'];?>
                </td>
                <td>
                    <? echo (float)$assembly['Assembly']['QtyPerAssembly'];?>
                </td>
                <td>
                    <? echo $assembly['Uom']['UOMCode']; ?>
                </td>
            </tr>
        <? } ?>
    </tbody>
</table>
</td>
</tr>
<? } ?>
