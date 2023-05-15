$(function () {
    'use strict';

    // Dashboard
    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

        if($(this).hasClass('selected')){
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }else{
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }
    });


    //Hide Placeholder on form focus
    $('[placeholder]').focus(function(){
        
        $(this).attr('data-text', $(this).attr('placeholder'));
        
        $(this).attr('placeholder', '');
    
    }).blur(function (){
    
        $(this).attr('placeholder',$(this).attr('data-text'));
    
    });
    
    
    $('input').each(function () {
        
        if ($(this).attr('required') === 'required') {

            $(this).after('<span class="asterisk">*</span>');
        
        }
    
    });
    // Convert Password Filed To Text Field
    

    $('.show-pass').hover(function () {

        $('.password').attr('type', 'text');
        
    },function () {

        $('.password').attr('type', 'password');
    
    });

    // Confirmation Message On Delete Button
    $('.confirm').click(function(){
        return confirm('Are You Sure ?');
    });

    // Category View Option

    $('.cat h3').click(function () {
        $(this).next('.full-view').fadeToggle(300);
    });
    $('.option span').click(function () {
        $(this).addClass('Active').siblings('span').removeClass('Active');
        if(($(this)).data('view')==='full'){
            $('.cat .full-view').fadeIn(200);
        }else{
            $('.cat .full-view').fadeOut(200);
        }
    });
    // Show Delete Button On Child Cat
    $('.child-link').hover(function(){
        $(this).find('.show-delete').fadeIn(400);
    },function(){
        $(this).find('.show-delete').fadeOut(400);
    });
});