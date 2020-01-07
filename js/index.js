
//Expands a bill to show what each member of the group owes to it and whether they've paid when it is double clicked on.
//Closes the currently opened bill if one is open.
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

  });
  
  //Allows the user to click the "paid" button to toggle whether they've paid it or not.
  $(document).on("click", '.paid', function() { 
    var id = $(this).attr("id");
    console.log(id);
    console.log($(this).attr("id"));
    $.get('paybill.php?id=' + id, function(thing) {

      $.post("updatebills.php",
      {
          //id: $(this).attr("id")
      },
      function(data,status){
          var item = JSON.parse(data);

          $("#main").empty();
          $("#main").html(item.name);

      });
    });  
  });

  //Deletes a bill when the user clicks the delete button.
  $(document).on("click", '.delete', function() { 
    var id = $(this).attr("id");
    console.log(id);
    console.log($(this).attr("id"));
    $.get('deletebill.php?id=' + id, function(thing) {
      $.post("updatebills.php",
      {
          //id: $(this).attr("id")
      },
      function(data){
          var item = JSON.parse(data);

          $("#main").empty();
          $("#main").html(item.name);
          console.log(item.name);
      });
    });
  });
  
  //Shows the total cost of the bill added together as the user adds what each person owes. 
  $(document).on("input", ".memberbox", function() {

    var sum = 0;
    $('.memberbox').each(function(){

        sum += parseFloat($(this).val()); 
    });
    $("#total").val(sum.toFixed(2));
  });
  
  //Allows the user to divide the total cost of the bill evenly across all people who owe money towards it.
  $(document).on("click", "#divide", function() {
    var value = parseFloat($("#total").val()).toFixed(2);
    $("#total").val(value);
    console.log(value);
    var count = 0;
    $('.memberbox').each(function(){
        count++;
    });
    $('.memberbox').each(function(){
        $(this).val(parseFloat(value / count).toFixed(2));

    });
  });

});

