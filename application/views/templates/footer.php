</div>
</body>

<script type="text/javascript">
$(document).ready(function(){
  $('#select_langguage').on('change',function(){
    var lang = $(this).val();
    $.ajax({
      type: 'POST',
      url: '<?php echo site_url("language/changeLanguage/"); ?>' + lang,
      dataType: 'json',
      success: function(response) {
        if(response.code == 1){
          window.location.reload();
        } else if(response.code == 0){
          $.notify(response.message, "danger");
        }
      },
      error: function(err) {
        $.notify('Some error occured, Please try again!', "danger");
      },
    });
  });
});
</script>
</html>
