<?php
namespace RzSDK\Phonetic\Character\Translation;
?>
<?php
trait PhoneticHindiToEnglish {
    // Hindi to English phonetic character map
    private static $hindiToEnglishMap = [
        // Vowels
        'अ' => 'a', 'आ' => 'aa', 'इ' => 'i', 'ई' => 'ee', 'उ' => 'u', 'ऊ' => 'oo',
        'ए' => 'e', 'ऐ' => 'ai', 'ओ' => 'o', 'औ' => 'au',

        // Consonants
        'क' => 'k', 'ख' => 'kh', 'ग' => 'g', 'घ' => 'gh', 'ङ' => 'ng',
        'च' => 'ch', 'छ' => 'chh', 'ज' => 'j', 'झ' => 'jh', 'ञ' => 'ny',
        'ट' => 't', 'ठ' => 'th', 'ड' => 'd', 'ढ' => 'dh', 'ण' => 'n',
        'त' => 't', 'थ' => 'th', 'द' => 'd', 'ध' => 'dh', 'न' => 'n',
        'प' => 'p', 'फ' => 'ph', 'ब' => 'b', 'भ' => 'bh', 'म' => 'm',
        'य' => 'y', 'र' => 'r', 'ल' => 'l', 'व' => 'v',
        'श' => 'sh', 'ष' => 'sh', 'स' => 's', 'ह' => 'h', 'ळ' => 'l',

        // Complex Joint Letters (Ligatures)
        'क्ष' => 'ksh', 'त्र' => 'tra', 'ज्ञ' => 'gya', 'श्र' => 'shra',
        'ट्ट' => 'tta', 'ड्ढ' => 'dda', 'ह्न' => 'hna', 'ह्म' => 'hma', 'ह्ल' => 'hla',
        'हृ' => 'hri', 'द्य' => 'dya', 'द्र' => 'dra', 'द्न' => 'dna', 'ध्र' => 'dhra',
        'क्ष्ण' => 'kshna', 'ष्ण' => 'shna', 'प्र' => 'pra', 'ब्र' => 'bra',

        // Matras (Vowel Modifiers)
        'ा' => 'a', 'ि' => 'i', 'ी' => 'ee', 'ु' => 'u', 'ू' => 'oo',
        'े' => 'e', 'ै' => 'ai', 'ो' => 'o', 'ौ' => 'au',

        // Anusvara & Chandrabindu
        'ं' => 'm', 'ँ' => 'n',

        // Halant (Virama) - Removes implicit "a" sound
        '्' => '',

        // Numbers (Devanagari to Latin)
        '०' => '0', '१' => '1', '२' => '2', '३' => '3', '४' => '4',
        '५' => '5', '६' => '6', '७' => '7', '८' => '8', '९' => '9',

        // Punctuation & Special Characters
        '।' => '.', '!' => '!', '?' => '?', ',' => ',', '.' => '.'
    ];

    public static function toEnglish($text) {
        return strtr($text, self::$hindiToEnglishMap);
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