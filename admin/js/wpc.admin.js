jQuery(function($){

    if (typeof wpc_config_page != "undefined") {
        var sheepItForm = $('#wpc_sheepItForm').sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: false,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 100,
            minFormsCount: 0,
            iniFormsCount: 1,
            data: inject_data
        });
        var sheepItForm1 = $('#wpc_sheepItForm1').sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: false,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 100,
            minFormsCount: 0,
            iniFormsCount: 1,
            data: inject_data_texture
        });
        var sheepItForm2 = $('#wpc_sheepItForm2').sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: false,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 100,
            minFormsCount: 0,
            iniFormsCount: 1,
            data: inject_data_size
        });
        var mediaUploader = null;
        $('body').on('click','.wpc_texture_upload',function(e){
            e.preventDefault();
            var self=$(this);
            mediaUploader = wp.media({
                multiple: false
            });
            mediaUploader.on('select', function () {
               $(self).prev().val(mediaUploader.state().get('selection').toJSON()[0].url);
            });
            mediaUploader.open();

        })
        $("#wpc_google_fonts").chosen({disable_search_threshold: 10});
    }

    if (typeof wpc_attribute_page != "undefined") {
        var mediaUploader = null;
        $("#btn_wpc_attribute_image_upload").click(function (e) {
            mediaUploader = wp.media({
                multiple: false
            });
            mediaUploader.on('select', function () {
                $("#hd_wpc_attribute_image").val(mediaUploader.state().get('selection').toJSON()[0].url);
                mediaUploader = null;
            });
            mediaUploader.open();
            e.preventDefault();
        });
        $('#hd_wpc_attribute_type').on('change', function () {
            var selected = $(this).find('option:selected');
            var data = selected.attr('data-value');
            if (data == 'wpc_none') {
                $('.wpc-hidden').hide();
            } else {
                $('.wpc-hidden').hide();
                $('.' + data).show();
            }
        })
    }

    if (typeof wpc_product_page != "undefined") {
        if($("#_wpc_check").is(':checked')){
            $('.wpc_default_config_options').removeClass('show_if_wpc_panel');
            $('.wpc_instructions_options').removeClass('show_if_wpc_panel');
        }else{
            $('.wpc_default_config_options').addClass('show_if_wpc_panel');
            $('.wpc_instructions_options').addClass('show_if_wpc_panel');
        }
        $('body').on('change','#_wpc_check',function(){

            if($(this).is(":checked")){

                $('.wpc_default_config_options').removeClass('show_if_wpc_panel');
                $('.wpc_instructions_options').removeClass('show_if_wpc_panel');
            }else{
                if($("#wpc_data_default_configuration").is(':visible')){
                    $("#wpc_data_default_configuration").hide();
                }
                if($("#wpc_instructions_tab").is(':visible')){
                    $("#wpc_instructions_tab").hide();
                }
                $('.wpc_default_config_options').addClass('show_if_wpc_panel');
                $('.wpc_instructions_options').addClass('show_if_wpc_panel');
            }
        });
        $('body').on('click', '#wpc_refresh_button', function (e) {
            e.preventDefault();
            var this_page = window.location.toString();
            this_page = this_page.replace('post-new.php?', 'post.php?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&');
            $('#wpc_data_default_configuration').block({message: null,
                overlayCSS: {
                    background: '#fff url(' + woocommerce_admin_meta_boxes.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center',
                    opacity: 0.6
                }
            });
            $('#wpc_data_default_configuration').load(this_page + ' #wpc_data_default_configuration', function () {
                $('#wpc_data_default_configuration').unblock();
            })
        });
        $('body').on('click', '#wpc_refresh_instructions_button', function (e) {
            e.preventDefault();
            var this_page = window.location.toString();
            this_page = this_page.replace('post-new.php?', 'post.php?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&');
            $('#wpc_instructions_tab').block({message: null,
                overlayCSS: {
                    background: '#fff url(' + woocommerce_admin_meta_boxes.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center',
                    opacity: 0.6
                }
            });
            $('#wpc_instructions_tab').load(this_page + ' #wpc_instructions_tab', function () {
                $('#wpc_instructions_tab').unblock();
            })
        });
    }
    $('body').on('click', '.background-upload', function (e) {
        e.preventDefault();
        // alert('sd');
        var self = this;
        mediaUploader = wp.media({
            multiple: false
        });
        mediaUploader.on('select', function () {

            $($(self).prev()).val(mediaUploader.state().get('selection').toJSON()[0].url);
            mediaUploader = null;
        });
        mediaUploader.open();
    })
    $('body').on('click', '.shadow-upload', function (e) {
        e.preventDefault();
        var self = this;
        mediaUploader = wp.media({
            multiple: false
        });
        mediaUploader.on('select', function () {

            $($(self).prev()).val(mediaUploader.state().get('selection').toJSON()[0].url);
            mediaUploader = null;
        });
        mediaUploader.open();
    })
    $('#step_image_form').on('submit', function (e) {
        e.preventDefault();
        // alert($(this).serialize());
        var data = {
            'action': 'wpc_save_configuration_form',
            'formData': $(this).serialize(),
            'postId': $(this).data('id')
        };
        $.post(ajaxurl, data, function (resp) {
            //console.log(resp);
        })

    })
    $('#step_texture_form').on('submit', function (e) {
        e.preventDefault();
        // alert($(this).serialize());
        var data = {
            'action': 'wpc_save_configuration_form_texture',
            'formData': $(this).serialize(),
            'postId': $(this).data('id')
        };
        $.post(ajaxurl, data, function (resp) {
            //console.log(resp);
        })

    })
    $('#step_color_form').on('submit', function (e) {
        e.preventDefault();
        var data = {
            'action': 'wpc_save_configuration_form_color',
            'formDataColor': $(this).serialize(),
            'postId': $(this).data('id')
        };
        $.post(ajaxurl, data, function (resp) {
            //console.log(resp);
        })

    })
    var addToCanvasSaddle = function (url,scale,scaleX,scaleY,xX,yY) {
        var image = new Image();
        image.src = url;
        setFieldValue('#wpc_hidden_saddle_design',url)
        $(image).on('load', function () {
            new fabric.Image.fromURL(url, function (oImg) {
                oImg.set({
                    top: yY,
                    left: xX,
                    scale:scale,
                    scaleX: scaleX,
                    scaleY: scaleY,
                    lockRotation: true
                });
                design.add(oImg).renderAll();
                console.log(oImg);
                $("#saddle_design_upload_btn").attr('disabled','disabled');
            })
        })
    }
    var setFieldValue = function (field,value){
        $(field).val(value);
    }
    var addToCanvas = function (url) {
        var scaleX = 1;
        var scaleY = 1;
        var image = new Image();
        image.src = url;
        setFieldValue('#wpc_hidden_base_design',url)
        $(image).on('load', function () {
            var imageWidth = image.width;
            var imageHeight = image.height;
            var canvasWidth = design.getWidth();
            var canvasHeight = design.getHeight();
            scaleX = canvasWidth < imageWidth ? (canvasWidth / imageWidth).toFixed(2) : 1;
            scaleY = canvasHeight < imageHeight ? (canvasHeight / imageHeight).toFixed(2) : 1;
            new fabric.Image.fromURL(url, function (oImg) {
                oImg.set({
                    top: 0,
                    left: 0,
                    scaleX: scaleX,
                    scaleY: scaleY,
                    lockMovementX: true,
                    lockMovementY: true,
                    lockRotation: true,
                    lockScalingX: true,
                    lockScalingY: true,
                    lockUniScaling: true
                });
                design.add(oImg).renderAll();
                $("#base_design_upload_btn").attr('disabled','disabled');
                $("#saddle_design_upload_btn").removeAttr('disabled');
            })
        })
    }
    if (typeof wpc_base_design_page != 'undefined') {
        var canvas = $('#wpc_base_design_stage').children('canvas').get(0);
        var design = new fabric.Canvas(canvas, {
            selection: false,
            hoverCursor: 'pointer',
            rotationCursor: 'default',
            centeredScaling: true
        });
        var designWidth = $('#wpc_base_design_stage').width();
        design.setHeight(400);
        design.setWidth(designWidth);
        var mediaUploader = null;
        $("#base_design_upload_btn").click(function (e) {
            mediaUploader = wp.media({
                multiple: false
            });
            mediaUploader.on('select', function () {
                addToCanvas(mediaUploader.state().get('selection').toJSON()[0].url);
                mediaUploader = null;
            });
            mediaUploader.open();
            e.preventDefault();
        });
        $("#saddle_design_upload_btn").click(function (e) {
            mediaUploader = wp.media({
                multiple: false
            });
            mediaUploader.on('select', function () {
                addToCanvasSaddle(mediaUploader.state().get('selection').toJSON()[0].url,1,1,1,0,0);
                $('#saddle_scale').val(1);
                $('#saddle_scaleX').val(1);
                $('#saddle_scaleY').val(1);
                mediaUploader = null;
            });
            mediaUploader.open();
            e.preventDefault();
        });
        $('#design_clear').on('click',function(e){
            e.preventDefault();
            design.clear();
            $('.wpc_hidden').val('');
            $("#saddle_design_upload_btn").attr('disabled','disabled');
            $("#base_design_upload_btn").removeAttr('disabled');
        })
        design.on({
            'object:scaling': function(opts) {
                var scale = Number(opts.target.scale).toFixed(2);
                var scaleX = Number(opts.target.scaleX).toFixed(2);
                var scaleY = Number(opts.target.scaleY).toFixed(2);
                setFieldValue('#saddle_scale',scale);
                setFieldValue('#saddle_scaleX',scaleX);
                setFieldValue('#saddle_scaleY',scaleY);
            },
            'object:moving': function(opts) {
                var X = Math.round(opts.target.left);
                var Y = Math.round(opts.target.top);
                setFieldValue('#saddle_pos_x',X);
                setFieldValue('#saddle_pos_y',Y);
            }
        });
        if(edit_mode==1){
            var base = $('#wpc_hidden_base_design').val();
            var saddle = $('#wpc_hidden_saddle_design').val();
            var edit_scale = parseFloat($('#saddle_scale').val());
            var edit_scaleX = parseFloat($('#saddle_scaleX').val());
            var edit_scaleY = parseFloat($('#saddle_scaleY').val());
            var x = parseInt($('#saddle_pos_x').val());
            var y = parseInt($('#saddle_pos_y').val());
            addToCanvas(base);
            setTimeout(function(){
                addToCanvasSaddle(saddle,edit_scale,edit_scaleX,edit_scaleY,x,y);
            },500);
        }
    }
    if(typeof embroidery_config !='undefined'){

                     var sheepItForm = $('#sheepItForm').sheepIt({
                         separator: '<div style="width:100%; border-top:1px solid #ccc; margin: 10px 0px;"></div>',
                         data:position_data
                     });

        $('#step_embroidery_form').on('submit', function (e) {
            e.preventDefault();
            // alert($(this).serialize());
            var data = {
                'action': 'wpc_save_configuration_form_embroidery',
                'formData': $(this).serialize(),
                'postId': $(this).data('id')
            };
            $.post(ajaxurl, data, function (resp) {
                console.log(resp);
            })

        })

    }

