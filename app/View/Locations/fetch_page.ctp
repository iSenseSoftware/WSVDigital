<table>
    <thead>
        <tr>
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
        </tr>
    </thead>
    <tbody>
        <?
        $allFields = (isset($data['hiddenFields']))?array_merge($data['hiddenFields'], $data['displayFields']):$data['displayFields'];
        foreach ($items as $key => $item) {
            if ($key !== 'Pager') {
                ?>
                <tr>
                    <?
                    foreach ($data['displayFields'] as $field) {
                        $splitField = explode('.', $field);
                        $field = $splitField[1];
                        $model = $splitField[0];
                        ?>
                        <td><?
            switch ($field) {
                case 'LocationCode':
                    if (isset($allFields['LocationID'])) {
                        echo $this->Html->link($item[$model]['LocationCode'], array('controller' => 'locations', 'action' => 'view', $item[$model]['LocationID']));
                    } else {
                        echo $item[$model]['LocationCode'];
                    }
                    break;
                default:
                    echo $item[$model][$field];
                    break;
            }
                        ?></td>
                    <? } ?>
                    <td>
                        <?
                        if ($this->Session->read('User.canAdmin') == true) {
                            echo $this->Html->link('Edit', array('action' => 'edit', $item['Location']['LocationID']));
                            echo '&nbsp;&nbsp;&nbsp;';
                            echo $this->Form->postLink('Delete', array('action' => 'delete', $item['Location']['LocationID']), array(
                                'confirm' => 'Are you sure you wish to delete this location?'
                            ));
                        } else {
                            //echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login'));
                            echo 'N/A';
                        }
                        ?>
                    </td>
                </tr>
                <?
            }
        }
        ?>
    </tbody>
</table>


    <? echo $this->JoshPaginateImp->pageLinks($items['Pager']['total'], $items['Pager']['current'], 6); ?>
