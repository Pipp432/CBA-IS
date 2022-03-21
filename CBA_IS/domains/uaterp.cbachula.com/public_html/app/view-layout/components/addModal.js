function addModal(id, title, body) {

    $("body").append('<div class="modal fade launch-modal" id="' + id + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"> \
        <div class="modal-dialog" role="document"> \
            <div class="modal-content"> \
                <div class="modal-header"> \
                    <h5 class="modal-title" id="exampleModalLabel">' + title + '<h5> \
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> \
                        <span aria-hidden="true">&times;</span> \
                    </button> \
                </div> \
                <div class="modal-body">' + body + '</div> \
                <div class="modal-footer"> \
                    <button id="dismissButton" type="button" class="btn btn-secondary" data-dismiss="modal" data-keyboard="true">ปิด</button> \
                </div> \
            </div> \
        </div> \
    </div>');
    
    $('#' + id).on('keypress', function(e) {
        if( e.keyCode === 13 ) {
            $('#' + id).modal('hide');
        }
    });

}