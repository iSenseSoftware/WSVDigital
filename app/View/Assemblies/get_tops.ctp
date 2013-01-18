<ul>
    <? foreach($assemblies as $assembly){ ?>
    <li>
        <? echo $this->Html->link($assembly['TopAssembly']['ModelCode'] . ' Rev ' . 
                $assembly['Assembly']['TopRevision'] . ', ' . $assembly['TopAssembly']['ModelName'],array( 
                'action'=>'view', 'controller'=>'assemblies', $assembly['Assembly']['TopID'], $assembly['Assembly']['TopRevision']));?>
    </li>
    <? } ?>
</ul>
<!--<table>
    <thead>
    <th>Assembly</th>
    <th>Components</th>
</thead>
<tbody>
    <? foreach ($assemblies as $assembly) { ?>
        <tr>
            <td>
                <strong>ID: </strong><? echo $assembly['Assembly']['TopID'];?><br/>
                <strong>P/N: </strong><? echo $assembly['TopAssembly']['ModelCode'];?><br/>
                <strong>Name: </strong><? echo $assembly['TopAssembly']['ModelName']; ?><br/>
                <strong>Rev: </strong><? echo $assembly['Assembly']['TopRevision']; ?><br/>
            </td>
            <td>
                <table>
                    <thead>
                    <th>P/N</th>
                    <th>Rev</th>
                    <th>Name</th>
                    </thead>
                    <tbody>
                        <? foreach($assembly['Component'] as $component) {?>
                        <tr>
                            <td><? echo $component['Component']['ModelCode'];?></td>
                            <td><? echo $component['Assembly']['ComponentRevision'];?></td>
                            <td><? echo $component['Component']['ModelName'];?></td>
                        </tr>
                        <? } ?>
                    </tbody>
                </table>
            </td>
        </tr>
    <? } ?>
</tbody>
</table>-->