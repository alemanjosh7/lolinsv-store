document.addEventListener("DOMContentLoaded", function () {
  var instances = M.Sidenav.init(document.querySelectorAll(".sidenav"));

  M.FloatingActionButton.init(document.querySelectorAll('.fixed-action-btn'))

});



var hastatop = document.getElementById('hasta_arriba');

window.onscroll = function(){

    if(document.documentElement.scrollTop >100){

        hastatop.style.display = "block";

    }else{

        hastatop.style.display = "none";

    }

};



hastatop.addEventListener('click', function(){

    window.scrollTo({

        top: 0,

        behavior: "smooth"

    })

});