<?
$rgbOffset = ($offset * 10);
if($rgbOffset > 143){
    $rgbOffset = $rgbOffset % 143;
}
$rgb = 255 - $rgbOffset;
$rgb = dechex($rgbOffset);
$rgb = '#5555' . $rgb;
$anchorCount = 0;
?>
<tr class="childOf<? echo $assemblies[0]['TopID']; ?>">
    <td colspan="7" style="border:1px solid black;">
        <table>
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
            foreach ($assemblies as $assembly) {
                $relSum = (isset($assembly['Sums']['REL'])) ? $assembly['Sums']['REL'] : 0;
                $holdSum = (isset($assembly['Sums']['HOLD'])) ? $assembly['Sums']['HOLD'] : 0;
                $inspSum = (isset($assembly['Sums']['INSP'])) ? $assembly['Sums']['INSP'] : 0;
                ?>
                <!--<tr style="<? echo "background-color:rgb($rgb, $rgb, $rgb);"; ?>" class="childOf<? echo $assembly['TopID']; ?>">-->
                <tr>
                    <td>
                        <? if ($assembly['IsAssembly']) { ?>
                            <a href="#childOf<? echo $assemblies[0]['TopID']; ?>Rev<? echo $assembly['ComponentRevision'] . $anchorCount; ?>" name="href="#childOf<? echo $assemblies[0]['TopID']; ?>Rev<? echo $assembly['ComponentRevision'] . $anchorCount; ?>"" offset="<? echo $offset; ?>" class="assembly" modelId="<? echo $assembly['ComponentID']; ?>" rev="<? echo $assembly['ComponentRevision']; ?>">  
                                <? echo $assembly['ModelCode']; ?>
                            </a>
                            <?
                            $anchorCount++;
                        } else {
                            echo $assembly['ModelCode'];
                        }
                        ?>
                    </td>
                    <td>
                        <? echo $assembly['ComponentRevision']; ?>
                    </td>
                    <td>
                        <? echo $assembly['ModelName']; ?>
                    </td>
                    <td>
                        <? echo (float) $relSum; ?>
                    </td>
                    <td>
                        <? echo (float) ($holdSum + $inspSum); ?>
                    </td>
                    <td>
                        <? echo $assembly['UOMCode']; ?>
                    </td>
                    <td>
                        <? echo (float) round($relSum / $assembly['QtyPerAssembly'], 2); ?>
                    </td>
                </tr>
            <? } ?>
        </tbody>
    </table>
</td>
</tr>