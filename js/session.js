function writeCookie(name,value,hours) {
    var date, expires;
    if (hours) {
        date = new Date();
        date.setTime(date.getTime()+(hours*60*60*1000));
        expires = "; expires=" + date.toGMTString();
            }else{
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var i, c, ca, nameEQ = name + "=";
    ca = document.cookie.split(';');
    for(i=0;i < ca.length;i++) {
        c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) == 0) {
            return c.substring(nameEQ.length,c.length);
        }
    }
    return '';
}

function getSession() {
    if (localStorage.getItem("userId") === null ) {
        $("#logoutBtn").hide();
        if (readCookie("session") === ""){
            console.log("not logged in or no session id");
            $.ajax({
                url: 'api/session.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    writeCookie("session", response.session_id, 1);
                },
                error: function() {
                    console.error('Could not retrieve cart count.');
                }
            });
        }
    }
    
}