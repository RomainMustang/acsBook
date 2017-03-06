$(document).ready(function() {
    /* ONCLICK EVENT */
    $("#notif").on('click', function () {
        loadNotification();
    });

    $("#friend").on('click', function () {
        loadFriend();
    });

    $("#submit").on('click', function() {
        saveMessage(
            $("#message").val(),
            $("#id").val()
        );
    });

    $(document).on('click', function(test) {
        if (!isNaN(test.target.id)) {
           if (test.target.className.indexOf("refuse") != -1 || test.target.className.indexOf("accept") != -1) {
               var classe = test.target.className.split(" ");
               AddOrRemoveFriend(classe[0], $("#id").val(), test.target.id);
           }
        }
    });

    /* REFRESH THE WALL !! */
    setInterval(function() {
        loadFriendBadge();
        loadWall();
    }, 30000);

    /* LOAD BADGE */
    loadFriendBadge();
    loadWall();
    loadNotifBadge();

    /* FUNCTION FOR SHITS */

    function AddOrRemoveFriend(classe, id1, id2) {
        if ((classe.length == 0) || (isNaN(id1)) || (isNaN(id2))) return false;
        $.ajax({
            url : '?controller=posts&action=friends',
            method: 'POST',
            data: {
                id1: id1,
                id2: id2,
                option: classe == "accept" ? 2 : 0
            }
        }).done(function(response) {
           var json = JSON.parse(response);
            if (json["error"] == false) {
                loadFriendBadge();
            } else {
                console.log("fail ...");
            }
        });

    }

    function saveMessage(message, id) {
        console.log(typeof(message));
        if (message.length == 0 || $.trim(message) == '') return false; // fml
        $.ajax({
            url: '?controller=posts&action=wall',
            method: 'POST',
            data: {
                message: message,
                id: id
            }
        }).done(function(msg) {
            $("#message").val("");
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
                        panel += '<a href="#"><img src="' + json[js]["avatar"] + '" class="media-object img-circle" alt="image avatar"></a>';
                        panel += '</div>';
                        panel += '<div class="media-body panel-body">';
                        panel += '<h4 class="media-heading">' + json[js]["nom_util"] + ' <small><i class="glyphicon glyphicon-time"></i> ' + json[js]["date"] + '</i></small></h4>';
                        panel += '<span style="white-space: pre-line;">' + json[js]["message"] + ' </span>';
                        panel += '</div> </div></div>';
                        $("#actu").html(panel);
                    }
                }, 500);
            } catch (e) {
                $("#actu").html('Il y a encore rien à voir ici.');
            }

        });
    }

    function loadFriendBadge() {
        var id = $("#id").val();
        $.ajax({
            url: '?controller=posts&action=friends',
            method: 'POST',
            data: {
                id: id
            }
        }).done(function(msg) {
            var json = JSON.parse(msg);
            $(".friendbadge").html(json.friendcount);
        });
    }

    function loadNotifBadge() {

    }

    function loadNotification() {
        $(".notif").html('<li ><a href="#"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Chargement...</a></li>');
        setTimeout(function () {
            $(".notif").html('<li><a href="#">Aucune notification</a></li>');
        }, 600);
    }

    function loadFriend() {
        var id = $("#id").val();
        $(".flist").html('<li ><a href="#"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Chargement...</a></li>');
        setTimeout(function () {
            $.ajax({
                url: '?controller=posts&action=friends',
                method: 'POST',
                data: {
                    id: id
                }
            }).done(function(msg) {
                try {
                    var json = JSON.parse(msg);
                    var li   = '';
                    if (json.friendcount == 0) {
                        $(".flist").html("<li><a href=\"#\">Aucune demande d'ami</a></li>");
                    } else {
                        for (var i = 0; i < json.friendcount; i++) {
                            li += '<li id="' + json[i]["requester"] + '">';
                            li += '<a href="#"><img class="flag" src="http://i.imgur.com/xWhH1Xp.png" alt="" />';
                            li += 'Accepter l\'invitation de ' + json[i]["requester"] + ' ? <span id="' + json[i]["requester"] + '" class="accept glyphicon glyphicon-ok"></span> <span id="' + json[i]["requester"] + '" class="refuse glyphicon glyphicon-remove"></span>';
                            li += '</a></li>';
                            $(".flist").html(li);
                        }
                    }
                } catch(e) {
                    console.log('fail');
                }
            });
        }, 600);
    }
});