$('body').on('click','.wpc_selectAllButton',function(e){
    e.preventDefault();
    var checked = $(this).parent().parent().parent().find('.color_checkbox:checked').length > 0;
    if(!checked) {
        $(this).parent().parent().parent().find('.color_checkbox').prop('checked', 'checked');
    }else{
        $(this).parent().parent().parent().find('.color_checkbox').removeAttr('checked');
    }
});

//Image Page Script
    var saveTabData=function(tabId){
        var action="wpc_save_tab_data",
            sectionId=null;
        var sections=['wpc_base_edge','wpc_cord_layers','wpc_multicolor_cords','wpc_cord_images','wpc_multicolor_images'];
        //switch (tabId){
        //    case 1:
        //        action= "wpc_save_configuration_form_cord_layers";
        //        formId="#wpc_cord_layers_form";
        //        break;
        //}
        var formId=$("#"+sections[tabId]+"_form");
        var data = {
            'action': action,
            'formData': $(formId).serialize(),
            'section':sections[tabId],
            'postId': postId
        };
     return  $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data,
            success:function(){
            },
            async:false
        });
    }


    $("#wpc_all_images").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slide",
        titleTemplate: "#title#",
        labels:wpc_image_labels,
        stepsOrientation: "vertical",
        onStepChanging:function(event, currentIndex, newIndex){
            if(newIndex > currentIndex){
                saveTabData(currentIndex);
            }
            return true;
        },
        onStepChanged:function(event, currentIndex, priorIndex){
            loadTab(currentIndex);
        }
    });
    var loadTab=function(tabId){
        var action="wpc_load_tab_data",
            sectionId=null;
        var sections=['wpc_base_edge','wpc_cord_layers','wpc_multicolor_cords','wpc_cord_images','wpc_multicolor_images'];

        sectionId=sections[tabId];
        if(sectionId == 'wpc_base_edge'  || sectionId=='wpc_cord_layers') return;
        $("#"+sectionId).block({message:null,
            overlayCSS: {
                background: '#eee',
                opacity: 0.6
            }
        });
        var data = {
            'action': action,
            'section': sectionId,
            'postId': postId
        };
          $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data,
            dataType:'html',
            success:function(resp){
                $("#"+sectionId).html(resp);
                $("#wpc_multicolor_cords_select").multiselect({});
                resizeJquerySteps();
              //  var formattedHtml=$($.parseHTML(resp));
               // console.log($(resp).filter("#wpc_cord_images_form"));
                if($(resp).filter("#wpc_cord_images_form").length>0){
                activateSheepIt('.wpc_combinations');
                }
                else if($(resp).filter("#wpc_multicolor_cords_form").length>0){
                    textureSheepIt('.wpc_combinations_texture');
                }
                $("#"+sectionId).unblock();
            }
        });
    }
    var activateSheepIt=function(className){
            $(className).each(function (k, v) {
                var injectData = $("#wpc_values_" + $(this).data("layer")).val();
                injectData = $.parseJSON(injectData);
                var sheepItForm = $('#' + $(v).attr("id")).sheepIt({
                    separator: '<div style="width:100%; border-top:1px solid #ccc; margin: 10px 0px;"></div>',
                    minFormsCount: 0,
                    iniFormsCount: 0,
                    afterAdd: function (source, newForm) {
                        resizeJquerySteps();
                    },
                    afterFill: function () {
                        // resizeJquerySteps();
                    },
                    data: injectData
                });
            });
            resizeJquerySteps();
    }
    var textureSheepIt=function(className){
        $(className).each(function (k, v) {
            //var injectData = $("#wpc_values_" + $(this).data("layer")).val();
          //  injectData = $.parseJSON(injectData);
            var sheepItForm = $('#' + $(v).attr("id")).sheepIt({
                separator: '<div style="width:100%; border-top:1px solid #ccc; margin: 10px 0px;"></div>',
                minFormsCount: 0,
                iniFormsCount: 0,
                afterAdd: function (source, newForm) {
                    resizeJquerySteps();
                },
                afterFill: function () {
                    // resizeJquerySteps();
                },
              //  data: injectData
            });
        });
        resizeJquerySteps();
    }

    $(document).on('click','.wpc_image_upload',function(e){
        e.preventDefault();
        var self=$(this);
        var inputField=$("#"+self.data('field'));
        mediaUploader = wp.media({
            multiple: false
        });
        mediaUploader.on('select', function () {
            inputField.val(mediaUploader.state().get('selection').toJSON()[0].url);
        });
        mediaUploader.open();

    });
    $(document).on('click','.wpc_image_upload_sheepit',function(e){
        e.preventDefault();
        var self=$(this);
        var inputField=self.prev();
        mediaUploader = wp.media({
            multiple: false
        });
        mediaUploader.on('select', function () {
            inputField.val(mediaUploader.state().get('selection').toJSON()[0].url);
        });
        mediaUploader.open();

    });
    function resizeJquerySteps() {
        $('.wizard .content').animate({ height: $('.body.current').outerHeight() }, "slow");
    }
});
