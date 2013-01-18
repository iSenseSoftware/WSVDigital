<h1>View Assembly</h1>

<p>
<?php  echo $this->Html->link('Edit Assembly', array('action'=>'edit', $assemblies[0]['Assembly']['TopID'])); ?>
</p>
<strong>Top Assembly Details:</strong><br/>
<div style="border:1px solid black;padding:0.5em;">
    <strong>Part ID: </strong><? echo $assemblies[0]['Assembly']['TopID']; ?><br/>
    <strong>P/N: </strong><? echo $this->Html->link($assemblies[0]['TopAssembly']['ModelCode'], array(
        'action'=>'view', 'controller'=>'parts', $assemblies[0]['TopAssembly']['ModelID']
    )); ?><br/>
    <strong>Name: </strong><? echo $this->Html->link($assemblies[0]['TopAssembly']['ModelName'], array(
        'action'=>'view', 'controller'=>'parts', $assemblies[0]['TopAssembly']['ModelID']
    )); ?><br/>
    <strong>Revision: </strong><? echo $assemblies[0]['Assembly']['TopRevision']; ?><br/>
    <strong>UoM: </strong><? echo $assemblies[0]['TopAssembly']['Uom']['UOMCode']; ?><br/>
</div>
<br/>
<strong>Components: </strong><br/>
<div style="border:1px solid black;padding:0.5em;">
    <table id="components">
        <thead>
        <th>P/N</th>
        <th>Rev</th>
        <th>Name</th>
        <th>Qty/Assy</th>
        <th>UoM</th>
        </thead>
        <tbody>
            <? foreach($assemblies as $assembly){ ?>
            <tr>
                <td><? echo $this->Html->link($assembly['Component']['ModelCode'], array(
                    'action'=>'view', 'controller'=>'parts', $assembly['Component']['ModelID']
                ));?></td>
                <td><? echo $assembly['Assembly']['ComponentRevision'];?></td>
                <td><? echo $this->Html->link($assembly['Component']['ModelName'], array(
                    'action'=>'view', 'controller'=>'parts', $assembly['Component']['ModelID']
                ));?></td>
                <td><? echo (float)$assembly['Assembly']['QtyPerAssembly'];?></td>
                <td><? echo $assembly['Component']['Uom']['UOMCode'];?></td>
            </tr>
            <? } ?>
        </tbody>
    </table>
</div>