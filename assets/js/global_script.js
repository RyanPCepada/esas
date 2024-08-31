let myToastTimeout;
$(document).ready(function () {
   // To overlay 2 modals at a time
    $(document).on('show.bs.modal', '.modal', function () {
       const zIndex = 1040 + 10 * $('.modal:visible').length;
       $(this).css('z-index', zIndex);
       setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
    });

    $('#mdlconfirm').on("shown.bs.modal", function() {
        $('#btnconfirmmdlokay').focus();
    });
});

function modal_alert(text, type, duration) {
    clearTimeout(myToastTimeout);
    var bgcolor;
    if (type == 'success') {
        bgcolor = 'mediumaquamarine';
    } else if (type == 'danger') {
        bgcolor = 'rosybrown';
    } 
    $('#gbltoast .toast-body').html(text);
    $('#gbltoast .toast-body').css('background-color', bgcolor);
    $('#gbltoast').show()
    myToastTimeout = setTimeout(() => {
        $('#gbltoast').hide(50)
    }, duration);

}

function showloading(type) {
   if (type) { // true
       $('#mdlloading').modal('show');
   } else { // false
       $('#mdlloading').modal('hide');
   }
}

function showloadingdiv(element) {
   $(element).html('<div class="w-100 text-center mt-3"><img src="assets/img/loadinggif.gif" height="30"></div>');

}

function modal_confirm(okayfunct, cancelfunct, message) {
   $('#mdlconfirm').modal('show');
   $('#btnconfirmmdlokay').attr('onclick', okayfunct);
   $('#btnconfirmmdlcancel').attr('onclick', cancelfunct);
   $('#pmdlconfirmtext').html(message);

}

function printview2(divID) {
    var newWin = window.open('', 'Print', 'height=600,width=800');
    newWin.document.write('<html><link href="css/styles.css" rel="stylesheet" /><style>.txt-black{color: black !important;}.hiddentag{display: none;}body{font-family: "Helvetica" !important;font-size: 8px !important;-webkit-print-color-adjust: exact;}#rpt-head{background-color:blue;}</style><body onload="window.print()">' + $('#' + divID).html() + '</body></html>');
}


function logout() {
   $.post("assets/components/logout.php", function (data) {
       localStorage.clear();
       deleteAllCookies();
       window.location.href = "login.php";
   });
}

function deleteAllCookies() {
   var cookies = document.cookie.split(";");
   for (var i = 0; i < cookies.length; i++) {
       var cookie = cookies[i];
       var eqPos = cookie.indexOf("=");
       var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
       document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
   }


}


$(document).ready(function() {
    $('#ticket_table').DataTable();
});
