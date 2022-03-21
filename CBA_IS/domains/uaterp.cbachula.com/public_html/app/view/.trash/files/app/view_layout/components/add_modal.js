function add_modal(id, body) {

    $("body").append('<div class="modal fade launch-modal" id="' + id + '" tabindex="-1" role="dialog" aria-hidden="true"> \
        <div class="modal-dialog" role="document"> \
            <div class="modal-content"> \
                <div class="modal-body" id="' + id + '_text">' + body + '</div> \
            </div> \
        </div> \
    </div>');

    $('#' + id).keypress((e) => { if(e.keyCode == 13) $('#' + id).modal('hide'); })

}