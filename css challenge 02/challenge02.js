
var x=document.getElementById("one");
var y=document.getElementById("two");
var z=document.getElementById("three");
var click=1;
function myfunction() {
  
    if(click%2!=0){
    move1();  
    
  }
  else if(click%2==0){
    move2();
    
  }
  click=click+1;
}
function move1(){
  x.style.animation = "mymove1 2s forwards 1";
  y.style.animation = "mymove2 2s forwards 1";
  z.style.animation = "mymove3 2s forwards 1";
}
function move2(){
  x.style.animation = "mymove12 2s forwards 1";
  y.style.animation = "mymove22 2s forwards 1";
  z.style.animation = "mymove32 2s forwards 1";
}



// x.addEventListener("webkitAnimationStart", myStartFunction);
// y.addEventListener("webkitAnimationStart", myStartFunction);
// z.addEventListener("webkitAnimationStart", myStartFunction);
// x.addEventListener("animationStart", myStartFunction);
// y.addEventListener("animationStart", myStartFunction);
// z.addEventListener("animationStart", myStartFunction);
// x.addEventListener("webkitAnimationIteration", myIterationFunction);
// x.addEventListener("webkitAnimationEnd", myEndFunction);
// function myStartFunction() {
//   $("#one").addClass("linea1");
//   $("#two").addClass("linea2");
//   $("#three").addClass("linea3");
// }
// function myIterationFunction() {
//     $("#one").addClass("linea1");
// }
// function myEndFunction() {
//    $("#one").addClass("line1");
// }