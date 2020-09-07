
$(function (){

$(".confirm").click(function() {
    return confirm("are you sure delet member");
})



})

$(".loginform span").click(function(){
    $(this).addClass("active").siblings().removeClass("active");
    $(".loginform form").fadeOut();
    $("." + $(this).data('class')).fadeIn();
})