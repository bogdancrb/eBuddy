$(document).ready(function () {
    var numberOfFriendRequests = 0;

    getFriendRequests();

    function addFriendAction(friendId)
    {
        var userInformation = {
            'friend_id': friendId
        };

        $.ajax({
            method: "POST",
            url:  "api/v1/send_friend_request",
            data: JSON.stringify(userInformation),
            success: function(data) {
                getRecomandedFriends();
            }
        });
    }
    
    function getRecomandedFriends(){
        var numberOfFriendsSuggestion = 0;

        $.ajax({
            method: 'GET',
            url: Routing.generate('get_recomanded_friends'),

            success: function(result) {

                $('#recomanderBlock').html('');

                var data = JSON.parse(result.content);
                if (!data.error) {
                    data.response.forEach(function (value, index) {
                        var userHtml = getRecomandedFriendHtml(value);
                        $('#recomanderBlock').append(userHtml);
                        numberOfFriendsSuggestion++;
                    });
                    $('.loading3').hide();
                    timelineScrollEnded = true;
                }

                $('.recommenderFriendAdd').on('click', function(){
                    addFriendAction($(this).val());
                });

                $('.badge-success').html(numberOfFriendsSuggestion);
            }
        });
    }

    function getFriendRequests()
    {
        var randomReasons = [
            'Please add me to your friends list',
            'Hello ! :D',
            'I want to add you, please accept my request',
            'Add me !',
            'Hello friend'
        ];

        $.ajax({
            method: "GET",
            url: 'api/v1/get_friend_requests',
            success: function(data)
            {
                var dataContent = JSON.parse(data.content);

                var friendRequestContainer = $('li.friendRequest-0');

                dataContent.response.forEach(function(val, index, arr) {
                    var friendNameContainer = friendRequestContainer.find('.media-body .media-heading');
                    var friendAddReasonContainer = friendRequestContainer.find('.media-body .text-muted');
                    var randomReasonKey = Math.floor(Math.random() * (randomReasons.length + 1));
                    var friendRequestAccept = friendRequestContainer.find('.media-right .friendAcceptRequestButton');
                    var friendRequestReject = friendRequestContainer.find('.media-right .friendRejectRequestButton');

                    friendRequestContainer.removeClass('hide');
                    friendNameContainer.html(val.profile_first_name + ' ' + val.profile_last_name);
                    friendAddReasonContainer.html(randomReasons[randomReasonKey]);
                    friendRequestAccept.val(val.user_id);
                    friendRequestReject.val(val.user_id);

                    if (index < arr.length - 1)
                    {
                        friendRequestContainer.parent().append('<li class="friendRequest-' + (index + 1) + ' media"></li>');
                        friendRequestContainer = $('li.friendRequest-' + (index + 1));
                        friendRequestContainer.html($('li.friendRequest-' + index).html());
                    }
                    numberOfFriendRequests++;
                });

                if (numberOfFriendRequests > 0)
                {
                    $('.friendRequestUsers').parent().append('<span class="nrOfFriendRequests badge bg-warning-400">'+ numberOfFriendRequests +'</span>');
                }

                $('.friendAcceptRequestButton').on('click', function(){
                    acceptFriendAction(this, $(this).val());
                });
            }
        })
    }

    function acceptFriendAction(elem, friendId)
    {
        var userInformation = {
            'friend_id': friendId
        };

        $.ajax({
            method: "POST",
            url:  "api/v1/accept_friend_request",
            data: JSON.stringify(userInformation),
            success: function(data) {
                $(elem).parent().parent().remove();
                $('.nrOfFriendRequests').html(numberOfFriendRequests - 1);

                numberOfFriendRequests--;

                if ($('.nrOfFriendRequests').html() <= 0)
                {
                    $('.nrOfFriendRequests').remove();
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
        '<button class="recommenderFriendAdd btn img-circle friend-request-btn transition"  value="' + friend.user_id + '"></button>' +
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