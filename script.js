function expandBar() {
    var x = document.getElementById("_navBar");
    if (x.className === "nav") {
      x.className += " responsive";
    } else {
      x.className = "nav";
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
  
  if (side) {
    if (window.pageYOffset > sticky) {
      sideText.style.marginTop = String(window.pageYOffset+15) + "px"
    } else {
      sideText.style.marginTop = "0px"
    }
  }

};

function getProjContent(name) {
  switch (name) {
    case 'machine design':
      html = `
        <h1>Machine Design</h1>   
        <div class = "_project_box_content">
        </div>     
        `
      break;
    case 'aerocapture':
      html = `
        <h1>Aerocapture</h1>
        <div class = "_project_box_content">
        <object type="application/pdf" data="pdf/aerocapture.pdf" style = "width: 100%; height: 100%;">
        </div> 
        `
      break;
    case 'feasibility study':
      html = `
        <h1>Feasibility Study</h1>
        <div class = "_project_box_content">
        </div>
          `
      break;
    case 'neural net':
      html = `
        <h1>Neural Network</h1>
        <div class = "_project_box_content">
        </div>
          `
  }

  html = `<span onclick="closeBox()" class="close" title="Close">&times;</span>` + html;
  html += `<p>Extended project detail goes here...</p>`
  return html
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
