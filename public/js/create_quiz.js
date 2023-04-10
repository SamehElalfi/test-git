var url = window.location.toString();
var curr_page = url.split('create#');
if (curr_page[curr_page.length-1] == "step-2"){
    next();
}else{
    back();
}
function set_date_return(){
    if ($('#options_return_input option:selected').val() == 3){
        $('#date_return').css('display', 'flex');
    }else{
        $('#date_return').css('display', 'none');
    }
}

function type_tour_change(){
    // alert(2312);
    if ($('#type_tour option:selected').val() == 1){
        // alert(1);
        $('#return_tour_options').css('display', 'block');
    }else{
        // alert(0);
        $('#return_tour_options').css('display', 'none');
    }
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
