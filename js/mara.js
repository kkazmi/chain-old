function ol(){
    $('div[data-on-load]').each(function(){
        $(this).load($(this).attr('data-template'));
    });
}
function root (){
    var location = window.location;
    var path = location.pathname + location.hash;
    var el, li, ul, cb, ch,pli;
    var notFirstVisit = typeof ThemeSettings != 'undefined' && ThemeSettings.getCookie('first-visit') == 'no';
    el = $('aside a[href='+'"'+path+'"' + ']'); // vertical navigation
    if( notFirstVisit && el.length > 0 ){
        li = el.parent();
        ul = li.parent();
        cb = ul.parent();
        ch = cb.siblings();
        pli = cb.parent();
        el.addClass('active');
        li.addClass('active');
        if(cb.length >0 && cb[0].localName != 'aside'){
            cb.attr('visit',  el.attr('href'));
            cb.css('display','block');
        }
        if(ch.length >0 && ch[0].localName != 'header'){
            ch.addClass('active');
            ch.attr('visit',  el.attr('href'));
        }
        if(pli.length >0 && pli[0].localName != 'div'){
            pli.addClass('active');
            pli.attr('visit',  el.attr('href'));
        }
    }
    el = $('nav.horizontal-nav a[href='+'"'+path+'"' + ']');  // horizontal navigation
    if( notFirstVisit && el.length > 0 ){
        if ( el.closest('ul.dropdown-content').length > 0 )
            el.closest('ul.dropdown-content').parent('li').addClass('active');
        el.parent('li').addClass('active');
    }
    typeof ThemeSettings != 'undefined' ? ThemeSettings.setCookie('first-visit', 'no') : null; // this and the "notFirstVisit" variable disables highlight for the first visit
}
function sparkBar(s, data, h, bw, bc, bs) {
    $(s).sparkline(data, {
        type: 'bar',
        height: h,
        barWidth: bw,
        barColor: bc,
        barSpacing: bs
    });
}
function sparkLine(s, data, w, h, lc, fc, lw, mxsc, misc, sc, sr, hsc, hlc) {
    $(s).sparkline(data, {
        type: 'line',
        width: w,
        height: h,
        lineColor: lc,
        fillColor: fc,
        lineWidth: lw,
        maxSpotColor: mxsc,
        minSpotColor: misc,
        spotColor: sc,
        spotRadius: sr,
        highlightSpotColor: hsc,
        highlightLineColor: hlc
    });
}

