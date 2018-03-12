//Start Default JS
$(function () {
    ol();
    var defaultColor = $('.default-background').css('background-color'),
        firstComplementary = $('.first-complementary-bg').css('background-color'),
        secondComplementary = $('.second-complementary-bg').css('background-color'),
        thirdComplementary = $('.third-complementary-bg').css('background-color'),
        chart1 = $('#small-chart1'),
        chart2 = $('#small-chart2'),
        chart3 = $('#small-chart3'),
        chart4 = $('#small-chart4')
    ;
    $('body').on('classChange', function () {
        //console.log('class change');
        updateChartsColors();
        showDistanceChart();
        showYearlyChart();
    });
    $('#myTable').DataTable();
    function colorLuminance(hex, lum) {
        if (hex.indexOf('#') == 0) {
            // validate hex string
            hex = String(hex).replace(/[^0-9a-f]/gi, '');
            if (hex.length < 6) {
                hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
            }
            lum = lum || 0;
            // convert to decimal and change luminosity
            var rgb = "#", c, i;
            for (i = 0; i < 3; i++) {
                c = parseInt(hex.substr(i * 2, 2), 16);
                c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
                rgb += ("00" + c).substr(c.length);
            }

            return rgb;
        } else {
            return 'rgba(' + hex.replace('rgb(', '').replace(')', '') + ',' + (1 + lum) + ')';
        }
    }
    function updateChartsColors() {
        defaultColor = $('.default-background').css('background-color');
        firstComplementary = $('.first-complementary-bg').css('background-color');
        secondComplementary = $('.second-complementary-bg').css('background-color');
        thirdComplementary = $('.third-complementary-bg').css('background-color');
        chart1.css('background', 'linear-gradient(to right, ' + firstComplementary + ' 50%, ' + colorLuminance(firstComplementary, -0.2) + ' 50%)');
        chart2.css('background', 'linear-gradient(to right, ' + secondComplementary + ' 50%, ' + colorLuminance(secondComplementary, -0.2) + ' 50%)');
        chart3.css('background', 'linear-gradient(to right, ' + thirdComplementary + ' 50%, ' + colorLuminance(thirdComplementary, -0.2) + ' 50%)');
        chart4.css('background', 'linear-gradient(to right, ' + defaultColor + ' 50%, ' + colorLuminance(defaultColor, -0.2) + ' 50%)');
    }
    function showDistanceChart() {
        var chartData = [
            {
                "date": "2017-03-23",
                "distance": 227,
                //"townName": "New York",
                //"townName2": "New York",
                //"townSize": 25,
                //"latitude": 40.71,
                //"duration": 408
            },
            {
                "date": "2017-03-22",
                "distance": 371,
            }
                //"townName": "Washington",
                //"townSize": 14,
                //"latitude": 38.89,
                //"duration": 482
           /*,
                    {
                        "date": "2012-01-03",
                        "distance": 433,
                        "townName": "Wilmington",
                        "townSize": 6,
                        "latitude": 34.22,
                        "duration": 562
                    }*/
        ];
        var valueAxes = [];
        if (window.innerWidth > 678) {
            valueAxes = valueAxes.concat([
                {
                    id: "a1",
                    title: "distance",
                    gridAlpha: 0,
                    axisAlpha: 0
                }, {
                    id: "a2",
                    position: "right",
                    gridAlpha: 0,
                    axisAlpha: 0,
                    labelsEnabled: false
                }, {
                    id: "a3",
                    title: "duration",
                    position: "right",
                    gridAlpha: 0,
                    axisAlpha: 0,
                    inside: true,
                    duration: "mm",
                    durationUnits: {
                        DD: "d. ",
                        hh: "h ",
                        mm: "min",
                        ss: ""
                    }
                }]);
            chartData = chartData.concat([
                {
                    "date": "2017-03-21",
                    "distance": 345,
                    //"townName": "Jacksonville",
                    //"townSize": 7,
                    //"latitude": 30.35,
                    //"duration": 379
                },
                {
                    "date": "2017-03-20",
                    "distance": 480,
                    //"townName": "Miami",
                    //"townName2": "Miami",
                    //"townSize": 10,
                    //"latitude": 25.83,
                    //"duration": 501
                },
                {
                    "date": "2017-03-19",
                    "distance": 386,
                    //"townName": "Tallahassee",
                    //"townSize": 7,
                    //"latitude": 30.46,
                    //"duration": 443
                },
                {
                    "date": "2017-03-18",
                    "distance": 348,
                    //"townName": "New Orleans",
                    //"townSize": 10,
                    //"latitude": 29.94,
                    //"duration": 405
                },
                {
                    "date": "2017-03-17",
                    "distance": 238,
                    //"townName": "Houston",
                    //"townName2": "Houston",
                    //"townSize": 16,
                    //"latitude": 29.76,
                    //"duration": 309
                },
                {
                    "date": "2017-03-16",
                    "distance": 218,
                    //"townName": "Dallas",
                    //"townSize": 17,
                    //"latitude": 32.8,
                    //"duration": 287
                },
                {
                    "date": "2017-03-15",
                    "distance": 349,
                    //"townName": "Oklahoma City",
                    //"townSize": 11,
                    //"latitude": 35.49,
                    //"duration": 485
                },
                {
                    "date": "2017-03-14"
                },
                {
                    "date": "2017-03-13"
                }
                //},
                 
                ////{
                ////    "date": "2012-02-12",
                ////    "distance": 456,
                ////},
                ////{
                ////    "date": "2012-03-12",
                ////    "distance": 394,
                ////},
                ////{
                ////    "date": "2012-03-14",
                ////    "distance": 462,
                ////}

            ]);
        }
        AmCharts.makeChart("chartdiv", {
            color: '#ffffff',
            type: "serial",
            dataDateFormat: "YYYY-MM-DD",
            dataProvider: chartData,
            gridColor: '#ffffff',
            addClassNames: true,
            startDuration: 1,
            marginLeft: 0,

            categoryField: "date",
            categoryAxis: {
                axisColor: '#ffffff',
                parseDates: true,
                minPeriod: "DD",
                autoGridCount: false,
                gridCount: 50,
                gridAlpha: 0.3,
                gridColor: '#ffffff',
                dateFormats: [{
                    period: 'DD',
                    format: 'DD'
                }, {
                    period: 'WW',
                    format: 'MMM DD'
                }, {
                    period: 'MM',
                    format: 'MMM'
                }, {
                    period: 'YYYY',
                    format: 'YYYY'
                }]
            },
            valueAxes: valueAxes,
            graphs: [{
                id: "g1",
                valueField: "distance",
                title: "distance",
                type: "column",
                fillAlphas: 0.9,
                valueAxis: "a1",
                balloonText: "[[value]] miles",
                legendValueText: "[[value]] mi",
                alphaField: "alpha",
                fillColors: 'rgba(255,255,255,0.4)',
                lineColor: '#ffffff' //firstComplementary

            }, {
                id: "g2",
                valueField: "latitude",
                classNameField: "bulletClass",
                title: "latitude/city",
                type: "line",
                valueAxis: "a2",
                lineColor: '#DBF8FF', //thirdComplementary,
                color: "#ffffff",
                lineThickness: 1,
                legendValueText: "[[value]]/[[description]]",
                descriptionField: "townName",
                bullet: "round",
                bulletSizeField: "townSize",
                bulletBorderColor: "#cabea9",
                bulletBorderAlpha: 1,
                bulletBorderThickness: 2,
                bulletColor: '#DBF8FF', //thirdComplementary,
                labelText: "[[townName2]]",
                labelPosition: "right",
                balloonText: "latitude:[[value]]",
                showBalloon: true,
                animationPlayed: true
            }, {
                id: "g3",
                title: "duration",
                valueField: "duration",
                type: "line",
                valueAxis: "a3",
                lineColor: '#9CF8FF', // secondComplementary,
                balloonText: "[[value]]",
                lineThickness: 1,
                legendValueText: "[[value]]",
                bullet: "square",
                bulletBorderColor: '#9CF8FF', //secondComplementary,
                bulletBorderThickness: 1,
                bulletBorderAlpha: 1,
                dashLengthField: "dashLength",
                animationPlayed: true
            }],
            chartCursor: {
                zoomable: false,
                categoryBalloonDateFormat: "DD",
                cursorAlpha: 0,
                cursorColor: '#2AF3FF', //firstComplementary,
                valueBalloonsEnabled: false
            },
            legend: {
                color: '#ffffff',
                bulletType: "round",
                equalWidths: true,
                valueWidth: 150,
                useGraphSettings: true
            }
        });
    }
    function showYearlyChart() {
        AmCharts.makeChart("chartdiv2", {
            "color": "#ffffff",
            "type": "serial",
            "theme": "light",
            "categoryField": "year",
            "rotate": true,
            "startDuration": 1,
            "categoryAxis": {
                "axisColor": '#ffffff',
                "gridPosition": "start",
                gridColor: '#ffffff',
                "position": "left"
            },
            "trendLines": [],
            "graphs": [
                {
                    "balloonText": "Income:[[value]]",
                    "fillAlphas": 0.8,
                    "id": "AmGraph-1",
                    "lineAlpha": 0.5,
                    "title": "Income",
                    "type": "column",
                    "valueField": "income",
                    fillColors: 'rgba(255,255,255,0.4)',
                    lineColor: '#ffffff'//firstComplementary
                },
                {
                    "balloonText": "Expenses:[[value]]",
                    "fillAlphas": 0.8,
                    "id": "AmGraph-2",
                    "lineAlpha": 0.5,
                    "title": "Expenses",
                    "type": "column",
                    "valueField": "expenses",
                    fillColors: 'rgba(255,255,255,0.6)',
                    lineColor: '#ffffff'// secondComplementary
                }
            ],
            "guides": [],
            "valueAxes": [
                {
                    "id": "ValueAxis-1",
                    "position": "top",
                    "axisAlpha": 0,
                    gridColor: '#ffffff'
                }
            ],
            "allLabels": [],
            "balloon": {},
            "titles": [],
            "dataProvider": [
                {
                    "year": 2005,
                    "income": 23.5,
                    "expenses": 18.1
                },
                {
                    "year": 2006,
                    "income": 26.2,
                    "expenses": 22.8
                },
                {
                    "year": 2007,
                    "income": 30.1,
                    "expenses": 23.9
                },
                {
                    "year": 2008,
                    "income": 29.5,
                    "expenses": 25.1
                },
                {
                    "year": 2009,
                    "income": 24.6,
                    "expenses": 25
                }
            ],
            "export": {
                "enabled": true
            }
        });
    }
    setTimeout(function () {
        sparkBar('#smc1', [6, 4, 8, 6, 5, 6, 7, 8, 3, 5, 9, 5, 8, 4, 3, 6, 8], '45px', 3, '#fff', 2);
        sparkBar('#smc2', [4, 7, 6, 2, 5, 3, 8, 6, 6, 4, 8, 6, 5, 8, 2, 4, 6], '45px', 3, '#fff', 2);
        sparkLine('#smc3', [9, 4, 6, 5, 6, 4, 5, 7, 9, 3, 6, 5], 85, 45, '#fff', 'rgba(0,0,0,0)', 1.25, 'rgba(255,255,255,0.4)', 'rgba(255,255,255,0.4)', 'rgba(255,255,255,0.4)', 3, '#fff', 'rgba(255,255,255,0.4)');
        sparkLine('#smc4', [5, 6, 3, 9, 7, 5, 4, 6, 5, 6, 4, 9], 85, 45, '#fff', 'rgba(0,0,0,0)', 1.25, 'rgba(255,255,255,0.4)', 'rgba(255,255,255,0.4)', 'rgba(255,255,255,0.4)', 3, '#fff', 'rgba(255,255,255,0.4)');
        updateChartsColors();
        showYearlyChart();
        showDistanceChart();
    }, 500);
    $(window).on('resize', function () {
        showDistanceChart();
    });
    var signupData = {
        names: ["Jonathon Locicero", "Madalyn Shahid", "Millie Brummond", "Loreta Puccio", "Joeann Hairston", "Blanca Hovland", "Joan Outten", "Sierra Shepard", "Jacqueline Mallari", "Joyce Perrino", "Leandra Jarrells", "Santa Groves", "Joselyn Lydick", "Laverna Bogner", "Stanton Newcomb"],
        lastUsedName: 0,
        profileImages: ["/assets/images/profile/128.jpg", "assets/images/profile/129.jpg", "assets/images/profile/130.jpg", "assets/images/profile/131.jpg", "assets/images/profile/blog1.jpg", "assets/images/profile/blog2.jpg", "assets/images/profile/blog3.jpg", "/assets/images/profile/blog4.jpg"],
        lastProfileImage: 0,
        pages: ['New order', 'Lunch at noon', 'Business opp...', 'New order', 'Supplier issue', 'New order', 'Insurance claim'],
        lastPage: 0,
        cities: ["Northfair", "Nashua", "Tripoli", "Kingston", "Orland Park", "Castries", "New Dorp", "Canarsie", "West Covina", "Merced", "Pasadena", "Walker", "Atkinson", "Oranjestad", "Killearn Estates", "Tehran", "Bujumbura", "Dale City", "Mesa", "Kettering", "Mentor", "Missouri City", "Santa Barbara", "Lincoln"],
        lastUsedCity: 0
    };
    function newSignUp(nrOfUsers) {
        nrOfUsers = nrOfUsers || 1;
        setTimeout(function () {
            for (var i = 0; i < nrOfUsers; i++) {
                !(signupData.lastUsedName < signupData.names.length) ? signupData.lastUsedName = 0 : null;
                !(signupData.lastProfileImage < signupData.profileImages.length) ? signupData.lastProfileImage = 0 : null;
                !(signupData.lastUsedCity < signupData.cities.length) ? signupData.lastUsedCity = 0 : null;
                !(signupData.lastPage < signupData.pages.length) ? signupData.lastPage = 0 : null;
                $('#signup-feed2').prepend(
                        '<div class="collection-item avatar new" style="display: none">' +
                                '<img src="' + signupData.profileImages[signupData.lastProfileImage] + '" alt="" class="circle responsive-img">' +
                                '<span class="title">' + signupData.names[signupData.lastUsedName] + '</span>' +
                                '<span class="badge hide-on-small-only"><small>' + signupData.pages[signupData.lastPage] + '</small></span>' +
                                '<p>' + signupData.cities[signupData.lastUsedCity] + ', USA</p>' +
                                '<p class="grey-text hide-on-med-and-up">' + signupData.pages[signupData.lastPage] + '</p>' +
                                '</div>'
                ).find('.new').slideDown('fast');
                signupData.lastUsedName++;
                signupData.lastProfileImage++;
                signupData.lastUsedCity++;
                signupData.lastPage++;
            }
            newSignUp();
        }, nrOfUsers == 1 ? (Math.floor(Math.random() * (4000 - 1500 + 1)) + 1500) : 0);
    }
    newSignUp(10);
});

