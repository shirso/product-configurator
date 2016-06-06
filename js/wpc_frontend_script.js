jQuery(function ($) {
    var canvasHeight = 800,
        canvasWidth = 800,
        scale= [],
        visitedStep=[],
        cords=[];
    var canvas = jQuery('#wpc_product_stage').children('canvas').get(0);
    var stage = new fabric.Canvas(canvas, {
        selection: false,
        hoverCursor: 'default',
        rotationCursor: 'default',
        centeredScaling: true
    });
   // stage.setBackgroundColor("#cfcfcf");
    var makeCanvasResponsive=function(){
        stage.setWidth($('#wpc_product_stage').width());
        stage.setHeight((canvasHeight * stage.getWidth())/canvasWidth);
    };
    var makeObjectResponsive=function(){
        var allObjects=stage.getObjects();
        for (var i = 0; i < allObjects.length; i++) {
            var tempScaleY=(1/canvasHeight) * stage.getHeight();
            var tempScaleX=(1/canvasWidth) * stage.getWidth();
            if(allObjects[i].imageType=="base_image" && allObjects[i].imageClass=="base_image"){
                //cords= _.without(cords, _.findWhere(cords, {scaleX: tempScaleX,scaleY:}));
                scale=[];
              scale.push({scaleX:tempScaleX,scaleY:tempScaleY});
            }
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
    var colorBaseEdge=function(color,type){
      var allObjects=stage.getObjects();
        for(var i in allObjects){
            if(allObjects[i].imageType==type && allObjects[i].imageClass=='base_image'){
                allObjects[i].filters.push(new fabric.Image.filters.Tint({color: color}))
                allObjects[i].applyFilters(stage.renderAll.bind(stage))
                break;
            }
        }
        stage.renderAll().calcOffset();
    };
    var loadImageData=function(attribute,object){
       // console.log(scale);
        var imageBase=new Image;
        imageBase.src=object.base;
        $(imageBase).load(function(){
            var imgInstance = new fabric.Image(imageBase, {
                hasControls: false,
                hasBorders: false,
                lockMovementX: true,
                lockMovementY: true,
                lockRotation: true,
                lockScalingX: true,
                lockScalingY: true,
                lockUniScaling: true,
                imageClass:"base_image",
                imageType:"cord_images_"+attribute,
                scaleX:scale.scaleX,
                scaleY:scale.scaleY,
            });
            stage.add(imgInstance);
            console.log(scale);
            var imageTexture=new Image;
            imageTexture.src= object.texture;
            $(imageTexture).load(function(){
                var imgInstance1 = new fabric.Image(imageTexture, {
                    hasControls: false,
                    hasBorders: false,
                    lockMovementX: true,
                    lockMovementY: true,
                    lockRotation: true,
                    lockScalingX: true,
                    lockScalingY: true,
                    lockUniScaling: true,
                    imageClass:"texture_image",
                    imageType:"cord_images_"+attribute,
                    scaleX:scale.scaleX,
                    scaleY:scale.scaleY,
                    top:0,
                    left:0
                });
                stage.add(imgInstance1);
            });
       });
        stage.renderAll().calcOffset();
    };
    var loadImagesFromAjax=function(data){
       if(!$.isEmptyObject(data)){
           $.each(data,function(k,v){
             if((typeof v.base !="undefined" &&  v.base!="") && (typeof v.texture !="undefined" && v.texture!="")){
                 loadImageData(k,v);
             }
           });
       }

    };
    makeCanvasResponsive();
    $(window).load(function () {
        $('#attribute-tabs').responsiveTabs({
            rotate: false,
            collapsible: 'accordion',
            activate: function (e, tab) {
                var selector=tab.selector,
                    selector_attribute= typeof selector != "undefined" && selector != null ? $(selector).data("attribute") : "";
               if(!_.contains(visitedStep,selector_attribute)){
                   visitedStep.push(selector_attribute);
               }
            }
        });

       loadBaseEdge('wpc_base_images','base_image');
       loadBaseEdge('wpc_edge_images','edge_image');
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
            termSlug=$this.data("term");
       $this.closest('.attribute_loop').find('button').removeClass('atv');
       $this.addClass('atv');
       if($this.hasClass('wpc_no_cords')){
           $('#wpc_color_tab_'+attributeName).html('');
           $('#wpc_texture_tab_'+attributeName).html('');
           cords= _.without(cords, _.findWhere(cords, {attribute: attributeName}));
       }
       if($this.hasClass('wpc_color_cords')){
         //Load Colors Tab
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
               'term':termSlug,
               'model':defaultModel,
               'productId':productId
           };
           $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
               $('#wpc_color_tab_'+attributeName).unblock();
               $('#wpc_color_tab_'+attributeName).html(response);
           });

           //Load Cord Images
           $('#wpc_product_stage').block({
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
           if (typeof _.findWhere(cords, {attribute: attributeName}) == "undefined") {
               cords.push({attribute:attributeName,term:termSlug});
           }else{
               var newArray = _.without(cords, _.findWhere(cords, {attribute: attributeName}));
               cords=newArray;
               cords.push({attribute:attributeName,term:termSlug});
           }
         var imageData= {
               'action': 'wpc_get_image_data',
               'attribute': attributeName,
               'cordsData':cords,
               'model':defaultModel,
               'productId':productId
           };
           $.post(wpc_ajaxUrl.ajaxUrl, imageData, function(response) {
               loadImagesFromAjax($.parseJSON(response));
               $('#wpc_product_stage').unblock();
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
              'term':termSlug,
              'model':defaultModel,
              'productId':productId
          };
          $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
              $('#wpc_texture_tab_'+attributeName).unblock();
              $('#wpc_texture_tab_'+attributeName).html(response);
          });
          cords= _.without(cords, _.findWhere(cords, {attribute: attributeName}));
      }
     });
    $(document).on("click",".change_color",function(e){
        e.preventDefault();
        $this=$(this);
        if($this.hasClass("active")){return false;}

        $this.closest('.c-seclect').find('.change_color').removeClass('active');
        $this.addClass("active");
        $this.closest('.c-seclect').find('i').remove();
        $this.append('<i class="fa fa-check-circle"></i>');

        if($this.hasClass('base_layer') || $this.hasClass('edge_layer')){
            $this.hasClass('base_layer')? colorBaseEdge($this.data('color'),'base_image'): colorBaseEdge($this.data('color'),'edge_image');
            return false;
        }
    });
});