document.addEventListener("DOMContentLoaded", function () {
  var instances = M.Sidenav.init(document.querySelectorAll(".sidenav"));
});

// Or with jQuery

$(document).ready(function () {
  $(".sidenav").sidenav();
});