$(document).ready(function () {
    $('.button-collapse').sideNav({
        menuWidth: 240, // Default is 240
        edge: typeof ThemeSettings != "undefined" && ThemeSettings.getCookie('reading-direction') == 'rtl' ? 'right' : 'left', // Choose the horizontal origin
        closeOnClick: false // Closes side-nav on <a> clicks, useful for Angular/Meteor
    });
    $('.collapsible').collapsible();
});

$('.button-collapse').sideNav({
    menuWidth: 240, // Default is 240
    edge: 'left', // Choose the horizontal origin
    closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
});
$('.collapsible').collapsible();
$('.notif-btn').dropdown({
    inDuration: 300,
    outDuration: 225,
    constrain_width: false, // Does not change width of dropdown to that of the activator
    hover: true, // Activate on hover
    gutter: 0, // Spacing from edge
    belowOrigin: true, // Displays dropdown below the button
    alignment: 'left' // Displays dropdown with edge aligned to the left of button
});
$('.drop-down-profile').dropdown({
    inDuration: 300,
    outDuration: 225,
    constrain_width: false, // Does not change width of dropdown to that of the activator
    hover: true, // Activate on hover
    gutter: 0, // Spacing from edge
    belowOrigin: true, // Displays dropdown below the button
    alignment: 'left' // Displays dropdown with edge aligned to the left of button
});


