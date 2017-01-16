/**
 * Created by marius.iliescu on 16-Jan-17.
 */
$(document).ready(function () {

    var timeLine = $("#postsTimeline");


    timeLine.html(getLoaderHtml());

    // if(!isGuest){
    //     if(userId == null) {
    //         loadLoggedUserPosts();
    //     }else{
    //         loadUserPosts(userId);
    //     }
    // }else {
    //     loadUserPosts(userId);
    // }

    $(window).scroll(function() {
        // End of the document reached?
        if (timelineScrollEnded && $(window).scrollTop() >= $(document).height() - $(window).height() - 300) {
            if(!isGuest){
                if(userId == null) {
                    loadLoggedUserPosts();
                }else{
                    loadUserPosts(userId);
                }
            }else {
                loadUserPosts(userId);
            }
        }
    });

});



function loadLoggedUserPosts(){
    timelineScrollEnded = false;

    $('.loading3').show();

    var payload = {
        "limit":limit,
        "offset":offset
    };

    $.ajax({
        method: 'POST',
        data: JSON.stringify(payload),
        url: Routing.generate('get_user_posts_with_limit_and_offset'),

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


function loadUserPosts(userId){
    timelineScrollEnded = false;

    $('.loading3').show();

    var payload = {
        "limit":limit,
        "offset":offset,
        "user_id":userId
    };

    $.ajax({
        method: 'POST',
        data: JSON.stringify(payload),
        url: Routing.generate('get_c_user_posts_with_limit_and_offset'),

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


function loadAllPosts(){
    timelineScrollEnded = false;

    $('.loading3').show();

    var payload = {
        "limit":limit,
        "offset":offset
    };

    $.ajax({
        method: 'POST',
        data: JSON.stringify(payload),
        url: Routing.generate('get_user_posts_with_limit_and_offset'),

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

function appendLastCommentInTimeLine(postId) {
    $.ajax({
        method: 'GET',
        url: Routing.generate('get_last_comment_from_a_post', {postId: postId}),
        error: function () {
            // $('#errors').html('<p>An error has occurred</p>');
        },
        success: function (data) {
            var response = JSON.parse(data.content);

            if (!data.error) {
                var latestPostId = '#' + postId;
                var responseContent = response.response;

                $(latestPostId).find(".lastComment").html(getLastCommentHtml(responseContent));
            } else {
                console.log(data.message);
            }
        }
    });
}


function getLastCommentHtml(comment) {

    if (comment != null) {
        return '<br/><h6 class="content-group">' +
            '    <i class="icon-comment-discussion position-left"></i>' +
            '        Las comment from <a href="'+Routing.generate('profile_edit')+"/"+comment.author_id+'">' + comment.author_name + '</a>:' +
            '</h6>' +
            '' +
            '<blockquote>' +
            '    <p>' + comment.content + '</p>' +
            '    <footer><cite title="Source Title" data-livestamp=' + comment.posted_at.date + '></cite>' +
            '    </footer>' +
            '</blockquote>';
    }


    return '<br/><h6 class="content-group">' +
        '    <i class="icon-comment-discussion position-left"></i>' +
        '        No comments yet' +
        '</h6>';

}