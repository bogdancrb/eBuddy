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
        var logged_user_picture= $('.logged_user_picture');
        var profileCoverImg = $('.profile-cover-img');
        var req =  data;

        fullLoggedUserName.each(function(i, obj) {
            $(obj).val(req.full_name);
        });

        logged_user_picture.each(function(i, obj) {
            console.log(obj);
            $(obj).prop('src', '/web/'+req.profile_picture);
        });

        profileCoverImg.each(function(i, obj) {
            console.log(obj);
            $(obj).css('background-image', 'url("/web/'+req.cover_image+'")');
        });
    }

    function progressHandlingFunction(e){
        if(e.lengthComputable){
            $('progress').attr({value:e.loaded,max:e.total});
        }
    }

    $('#newPostModal').on('shown.bs.modal', function () {
        $('#newPostContent').wysihtml5();
    });

    var timeLine = $("#postsTimeline");


    var offset = 0;
    var limit = 3;
    var timelineScrollEnded = true;

    timeLine.html(getLoaderHtml());
    loadPosts();
    getRecomandedFriends();

    $(".btn-pref .btn").click(function () {
        $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
        // $(".tab").addClass("active"); // instead of this do the below
        $(this).removeClass("btn-default").addClass("btn-primary");
    });

    $('#newPostButton').click(function() {
        var postContent = $("#newPostContent").val();
        $("#postsTimeline").html(getLoaderHtml());
        addNewPostAction(postContent);
        offset=0;
        loadPosts();
    });

    $(window).scroll(function() {
        // End of the document reached?
        if (timelineScrollEnded && $(window).scrollTop() >= $(document).height() - $(window).height() - 300) {
            loadPosts();
        }
    });

    function loadPosts(){
        timelineScrollEnded = false;

        $('.loading3').show();

        var payload = {
            "limit":limit,
            "offset":offset
        };

        $.ajax({
            method: 'POST',
            data: JSON.stringify(payload),
            url: apiGETpostsUrl,

            success: function(result) {
                if (!result.error) {
                    result.response.forEach(function (value, index) {
                        offset++;
                        var postHtml = getHtmlPost(value);
                        $('#postsTimeline .timeline-row:last').before(postHtml);
                        appendLastCommentInTimeLine(value.id);

                    });
                    $('.loading3').hide();
                    timelineScrollEnded = true;
                }
            }
        });
    }

    function getRecomandedFriends(){
        $.ajax({
            method: 'GET',
            url: Routing.generate('get_recomanded_friends'),

            success: function(result) {

                var data = JSON.parse(result.content);
                if (!data.error) {
                    data.response.forEach(function (value, index) {
                        var userHtml = getRecomandedFriendHtml(value);
                        $('#recomanderBlock').append(userHtml);
                    });
                    $('.loading3').hide();
                    timelineScrollEnded = true;
                }
            }
        });
    }
});

function addNewPostAction(postContent){
    var payload = {
        "post_content":postContent
    };

    $.ajax({
        method: 'POST',
        url: apiAddNewPostUrl,
        data: JSON.stringify(payload),
        error: function() {
//                    $('#errors').html('<p>An error has occurred</p>');
        },
        success: function(data) {
            console.log(data);
        }

    });
}

function appendPostContentInModal(postId){

    $.ajax({
        method: 'GET',
        url: "{{url('get-post_by_id',{'postId': 5})}}",

        error: function() {
            // $('#errors').html('<p>An error has occurred</p>');
        },
        success: function(data) {
            if(data.error != false){
                $("#postModal").find(".postContentInModal").html(getHtmlModalPost(data.response));
            }
        }

    });
}

function appendCommentsInModal(postId, limit, offset){
    $.ajax({
        method: 'GET',
        url: "{{ url('get_post_comments_with_limit_and_offset' { 'postId': 5, 'limit': 2, 'offset': 0 })}}",
        error: function() {
            // $('#errors').html('<p>An error has occurred</p>');
        },
        success: function(data) {
            if(data.error != false){
                JSON.parse(result.response).forEach(function(value, index) {
                    $("#postModal").find(".postContentInModal").append(getHtmlModalComment(value));
                    offset++;
                });
            }
        }

});
}