var countries = [{ value: "Alabama" }, { value: "Alaska" }, { value: "Arizona" }, { value: "Arkansas" }, { value: "California" },
    { value: "Colorado" }, { value: "Connecticut" }, { value: "District of Columbia" }, { value: "Delaware" }, { value: "Florida" },
    { value: "Georgia" }, { value: "Hawaii" }, { value: "Idaho" }, { value: "Indiana" }, { value: "Iowa" }, { value: "Kansas" }, { value: "Kentucky" },
    { value: "Louisiana" }, { value: "Maine" }
];
var input = $('.search-top-bar #search');
if (input.hasClass('autocomplete')) {
    var inputDiv = input.closest('div');
    var searchHtml = '<ul class="autocomplete-results hide">';
    for (var i = 0; i < countries.length; i++) {
        searchHtml += '<li class="result"><span>' + countries[i]['value'] + '</span></li>';
    }
    searchHtml += '</ul>';
    inputDiv.append(searchHtml);
    input.on('keyup', input, function () {
        var $val = input.val().trim(),
                $select = $('.autocomplete-results');
        $select.css('width', input.width());
        if ($val != '') {
            $select.children('li').addClass('hide');
            $select.children('li').filter(function () {
                $select.removeClass('hide');
                var check = true;
                for (var i in $val) {
                    if ($val[i].toLowerCase() !== $(this).text().toLowerCase()[i])
                        check = false;
                }
                return check ? $(this).text().toLowerCase().indexOf($val.toLowerCase()) !== -1 : false;
            }).removeClass('hide');
        } else {
            $select.children('li').addClass('hide');
        }
    });
    $('.result').click(function () {
        input.val($(this).text().trim());
        $('.result').addClass('hide');
    });
    $("#search-hover").click(function (e) {
        $("#dropdown2").hide();
        $("#search").trigger("focus");
    });
}
//End Default JS

