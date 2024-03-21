(function ($) {
  $(document).ready(function () {
    // DOM ready
    let captcha_endpoint = "/simple-captcha-endpoint";

    $("#botdetect-captcha").captcha({
      captchaEndpoint: captcha_endpoint,
    });
  });
})(jQuery);
