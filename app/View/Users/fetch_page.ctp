<table>
  <thead>
    <tr>

    </tr>
  </thead>
  <tbody>
    <? foreach($results as $key=>$result){ 
        if($key !== 'Pager'){
        ?>
    <tr>

    </tr>
    <? }
    } ?>
  </tbody>
</table>

<? echo $this->JoshPaginate->pageLinks($results['Pager']['total'], $results['Pager']['current'], 2, $data['pagingId']); ?>


