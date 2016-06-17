jQuery(function ($) {
    var canvasHeight = 800,
        canvasWidth = 800,
        cordScaleX=1,
        cordScaleY= 1,
        visitedStep=[],
        cords=[],
        colors=[],
        emb_positions={};
    var canvas = jQuery('#wpc_product_stage').children('canvas').get(0);
    var stage = new fabric.Canvas(canvas, {
        selection: false,
        hoverCursor: 'default',
        rotationCursor: 'default',
        centeredScaling: true
    });
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
                if(allObjects[i].objectType=="image"){
                    var tempFontSize=(emb_positions.fontSize/emb_positions.stageWidht) * stage.getWidth();
                    allObjects[i].set({fontSize:tempFontSize,left:tempLeft,top:tempTop});
                    emb_positions.fontSize=tempFontSize;
                }
                emb_positions.top=tempTop;
                emb_positions.left=tempLeft;
                emb_positions.stageHeight=stage.getHeight();
                emb_positions.stageWidth=stage.getWidth();
            }
            allObjects[i].setCoords();
            console.log(emb_positions)
        }
        stage.renderAll().calcOffset();
    };
    var loadBaseEdge=function(divId,imageType){
        var imageClasses=['base_image','texture_image'];
        var attribute=$("#"+divId).data("attribute");
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
    var removeColorCordsFromArray=function(attribute){
        if(typeof _.findWhere(colors,{attribute:attribute}!="undefined")){
            var newArray = _.without(colors, _.findWhere(colors, {attribute: attribute}));
            colors=newArray;
        }
        cords= _.without(cords, _.findWhere(cords, {attribute: attribute}));
    };
 var fetchImageData=function(attributeName){
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
      clearEmbControls();
    };
    var clearEmbControls=function(){
        $("#wpc_font_select").html("");
        $("#wpc_size_select").html("");
        $("#wpc_text_add").val("");
        $("#wpc_emb_colors").html("");
        $("#wpc_emb_postion_buttons").html("");
        $("#wpc_text_options").addClass("wpc_hidden");
        $("#wpc_emb_colors").addClass("wpc_hidden");
        $("#wpc_emb_postion_buttons").addClass("wpc_hidden");
        $("#wpc_image_upload").val("");
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
        positions["top"]=(top * stage.getHeight())/800;
        positions["left"]=(left * stage.getWidth())/800;
        return positions;
    };
    var getFontSize=function(size){
    return ((size*stage.getWidth())/800);
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
            termSlug=$this.data("term"),
            termId=$this.data("id");
       $this.closest('.attribute_loop').find('button').removeClass('atv');
       $this.addClass('atv');
       if($this.hasClass('wpc_no_cords')){
           $('#wpc_color_tab_'+attributeName).html('');
           $('#wpc_texture_tab_'+attributeName).html('');
           removeColorCordsFromArray(attributeName);
           fetchImageData(attributeName);
           removeImageFromCanvas(attributeName);
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
               'termId':termId,
               'model':defaultModel,
               'productId':productId
           };
           $.post(wpc_ajaxUrl.ajaxUrl, data, function(response) {
               $('#wpc_color_tab_'+attributeName).unblock();
               $('#wpc_color_tab_'+attributeName).html(response);
               if(typeof _.findWhere(colors,{attribute:attributeName})!="undefined"){
                   var colorData=_.findWhere(colors,{attribute:attributeName});
                  if($("#wpc_color_tab_"+attributeName+" .change_color[data-color='"+colorData.color+"']").length>0){
                      $("#wpc_color_tab_"+attributeName+" .change_color[data-color='"+colorData.color+"']").append('<i class="fa fa-check-circle"></i>')
                  }
               }

           });

           //Load Cord Images
           if (typeof _.findWhere(cords, {attribute: attributeName}) == "undefined") {
               cords.push({attribute:attributeName,term:termSlug});
           }else{
               var newArray = _.without(cords, _.findWhere(cords, {attribute: attributeName}));
               cords=newArray;
               cords.push({attribute:attributeName,term:termSlug});
           }
            fetchImageData(attributeName);
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
        if($this.hasClass("wpc_no_emb")){
            clearEmbTab();
            $("#embroidery_tab").addClass("wpc_hidden");
            return false;
        }
        if($this.hasClass("wpc_emb_buttons")){
            $("#embroidery_tab").removeClass("wpc_hidden");
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
        var attribute=$this.data("attribute"),
            colorValue=$this.data("color")
        if(typeof _.findWhere(colors,{attribute:attribute})=="undefined"){
            colors.push({attribute:attribute,color:colorValue});
        }else{
            var newArray = _.without(colors, _.findWhere(colors, {attribute: attribute}));
            colors=newArray;
            colors.push({attribute:attribute,color:colorValue});
        }
        colorCanvas(attribute,colorValue);
    });
    $(document).on("click",".wpc_emb_tabs",function(e){
        e.preventDefault();
        $this=$(this);
        $('.wpc_emb_controls').addClass("wpc_hidden");
        $('.wpc_emb_tabs').removeClass("atv");
        $($this.attr("href")).removeClass("wpc_hidden");
        $($this).addClass("atv");
        clearEmbControls();
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
                    backgroundColor: 'transparent',
                    opacity: 1,
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
                removeEmb();
                var position_x=$("#wpc_emb_postion_buttons").find(".active").data("left");
                var position_y=$("#wpc_emb_postion_buttons").find(".active").data("top");
                var actualPostions=getLogoPostions(position_y,position_x);
                var fontSize=getFontSize($("#wpc_size_select").val());
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
                    fontSize:fontSize
                });
                stage.add(comicSansText);
                emb_positions={objectType:"image",stageWidth:stage.getWidth(),stageHeight:stage.getHeight(),top:actualPostions.top,left:actualPostions.left,fontSize:fontSize};
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
                backgroundColor: 'transparent',
                opacity: 1,
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
                var position_x=$("#wpc_emb_postion_buttons").find(".active").data("left");
                var position_y=$("#wpc_emb_postion_buttons").find(".active").data("top");
                var actualPostions=getLogoPostions(position_y,position_x);
                new fabric.Image.fromURL(data.filepath, function (oImg) {
                    var imageHeight=data.sizes.height;
                    var imageWidth=data.sizes.width;
                    var tempWidth=(imageWidth/800) * stage.getWidth();
                    var tempHeight=(imageHeight/800) * stage.getHeight();
                    var actualScaleX=oImg.width > tempWidth ? tempWidth/oImg.width : 1;
                    var actualScaleY=oImg.height > tempHeight ? tempHeight/oImg.height : 1;
                    oImg.set({hasControls: false,hasBorders: false,lockMovementX: true,lockMovementY: true,lockRotation: true,lockScalingX: true,lockScalingY: true,lockUniScaling: true,left:actualPostions.left,top:actualPostions.top,scaleX: actualScaleX, scaleY: actualScaleY, title: 'extraContent', objectType: 'image'});
                    //emb_positions=[];
                    emb_positions={objectType:"image",stageWidth:stage.getWidth(),stageHeight:stage.getHeight(),top:actualPostions.top,left:actualPostions.left,scaleX:actualScaleX,scaleY:actualScaleY};
                    stage.add(oImg);
                    stage.calcOffset().renderAll();
                    $('#wpc_product_stage').unblock();
                });
            }
        });
    });
    $(document).on("click",".wpc_emb_btn",function(e){
        e.preventDefault();
        $this=$(this);
        if($this.hasClass("active"))return false;
        var position_x=$this.data("left"),
            position_y=$this.data("top"),
            actualPostions=getLogoPostions(position_y,position_x);
        $("#wpc_emb_postion_buttons").find(".active").removeClass("active");
        $this.addClass("active");
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
    });
    $(document).on('change', '#wpc_font_select', function () {
       $this=$(this);
        if($this.val()!=""){
            var objects = stage.getObjects();
            for (var i = 0; i < objects.length; i++) {
                if(objects[i].objectType=='text'){
                    objects[i].set({fontFamily: $this.val()});
                    break;
                }
            }
            stage.renderAll();
        }
    });
    $(document).on('change', '#wpc_size_select', function () {
        $this=$(this);
        if($this.val()!=""){
            var objects = stage.getObjects();
            var fontSize=getFontSize($this.val());
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
        }
    });
    $(document).on('click', '.change_color_emb', function (e) {
        e.preventDefault();
        $this=$(this);
        var color=$this.data('color'),
            all=$this.data('all').split("|"),
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
    });
    $(document).on('keyup','#wpc_text_add',function(event){
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
    $(document).on('click', '#wpc_bold_select', function (e) {
        e.preventDefault();
        var objects = stage.getObjects();
        for (var i = 0; i < objects.length; i++) {
            if(objects[i].objectType=='text'){
                switch (objects[i].fontWeight) {
                    case 'normal':
                        objects[i].set({
                            fontWeight: 'bold'
                        });
                        break;
                    case 'bold':
                        objects[i].set({
                            fontWeight: 'normal'
                        });
                        break;
                }
            }
        }
        stage.renderAll();
    });
    $(document).on('click', '#wpc_italic_select', function (e) {
        e.preventDefault();
        var objects = stage.getObjects();
        for (var i = 0; i < objects.length; i++) {
            if(objects[i].objectType=='text'){
                switch (objects[i].fontStyle) {
                    case '':
                        objects[i].set({
                            fontStyle: 'italic'
                        });
                        break;
                    case 'italic':
                        objects[i].set({
                            fontStyle: ''
                        });
                        break;
                }
            }
        }
        stage.renderAll();
    });
});