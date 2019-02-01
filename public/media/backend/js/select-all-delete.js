$("document").ready(function()
{
$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
});
function afterRequestComplete()
{
    // Ajax loaded Do post back functions
     $(".checkboxes").on("click",function()
    { 
        var flag=1;
        $(".checkboxes").each(function()
        { 
            if(!$(this).is(":checked"))
            {
                flag=0;
            }
            
        });
        
        if(flag===0)
        { 
             //$("#select_all_delete").parent().removeClass("checked");
             $("#select_all_delete").prop("checked",false);
        }else{
            // $("#select_all_delete").parent().addClass("checked");
             $("#select_all_delete").prop("checked",true);
        }
    });
}
    
$('#select_all_delete').change(function()
{

    $(".checkboxes").each(function()
    { 
        if($("#select_all_delete").is(":checked"))
        {  
           $(this).prop("checked",true);
        }else{
          $(this).prop("checked",false);
       }
      
    });
});

function deleteAll(path)
{
       var flag=0;

        $(".checkboxes").each(function()
        {
            if($(this).is(":checked"))
            {
                flag=1;
            }
            
        });
        
        if(flag===0)
        { 
            alert("Please select atleast one record to delete?");
        }else{
            if(confirm("Do you really want to proceed with deleting of selected records?"))
            {
                    $(".checkboxes").each(function()
                    {
                        if($(this).is(":checked")) {
                            $.ajax({
                                url: path + '/' + ($(this).val()),
                                type: 'delete',
                                async: true,
                                'dataType': 'json',
                                success: function (data) {
                                    if (data.success == "1") {


                                    }
                                }
                            });
                        }
                    });
                alert("Selected records has been deleted successfully.");
                window.location.href=window.location.href;
            }
        }
}

function deleteAllAdvertisement(path)
{
    var flag=0;

    $(".checkboxes").each(function()
    {
        if($(this).is(":checked"))
        {
            flag=1;
        }

    });

    if(flag===0)
    {
        alert("Please select atleast one record to delete?");
    }else{
        if(confirm("Do you really want to proceed with deleting of selected records?"))
        {
            var ids = [];
            // $('.mp-progress').show();
            // $('.mp-progress').removeClass('hide');
            // var total_rec = 0;
            // $(".checkboxes").each(function()
            // {
            //     if($(this).is(":checked")!='')
            //     {
            //         total_rec++;
            //     }});
            // var percent = 100/total_rec;
            // var total_perc = 0;
            $(".checkboxes").each(function()
            {
                if($(this).is(":checked")!='')
                {
                    ids.push($(this).val());
                }

            });

            $.ajax({
                // url:path+'/'+($(this).val()),
                url:path+'/',
                method: 'get',
                data: {
                    ids:ids
                },
                async: true,
                'dataType': 'json',
                beforeSend:function(){
                    $('.mp-progress').removeClass('hide');
                },
                success:function(data)
                {
                    if(data.success=="1")
                    {
                        // setTimeout(function(){
                        // total_perc += parseInt(percent);
                        // $('#myBar').html(total_perc + '%');
                        // $('#myBar').width(total_perc + '%');
                        //     if(total_perc >=100 )
                        //     {
                        //         // alert("Selected records has been deleted successfully.");
                        //         window.location.href=window.location.href;
                        //     }
                        // },300);


                    }
                }
            });
            // window.location.href=window.location.href;

            alert("Selected records has been deleted successfully.");
             window.location.href=window.location.href;
        }
    }
}

function showBar(total_perc) {
    $('#myBar').html(total_perc + '%');
    $('#myBar').width(total_perc + '%');
    return true;
}
