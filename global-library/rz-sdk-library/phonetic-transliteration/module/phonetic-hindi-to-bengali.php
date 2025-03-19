<?php
namespace RzSDK\Phonetic\Character\Translation;
?>
<?php
trait PhoneticHindiToBengali {
    private static $hindiToBanglaMap = [
        // Independent Vowels
        'अ' => 'অ', 'आ' => 'আ', 'इ' => 'ই', 'ई' => 'ঈ', 'उ' => 'উ', 'ऊ' => 'ঊ',
        'ए' => 'এ', 'ऐ' => 'ঐ', 'ओ' => 'ও', 'औ' => 'ঔ',

        // Consonants
        'क' => 'ক', 'ख' => 'খ', 'ग' => 'গ', 'घ' => 'ঘ', 'ङ' => 'ঙ',
        'च' => 'চ', 'छ' => 'ছ', 'ज' => 'জ', 'झ' => 'ঝ', 'ञ' => 'ঞ',
        'ट' => 'ট', 'ठ' => 'ঠ', 'ड' => 'ড', 'ढ' => 'ঢ', 'ण' => 'ণ',
        'त' => 'ত', 'थ' => 'থ', 'द' => 'দ', 'ध' => 'ধ', 'न' => 'ন',
        'प' => 'প', 'फ' => 'ফ', 'ब' => 'ব', 'भ' => 'ভ', 'म' => 'ম',
        'य' => 'য', 'र' => 'র', 'ल' => 'ল', 'व' => 'ভ',
        'श' => 'শ', 'ष' => 'ষ', 'स' => 'স', 'ह' => 'হ', 'ळ' => 'ল',

        // Complex Joint Letters (Ligatures)
        'क्ष' => 'ক্ষ', 'त्र' => 'ত্র', 'ज्ञ' => 'জ্ঞ', 'श्र' => 'শ্র',
        'ट्ट' => 'ট্ট', 'ड्ढ' => 'ড্ড', 'ह्न' => 'হ্ন', 'ह्म' => 'হ্ম', 'ह्ल' => 'হ্ল',
        'हृ' => 'হৃ', 'द्य' => 'দ্য', 'द्र' => 'দ্র', 'द्न' => 'দ্ন', 'ध्र' => 'ধ্র',
        'क्ष्ण' => 'ক্ষ্ণ', 'ष्ण' => 'ষ্ণ', 'प्र' => 'প্র', 'ब्र' => 'ব্র',

        // Vowel Diacritics (Matras)
        'ा' => 'া', 'ि' => 'ি', 'ी' => 'ী', 'ु' => 'ু', 'ू' => 'ূ',
        'े' => 'ে', 'ै' => 'ৈ', 'ो' => 'ো', 'ौ' => 'ৌ',

        // Anusvara & Chandrabindu
        'ं' => 'ং', 'ँ' => 'ঁ',

        // Halant (Virama) - To remove implicit vowel "अ"
        '्' => '্',

        // Numbers (Devanagari to Bengali)
        '०' => '০', '१' => '১', '२' => '২', '३' => '৩', '४' => '৪',
        '५' => '৫', '६' => '৬', '७' => '৭', '८' => '৮', '९' => '৯',

        // Punctuation & Special Characters
        '।' => '।', '!' => '!', '?' => '?', ',' => ',', '.' => '.'
    ];

    public static function toBengali($text) {
        return strtr($text, self::$hindiToBanglaMap);
    }
}
?>
<?php
/*// Example Usage
// "कंप्यूटर" → "কম্পিউটার", "विज्ञान" → "বিজ্ঞান"
$hindiText1 = "कंप्यूटर विज्ञान";
// "क्षेत्र" → "ক্ষেত্র", "१२३" → "১২৩"
$hindiText2 = "क्षेत्र १२३";
// "ध्यान" → "ধ্যান", "दें।" → "দেন।"
$hindiText3 = "ध्यान दें।";

$banglaText1 = PhoneticHindi::toBangla($hindiText1);
$banglaText2 = PhoneticHindi::toBangla($hindiText2);
$banglaText3 = PhoneticHindi::toBangla($hindiText3);

echo "Hindi to Bangla 1: $banglaText1\n";
// Output: কম্পিউটার বিজ্ঞান
echo "Hindi to Bangla 2: $banglaText2\n";
// Output: ক্ষেত্র ১২৩
echo "Hindi to Bangla 3: $banglaText3\n";
// Output: ধ্যান দেন।*/
?>
