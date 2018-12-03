//function designerCall(){
//	alert("设计师电话：1323456789！")
//}
// $(function() {
//  $( "#dialog" ).dialog();
//});

 $(function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
 
    $( "#opener" ).click(function() {
      $( "#dialog" ).dialog( "open" );
    });
  });
