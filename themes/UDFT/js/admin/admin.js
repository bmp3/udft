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

    $('.img-control').on( 'click', function( e ) {
        var trg;
        if ( !$(e.target).hasClass('img-control') ) trg = $(e.target).parent(); else trg = $(e.target);

        $(trg).parents('.img-controls-box').find('.img-control').each( function( i, el ) {
           $(el).removeClass('selected');
        });
        $(trg).addClass('selected').parents('.img-controls-box').find('.img-control-receiver').val($(trg).attr('data-value'));

    });

    $('.tab-select').on( 'change', function( e ) {
        var v = $(e.target).val(), trg = null;

        $(e.target).parents('.tab-select-box').find('.tab-select-item').each( function( i, el ) {
            $( el ).removeClass('active');
            if ( v == $(el).attr('data-value') ) {
                trg = $(el);
            }
            if ( trg ) {
                $(trg).addClass('active');
                trg = null;
            }
            $(e.target).parents('.tab-select-box').find('input.ps-input').val($(e.target).val());
        });

    });



    $('.img_change').click(function(e) {

        e.preventDefault();
        var image = wp.media({
            title: 'Upload Image',

            multiple: false
        }).open()
            .on('select', function(e1){
                var viewport = $(e.target).parents('.img_control').find('.img_prv'), url;
                var uploaded_image = image.state().get('selection').first();
                var image_id = uploaded_image.toJSON().id;

                $(e.target).parents('.img_control').find('input').attr( 'value', image_id );
                $(viewport).css( { 'background-image' : 'url(' + uploaded_image.changed.url + ')' } );

            });

    });

    $('.img_remove').click(function(e) {

        e.preventDefault();
        $(e.target).parents('.img_control').find('input').val('');
        $(e.target).parents('.img_control').find('.img_prv').css( { 'background-image' : 'none' } );
        if ( $('.img_control').length > 1 )
            $(e.target).parents('.img_control').detach();

        return false;

    });

    $('.add_img_control').click(function(e) {

        e.preventDefault();
        var el = $(e.target).parents('.imgs_control').find('.img_control')[0];
        el  = $(el).clone(true);
        $(el).appendTo('.imgs_control');
        $(el).find('input').val('');
        $(el).find('.img_prv').css( { 'background-image' : 'none' } );

        return false;

    });

    $('.reset-post-settings').on( 'click', function( e ) {
        $('.ps-reset').val(1);
    });



    $('.tab-nav > .tab-item:nth-child(2) a.tab-a').trigger( 'click' );

});