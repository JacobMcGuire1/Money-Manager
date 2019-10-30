$(document).ready( function() { 
  var open = false;
  //$(".bill").dblclick(function(){
  $(document).on("dblclick", '.bill', function() { 
    if (open) {
      open = false;
      $( ".moreinfo" ).remove();
    }else{
      open = true;
      var id = $(this).attr("id");
      $.post("getrows.php",
      {
          id: $(this).attr("id")
      },
      function(data,status){
          var item = JSON.parse(data);

          console.log(id);
          $('#' + id).after(item.name);
      });
    }
    
    
    //$(this).after(addition);
    //$(this).after("wdaw");
  });
  
  $(document).on("click", '.paid', function() { 
    var id = $(this).attr("id");
    console.log(id);
    console.log($(this).attr("id"));
    $.get('paybill.php?id=' + id, function(thing) {
      //alert("Server Returned: " + data);
      $.post("updatebills.php",
      {
          //id: $(this).attr("id")
      },
      function(data,status){
          var item = JSON.parse(data);

          //console.log(id);
          //refresh(item);
          $("#main").empty();
          $("#main").html(item.name);
          //console.log(item.name);
      });
    });
    
    
  });
  $(document).on("click", '.delete', function() { 
    //$(".delete").click(function(){
    var id = $(this).attr("id");
    console.log(id);
    console.log($(this).attr("id"));
    $.get('deletebill.php?id=' + id, function(thing) {
      //alert("Server Returned: " + data);
      $.post("updatebills.php",
      {
          //id: $(this).attr("id")
      },
      function(data){
          var item = JSON.parse(data);

          //console.log(id);
          //refresh(item);
          $("#main").empty();
          $("#main").html(item.name);
          console.log(item.name);
      });
    });
  });
  
  $(document).on("input", ".memberbox", function() {
    //var total = 0;
    //$('.memberbox').each(function() {
    //  total += parseFloat($(this).val);
    //});
    var sum = 0;
    $('.memberbox').each(function(){
        //$(this).val.toFixed(2);
        //var value = parseFloat($(this).val()).toFixed(2);
        //$(this).val(value);
        sum += parseFloat($(this).val()); 
    });
    //$("#total").val(sum);
    $("#total").val(sum.toFixed(2));
    //alert("Change to " + this.value);
  });
  
  $(document).on("click", "#divide", function() {
    var value = parseFloat($("#total").val()).toFixed(2);
    $("#total").val(value);
    //$("#total").val().toFixed(2);
    console.log(value);
    var count = 0;
    $('.memberbox').each(function(){
        count++;
        //$(this).val(parseFloat(value / 5).toFixed(2));
        //$(this).val().toFixed(2);
    });
    $('.memberbox').each(function(){
        $(this).val(parseFloat(value / count).toFixed(2));
        //$(this).val().toFixed(2);
    });
  });
  
  /*
  $(document).on("click", "#loginsubmit", function() {
    console.log("dhnioihnnoin");
    $.post("loginvalidation.php",
      {
          email: $("#loginemail").val()
      },
      function(data){
          console.log(data);
          var item = JSON.parse(data);

          //console.log(id);
          //refresh(item);
          //$("#main").empty();
          //$("#main").html(item.name);
          console.log(item.name);
          $("#errormsg").html(item.name);
      });
  })*/
  
 
  /*$(document).on("click", "#submitbill", function() {
  
    $.post("getrows.php",
      {
          id: $(this).attr("id")
          $('.memberbox').each(function(){
            
          });
      },
      function(data,status){

      });
    
    $('.memberbox').each(function(){*/
        


});
/*
 function validateForm() {
    var x = document.forms["loginform"]["email"].value;
    var re = true;
    if (x == "") {
      alert("Email must be filled out");
      console.log(x);
      re = false;
    }
    
    var y = document.forms["loginform"]["user_password"].value;
    if (y == "") {
      alert("Password must be filled out");
      console.log(x);
      re = false;
    }
    //document.errormsg.html("OINNIOIN");
    return re;
    
    
  }
  
  function validateRegister() {
    var x = document.forms["registerform"]["email"].value;
    var re = true;
    if (x == "") {
      alert("Email must be filled out");
      console.log(x);
      re = false;
    }
    
    var y = document.forms["registerform"]["username"].value;
    if (y == "") {
      alert("Username must be filled out");
      console.log(x);
      re = false;
    }
    
    var z = document.forms["registerform"]["user_password"].value;
    if ( == "") {
      alert("Password must be filled out");
      console.log(x);
      re = false;
    }
    //document.errormsg.html("OINNIOIN");
    return re;
    
    
  }*/

