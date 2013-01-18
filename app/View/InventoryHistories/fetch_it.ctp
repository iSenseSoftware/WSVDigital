<script>
    $(document).ready(function(){
        $('.expired').parent().parent().css('background-color', 'tomato');
    });
</script>
<?
$pager = $items['Pager'];
unset($items['Pager']);
?>
<table>
    <thead>
        <?
        foreach ($data['displayFields'] as $alias => $field) {
            if (isset($data['fieldFilters'][$field])) {
                echo '<th>' . $this->JoshPaginateImp->sortLink($alias, $field, $data['fieldFilters'][$field]) . '</th>';
            } else {
                echo '<th>' . $this->JoshPaginateImp->sortLink($alias, $field) . '</th>';
            }
        }
        ?>
</thead>
<tbody>
    <?
    $allFields = (isset($data['hiddenFields'])) ? array_merge($data['hiddenFields'], $data['displayFields']) : $data['displayFields'];
    foreach ($items as $key => $item) {
        ?>
        <tr>
            <?
            foreach ($data['displayFields'] as $field) {
                $splitField = explode('.', $field);
                switch ($splitField[1]) {
                    case 'LocationCode':
                        if (isset($allFields['LocationID'])) {
                            echo '<td>' . $this->Html->link($item[$splitField[1]], array(
                                'action' => 'view', 'controller' => 'locations', $item['LocationID']
                            )) . '</td>';
                        } else {
                            echo '<td>' . $item[$splitField[1]] . '</td>';
                        }
                        break;
                    Case 'Quantity':
                        echo '<td>' . (float) $item[$splitField[1]] . '</td>';
                        break;
                    default:
                        echo '<td>' . $item[$splitField[1]] . '</td>';
                        break;
                }
                ?>


            <? } ?>

        </tr>
        <?
    }
    ?>
</tbody>
</table>
<? echo $this->JoshPaginateImp->pageLinks($pager['total'], $pager['current'], 6, 'historyPaging'); ?>
