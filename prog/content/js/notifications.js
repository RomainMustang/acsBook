$(document).ready(function() {
    /* ONCLICK EVENT */
    $("#notif").on('click', function () {
        loadNotification();
    });

    $("#friend").on('click', function () {
        loadFriendBadge();
        loadFriend();
    });

    $("#submit").on('click', function() {
        saveMessage(
            $("#message").val(),
            $("#id").val()
        );
    });

    $("#actu2").ready(function() {
        loadWallProfil();
    });

    $(document).on('click', function(test) {
        if (!isNaN(test.target.id)) {
            if (test.target.className.indexOf("remove") != -1) {
                console.log($("#id").val(), $("p").attr("data-friend"));
            }
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
        if ($("#actu2") != undefined) {
            loadWallProfil();
        }
    }, 30000);

    /* LOAD BADGE */
    loadFriendBadge();
    loadWall();
    loadNotifBadge();
    loadFriendList();

    /* FUNCTION FOR SHITS */

    function AddOrRemoveFriend(classe, id1, id2) {
        if ((classe.length == 0) || (isNaN(id1)) || (isNaN(id2))) return false;
        $.ajax({
            url : '?controller=posts&action=friend',
            method: 'POST',
            data: {
                id1: id1,
                id2: id2,
                option: classe == "accept" ? 2 : 0
            }
        }).done(function(response) {
            loadFriendBadge();
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
            loadWallProfil();
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
                        panel += '</div>';
                        panel += '</div></div>';
                        $("#actu").html(panel);
                    }
                }, 500);
            } catch (e) {
                $("#actu").html('Il y a encore rien à voir ici.');
            }

        });
    }

    function loadWallProfil() {
        var id = $("#id").val();
        $.ajax({
            url: '?controller=posts&action=wallView&profil=true',
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
                        panel += '</div>';
                        panel += '</div></div>';
                        $("#actu2").html(panel);
                    }
                }, 500);
            } catch (e) {
                $("#actu2").html('Il y a encore rien à voir ici.');
            }

        });
    }

    function loadFriendBadge() {
        var id = $("#id").val();
        $.ajax({
            url: '?controller=posts&action=friend',
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
                url: '?controller=posts&action=friend',
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
                            li += '<li id="' + json[i]["id"] + '">';
                            li += '<a href="#"><img src="' + json[i]["av"] + '" alt="" />';
                            li += 'Accepter l\'invitation de ' + json[i]["nom"] + ' ? <span id="' + json[i]["id"] + '" class="accept glyphicon glyphicon-ok"></span> <span id="' + json[i]["id"] + '" class="refuse glyphicon glyphicon-remove"></span>';
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

    function loadFriendList() {
        $.ajax({
            url: '?controller=posts&action=friend&list=true',
            method: 'POST',
            data: {
                id: $("#id").val()
            }
        }).done(function(msg) {
            var li = '';
            var json = JSON.parse(msg);
            for (var f in json) {
                li += '<div class="group-ami">';
                li += '<img src="' + json[f]["av"] + '">';
                li += '<p data-friend="' + json[f]["id"] + '">' + json[f]["nom"] + '</p>';
                li += '<button type="button" class="btn btn-default remove" style="margin-bottom: 3%">Enlever des amis</button></div>';
                $("#friendload").html(li);
            }
        });
    }
});
