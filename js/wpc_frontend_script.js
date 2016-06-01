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
    stage.setBackgroundColor("#cfcfcf");
    var makeCanvasResponsive=function(){
        stage.setWidth($('#wpc_product_stage').width());
        stage.setHeight((canvasHeight * stage.getWidth())/canvasWidth);
    };
    var makeObjectResponsive=function(){
        var stageHeight = stage.getHeight();
        var stageWidth = stage.getWidth();
        var allObjects=stage.getObjects();
        for (var i = 0; i < allObjects.length; i++) {
           // console.log(allObjects[i]);
            var tempScaleY=(1/canvasHeight) * stage.getHeight();
            var tempScaleX=(1/canvasWidth) * stage.getWidth();
            console.log(tempScaleX);
            console.log(tempScaleY);
            allObjects[i].set({scaleX:tempScaleX,scaleY:tempScaleY});
            allObjects[i].setCoords();
        }
        stage.renderAll().calcOffset();
    };

    var loadBaseEdge=function(divId,imageType){
        var imageClasses=['base_image','texture_image'];
        $.each(imageClasses,function(k,v){
            var imgInstance = new fabric.Image($("#"+divId).children('.'+v).get(0), {
                hasControls: false,
                hasBorders: false,
                lockMovementX: true,
                lockMovementY: true,
                lockRotation: true,
                lockScalingX: true,
                lockScalingY: true,
                lockUniScaling: true,
                imageClass:v,
                imageType:imageType
            });

            stage.add(imgInstance);
        });
        stage.renderAll().calcOffset();
    };
    makeCanvasResponsive();
    $(window).load(function () {
        $('#attribute-tabs').responsiveTabs({
            rotate: false,
            collapsible: 'accordion',
            activate: function (e, tab) {

            }
        });

       loadBaseEdge('wpc_base_images','base');
       loadBaseEdge('wpc_edge_images','edge');
       makeObjectResponsive();
    });
    $(window).resize(function () {
        makeCanvasResponsive();
        makeObjectResponsive();
    });
    $(document).on("click",".wpc_terms",function(e){
        e.preventDefault();
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
    $(document).on("click",".change_color",function(e){
        e.preventDefault();

    });
});