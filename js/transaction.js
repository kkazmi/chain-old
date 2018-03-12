
//start button btc usd code
$(document).ready(function () {
    $("#ULlist li").each(function () {
        $(this).find(".btnusd").hide();
    });
    $("#ULlist li").each(function () {
        $(this).find(".btnbtc").click(function () {
            $("#ULlist li").each(function () {
                $(this).find(".btnbtc").hide();
                $(this).find(".btnusd").show();
            });
        })
    });
    $("#ULlist li").each(function () {
        $(this).find(".btnusd").click(function () {
            $("#ULlist li").each(function () {
                $(this).find(".btnusd").hide();
                $(this).find(".btnbtc").show();
            });
        })
    });
});

//End button btc usd code


//start edit description pop up 
$(document).on('click', '.editdetails', function () {
    var hash = $(this).data("recid");
    var type = $(this).data("typedetails");
    $.ajax({
        type: "POST",
        url: "/wallet/checkdescription",
        data: "{hash:'" + $(this).data("recid") + "',type:'" + $(this).data("typedetails") + "'}",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            $("#inputdescription").val(response.msg);
            $("#hiddenid").val(hash);
            $("#hiddentype").val(type);
            $('#panelupdate').openModal();
           
        },
        failure: function (response) {
            //alert(response.responseText);
        },
        error: function (response) {
            //alert(response.responseText);
        }
    });

});
   // End edit description pop up



function showDetails(animal) {
    var animalType = animal.getAttribute("data-animal-type");
    alert("The " + animal.innerHTML + " is a " + animalType + ".");
}

//start update description pop up
$(document).on('click', '#btnsubmitss', function () {
    var desc = $("#inputdescription").val();

    if (desc == '' || desc == undefined || desc == null) {
        $('#inputdescription').attr('style', 'border:2px solid #e08081;');
        $('#inputdescription').focus();
        return;
    }

    $.ajax({
        type: "POST",
        url: "/wallet/updatedescription",
        data: "{txnhash:'" + $("#hiddenid").val() + "',description:'" + $("#inputdescription").val() + "',type:'" + $("#hiddentype").val() + "'}",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            if (response.msg == "Success") {
                alertify.success("Successfully Updated");
                $('.modal-close').trigger('click');
                var url = '/wallet/transactions/';
                $(window).attr("location", url);
               
            }
            
            else {
                alertify.error(response.msg);
            }
        },
        failure: function (response) {
            //alert(response.responseText);
        },
        error: function (response) {
            //alert(response.responseText);
        }
    });

});
    //End update description pop up


//Start For transaction confirmed or pending Code 
    $("#ULlist li").each(function () {
        var type = $(this).find("#txnstatus").html()

        if (parseInt(type) < 3) {
            $(this).find("#checkimg").removeClass("fa-check");
            $(this).find("#checkimg").addClass("fa-exclamation");
            $(this).find("#checkimg").css('color', '#e08081');
            $(this).find("#txnstatus").css('color', '#e08081');
            $(this).find(".pendingwm").css("display", "inline-block");
            
        }
        else {
            $(this).find("#checkimg").css("display","inline");
            $(this).find("#txnstatus").css('color', '#86b45a');
            $(this).find("#checkimg").css('color', '#86b45a');
        }
    })
    //End For transaction confirmed or pending Code


    //Start Sent/Received/Transaferred Code
    $("#ULlist li").each(function () {
        var type = $(this).find("#txntype").html()
        if (type == "Received") {
            //$(this).find("#txntype").css('color', '#8dc429');
            $(this).find("#txntype").html('');
            $(this).find("#status").attr('src', '/img/received.png');
            $(this).find(".btnbtc").css('background', '#86b45a');
            $(this).find(".btnusd").css('background', '#86b45a');
            $(this).find("#tofrom").html('From');
            $(this).find("#txnfees").css("display", "none"); 
            //$(this).find(".editdetails").css("display", "none");
            
        }
        else if (type == "Sent") {
            //$(this).find("#txntype").css('color', '#e84c3d');
            $(this).find("#txntype").html('');
            $(this).find("#status").attr('src', '/img/sent.png');
            $(this).find(".btnbtc").css('background', '#e08081');
            $(this).find(".btnusd").css('background', '#e08081');
            $(this).find("#tofrom").html('To');
        }
        else if (type == "Transferred") {
            //$(this).find("#txntype").css('color', '#ffab00');
            $(this).find("#txntype").html('');
            $(this).find("#status").attr('src', '/img/transferd.png');
            $(this).find(".btnbtc").css('background', 'rgb(236, 180, 66)');
            $(this).find(".btnusd").css('background', 'rgb(236, 180, 66)');
            $(this).find("#tofrom").html('To');
        }
    })
    //End Sent/Received/Transaferred Code
    
