$(document).ready(function() {

    /* ONCLICK EVENT */
    $("#notif").on('click', function () {
        loadNotification(/* ID LATER */);
    });

    $("#friend").on('click', function () {
        loadFriend(/* ID LATER */);
    });

    $("#submit").on('click', function() {
        saveMessage(
            $("#message").val(),
            $("#id").val()
        );
    });

    /* REFRESH THE WALL !! */
    setInterval(function() {
        loadWall();
    }, 2000);

    /* LOAD BADGE */
    loadFriendBadge(/* ID LATER */);
    loadWall();
    loadNotifBadge(/* ID LATER */);

    /* FUNCTION FOR SHITS */

    function saveMessage(message, id) {
        $.ajax({
            url: '?controller=posts&action=wall',
            method: 'POST',
            data: {
                message: message,
                id: id
            }
        }).done(function(msg) {
            loadWall();
        });
    }

    function loadWall() {
        var id = $("#id").val();
        $.ajax({
            url: '?controller=posts&action=wallView',
            method: 'POST',
            data: {
                id: id
            }
        }).done(function(msg) {
            try {
                var json    = JSON.parse(msg);
                var panel   = '';
                setTimeout(function () {
                    for (var js in json) {
                        panel += '<div class="panel panel-default message">';
                        panel += '<div class="panel-body "><div class="media-left">';
                        panel += '<a href="#"><img src="http://i.imgur.com/xWhH1Xp.png" class="media-object img-circle" alt="image avatar"></a>';
                        panel += '</div>';
                        panel += '<div class="media-body panel-body">';
                        panel += '<h4 class="media-heading">Clément lol <small><i class="glyphicon glyphicon-time"></i> ' + json[js]["date"] + '</i></small></h4>';
                        panel += '<span style="white-space: pre-line;">' + json[js]["message"] + ' </span>';
                        panel += '</div> </div></div>';
                        $("#actu").html(panel);
                    }
                }, 500);
            } catch (e) {
                $("#actu").html('Vos amis ne publient rien.');
            }

        });
    }

    function loadFriendBadge() {
        $(".notifbadge").html(10);
    }

    function loadNotifBadge() {
        $(".friendbadge").html(100);
    }

    function loadNotification() {
        $(".notif").html('<li ><a href="#"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Chargement...</a></li>');
        setTimeout(function () {
            $(".notif").html('<li><a href="#">Aucune notification</a></li>');
        }, 600);
    }

    function loadFriend() {
        $(".flist").html('<li ><a href="#"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Chargement...</a></li>');
        setTimeout(function () {
            $(".flist").html('<li><a href="#">Aucune nouvelle invitation</a></li>');
        }, 600);
    }
});
