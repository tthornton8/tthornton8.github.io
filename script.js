function expandBar() {
    var x = document.getElementById("_navBar");
    if (x.className === "nav") {
      x.className += " responsive";
    } else {
      x.className = "nav";
    }
  };

var state = 0;
window.onscroll = function () { 
  "use strict";

  var navBar = document.getElementById("top");

  if (state == 0) {
    var th = 25;
  }
  else {
    var th = 0;
  }

  if (window.scrollY > th){
    // console.log(navBar.classList);
    navBar.classList.add("_top_scroll");
    navBar.classList.remove("_top");
    var state = 1;
  } 
  else {
    // console.log(navBar.classList);
    navBar.classList.add("_top");
    navBar.classList.remove("_top_scroll");
    var state = 0;
  }
};
