$(document).ready(function(){
 
    $('#comment_form').on('submit', function(event){
     event.preventDefault();
     var form_data = $(this).serialize();
     $.ajax({
      url:"add_comment.php",
      method:"POST",
      data:form_data,
      dataType:"JSON",
      success:function(data)
      {
       if(data.message != '')
       {
        $('#comment_form')[0].reset();
        $('#comment_message').html(data.message);
        $('#parent_id').val('0');
        load_comment();
       }
      }
     })
    });
   
    load_comment();
   
    function load_comment()
    {
     $.ajax({
      url:"fetch_comment.php",
      method:"POST",
      success:function(data)
      {
       $('#display_comment').html(data);
      }
     })
    }
   
    $(document).on('click', '.reply', function(){
     var comment_id = $(this).attr("id");
     $('#parent_id').val(comment_id);
     $('#comment_name').focus();
    });
    
   });