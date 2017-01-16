/**
 * Created by marius.iliescu on 16-Jan-17.
 */
$(document).ready(function () {

    $('#profile_update_form').on("submit",function(event) {
        event.preventDefault();

        var form = $("#profile_update_form");


        var formData = new FormData();
        formData.append('profile_picture', $('input[type=file]')[0].files[0]);
        formData.append('cover_picture', $('input[type=file]')[1].files[0]);
        formData.append('other_data', form.serialize());

        // Apply animation once per click
        $(this).parents(".panel").addClass("animated flipOutX").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
            $(this).removeClass("animated flipOutX");
        });


        $.ajax({
            url: Routing.generate('profile_update'),
            type: 'POST',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){ // Check if upload property exists
                    myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
                }
                return myXhr;
            },
            //Ajax events
            success: function(result) {
                var data = JSON.parse(result);
                if(!data.error) {
                    var response = data.response;
                    redrawUserInformation(response);
                }else{
                    alert(result.message);
                }
            },
            error: function(result) {
                console.log("not ok")
            },
            // Form data
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });
    });

    function redrawUserInformation(data){
        var fullLoggedUserName = $('.full_logged_user_name');
        var req =  data;

        redrawProfilePicture(req.profile_picture);
        redrawCoverPicture(req.cover_image);

        fullLoggedUserName.each(function(i, obj) {
            $(obj).val(req.full_name);
        });
    }

    function progressHandlingFunction(e){
        if(e.lengthComputable){
            $('progress').attr({value:e.loaded,max:e.total});
        }
    }
});

