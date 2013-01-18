<style>
    table tr:nth-child(even) {
    background: inherit;
}
</style>
<?
$anchorCount = 0;
?>
<table id="mainTable">
    <thead>
    <th>Top</th>
    <th>Components</th>
</thead>
<tbody>
    <?
    foreach ($assemblies as $key => $assembly) {
        if ($key === 'Pager') {
            
        } else {
            foreach ($assembly['Assembly'] as $revision => $anAssembly) {
                // assign variables
                $relSum = (isset($anAssembly['Item']['Sums']['REL'])) ? $anAssembly['Item']['Sums']['REL'] : 0;
                $holdSum = (isset($anAssembly['Item']['Sums']['HOLD'])) ? $anAssembly['Item']['Sums']['HOLD'] : 0;
                $inspSum = (isset($anAssembly['Item']['Sums']['INSP'])) ? $anAssembly['Item']['Sums']['INSP'] : 0;
                $maxOrder = ($assembly['Part']['MaxOrderTo'] != null)?$assembly['Part']['MaxOrderTo']:0;
                $minQty = ($assembly['Part']['MinOnHand'] != null)?$assembly['Part']['MinOnHand']:0;
                ?>
                <tr class="topRow" modelId="<? echo $assembly['Part']['ModelID'];?>" rev="<? echo $revision;?>">
                    <td>
                        <strong>P/N: </strong><? echo $assembly['Part']['ModelCode']; ?><br/>
                        <strong>Rev: </strong><? echo $revision; ?><br/>
                        <strong>Name: </strong><? echo $assembly['Part']['ModelName']; ?><br/>
                        <strong>UoM: </strong><? echo $assembly['Part']['UOMCode']; ?><br/>
                        <br/>
                        <strong>Stock Summary:</strong>
                        <div style="border:1px solid black;padding:0.5em;">
                            <strong>Total Released: </strong><? echo (float) $relSum; ?>
                            <? $holdQty = $holdSum + $inspSum; ?><br/>
                            <strong>Total HOLD/INSP: </strong><? echo $holdQty; ?><br/>
                            <strong>Min on Hand: </strong><? echo $minQty; ?><br/>
                            <strong>Max Order To: </strong><span id='maxFor<? echo $assembly['Part']['ModelID'];?>Rev<? echo $revision;?>' ><? echo $maxOrder; ?></span><br/>
                            <?
                            $reorder = $maxOrder - $relSum;
                            if ($reorder < 0) {
                                $reorder = 0;
                            }
                            ?>
                            <strong>Recommended Re-Order: </strong><span id='reOrderFor<? echo $assembly['Part']['ModelID'];?>Rev<? echo $revision;?>' ><? echo $reorder; ?>
                        </div>
                    </td>
                    <td>
                        <table id="topFor<? echo $assembly['Part']['ModelID'] . 'Rev' . $revision; ?>">
                            <thead>
                            <th>P/N</th>
                            <th>Rev</th>
                            <th>Name</th>
                            <th>Qty REL</th>
                            <th>Qty HOLD/INSP</th>
                            <th>UoM</th>
                            <th># Assemblies</th>
                            </thead>
                            <tbody>
                                <?
                                foreach ($anAssembly as $theKey => $component) {
                                    if ($theKey !== 'Item') {
                                        $componentRelSum = (isset($component['Sums']['REL'])) ? $component['Sums']['REL'] : 0;
                                        $componentHoldSum = (isset($component['Sums']['HOLD'])) ? $component['Sums']['HOLD'] : 0;
                                        $componentInspSum = (isset($component['Sums']['INSP'])) ? $component['Sums']['INSP'] : 0;
                                        ?>
                                        <tr>
                                            <td>
                                                <?
                                                if($component['IsAssembly']) {
                                                    echo "<a href='#top$anchorCount' offset='0' name='top$anchorCount' class='assembly' modelId='{$component['ModelID']}' rev='{$component['ComponentRevision']}'>
                                                    {$component['ModelCode']}</a>";
                                                    $anchorCount++;
                                                } else {
                                                    echo $component['ModelCode'];
                                                }
                                                ?></td>
                                            <td><? echo $component['ComponentRevision']; ?></td>
                                            <td><? echo $component['ModelName']; ?></td>
                                            <td><? echo (float) $componentRelSum; ?></td>
                                            <td><? echo (float) ($componentHoldSum + $componentInspSum); ?></td>
                                            <td><? echo $component['UOMCode']; ?></td>
                                            <td><? echo (float) round(($componentRelSum / $component['QtyPerAssembly']), 2); ?></td>
                                        </tr>
                                    <?
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                            <strong>Sufficient REL Components For: </strong><span class="totalRelAssemblies" id="totalQtyFor<? echo $assembly['Part']['ModelID'] . 'Rev' . $revision;?>"></span><br/>
                    </td>
                </tr>
                <?
            }
        }
    }
    ?>
</tbody>
</table>
<? echo $this->JoshPaginateImp->pageLinks($assemblies['Pager']['total'], $assemblies['Pager']['current'], 6, 'pageLinks'); ?>