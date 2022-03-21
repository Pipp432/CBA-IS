function add_modal(id, body) {

    $("body").append('<div class="modal fade launch-modal" id="' + id + '" tabindex="-1" role="dialog" aria-hidden="true"> \
        <div class="modal-dialog" role="document"> \
            <div class="modal-content"> \
                <div class="modal-body" id="' + id + '_text">' + body + '</div> \
            </div> \
        </div> \
    </div>');

    $('#' + id).keypress(e => { if(e.keyCode == 13) $('#' + id).modal('hide'); });

}

function add_module_link(id, link, icon, text) {
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

function num_to_thai(num) {
            
    var wordBaht = ""
    var wordSatang = ""
    var num = num;
    
    if (num == 0) { 
        return "ศูนย์บาทถ้วน"
    } else {
            var money = num.toString();
            if (money.includes(".")) {
                money = money.split(".");
                var baht = money[0];
                var satang = money[1]; }
            else {
                var baht = money;
                var satang = undefined; }

        //บาท
        baht = reverse(baht);
        for (i = baht.length - 1 ; i > -1 ; i--) {
            var NoText = "";
            var NoPos = "";
        //หาเลข
        //หลักสิบ
            if (i == 1 && baht[1] == "1") {NoText = ""}
            else if (i == 1 && baht[1] == "2") {NoText = "ยี่"}
            else if (i == 0 && baht[0] == "1" && baht.length > 1 ) {NoText = "เอ็ด"}
        //หลักสิบล้าน
            else if (i == 7 && baht[7] == "1") {NoText = ""}
            else if (i == 7 && baht[7] == "2") {NoText = "ยี่"}
            else if (i == 6 && baht[6] == "1" && baht.length > 7) {NoText = "เอ็ด"}
            else {NoText = NumberToText(baht[i])}
        //หาหน่วย    
            if (baht[i] == 0 && i != 6) { NoPos = "";} 
            else { NoPos = PositionToText(i);}
            wordBaht = wordBaht + NoText + NoPos;
        }

        //สตางค์
        if (satang != undefined) {
	        satang = reverse(satang);
	        for (i = satang.length - 1 ; i > -1 ; i--) {
                var NoText = "";
                var NoPos = "";
        //หาเลข
            //หลักสิบ
	            if (i == 1 && satang[1] == "1") {NoText = ""}
                else if (i == 1 && satang[1] == "2") {NoText = "ยี่"}
	            else if (i == 0 && satang[0] == "1" && satang.length > 1 && satang[1] != "0") {NoText = "เอ็ด"}
            //กรณีทศนิยม 1 ตำแหน่ง
                else if (satang.length == 1 && satang[0] == "1") {NoText = ""}
                else if (satang.length == 1 && satang[0] == "2") {NoText = "ยี่"}
                else {NoText = NumberToText(satang[i])}
        //หาหน่วย          
                if (satang.length == 1) { NoPos = "สิบ";} //กรณีทศนิยม 1 ตำแหน่ง
                else if (satang[i] == 0) { NoPos = "";} 
                else { NoPos = PositionToText(i); }
                wordSatang = wordSatang + NoText + NoPos;
            }
        }

        //รวม
        var FinalText = "";
        if (satang === undefined) {FinalText = wordBaht + "บาทถ้วน";}
        else {FinalText = wordBaht + "บาท" + wordSatang + "สตางค์";}
        return FinalText;

    }

    //สลับตำแหน่ง
    function reverse(str){
        let reversed = "";    
        for (var i = str.length - 1; i >= 0; i--){        
            reversed += str[i];
            }    
        return reversed;
    }

    function NumberToText(num){
        var nText = "";
        if (num == "0") { nText = "";}
        else if (num == "1") { nText = "หนึ่ง";}
        else if (num == "2") { nText = "สอง";}
        else if (num == "3") { nText = "สาม";}
        else if (num == "4") { nText = "สี่";}
        else if (num == "5") { nText = "ห้า";}
        else if (num == "6") { nText = "หก";}
        else if (num == "7") { nText = "เจ็ด";}
        else if (num == "8") { nText = "แปด";}
        else if (num == "9") { nText = "เก้า";}
        return nText;
    }

    function PositionToText(pos){
        var nPos = "";
        if (pos == 0) { nPos  = "";}
        else if (pos == 1) { nPos = "สิบ";}
        else if (pos == 2) { nPos = "ร้อย";}
        else if (pos == 3) { nPos = "พัน";}
        else if (pos == 4) { nPos = "หมื่น";}
        else if (pos == 5) { nPos = "แสน";}
        else if (pos == 6) { nPos = "ล้าน";}
        else if (pos == 7) { nPos = "สิบ";}
        else if (pos == 8) { nPos = "ร้อย";}
        else if (pos == 9) { nPos = "พัน";}
        else if (pos == 10) { nPos = "หมื่น";}
        else if (pos == 11) { nPos = "แสน";}
        else if (pos == 12) { nPos = "ล้าน";}
        return nPos;
    } 

}