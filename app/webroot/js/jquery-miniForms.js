(function( $ ) {
	var defaults = {
			plusUrl: '../img/plus.png',
			minusUrl: '../img/minus.png',
			iconSize: '24px'
			
	}
	var methods = {
			init: function(options){
				
			}
			add: function(options){
				var settings = $.extend(defaults, options);
				// Append + link to element
				this.after("<a href='#' id='" + settings.model + "Add'><img src='" + settings.plusUrl + "' height='" + settings.iconSize + "' width='" + settings.iconSize + "' alt='add" + settings.model + "'></a>");
				// Bind click event to link
				$('#' + settings.model + 'Add').click(function(){
					// ajax GET request to formURL
					var x = $(this).offset().left;
					var y = $(this).offset().top;
					$.ajax({
						url: settings.formURL,
						type:'GET',
						dataType: 'html',
						success: function(html){
							$('#content').append(html);
							$('#' + settings.model + settings.action + 'Form').css('top', y)
																				.css('left', x);
							$('#' + settings.model + 'Add').unbind('click')
															.click(function(){
																$('#' + settings.model + settings.action + 'FormWrapper').toggle();
															});
							$('#' + settings.model + settings.action + 'Form').submit(function(){
								if($(this).valid()){
									$.ajax({
										url: settings.formUrl,
										dataType:'html',
										data:$(this).serialize(),
										success:function(html){
											alert('Add Successful');
											$('#' + settings.model + settings.action + 'FormWrapper').hide();
										},
										error: function(object, status, errorThrown){
											alert("Error: " + errorThrown.status + "\r\n" + errorThrown.statusMessage);
										}
									})
								}else{
									
								}
							})
						}
					})
					// append html result to content and set position
					// change + button click event to toggle
						// on toggle, change image
					// set submit() event
						// validate form
						// POST to formURL
						// success:
							// alert user of success
							// clear form values
							// hide form (and swap link image)
						// error:
							// display error message
				})
					
			}	
			edit: function(options){
				var settings = $.extend(defaults, options);
			}
		}
	$.fn.miniForm = function(method){
		// Method calling logic
	    if ( methods[method] ) {
	      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
	    } else if ( typeof method === 'object' || ! method ) {
	      return methods.init.apply( this, arguments );
	    } else {
	      $.error( 'Method ' +  method + ' does not exist on jQuery.miniForm' );
	    }  
	
	}
})(jQuery);