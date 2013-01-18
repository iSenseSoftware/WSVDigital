<table>
    <thead>
    <th>Assembly</th>
    <th>Components:</th>
</thead>
<tbody>
    <? foreach ($parts as $key => $part) { ?>
        <?
        if ($key === 'Pager') {
            // do nothing
        } else {
            foreach ($part['Assembly'] as $assyKey => $assembly) {
                ?>
                <tr>
                    <td style="min-width:200px;">
                        <strong>Part ID: </strong><? echo $part['Part']['ModelID']; ?><br/>
                        <strong>P/N: </strong><? echo $part['Part']['ModelCode']; ?><br/>
                        <strong>Rev: </strong><? echo $assyKey; ?><br/>
                        <strong>Name: </strong><? echo $part['Part']['ModelName']; ?><br/>
                        <strong>UoM: </strong><? echo $part['Part']['UOMCode']; ?><br/>
                        <br/>
                        <? echo $this->Html->link('Edit Assembly', array('action'=>'edit', $part['Part']['ModelID'], $assyKey));?>
                        <br/>
                        <br/>
                        <? //echo $this->Html->link('Print Bill of Materials', array('action' => 'generateBom', $part['Part']['ModelID'], $assyKey)); ?>
                        
                    </td>
                    <td>
                        <table>
                            <thead>
                            <th>P/N</th>
                            <th>Name</th>
                            <th>Rev</th>
                            <th>Qty/Assy</th>
                            <th>UoM</th>
                            </thead>
                            <tbody>
                                <? foreach ($assembly as $anAssembly) { ?>
                                    <tr>
                                        <? if($anAssembly['IsAssembly']){?>
                                        <td><a href="#" offset="0" class="assembly" modelId="<? echo $anAssembly['ModelID']; ?>" rev="<? echo $anAssembly['ComponentRevision']; ?>">
                                                <? echo $anAssembly['ModelCode']; ?>
                                            </a>
                                        </td>
                                        <? }else{ ?>
                                        <td>
                                                <? echo $anAssembly['ModelCode']; ?>
                                        </td>
                                        <? } ?>
                                        <td>
                                            <? echo $anAssembly['ModelName']; ?>
                                        </td>
                                        <td>
                                            <? echo $anAssembly['ComponentRevision']; ?>
                                        </td>
                                        <td>
                                            <? echo (float) $anAssembly['QtyPerAssembly']; ?>
                                        </td>
                                        <td>
                                            <? echo $anAssembly['UOMCode']; ?>
                                        </td>
                                    </tr>
                                    <?
                                }
                            
                            ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        <?
            }
        }
    }
    ?>
</tbody>
</table>
<br/>
<? echo $this->JoshPaginateImp->pageLinks($parts['Pager']['total'], $parts['Pager']['current'], 6, 'pageLinks'); ?>