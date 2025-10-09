
$(document).ready(function () {
    $('.js-sliderWelcome').slick({
        slidesToShow: 1,
        infinite: false,
        arrows: false,
        fade: true,
        dots: true
    });
    $('.js-sliderTeam').slick({
        slidesToShow: 1,
        infinite: false,
        arrows: true,
        fade: true,
        dots: true
    });

    jQuery('.send-from').on('submit', function (e) {
        e.preventDefault();
        var form = jQuery(this);
        var data = {
            name: form.find('input[name="name"]').val(),
            email: form.find('input[name="email"]').val(),
            phone: form.find('input[name="phone"]').val(),
            note: form.find('input[name="note"]').val(),
            subscribe_id: form.find('input[name="subscribe_id"]').val(),
            template_id: template_id,
            task: 'save_order'
        };

        jQuery.ajax({
            url: 'ajax.php',
            method: 'get',
            data: data,
            async: false,
            success: function () {
                form.find('input[name="name"]').val('');
                form.find('input[name="email"]').val('');
                form.find('input[name="phone"]').val('');
                form.find('input[name="note"]').val('');
                jQuery('.popup').fadeOut(300);
                jQuery('.popup.thanks').fadeIn(300);
                //window.open('/kp.pdf');
            }
        });
    });
});






