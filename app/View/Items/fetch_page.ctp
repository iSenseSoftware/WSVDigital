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
    <th colspan="2">Actions</th>
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
                    case 'ModelCode':
                        if (isset($allFields['ModelID'])) {
                            echo '<td>' . $this->Html->link($item[0][$splitField[1]], array(
                                'action' => 'view', 'controller' => 'parts', $item[0]['ModelID']
                            )) . '</td>';
                        } else {
                            echo '<td>' . $item[0][$splitField[1]] . '</td>';
                        }
                        break;
                    case 'LocationCode':
                        if (isset($allFields['LocationID'])) {
                            echo '<td>' . $this->Html->link($item[0][$splitField[1]], array(
                                'action' => 'view', 'controller' => 'locations', $item[0]['LocationID']
                            )) . '</td>';
                        } else {
                            echo '<td>' . $item[0][$splitField[1]] . '</td>';
                        }
                        break;
                    Case 'Quantity':
                        echo '<td>' . (float) $item[0][$splitField[1]] . '</td>';
                        break;
                    Case 'ItemCode':
                        if (isset($allFields['ItemID'])) {
                            echo '<td>' . $this->Html->link($item[0][$splitField[1]], array(
                                'action' => 'view', 'controller' => 'items', $item[0]['ItemID']
                            )) . '</td>';
                        } else {
                            echo '<td>' . $item[0][$splitField[1]] . '</td>';
                        }
                        break;
                    Case 'TransLineItemUD6':
                    if (strtotime($item[0]['TransLineItemUD6']) == 0) {
                        echo '<td>N/A</td>';
                    } else {
                        if (strtotime($item[0]['TransLineItemUD6']) < Time()) {
                            echo "<td><span class='expired'>" . date('M Y', strtotime($item[0]['TransLineItemUD6'])) . '</span></td>';
                        } else {
                            echo '<td>' . date('M Y', strtotime($item[0]['TransLineItemUD6'])) . '</td>';
                        }
                    }
                    break;
                    default:
                        echo '<td>' . $item[0][$splitField[1]] . '</td>';
                        break;
                }
                ?>


            <? } ?>
            <td>
                <?
                if ($this->Session->read('User.canIssue') == true) {
                    echo $this->Html->link('Issue', array('action' => 'issue', $item[0]['ItemID']));
                    echo '&nbsp;&nbsp;&nbsp;';
                    echo $this->Html->link('Move', array('action' => 'move', $item[0]['ItemID']));
                    echo '&nbsp;&nbsp;&nbsp;';
                    echo $this->Html->link('Adjust', array('action' => 'adjust', $item[0]['ItemID']));
                } else {
                    echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login'));
                }
                echo '&nbsp;&nbsp;&nbsp;';
                echo $this->Html->link('View', array('action' => 'view', $item[0]['ItemID']));
                ?>
            </td>
        </tr>
        <?
    }
    ?>
</tbody>
</table>

<? echo $this->JoshPaginateImp->pageLinks($pager['total'], $pager['current'], 6); 
	//$this->log('Complete' . ' ' . $items[0][0]['ItemID']);
?>
