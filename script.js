function expandBar() {
    var x = document.getElementById("_navBar");
    if (x.className === "_nav") {
      x.className += " responsive";
    } else {
      x.className = "_nav";
    }
  };

var state = 0;
var side = document.getElementById("side");
if (side) {
  var sideText = side;
  var sticky = 0;
}

window.onscroll = function () { 
  "use strict";

  var navBar = document.getElementById("top");

  if (state == 0) {
    var th = 25;
  }
  else {
    var th = 0;
  }

  if (window.scrollY > 450){
    // console.log(navBar.classList);
    navBar.classList.add("_top_scroll");
    var state = 1;
  } 
  else {
    // console.log(navBar.classList);
    navBar.classList.remove("_top_scroll");
    var state = 0;
  }
  
  if (side) {
    if (window.pageYOffset > sticky) {
      sideText.style.marginTop = String(window.pageYOffset+15) + "px"
    } else {
      sideText.style.marginTop = "0px"
    }
  }

};



function clickBox(name) {
  bg = document.getElementById("bg");
  bg.style.display = "block";

  x = document.getElementById("project_box");
  x.classList.remove("_project_box_unclick");
  x.classList.add("_project_box_click");

  x.innerHTML = getProjContent(name);
};

function closeBox() {
  bg = document.getElementById("bg");
  bg.style.display = "none";

  x = document.getElementById("project_box");
  x.classList.add("_project_box_unclick");
  x.classList.remove("_project_box_click");
};

function expand(subj) {
  switch (subj) {
    case 'engineering':
      var sub = document.getElementById("_check_eng");
      var box = document.getElementById('engineering');
      if (box.checked) {
        sub.style.display = 'block';
      } else {
        sub.style.display = 'none';
      }
      break;
    case 'business':
      break;
    case 'economics':
      break;
    case 'natsci':
      break;
    case 'natsci':
      break;
  }
};
