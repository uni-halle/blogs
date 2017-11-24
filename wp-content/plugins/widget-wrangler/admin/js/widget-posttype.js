(function($){

  $(document).ready(function(){

    $('#preview-widget').click(function(event){
      event.preventDefault();
      var post_id = $(this).data('postid');
      var items = $('form#post').find('[name^="ww-data"]').serializeArray();
      var ww_data = {};

      $.each(items, function(i, obj){
        var name = obj.name.replace('ww-data[', '');
        ww_data[name] = obj.value;
      });

      if (post_id) {
        $.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'widget_wrangler_preview',
                widget_post_id: post_id,
                form_state: ww_data
            }
        }).done(function(data){
            $('#preview-target').html(data);
        })
      }
    });
  });
})(jQuery);