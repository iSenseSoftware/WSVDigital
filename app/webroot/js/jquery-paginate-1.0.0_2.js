/* Contains a set of functions for AJAX-driven paginate and sort functions
 * on a dataset
 * each controller that uses this must have a fetchPage action and 
 * corresponding view file which renders a table of the values of interest
 * with links of class 'sorter' and a 'sortField' attribute containing the key value
 * of the column in the TableAlias.field format.
 * 
 * @TODO: Better document this object. 19 Jul 2012
 * 
 * @version 1.0.0
 */


/*
 *   The structure of the data object passed to the server-side listener:
 *   jObj = {
 *       action: ['index', 'edit', 'add', 'view'],
 *       fields:['fieldAlias1' => 'fieldName1', 'fieldAlias2'=>'fieldName2'],
 *       page: null,
 *       resultCount:null,
 *       sortFields: [
 *           'field' => 'direction'
 *       ],
 *       fullTextQuery: 'query string',
 *       fieldFilters: {
 *           fieldAlias:'filter string'
 *       },
 *       id: null
 *   }
 */
   

function joshPaginator(userOptions){
    // This is the constructor statement for the joshPaginator object
    var self = this
    this.options = {
        baseUrl: 'fetchImpPage/',
        action: 'index',
        contentId: '#pageContent',
        resultsId: '#resultInput',
        pagingId: '#pageLinks',
        selectedColsId: '#selectedCols',
        availableColsId: '#availableCols',
        fullTextSearchId: '#queryString',
        connectClass: '.connectedSortable',
        fieldSearchClass: '.fieldQuery',
        sortDirection: 'asc',
        sortBy: 'id',
        resultCount: 10,
        displayFields:{},
        hiddenFields:{},
        fullTextQuery: '',
        fieldFilters:{}
    };
    if($.isPlainObject(userOptions)){
        $.extend(this.options, userOptions);
    }
    this.requestData = {};
    this.currentPage = 1;
    this.queryString = null;
    this.id = null;
    this.initialized = false;
    this.initialize = function(){
        this.initialized = true;
        // 1. Assemble the data object with default values
        // 2. POST the request to the generic listener.  Load the page returned
        this.populateFieldSelect()
        this.requestData = {
            action: this.options.action,
            page: this.currentPage,
            resultCount:this.options.resultCount,
            sortFields: this.getSortFields(),
            allowZero: 0,
            fullTextQuery: this.options.fullTextQuery,
            id: null,
            displayFields: this.options.displayFields,
            hiddenFields: this.options.hiddenFields,
            fieldFilters: this.options.fieldFilters,
            pagingId: this.options.pagingId
        }
        $.ajax({
            type:'POST',
            url:this.options.baseUrl,
            data:this.requestData,
            async:false,
            dataType:'html',
            success:this.loadPage   
        });
        // 3. check the loaded columns and set columns in options object
        // 4. populate the column lists accordingly
        
        // 5. initialize sortable behavior on column lists
        $( this.options.availableColsId + ", " + this.options.selectedColsId ).sortable({
            connectWith: this.options.connectClass
        }).disableSelection();  
 
        // The value of 'this' is assigned to the variable 'self'
        // because when I want to access the object in this scope within the callback
        // function for the click event binding function. The value of 'this' will have changed.
        // this alias allows me to access it.

        $(this.options.contentId + 'button').click(function(event){
                    self.search();
        })
        $(this.options.contentId).find('.fieldQuery').keypress(function(event){
            if(event.keyCode=='13'){
                self.search();
            }
            
        })

        // 8. Bind the search function to each option in the '# of Results' drop-down
        if(this.options.resultsId != null){
            $(this.options.resultsId + ' option').click(function(){
                self.search();
            }); 
        }
    }
    
    this.getFieldFilters = function(){
        
        var objOut = this.options.fieldFilters
        $(this.options.fieldSearchClass).each(function(){
            if($(this).val() != ''){
                objOut[$(this).attr('searchField')] = 
                $(this).val();
            }
        })
        
        return objOut
    }
    
    this.getSortFields = function(){
        var objOut = {}
        objOut[this.options.sortBy] = this.options.sortDirection
        return objOut
    }
    
    this.search = function(noRefresh){
        // The noRefresh variable determines whether the current page will be reset
        // to page 1
        this.options.fullTextQuery = $(this.options.fullTextSearchId).val();
        if(this.options.resultsId != null){
            this.options.resultCount = $(this.options.resultsId).val();
        }
        if(this.options.fullTextSearchId != null){
            this.queryString = $(this.options.fullTextSearchId).val();
        }
        // query_string is assigned the value null if it is an empty string.
        // This is to avoid a problem with the way CakePHP parses GET URLS:
        // url/arg1//arg3 is interpretted as url/arg1/arg3 rather than arg1/null/arg3
        // as expected
        if(this.queryString == ''){
            this.queryString = null;
        }
        if(!noRefresh || noRefresh == false){
            this.currentPage = 1;
        }
        this.setFields()
        this.requestData = {
            action: this.options.action,
            page: this.currentPage,
            resultCount:this.options.resultCount,
            sortFields: this.getSortFields(),
            fullTextQuery: this.options.fullTextQuery,
            allowZero: 0,
            fieldFilters: this.getFieldFilters(),
            id: null,
            displayFields: this.options.displayFields,
            hiddenFields: this.options.hiddenFields,
            pagingId: this.options.pagingId
        }

        $.ajax({
            type:'POST',
            url:this.options.baseUrl,
            data:this.requestData,
            async:false,
            dataType:'html',
            success:this.loadPage   
        });
    }
    
    this.loadPage = function(html){

        var contentId = self.options.contentId;
        $(contentId).children().remove();
        $(contentId).append(html);
        $(self.options.resultsId).val(self.options.resultCount);
        // After the page is loaded, bind the appropriate events to the 
        // page links and sortable header links
        $(self.options.contentId + 'button').click(function(event){
                    self.search();
        })
        $(self.options.contentId).find('.fieldQuery').keypress(function(event){
            if(event.keyCode=='13'){
                self.search();
            }
            
        })

        // 8. Bind the search function to each option in the '# of Results' drop-down
        if(self.options.resultsId != null){
            $(self.options.resultsId + ' option').click(function(){
                self.search();
            }); 
        }
        self.pageLinks();
        self.sortLinks(); 
    }
    
    this.pageLinks = function(){
        var pagingId = this.options.pagingId;
        var self = this;
        $(pagingId + ' .pageLink').each(function(){
            $(this).click(function(){ 
                self.currentPage = $(this).text();
                self.search(true);
            }
            );
        });        
    }
    
    this.sortLinks = function(){
        var self = this;
        $(this.options.contentId + ' .sorter').each(function(){
            $(this).click(function(){
                self.currentPage = 1;
                // toggle sort direction
                var sortFieldArray = $(this).attr('sortField').split('.')
                var sortField = sortFieldArray[1]
                if(sortField == self.options.sortBy){
                    if(self.options.sortDirection == 'asc'){
                        self.options.sortDirection = 'desc';
                    }else{
                        self.options.sortDirection = 'asc';
                    }
                }
                self.options.sortBy = sortField;
                
                self.search();
            });
        });     
    }

    this.populateFieldSelect = function(){
        var self = this
        $(this.options.availableColsId).children().remove()
        $.each(this.options.hiddenFields, function(index, element){
            $(self.options.availableColsId).append("<li class='ui-state-default' field='" + element + "'>" + index + "</li>")
        })
        $(this.options.selectedColsId).children().remove()
        $.each(this.options.displayFields, function(index, element){
            $(self.options.selectedColsId).append("<li class='ui-state-default' field='" + element + "'>" + index + "</li>")
        })
    }
    
    this.setFields = function(){
        var self = this
        this.options.displayFields = {}
        this.options.hiddenFields = {}
        $(this.options.availableColsId + ' li').each(function(){
            self.options.hiddenFields[$(this).text()] = $(this).attr('field')
        })
        $(this.options.selectedColsId + ' li').each(function(){
            self.options.displayFields[$(this).text()] = $(this).attr('field')
        })
    }
}