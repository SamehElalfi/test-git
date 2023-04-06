function seen(){
    var myreq = new XMLHttpRequest();
    myreq.onreadystatechange = function (){
    };
    $url = "/seen/all";
    myreq.open('get', $url, true);
    myreq.send();
}
