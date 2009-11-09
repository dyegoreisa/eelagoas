/**
 * Novo método validador para suportar os formatos (mm/aaaa hh) ou (dd/mm/aaaa hh) 
 * Adicionado ao plugin jquery validator
 */
$.validator.addMethod("dataColeta", function(value, element) { 
    dd_mm_aaaa_hh = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/;
    mm_aaaa_hh    = /^(0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/;

    ok = dd_mm_aaaa_hh.exec(value);
    if (ok != null) {
        return true;
    } else {
        ok = mm_aaaa_hh.exec(value);
        if (ok != null) {
            return true;
        } else {
            return false;
        }
    }
}, "Olha a data");