function appendLastCommentInTimeLine(postId){
    $.ajax({
        method: 'GET',
        url: Routing.generate('get_last_comment_from_a_post', { postId: postId }),
        error: function() {
            // $('#errors').html('<p>An error has occurred</p>');
        },
        success: function(data) {
            var response = JSON.parse(data.content);

            if(!data.error){
                var latestPostId = '#post_'+postId;
                var responseContent = response.response;
                $(latestPostId).find(".lastComment").html(getLastCommentHtml(responseContent));
            }else{
                console.log(data.message);
            }
        }
    });
}

function getHtmlPost(postData){
    var myvar = '<div id= "post_'+postData.id+'" class="timeline-row">'+
        '   <div class="timeline-icon">'+
        '         <img alt="img" src = "/web/'+postData.author_picure_path+'">'+
        '     </div>'+
        ''+
        '     <div class="panel panel-flat timeline-content">'+
        '         <div class="panel-heading">'+
        '             <h6 class="panel-title"></h6>'+
        '             <div class="heading-elements">'+
        '                 <span class="heading-text" data-livestamp='+postData.posted_at.date+'><i'+
        '                             class="icon-checkmark-circle position-left text-success"></i></span>'+
        '                 <ul class="icons-list">'+
        '                     <li class="dropdown">'+
        '                         <a href="#" class="dropdown-toggle"'+
        '                            data-toggle="dropdown">'+
        '                             <i class="icon-arrow-down12"></i>'+
        '                         </a>'+
        ''+
        '                         <ul class="dropdown-menu dropdown-menu-right">'+
        '                             <li><a href="#"><i class="icon-user-lock"></i> Hide'+
        '                                     user posts</a></li>'+
        '                             <li><a href="#"><i class="icon-user-block"></i>'+
        '                                     Block user</a></li>'+
        '                             <li><a href="#"><i class="icon-user-minus"></i>'+
        '                                     Unfollow user</a></li>'+
        '                             <li class="divider"></li>'+
        '                             <li><a href="#"><i class="icon-embed"></i> Embed'+
        '                                     post</a></li>'+
        '                             <li><a href="#"><i class="icon-blocked"></i> Report'+
        '                                     this post</a></li>'+
        '                         </ul>'+
        '                     </li>'+
        '                 </ul>'+
        '             </div>'+
        '         </div>'+
        ''+
        '         <div class="panel-body">'+
        ''+
        '             '+postData.content+
        ''+
        '             <div class="lastComment">'+
        '             </div>'+
        '         </div>'+
        ''+
        '         <div class="panel-footer">'+
        '           <div class="row">'+
        '               <div class="post-actions pull-left">'+
        '                   <a href="#"><img src="/web/eBuddy-assets/images/icons/profile/share.png" class="custom-responsive-action-img"></a>'+
        '                   <a href="#"><img src="/web/eBuddy-assets/images/icons/profile/like.png" class="custom-responsive-action-img"></a>'+
        '                   <a href="#"><img src="/web/eBuddy-assets/images/icons/profile/heart.png" class="custom-responsive-action-img"></a>'+
        '                   <a href="#"><img src="/web/eBuddy-assets/images/icons/profile/heart_broken.png" class="custom-responsive-action-img"></a>'+
        '               </div>'+
        ''+
        '               <div class="pull-right row">'+
        '                 <button class = "btn col-lg-3" onclick="showUpCommentBox(event);" ><span class="icon-comment-discussion position-left"></span></button>'+
        '                 <button class = "btn col-lg-offset-1 col-lg-8" onclick="popupPostModal(event);" data-toggle="modal" data-target="#postModal">See More<span class="icon-arrow-right14 position-right"></span></button>'+
        '               </div>'+
        '           </div>'+
        '         </div>'+
        '         <div class="panel-footer comment_box hide">'+
        '               <input type="text" class="form-control" placeholder="leave a comment">'+
        '         </div>'+

        '     </div>'+
        '   </div>';

    return myvar;
}

function getHtmlModalPost(postData){
    return '         <div class="panel-body">' +
           '             ' + postData.content +
           '         </div>';
}

function getHtmlModalComment(comment){
    return '<div class="media">'+
            '<p class="pull-right"><small data-livestamp='+comment.posted_at.date+'></small></p>'+
            '<a class="media-left" href="#">'+
            '   <img src="/web/'+comment.author_picure_path+'" class="img-circle">'+
            '</a>'+
            '<div class="media-body">'+
                '<h4 class="media-heading user_name">'+comment.author_name+'</h4>'+
                    comment.content+
            '</div>'+
        '</div>';
}