//Start get balance and usernanme 
$(document).ready(function () {
   
    mainbalance();
});



function mainbalance() {

    $.ajax({
        type: "POST",
        url: "/wallet/getbalance",
        data: '',
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {          
            if (response.btcbalance != 0 || response.usdbalance != 0) {              
                $("h5[id='lblbtcbalance']").html(response.btcbalance + " BTC");
                $("h6[id='lblusdbalance']").html(response.usdbalance + " USD");
                $("h5[id='lblusdbalance2']").html(response.usdbalance + " USD");
                $("h6[id='lblbtcbalance2']").html(response.btcbalance + " BTC");
                $("h5[id='lblbtcbalancesmall']").html(response.btcbalance + " BTC");
                $("h6[id='lblusdbalancesmall']").html(response.usdbalance + " USD");
                $("h5[id='lblusdbalance2small']").html(response.usdbalance + " USD");
                $("h6[id='lblbtcbalance2small']").html(response.btcbalance + " BTC");
                //$("span[id='lblusername']").html(response.username);
                // $("span[id='lblemail']").html(response.emailid);
                $("span[id='lblliveusd']").html(response.liveusdrate);
            }
            else {             
                $("h5[id='lblbtcbalance']").html("0 BTC");
                $("h6[id='lblusdbalance']").html("0.00 USD");
                $("h5[id='lblusdbalance2']").html("0.00 USD");
                $("h6[id='lblbtcbalance2']").html("0 BTC");
                $("h5[id='lblbtcbalancesmall']").html("0 BTC");
                $("h6[id='lblusdbalancesmall']").html("0.00 USD");
                $("h5[id='lblusdbalance2small']").html("0.00 USD");
                $("h6[id='lblbtcbalance2small']").html("0 BTC");
                //$("span[id='lblusername']").html(response.username);                    
                //$("span[id='lblemail']").html(response.emailid);
                $("span[id='lblliveusd']").html(response.liveusdrate);
            }
        },
        failure: function (response) {
            ////alert(response.responseText);           
        },
        error: function (response) {
            ////alert(response.responseText);
            var url = '/wallet/transactions/';
            $(window).attr("location", url);
        }
    });

}
//End get balance and usernanme 

//for numeric value only 
//function validate(evt) {
//    var theEvent = evt || window.event;
//    var key = theEvent.keyCode || theEvent.which;
//    key = String.fromCharCode(key);
//    var regex = /[0-9]|\./;
//    if (!regex.test(key)) {
//        theEvent.returnValue = false;
//        if (theEvent.preventDefault) theEvent.preventDefault();
//    }
//}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31
      && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

//end numeric value code 


