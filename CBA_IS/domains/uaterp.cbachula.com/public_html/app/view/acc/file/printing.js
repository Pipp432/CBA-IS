// JavaScript Document

function Toggle() {
var x = document.getElementById("printing");
var y = document.getElementById("dot_printing");
if (x.style.display === "none") {
	x.style.display = "block";
	y.style.display = "none";
} else {
	y.style.display = "block";
	x.style.display = "none";
 }
}
function Hide() {
var z = document.getElementById("PrintMode");
	z.style.display = "none";
}
function Show() {
var z = document.getElementById("PrintMode");
	z.style.display = "block";
}