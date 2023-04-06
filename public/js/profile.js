function get_otp() {
    var myreq = new XMLHttpRequest();
    myreq.onreadystatechange = function (){
        if(this.readyState === 4 && this.status == 200){
            var ovj = JSON.parse(this.responseText);
            $('#send_val').html(ovj.value_send);
            $('#send_currency').html(ovj['payment_1'].currency);
            $('#get_val').html(ovj.value_recieve);
            $('#get_currency').html(ovj['payment_2'].currency);
        }
    };
    $url = "/user/get_otp";
    myreq.open('get', $url, true);
    myreq.send();

    // const audio = new Audio("https://freesound.org/data/previews/501/501690_1661766-lq.mp3");
    // audio.play();
}
function togglePopup() {
    $("#content").toggle();
}
function togglePopupOTP() {
    $("#content_otp").toggle();
}
function prev() {
    $('#step-2').toggle();
    $('#step-1').toggle();
    $('#pop-fullname').toggle();
    $('#pop-gender').toggle();
    $('#pop-age').toggle();
    $('#pop-phone').toggle();
    $('#pop-relation').toggle();
    $('#pop-redirect').toggle();
    $('#text-step-1').toggle();
    $('#text-step-2').toggle()
}
function next() {
    $('#step-2').toggle();
    $('#step-1').toggle();
    $('#pop-fullname').toggle();
    $('#pop-gender').toggle();
    $('#pop-age').toggle();
    $('#pop-phone').toggle();
    $('#pop-relation').toggle();
    $('#pop-redirect').toggle();
    $('#text-step-1').toggle();
    $('#text-step-2').toggle()
}
