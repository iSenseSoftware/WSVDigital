<? $baseUrl = 'huswivc0219/hazard_summary.php?MaterialID='; ?>
<table>
    <thead>
    <th>ID</th>
    <th>Name</th>
    <th>P/N</th>
    <th>CH</th>
    <th>H</th>
    <th>F</th>
    <th>P</th>
    <th>PPE</th>
    <th>Location</th>
    <th>MSDS</th>
    </thead>
    <tbody>
        <? foreach($data as $item){ ?>
        <tr>
            <td><a href='<? echo "$baseUrl{$item['MaterialId']}";?>'>
                <? echo $item['MaterialId']; ?>
                </a></td>
            <td><a href='<? echo "$baseUrl{$item['MaterialId']}";?>'>
                <? echo $item['Name'];?>
                </a></td>
            <td><a href='<? echo "$baseUrl{$item['MaterialId']}";?>'>
                <? echo $item['PartNumber']; ?>
                </a></td>
            <td><? echo $item['CH'];?></td>
            <td><? echo $item['H'];?></td>
            <td><? echo $item['F'];?></td>
            <td><? echo $item['P'];?></td>
            <td><? echo $item['PPE'];?></td>
            <td><? echo $item['LocationCode'];?></td>
            <td>
                <? foreach($item['Documents'] as $doc){ ?>
                <a href='<? echo $doc['Hyperlink'];?>'><? echo $doc['DocumentTitle'];?></a>
                <? } ?>
            </td>
        </tr>
        <? } ?>
    </tbody>
</table>