document.addEventListener("DOMContentLoaded", function () {
  var instance = M.Carousel.init({
    fullWidth: true,
  });
  var instances = M.Carousel.init(document.querySelectorAll(".carousel"));
});

// Or with jQuery

$(document).ready(function () {
  $(".carousel").carousel();
});
