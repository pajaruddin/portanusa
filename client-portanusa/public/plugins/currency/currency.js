function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function decimalValue(event) {
    var charCode = (event.which) ? event.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function formatCurr(ini) {
    if (ini.value != "") {
        var number = bersihPemisah(ini.value);
        ini.value = CurrencyFormat(number);
    } else {
        ini.value = 0;
    }
}

function CurrencyFormat(number) {
    var decimalplaces = 0;
    var decimalcharacter = ",";
    var thousandseparater = ".";
    number = parseFloat(number);
    var sign = number < 0 ? "-" : "";
    var formatted = new String(number.toFixed(decimalplaces));
    if (decimalcharacter.length && decimalcharacter != ".") {
        formatted = formatted.replace(/\./, decimalcharacter);
    }
    var integer = "";
    var fraction = "";
    var strnumber = new String(formatted);
    var dotpos = decimalcharacter.length ? strnumber.indexOf(decimalcharacter) : -1;
    if (dotpos > -1)
    {
        if (dotpos) {
            integer = strnumber.substr(0, dotpos);
        }
        fraction = strnumber.substr(dotpos + 1);
    }
    else {
        integer = strnumber;
    }
    if (integer) {
        integer = String(Math.abs(integer));
    }
    while (fraction.length < decimalplaces) {
        fraction += "0";
    }
    temparray = new Array();
    while (integer.length > 3)
    {
        temparray.unshift(integer.substr(-3));
        integer = integer.substr(0, integer.length - 3);
    }
    temparray.unshift(integer);
    integer = temparray.join(thousandseparater);
    return sign + integer;
}

function bersihPemisah(ini) {
    a = ini.toString().replace(/\./g, "");
    return a;
}