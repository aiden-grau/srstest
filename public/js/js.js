window.removeFakeCaptcha = function() {
  console.log('remove');
   document.querySelector('.captcha-fake-field').remove();
}
window.onload = function() {
    var $recaptcha = document.querySelector('.g-recaptcha');

    if($recaptcha) {
        $recaptcha.setAttribute("data-callback", "removeFakeCaptcha");
    }

};
