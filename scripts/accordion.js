// https://www.w3schools.com/howto/howto_js_accordion.asp
// create collection for accordion elements
var collection = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < collection.length; i++) {
  collection[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}