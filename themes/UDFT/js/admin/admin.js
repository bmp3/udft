jQuery(document).ready( function($) {

    $('a.tab-a').on( 'click', function( e ) {
        var trg = null;
         $(e.target).parents('.tabs-box').find('.tab-content-item').each( function( i, el ) {
             $(el).removeClass('active');
             if ( $(e.target).attr( 'data-trigger' ) == $(el).attr('data-target') ) {
                 trg = $(el);
             }
         });
         if ( trg ) {
             $(trg).addClass('active');
         }
    });

    $('.tab-select').on( 'change', function( e ) {
        var v = $(e.target).val(), trg = null;

        $(e.target).parents('.tab-select-box').find('.tab-select-item').each( function( i, el ) {
            $( e.target ).removeClass('active');
            if ( v == $(el).attr('data-value') ) {
                trg = $(el);
            }
            if ( trg ) {
                $(trg).addClass('active');
            }
        });

    });

    $('a.tab-a:first-child').trigger( 'click' );
});