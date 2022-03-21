function NumToThai(num) {
            
    var wordBaht = ""
    var wordSatang = ""
    var num = num;
    
    if (num == 0) { 
        return "à¸¨à¸¹à¸™à¸¢à¹Œà¸šà¸²à¸—à¸–à¹‰à¸§à¸™"
    } else {
            var money = num.toString();
            if (money.includes(".")) {
                money = money.split(".");
                var baht = money[0];
                var satang = money[1]; }
            else {
                var baht = money;
                var satang = undefined; }

        //à¸šà¸²à¸—
        baht = reverse(baht);
        for (i = baht.length - 1 ; i > -1 ; i--) {
            var NoText = "";
            var NoPos = "";
        //à¸«à¸²à¹€à¸¥à¸‚
        //à¸«à¸¥à¸±à¸à¸ªà¸´à¸š
            if (i == 1 && baht[1] == "1") {NoText = ""}
            else if (i == 1 && baht[1] == "2") {NoText = "à¸¢à¸µà¹ˆ"}
            else if (i == 0 && baht[0] == "1" && baht.length > 1 ) {NoText = "à¹€à¸­à¹‡à¸”"}
        //à¸«à¸¥à¸±à¸à¸ªà¸´à¸šà¸¥à¹‰à¸²à¸™
            else if (i == 7 && baht[7] == "1") {NoText = ""}
            else if (i == 7 && baht[7] == "2") {NoText = "à¸¢à¸µà¹ˆ"}
            else if (i == 6 && baht[6] == "1" && baht.length > 7) {NoText = "à¹€à¸­à¹‡à¸”"}
            else {NoText = NumberToText(baht[i])}
        //à¸«à¸²à¸«à¸™à¹ˆà¸§à¸¢    
            if (baht[i] == 0 && i != 6) { NoPos = "";} 
            else { NoPos = PositionToText(i);}
            wordBaht = wordBaht + NoText + NoPos;
        }

        //à¸ªà¸•à¸²à¸‡à¸„à¹Œ
        if (satang != undefined) {
	        satang = reverse(satang);
	        for (i = satang.length - 1 ; i > -1 ; i--) {
                var NoText = "";
                var NoPos = "";
        //à¸«à¸²à¹€à¸¥à¸‚
            //à¸«à¸¥à¸±à¸à¸ªà¸´à¸š
	            if (i == 1 && satang[1] == "1") {NoText = ""}
                else if (i == 1 && satang[1] == "2") {NoText = "à¸¢à¸µà¹ˆ"}
	            else if (i == 0 && satang[0] == "1" && satang.length > 1 && satang[1] != "0") {NoText = "à¹€à¸­à¹‡à¸”"}
            //à¸à¸£à¸“à¸µà¸—à¸¨à¸™à¸´à¸¢à¸¡ 1 à¸•à¸³à¹à¸«à¸™à¹ˆà¸‡
                else if (satang.length == 1 && satang[0] == "1") {NoText = ""}
                else if (satang.length == 1 && satang[0] == "2") {NoText = "à¸¢à¸µà¹ˆ"}
                else {NoText = NumberToText(satang[i])}
        //à¸«à¸²à¸«à¸™à¹ˆà¸§à¸¢          
                if (satang.length == 1) { NoPos = "à¸ªà¸´à¸š";} //à¸à¸£à¸“à¸µà¸—à¸¨à¸™à¸´à¸¢à¸¡ 1 à¸•à¸³à¹à¸«à¸™à¹ˆà¸‡
                else if (satang[i] == 0) { NoPos = "";} 
                else { NoPos = PositionToText(i); }
                wordSatang = wordSatang + NoText + NoPos;
            }
        }

        //à¸£à¸§à¸¡
        var FinalText = "";
        if (satang === undefined) {FinalText = wordBaht + "à¸šà¸²à¸—à¸–à¹‰à¸§à¸™";}
        else {FinalText = wordBaht + "à¸šà¸²à¸—" + wordSatang + "à¸ªà¸•à¸²à¸‡à¸„à¹Œ";}
        return FinalText;

    }

    //à¸ªà¸¥à¸±à¸šà¸•à¸³à¹à¸«à¸™à¹ˆà¸‡
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
        else if (num == "1") { nText = "à¸«à¸™à¸¶à¹ˆà¸‡";}
        else if (num == "2") { nText = "à¸ªà¸­à¸‡";}
        else if (num == "3") { nText = "à¸ªà¸²à¸¡";}
        else if (num == "4") { nText = "à¸ªà¸µà¹ˆ";}
        else if (num == "5") { nText = "à¸«à¹‰à¸²";}
        else if (num == "6") { nText = "à¸«à¸";}
        else if (num == "7") { nText = "à¹€à¸ˆà¹‡à¸”";}
        else if (num == "8") { nText = "à¹à¸›à¸”";}
        else if (num == "9") { nText = "à¹€à¸à¹‰à¸²";}
        return nText;
    }

    function PositionToText(pos){
        var nPos = "";
        if (pos == 0) { nPos  = "";}
        else if (pos == 1) { nPos = "à¸ªà¸´à¸š";}
        else if (pos == 2) { nPos = "à¸£à¹‰à¸­à¸¢";}
        else if (pos == 3) { nPos = "à¸žà¸±à¸™";}
        else if (pos == 4) { nPos = "à¸«à¸¡à¸·à¹ˆà¸™";}
        else if (pos == 5) { nPos = "à¹à¸ªà¸™";}
        else if (pos == 6) { nPos = "à¸¥à¹‰à¸²à¸™";}
        else if (pos == 7) { nPos = "à¸ªà¸´à¸š";}
        else if (pos == 8) { nPos = "à¸£à¹‰à¸­à¸¢";}
        else if (pos == 9) { nPos = "à¸žà¸±à¸™";}
        else if (pos == 10) { nPos = "à¸«à¸¡à¸·à¹ˆà¸™";}
        else if (pos == 11) { nPos = "à¹à¸ªà¸™";}
        else if (pos == 12) { nPos = "à¸¥à¹‰à¸²à¸™";}
        return nPos;
    } 

}