//For received pop up code      //alert('addlist length old : '+ addlist.length);
var jq = window.jQuery;
$(document).on('click', '#btnreceived', function () {

    //$('#btnreceived').css("display", "none");
    //$('#ReceivebtnLoading').css("display", "block");
    //$('#ReceivebtnLoading').prop('disabled', true);
    //$('#ReceivebtnLoading').css("cursor", "not-allowed");

    $.ajax({
        type: "POST",
        url: "/wallet/receivedwallet",
        data: '',
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            if (response.btcaddress != "Invalid Address") {
     
               // alert('addlist length old : '+ addlist.length);

                addlist.push(response.btcaddress);

                //alert('addlist length NEW : ' + addlist.length);

                checkcode(response.btcaddress);
                //{ backdrop: 'static', keyboard: false }
                $("label[id='recnewadd']").html(response.btcaddress);
                //$('#panel').openModal({ backdrop: 'static', keyboard: false });
                $('#panel').openModal();
            }

        },
        failure: function (response) {
           
        },
        error: function (response) {
           
        }
    });
});
function checkcode(getaddess) {
    //var address = 'bitcoin:@TempData["Newaddress"](@Session["Recbtcaddress"])';
    var address = 'bitcoin:' +getaddess ;
    if (address != "") {
        var options = {
            render: 'div',
            ecLevel: 'H',
            minVersion: 10,

            fill: '#000',
            background: null,
            text: address,
            size: 200,
            radius: 0,
            quiet: 4,

            mode: 0,

            mSize: 0.1,
            mPosX: 0.5,
            mPosY: 0.5,


            label: 'bitbots',
            fontname: 'sans',
            fontcolor: '#000',
            image: null
        };
        jq("#qrdiv").html('');
        jq("#qrdiv").qrcode(options);

    }
}
//End received pop up code 

//default code 
var clipboard = new Clipboard('.clippy');
clipboard.on('success', function (e) {
    //console.info('Action:', e.action);
    //console.info('Text:', e.text);
    //console.info('Trigger:', e.trigger);

    e.clearSelection();
});
clipboard.on('error', function (e) {
    console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);
});

$('.collapsible li a').click(function (e) {
    $('.collapsible li').addClass('active');

    var $parent = $(this).parent();
    if (!$parent.hasClass('active')) {
        $parent.addClass('active');
    }
            
});

//for send pop up code 

var usdvalue;
$(document).on('click', '#btnsend', function () {

    $('#fullcam').hide();
    $('#panelsend').css("width", "650px");
    $('#send2ndstep').hide();
    $('#send3rdstep').hide();
    $('#send1ststep').show();
    $('#btcaddress').val('');
    $('#btcval').val('');
    $('#usdval').val('');
    $('#discription').val('');
    $("#lbldiscription").html('');
    $("label[id='lblbtcadd']").html('');
    $("label[id='lblbtc']").html('');
    $("label[id='lblusd']").html('');
    $("#search_field").val('')
    //$('#panelsend').openModal({ backdrop: 'static', keyboard: false });
    $('#btcaddress').prop('disabled', false);
    $('#btcval').prop('disabled', false);
    $('#btcusd').prop('disabled', false);
    $('#discription').prop('disabled', false);
    $('#btnLoadingnext').css("display", "none");
    $('#btnnext').css("display", "block");
    $('#btcaddress').removeAttr('style');
    $('#btcval').removeAttr('style');
    $('#usdval').removeAttr('style');
    $('#useminusfees').hide();
    $('#panelsend').openModal();

});

//
$(document).on('click', '#btnnext', function () {

    $('#btcaddress').removeAttr('style');
    $('#btcval').removeAttr('style');
    $('#usdval').removeAttr('style');

    var btcaddress = $('#btcaddress').val();
    var btcval = $('#btcval').val();
    var btcusd = $('#usdval').val();
    var discription = $('#discription').val();

   
    if (btcaddress == '' || btcaddress == undefined || btcaddress == null) {
        $('#btcaddress').attr('style', 'border-bottom:2px solid #e84c3d;');
        $('#btcaddress').focus();
        return;
    }

    //else if(!validatebtcaddress(btcaddress))
    //{
    //    alertify.error('Invalid BTC Address');
    //    return;
    //}

    else if (btcval == '' || btcval == undefined || btcval == null || btcval == 0) {
        $('#btcval').attr('style', 'border-bottom:2px solid #e84c3d;');
        $('#btcval').focus();
        return;
    }

    else if (btcusd == '' || btcusd == undefined || btcusd == null || btcusd == 0) {
        $('#btcusd').attr('style', 'border-bottom:2px solid #e84c3d;');
        $('#btcusd').focus();
        return;
    }

   // $('#send1ststep').hide();
    //  $('#panelsend').css("width", "880px");

    $('#btcaddress').prop('disabled', true);
    $('#btcval').prop('disabled', true);
    $('#usdval').prop('disabled', true);
    $('#discription').prop('disabled', true);

    $("#lbldiscription").html(discription);
    $("label[id='lblbtcadd']").html(btcaddress);
    $("label[id='lblbtc']").html(btcval + " BTC");
    $("label[id='lblusd']").html(btcusd + " USD");

    $('#btnnext').css("display", "none");
    $('#btnLoadingnext').css("display", "block");
    $('#btnLoadingnext').prop('disabled', true);
    $('#btnLoadingnext').css("cursor", "not-allowed");

    estimatefee();
});

