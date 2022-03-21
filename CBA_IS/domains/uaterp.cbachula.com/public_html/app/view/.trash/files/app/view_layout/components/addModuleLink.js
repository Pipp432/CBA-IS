function addModuleLink(id, link, icon, text) {
    $('#' + id).append('<div class="col p-2"> \
                            <a href="' + link + '"> \
                                <div class="itemCol"> \
                                        <div class=" m-2 p-2"> \
                                            <table style="width: 100%;"> \
                                                <tr> \
                                                    <td> \
                                                        <h6 class="p-0"><i class="fa fa-4x fa-' + icon + '" aria-hidden="true"></i></h6> \
                                                        <h6 class="p-0 mb-0">' + text + '</h6> \
                                                    </td> \
                                                </tr> \
                                            </table> \
                                        </div> \
                                </div> \
                            </a> \
                        </div>');
}