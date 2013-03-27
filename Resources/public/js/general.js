$(document).ready(function() {
    $('div.block-link').live("click", function(e){
        // if target is a button, ignore
        if($(e.target).hasClass('btn')) return;
       
        // use href from any link clicked
        if($(e.target).is('a'))
        {
            var href = $(e.target).attr('href');
        }
        // use href from any part of any link clicked
        else if($(e.target).parent().is('a'))
        {
            var href = $(e.target).parent().attr('href');          
        }
        // otherwise use the first href
        else {
            var href = $(this).find('a').first().attr('href');
        }
       
        window.open(href, "_self");
    });
    
    // trigger popovers and tooltips
    // NOTE: we shouldn't have to do this, but the data attributes don't seem to work
    $('[rel="popover"]').popover();
    $('[rel="tooltip"]').tooltip();
    
    $('#signup-submit').live("click touchstart", function(e){
        $(this).button('loading');
    });


    $('select.per-page').change(function(event) {
        window.top.location.href=this.options[this.selectedIndex].value;
    });

    $('.more-filters-btn').live("click", function(e){
        e.preventDefault();
        $('.more-filters-container').show();
        $(this).hide();
        $('.less-filters-btn').show();
    });
    $('.less-filters-btn').live("click", function(e){
        e.preventDefault();
        $('.more-filters-container').hide();
        $("html, body").animate({ scrollTop: 0 }, "fast");
        $(this).hide();
        $('.more-filters-btn').show();
    });


});