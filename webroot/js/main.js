/*
 * jQuery File Upload Plugin JS Example 8.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, unparam: true, regexp: true */
/*global $, window, document */

$(document).ready(function () {
    'use strict';

    if ($('table tbody.files tr').length == 0)
        $('#content .nav-tabs > li a[href="#product-images"]').tab('show');

    var url_upload_image = $("#product-images").attr("rel");

    // Initialize the jQuery File Upload widget:
    $('#ProductAdminEditForm').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: url_upload_image
    });

    // Load existing files:
    $('#ProductAdminEditForm').addClass('fileupload-processing');
    $.ajax({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: url_upload_image,
        dataType: 'json',
        context: $('#ProductAdminEditForm')[0]
    }).always(function (result) {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) {
            $('#content .nav-tabs > li:first_child').tab('show');
        $(this).fileupload('option', 'done')
            .call(this, null, {result: result});
    });
});