function estimatefee() {
    
    $.ajax({
        type: "POST",
        url: "/wallet/getestimatefee",
        data: "{btcamount:'" + $('#btcval').val() + "'}",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
        
            if (response.msg == "success") {
                $('#btcaddress').prop('disabled', false);
                $('#btcval').prop('disabled', false);
                $('#btcusd').prop('disabled', false);
                $('#discription').prop('disabled', false);

                $('#btnLoadingnext').css("display", "none");
                $('#btnnext').css("display", "block");                
                $('#send1ststep').hide();
                $('#panelsend').css("width", "880px");


                $("#Selectmenu2").val("Medium (" + response.MedVal + " BTC )");

                $("#Selectul2 li a span.highbtc").html("High (" + response.HighVal + " BTC )");
                $("#Selectul2 li a span.mediumbtc").html("Medium (" + response.MedVal + " BTC )");
                $("#Selectul2 li a span.lowbtc").html("Low (" + response.LowVal + " BTC )");                       
          
                var connvertedusd = response.MedVal * response.usdvalue;          
                $("label[id='txnfeeusd']").html(connvertedusd.toFixed(2) + " USD");

                $('#send2ndstep').show();               
            }            
        },
        failure: function (response) {
            //alert(response.responseText);
        },
        error: function (response) {
            //alert(response.responseText);
        }
    });
}

$(document).on('click', '#btnback', function () {
    $('#panelsend').css("width", "650px");
    $('#usdval').prop('disabled', false);
    $('#send1ststep').show();
    $('#send2ndstep').hide();
  
});

$(document).on('click', '#btnsubmit', function () {
    $('#send2ndstep').hide(); 
    $('#search_field').val('');
    $('#send3rdstep').show();
    $('#automatic').html('final transaction');
  
});


