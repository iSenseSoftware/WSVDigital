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
        resultCount: 10,
        displayFields:{},
        hiddenFields:{},
        fullTextQuery: '',
        fieldFilters:{},
        permFilters:{},
        aSync:true,
        callback:null
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
        $(this.options.resultsId).val(this.options.resultCount);
        this.populateFieldSelect();
        this.search();
       
    }
    
    this.getFieldFilters = function(){
        var objOut = {};
        $(this.options.fieldSearchClass).each(function(){
            if($(this).val() != ''){
                objOut[$(this).attr('searchField')] = $(this).val();
            }else{
               
            }
        })
        
        return objOut;
    }
    
    this.getSortFields = function(){
        var objOut = {};
        objOut[this.options.sortBy] = this.options.sortDirection;
        return objOut;
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
        if(this.queryString == ''){
            this.queryString = null;
        }
        if(!noRefresh || noRefresh == false){
            this.currentPage = 1;
        }
        this.setFields();
        this.setRequestData();
        $.ajax({
            cache:false,            type:'POST',
            url:this.options.baseUrl,
            data:this.requestData,
            aSync:this.options.aSync,
            dataType:'html',
            success:this.loadPage   
        });
    }
    
    this.setRequestData = function(){
        this.requestData = {
            action: this.options.action,
            page: this.currentPage,
            resultCount:this.options.resultCount,
            sortFields: this.getSortFields(),
            fullTextQuery: this.options.fullTextQuery,
            allowZero: 0,
            fieldFilters: this.getFieldFilters(),
            id: this.id,
            displayFields: this.options.displayFields,
            hiddenFields: this.options.hiddenFields,
            pagingId: this.options.pagingId,
            permFilters:this.options.permFilters
        };
    }
    
    this.loadPage = function(html){
        var contentId = self.options.contentId;
        $(contentId).children().remove();
        $(contentId).append(html);
        $(self.options.resultsId).val(self.options.resultCount);
        // After the page is loaded, bind the appropriate events to the 
        // page links and sortable header links
        self.setEvents();
        if(self.options.callback != null){
            self.options.callback();          
        }
    }
    
    this.setEvents = function(){
    	
        $( this.options.availableColsId + ", " + this.options.selectedColsId ).sortable({
            connectWith: this.options.connectClass
        }).disableSelection();  
        $(this.options.contentId + 'button').unbind('click');
        $(this.options.contentId + 'button').click(function(event){
            self.search();
        })
        $(this.options.contentId).find('.fieldQuery').unbind('keypress');
        $(this.options.contentId).find('.fieldQuery').keypress(function(event){
            if(event.keyCode=='13'){
                self.search();
            } 
        })
         $(this.options.fullTextSearchId).unbind('keypress');
        $(this.options.fullTextSearchId).keypress(function(event){
            if(event.keyCode=='13'){
                self.search();
            }
        })

        // 8. Bind the search function to each option in the '# of Results' drop-down
        if(this.options.resultsId != null){
             $(this.options.resultsId + ' option').unbind('click');
            $(this.options.resultsId + ' option').click(function(){
                self.search();
            }); 
        }
        self.pageLinks();
        self.sortLinks(); 
    }
    
    this.pageLinks = function(){

        var pagingId = this.options.pagingId;
        $(pagingId + ' .pageLink').each(function(){
            $(this).unbind('click');
            $(this).click(function(){ 
                self.currentPage = $(this).text();
                self.search(true);
            }
            );
        });        
    }
    
    this.sortLinks = function(){
        $(this.options.contentId + ' .sorter').each(function(){
            $(this).unbind('click');
            $(this).click(function(){
                self.currentPage = 1;
                // toggle sort direction
                var sortField = $(this).attr('sortField');
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
        $(this.options.availableColsId).children().remove();
        $.each(this.options.hiddenFields, function(index, element){
            $(self.options.availableColsId).append("<li class='ui-state-default' field='" + element + "'>" + index + "</li>");
        })
        $(this.options.selectedColsId).children().remove();
        $.each(this.options.displayFields, function(index, element){
            $(self.options.selectedColsId).append("<li class='ui-state-default' field='" + element + "'>" + index + "</li>");
        })
    }
    
    this.setFields = function(){
        this.options.displayFields = {};
        this.options.hiddenFields = {};
        $(this.options.availableColsId + ' li').each(function(){
            self.options.hiddenFields[$(this).text()] = $(this).attr('field')
        });
        $(this.options.selectedColsId + ' li').each(function(){
            self.options.displayFields[$(this).text()] = $(this).attr('field')
        });
    }
}