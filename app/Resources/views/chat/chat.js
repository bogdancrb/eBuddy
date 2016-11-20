$(document).ready(function () {
    var users = getUsers();
    users.forEach(spawnUsersInChatList);

    $(".chat_head").click(function(){
        $(".chat_body").slideToggle("slow");
    });
});


function getUsers() {
    var userList = [];

    var user1 = {
        id: "1234",
        name: "Marius Iliescu",
        profilePicture: "http://bootdey.com/img/Content/User_for_snippets.png",
        status: "inactive"
    };

    var user3 = {
        id: "1236",
        name: "Somewone",
        profilePicture: "http://www.sgbcconf.org/images/team/1.png",
        status: "away"
    };

    var user2 = {
        id: "1235",
        name: "Admin",
        profilePicture: "https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg",
        status: "available"
    };

    userList.push(user1);
    userList.push(user2);
    userList.push(user3);
    return userList;
}

function spawnUsersInChatList(item, index) {
    var frient_item =
        '<li id="'+ item.id+'" class="friend list-group-item text-left" onClick="openChatBox(event)">' +
            '<img class="img-circle" src="' + item.profilePicture + '">' +
            '<label class="name">' +
                 item.name +
            '</label>' +
            '<label class="pull-right">' +
                '<div class="status ' + item.status + '"></div>' +
            '</label>' +
        '</li>"';

    var new_item = $(frient_item).hide();
    $('#friend_list').append(new_item);
    new_item.show('normal');
}

function openChatBox(obj) {

    var source = $(obj.srcElement).closest("li");

    var user = getUserById(source.attr('id'));

    if(document.getElementById("chat_"+user.id) != null){
        return;
    }

    var chatBox =
        '<div id="chat_'+user.id+'" class="chat list-group-item">'+
            '<div class="head" onclick="togleChatBox(this)">'+
                '<div class="head_details">'+
                    '<img class="img-circle" src="' + user.profilePicture + '">' + '     '+
                    user.name+
                '</div>'+
                '<div class="head_tools">'+
                     '<button type="button" class="btn btn-chat-tool" data-widget="remove" onClick="closeConversation(this)">'+
                        '<span class=" glyphicon glyphicon-remove"></span>'+
                     '</button>'+
                '</div>'+
            '</div>'+
            '<div class="body">'+
                '<div class="content">'+
                    '<ul class="messages_list">'+
                    '</ul>'+
                '</div>'+
                '<div class="footer">'+
                    '<div class="input-group">'+
                        '<input type="text" name="message" onkeypress="typeMessage(event)" placeholder="Type Message ..." class="form-control">'+
                        '<span class="input-group-btn">'+
                            '<button type="submit" name = "btn_send_message" class="btn btn-default btn-flat">'+
                                '<span class="glyphicon glyphicon-send"></span>'+
                            '</button>'+
                        '</span>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>';

    var new_item = $(chatBox).hide();
    $('#chats_windows').append(new_item);
    new_item.show('normal');
}


function getUserById(id){
    var users = getUsers();

    for (var i=0;i<users.length;i++){
        if(users[i].id == id){
            return users[i];
        }
    }
}

function closeConversation(obj){
    var chatPanel = $(obj).parent().parent().parent();
    chatPanel.fadeOut("slow", function() { chatPanel.remove();});
}

function togleChatBox(obj) {
    $(obj).parent().find(".body").slideToggle('slow');
}

function typeMessage(event){
    var key = event.which;
    if(key == 13)  // the enter key code
    {
        var messageList = $(event.target).parent().parent().parent().find('.messages_list');
        appendMyMessages(event.target.value, messageList);

        event.target.value ='';


        //$(event.target).text("ok");
        $('input[name = btn_send_message]').click();
        return false;
    }
}

function appendMyMessages(message , to){
    var msg =
        '<li class="message to">'+
            '<div class="message_content">'+
                message+
            '</div>'+
        '</li>';
    to.animate({scrollTop : 0},800);
    to.append(msg);
}