function validatebtcaddress(address) {
    var filter = /^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$/;
    if (filter.test(address)) {
        return true;
    }
    else {
        return false;
    }
}
   
 $(document).on('click', '#finalsubmit', function () {            
     $('#automatic').html('final transaction');
     var password = $('#search_field').val();
            if (password == '' || password == undefined || password == null) {
                $('#search_field').attr('style', 'border-bottom:2px solid #e84c3d;');
                $('#search_field').focus();
                return;
            }

            var arr = $("#Selectmenu2").val().split(' (');
            var txntype = arr[0];
            var arrvalue = arr[1].split(' BTC )');
            var txnfeebtcvalue = arrvalue[0];

            $('#finalsubmit').css("display", "none");
            $('#btnLoading').css("display", "block");
            $('#btnLoading').prop('disabled', true);
            $('#btnLoading').css("cursor", "not-allowed");

            $.ajax({
                type: "POST",
                url: "/wallet/sendtransaction",
                data: "{address:'" + $('#btcaddress').val() + "',btcamount:" + $('#btcval').val() + ",password:'" + $('#search_field').val() + "',senddescription:'" + $('#discription').val() + "',transactionfee:'" + txnfeebtcvalue + "'}",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (response) {
                    if (response.msg == "success") {
                       
                        $('#btnLoading').css("display", "none");
                        //$('#send3rdstep').hide();
                        //$('#panelsend').css("width", "450px");
                        //$('#send4step').show();
                        var buzzer = $('#buzzer')[0];
                        buzzer.play();
                        alertify.success("Successfully Sent");
                        var url = '/wallet/transactions/';
                        $(window).attr("location", url);
                    }
                    else if (response.msg == "Invalid Spending Password") {                       
                        $('#search_field').val('');
                        $('#btnLoading').css("display", "none");
                        $('#finalsubmit').css("display", "block");
                        alertify.error(response.msg);
                    }
                    else {                        
                        $('#search_field').val('');
                        $('#btnLoading').css("display", "none");
                        $('#finalsubmit').css("display", "block");
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


 //$(document).on('click', '#final4step', function () {
 //    var url = '/wallet/transactions/';
 //    $(window).attr("location", url);
 //});
//end send pop up code 


//for top btc/usd button code
$(document).ready(function () {       
   
    $("#lblusdbalance2").hide(); 
    $("#lblbtcbalance2").hide();
    $(document).on('click', '#lblbtcbalance', function () {
        $("#lblusdbalance").hide(); //.attr("style", "display:none"); 
        $("#lblbtcbalance").hide(); 
        $("#lblusdbalance2").show(); 
        $("#lblbtcbalance2").show();  
           
    });

    $(document).on('click', '#lblusdbalance', function () {  
        $("#lblusdbalance").hide(); 
        $("#lblbtcbalance").hide();  
        $("#lblusdbalance2").show(); 
        $("#lblbtcbalance2").show();  
                
    });

    $(document).on('click', '#lblbtcbalance2', function () {            
        $("#lblusdbalance").show(); 
        $("#lblbtcbalance").show();  
        $("#lblusdbalance2").hide(); 
        $("#lblbtcbalance2").hide();  
    });


    $(document).on('click', '#lblusdbalance2', function () {         
        $("#lblusdbalance").show(); 
        $("#lblbtcbalance").show(); 
        $("#lblusdbalance2").hide(); 
        $("#lblbtcbalance2").hide();  
    });

});       
//end top btc/usd button code


//for top btc/usd small screen code 
$(document).ready(function () {

    $("#lblusdbalance2small").hide();
    $("#lblbtcbalance2small").hide();
    
    $(document).on('click', '#lblbtcbalancesmall', function () {
        $("#lblusdbalancesmall").hide(); //.attr("style", "display:none");
        $("#lblbtcbalancesmall").hide();
        $("#lblusdbalance2small").show();
        $("#lblbtcbalance2small").show();

    });

    $(document).on('click', '#lblusdbalancesmall', function () {
        $("#lblusdbalancesmall").hide();
        $("#lblbtcbalancesmall").hide();
        $("#lblusdbalance2small").show();
        $("#lblbtcbalance2small").show();

    });

    $(document).on('click', '#lblbtcbalance2small', function () {
        $("#lblusdbalancesmall").show();
        $("#lblbtcbalancesmall").show();
        $("#lblusdbalance2small").hide();
        $("#lblbtcbalance2small").hide();
    });


    $(document).on('click', '#lblusdbalance2small', function () {
        $("#lblusdbalancesmall").show();
        $("#lblbtcbalancesmall").show();
        $("#lblusdbalance2small").hide();
        $("#lblbtcbalance2small").hide();
    });

});
//end top btc/usd smal screen button code


//<!--Enter Button Jquery-->
$(document).keypress(function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
        
        var chk = $('#automatic').html();
      
        if (chk == "submit") {
            $('#backupsubmit').click();
        }
        else if (chk == "next step") {
            $('#BackRecoverNextStep').click();
        }
        else if (chk == "final step") {
            $('#finalstep').click();
        }
        else if (chk == "recovery check") {
            $('#finishsteps').click();
        }
        else if (chk == "finish last step") {
            $('.modal-close').trigger('click');
        }
                   
        else if (chk == "final transaction") {         
            $('#finalsubmit').click();
        }

    }
});
//<!--Enter Button Jquery-->


//<!--start backup phrases-->


$(document).on('click', '#backupbtn', function () {
    $('#backupphrase').css('width', '500px');
    $('#inptrequired').show();
    $('#BackupRecoveryPhrase').hide();
    $('#BackupRecoveryPhrase2').hide();
    $('#BackupRecoveryPhrase3').hide();
    $('#afterfinish').hide();
    $('#backupphrase').openModal();
    $('#txtloginpassword').val('');
    $('#txtloginpassword').removeAttr('style');
    $('#backupsubmit').prop('disabled', false);
    $('#txtloginpassword').prop('disabled', false);
    $('#automatic').html('submit');
});

$(document).on('click', '#finalstep', function () {
    $('#automatic').html('recovery check');
    $('#BackupRecoveryPhrase2').hide();

    $('#txtsecondword').val('');
    $('#txtEleventhword').val('');
    $('#txteightword').val('');
    $('#txtfifthword').val('')
    $('#txtsecondword').removeAttr('style');
    $('#txtEleventhword').removeAttr('style');
    $('#txteightword').removeAttr('style');
    $('#txtfifthword').removeAttr('style');
    $('#BackupRecoveryPhrase3').show();
});

$(document).on('click', '#backrp2', function () {
    $('#automatic').html('next step');
    $('#BackupRecoveryPhrase2').hide();
    $('#BackupRecoveryPhrase').show();
});
$(document).on('click', '#backrp3', function () {
    $('#automatic').html('final step');
    $('#BackupRecoveryPhrase3').hide();
    $('#BackupRecoveryPhrase2').show();
});


$(document).on('click', '#Next4word', function () {
    var slide1 = $('#slide1').attr("class");
    var slide2 = $('#slide2').attr("class");
    if (slide1 == 'current') {
        $('#slide1').hide();
        $('#finalstep').prop('disabled', true);
        $('#finalstep').css('cursor', 'not-allowed');
        $('#Previous4word').show();
        $('#slide1').removeClass('current');
        $('#slide2').addClass('current');
        $('#slide2').show();
    }
    else if (slide2 == 'current') {
        $('#automatic').html('final step');
        $('#slide2').hide();
        $('#Next4word').hide();
        $('#Previous4word').show();
        $('#finalstep').prop('disabled', false);
        $('#finalstep').css('cursor', 'pointer');
        $('#slide2').removeClass('current');
        $('#slide3').addClass('current');

        $('#slide3').show();

    }
});
$(document).on('click', '#Previous4word', function () {
    var slide2 = $('#slide2').attr("class");
    var slide3 = $('#slide3').attr("class");
    if (slide3 == 'current') {
        $('#slide3').hide();
        $('#finalstep').prop('disabled', true);
        $('#finalstep').css('cursor', 'not-allowed');
        $('#slide3').removeClass('current');
        $('#slide2').addClass('current');
        $('#Next4word').show();
        $('#slide2').show();
    }
    else if (slide2 == 'current') {
        $('#slide2').hide();
        $('#slide2').removeClass('current');
        $('#slide1').addClass('current');
        $('#Previous4word').hide();
        $('#Next4word').show();
        $('#slide1').show();

    }
});

//<!--end backup phrases-->



//<!-- Start Backup Phares-->

    $(document).on('click', '#backupsubmit', function () {

        $('#txtloginpassword').removeAttr('style');


        var loginpassword = $('#txtloginpassword').val();

        if (loginpassword == '' || loginpassword == undefined || loginpassword == null) {
            $('#txtloginpassword').attr('style', 'border-bottom:2px solid #e08081;');
            $('#txtloginpassword').focus();
            return;
        }

        $('#txtloginpassword').attr("disabled", "disabled");

        //$('#backupsubmit').attr("disabled", "disabled");
        //$('#backupsubmit').css("cursor", "not-allowed");


        $.ajax({
            type: "POST",
            url: "/wallet/checkloginpassword",
            data: "{password: '" + $('#txtloginpassword').val() + "'}",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (response) {
                if (response.msg == "Invalid Password") {
                    alertify.error(response.msg);
                    $('#txtloginpassword').prop('disabled', false);
                    $('#txtloginpassword').val('');
                    $('#txtloginpassword').removeAttr('style');
                }

                else if (response.msg == "Success") {
                    $('#automatic').html('next step');
                    $('#inptrequired').hide();
                    $('#BackupRecoveryPhrase').show();
                    $('#backupphrase').css('width', '800px');
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


$(document).on('click', '#BackRecoverNextStep', function () {

    secretphrases();
});


function secretphrases() {
    $.ajax({
        type: "POST",
        url: "/wallet/getsecretphrases",
        data: "",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            if (response.msg == "Success") {

                var arr = response.phrases.split(" ");

                $("label[id='word1']").html(arr[0]);
                $("label[id='word2']").html(arr[1]);
                $("label[id='word3']").html(arr[2]);
                $("label[id='word4']").html(arr[3]);
                $("label[id='word5']").html(arr[4]);
                $("label[id='word6']").html(arr[5]);
                $("label[id='word7']").html(arr[6]);
                $("label[id='word8']").html(arr[7]);
                $("label[id='word9']").html(arr[8]);
                $("label[id='word10']").html(arr[9]);
                $("label[id='word11']").html(arr[10]);
                $("label[id='word12']").html(arr[11]);

                $('#Previous4word').hide();
                $('#slide3').hide();
                $('#slide2').hide();
                $('#finalstep').prop('disabled', true);
                $('#finalstep').css('cursor', 'not-allowed');
                $('#slide1').addClass('current');
                $('#slide1').show();
                $('#Next4word').show();
                $('#BackupRecoveryPhrase').hide();
                $('#BackupRecoveryPhrase2').show();
            }


        },
        failure: function (response) {
            //alert(response.responseText);
        },
        error: function (response) {
            //alert(response.responseText);
        }
    });

}


$(document).on('click', '#finishsteps', function () {

    $('#txtsecondword').removeAttr('style');
    $('#txtEleventhword').removeAttr('style');
    $('#txteightword').removeAttr('style');
    $('#txtfifthword').removeAttr('style');

    var secondword = $('#txtsecondword').val();
    var elevenword = $('#txtEleventhword').val();
    var eightword = $('#txteightword').val();
    var fifthword = $('#txtfifthword').val();


    if (secondword == '' || secondword == undefined || secondword == null) {
        $('#txtsecondword').attr('style', 'border-bottom:2px solid #e08081;');
        $('#txtsecondword').focus();
        return;
    }

    else if (elevenword == '' || elevenword == undefined || elevenword == null) {
        $('#txtEleventhword').attr('style', 'border-bottom:2px solid #e08081;');
        $('#txtEleventhword').focus();
        return;
    }

    else if (eightword == '' || eightword == undefined || eightword == null) {
        $('#txteightword').attr('style', 'border-bottom:2px solid #e08081;');
        $('#txteightword').focus();
        return;
    }
    else if (fifthword == '' || fifthword == undefined || fifthword == null) {
        $('#txtfifthword').attr('style', 'border-bottom:2px solid #e08081;');
        $('#txtfifthword').focus();
        return;
    }

    $.ajax({
        type: "POST",
        url: "/wallet/verifysecretphrases",
        data: "{secondword: '" + $('#txtsecondword').val() + "',elevenword: '" + $('#txtEleventhword').val() + "',eightword: '" + $('#txteightword').val() + "',fifthword: '" + $('#txtfifthword').val() + "'}",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            if (response.msg != "Success") {
                alertify.error(response.msg);
               
                $('#txtsecondword').removeAttr('style');
                $('#txtEleventhword').removeAttr('style');
                $('#txteightword').removeAttr('style');
                $('#txtfifthword').removeAttr('style');
            }

            else if (response.msg == "Success") {
                $('#automatic').html('finish last step');
                $('#BackupRecoveryPhrase3').hide();
                $('#afterfinish').show();

            }
        },
        failure: function (response) {
            ////alert(response.responseText);
        },
        error: function (response) {
            ////alert(response.responseText);
        }
    });
});


$(document).on('click', '#finalclosingbackupphrases', function () {
    $('#backupbtn').css("display", "block");
    $('#backupbtn').prop('disabled', true);
    $('#backupbtn').css("cursor", "not-allowed");
    var url = '/wallet/securitycenter/';
    $(window).attr("location", url);
});

//<!-- End Backup Phares coding-->



//<!-- start Received sound code ->



var addlist = [];


if (addlist.length == 0) {
    $.ajax({
        type: "POST",
        url: "/wallet/GetAllReceivedTxn",
        data: "",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            for(var i = 0; i < response.length; i++) {
                addlist.push({
                    add: response[i].Address
                });
            }
           
        },

        failure: function (response) {
       
       },
    error: function (response) {
       
    }
    });
}


//<!-- End Received sound code ->


function chkreceivingtxn(chkaddress) {

    $.each(addlist, function (k, v) {
       
        if (v.add == chkaddress) {
            $('.modal-close').trigger('click');
            buzzer.play();
            mainbalance();
            var url = '/wallet/transactions/';
            $(window).attr("location", url);
        }
    });
  
}

