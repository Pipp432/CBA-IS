<?php

namespace _core\helper;
use DateTime;

class thaiNum {

    public static function convertPriceToThai($price) {
        $int = str_replace(",", "", explode(".", $price)[0]);
        $float = explode(".", $price)[1];
        $out = self::convert($int)."บาท";
        if ($float=="00") { 
            $out.="ถ้วน";
        } else {
            $out.=self::convert($float, true)."สตางค์";
        }
        return $out;
    }

    private static function convert($string) {
        $string=ltrim($string, "0");
        $out="";
        $len=strlen($string);
        for ($i=$len; $i>0; $i--) {
            switch ($string[$len-$i]) {
                case "1": ($i==1 && $len>=2) ? $out .= "เอ็ด" : $out .= "หนึ่ง"; break;
                case "2": $i==2 ? $out .= "ยี่" : $out .= "สอง"; break;
                case "3": $out .= "สาม"; break;
                case "4": $out .= "สี่"; break;
                case "5": $out .= "ห้า"; break;
                case "6": $out .= "หก"; break;
                case "7": $out .= "เจ็ด"; break;
                case "8": $out .= "แปด"; break;
                case "9": $out .= "เก้า"; break;
            }
            if ($string[$len-$i]!="0") {
                switch ($i) {
                    case 7: $out .= "ล้าน"; break;
                    case 6: $out .= "แสน"; break;
                    case 5: $out .= "หมื่น"; break;
                    case 4: $out .= "พัน"; break;
                    case 3: $out .= "ร้อย"; break;
                    case 2: $out .= "สิบ"; break;
                }
            }
        }
        return $out;
    }

}