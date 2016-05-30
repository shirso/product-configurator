jQuery(function ($) {
    var canvasHeight = 800,
        canvasWidth = 800;
    var canvas = jQuery('#wpc_product_stage').children('canvas').get(0);
    var stage = new fabric.Canvas(canvas, {
        selection: false,
        hoverCursor: 'default',
        rotationCursor: 'default',
        centeredScaling: true
    });
    stage.setWidth($('#wpc_product_stage').width());
    stage.setHeight((canvasHeight * stage.getWidth())/canvasWidth);

    $(window).load(function () {
        $('#attribute-tabs').responsiveTabs({
            rotate: false,
            collapsible: 'accordion',
            activate: function (e, tab) {

            }
        });

        var imgInstance = new fabric.Image($("#wpc_base_images").children('.base_image').get(0), {
            hasControls: false,
            hasBorders: false,
            lockMovementX: true,
            lockMovementY: true,
            lockRotation: true,
            lockScalingX: true,
            lockScalingY: true,
            lockUniScaling: true,
            imgType:'background'
        });
        var imgInstance1 = new fabric.Image($("#wpc_base_images").children('.texture_image').get(0), {
            hasControls: false,
            hasBorders: false,
            lockMovementX: true,
            lockMovementY: true,
            lockRotation: true,
            lockScalingX: true,
            lockScalingY: true,
            lockUniScaling: true,
            imgType:'foreground'
        });
        stage.add(imgInstance);
        stage.add(imgInstance1);
        stage.renderAll().calcOffset();
    });
    $(document).on("click",".wpc_terms",function(){
        $this=$(this);
        if($this.hasClass('atv')){
            return false;
        }
        var attributeName=$this.data("attribute"),
            termId=$this.data("term");
       $this.closest('.attribute_loop').find('button').removeClass('atv');
       $this.addClass('atv');
       if($this.hasClass('wpc_no_cords')){
           $('#wpc_color_tab_'+attributeName).html('');
           $('#wpc_texture_tab_'+attributeName).html('');
       }
       if($this.hasClass('wpc_color_cords')){
         //Load Colors
           $('#wpc_texture_tab_'+attributeName).html("");
           $('#wpc_color_tab_'+attributeName).block({
               message: '',
               overlayCSS: {
                   border: 'none',
                   padding: '0',
                   margin: '0',
                   backgroundColor: 'transparent',
                   opacity: 1,
                   color: '#fff'
               }
           });
           var data = {
               'action': 'wpc_get_color_data',
               'attribute': attributeName,
               'term':termId,
               'model':defaultModel,
               'productId':productId
           };
           $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
               $('#wpc_color_tab_'+attributeName).unblock();
               $('#wpc_color_tab_'+attributeName).html(response);
           });
       }
      if($this.hasClass('wpc_texture_cords')){
          $('#wpc_color_tab_'+attributeName).html("");
          $('#wpc_texture_tab_'+attributeName).block({
              message: '',
              overlayCSS: {
                  border: 'none',
                  padding: '0',
                  margin: '0',
                  backgroundColor: 'transparent',
                  opacity: 1,
                  color: '#fff'
              }
          });
          var data = {
              'action': 'wpc_get_texture_data',
              'attribute': attributeName,
              'term':termId,
              'model':defaultModel,
              'productId':productId
          };
          $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
              $('#wpc_texture_tab_'+attributeName).unblock();
              $('#wpc_texture_tab_'+attributeName).html(response);
          });
      }
     });
});