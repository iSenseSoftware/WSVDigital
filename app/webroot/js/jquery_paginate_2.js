/* Contains a set of functions for AJAX-driven paginate and sort functions
 * on a dataset
 * each controller that uses this must have a fetchPage action and 
 * corresponding view file which renders a table of the values of interest
 * with links of class 'sorter' and a 'sortField' attribute containing the key value
 * of the column in the TableAlias.field format.
 * 
 * @TODO: Better document this object. 19 Jul 2012
 */
function joshPaginator(userOptions){
    // This is the constructor statement for the joshPaginator object
    this.options = {
        base_url: 'fetchPage/',
        content_id: '#pageContent',
        results_id: '#resultInput',
        paging_id: '#pageLinks',
        search_id: '#queryString',
        sort_direction: 'asc',
        sort_by: 'id',
        result_count: 10
    };
    if($.isPlainObject(userOptions)){
        $.extend(this.options, userOptions);
    }
    this.current_page = 1;
    this.query_string = null;
    this.id = null;
    this.initialized = false;
    this.initialize = function(){
        this.initialized = true;
        var url = this.options.base_url + this.current_page + '/' + this.options.result_count +
                '/' + this.options.sort_by + '/' + this.options.sort_direction + '/' + 
                this.query_string + '/' + this.id;
        $.ajax({
            type:'GET',
            url:url,
            data:{},
            async:false,
            dataType:'html',
            success:this.loadPage  
        });
        // The value of 'this' is assigned to the variable 'self'
        // because when I want to access the object in this scope within the callback
        // function for the click event binding function. The value of 'this' will have changed.
        // this alias allows me to access it.
        var self = this;
        
        

        if(this.options.search_id != null){
        $(this.options.search_id).keypress(function(event){
            // event.which returns 13 when the enter key is pressed while the cursor
            // is inside this field
            if(event.which == 13){
                self.search();
            }
            
        })
        }
        // Bind the search function to each option in the '# of Results' drop-down
        if(this.options.results_id != null){
        $(this.options.results_id + ' option').click(function(){
            self.search();
        }); 
        }
    }
    
    this.search = function(noRefresh){
        // The noRefresh variable determines whether the current page will be reset
        // to page 1
        if(this.options.results_id != null){
            this.options.result_count = $(this.options.results_id).val();
        }
        if(this.options.search_id != null){
            this.query_string = $(this.options.search_id).val();
        }
        // query_string is assigned the value null if it is an empty string.
        // This is to avoid a problem with the way CakePHP parses GET URLS:
        // url/arg1//arg3 is interpretted as url/arg1/arg3 rather than arg1/null/arg3
        // as expected
        if(this.query_string == ''){this.query_string = null;}
        if(!noRefresh || noRefresh == false){this.current_page = 1;}
        var url = this.options.base_url + this.current_page + '/' + this.options.result_count +
                '/' + this.options.sort_by + '/' + this.options.sort_direction + '/' + 
                this.query_string + '/' + this.id;
        $.ajax({
            type:'GET',
            url:url,
            async:false,
            data:{},
            dataType: 'html',
            success:this.loadPage
        });
    }
    
    this.loadPage = function(html){
        var content_id = this.options.content_id;
        $(content_id).children().remove();
        $(content_id).append(html);
        $(this.options.results_id).val(this.options.result_count);
        // After the page is loaded, bind the appropriate events to the 
        // page links and sortable header links
        this.pageLinks();
        this.sortLinks(); 
    }
    
    this.pageLinks = function(){
        var paging_id = this.options.paging_id;
        var self = this;
        $(paging_id + ' .pageLink').each(function(){
            $(this).click(function(){ 
                self.current_page = $(this).text();
                self.search(true);
            }
        );
        });        
    }
    
    this.sortLinks = function(){

        var self = this;
        $(this.options.content_id + ' .sorter').each(function(){
            $(this).click(function(){
                self.current_page = 1;
                // toggle sort direction
                if(self.options.sort_by == $(this).attr('sortField')){
                    if(self.options.sort_direction == 'asc'){
                        self.options.sort_direction = 'desc';
                    }else{
                        self.options.sort_direction = 'asc';
                    }
                }
                self.options.sort_by = $(this).attr('sortField');
                self.search();
            });
        });        
    }    
}
