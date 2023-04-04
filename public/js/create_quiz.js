var url = window.location.toString();
var curr_page = url.split('create#');
if (curr_page[curr_page.length-1] == "step-2"){
    next();
}else{
    back();
}
function next(){
    if ($('#name').css('display') != "none"){
        $('#name').toggle();
        $('#phone').toggle();
        $('#reason').toggle();
        $('#needs').toggle();
        $('#notes').toggle();
        $('#date_tour').toggle();
        $('#time_tour').toggle();
        $('#start_point').toggle();
        $('#end_point').toggle();
        $('#full_address').toggle();
        $('#text-step-1').toggle();
        $('#text-step-2').toggle();
        $('#btn-step-1').toggle();
        $('#btn-step-2').toggle();
    }
}
function back(){
    if ($('#name').css('display') == "none"){
        $('#name').toggle();
        $('#phone').toggle();
        $('#reason').toggle();
        $('#needs').toggle();
        $('#notes').toggle();
        $('#date_tour').toggle();
        $('#time_tour').toggle();
        $('#start_point').toggle();
        $('#end_point').toggle();
        $('#full_address').toggle();
        $('#text-step-1').toggle();
        $('#text-step-2').toggle();
        $('#btn-step-1').toggle();
        $('#btn-step-2').toggle();
    }
}
