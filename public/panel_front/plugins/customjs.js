;(function($, window){
    
    // init array
    var choosenElems = [];
    
    // get the containers
    var a = $('#chosen_categories').find('.ids'),
        b = $('#chosen_countries').find('.ids'),
        c = $('#chosen_companies').find('.ids');
    
    a.each(function(i, obj){
        choosenElems.push( $(obj).text() );
    });
    
    b.each(function(i, obj){
        choosenElems.push( $(obj).text() );
    });
    
    c.each(function(i, obj){
        choosenElems.push( $(obj).text() );
    });
    
    window.closeThis = function(thiss){
        $(thiss).parent().remove();
    }
    
    // Success Story Add info Button
    $('#signin-form_id').on('click', '.btn-trig', function(){
        
        // cards
        var self = $( this );
        var dataTarget =   $('#'+self.data('target'));
        var dataResponse = $('#'+self.data('response'));
        var dataInputName = self.data('inputn');
        var dataAppendMode = self.data('appendmode');
        
        var selectedText = dataTarget.find(':selected').text();
        var selectedVal  = dataTarget.find(':selected').val();
        
        var flag = 1;
        var appendMode = '';
         
        // no specific input name provided
        if ( dataInputName == undefined || dataInputName == ''){
            flag = 0;
        }
        
        // append mode
        if ( dataAppendMode == undefined || dataAppendMode == ''){
            appendMode = 'm';
        } else {
            appendMode = dataAppendMode;
        }
        
        // version with square braces
        var outInputName = ( flag == 0) ? self.data('target') + '[]' : dataInputName;
        
        // version without square braces
        var outInputName2 = ( flag == 0) ? self.data('target') : dataInputName;
        
        
        console.log( selectedVal );
        
        // some validation
        if(selectedVal == '' || selectedVal == undefined){
            alert('Please Choose a Value'); 
        } else {
            
            if(choosenElems.indexOf( outInputName2+selectedVal ) == -1){
                var b = $("<span class='close-this' data-valToDel='"+outInputName+selectedVal+"'>X</span>"),
                    a = $("<div class='inputs-wrapper'><span>"+selectedText+"</span><input type='text' style='display:none;' name='"+outInputName+"' value='"+selectedVal+"'></div>");
                a.append(b);
                
                if( appendMode == 'm'){
                    dataResponse.append(a);
                } else {
                    
                    if (dataResponse.html() == ''){
                        dataResponse.append(a);
                    } else {
                        dataResponse.html(a);
                    }
                    
                }
                // append element
                
                choosenElems.push(outInputName2+selectedVal);
            } else {
                alert('Duplicated Entry');
            }
            
        }
        
        $('.close-this').on('click', function(){
            
            $(this).parent().remove();
            
            var index = jQuery.inArray( $(this).attr('data-valToDel'), choosenElems);
            
            if ( index > -1 ) {
                
                choosenElems.splice( index, 1 );
                
            }
            
        });
        
        
    });
    
    
    // select categories box filteration
    var allCatsSelectBox = $('#all_cats_select');
    $('#filter_all_cats_select').on('input', function(){
        
        $.ajax({
            
            type: "POST",
            
            data: {
                'title': $(this).val(),
                'values': 'a.id childid, a.title childtitle , b.title parenttitle',
            },
            
            url: "https://logic-host.com/work/alkan5/beta/includes/get_filterd_options.php", 
            
            success: function(result){ 
                
                var result = JSON.parse( result );
                var parent = '';
                allCatsSelectBox.html("");
                // console.log( result.dataNum );
                for(var i=0; i<result.dataNum; i++){
                    parent = (result.data[i].parenttitle == null) ? '' : " > " + result.data[i].parenttitle; 
                    allCatsSelectBox.append("<option value="+result.data[i].childid+">"+result.data[i].childtitle + parent+"</option>");
                }
            
                
            }
            
        });
        
    });
    
    // set the gallery element id to the input
    $('.choosegalleryfilebtn').on('click', function(){
        var fileInput = $(this).attr('data-inputname');
        var mediaUseBtn = $('.media-use-btn').attr('data-inputnameset', fileInput);
        var mediaUnUseBtn = $('.media-unuse-btn').attr('data-inputnameset', fileInput);
    });
    
    $('.media-use-btn').on('click', function(){
        
        var fileInputset = $(this).attr('data-inputnameset'),
            fileInputId = $(this).attr('data-mediafileid');
            
        $('.' + fileInputset).attr('value', fileInputId);
        $('.' + fileInputset + '_badge').html(fileInputId);
    });
    
    $('.media-unuse-btn').on('click', function(){
        
        var fileInputset = $(this).attr('data-inputnameset');
            
        $('.' + fileInputset).attr('value', 0);
        $('.' + fileInputset + '_badge').html(0);
    });
    
    
}(jQuery, window))