function addConfirmModal(id, title, body, method) {
    
    $('#' + id).on('keypress', (e) => { if(e.keyCode === 13) $('#' + id).modal('hide'); });
    
    return '<div class="modal fade" id="' + id + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"> \
        <div class="modal-dialog" role="document"> \
            <div class="modal-content"> \
                <div class="modal-header"> \
                    <h5 class="modal-title" id="exampleModalLabel">' + title + '</h5> \
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> \
                        <span aria-hidden="true">&times;</span> \
                    </button> \
                </div> \
                <div class="modal-body">' + body + '</div> \
                <div class="modal-footer"> \
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">à¸›à¸´à¸”</button> \
                    <button type="button" class="btn btn-default" ng-click="' + method + '">à¸¢à¸·à¸™à¸¢à¸±à¸™</button> \
                </div> \
            </div> \
        </div> \
    </div>';

}