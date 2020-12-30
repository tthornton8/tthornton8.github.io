function expandBar() {
    var x = document.getElementById("_navBar");
    if (x.className === "nav") {
      x.className += " responsive";
    } else {
      x.className = "nav";
    }
  }