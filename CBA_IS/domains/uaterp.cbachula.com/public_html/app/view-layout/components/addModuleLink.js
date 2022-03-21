function addModuleLink(id, link, icon, text) {
    $('#' + id).append('<div class="col p-0"> \
                            <a class="moduleLink" href="' + link + '"> \
                                <div class="align-middle itemCol m-2"> \
                                    <table style="text-align: center; width: 100%;"> \
                                        <tr> \
                                            <td class="align-middle"> \
                                                <h6 class="p-0 mb-2 mt-3"><i class="fa fa-3x fa-' + icon + '" aria-hidden="true"></i></h6> \
                                                <h6 class="p-0 mt-2 mb-3">' + text + '</h6> \
                                            </td> \
                                        </tr> \
                                    </table> \
                                </div> \
                            </a> \
                        </div>');
}