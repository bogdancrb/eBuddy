$(document).ready(function () {
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

    getRecomandedFriends();
});


function getRecomandedFriendHtml(friend) {

    return '<li class="media">' +
        '<div class="media-link">' +
        '<div class="media-left"><img src="/web/' + friend.picture + '"' +
        'class="img-circle" alt="no picture"></div>' +
        '<div class="media-body">' +
        '<a href="'+Routing.generate('profile_edit')+"/"+friend.id+'" class="media-heading text-semibold">' + friend.name + '</a>' +
        '<span class="media-annotation">' + friend.name + '</span>' +
        '</div>' +
        '<div class="media-right media-middle">' +
        '<button class="btn img-circle frind-request-btn transition"></button>' +
        '</div>' +
        '</div>' +
        '</li>';
}

function handleLike(data){
    var likeButton =$(data);
    var dislikeButton = likeButton.siblings(".action_btn").eq(0);
    var status = 'like';

    var postId = likeButton.closest('.timeline-row').attr('id');

    likeButton.effect("bounce", { direction:'up', times:5 }, 300);

    if (likeButton.hasClass("like_button_active")) {

        likeButton.removeClass('like_button_active');
        likeButton.addClass('like_button_inactive');

        status = 'none';
    }else{
        if (dislikeButton.hasClass("dislike_button_active")) {
            dislikeButton.removeClass('dislike_button_active');
            dislikeButton.addClass('dislike_button_inactive');
        }

        likeButton.removeClass('like_button_inactive');
        likeButton.addClass('like_button_active');
    }

    changeAppreciationStatus(status, postId);
}

function handleDisLike(data){

    var dislikeButton =$(data);
    var likeButton = dislikeButton.siblings(".action_btn").eq(0);
    var status = 'dislike';

    var postId = dislikeButton.closest('.timeline-row').attr('id');

    dislikeButton.effect("bounce", { direction:'down', times:5 }, 300);

    if (dislikeButton.hasClass("dislike_button_active")) {
        dislikeButton.removeClass('dislike_button_active');
        dislikeButton.addClass('dislike_button_inactive');

        status = 'none';

    }else{
        if (likeButton.hasClass("like_button_active")) {
            likeButton.removeClass('like_button_active');
            likeButton.addClass('like_button_inactive');
        }

        dislikeButton.removeClass('dislike_button_inactive');
        dislikeButton.addClass('dislike_button_active');

    }

    changeAppreciationStatus(status, postId);
}

function changeAppreciationStatus(status, postId) {

    var payload = {
        'post_id':postId,
        'status': status
    };

    $.ajax({
        method: 'POST',
        url: Routing.generate('changeAppreciation'),
        data: JSON.stringify(payload),
        error: function () {

        },
        success: function (data) {

        }

    });
}