function getLastCommentHtml(comment) {
    if(comment != null){
        return '<br/><h6 class="content-group">'+
            '    <i class="icon-comment-discussion position-left"></i>'+
            '        Las comment from <a href="#">'+comment.author_name+'</a>:'+
            '</h6>'+
            ''+
            '<blockquote>'+
            '    <p>'+comment.content+'</p>'+
            '    <footer><cite title="Source Title" data-livestamp='+comment.posted_at.date+'></cite>'+
            '    </footer>'+
            '</blockquote>';
    }else{
        return '<br/><h6 class="content-group">'+
            '    <i class="icon-comment-discussion position-left"></i>'+
            '        No comments yet'+
            '</h6>';

    }

}

function popupPostModal(e) {
    e = e || window.event;
    var target = e.target || e.srcElement;

    var limit = 5;
    var offset = 0;

    var postId = $(target).closest(".timeline-row").attr('id');
    postId = postId.substr(postId.indexOf("_") + 1);

    var modalContent = $("#postModal").find(".postContentInModal");


    modalContent.block({
        message: '<i class="icon-spinner2 spinner"></i>',
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.9,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'none'
        }
    });


/*    function loadComments() {
        $.ajax({
            method: 'GET',
            url: Routing.generate('get_post_comments_with_limit_and_offset', {
                postId: postId,
                limit: limit,
                offset: offset
            }),
            error: function () {
                //                    $('#errors').html('<p>An error has occurred</p>');
            },
            success: function (data) {
                var content = JSON.parse(data.content);
                if (!content.error) {
                    var response = JSON.parse(content.response);
                    if(response.length == 0 ){
                        $("#postModal").find(".postCommentsInModal").html(
                            '<h6 class="content-group">    <i class="icon-comment-discussion position-left"></i>        No comments yet</h6>'
                        );
                    }
                    response.forEach(function (value, index) {
                        $("#postModal").find(".postCommentsInModal").append(getHtmlModalComment(value));
                        offset++;
                    });
                }
            }

        });
    }*/

    function loadPost() {
        $.ajax({
            method: 'GET',
            url: Routing.generate('get_post_by_id', {postId: postId}),
            error: function () {
                //                    $('#errors').html('<p>An error has occurred</p>');
            },
            success: function (data) {

                if (!data.error) {
                    var response = JSON.parse(data.response);
                    modalContent.unblock();
                    modalContent.html(getHtmlModalPost(response));
                }
            }

        });
    }

    loadPost();
    $("#postModal").find(".postCommentsInModal").html("");
    loadComments();
}

function showUpCommentBox(e){
    e = e || window.event;

    var target = e.target || e.srcElement;

    var postId = $(target).closest(".timeline-row").attr('id');
    postId = postId.substr(postId.indexOf("_") + 1);

    var commentBox = $(target).closest(".panel-footer").siblings(".comment_box");

    var inputCommentBox = commentBox.find("input");


    inputCommentBox.keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            var commentContent = $(this).val();

            postComment(postId ,commentContent);
            appendLastCommentInTimeLine(postId);
            setTimeout(function() { appendLastCommentInTimeLine(postId); }, 1000);
            inputCommentBox.val('');
            return false;
        }
    });
    commentBox.removeClass("hide", function() {
        commentBox.fadeIn("slow");
    });

    //desactivate button
    $(target).attr("disabled", true);
}

function postComment(postId, commentContent){
    var payload = {
        "comment_content":commentContent
    };
    $.ajax({
        method: 'POST',
        url:  Routing.generate('add_new_comment', { postId: postId }),
        data: JSON.stringify(payload),
        error: function() {
//                    $('#errors').html('<p>An error has occurred</p>');
        },
        success: function(data) {
            console.log(data);
        }

    });
}

function getLoaderHtml() {
    return  '<div class="timeline-row loading3 col-centered" style="display: none">' +
            '   <div></div><div></div><div></div>' +
            '   <div></div><div></div>' +
            '</div>';
}


function getRecomandedFriendHtml(friend){

    return  '<li class="media">'+
            '<div class="media-link">'+
            '<div class="media-left"><img src="/web/'+friend.picture+'"'+
            'class="img-circle" alt="no picture"></div>'+
            '<div class="media-body">'+
            '<span class="media-heading text-semibold">'+friend.name+'</span>'+
            '<span class="media-annotation">'+friend.name+'</span>'+
            '</div>'+
            '<div class="media-right media-middle">'+
            '<button class="btn img-circle frind-request-btn transition"></button>'+
            '</div>'+
            '</div>'+
            '</li>';
}
