 jQuery(function ($) {
    var canvasHeight = 800, canvasWidth = 800,designWidth=600,designHeight=400, cordScaleX=1, cordScaleY= 1, visitedStep=[],textures=[],colors=[],emb_positions={},image_change_possible=false,coming_from_reset=false,first_time_emb_change=true,imageLoading=false;
    var redirect_form_cart=wpc_cart_redirect_items!=null?true:false;
    var previousTab={},currentTab={};
    var globalEmbType=null;
    var canvas = jQuery('#wpc_product_stage').children('canvas').get(0);
    var stage = new fabric.Canvas(canvas, {
        selection: false,
        hoverCursor: 'default',
        rotationCursor: 'default',
        centeredScaling: true
    });
    var designCanvas= jQuery('#wpc_final_design').children('canvas').get(0);
    var designStage = new fabric.Canvas(designCanvas, {
        selection: false,
        hoverCursor: 'default',
        rotationCursor: 'default',
        centeredScaling: true
    });
     var checkTab=function(colorDiv,textureDiv,tabDiv){
         var checking=true;
         if((colorDiv!="" && colorDiv.find('.flclr').length)){
             if(colorDiv.find('i').length<=0) checking=false;
         }
         if((textureDiv!="" && textureDiv.find('.flclr').length)){
             if(textureDiv.find('i').length<=0) checking=false;
         }
         if(globalEmbType=='image' && $(tabDiv).data("attribute")==wpc_emb_layer){
             if($("#wpc_product_emb_image").val()==""){
                 checking=false;
             }
         }
         if(globalEmbType=='text' && $(tabDiv).data("attribute")==wpc_emb_layer){
             if($("#wpc_product_emb_text").val()=="" || $("#wpc_product_emb_fontcolor").val()==""){
                 checking=false;
             }
         }
         return checking;
     };
     var resetEverything=function(values,dont_change_model){
       if(!_.isEmpty(values)){
           image_change_possible=true;
           $.each(values,function(k,v){
               var button=$("."+"wpc_attribute_button_"+v["attribute"]+"_"+v["term"]);
               $(button).trigger("click");
               $("#wpc_attributes_values_"+v["attribute"]).parent().addClass("wpc_hidden");
               $("#wpc1_attributes_values_"+v["attribute"]).parent().addClass("wpc_hidden");
           });

       }
         visitedStep=[], cords=[],textures=[],colors=[],emb_positions={},first_time_emb_change=true;
         if(typeof dont_change_model=="undefined") {
             initialModel = defaultModel;
         }
         clearEmbTab();
         $("#embroidery_tab").addClass("wpc_hidden");
         $('.variations_button').addClass('wpc_hidden');
         if(!$.isEmptyObject(actualCords)){
             cords=actualCords;
         }
         coming_from_reset=false;
     };
    var makeCanvasResponsive=function(){
        stage.setWidth($('#wpc_product_stage').width());
        stage.setHeight((canvasHeight * stage.getWidth())/canvasWidth);
    };
    var makeDesignResponsive=function(){
        var desiredWidth=$('#wpc_final_design').width(),
            desiredHeight=desiredWidth*0.67;
        designStage.setDimensions({width:desiredWidth,height:desiredHeight});
    };
    var makeObjectResponsive=function(){
        var allObjects=stage.getObjects();
        for (var i = 0; i < allObjects.length; i++) {
            var tempScaleY=(1/canvasHeight) * stage.getHeight();
            var tempScaleX=(1/canvasWidth) * stage.getWidth();
            if(allObjects[i].imageType=="base_image" && allObjects[i].imageClass=="base_image"){
              cordScaleX=tempScaleX;
              cordScaleY=tempScaleY;
            }
            allObjects[i].set({scaleX:tempScaleX,scaleY:tempScaleY});
            if(allObjects[i].title=="extraContent"){
                var tempTop=(emb_positions.top/emb_positions.stageHeight) * stage.getHeight(),
                    tempLeft=(emb_positions.left/emb_positions.stageWidth) * stage.getWidth();
                if(allObjects[i].objectType=="image"){
                    var tempScaleX=(emb_positions.scaleX/emb_positions.stageWidth) * stage.getWidth(),
                        tempScaleY=(emb_positions.scaleY/emb_positions.stageHeight) * stage.getHeight();
                    allObjects[i].set({scaleX:tempScaleX,scaleY:tempScaleY,left:tempLeft,top:tempTop});
                    emb_positions.scaleX=tempScaleX;
                    emb_positions.scaleY=tempScaleY;
                }
                if(allObjects[i].objectType=="text"){
                    var tempFontSize=(emb_positions.fontSize/emb_positions.stageWidth) * stage.getWidth();
                    allObjects[i].set({fontSize:tempFontSize,left:tempLeft,top:tempTop});
                    emb_positions.fontSize=tempFontSize;
                }
                emb_positions.top=tempTop;
                emb_positions.left=tempLeft;
                emb_positions.stageHeight=stage.getHeight();
                emb_positions.stageWidth=stage.getWidth();
            }
            allObjects[i].setCoords();
        }
        stage.renderAll().calcOffset();
    };
    var loadBaseEdge=function(divId,imageType){
        var imageClasses=['base_image','texture_image'];
        var attribute=$("#"+divId+"_"+initialModel).data("attribute");
        $.each(imageClasses,function(k,v){
            var imgInstance = new fabric.Image($("#"+divId+"_"+initialModel).children('.'+v).get(0), {
                hasControls: false,
                hasBorders: false,
                lockMovementX: true,
                lockMovementY: true,
                lockRotation: true,
                lockScalingX: true,
                lockScalingY: true,
                lockUniScaling: true,
                imageClass:v,
                imageType:imageType,
                attribute:attribute
            });
            stage.add(imgInstance);
        });
        stage.renderAll().calcOffset();
    };
    var loadImageData=function(attribute,object){
       removeImageFromCanvas(attribute);
       var checking_base=0;
       var imageBase=new Image;
        imageBase.src=object.base;
        $(imageBase).load(function(){
          if(checking_base==0) {
              var imgInstance = new fabric.Image(imageBase, {
                  hasControls: false,
                  hasBorders: false,
                  lockMovementX: true,
                  lockMovementY: true,
                  lockRotation: true,
                  lockScalingX: true,
                  lockScalingY: true,
                  lockUniScaling: true,
                  imageClass: "base_image",
                  imageType: "cord_images",
                  attribute: attribute,
                  scaleX: cordScaleX,
                  scaleY: cordScaleY
              });
              stage.add(imgInstance);
              var imageTexture = new Image;
              imageTexture.src = object.texture;
              var checking_texture=0;
              $(imageTexture).load(function () {
                  if(checking_texture==0) {
                      var imgInstance1 = new fabric.Image(imageTexture, {
                          hasControls: false,
                          hasBorders: false,
                          lockMovementX: true,
                          lockMovementY: true,
                          lockRotation: true,
                          lockScalingX: true,
                          lockScalingY: true,
                          lockUniScaling: true,
                          imageClass: "texture_image",
                          imageType: "cord_images",
                          attribute: attribute,
                          scaleX: cordScaleX,
                          scaleY: cordScaleY
                      });
                      stage.add(imgInstance1);
                      checking_texture += 1;

                  }
              });
              stage.renderAll().calcOffset();
              checking_base+=1;
              if(typeof _.findWhere(colors,{attribute:attribute})!="undefined"){
                  var color= _.findWhere(colors,{attribute:attribute});
                  colorCanvas(attribute,color.color);
              }
          }
       });

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
    var loadSingleTextureImage=function(attribute,image){
        removeImageFromCanvas(attribute);
        var imageBase=new Image;
        imageBase.src= $.parseJSON(image);
        var checking_base=0;
        $(imageBase).load(function(){
            if(checking_base==0) {
                var imgInstance = new fabric.Image(imageBase, {
                    hasControls: false,
                    hasBorders: false,
                    lockMovementX: true,
                    lockMovementY: true,
                    lockRotation: true,
                    lockScalingX: true,
                    lockScalingY: true,
                    lockUniScaling: true,
                    imageClass: "texture_image",
                    imageType: "cord_images",
                    attribute: attribute,
                    scaleX: cordScaleX,
                    scaleY: cordScaleY
                });
                stage.add(imgInstance);
                checking_base +=1;}
        });
        stage.renderAll().calcOffset();
    };
    var loadTextureFromAjax=function(data){
        if(!$.isEmptyObject(data)){
           data=JSON.parse(data);
            $.each(data,function(k,v){
                if(v!=""){
                removeImageFromCanvas(k);
                    var checking_base=0;
                    var imageBase=new Image;
                    imageBase.src=v;
                    $(imageBase).load(function(){
                        if(checking_base==0) {
                        var imgInstance = new fabric.Image(imageBase, {
                            hasControls: false,
                            hasBorders: false,
                            lockMovementX: true,
                            lockMovementY: true,
                            lockRotation: true,
                            lockScalingX: true,
                            lockScalingY: true,
                            lockUniScaling: true,
                            imageClass: "texture_image",
                            imageType: "cord_images",
                            attribute: k,
                            scaleX: cordScaleX,
                            scaleY: cordScaleY
                        });
                            stage.add(imgInstance);
                            checking_base +=1;}
                    });
                }
                stage.renderAll().calcOffset();
            });
        }
    };
    var loadStaticImages=function(){
        $('#wpc_product_stage').block({
            message: '',
            overlayCSS: {
                border: 'none',
                padding: '0',
                margin: '0',
                backgroundColor: '#fff',
                opacity: 0.6,
                color: '#fff'
            }
        });
        var imageData= {
            'action': 'wpc_get_static_images',
            'model':initialModel,
            'productId':productId
        };
        $.post(wpc_ajaxUrl.ajaxUrl, imageData, function(data) {
            if(!$.isEmptyObject(data)){
                var response= $.parseJSON(data);
                $.each(response,function(k,v){
                    if((typeof v.base !="undefined" &&  v.base!="") && (typeof v.texture !="undefined" && v.texture!="")){
                        if(redirect_form_cart){
                       $(document).trigger("staticimageload");
                        }
                        loadImageData(k,v);
                    }
                });
            }
            $(document).trigger("loadstatic");
            $('#wpc_product_stage').unblock();
        });
    };
    var loadStaticImageSingle=function(attributeName){
        $('#wpc_product_stage').block({
            message: '',
            overlayCSS: {
                border: 'none',
                padding: '0',
                margin: '0',
                backgroundColor: '#fff',
                opacity: 0.6,
                color: '#fff'
            }
        });
        var imageData= {
            'action': 'wpc_get_single_static_image',
            'model':initialModel,
            'productId':productId,
            'attribute':attributeName
        };
        $.post(wpc_ajaxUrl.ajaxUrl, imageData, function(data) {
            var response= $.parseJSON(data);
            if((typeof response.base !="undefined" &&  response.base!="") && (typeof response.texture !="undefined" && response.texture!="")){
                loadImageData(attributeName,response);
            }
            $('#wpc_product_stage').unblock();
        });
    };
   var loadColorOrTextureTab=function(textureORcolor,attributeName,term,termId,buttonType){
       var tabDiv=textureORcolor=="color"?'#wpc_color_tab_'+attributeName:'#wpc_texture_tab_'+attributeName;
       $(tabDiv).block({
           message: '',
           overlayCSS: {
               border: 'none',
               padding: '0',
               margin: '0',
               backgroundColor: '#fff',
               opacity: 0.6,
               color: '#fff'
           }
       });
       var action=textureORcolor=='color'?'wpc_get_color_data':'wpc_get_texture_data';
       var data = {
           'action':action,
           'attribute': attributeName,
           'term':term,
           'termId':termId,
           'model':defaultModel,
           'productId':productId,
           'buttonType':buttonType
       };
       $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
          switch (textureORcolor){
              case "color":
                  $('#wpc_texture_tab_'+attributeName).html("");
                  $(tabDiv).html(response);
                  if(typeof _.findWhere(colors,{attribute:attributeName})!="undefined"){
                      var colorData=_.findWhere(colors,{attribute:attributeName});
                      if($("#wpc_color_tab_"+attributeName+" .change_color[data-color='"+colorData.color+"']").length>0){
                          $("#wpc_color_tab_"+attributeName+" .change_color[data-color='"+colorData.color+"']").trigger('click');
                      }
                  }
                  $(tabDiv).unblock();
                  break;
              case "texture":
                  $('#wpc_color_tab_'+attributeName).html("");
                  $(tabDiv).html(response);
                  if(typeof _.findWhere(textures,{attribute:attributeName})!="undefined"){
                      var textureData=_.findWhere(textures,{attribute:attributeName});
                      if($("#wpc_color_tab_"+attributeName+" .change_texture[data-clean='"+textureData.texture+"']").length>0){
                          $("#wpc_color_tab_"+attributeName+" .change_texture[data-clean='"+textureData.texture+"']").append('<i class="fa fa-check-circle"></i>')
                      }
                  }
                  $(tabDiv).unblock();
                  break;
          }
       });
   };
    var removeColorCordsFromArray=function(attribute){
        if(typeof _.findWhere(colors,{attribute:attribute}!="undefined")){
            var newArray = _.without(colors, _.findWhere(colors, {attribute: attribute}));
            colors=newArray;
        }
        if(typeof _.findWhere(textures,{attribute:attribute}!="undefined")){
            var newArray = _.without(textures, _.findWhere(textures, {attribute: attribute}));
            textures=newArray;
        }
        if(typeof _.findWhere(cords,{attribute:attribute}!="undefined")) {
            cords = _.without(cords, _.findWhere(cords, {attribute: attribute}));
        }
    };
 var removeColorTexttureArray=function(type,attribute){
     switch (type){
         case "color":
             if(typeof _.findWhere(colors,{attribute:attribute}!="undefined")){
                 var newArray = _.without(colors, _.findWhere(colors, {attribute: attribute}));
                 colors=newArray;
             }
             break;
         case "texture":
             if(typeof _.findWhere(textures,{attribute:attribute}!="undefined")){
                 var newArray = _.without(textures, _.findWhere(textures, {attribute: attribute}));
                 textures=newArray;
             }
             break
         default :
             break;
     }

 };
 var fetchImageData=function(attributeName){
     $('#wpc_product_stage').block({
         message: '',
         overlayCSS: {
             border: 'none',
             padding: '0',
             margin: '0',
             backgroundColor: '#fff',
             opacity: 0.6,
             color: '#fff'
         }
     });
     var imageData= {
         'action': 'wpc_get_image_data',
         'attribute': attributeName,
         'cordsData':cords,
         'model':initialModel,
         'productId':productId
     };
     $.post(wpc_ajaxUrl.ajaxUrl, imageData, function(response) {
         loadImagesFromAjax($.parseJSON(response));
         $('#wpc_product_stage').unblock();
     });
 };
 var fetchTextureData=function(){
     $('#wpc_product_stage').block({
         message: '',
         overlayCSS: {
             border: 'none',
             padding: '0',
             margin: '0',
             backgroundColor: '#fff',
             opacity: 0.6,
             color: '#fff'
         }
     });
     var imageData= {
         'action': 'wpc_get_texture_image_data',
         'cordsData':cords,
         'textureData':textures,
         'model':initialModel,
         'productId':productId
     };
     $.post(wpc_ajaxUrl.ajaxUrl, imageData, function(response) {
         loadTextureFromAjax(response);
        $('#wpc_product_stage').unblock();
     });
 };
 var loadTextureDataSingle=function(attribute,texture){
     $('#wpc_product_stage').block({
         message: '',
         overlayCSS: {
             border: 'none',
             padding: '0',
             margin: '0',
             backgroundColor: '#fff',
             opacity: 0.6,
             color: '#fff'
         }
     });
     var imageData= {
         'action': 'wpc_get_single_texture_image_data',
         'texture':texture,
         'attribute':attribute,
         'model':initialModel,
         'productId':productId
     };
     $.post(wpc_ajaxUrl.ajaxUrl, imageData, function(response) {
         loadSingleTextureImage(attribute,response);
         $('#wpc_product_stage').unblock();
     });
 };

 var removeImageFromCanvas=function(attribute){
   var objects=stage.getObjects();
     for (var i = 0; i < objects.length; i++) {
         if(objects[i].attribute==attribute){
             stage.remove(objects[i]);
             i--;
         }
     }
     stage.renderAll().calcOffset();
 };
 var resetCanvas=function(){
     stage.clear();
     loadBaseEdge('wpc_base_images','base_image');
     loadStaticImages();
 };
 var colorCanvas=function(attribute,color){
     var objects=stage.getObjects();
     for (var i = 0; i < objects.length; i++) {
            if(objects[i].attribute==attribute && objects[i].imageClass=="base_image"){
                objects[i].filters.push(new fabric.Image.filters.Tint({color: color}));
                objects[i].applyFilters(stage.renderAll.bind(stage));
                break;
            }
     }
     stage.renderAll().calcOffset();
 };
    var clearEmbTab=function(){
      $(".wpc_emb_tabs ").removeClass("atv");
      $(".wpc_emb_controls").addClass("wpc_hidden");
      globalEmbType=null;
      clearEmbControls();
    };
    var clearEmbControls=function(){
        $("#wpc_font_select").html("");
        $("#wpc_size_select").html("");
        $("#wpc_text_add").val("");
        $(".wpc_hidden_emb").val("");
        $("#wpc_emb_colors").html("");
        $("#wpc_emb_postion_buttons").html("");
        $("#wpc_text_options").addClass("wpc_hidden");
        $("#wpc_emb_colors").addClass("wpc_hidden");
        $("#wpc_emb_postion_buttons").addClass("wpc_hidden");
        $(".wpc_emb_rotate_buttons").addClass("wpc_hidden");
        $("#wpc_image_upload").val("");
        $(".wpc_emb_options").parent().addClass("wpc_hidden");
        emb_positions={};
        removeEmb();
    };
    var removeEmb=function(){
        var objects=stage.getObjects();
        for (var i = 0; i < objects.length; i++) {
            if(objects[i].title=="extraContent"){
                stage.remove(objects[i]);
                i--;
            }
        }
        stage.renderAll().calcOffset();
    };
    var getLogoPostions=function(top,left){
      var positions={};
        positions["top"]=(top * stage.getHeight())/canvasHeight;
        positions["left"]=(left * stage.getWidth())/canvasWidth;
        return positions;
    };
    var getFontSize=function(size){
    return ((size*stage.getWidth())/canvasWidth);
    };
    var setCords=function(attributeName,termSlug){
        if (typeof _.findWhere(cords, {attribute: attributeName}) == "undefined") {
            cords.push({attribute:attributeName,term:termSlug});
        }else{
            var newArray = _.without(cords, _.findWhere(cords, {attribute: attributeName}));
            cords=newArray;
            cords.push({attribute:attributeName,term:termSlug});
        }
    };
    var zoomImage=function(ratio){
            var modifiedWidth=stage.getWidth() * ratio,
             modifiedHieght=stage.getHeight() * ratio;

        stage.setDimensions({height:modifiedHieght,width:modifiedWidth});
        makeObjectResponsive();

       var  dataUrl = stage.toDataURL({
            format: 'png',
            quality: 1
        });
      makeCanvasResponsive();
      makeObjectResponsive();
        return dataUrl;
    };
    var setAttributeValues=function(attribute,term){
      $("#wpc_attributes_values_"+attribute).parent().removeClass("wpc_hidden");
      $("#wpc_attributes_values_"+attribute).text(term);
    };
    var displayOptions=function(prefix,attribute_name,value){

        var td=$("#"+prefix+'_'+attribute_name);
        if(value!='') {
            td.parent().removeClass('wpc_hidden');
        }else{
            td.parent().addClass('wpc_hidden');
        }
        td.text(value);

    };
    var putEmbData=function(type,text,html){
        var td=$("#wpc_emb_options_"+type);
        var textWithLineBreak=text.replace(/\n\r?/g, '<br />');
        $(td).parent().removeClass("wpc_hidden");
        if(text==""){$(td).parent().addClass("wpc_hidden");}
        if(html){
            $(td).html(textWithLineBreak);
        }else{
            $(td).text(text);
        }
        $("#wpc_product_emb_"+type).val(text);
    };
    var hiddenData=function(attribute,value){
        $('#wpc_extra_item_' + attribute).val(value);
    };
     var hiddenActualData=function(attribute,value){
         $('#wpc_original_item_' + attribute).val(value);
         var objects=stage.getObjects();
         for (var i = 0; i < objects.length; i++) {
             // console.log(i);
             // console.log(objects[i]);
         }
     };
     fabric.Object.prototype.setOriginToCenter = function () {
         this._originalOriginX = this.originX;
         this._originalOriginY = this.originY;

         var center = this.getCenterPoint();

         this.set({
             originX: 'center',
             originY: 'center',
             left: center.x,
             top: center.y
         });
     };

     fabric.Object.prototype.setCenterToOrigin = function () {
         var originPoint = this.translateToOriginPoint(
             this.getCenterPoint(),
             this._originalOriginX,
             this._originalOriginY);

         this.set({
             originX: this._originalOriginX,
             originY: this._originalOriginY,
             left: originPoint.x,
             top: originPoint.y
         });
     };
     var rotateObject=function(obj,angleOffset,resetEvrything){
         var resetOrigin = false;
         if (!obj) return;
         var angle = obj.getAngle() + angleOffset;
         if(typeof resetEvrything !="undefined"){
             angle=angleOffset;
         }
         if ((obj.originX !== 'center' || obj.originY !== 'center') && obj.centeredRotation) {
             obj.setOriginToCenter && obj.setOriginToCenter();
             resetOrigin = true;
         }
         angle = angle > 360 ? angle-360 : angle < -360 ? -360-angle : angle;
         obj.setAngle(angle).setCoords();
         if (resetOrigin) {
             obj.setCenterToOrigin && obj.setCenterToOrigin();
         }
         var finalAngle=obj.getAngle();
         var angleText=finalAngle>0?translate_text.right + ' '+Math.abs(finalAngle) : translate_text.left + ' '+Math.abs(finalAngle);
         if(finalAngle!=0 && finalAngle != 360 && finalAngle != -360) {
             putEmbData("angle", angleText);
         }else{
             putEmbData("angle", '');
         }
         stage.renderAll();
     };
    $(document).on("click",".wpc_clear_all",function(e){
        e.preventDefault();
        clearEmbControls();
        clearEmbTab();
    });
    makeCanvasResponsive();
    $('.variations_button').addClass('wpc_hidden');
         $('#wpc_main_container').block({
             message: '',
             overlayCSS: {
                 border: 'none',
                 padding: '0',
                 margin: '0',
                 backgroundColor: '#fff',
                 opacity: 1,
                 color: '#fff'
             }
         });
$(document).on("staticimageload",function(){
    var variations=wpc_cart_redirect_items.variations;
    if(!_.isEmpty(variations)) {
        var i=1;
        //console.log(Object.keys(variations).length );
        $.each(variations, function (k, v) {
            setTimeout(function () {
                var variation_button=$('.wpc_attribute_button_'+ k.replace('attribute_','')+'_'+v);
                $(variation_button).trigger("click");
            }, i*100);
            i++;
            $('#wpc_main_container').unblock();
        });
    }
});
     var comingFromOtherTab=false;
    $(window).load(function () {
        $('#attribute-tabs').find('.wpc_original_item').detach().appendTo($('.variations_form'));
        $('#attribute-tabs').find('.wpc_extra_item').detach().appendTo($('.variations_form'));
        $('#attribute-tabs').responsiveTabs({
            rotate: false,
            collapsible: 'accordion',
            activate: function (e, tab) {
                var selector=tab.selector,
                    selector_attribute= typeof selector != "undefined" && typeof $(selector).data("attribute")!="undefined" && $(selector).data("attribute") != null ? $(selector).data("attribute") : "",
                    colorDiv=$("#wpc_color_tab_"+selector_attribute),
                    textureDiv=$("#wpc_texture_tab_"+selector_attribute);
                if(_.isEmpty(previousTab) && _.isEmpty(currentTab)){
                    currentTab.tabId=tab.id;
                    currentTab.colorDiv=colorDiv.length ? colorDiv: '';
                    currentTab.textureDiv=textureDiv.length ? textureDiv: '';
                    currentTab.tabDiv=selector;
                }
                else{
                    previousTab.tabId=currentTab.tabId;
                    previousTab.colorDiv=currentTab.colorDiv;
                    previousTab.textureDiv=currentTab.textureDiv;
                    previousTab.tabDiv=currentTab.tabDiv;
                    currentTab.tabId=tab.id;
                    currentTab.colorDiv=colorDiv.length ? colorDiv : '';
                    currentTab.textureDiv=textureDiv.length ? textureDiv : '';
                    currentTab.tabDiv=selector;
                }
                if(comingFromOtherTab) previousTab={};
                var checkTabContent=checkTab(typeof previousTab.colorDiv!='undefined' ? previousTab.colorDiv : '',typeof previousTab.textureDiv!='undefined' ? previousTab.textureDiv : '' ,typeof previousTab.tabDiv!='undefined' ? previousTab.tabDiv : '');
                if(!checkTabContent){

                    alert(translate_text.step_alert);
                    comingFromOtherTab=true;
                    $('#attribute-tabs').responsiveTabs('activate', previousTab.tabId);
                }
                comingFromOtherTab=false;
                var cordButton=$(selector).find('.atv');
                if($(cordButton).hasClass('wpc_color_cords')){
                    loadColorOrTextureTab("color",$(cordButton).data("attribute"),$(cordButton).data("term"),$(cordButton).data("id"),$(cordButton).hasClass('wpc_static_layer')?'static_button':'')
                }
                if($(cordButton).hasClass('wpc_texture_cords')){
                    loadColorOrTextureTab("texture",$(cordButton).data("attribute"),$(cordButton).data("term"),$(cordButton).data("id"),$(cordButton).hasClass('wpc_static_layer')?'static_button':'')
                }
               if(selector_attribute!="" && !_.contains(visitedStep,selector_attribute)){
                   visitedStep.push(selector_attribute);
                   var selected_term=$(selector).find(".atv").data("display");
                   setAttributeValues(selector_attribute,selected_term);
               }
            }
        });

         if(!redirect_form_cart) $('#wpc_main_container').unblock();
            loadBaseEdge('wpc_base_images', 'base_image');
            loadStaticImages();
            makeObjectResponsive();
    });
    $(window).resize(function () {
        makeCanvasResponsive();
        makeObjectResponsive();
        makeDesignResponsive();
    });
    $(document).on("click",".wpc_terms",function(e){
        e.preventDefault();
        var self=$(this);
        if(self.hasClass('atv') && !redirect_form_cart){
            return false;
        }
        var attributeName=self.data("attribute"),
            termSlug=self.data("term"),
            termId=self.data("id"),
            display=self.data("display");
        self.closest('.attribute_loop').find('button').removeClass('atv');
        self.addClass('atv');
       setAttributeValues(attributeName,display);
        $("#" + attributeName).focusin().val(termSlug).change();
       if(self.hasClass('wpc_no_cords')){
           $('#wpc_color_tab_'+attributeName).html('');
           $('#wpc_texture_tab_'+attributeName).html('');
           removeColorCordsFromArray(attributeName);
           fetchImageData(attributeName);
           fetchTextureData();
           removeImageFromCanvas(attributeName);
           displayOptions("wpc1_attributes_values",attributeName,'');
           hiddenData(attributeName,"");
       }
       if(self.hasClass('wpc_color_cords')){
           removeColorTexttureArray("texture",attributeName);
            loadColorOrTextureTab("color",attributeName,termSlug,termId,self.hasClass('wpc_static_layer')?'static_button':'');
           //Load Cord Images
           if(self.hasClass("wpc_static_layer")){
               loadStaticImageSingle(attributeName);
               return false;
           }
            setCords(attributeName,termSlug);
            fetchImageData(attributeName);
       }
      if(self.hasClass('wpc_texture_cords')){
          removeColorTexttureArray("color",attributeName);
          loadColorOrTextureTab("texture",attributeName,termSlug,termId,self.hasClass('wpc_static_layer')?'static_button':'');
          if(self.hasClass("wpc_static_layer")) return false;
          setCords(attributeName,termSlug);
      }
        if(self.hasClass("wpc_no_emb")){
            clearEmbTab();
            $("#embroidery_tab").addClass("wpc_hidden");
            return false;
        }
        if(self.hasClass("wpc_emb_buttons")){
            $("#embroidery_tab").removeClass("wpc_hidden");
            if(first_time_emb_change){
                first_time_emb_change=false;
                $('#wpc_emb_buttons').block({
                    message: '',
                    overlayCSS: {
                        border: 'none',
                        padding: '0',
                        margin: '0',
                        backgroundColor: '#fff',
                        opacity: 0.6,
                        color: '#fff'
                    }
                });
                var data= {
                    'action': 'wpc_get_emb_button_data',
                    'model':defaultModel,
                    'productId':productId
                };
                $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
                    var data= $.parseJSON(response);
                    $('#wpc_emb_buttons').html(data.html);
                    $('#wpc_emb_buttons').unblock();
                    if(data.type!='both'){
                        if($("#wpc_emb_buttons .wpc_emb_tabs[data-type='"+data.type+"']").length>0){
                            $("#wpc_emb_buttons .wpc_emb_tabs[data-type='"+data.type+"']").trigger('click');
                        }
                    }
                });
            }

        }
     });
     $(document).on('click','.wpc_model',function(e){
         var self=$(this);
         if(self.hasClass('atv') && !image_change_possible){
             return false;
         }
         image_change_possible=false;
         var attributeName=self.data("attribute"),
             termSlug=self.data("term"),
             termId=self.data("id"),
             display=self.data("display");
         if(visitedStep.length>1 && !image_change_possible && !coming_from_reset){
             var confirmation=confirm(translate_text.model_change);
             if(!confirmation) return false;
             defaultModel=termId;
             if(self.hasClass("wpc_available_model")) initialModel=termId;
             var newArray = _.without(defaultValues, _.findWhere(defaultValues, {attribute: attributeName}));
             resetEverything(newArray,true);
             resetCanvas();
         }
         defaultModel=termId;
         if(self.hasClass("wpc_available_model")){
             initialModel==termId;
             resetCanvas();
         }
         self.closest('.attribute_loop').find('a').removeClass('atv');
         self.closest('.attribute_loop').find('i').remove();
         self.addClass('atv');
         self.parent().append('<i class="fa fa-check"></i>');
         setAttributeValues(attributeName,display);
         $("#" + attributeName).focusin().val(termSlug).change();
     });

    $(document).on("click",".change_color",function(e){
        e.preventDefault();
       var self=$(this);
        if(self.hasClass("active")){return false;}

        self.closest('.c-seclect').find('.change_color').removeClass('active');
        self.addClass("active");
        self.closest('.c-seclect').find('i').remove();
        self.append('<i class="fa fa-check-circle"></i>');
        var attribute=self.data("attribute"),
            colorValue=self.data("color"),
            displayValue=self.data("display");
        if(typeof _.findWhere(colors,{attribute:attribute})=="undefined"){
            colors.push({attribute:attribute,color:colorValue});
        }else{
            var newArray = _.without(colors, _.findWhere(colors, {attribute: attribute}));
            colors=newArray;
            colors.push({attribute:attribute,color:colorValue});
        }
        displayOptions("wpc1_attributes_values",attribute,displayValue);
        colorCanvas(attribute,colorValue);
        hiddenData(attribute,displayValue);
        hiddenActualData(attribute,colorValue);
    });
    $(document).on("click",".change_texture",function(e){
        e.preventDefault();
        var self=$(this);
        if(self.hasClass("active")){return false;}
        var attribute=self.data("attribute"),
            term=self.data("term"),
            texture=self.data("clean"),
            display=self.data("display");
        self.closest('.c-seclect').find('.change_texture').removeClass('active');
        self.addClass("active");
        self.closest('.c-seclect').find('i').remove();
        self.append('<i class="fa fa-check-circle"></i>');
        if(typeof _.findWhere(textures,{attribute:attribute})=="undefined"){
            textures.push({attribute:attribute,texture:texture});
        }else{
            var newArray = _.without(textures, _.findWhere(textures, {attribute: attribute}));
            textures=newArray;
            textures.push({attribute:attribute,texture:texture});
        }
        if(!self.hasClass("static_button")){fetchTextureData();}else{
            loadTextureDataSingle(attribute,texture);
        }
        displayOptions("wpc1_attributes_values",attribute,display);
        hiddenData(attribute,display);
        hiddenActualData(attribute,texture);
    });
    $(document).on("click",".wpc_emb_tabs",function(e){
        e.preventDefault();
        var self=$(this);
        $('.wpc_emb_controls').addClass("wpc_hidden");
        $('.wpc_emb_tabs').removeClass("atv");
        $(self.attr("href")).removeClass("wpc_hidden");
        $(self).addClass("atv");
        clearEmbControls();
        globalEmbType=self.data('type');
        putEmbData("type",self.text());
    });
    $(document).on("click","#wpc_add_text_btn",function(e){
        e.preventDefault();
        var textToPut = $('#wpc_text_add').val().trim();
        if(textToPut!="") {
            $('#embroidery_tab').block({
                message: '',
                overlayCSS: {
                    border: 'none',
                    padding: '0',
                    margin: '0',
                    backgroundColor: '#fff',
                    opacity: 0.6,
                    color: '#fff'
                }
            });
            var data = {
                'action': 'wpc_get_emb_config',
                'model':defaultModel,
                'type':'text',
                'productId':productId
            };
            $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
               var responseData= $.parseJSON(response);
                $("#wpc_font_select").html(responseData.fontOptions);
                $("#wpc_size_select").html(responseData.fontSizes);
                $("#wpc_emb_colors").html(responseData.colors);
                $("#wpc_emb_postion_buttons").html(responseData.positions);
                $("#wpc_text_options").removeClass("wpc_hidden");
                $("#wpc_emb_colors").removeClass("wpc_hidden");
                $("#wpc_emb_postion_buttons").removeClass("wpc_hidden");
                $(".wpc_emb_rotate_buttons").removeClass("wpc_hidden");
                removeEmb();
                var position_x=$("#wpc_emb_postion_buttons").find(".active").data("left");
                    position_y=$("#wpc_emb_postion_buttons").find(".active").data("top"),
                    actualPostions=getLogoPostions(position_y,position_x),
                    tempfontSize=getFontSize(responseData.selectedFontSize),
                    tempfontStyle=responseData.selectedFontStyle,
                    positionText= $("#wpc_emb_postion_buttons").find(".active").text();
                var comicSansText = new fabric.Text(textToPut, {
                    title: 'extraContent',
                    objectType: 'text',
                    hasControls: false,
                    hasBorders: false,
                    lockMovementX: true,
                    lockMovementY: true,
                    lockRotation: true,
                    lockScalingX: true,
                    lockScalingY: true,
                    lockUniScaling: true,
                    top:actualPostions.top,
                    left:actualPostions.left,
                    fontSize:tempfontSize,
                    fontFamily:tempfontStyle
                });
                stage.add(comicSansText);
                stage.renderAll();
                emb_positions={objectType:"text",stageWidth:stage.getWidth(),stageHeight:stage.getHeight(),top:actualPostions.top,left:actualPostions.left,fontSize:tempfontSize};
                putEmbData("text",textToPut,true);
                putEmbData("fontsize",$("#wpc_size_select :selected").text());
                putEmbData("font",$("#wpc_font_select :selected").text());
                putEmbData("position",positionText);
                $('#wpc_text_add').val("");
                $('#embroidery_tab').unblock();
            });
        }
    });
    $(document).on('change', '#wpc_image_upload', function (e) {
        var reg = /(.jpg|.gif|.png)$/;
        if ($("#wpc_image_upload").val()=="" && !reg.test($("#wpc_image_upload").val())) {
            return false;
        }
        $('#wpc_product_stage').block({
            message: '',
            overlayCSS: {
                border: 'none',
                padding: '0',
                margin: '0',
                backgroundColor: '#fff',
                opacity: 0.6,
                color: '#fff'
            }
        });
        $("#wpc_image_upload_form").ajaxSubmit({
            dataType: 'json',
            data:{productId:productId,model:defaultModel},
            success: function (data, statusText, xhr, wrapper) {
                removeEmb();
                $("#wpc_emb_postion_buttons").html(data.positions);
                $("#wpc_emb_postion_buttons").removeClass("wpc_hidden");
                $(".wpc_emb_rotate_buttons").removeClass("wpc_hidden");
                var position_x=$("#wpc_emb_postion_buttons").find(".active").data("left"),
                     position_y=$("#wpc_emb_postion_buttons").find(".active").data("top"),
                     actualPostions=getLogoPostions(position_y,position_x),
                    positionText= $("#wpc_emb_postion_buttons").find(".active").text();
                new fabric.Image.fromURL(data.filepath, function (oImg) {
                    var imageHeight=data.sizes.height,
                        imageWidth=data.sizes.width,
                        maxWidth=(imageWidth/canvasWidth) * stage.getWidth(),
                        maxHeight = (imageHeight/canvasHeight) * stage.getHeight(),
                        ratio=1;
                    if(oImg.width > maxWidth){
                        ratio=maxWidth / oImg.width;
                    }
                    if(oImg.height > maxHeight){
                        ratio = maxHeight / oImg.height;
                    }
                    oImg.set({hasControls: false,hasBorders: false,lockMovementX: true,lockMovementY: true,lockRotation: true,lockScalingX: true,lockScalingY: true,lockUniScaling: true,left:actualPostions.left,top:actualPostions.top,scaleX: ratio, scaleY: ratio, title: 'extraContent', objectType: 'image'});
                    emb_positions={objectType:"image",stageWidth:stage.getWidth(),stageHeight:stage.getHeight(),top:actualPostions.top,left:actualPostions.left,scaleX:ratio,scaleY:ratio};
                    stage.add(oImg);
                    stage.calcOffset().renderAll();
                    putEmbData("image",'<a target="_blank" href="'+data.filepath+'">'+translate_text.image_file+'</a>',true);
                    putEmbData("position",positionText);
                    $('#wpc_product_stage').unblock();
                });
            }
        });
    });
    $(document).on("click",".wpc_emb_btn",function(e){
        e.preventDefault();
        var self=$(this);
        if(self.hasClass("active"))return false;
        var position_x=self.data("left"),
            position_y=self.data("top"),
            actualPostions=getLogoPostions(position_y,position_x),
            positionText=self.text();
        $("#wpc_emb_postion_buttons").find(".active").removeClass("active");
        self.addClass("active");
        var objects = stage.getObjects();
        for (var i = 0; i < objects.length; i++) {
            if(objects[i].title=="extraContent"){
                objects[i].set({top:actualPostions.top,left:actualPostions.left});
                    emb_positions.top=actualPostions.top;
                    emb_positions.left=actualPostions.left;
                    emb_positions.stageWidht=stage.getWidth();
                    emb_positions.stageHeight=stage.getHeight();
            }
        }
        stage.renderAll().calcOffset();
        putEmbData("position",positionText);
    });
   $(document).on("click",".wpc_emb_rotate_buttons",function(e){
       e.preventDefault();
      var self=$(this);
       var type=self.data("type");
       var angle=parseFloat($("#wpc_emb_angle").val());
       //var embData=null;
       if(angle>0 && angle<360) {
           var objects = stage.getObjects();
           for (var i = 0; i < objects.length; i++) {
               if (objects[i].title == "extraContent") {
                   switch (type) {
                       case "left":
                           rotateObject(objects[i],-angle);
                           break;
                       case "right":
                           rotateObject(objects[i], angle);
                           break;
                   }
                   break;
               }
           }
       }
   });
   $(document).on("click",".wpc_emb_rotate_only",function(e){
       e.preventDefault();
       var self=$(this),
           angle=self.data("angle");
       var objects = stage.getObjects();
       for (var i = 0; i < objects.length; i++) {
           if (objects[i].title == "extraContent") {
               rotateObject(objects[i],-angle);
               break;
           }
       }
   });
    $(document).on("click","#wpc_reset_angle",function(e){
        e.preventDefault();
        var objects = stage.getObjects();
        for (var i = 0; i < objects.length; i++) {
            if (objects[i].title == "extraContent") {
                rotateObject(objects[i],0,true);
                break;
            }
        }
        $("#wpc_emb_angle").val('');
        putEmbData("angle", '');
    });
    $(document).on('change', '#wpc_font_select', function () {
      var self=$(this);
        if(self.val()!=""){
            var objects = stage.getObjects();
            for (var i = 0; i < objects.length; i++) {
                if(objects[i].objectType=='text'){
                    objects[i].set({fontFamily:self.val()});
                    break;
                }
            }
            stage.renderAll();
            putEmbData("font",$("#wpc_font_select :selected").text());
        }
    });
    $(document).on('change', '#wpc_size_select', function () {
       var self=$(this);
        if(self.val()!=""){
            var objects = stage.getObjects(),
                fontSize=getFontSize(self.val());
            for (var i = 0; i < objects.length; i++) {
                if(objects[i].objectType=='text'){
                    objects[i].set({fontSize:fontSize});
                    emb_positions.stageHeight=stage.getHeight();
                    emb_positions.stageWidht=stage.getWidth();
                    emb_positions.fontSize=fontSize;
                    break;
                }
            }

            stage.renderAll();
            putEmbData("fontsize",$("#wpc_size_select :selected").text());
        }
    });
    $(document).on('click', '.change_color_emb', function (e) {
        e.preventDefault();
       var self=$(this);
        var color=self.data('color'),
            all=self.data('all').split("|"),
            colorName=all[0];
        var objects = stage.getObjects();
        for (var i = 0; i < objects.length; i++) {
            if(objects[i].objectType=='text'){
                objects[i].set({fill: color});
                break;
            }
        }
        stage.renderAll();
        $(this).parent().parent().find('i').remove();
        $(this).append('<i class="fa fa-check-circle"></i>');
        putEmbData("fontcolor",colorName);
    });
    $(document).on('keypress','#wpc_text_add',function(event){
        var text=$(this).val();
        var arr = text.split("\n");
        if(arr.length > wpc_emb_limit.line_limit) {
            event.preventDefault();
            text=text.substring(0, text.length - 1);
            $(this).val(text);
        }else{
            for(var i = 0; i < arr.length; i++) {
                if(arr[i].length > wpc_emb_limit.character_limit) {
                    event.preventDefault();
                    arr[i]=arr[i].substring(0, arr[i].length - 1);
                    text=arr.join('\n');
                    $(this).val(text);
                }
            }
        }
    });
     $('#wpc_text_add').bind("cut copy paste",function(e) {
         e.preventDefault();
     });
    $(document).on('click', '#wpc_bold_select', function (e) {
        e.preventDefault();
        var title=$(this).attr("title");
        var objects = stage.getObjects();
        for (var i = 0; i < objects.length; i++) {
            if(objects[i].objectType=='text'){
                switch (objects[i].fontWeight) {
                    case 'normal':
                        objects[i].set({
                            fontWeight: 'bold'
                        });
                        putEmbData("fontweight",title);
                        break;
                    case 'bold':
                        objects[i].set({
                            fontWeight: 'normal'
                        });
                        putEmbData("fontweight","");
                        break;
                }
            }
        }
        stage.renderAll();
    });
    $(document).on('click', '#wpc_italic_select', function (e) {
        e.preventDefault();
        var title=$(this).attr("title");
        var objects = stage.getObjects();
        for (var i = 0; i < objects.length; i++) {
            if(objects[i].objectType=='text'){
                switch (objects[i].fontStyle) {
                    case '':
                        objects[i].set({
                            fontStyle: 'italic'
                        });
                        putEmbData("fontstyle",title);
                        break;
                    case 'italic':
                        objects[i].set({
                            fontStyle: ''
                        });
                        putEmbData('fontstyle',"");
                        break;
                }
            }
        }
        stage.renderAll();
    });
    $(document).on("click","#wpc_emb_extra_comment_button",function(e){
        e.preventDefault();
        var text=$("#wpc_emb_extra_comment_text").val();
        if(text!="") {
            putEmbData("extra_comment", text,true);
            $("#wpc_emb_extra_comment_text").val("");
        }
    });
    $(document).on("click","#wpc_emb_extra_comment_button_remove",function(e){
        e.preventDefault();
        $("#wpc_emb_extra_comment_text").val("");
        putEmbData("extra_comment", "");
    });
   $(document).on("click","#wpc_product_stage",function(e){
       var windoWidth = $(window).width();
       var self=$(this);
       if(windoWidth<767){ return false};
       if($("#tempForCloud").length){
           $('#tempForCloud').remove();
       }
       $('#wpc_product_stage').block({
           message: '',
           overlayCSS: {
               border: 'none',
               padding: '0',
               margin: '0',
               backgroundColor: '#fff',
               opacity: 0.6,
               color: '#fff'
           }
       });
       var position=self.offset();
       var orginalImage=stage.toDataURL({
           format: 'png',
           quality: 1
       });
       var zoomingImage=zoomImage(canvasWidth/stage.getWidth());
       var anchor='<img class="cloudzoom"  id ="zoom1" src="'+orginalImage+'" data-cloudzoom=\'zoomImage:"'+zoomingImage+'",zoomSizeMode:"image",autoInside: 550\' />'
       var div='<div id="tempForCloud" style="top:'+position.top+'px;left:'+position.left+'px;width:'+self.width()+'px;height:'+self.height()+'px;z-index:100;position:absolute">'+anchor+'</div>';
       $('body').append(div);
       $('#zoom1').CloudZoom();
       $('#zoom1').bind('cloudzoom_ready',function(){  $('#wpc_product_stage').unblock();});
       $('#zoom1').bind('cloudzoom_end_zoom',function(){$('#tempForCloud').remove();});
       $('#zoom1').bind('click',function(){
           var cloudZoom = $(this).data('CloudZoom');
          cloudZoom.closeZoom();
          stage.deactivateAllWithDispatch();
           $.magnificPopup.open({
               items: {src: zoomingImage},
               type: 'image'
           }, 0);
       });
   });
    $(document).on("change","#wpc_base_design_options",function(){
       var self=$(this);
       if(self.val()==""){ designStage.clear(); return false};
        $('#wpc_final_design').block({
            message: '',
            overlayCSS: {
                border: 'none',
                padding: '0',
                margin: '0',
                backgroundColor: '#fff',
                opacity: 0.6,
                color: '#fff'
            }
        });
        var data = {
            'action': 'wpc_get_design_data',
            'postId':self.val()
        };
        $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
            var data=JSON.parse(response);
            designStage.clear();
            var imagedata=data.wpc_hidden_base_design;
            var img=new Image;
            img.src=imagedata;
            var checking=0;
            makeDesignResponsive();
            $(img).load(function(){
                if(checking==0) {
                    var ratio=1;
                    if(img.width > designStage.getWidth()){
                        ratio=designStage.getWidth() / img.width;
                    }
                    if(img.height > designStage.getHeight()){
                        ratio = designStage.getHeight() / img.height;
                    }
                    var imgInstance = new fabric.Image(img, {
                        hasControls: false,
                        hasBorders: false,
                        lockMovementX: true,
                        lockMovementY: true,
                        lockRotation: true,
                        lockScalingX: true,
                        lockScalingY: true,
                        lockUniScaling: true,
                        imageType: "base_image",
                        scaleX: ratio,
                        scaleY: ratio,
                    });
                    designStage.add(imgInstance).renderAll();
                    checking+=1;
                }
            });
            $('#wpc_final_design').unblock();
        });
    });
    $(document).on("click",".wpc_finish_product",function(e){
        e.preventDefault();
        if($("#wpc_nav_buttons").find('li').length-1!=visitedStep.length){
           alert(translate_text.finish_all);
           return false;
        }
        $("#wpc_product_additional_comment").val($("#wpc_additional_comment_text").val());
        var single_variation_wrap=$('.variations_form').find('.single_variation_wrap');
        if ( $(single_variation_wrap).is( ':visible' ) ) {
            $('.variations_button').removeClass('wpc_hidden');}else{ $('.variations_button').addClass('wpc_hidden');}
        var finalData=zoomImage(canvasWidth/stage.getWidth());
        var designValue=$("#wpc_base_design_options").val();
        $('#wpc_final_design').block({
            message: '',
            overlayCSS: {
                border: 'none',
                padding: '0',
                margin: '0',
                backgroundColor: '#fff',
                opacity: 0.6,
                color: '#fff'
            }
        });
        if(designValue!=""){
            var data = {
                'action': 'wpc_get_design_data',
                'postId':designValue
            };
            $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
                var data=JSON.parse(response);
                var allObjects=designStage.getObjects(),
                    isBase=false,
                    saddle_image=data.wpc_hidden_saddle_design;
                for (var i = 0; i < allObjects.length; i++) {
                    if(allObjects[i].imageType=="base_image"){
                        isBase=true;
                    }
                    if(allObjects[i].title=="finalDesign"){
                        designStage.remove(allObjects[i]);
                        i--;
                    }
                }
                if(isBase){
                  var img=new Image;
                    img.src=saddle_image;
                    var actualHeight=img.height * data.wpc_saddle_scaleY,
                        actualWidth=img.width * data.wpc_saddle_scaleX;
                   var maxHeight=(actualHeight /designHeight) * designStage.getHeight() ,
                       maxWidth=(actualWidth /designWidth) * designStage.getWidth();
                    var ratio=1;
                    if(stage.getWidth() > maxWidth){
                        ratio=maxWidth / stage.getWidth();
                    }
                    if(stage.getHeight() >maxHeight){
                        ratio = maxHeight / stage.getHeight();
                    }
                    var tempTop=(data.wpc_saddle_pos_y/designHeight) * designStage.getHeight(),
                        tempLeft=(data.wpc_saddle_pos_x/designWidth) * designStage.getWidth();
                    var saddelImage=new Image;
                    var dataUrlForFinal=zoomImage(ratio);
                    saddelImage.onload = function () {
                        var imgbase64 = new fabric.Image(saddelImage, {
                            top: tempTop,
                            left: tempLeft,
                            hasControls: false,
                            hasBorders: false,
                            lockMovementX: true,
                            lockMovementY: true,
                            lockRotation: true,
                            lockScalingX: true,
                            lockScalingY: true,
                            lockUniScaling: true,
                            title: 'finalDesign'

                        });
                        designStage.add(imgbase64);
                    };
                    saddelImage.src = dataUrlForFinal;
                }
            });

        }else{
            designStage.clear();
            makeDesignResponsive();
            var ratio=1;
            if(stage.getWidth() > designStage.getWidth()){
                ratio=designStage.getWidth() / stage.getWidth();
            }
            if(stage.getHeight() >designStage.getHeight()){
                ratio = designStage.getHeight() / stage.getHeight();
            }
            var dataUrlForFinal=zoomImage(1);
            var saddelImage=new Image;
            saddelImage.onload = function () {
                var imgbase64 = new fabric.Image(saddelImage, {
                    top: 0,
                    left: 0,
                    scaleX: ratio,
                    scaleY: ratio,
                    hasControls: false,
                    hasBorders: false,
                    lockMovementX: true,
                    lockMovementY: true,
                    lockRotation: true,
                    lockScalingX: true,
                    lockScalingY: true,
                    lockUniScaling: true,
                    title: 'finalDesign'
                });
                designStage.add(imgbase64);
            };
            saddelImage.src = dataUrlForFinal;
        }
        var data = {
            'action': 'wpc_post_final_image',
            'imageData':finalData
        };
        $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
            $("#wpc_product_image_data").val(response);
            $('#wpc_final_design').unblock();
        })
    });
    $(document).on('keyup','#wpc_fake_qty',function() {
        $('.variations_form').find('.single_variation_wrap').find('.variations_button').find('.qty').val($(this).val());

    });
    $(document).on('click','#wpc_fake_add-to_cart',function(e) {
        e.preventDefault();
        $('.variations_form').find('.single_variation_wrap').find('.variations_button').find('.single_add_to_cart_button').trigger('click');
    });
     $(document).on('click','.wpc-scroll',function(e){
         e.preventDefault();
         var scrollTo= "#"+$(this).data("scroll"),
             windowwidth =$(window).width();
         if(windowwidth>767){
             $('html, body').animate({scrollTop:$(scrollTo).offset().top-100}, 'slow');
         }
         else{$('html, body').animate({scrollTop:$(scrollTo).offset().top}, 'slow');}
     });
     $(document).on('click','.wpc_finish_reset',function(e){
         e.preventDefault();
         var confirmation=confirm(translate_text.reset_text);
         if(confirmation){
             coming_from_reset=true;
            resetEverything(defaultValues);
             $('#attribute-tabs').responsiveTabs('activate', 0);
         }
     });
});