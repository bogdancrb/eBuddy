$(document).ready(function () {
    $('#newPostModal').on('shown.bs.modal', function () {
        $('#newPostContent').wysihtml5();
    });

    var offset = 0;
    var limit = 3;
    var timelineScrollEnded = true;

    loadPosts();

    $(".btn-pref .btn").click(function () {
        $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
        // $(".tab").addClass("active"); // instead of this do the below
        $(this).removeClass("btn-default").addClass("btn-primary");
    });

    $('#newPostButton').click(function() {
        var postContent = $("#newPostContent").val();
        addNewPostAction(postContent);
        //offset=0;
        //                    $('#postsTimeline').prepend(postHtml);
    });

    $(window).scroll(function() {
        // End of the document reached?
        if (timelineScrollEnded && $(window).scrollTop() >= $(document).height() - $(window).height() - 300) {
            timelineScrollEnded = false;
            loadPosts();
        }
    });

    function loadPosts(){
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

                JSON.parse(result.response).forEach(function(value, index) {
                    offset++;
                    var postHtml = getHtmlPost(value);
                    $('#postsTimeline .timeline-row:last').before(postHtml);
                    appendLastCommentInTimeLine(value.id);

                });
                $('.loading3').hide();
                timelineScrollEnded = true;
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
        url: apliGetLastCommentFromApost,
        error: function() {
            // $('#errors').html('<p>An error has occurred</p>');
        },
        success: function(data) {
            var response = JSON.parse(data.content);

            if(response.error == false){
                var latestPostId = '#post_'+postId;
                var responseContent = JSON.parse(response.response);
                var comment = responseContent != [] ? responseContent[0] : null;
                $(latestPostId).find(".lastComment").append(getLastCommentHtml(comment));
            }
        }
    });
}

function getHtmlPost(postData){
    var myvar = '<div id= "post_'+postData.id+'" class="timeline-row">'+
        '   <div class="timeline-icon">'+
        '         <img alt="img" src = "https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg">'+
        '     </div>'+
        ''+
        '     <div class="panel panel-flat timeline-content">'+
        '         <div class="panel-heading">'+
        '             <h6 class="panel-title">Post Id '+postData.id+'</h6>'+
        '             <div class="heading-elements">'+
        '                 <span class="heading-text"><i'+
        '                             class="icon-checkmark-circle position-left text-success"></i>'+postData.postedAt.date+'</span>'+
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
        '             <ul>'+
        '                 <li><a href="#"><i class="icon-comment-discussion position-left"></i>71</a></li>'+
        '             </ul>'+
        ''+
        '             <ul class="pull-right">'+
        '                 <li><a onclick="popupPostModal(event);" data-toggle="modal" data-target="#postModal" href="#">See More<i class="icon-arrow-right14 position-right"></i></a>'+
        '                 </li>'+
        '             </ul>'+
        '         </div>'+
        '     </div>'+
        '   </div>';

    return myvar;
}

function getHtmlModalPost(postData){
    var myvar = '         <div class="panel-body">'+
        '             '+postData.content+
        '         </div>';

    return myvar;
}

function getHtmlModalComment(comment){
    var myvar = '         <div class="panel-body">'+
        '             '+postComment.content+
        '         </div>';

    return myvar;
}

function getLastCommentHtml(comment) {
    if(comment != null){
        return '<br/><h6 class="content-group">'+
            '    <i class="icon-comment-discussion position-left"></i>'+
            '        Las comment from <a href="#">Marius Iliescu</a>:'+
            '</h6>'+
            ''+
            '<blockquote>'+
            '    <p>'+comment.content+'</p>'+
            '    <footer>Marius, <cite title="Source Title">'+comment.postedAt.date+'</cite>'+
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


    var postId = $(target).closest(".timeline-row").attr('id');


    $.ajax({
        method: 'GET',
        url:  "{{ url('get-post_by_id',{'postId': 5,})}}",
        error: function() {
        //                    $('#errors').html('<p>An error has occurred</p>');
        },
        success: function(data) {
            if(data.error != false){
                $("#postModal").find(".postContentInModal").html(getHtmlModalPost(data.response));
            }
        }

});

$("#postModal").find(".postCommentsInModal").html('works');

}