//************************  Start Filter Code ************************ 
    $(document).on('click', '#Type_All', function () {
        var typee = $('#Type_All').text();
        $("#Type_Sent").css('font-weight', '');
        $("#Type_Receive").css('font-weight', '');
        $("#Type_Transferred").css('font-weight', '');
        $("#Type_All").css('font-weight', 'Bold');
        $('#ULlist li').show();
    });

    $(document).on('click', '#Type_Sent', function () {
        $("#Type_All").css('font-weight', '');
        $("#Type_Receive").css('font-weight', '');
        $("#Type_Transferred").css('font-weight', '');
        $("#Type_Sent").css('font-weight', 'Bold');
        var typee = $('#Type_Sent').text();
        $('#ULlist li').hide();
        $('#ULlist li.' + typee).show();
    });

    $(document).on('click', '#Type_Receive', function () {
        $("#Type_All").css('font-weight', '');
        $("#Type_Sent").css('font-weight', '');
        $("#Type_Transferred").css('font-weight', '');
        $("#Type_Receive").css('font-weight', 'Bold');
        var typee = $('#Type_Receive').text();
        $('#ULlist li').hide();
        $('#ULlist li.' + typee).show();
    });

    $(document).on('click', '#Type_Transferred', function () {
        $("#Type_All").css('font-weight', '');
        $("#Type_Sent").css('font-weight', '');
        $("#Type_Receive").css('font-weight', '');
        $("#Type_Transferred").css('font-weight', 'Bold');
        var typee = $('#Type_Transferred').text();
        $('#ULlist li').hide();
        $('#ULlist li.' + typee).show();
    });
//************************  End Filter Code ************************ 



//************************  Start Search Code ************************ 

    $("#search").keyup(function () {
        var filter = $(this).val();
        $("#ULlist li").each(function () {
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).hide();
            }
            else {
                $(this).show()
            }
        });
    })

//$("#ULlist li").highlight(filter);
// $("#ULlist li").search(filter).css("font-weight", "bold");
// $("#ULlist li").find(filter).css("font-weight", "bold");

//filter = filter.replace(filter, '<span class="highlight">' + filter + '</span>');                
//filter = $("#ULlist li").clone().find(filter).addClass('highlight').end().html();


//$("#ULlist li:contains('" + filter + "')").each(function (i, element) {
//    var content = $(element).text();
//    alert(content)
//    content = content.replace(filter, '<span class="highlight">' + filter + '</span>');
//    element.html(content);
//});


//************************ End Search Code ************************ 



//  ************************  Mobile Search view  *******************


    var windowWidth = $(window).width();
    if (windowWidth < 768) {
        // Do stuff here
        $(".fa-search").click(function () {
            var filter = $('#searchsmall').val();
            $("#ULlist li").each(function () {
                if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                    $(this).hide();
                }
                else {
                    $(this).show()
                }
            });
        })
    }
        

    $(document).on('click', '#Selectmenu', function () {
        $("#Selectul").slideToggle();
    });
    $('#Selectul').hide();
    $(document).on('click', '#Selectul li a span', function () {
        $('#Selectmenu').val($(this).text());
        $('#Selectul').hide();
    });
    $(".no18").hide();
    $(document).on('click', '.mn12', function () {
        $(".no18").slideToggle();
    });
    
    // ----------------------------------- download entries PopUp -------------------------------------------------//

    $(document).ready(function () {
        $(document).on('click', '#OpenDownloadModal', function () {
            $("#DownloadEntries").openModal();
        });
    });
