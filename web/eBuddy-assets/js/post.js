/**
 * Created by marius.iliescu on 16-Jan-17.
 */
$(document).ready(function () {

    $('#newPostModal').on('shown.bs.modal', function () {
        $('#newPostContent').wysihtml5();
    });

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

        setTimeout(function()
        {
            loadLoggedUserPosts();
        }, 2000);

    });
});


function addNewPostAction(postContent) {
    var payload = {
        "post_content": postContent
    };

    $.ajax({
        method: 'POST',
        url: Routing.generate('add_new_post'),
        data: JSON.stringify(payload),
        error: function () {
//                    $('#errors').html('<p>An error has occurred</p>');
        },
        success: function (data) {
            console.log(data);
        }

    });
}

function appendPostContentInModal(postId) {

    $.ajax({
        method: 'GET',
        url: "{{url('get-post_by_id',{'postId': 5})}}",

        error: function () {
            // $('#errors').html('<p>An error has occurred</p>');
        },
        success: function (data) {
            if (data.error != false) {
                $("#postModal").find(".postContentInModal").html(getHtmlModalPost(data.response));
            }
        }

    });
}



function appendCommentsInModal(postId, limit, offset) {
    $.ajax({
        method: 'GET',
        url: "{{ url('get_post_comments_with_limit_and_offset' { 'postId': 5, 'limit': 2, 'offset': 0 })}}",
        error: function () {
            // $('#errors').html('<p>An error has occurred</p>');
        },
        success: function (data) {
            if (data.error != false) {
                JSON.parse(result.response).forEach(function (value, index) {
                    $("#postModal").find(".postContentInModal").append(getHtmlModalComment(value));
                    offset++;
                });
            }
        }

    });
}

function getHtmlPost(postData) {
    var sppSt = postData.appreciation_status;

    var likeBtnStatus = sppSt == 'none' ? 'inactive' : sppSt == 'like' ? 'active' : 'inactive';
    var disLikeBtnStatus = sppSt == 'none' ? 'inactive' : sppSt == 'dislike' ? 'active' : 'inactive';


    var footer =  '         <div class="panel-footer">' +
        '           <div class="row">' +
        '               <div class="post-actions pull-left">' +
        '                   <div class="row">' +
        '                       <div onclick="handleLike(this)" class="custom-responsive-action-img action_btn like_button_'+likeBtnStatus+' col-lg-6 col-xs-6"></div>' +
        '                       <div onclick="handleDisLike(this)" class="custom-responsive-action-img action_btn dislike_button_'+disLikeBtnStatus+' col-lg-6 col-xs-6"></div>'+
        '                   </div>' +
        '               </div>' +
        '' +
        '               <div class="pull-right row">' +
        '                 <button class = "btn col-lg-3" onclick="showUpCommentBox(event);" ><span class="icon-comment-discussion position-left"></span></button>' +
        '                 <button class = "btn col-lg-offset-1 col-lg-8" onclick="popupPostModal(event);" data-toggle="modal" data-target="#postModal">See More<span class="icon-arrow-right14 position-right"></span></button>' +
        '               </div>' +
        '           </div>' +
        '         </div>' +
        '         <div class="panel-footer comment_box hide">' +
        '               <input type="text" class="form-control" placeholder="leave a comment">' +
        '         </div>' ;

    var myvar = '<div id= "' + postData.id + '" class="timeline-row">' +
        '   <div class="timeline-icon">' +
        '         <img alt="img" src = "/web/' + postData.author_picure_path + '">' +
        '     </div>' +
        '' +
        '     <div class="panel panel-flat timeline-content">' +
        '         <div class="panel-heading">' +
        '             <h6 class="panel-title"></h6>' +
        '             <div class="heading-elements">' +
        '                 <span class="heading-text" data-livestamp=' + postData.posted_at.date + '><i' +
        '                             class="icon-checkmark-circle position-left text-success"></i></span>' +
        '                 <ul class="icons-list">' +
        '                     <li class="dropdown">' +
        '                         <a href="#" class="dropdown-toggle"' +
        '                            data-toggle="dropdown">' +
        '                             <i class="icon-arrow-down12"></i>' +
        '                         </a>' +
        '' +
        '                     </li>' +
        '                 </ul>' +
        '             </div>' +
        '         </div>' +
        '' +
        '         <div class="panel-body">' +
        '' +
        '             ' + postData.content +
        '' +
        '             <div class="lastComment">' +
        '             </div>' +
        '         </div>' +
        (isGuest ? '' : footer)+
        '     </div>' +
        '   </div>';

    return myvar;
}

function getHtmlModalPost(postData) {
    return '         <div class="panel-body">' +
        '             ' + postData.content +
        '         </div>';
}

function getHtmlModalComment(comment) {
    return '<div class="media">' +
        '<p class="pull-right"><small data-livestamp=' + comment.posted_at.date + '></small></p>' +
        '<a class="media-left" href="#">' +
        '   <img src="/web/' + comment.author_picure_path + '" class="img-circle">' +
        '</a>' +
        '<div class="media-body">' +
        '<h4 class="media-heading user_name">' + comment.author_name + '</h4>' +
        comment.content +
        '</div>' +
        '</div>';
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


    function loadComments() {
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
    }

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

function showUpCommentBox(e) {
    e = e || window.event;

    var target = e.target || e.srcElement;

    var postId = $(target).closest(".timeline-row").attr('id');
    postId = postId.substr(postId.indexOf("_") + 1);

    var commentBox = $(target).closest(".panel-footer").siblings(".comment_box");

    var inputCommentBox = commentBox.find("input");


    inputCommentBox.keypress(function (e) {
        var key = e.which;
        if (key == 13)  // the enter key code
        {
            var commentContent = $(this).val();

            postComment(postId, commentContent);
            appendLastCommentInTimeLine(postId);
            setTimeout(function () {
                appendLastCommentInTimeLine(postId);
            }, 1000);
            inputCommentBox.val('');
            return false;
        }
    });
    commentBox.removeClass("hide", function () {
        commentBox.fadeIn("slow");
    });

    //desactivate button
    $(target).attr("disabled", true);
}

function postComment(postId, commentContent) {
    var payload = {
        "comment_content": commentContent
    };
    $.ajax({
        method: 'POST',
        url: Routing.generate('add_new_comment', {postId: postId}),
        data: JSON.stringify(payload),
        error: function () {
//                    $('#errors').html('<p>An error has occurred</p>');
        },
        success: function (data) {
            console.log(data);
        }

    });
}

