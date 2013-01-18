<?php

/**
 * JoshPaginateHelper - Used for generating html elements that will be used for
 * AJAX-driven sort, search and pagination functions by the JoshPaginator javascript object
 * 
 * 
 * @author Joshua McKenzie <mckenzie.jw@gmail.com>
 * 
 */
class JoshPaginateImpHelper extends AppHelper {
    /*
     * The sortLink() function creates a link to be rendered within a table header
     * 
     * The JoshPaginator will look for these links and assign a search function
     * to the click event.
     * 
     * @param $alias string The link text
     * @param $keyField string The name of the database field to be sorted on.  
     *                         Expected format: TableName.fieldName
     * 
     * @return string A string containing HTML markup
     */

    var $helpers = array('Html');

    public function listAssemblies($array) {
        $output = '<ul>';
        foreach($array as $element){
            $output .= '<li>';
            $output .= $this->Html->link($element['TopAssembly']['ModelCode'] . ' Rev ' .
                $element['Assembly']['TopRevision'] . ', ' . $element['TopAssembly']['ModelName'], array('action' => 'view', 'controller' => 'assemblies', $element['Assembly']['TopID'], $element['Assembly']['TopRevision']));
            if(isset($element['TopAssembly']['TopAssembly'])){
                $output .= $this->listAssemblies($element['TopAssembly']['TopAssembly']);
            }
        }
        $output .= '</ul>';
        return $output;
    }

    public function sortLink($alias, $keyField, $existingValue = null) {
        $output = "<a href='#' class='sorter' sortField='$keyField'>$alias</a><br/>
        <input class='fieldQuery' id='$alias' searchField='$keyField' value='$existingValue'>";
        return $output;
    }

    /*
     * pageLinks() generates a set of links used for paging by the JoshPaginator
     * javascript object
     * 
     * The JoshPaginator will look for these links and assign a search/paginate function
     * to the click event.
     * 
     * @param $totalPages integer Total pages of content returned by the query
     * @param $currentPage integer Page currently displayed.  Will be text rather than a link
     * @param $padding integer Sets the number of pages to be displayed to the left and right of the current page
     * 
     * 
     * @return string A string containing HTML markup
     */

    public function pageLinks($totalPages, $currentPage, $padding = 6, $id = 'pageLinks') {
        $output = "<span id='$id'>";
        if ($totalPages == 1) {
            return $output;
        }
        $i;
        $start = $currentPage - $padding;
        if ($start <= 0) {
            $start = 1;
        }
        $end = $currentPage + $padding;
        if ($end > $totalPages) {
            $end = $totalPages;
        }

        if ($start > 1) {
            $output .= "&nbsp;&nbsp;&nbsp;<a href='#' class='pageLink'>1</a>&nbsp;&nbsp;&nbsp; ... ";
        }
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $currentPage) {
                $output .= "&nbsp;&nbsp;&nbsp;$i&nbsp;&nbsp;&nbsp;";
            } else {
                $output .= "&nbsp;&nbsp;&nbsp;<a href='#' class='pageLink'>$i</a>&nbsp;&nbsp;&nbsp;";
            }
        }
        if ($end < $totalPages) {
            $output .= " ...&nbsp;&nbsp;&nbsp;<a href='#' class='pageLink'>$totalPages</a>&nbsp;&nbsp;&nbsp;";
        }
        return $output . '</span>';
    }

    /*
     * contentSpace() generates the basic container HTML for the JoshPaginator
     * search and pagination functions to manipulate
     * 
     * contentSpace will generate a main content div into which pages of results
     * are loaded.  Depending on the options provided this will also generate a text input for search
     * and a select element allowing the user to change the number of results shown per page
     * 
     * @param $options 
     * 
     */

    public function contentSpace($options = array()) {
        $defaultOptions = array(
            'searchId' => 'queryString',
            'contentId' => 'pageContent',
            'resultsId' => 'resultInput',
            'availableColsId' => 'availableCols',
            'selectedColsId' => 'selectedCols',
            'connectionClass' => 'connectedSortable',
            'showColumnSelect' => true,
            'showResults' => true,
            'showSearch' => true
        );
        // merge inputted options with defaults, overriding defaults with user preferences
        $options = array_merge($defaultOptions, $options);
        $output = '';

        if ($options['showSearch']) {
            $output .= "<div class='floatLeft'><label for='{$options['searchId']}'>Search (Case-insensitive)</label>
           <input id='{$options['searchId']}' name='{$options['searchId']}' type='text' style='width:15em;height:1em;font-size:12pt;'/></div>";
        }
        $output .= "<div id='{$options['contentId']}'></div>";
        if ($options['showColumnSelect']) {
            $output .= <<<EOL
<style>
    #{$options['availableColsId']}, #{$options['selectedColsId']} { list-style-type: none; margin: 0; padding: 0 0 2.5em; float: left; margin-right: 10px; }
    #{$options['availableColsId']} li, #{$options['selectedColsId']} li { margin: 0 5px 5px 5px; padding: 5px; font-size: .75em; width: 120px; }
    .floatLeft{
        float:left;
        margin-right:4em;
    }
</style>
    <br/><br/>
    <h1>Column Order</h1>
<div class="sortLists floatLeft">  
    <label for='{$options['availableColsId']}'>Hidden</label>
    <ul id="{$options['availableColsId']}" class="{$options['connectionClass']}">

    </ul>
</div>
<div class="sortLists floatLeft">
    <label for='{$options['selectedColsId']}'>Displayed</label>
    <ul id="{$options['selectedColsId']}" class="{$options['connectionClass']}">
    </ul>
</div>

EOL;
        }
        if ($options['showResults']) {
            $output .= "
            <div class='floatLeft'>
<label for='{$options['resultsId']}' >Results per page</label>
<select id='{$options['resultsId']}' style='width:3em;height:1.5em;'>
    <option value='5'>5</option>
    <option value='10'>10</option>
    <option value='25'>25</option>
    <option value='100'>100</option>
</select></div>";
        }
        $output.="<div class='floatLeft'><button type='button' id='{$options['contentId']}button'>Apply</button></div><div style='clear:both;'></div>";
        return $output;
    }

}

?>
