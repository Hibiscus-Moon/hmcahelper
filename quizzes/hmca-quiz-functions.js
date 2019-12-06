function loadNextQuestion(id) {
    jQuery("#question" + id).hide(300);
    jQuery("#question" + (id + 1)).show(300);
    updateProgress(id+1);
    return false;
}

function loadPrevQuestion(id) {
    jQuery("#question" + id).hide(300);
    jQuery("#question" + (id - 1)).show(300);
    updateProgress(id-1);
    return false;
}
function updateProgress(id) {
    var total = jQuery("#progressBar").data("total");
    width = (id / total) * 100;
    jQuery("#progressBar").css("width", width + "%");
    jQuery("#progressBar").css("margin-right", (100-width) + "%");
}