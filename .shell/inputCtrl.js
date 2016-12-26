$(window).load(function() {
    $("html, body").animate({ scrollTop: $(document).height() }, 0);//1000
});
$(document).ready(function(){

    $('#home').click(function(){
        $('#pwd').val(function(n , c){
            return "/home1/student/stud104/s10410/public_html/shell";
        });
        $('#pspwd').text("~ ");
    });
    $('#chooseUsr').change(function(){
        $('#psUsr').text($('option:selected').text() + $('#psUsr').text().substr($('#psUsr').text().indexOf('@')));
    });
    if($('#cbox').attr('checked')){
        $('#f').attr('autocomplete' , 'on');
    }
    $('#cbox').change(function(){
        if($(this).is(":checked"))
            $('#f').attr('autocomplete' , 'on');
        else
            $('#f').attr('autocomplete' , 'off');
    });
    $('#cursorFix').change(function(){
        if($(this).is(":checked")){//pls identity different between prop , attr
            $('#cursorFix').attr('checked' , true);//set .prop() failed , why?
            $('#cursorFix').attr('value' , 1);
            $('#f').focus();
        }
        else{
            $('#cursorFix').attr('checked' , false);
            $('#cursorFix').attr('value' , 0);
        }
    });
    $('#f').focusout(function(){
        if($('#cursorFix').attr('checked')){
            $('#f').focus();
            //alert("hello");
        }
    });
    $('#f').on('input' , function(){
        $('#pscmd').text($('#f').val());
    });
});

