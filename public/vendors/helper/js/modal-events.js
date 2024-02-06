// Code to delete record
$(document).on("click", "[class^='openmodal-']", function(e) {
    // Get the class attribute
    var classes = $(this).attr('class');
    var model = classes.replace('openmodal-', '');  

    var thiselement = this;
    var url = $(thiselement).attr('deleteto');
    var modal = $(thiselement).attr('modal');

    $.ajax({
        url: Base_url + 'getRecord/' + url_routes + '/' + id,
        method: "post",
        success: function(data)
        {
            if (json.Status == 200)
            {
                var modal = $('#' + modal);

                if(modal.hasClass('modal'))
                    $('#' + modal).modal('show');
                
                setModalVlues(json.Data ,modal);
            }
        }

    });

});
//--------------------------------------------------------------------------------------------------
//function open the modal at edit time and set data in form
function openmodal(id, name,integration = '')
{
    $('#' + name + '-modal').trigger("reset");
    $('#' + name + '-modal .span-error').html("");
    $('#' + name + '-modal .invalid-feedback').html("");
    $('#' + name + '-modal img').attr('src',SelectImageUrl);
    $("#" + name + "-modal [id$='_url_info']").html('Click For Select Image');
    $("#" + name + "-modal .invalid-feedback").addClass('d-none');
    $('body').prepend('<span  id="loader-spinner" role="status" aria-hidden="true" style="position: absolute;margin: auto;top: 50%;left: 50%;z-index: 8;background: #3051d3;color: whitesmoke;padding: 12px;border-radius: 10px;border: 2px #9a9aff solid;box-shadow: 0px 3px 10px 0px grey;"><span class="spinner-border spinner-border-sm mx-1"></span>Please wait...</span> ');
    // alert(baseUrl + 'modaldata/' + name + '/' + id);

    var url_routes = integration=='' ? name : integration;

    $.ajax({
        url: Base_url + 'getRecord/' + url_routes + '/' + id,
        method: "post",
        success: function(data)
        {
            var json = JSON.parse(data);        
            if (json.Status == 200)
            {
                var modal = $('#' + name + '-modal');

                if(modal.hasClass('modal'))
                    $('#' + name + '-modal').modal('show');
                
                setModalVlues(json.Data ,modal);
                
                
                $(".basiccolorpicker").colorpicker('destroy');
                $(".basiccolorpicker").colorpicker();
            }
             $('#loader-spinner').remove();
        }
    });
}
//--------------------------------------------------------------------------------------------------
function setModalVlues(modalData ,modal)
{
    for (var key in modalData)
    {
        // var tagname = $('#' + key).prop("tagName");
        tagname = ($("[id='" + key + "']",modal).prop("tagName") != undefined) ? $("[id='" + key + "']",modal).prop("tagName") : $("[name='" + key + "']",modal).prop("tagName");
        if (tagname == 'INPUT')
        {
            type = $("[name='" + key + "']",modal).attr("type");
            if(type == 'radio')
            {
               $('input[name="' + key + '"][value="' + modalData[key] + '"]',modal).click();
            }
            else if(type == 'file')
            {
                if ($('#' + key,modal).hasClass('custom_fields_dropify')) 
                {
                    var file_key = key;
                    var file_path = modalData[key];                        
                }
            }
            else if(type == 'checkbox')
            {
                if (Array.isArray(modalData[key]))
                {
                    for (let i of modalData[key]) 
                    {
                         if (jQuery.inArray(i, modalData[key]) != -1)
                            $('input[name="' + key + '"][value="' + i + '"]',modal).prop('checked',true);
                        else
                            $('input[name="' + key + '"][value="' + i + '"]',modal).prop('checked',false);
                            
                        $('input[name="' + key + '"][value="' + i + '"]',modal).change();

                        if (jQuery.inArray(this.value, modalData[key]) != -1)
                            $('input[name="' + key + '[]"][value="' + i + '"]',modal).prop('checked',true);
                        else
                            $('input[name="' + key + '[]"][value="' + i + '"]',modal).prop('checked',false);
                            
                        $('input[name="' + key + '[]"][value="' + i + '"]',modal).change();

                    }                   
                }
                else
                {                                
                    $('input[name="' + key + '"][value="' + modalData[key] + '"]',modal).prop('checked',true);
                    $('input[name="' + key + '"][value="' + modalData[key] + '"]',modal).change();
                }
                // $('input[name="' + key + '"][value="' + modalData[key] + '"]').prop('checked',true);
            }
            else
                $('#' + key,modal).val(modalData[key]);
        }
        else if(tagname == 'IMG')
        {
            $('#' + key,modal).attr('src',modalData[key]);
        }
        else if(tagname == 'TEXTAREA')
        {
            if($('#' + key,modal).hasClass('summernote')) //when textarea with summernote
                $('#' + key,modal).summernote("code", modalData[key]);
            else
                $('#' + key,modal).val(modalData[key]);
        }
        else if(tagname == 'SELECT')
        {

            if(modalData[key]=='' || modalData[key]==0)
            {
                $("#"+key+" :nth-child(0)",modal).prop('selected', true);
                $("#"+key,modal).attr('selected-value','');
            }
            else
            {
                $("#"+key,modal).val(modalData[key]);
                $("#"+key,modal).attr('selected-value',modalData[key]);
            }

            //  $("#"+key).val(modalData[key]);

            $("#"+key,modal).trigger('change');

            
        }
        else if (tagname == 'I')
            $('#' + key,modal).css('background',modalData[key]);
        else if(tagname == 'IFRAME')
        {
            $('#' + key,modal).attr('src',modalData[key]);
        }
        else if(tagname == 'A')
        {
            $('#' + key,modal).attr('href',modalData[key]);
            $("#" + key,modal).removeClass ('d-none');    
        }
    }
}
//--------------------------------------------------------------------------------------------------
