var $body = $('body');

$('#getRegistrationForm').click(function () {
    $('h1').text('Регистрация');
    $('.logo').css({
        width: '120px',
        height: '120px',
        'border-radius': '60px',
    });
    $('#login-form').fadeOut(200);
    $('#registration-form').delay(300).fadeIn(500);
    $('.other-login-options').fadeOut(200);
    $('.other-registration-options').fadeIn(200);
});

$('#getLoginForm').click(function () {
    $('h1').text('Аутентификация');
    $('.logo').css({
        width: '150px',
        height: '150px',
        'border-radius': '75px',
    });
    $('#registration-form').fadeOut(200);
    $('#login-form').delay(300).fadeIn(500);
    $('.other-registration-options').fadeOut(200);
    $('.other-login-options').fadeIn(200);
});

$('.control-search').click(function () {
    if (!$body.hasClass('mode-modal search')) {
        $body[0].className = 'mode-modal search';
        $('.input-search').focus();
        return;
    }
    $body[0].className = '';
});

$('.control-portfolio').click(function () {
    if (!$body.hasClass('mode-modal portfolio')) {
        $body[0].className = 'mode-modal portfolio';
        return;
    }
    $body[0].className = '';
});

$('.fa-times').click(function () {
    $body[0].className = '';
});
