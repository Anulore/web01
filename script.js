$('#input-y :button').on("click", function () {
    $("input[type='button']").removeClass("pressed-y");
    $(this).addClass("pressed-y");
});

$('#input-r :button').on("click", function () {
    $("input[type='button']").removeClass("pressed-r");
    $(this).addClass("pressed-r");
});
