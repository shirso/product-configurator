jQuery(function ($) {
       // CloudZoom.quickStart();
        // Hide Add to Cart Button

        $('.variations_button').addClass('wpc_hidden');
        $('.color_picker').colorPicker({
            doRender: 'div',
            renderCallback: function($elm, toggled) {
                var colors = this.color.colors
                var hex="#"+colors.HEX;
                var objects = FinalStage.getObjects();
                for (var i = 0; i < objects.length; i++) {
                    if (objects[i].title == 'baseT') {
                        objects[i].set({opacity: 1});
                        objects[i].filters.push(new fabric.Image.filters.Tint({color: hex}))
                        objects[i].applyFilters(FinalStage.renderAll.bind(FinalStage))

                        break;
                    }
                }
                FinalStage.renderAll().calcOffset();
            }
        });
        $('#wpc_main_container').block({
            message: '<img src="'+wpc_loading.loading+'" />',
            overlayCSS: {
                border: 'none',
                padding: '0',
                margin: '0',
                backgroundColor: 'transparent',
                opacity: 1,
                color: '#fff'
            }
        });
        var selector = null;
        var selector_attribute = null;
        var pre_config = typeof this_is_main_kelma.pre_configure != "undefined" ? this_is_main_kelma.pre_configure : null;
        var color_dependency = typeof this_is_main_kelma.color_dependency != "undefined" ? this_is_main_kelma.color_dependency : null;
        var selected_term = null;
        var exclude_image = typeof this_is_main_kelma.exclude_image != "undefined" ? this_is_main_kelma.exclude_image : null;
        var exclude_color = typeof this_is_main_kelma.exclude_color != "undefined" ? this_is_main_kelma.exclude_color : null;
        var image_config = typeof this_is_main_kelma.image_config["_wpc_images"] != "undefined" ? this_is_main_kelma.image_config["_wpc_images"] : null;
        var color_config = typeof this_is_main_kelma.color_config["_wpc_colors"] != "undefined" ? this_is_main_kelma.color_config["_wpc_colors"] : null;
        var texture_config = typeof this_is_main_kelma.texture_config != "undefined" ? this_is_main_kelma.texture_config : null;
        var logo_size=typeof this_is_main_kelma.logo_size !="undefined" ? this_is_main_kelma.logo_size :null;
        var font_size=typeof this_is_main_kelma.font_size !='undefined' ? this_is_main_kelma.font_size :null;
        var canvas = jQuery('#wpc_product_stage').children('canvas').get(0);
        var selectedBase = null;
        var canvasHeight = 800;
        var canvasWidth = 800;
        var baseHeight = 300;
        var baseWidth = 500;
        var clickedAttributes = [];
        var storeColorMate = [];
        var storeTextureMate = [];
        var storePositions = [];
        var scaleAmount = 1;
        var currentElement = null;
        var baseColor=null;
        var tempPosX,tempPosY,tempScaleX,tempScaleY;
        var baseColorClicked=false;
        var selectedEmbroidery=null;
        var stage = new fabric.Canvas(canvas, {
            selection: false,
            hoverCursor: 'default',
            rotationCursor: 'default',
            centeredScaling: true
        });
        stage.setWidth($('#wpc_product_stage').width());
        stage.setHeight(canvasHeight * (canvasHeight / canvasWidth));
        var finalCanvas = $('#wpc_final_design').children('canvas').get(0);
        var FinalStage = new fabric.Canvas(finalCanvas, {
            selection: false,
            hoverCursor: 'default',
            rotationCursor: 'default',
            centeredScaling: true
        });
        FinalStage.setHeight(baseHeight * (baseHeight / baseWidth));
        FinalStage.setWidth(baseWidth);
        stage.on({
            'object:selected': function (opts) {
                activeObject(opts);
            },
            'object:moving': function (opts) {
              //  moveObject(opts);
            }
        })
        var activeObject = function (opts) {
            if (typeof opts.target != 'undefined') {
                if (opts.target.objectType == 'text') {
                    currentElement = opts.target;
                    $('#wpc_font_select').val(currentElement.fontFamily);
                    var selectedIndex = $('#wpc_font_select :selected').index();
                    if (selectedIndex < 0) {
                        $('#wpc_font_select').val('');
                    }
                    $('#wpc_color_select').val(currentElement.fill);
                    $('#wpc_size_select').val(currentElement.fontSize);
                    var selectedIndexSize = $('#wpc_size_select :selected').index();
                    if (selectedIndexSize < 0) {
                        $('#wpc_size_select').val('');
                    }

                }
            }
        }

        var moveObject = function (opts) {

            var object = opts.target;
            var id = object.get('id');
            if (typeof _.findWhere(storePositions, {id: id}) == "undefined") {
                //storePositions.push({attribute: attribute_name, term: term_name, color: color_code});
                storePositions.push({
                    id: object.get('id'),
                    width: stage.getWidth(),
                    height: stage.getHeight(),
                    top: object.get('top'),
                    left: object.get('left')
                });

            } else {
                var newArray = _.without(storePositions, _.findWhere(storePositions, {id: id}));
                storePositions = newArray;
                storePositions.push({
                    id: object.get('id'),
                    width: stage.getWidth(),
                    height: stage.getHeight(),
                    top: object.get('top'),
                    left: object.get('left')
                });
            }
        }
        var makeItResponsive = function () {
            var canvasContainerWidth = $("#wpc_product_stage").width();
            var HeightWidthRatio = canvasHeight > canvasContainerWidth ? (canvasContainerWidth / canvasHeight).toFixed(2) : 1;
            var canvasContainerHeight = canvasHeight * HeightWidthRatio;
            var stageHeight = stage.getHeight();
            var stageWidth = stage.getWidth();
            scaleAmount = canvasWidth > canvasContainerWidth ? 1 * (canvasContainerWidth / canvasWidth).toFixed(2) : 1;
            stage.setDimensions({width: canvasContainerWidth, height: canvasContainerHeight});
            var objects = stage.getObjects();
            for (var i in objects) {
                var tempScaleX,tempScaleY,tempLeft,tempTop;
                var objectId=objects[i].get('id');
                if (objects[i].title == 'extraContent') {
                    var positions = _.findWhere(storePositions, {id: objectId});
                    if (typeof positions != 'undefined') {
                        tempScaleX=(positions.scaleX/positions.width) * stage.getWidth();
                        tempScaleY=(positions.scaleY/positions.height) * stage.getHeight();
                        tempLeft=(positions.left / positions.width) * stage.getWidth();
                        tempTop=(positions.top / positions.height) * stage.getHeight();

                    }
                }else{
                    tempLeft=0;
                    tempTop=0;
                    tempScaleX=scaleAmount;
                    tempScaleY=scaleAmount;
                }
                objects[i].set({top: tempTop, left: tempLeft,scaleX:tempScaleX,scaleY:tempScaleY});
                objects[i].setCoords();
            }
            stage.renderAll().calcOffset();
        };
        var makeBaseResponsive = function (given_posX,given_posY,given_scaleX,given_scaleY) {
            var canvasContainerWidth = $("#wpc_final_design").width();
            $("#wpc_final_design").removeAttr('position');
            $("#wpc_final_design").removeAttr('visibility');
            var HeightWidthRatio = baseHeight > canvasContainerWidth ? (canvasContainerWidth / baseHeight).toFixed(2) : 1;
            var canvasContainerHeight = baseHeight * HeightWidthRatio;
            FinalStage.setDimensions({width: canvasContainerWidth, height: canvasContainerHeight});
            if(given_posX!="undefined" && given_posY!="undefined" && given_scaleX!="undefined" && given_scaleY!="undefined") {
                var posX = parseInt(given_posX);
                var posY = parseInt(given_posY);
                var actual_scaleX = parseFloat(given_scaleX);
                var actual_scaleY = parseFloat(given_scaleY);
                var objects = FinalStage.getObjects();
                var scaleX, scaleY, top, left;
                for (var i in objects) {
                    if (objects[i].title == 'baseT') {

                        scaleX = objects[i].width > canvasContainerWidth ? canvasContainerWidth / objects[i].width : 1;
                        scaleY = objects[i].height > canvasContainerHeight ? canvasContainerHeight / objects[i].height : 1;
                        top = 0;
                        left = 0;
                    } else {
                        scaleX = (actual_scaleX / 600) * canvasContainerWidth;
                        scaleY = (actual_scaleY / 400) * canvasContainerHeight;
                        top = (posY / 400) * canvasContainerHeight;
                        left = (posX / 600) * canvasContainerWidth;
                    }
                    objects[i].set({top: top, left: left, scaleX: scaleX, scaleY: scaleY});
                    objects[i].setCoords();
                }
                FinalStage.renderAll().calcOffset();
            }
        };
        //Window Load Functions
        $(window).load(function () {
            $(".attribute_loop a[data-attribute='" + color_dependency + "']").removeClass('wpc_terms').addClass('do_not_mess_with_it');
            $('#attribute-tabs').responsiveTabs({
                rotate: false,
                // startCollapsed: 'accordion',
                collapsible: 'accordion',
                activate: function (e, tab) {
                    selector = tab.selector;
                    selector_attribute = typeof selector != "undefined" && selector != null ? $(selector).data("attribute") : "";

                    //Check  attribute DIV
                    if (typeof $("#wpc_" + selector_attribute) != "undefined" || $("#wpc_" + selector_attribute).length > 0) {
                        if (typeof _.findWhere(clickedAttributes, {attribute: selector_attribute}) == "undefined") {
                            selected_term = typeof pre_config[selector_attribute] != "undefined" ? pre_config[selector_attribute] : "";

                        } else {
                            var getValue = _.findWhere(clickedAttributes, {attribute: selector_attribute});
                            selected_term = getValue.term;
                        }
                    }


                    if (typeof $("#wpc_" + selector_attribute).find(".attribute_loop").find(".wpc_term_" + selected_term) != "undefined" || $("#wpc_" + selector_attribute).find(".attribute_loop").find(".wpc_term_" + selected_term).length > 0) {
                        if ($("#wpc_" + selector_attribute).find(".attribute_loop").find(".wpc_term_" + selected_term).find('button').length > 0) {

                            var buttonTerm = $("#wpc_" + selector_attribute).find(".attribute_loop").find(".wpc_term_" + selected_term).find('button');
                            $(buttonTerm).trigger('click');
                        }
                        if ($("#wpc_" + selector_attribute).find(".attribute_loop").find(".wpc_term_" + selected_term).find('a').length > 0) {
                            var anchorTerm = $("#wpc_" + selector_attribute).find(".attribute_loop").find(".wpc_term_" + selected_term).find('a');

                            $(anchorTerm).trigger('click');
                        }
                    }
                    if($(selector).attr('id')=='wpc_finish_product_builder'){
                        $("#wpc_base_design_options").prop('selectedIndex', 0);
                        FinalStage.clear();
                       // $(".wpc_finish_product").attr('disabled','disabled');
                    }
                }
            });
            $('.variations_form').append($('#attribute-tabs').find('.wpc_extra_item'));
            if(typeof wpc_cart_redirect_items!='undefined' && wpc_cart_redirect_items!=null){
                var wpc_cart_variations=wpc_cart_redirect_items.variations;
                $.each(wpc_cart_variations,function(key,value){
                    var wpc_cart_attribute=key.substr(10,key.length);
                    var wpc_cart_term=value;
                    selector_attribute=wpc_cart_attribute;
                     $(".attribute_loop .do_not_mess_with_it[data-attribute='" + wpc_cart_attribute + "'][data-term='" + wpc_cart_term + "']").trigger('click');
                     $(".attribute_loop .wpc_terms[data-attribute='" + wpc_cart_attribute + "'][data-term='" + wpc_cart_term + "']").trigger('click');

                });
                embroideryAdd(wpc_cart_redirect_items);
            }
            $("#wpc_main_container").unblock();
        });

        var createSaddle = function () {
            var saddleWidth = $('#wpc_hidden_saddle_design').width();
            var saddleHeight = $('#wpc_hidden_saddle_design').height();
            var scaleXRatio = saddleWidth / stage.getWidth();
            var scaleYRatio = saddleHeight / stage.getHeight();
        }
        $('body').on('change', '#wpc_color_picker', function () {
            var objects = FinalStage.getObjects();
            for (var i = 0; i < objects.length; i++) {
                if (objects[i].title == 'baseT') {
                    objects[i].set({opacity: 1});
                    objects[i].filters.push(new fabric.Image.filters.Tint({color: $(this).val()}))
                    objects[i].applyFilters(FinalStage.renderAll.bind(FinalStage))

                    break;
                }
            }
            FinalStage.renderAll().calcOffset();
        })
        //Window Resize Functions
        $(window).resize(function () {
            makeItResponsive();
            makeBaseResponsive(tempPosX,tempPosY,tempScaleX,tempScaleY);
        })
        $(document).on('click', '.change_color', function (e) {
            e.preventDefault();
            var colorId = $(this).data('id');
            var color = $(this).data('color');
            var attribute_name = $(this).data('attribute');
            var term_name = $(this).data('term');
            var all = $(this).data("all").split('|');
            $('#wpc_extra_item_' + attribute_name).val(all[0]);

            if($(this).hasClass('this_is_base_color') && !baseColorClicked) {
                baseColorClicked=true;
                baseColor = color;
                colorWithBase(baseColor);
            }
            displayOptions('wpc1_attributes_values',attribute_name,all[0]);
            colorChange(colorId, color,selectedBase,attribute_name);
            storeColor(attribute_name, term_name, $(this).data("all"));
            $(this).parent().parent().find('i').remove();
            $(this).append('<i class="fa fa-check-circle"></i>');
        })
        $(document).on('click', '.change_texture', function (e) {
            e.preventDefault();
            var attribute_name = $(this).data('attribute');
            var term_name = $(this).data('term');
            var term_id = $(this).data('id');
            $('#wpc_extra_item_' + attribute_name).val($(this).data('texture'));
            storeTexture(attribute_name, term_id, $(this).data("texture"));
            displayOptions('wpc1_attributes_values',selector_attribute,$(this).data("texture"));
            $(this).parent().parent().find('i').remove();
            $(this).append('<i class="fa fa-check-circle"></i>');
            textureChange(selectedBase,attribute_name,$(this).data("texture"));
        })
        $(document).on('click', '.wpc_terms', function (e) {
            e.preventDefault();
                if(typeof wpc_cart_redirect_items!='undefined' && wpc_cart_redirect_items!=null){
                    var wpc_cart_extra_items=wpc_cart_redirect_items.extra_items;
                    var cartColorOrTexture= wpc_cart_extra_items[selector_attribute];
                }

            if ($(this).hasClass('atv') &&  wpc_cart_redirect_items==null) {
                return false;
            }
            if($(this).hasClass('embroidery_buttons')){
                    $("#embroidery_tab").removeClass('wpc_hidden');

            }else if($(this).hasClass('no_embroidery')){
                hideControls();
            }
            $("#" + $(this).data('attribute')).focusin().val($(this).data('term')).change();
            $(this).parent().parent().find('li').find('button').removeClass('atv');
            if ($(this).hasClass('texture_button')) {
                $("#wpc_color_tab_" + selector_attribute).html('');
                var checkTextureValue = checkTexture(selectedBase, selector_attribute, $(this).data('id'));
                if ($(selector).find('.c-select_' + selectedBase + '_' + selector_attribute).find("[data-texture='" + checkTextureValue + "']").length) {
                    $(selector).find('.c-select_' + selectedBase + '_' + selector_attribute).find("[data-texture='" + checkTextureValue + "']").trigger('click');
                }
                $("#wpc_texture_tab_" + selector_attribute).html($(selector).find('.c-select_' + selectedBase + '_' + selector_attribute).html());

            } else {
                $("#wpc_color_tab_" + selector_attribute).html($(selector).find('.' + selector_attribute + '_' + $(this).data("term")).closest('li').find('.' + selectedBase + '_' + selector_attribute).html());
            }

            $(this).addClass('atv');
            displayOptions('wpc_attributes_values',selector_attribute,$(this).data('display'));
            storeClick(selector_attribute,$(this).data('term'));
            //(todo: fix texture change and no cord)
            if ( !_.contains(exclude_image, selector_attribute)  && typeof image_config[selectedBase][selector_attribute][$(this).data("term")]["no_image"] != "undefined") {
              //  imageRemove(selectedBase + '_' + selector_attribute + '_background');
                if(!$(this).hasClass('texture_button')){
                displayOptions('wpc1_attributes_values',selector_attribute,'');
                }

                imageRemove(selectedBase + '_' + selector_attribute + '_foreground');
                colorChangeSingle(selectedBase + '_' + selector_attribute + '_background', baseColor);
                if ($("#wpc_color_tab_" + selector_attribute).length > 0) {
                    $("#wpc_color_tab_" + selector_attribute).html('');
                }
                $('#wpc_extra_item_' + $(this).data('attribute')).val('');
                return false;
            }

            if(typeof cartColorOrTexture!='undefined'){
                if($(this).parent().find('.' + selectedBase + '_' + selector_attribute).find("[data-colorname='" + cartColorOrTexture + "']").length>0){
                    $(this).parent().find('.' + selectedBase + '_' + selector_attribute).find("[data-colorname='" + cartColorOrTexture + "']").trigger('click');
                }
            }
            if ($(selector).find('.' + selector_attribute + '_' + $(this).data("term")).closest('li').find('.' + selectedBase + '_' + selector_attribute).length > 0) {

                var checkColorValue = checkColor(selectedBase, selector_attribute, $(this).data('term'));

                if ($(selector).find('.' + selector_attribute + '_' + $(this).data("term")).closest('li').find('.' + selectedBase + '_' + selector_attribute).find("[data-all='" + checkColorValue + "']").length > 0) {
                    $(selector).find('.' + selector_attribute + '_' + $(this).data("term")).closest('li').find('.' + selectedBase + '_' + selector_attribute).find("[data-all='" + checkColorValue + "']").trigger('click');
                }

                if ($(this).hasClass('texture_button')) {

                } else {
                    $("#wpc_texture_tab_" + selector_attribute).html('');
                    $("#wpc_color_tab_" + selector_attribute).html($(selector).find('.' + selector_attribute + '_' + $(this).data("term")).closest('li').find('.' + selectedBase + '_' + selector_attribute).html());
                }

                //

            }
        });

        $(document).on('change', '#wpc_image_upload', function (e) {
            // alert($(this).val());
            var reg = /(.jpg|.gif|.png)$/;
            if (!reg.test($("#input-file").val())) {
                // return false;
            }
            uploadFile();

        });
        $(document).on('click', '.do_not_mess_with_it', function (e) {
            e.preventDefault();
            if ($(this).parent().find('i').length > 0) {
                return false;
            }
            resetAll();
            $("#" + $(this).data('attribute')).focusin().val($(this).data('term')).change();
            selectedBase = $(this).data('term');
            displayOptions('wpc_attributes_values',$(this).data('attribute'),$(this).data('display'));
            $(this).parent().parent().find('i').remove();
            $(this).parent().append('<i class="fa fa-check"></i>')
            stage.clear();
            loadImages();
            makeItResponsive();
        });

        $(document).on('click',".wpc_finish_reset", function(e){
            e.preventDefault();
            resetAll();
            $('#attribute-tabs').responsiveTabs('activate', 0);
            $('.do_not_mess_with_it').parent().find('i').remove();
            selected_term = typeof pre_config[selector_attribute] != "undefined" ? pre_config[selector_attribute] : "";
            if ($("#wpc_" + selector_attribute).find(".attribute_loop").find(".wpc_term_" + selected_term).find('a').length > 0) {
                var anchorTerm = $("#wpc_" + selector_attribute).find(".attribute_loop").find(".wpc_term_" + selected_term).find('a');
                $(anchorTerm).trigger('click');
            }

        });

        $(document).on('click', '.wpc_finish_product', function (e) {
            e.preventDefault();
            stage.deactivateAllWithDispatch();
            var stageWidth = stage.getWidth();
            var stageHeight = stage.getHeight();
            var zoomValue=stage.getWidth()< 800? 800/stage.getWidth() :1;
            var dataUrl=zoomImage(zoomValue);
            var dataUrlForFinal = stage.toDataURL({
                format: 'png',
                quality: 1
            });
            $("#wpc_product_image_data").val(dataUrl);
            var single_variation_wrap=$('.variations_form').find('.single_variation_wrap');
                if ( $(single_variation_wrap).is( ':visible' ) ) {
            $('.variations_button').removeClass('wpc_hidden');}else{ $('.variations_button').addClass('wpc_hidden');}
          /* var windowwidth =$(window).width();
            if(windowwidth>767){
                $('html, body').animate({scrollTop:$(".variations_button").offset().top-100}, 'slow');
            }
            else{$('html, body').animate({scrollTop:$(".variations_button").offset().top-30}, 'slow');}*/
            if($("#wpc_base_design_options").val()!=''){
                var fnObjects = FinalStage.getObjects();
                for (var i = 0; i < fnObjects.length; i++) {
                    if (typeof fnObjects[i] != "undefined" && fnObjects[i].title == 'finalDesign') {
                        FinalStage.remove(fnObjects[i]);
                    }
                    if (typeof fnObjects[i] != "undefined" && fnObjects[i].title == 'saddleT') {
                        var objWidth = fnObjects[i].getWidth(),
                            objHeight = fnObjects[i].getHeight(),
                            objScaleTop = fnObjects[i].get('top'),
                            objScaleLeft = fnObjects[i].get('left');
                            fnObjects[i].set('opacity', 0);
                            var img = new Image();
                            var actualScaleX = (objWidth / stageWidth);
                            var actualScaleY = (objHeight / stageHeight);

                        img.onload = function () {
                            var imgbase64 = new fabric.Image(img, {
                                top: objScaleTop,
                                left: objScaleLeft,
                                scaleX: actualScaleX,
                                scaleY: actualScaleY,
                                lockMovementX: true,
                                lockMovementY: true,
                                lockRotation: true,
                                lockScalingX: true,
                                lockScalingY: true,
                                lockUniScaling: true,
                                title: 'finalDesign'

                            });
                            FinalStage.add(imgbase64);
                        }
                        img.src = dataUrlForFinal;
                    }
                    FinalStage.renderAll().calcOffset();
                }
            }
            var tempCanvas = [];
            var actualObjects = stage.getObjects();
            for (var i = 0; i <= actualObjects.length; i++) {
                if (typeof actualObjects[i] != "undefined") {
                    tempCanvas.push({left: actualObjects[i].get('left'), top: actualObjects[i].get('top')})
                }
            }
        })
        $(document).on('keyup','#wpc_fake_qty',function() {
            $('.variations_form').find('.single_variation_wrap').find('.variations_button').find('.qty').val($(this).val());

        })

    $(document).on('click','#wpc_fake_add-to_cart',function(e) {
        e.preventDefault();
        $('.variations_form').find('.single_variation_wrap').find('.variations_button').find('.single_add_to_cart_button').trigger('click');


    })
        $(document).on('click', '#wpd_add_text_btn', function (e) {
            e.preventDefault();
            var textToPut = $('#wpc_text_add').val().trim();
            if(textToPut !=''){
                clearAll();
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
                    lockUniScaling: true
                });
                comicSansText.set({top: 0, left: 0, id: guid()});
                stage.add(comicSansText);
                $("#wpc_emb__options_text").text(textToPut);
                $("#wpc_emb__options_text").parent().removeClass('wpc_hidden');
                $('#wpc_text_add').val('');

            selectedEmbroidery="text";
            clickPositionButton();
            showControls('text');
            changeEmbType('text');
            displayEmbOptions('text');
            putEmbValues('wpc_product_emb_text',textToPut);
            }
        })
        $(document).on('change', '#wpc_font_select', function () {
            var Selected = $(this).val();
                if(selectedEmbroidery=='text' && Selected!=''){
                    var objects = stage.getObjects();
                    for (var i = 0; i < objects.length; i++) {
                        if(objects[i].objectType=='text'){
                            objects[i].set({fontFamily: Selected});
                            break;
                        }
                    }
                stage.renderAll();
                $("#wpc_emb__options_font").text($('#wpc_font_select option:selected').text());
                $("#wpc_emb__options_font").parent().removeClass('wpc_hidden');
                putEmbValues('wpc_product_emb_font',$('#wpc_font_select option:selected').text());
            }
        })
        $(document).on('change', '#wpc_size_select', function () {
            var Selected = $(this).val();
            if(selectedEmbroidery=='text' && Selected!=''){
                var objects = stage.getObjects();
                for (var i = 0; i < objects.length; i++) {
                    if(objects[i].objectType=='text'){
                        objects[i].set({fontSize: Selected});
                        break;
                    }
                }
                stage.renderAll();
                $("#wpc_emb__options_fontsize").text($('#wpc_size_select option:selected').text());
                $("#wpc_emb__options_fontsize").parent().removeClass('wpc_hidden');
                putEmbValues('wpc_product_emb_font_size',$('#wpc_size_select option:selected').text());
            }
        })
        $(document).on('click', '.change_color_emb', function (e) {
            e.preventDefault();
                if(selectedEmbroidery=='text'){
                    var color=$(this).data('color');
                    var all=$(this).data('all').split("|");
                    //var colorSplit=
                    var colorName=all[0];
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
                    $("#wpc_emb__options_fontcolor").text(colorName);
                    $("#wpc_emb__options_fontcolor").parent().removeClass('wpc_hidden');
                    putEmbValues('wpc_product_emb_color',colorName);
                }
        })
        $(document).on('click', '#wpc_bold_select', function (e) {
            e.preventDefault();
            if(selectedEmbroidery=='text'){
                var displayText=$(this).attr('title');
                var objects = stage.getObjects();
                for (var i = 0; i < objects.length; i++) {
                    if(objects[i].objectType=='text'){
                        switch (objects[i].fontWeight) {
                            case 'normal':
                                objects[i].set({
                                    fontWeight: 'bold'
                                });
                                $("#wpc_emb__options_fontweight").text(displayText);
                                $("#wpc_emb__options_fontweight").parent().removeClass('wpc_hidden');
                                putEmbValues('wpc_product_emb_font_weight',displayText);
                                break;
                            case 'bold':
                                objects[i].set({
                                    fontWeight: 'normal'
                                });
                                $("#wpc_emb__options_fontweight").text('');
                                $("#wpc_emb__options_fontweight").parent().addClass('wpc_hidden');
                                putEmbValues('wpc_product_emb_font_weight','');
                                break;
                        }
                        break;
                    }
                }
                stage.renderAll();
            }
        })
        $(document).on('click', '#wpc_italic_select', function (e) {
            e.preventDefault();
            if(selectedEmbroidery=='text'){
                var displayText=$(this).attr('title');
                var objects = stage.getObjects();
                for (var i = 0; i < objects.length; i++) {
                    if(objects[i].objectType=='text'){
                        switch (objects[i].fontStyle) {
                            case '':
                                objects[i].set({
                                    fontStyle: 'italic'
                                });
                                $("#wpc_emb__options_fontstyle").text(displayText);
                                $("#wpc_emb__options_fontstyle").parent().removeClass('wpc_hidden');
                                putEmbValues('wpc_product_emb_font_style',displayText);
                                break;
                            case 'italic':
                                objects[i].set({
                                    fontStyle: ''
                                });
                                $("#wpc_emb__options_fontstyle").text('');
                                $("#wpc_emb__options_fontstyle").parent().addClass('wpc_hidden');
                                putEmbValues('wpc_product_emb_font_style','');
                                break;
                        }
                        break;
                    }
                }
                stage.renderAll();
            }
        });
        $(document).on('change','#wpc_base_design_options',function(){
            FinalStage.clear();
         if($(this).val()!=""){
            // $('.wpc_finish_product').removeAttr('disabled');
                var designObject=$("#wpc_design_data_"+$(this).val());
                //var imageBase = document.querySelector("#base_design");
             var baseInstance = new fabric.Image($(designObject).find('.base_image').children('img').get(0), {
                 hasControls: false,
                 hasBorders: false,
                 lockMovementX: true,
                 lockMovementY: true,
                 lockRotation: true,
                 lockScalingX: true,
                 lockScalingY: true,
                 lockUniScaling: true,
                 title: 'baseT'
             });
             FinalStage.add(baseInstance).renderAll();
                 var saddle_posX = parseInt($(designObject).find('.wpc_left').val());
                 var saddle_posY = parseInt($(designObject).find('.wpc_top').val());
                 var saddle_scale=parseFloat($(designObject).find('.wpc_scale').val());
                 var saddle_scaleX=parseFloat($(designObject).find('.wpc_scaleX').val());
                 var saddle_scaleY=parseFloat($(designObject).find('.wpc_scaleY').val());
                 var saddleInstance = new fabric.Image($(designObject).find('.saddle_image').children('img').get(0), {
                     top: saddle_posY,
                     left: saddle_posX,
                     scaleX:saddle_scaleX,
                     scaleY:saddle_scaleY,
                     hasControls: false,
                     hasBorders: false,
                     lockMovementX: true,
                     lockMovementY: true,
                     lockRotation: true,
                     lockScalingX: true,
                     lockScalingY: true,
                     lockUniScaling: true,
                     title: 'saddleT'
                 });
                 FinalStage.add(saddleInstance);
                 FinalStage.renderAll().calcOffset();
                //setTimeout( makeBaseResponsive(),1000);
             tempPosX=saddle_posX;
             tempPosY=saddle_posY;
             tempScaleX=saddle_scaleX;
             tempScaleY=saddle_scaleY;
             makeBaseResponsive(saddle_posX,saddle_posY,saddle_scaleX,saddle_scaleY);

         }else{
            // $('.wpc_finish_product').attr('disabled','disabled');
             FinalStage.clear();
         }
        });
        $('#wpc_product_stage').click(
            function(e) {
                var wndowidth = $(window).width();
                if(wndowidth<767){ return false};
                $('#wpc_product_stage').block({
                    message: '<img src="'+wpc_loading.loading+'" />',
                    overlayCSS: {
                        border: 'none',
                        padding: '0',
                        margin: '0',
                        backgroundColor: 'transparent',
                        opacity: 1,
                        color: '#fff'
                    }
                });
               var position= $(this).offset();
                var dataUrl = '';
               dataUrl = stage.toDataURL({
                    format: 'png',
                    quality: 1
                });

               var anchor='<img class="cloudzoom"  id ="zoom1" src="'+dataUrl+'" data-cloudzoom=\'zoomImage:"'+zoomImage(2)+'",zoomSizeMode:"image",autoInside: 550\' />'
               var div='<div id="tempForCloud" style="top:'+position.top+'px;left:'+position.left+'px;width:'+$(this).width()+'px;height:'+$(this).height()+'px;z-index:100;position:absolute">'+anchor+'</div>';
              $('body').append(div);
                var options={};
                $('#zoom1').CloudZoom();
                $('#zoom1').bind('cloudzoom_ready',function(){  $('#wpc_product_stage').unblock();});
                $('#zoom1').bind('cloudzoom_end_zoom',function(){$('#tempForCloud').remove()});
                $('#zoom1').bind('click',function(){
                    var cloudZoom = $(this).data('CloudZoom');
                    cloudZoom.closeZoom();
                    //$("#wpc_zoom_canvas").trigger('click')
                    stage.deactivateAllWithDispatch();
                    var dataUrl = zoomImage(2);
                    $.magnificPopup.open({
                        items: {src: dataUrl},
                        type: 'image'
                    }, 0);
                });
            }
        );
        $(document).on('click','.wpc_emb_tabs',function(e){
            e.preventDefault();
            var tabType=$(this).data('type');
            showControls(tabType);
            $('.wpc_emb_tabs').removeClass('active');
            $(this).addClass('active');
            var targetDiv=$(this).attr('href');
            $('.wpc_emb_controls').addClass('wpc_hidden');
            $(targetDiv).removeClass('wpc_hidden');
            changeEmbType(tabType);
        })
        $(document).on('click','.wpc_emb_btn',function(e){
            e.preventDefault();
            $('.wpc_emb_btn').removeClass('active');
            $(this).addClass('active');
            var left=parseFloat( $(this).data('left'));
            var top=parseFloat($(this).data('top'));
            var objects = stage.getObjects();
            for (var i = 0; i < objects.length; i++) {
                if(objects[i].title=='extraContent'){
                    var tempLeft=(left/800) * stage.getWidth();
                    var tempTop=(top/800) * stage.getHeight();
                    objects[i].set({top:tempTop,left:tempLeft});
                    objects[i].setCoords();
                    if (typeof _.findWhere(storePositions, {id: objects[i].get('id')}) == "undefined") {
                        storePositions.push({
                            id: objects[i].get('id'),
                            width: stage.getWidth(),
                            height: stage.getHeight(),
                            top: objects[i].get('top'),
                            left: objects[i].get('left'),
                            scaleX:objects[i].get('scaleX'),
                            scaleY:objects[i].get('scaleY')
                        });
                    }else{
                        var newArray = _.without(storePositions, _.findWhere(storePositions, {id: objects[i].get('id')}));
                        storePositions = newArray;
                        storePositions.push({
                            id: objects[i].get('id'),
                            width: stage.getWidth(),
                            height: stage.getHeight(),
                            top: objects[i].get('top'),
                            left: objects[i].get('left'),
                            scaleX:objects[i].get('scaleX'),
                            scaleY:objects[i].get('scaleY')
                        });
                    }
                    break;
                }
            }
            stage.renderAll().calcOffset();
            $("#wpc_emb__options_position").text($(this).text());
            $("#wpc_emb__options_position").parent().removeClass('wpc_hidden');
            putEmbValues('wpc_product_emb_position',$(this).text());

        })
        $(document).on('click','.wpc_clear_all',function(e){
            e.preventDefault();
            clearEmb();
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
    $(document).on('click','.wpc-scroll',function(e){
        e.preventDefault();
         var scrolTo= "#"+$(this).data("scroll"),
        //console.log($(scrolTo));
        windowwidth =$(window).width();
        if(windowwidth>767){
        $('html, body').animate({scrollTop:$(scrolTo).offset().top-100}, 'slow');
        }
        else{$('html, body').animate({scrollTop:$(scrolTo).offset().top}, 'slow');}
    });
        $(document).on('click','#wpc_emb_extra_comment_button',function(e){
            var text=$("#wpc_emb_extra_comment_text").val();
            if(text!=''){
                $("#wpc_emb__options_extra_comment").text(text);
                $("#wpc_product_extra_comment").val(text);
                $("#wpc_emb__options_extra_comment").parent().removeClass('wpc_hidden');
                $("#wpc_emb_extra_comment_text").val('');
            }
        });
        var embroideryAdd=function(params){

            if(typeof params.extra_items.wpc_product_extra_comment!="undefined"){
                $("#wpc_emb__options_extra_comment").text(params.extra_items.wpc_product_extra_comment);
                $("#wpc_product_extra_comment").val(params.extra_items.wpc_product_extra_comment);
                $("#wpc_emb__options_extra_comment").parent().removeClass('wpc_hidden');
            }
            if(typeof params.extra_items.wpc_product_emb_type!="undefined"){
                var type=null;
                $('.wpc_emb_tabs').each ( function() {
                    if($(this).text()==params.extra_items.wpc_product_emb_type){
                        type=$(this).data('type');
                        $(this).trigger('click');
                        selectedEmbroidery=type;
                        showControls(type);
                        changeEmbType(type);
                        displayEmbOptions(type);
                        return false;
                     }
                });
                if(type!=null){
                    if(type=='text'){
                       if(typeof params.extra_items.wpc_product_emb_text!="undefined"){
                           var textToPut=params.extra_items.wpc_product_emb_text;
                           clearAll();
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
                               lockUniScaling: true
                           });
                           comicSansText.set({top: 0, left: 0, id: guid()});
                           stage.add(comicSansText);
                           $("#wpc_emb__options_text").text(textToPut);
                           $("#wpc_emb__options_text").parent().removeClass('wpc_hidden');
                           putEmbValues('wpc_product_emb_text',textToPut);
                       }
                        if(typeof params.extra_items.wpc_product_emb_position!="undefined"){
                                $('.wpc_emb_btn').each(function(){
                                   if($(this).text()==params.extra_items.wpc_product_emb_position){
                                       $(this).trigger('click');
                                       return false;
                                   }
                                });
                        }
                        if(typeof params.extra_items.wpc_product_emb_font!="undefined"){
                            $("#wpc_font_select option").each(function(){
                              if($(this).text()==params.extra_items.wpc_product_emb_font){
                                  $("#wpc_font_select").val($(this).val()).change();
                                  return false;
                              }
                            });
                        }
                        if(typeof params.extra_items.wpc_product_emb_font_size!="undefined"){
                            $("#wpc_size_select option").each(function(){
                                if($(this).text()==params.extra_items.wpc_product_emb_font_size){
                                    $("#wpc_size_select").val($(this).val()).change();
                                    return false;
                                }
                            });
                        }
                        if(typeof params.extra_items.wpc_product_emb_color!="undefined"){
                                if( $("#wpc_emb_colors").find("[data-colorname='" + params.extra_items.wpc_product_emb_color + "']").length>0){$("#wpc_emb_colors").find("[data-colorname='" + params.extra_items.wpc_product_emb_color + "']").trigger('click');}
                        }
                        if(typeof params.extra_items.wpc_product_emb_font_weight!="undefined"){
                            $("#wpc_bold_select").trigger('click')
                        }
                        if(typeof params.extra_items.wpc_product_emb_font_style!="undefined"){
                            $("#wpc_italic_select").trigger('click')
                        }
                    }else if(type=='image'){

                              if(typeof params.extra_items.wpc_product_emb_image!="undefined" && typeof params.extra_items.wpc_product_emb_position!="undefined"){
                                    processImage(params.extra_items.wpc_product_emb_image,params.extra_items.wpc_product_emb_position);
                                }

                    }
                }
            }
        }
        var showControls=function(type){
            if(typeof selectedEmbroidery!='undefined' && selectedEmbroidery!=null){
                $("#wpc_emb_postion_buttons").addClass('wpc_hidden');
                    if(selectedEmbroidery=='image' && type=='image'){
                        $("#wpc_text_options").addClass('wpc_hidden');
                        $("#wpc_emb_colors").addClass('wpc_hidden');
                        $("#wpc_emb_postion_buttons").removeClass('wpc_hidden');
                        $("#wpc_emb_colors").find('i').remove();
                        $("#wpc_font_select").val('').change();
                        $("#wpc_size_select").val('').change();
                        $('.wpc_emb_cart_text').val('');
                    }else if(selectedEmbroidery=='text' && type=='text'){
                        $("#wpc_text_options").removeClass('wpc_hidden');
                        $("#wpc_emb_colors").removeClass('wpc_hidden');
                        $("#wpc_emb_postion_buttons").removeClass('wpc_hidden');
                        $('.wpc_emb_cart_image').val('');
                    }
            }
        }
        var putEmbValues=function(controlId,value){
            $("#"+controlId).val(value);
        }
        var hideControls=function(){
            clearAll();
            $("#wpc_emb_postion_buttons").addClass('wpc_hidden');
            $('.wpc_emb_controls').addClass('wpc_hidden');
            $('.wpc_emb_tabs').removeClass('active');
            $("#embroidery_tab").addClass('wpc_hidden');
            $("#wpc_text_options").addClass('wpc_hidden');
            $("#wpc_emb_colors").addClass('wpc_hidden');
            $('.wpc_hidden_emb').val('');
            $("#wpc_emb_colors").find('i').remove();
            $("#wpc_font_select").val('').change();
            $("#wpc_size_select").val('').change();
            selectedEmbroidery=null;
            displayEmbOptions();
        }
        var clearEmb=function(){
            clearAll();
            $("#wpc_emb_postion_buttons").addClass('wpc_hidden');
            $('.wpc_emb_controls').addClass('wpc_hidden');
            $('.wpc_emb_tabs').removeClass('active');
            $("#wpc_text_options").addClass('wpc_hidden');
            $("#wpc_emb_colors").addClass('wpc_hidden');
            $('.wpc_hidden_emb').val('');
            $("#wpc_emb_colors").find('i').remove();
            $("#wpc_font_select").val('').change();
            $("#wpc_size_select").val('').change();
            selectedEmbroidery=null;
            displayEmbOptions();

        }
        var resetAll=function(){
             selector = null;
             selector_attribute = null;
             selectedBase = null;
             clickedAttributes = [];
             storeColorMate = [];
             storeTextureMate = [];
             storePositions = [];
             scaleAmount = 1;
             currentElement = null;
             baseColor=null;
            $('.wpc_extra_item').val('');
            $('.wpc_options_display').find('tr').addClass('wpc_hidden');
            $('.variations_button').addClass('wpc_hidden');

        }
        var loadImages = function () {
            console.log(pre_config);
            $.each(pre_config, function (k, v) {

                if (!_.contains(exclude_image, k)) {
                    var imageObject = $("#wpc_images_configur").find("#wpc_layout_" + selectedBase).find("#wpc_attribute_" + k).find("#wpc_term_" + v);
                    var textureObject = $("#wpc_images_configur").find("#wpc_layout_" + selectedBase).find("#wpc_attribute_" + k).find(".texture_images");
                    var tempImg = false;
                    if ($(imageObject).find('.wpc_background').length > 0 && $(imageObject).find('.wpc_background').attr('src') != "") {
                        tempImg = true;
                        var imgInstance = new fabric.Image($(imageObject).children('.wpc_background').get(0), {
                            hasControls: false,
                            hasBorders: false,
                            lockMovementX: true,
                            lockMovementY: true,
                            lockRotation: true,
                            lockScalingX: true,
                            lockScalingY: true,
                            lockUniScaling: true,
                            imgType:'background',
                            term:$(imageObject).find('.wpc_background').data('term'),
                            title: $(imageObject).find('.wpc_background').data('id'),
                            id: guid()
                        });
                        stage.add(imgInstance);
                    }
                    if ($(imageObject).find('.wpc_foreground').length > 0 && $(imageObject).find('.wpc_foreground').attr('src') != "") {
                        tempImg = true;
                        var imgInstance1 = new fabric.Image($(imageObject).children('.wpc_foreground').get(0), {
                            hasControls: false,
                            hasBorders: false,
                            lockMovementX: true,
                            lockMovementY: true,
                            lockRotation: true,
                            lockScalingX: true,
                            lockScalingY: true,
                            lockUniScaling: true,
                            imgType:'foreground',
                            term:$(imageObject).find('.wpc_foreground').data('term'),
                            title: $(imageObject).find('.wpc_foreground').data('id'),
                            id: guid()
                        });
                        stage.add(imgInstance1);
                    }
                    if ($(textureObject).length) {
                        $(textureObject).find('.texture_contents').each(function () {
                            if ($(this).find('img').attr('src') != '') {
                                var imgInstanceT = new fabric.Image($(this).children('.texture').get(0), {
                                    hasControls: false,
                                    hasBorders: false,
                                    lockMovementX: true,
                                    lockMovementY: true,
                                    lockRotation: true,
                                    lockScalingX: true,
                                    lockScalingY: true,
                                    lockUniScaling: true,
                                    opacity: 0,
                                    imgType:'texture',
                                    title: $(this).data('id'),
                                    class: $(this).data('class'),
                                    id: guid()
                                });
                                stage.add(imgInstanceT);
                            }
                        })
                    }
                  //Load Other Term Images
                    var attributeDiv=$("#wpc_images_configur").find("#wpc_layout_" + selectedBase).find("#wpc_attribute_" + k);
                    var imageDivs=$(attributeDiv).find('.wpc_image_div');
                    $(imageDivs).each(function(){
                        if($(this).attr('id')!="wpc_term_" + v){
                            var self=this;
                            if ($(self).find('.wpc_background').length > 0 && $(self).find('.wpc_background').attr('src') != "") {
                                var imgInstanceBack = new fabric.Image($(self).children('.wpc_background').get(0), {
                                    hasControls: false,
                                    hasBorders: false,
                                    lockMovementX: true,
                                    lockMovementY: true,
                                    lockRotation: true,
                                    lockScalingX: true,
                                    lockScalingY: true,
                                    lockUniScaling: true,
                                    imgType:'background',
                                    opacity:0,
                                    term:$(self).find('.wpc_background').data('term'),
                                    title: $(self).find('.wpc_background').data('id'),
                                    id: guid()
                                });
                                stage.add(imgInstanceBack);
                            }
                            if ($(self).find('.wpc_foreground').length > 0 && $(self).find('.wpc_foreground').attr('src') != "") {
                                var imgInstanceFront = new fabric.Image($(self).children('.wpc_foreground').get(0), {
                                    hasControls: false,
                                    hasBorders: false,
                                    lockMovementX: true,
                                    lockMovementY: true,
                                    lockRotation: true,
                                    lockScalingX: true,
                                    lockScalingY: true,
                                    lockUniScaling: true,
                                    imgType:'background',
                                    opacity:0,
                                    term:$(self).find('.wpc_foreground').data('term'),
                                    title: $(self).find('.wpc_foreground').data('id'),
                                    id: guid()
                                });
                                stage.add(imgInstanceFront);
                            }
                        }
                    });

                    // Load other Images
                    if (!tempImg) {
                        var parentObj = $(imageObject).parent();
                        var tempImageObj = $(parentObj).find('.wpc_image_div[data-no=0]').first();
                        if ($(tempImageObj).find('.wpc_background').length > 0 && $(tempImageObj).find('.wpc_background').attr('src') != "") {
                            var imgInstance2 = new fabric.Image($(tempImageObj).children('.wpc_background').get(0), {
                                hasControls: false,
                                hasBorders: false,
                                lockMovementX: true,
                                lockMovementY: true,
                                lockRotation: true,
                                lockScalingX: true,
                                lockScalingY: true,
                                lockUniScaling: true,
                                imgType:'background',
                                opacity: 1,
                                term:$(imageObject).find('.wpc_background').data('term'),
                                title: $(tempImageObj).find('.wpc_background').data('id'),
                                id: guid()
                            });

                            stage.add(imgInstance2);
                        }
                        if ($(tempImageObj).find('.wpc_foreground').length > 0 && $(tempImageObj).find('.wpc_foreground').attr('src') != "") {
                            var imgInstance3 = new fabric.Image($(tempImageObj).children('.wpc_foreground').get(0), {
                                hasControls: false,
                                hasBorders: false,
                                lockMovementX: true,
                                lockMovementY: true,
                                lockRotation: true,
                                lockScalingX: true,
                                lockScalingY: true,
                                lockUniScaling: true,
                                imgType:'foreground',
                                opacity: 0,
                                term:$(imageObject).find('.wpc_foreground').data('term'),
                                title: $(tempImageObj).find('.wpc_foreground').data('id'),
                                id: guid()
                            });

                            stage.add(imgInstance3);
                        }
                    }

                }
            })
            stage.renderAll().calcOffset();
        }
        var colorChange = function (dataId, color,baseName, attributeName, termName) {
            var objects = stage.getObjects();
            var Class=baseName+'_'+attributeName;
            for (var i = 0; i < objects.length; i++) {
                if(objects[i].class==Class){
                    objects[i].set({opacity: 0});

                }
                if (objects[i].title == dataId + '_foreground') {
                    objects[i].set({opacity: 1});
                }
                if (objects[i].title == dataId + '_background') {
                    objects[i].set({opacity: 1});
                    objects[i].filters.push(new fabric.Image.filters.Tint({color: color}))
                    objects[i].applyFilters(stage.renderAll.bind(stage))


                }
            }
            stage.renderAll().calcOffset();
        }
        var colorChangeSingle=function (dataId, color) {
            var objects = stage.getObjects();
            for (var i = 0; i < objects.length; i++) {

                if (objects[i].title == dataId ) {

                    objects[i].filters.push(new fabric.Image.filters.Tint({color: color}))
                    objects[i].applyFilters(stage.renderAll.bind(stage))


                }
            }
            stage.renderAll().calcOffset();
        }
        var colorWithBase=function(color){
            var objects = stage.getObjects();
            for (var i = 0; i < objects.length; i++) {
                if(objects[i].imgType=='background'){
                    objects[i].filters.push(new fabric.Image.filters.Tint({color: color}))
                    objects[i].applyFilters(stage.renderAll.bind(stage))
                }
            }
            stage.renderAll().calcOffset();
        }
        var textureChange=function(base,attribute,texture){
            var Class=base+'_'+attribute;
            var ID='texture_'+base+'_'+attribute+'_'+texture;
            var objects = stage.getObjects();
            imageRemove(base + '_' + attribute + '_background');
            imageRemove(base + '_' + attribute + '_foreground');
            for (var i = 0; i < objects.length; i++) {
                if(objects[i].class==Class){
                    objects[i].set({opacity: 0});
                }
                if(objects[i].title==ID){
                    objects[i].set({opacity: 1});
                }
            }
            stage.renderAll().calcOffset();
        }
        var imageChange = function (dataId, imageObj) {
            var objects = stage.getObjects();
            for (var i = 0; i < objects.length; i++) {
                if (objects[i].title == dataId) {
                    objects[i].setElement(imageObj);
                    objects[i].set({opacity: 1, selectable: true});
                    stage.renderAll();
                    break;
                }
            }
        }
        var storeClick=function(attribute,term){
            if (typeof _.findWhere(clickedAttributes, {attribute: selector_attribute}) == "undefined") {
                    clickedAttributes.push({attribute:attribute,term:term});
            } else{
                var newArray = _.without(clickedAttributes, _.findWhere(clickedAttributes, {attribute: attribute}));
                clickedAttributes=newArray;
                clickedAttributes.push({attribute:attribute,term:term});
            }
        }
        var imageRemove = function (id) {
            var objects = stage.getObjects();
            for (var i = 0; i < objects.length; i++) {
                if (objects[i].title == id) {
                    objects[i].set({opacity: 0, selectable: false});
                    break;
                }
            }
            stage.renderAll();
        }
        var storeColor = function (attribute_name, term_name, color_code) {
            if (typeof _.findWhere(storeColorMate, {attribute: selector_attribute}) == "undefined") {
                storeColorMate.push({attribute: attribute_name, term: term_name, color: color_code});

            } else {
                var newArray = _.without(storeColorMate, _.findWhere(storeColorMate, {attribute: attribute_name}));
                storeColorMate = newArray;
                storeColorMate.push({attribute: attribute_name, term: term_name, color: color_code});
            }
        }
        var storeTexture = function (attribute_name, term_id, texture_name) {
            if (typeof _.findWhere(storeTextureMate, {attribute: selector_attribute}) == "undefined") {
                storeTextureMate.push({attribute: attribute_name, term: term_id, texture: texture_name})
            } else {
                var newArray = _.without(storeColorMate, _.findWhere(storeTextureMate, {attribute: attribute_name}));
                storeTextureMate = newArray;
                storeTextureMate.push({attribute: attribute_name, term: term_id, texture: texture_name});
            }
        }
        var checkColor = function (selectBase, selectAttribute, selectTerm) {
            var valueFromPreConfig = typeof color_config[selectBase][selectAttribute][selectTerm]['defaults'] != "undefined" ? color_config[selectBase][selectAttribute][selectTerm]['defaults'] : null;
            var valueFromLocal = typeof _.findWhere(storeColorMate, {
                attribute: selectAttribute,
                term: selectTerm
            }) != "undefined" ? _.findWhere(storeColorMate, {attribute: selectAttribute, term: selectTerm}) : null;
            var returnValue = valueFromLocal != null ? valueFromLocal.color : valueFromPreConfig;
            return returnValue;
        }
        var checkTexture = function (selectBase, selectAttribute, selectTerm) {

            var valueFromPreConfig = texture_config[selectBase][selectAttribute][selectTerm] || null;
            var filtered = null;
            for (var i in valueFromPreConfig) {
                if (typeof  valueFromPreConfig[i].default != "undefined") {
                    // filtered = valueFromPreConfig[i];
                    filtered = i;
                    break;
                }
            }
            var valueFromLocal = typeof _.findWhere(storeTextureMate, {
                attribute: selectAttribute,
                term: selectTerm
            }) != 'undefined' ? _.findWhere(storeTextureMate, {attribute: selectAttribute, term: selectTerm}) : null;
            var returnValue = valueFromLocal != null ? valueFromLocal.texture : filtered;
            return returnValue;

        }
        var zoomImage = function (width) {
            var tempStage = [];
            var tempPositions = [];
            tempStage.push({width: stage.getWidth(), height: stage.getHeight()});
            var tempHeight = _.pluck(tempStage, 'height') * width;
            var tempWidth = _.pluck(tempStage, 'width') * width;
            stage.setDimensions({width: tempWidth, height: tempHeight});
            var objects = stage.getObjects();
            for (var i = 0; i < objects.length; i++) {
                var scaleAmount = (objects[i].get('scaleX') / _.pluck(tempStage, 'width')) * stage.getWidth();
                var scaleAmountY = (objects[i].get('scaleY') / _.pluck(tempStage, 'height')) * stage.getHeight();

                tempPositions.push({
                    id: objects[i].get('id'),
                    scale: objects[i].get('scale'),
                    scaleX: objects[i].get('scaleX'),
                    scaleY: objects[i].get('scaleY'),
                    top: objects[i].get('top'),
                    left: objects[i].get('left')
                });
                objects[i].set({scaleX: scaleAmount, scaleY: scaleAmountY});

                if (objects[i].title == 'extraContent') {
                    var tempTop = objects[i].get('top') * width;
                    var tempLeft = objects[i].get('left') * width;
                    objects[i].set({left: tempLeft, top: tempTop});
                    objects[i].setCoords();
                }
            }
            stage.renderAll().calcOffset();
            var dataUrl = '';
            dataUrl = stage.toDataURL({
                format: 'png',
                quality: 1
            });

            stage.setDimensions({width: _.pluck(tempStage, 'width'), height: _.pluck(tempStage, 'height')});
            var objects1 = stage.getObjects();
            for (var i = 0; i < objects1.length; i++) {
                var tempObject = _.findWhere(tempPositions, {id: objects1[i].get('id')});
                objects1[i].set({
                    scale: tempObject.scale,
                    scaleX: tempObject.scaleX,
                    scaleY: tempObject.scaleY,
                    top: tempObject.top,
                    left: tempObject.left
                });
            }
            stage.renderAll().calcOffset();

            return dataUrl;
        }
        var uploadFile = function () {
            $('#wpc_product_stage').block({
                message: '<img src="'+wpc_loading.loading+'" />',
                overlayCSS: {
                    border: 'none',
                    padding: '0',
                    margin: '0',
                    backgroundColor: 'transparent',
                    opacity: 1,
                    color: '#fff'
                }
            });
             clearAll();
            $("#wpc_image_upload_form").ajaxSubmit({
                dataType: 'json',
                success: function (data, statusText, xhr, wrapper) {
                    new fabric.Image.fromURL(data.filepath, function (oImg) {
                        var imageHeight=logo_size.h;
                        var imageWidth=logo_size.w;
                        var tempWidth=(imageWidth/800) * stage.getWidth();
                        var tempHeight=(imageHeight/800) * stage.getHeight();
                        var actualScaleX=oImg.width > tempWidth ? tempWidth/oImg.width : 1;
                        var actualScaleY=oImg.height > tempHeight ? tempHeight/oImg.height : 1;
                        oImg.set({hasControls: false,hasBorders: false,lockMovementX: true,lockMovementY: true,lockRotation: true,lockScalingX: true,lockScalingY: true,lockUniScaling: true,left:0,top:0,scaleX: actualScaleX, scaleY: actualScaleY, title: 'extraContent', 'id': guid()});
                        stage.add(oImg);
                        stage.calcOffset().renderAll();
                        $('#wpc_product_stage').unblock();
                        storePositions.push({
                           id:oImg.get('id'),
                           width:stage.getWidth(),
                           height:stage.getHeight(),
                           top:oImg.get('top'),
                           left:oImg.get('left'),
                           scaleX:oImg.get('scaleX'),
                           scaleY:oImg.get('scaleY')
                        });
                        selectedEmbroidery='image';
                        clickPositionButton();
                        showControls('image');
                        changeEmbType('image');
                        displayEmbOptions('image');
                        $("#wpc_emb__options_image").text(data.filepath);
                        $("#wpc_emb__options_image").parent().removeClass('wpc_hidden');
                        putEmbValues('wpc_product_emb_image',data.filepath);
                    });
                }
            });

        }
        var processImage=function(imgUrl,position){
            clearAll();
            new fabric.Image.fromURL(imgUrl, function (oImg) {
                var imageHeight=logo_size.h;
                var imageWidth=logo_size.w;
                var tempWidth=(imageWidth/800) * stage.getWidth();
                var tempHeight=(imageHeight/800) * stage.getHeight();
                var actualScaleX=oImg.width > tempWidth ? tempWidth/oImg.width : 1;
                var actualScaleY=oImg.height > tempHeight ? tempHeight/oImg.height : 1;
                oImg.set({hasControls: false,hasBorders: false,lockMovementX: true,lockMovementY: true,lockRotation: true,lockScalingX: true,lockScalingY: true,lockUniScaling: true,left:0,top:0,scaleX: actualScaleX, scaleY: actualScaleY, title: 'extraContent', 'id': guid()});
                stage.add(oImg);
                stage.calcOffset().renderAll();
                storePositions.push({
                    id:oImg.get('id'),
                    width:stage.getWidth(),
                    height:stage.getHeight(),
                    top:oImg.get('top'),
                    left:oImg.get('left'),
                    scaleX:oImg.get('scaleX'),
                    scaleY:oImg.get('scaleY')
                });
                selectedEmbroidery='image';
                showControls('image');
                changeEmbType('image');
                displayEmbOptions('image');
                $("#wpc_emb__options_image").text(imgUrl);
                $("#wpc_emb__options_image").parent().removeClass('wpc_hidden');
                putEmbValues('wpc_product_emb_image',imgUrl);
                $('.wpc_emb_btn').each(function(){
                    if($(this).text()==position){
                        $(this).trigger('click');
                        return false;
                    }
                });
            })

        }
        var clickPositionButton=function(type){
            if(selectedEmbroidery=='text'){
               // alert(font_size);
                $("#wpc_size_select").val(font_size).change();
            }
            $("#wpc_emb_postion_buttons").find('.active').trigger('click');
        }
        var displayOptions=function(prefix,attribute_name,value){

            var td=$("#"+prefix+'_'+attribute_name);
            if(value!='') {
                td.parent().removeClass('wpc_hidden');
            }else{
                td.parent().addClass('wpc_hidden');
            }
            td.text(value);

        }
        var clearAll=function(){
            var objects = stage.getObjects();
            for (var i = 0; i <= objects.length; i++) {
                if (typeof objects[i] != 'undefined' && objects[i].title == 'extraContent') {

                    stage.remove(objects[i]);
                    i--;
                }
            }
        }
        var changeEmbType=function(type){
            var displayText=$(".wpc_emb_tabs.active").text();
            if(selectedEmbroidery==null){
                $("#wpc_emb__options_type").text(displayText);
                $("#wpc_emb__options_type").parent().removeClass('wpc_hidden');
                putEmbValues('wpc_product_emb_type',displayText);
            }else if(selectedEmbroidery==type){
                $("#wpc_emb__options_type").text(displayText);
                $("#wpc_emb__options_type").parent().removeClass('wpc_hidden');
                putEmbValues('wpc_product_emb_type',displayText);
            }else{
                return false;
            }
        }
        var displayEmbOptions=function(type){
            if(typeof type=='undefined'){
                $(".wpc_emb_options").parent().addClass('wpc_hidden');
                $(".wpc_emb_options").text('');
            }
            if(type=='image'){
                $(".emb_options_for_text").parent().addClass('wpc_hidden');
                $(".emb_options_for_text").text('');
            }else if(type=='text'){
                $(".emb_options_for_image").parent().addClass('wpc_hidden');
                $(".emb_options_for_image").text('');
            }
        }
        var guid = function () {
            function s4() {
                return Math.floor((1 + Math.random()) * 0x10000)
                    .toString(16)
                    .substring(1);
            }

            return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                s4() + '-' + s4() + s4() + s4();
        }

    }
);