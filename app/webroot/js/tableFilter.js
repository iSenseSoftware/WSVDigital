/**
 * tableFilter.js
 * Author: Joshua McKenzie
 * Date: 27 Jan 2012
 * 
 * This file contains a set of functions for performing server side search and filtering
 * operations on an html table.  Filter values are taken from a text field
 * and rows without matching entries are hidden.
 * 
 */

function tableToArray(tableId){
  // output variable declaration
  var outputArray = Array();
  var counter = 1;
  var id = '#' + tableId + ' tr';
  // Grab each row and iterate through the columns
  $(id).each(function(){
    // declare the string variable to which the text of each cell will be appended
    var singleRowString = '';
    $(this).find('td').each(function(){
      // check to see if the cell contains html tags other than text.  If so, append the innerHTML from the child to the string
      if(this.childNodes.length > 0){
              var childOne = this.childNodes[0];
              //  Checks to see if the child is a text node
              if(childOne.nodeType == 1){
                // if so, decode html entities (to plain text) and append
                singleRowString += " " + Encoder.htmlDecode(childOne.innerHTML);
              } else {
                singleRowString += " " + Encoder.htmlDecode(this.innerHTML);
              }
              
            } else{
              if(this.innerHTML){
                singleRowString += " " + Encoder.htmlDecode(this.innerHTML);
              } else {
                singleRowString += " ";
              }
            }
    }
  );
    outputArray.push(singleRowString);
  });

  return outputArray;
}

function applyFilter(tableId, needle, tableArray){
  //  Function take the given string and searches each row of the given table for the string
  //  and hides all rows which do not contain it
  //var tableArray = tableToArray(tableId);
  var id = '#' + tableId + " tr";
    $(id).each(function(index){
    if(index == 0){
      // do nothing
    } else {
      haystack = tableArray[index].toLowerCase();
      theNeedle = needle.toLowerCase();
      if(haystack.indexOf(theNeedle) == -1){
        $(this).hide();
      } else {
        $(this).show();
      }
    }
  })
  
  
}

function clearFilter(tableId, inputBox){
  var BoxId = '#' + inputBox;
  $(BoxId).val('');
  var rowsToClear = '#' + tableId + ' tr';
  $(rowsToClear).each(function(){
    $(this).show();
  });
  
}