<?php
namespace RzSDK\Phonetic\Character\Translation;
?>
<?php
trait PhoneticBengaliToHindi {
    private static $banglaToHindiMap = [
        // Vowels
        'অ' => 'अ', 'আ' => 'आ', 'ই' => 'इ', 'ঈ' => 'ई', 'উ' => 'उ', 'ঊ' => 'ऊ',
        'এ' => 'ए', 'ঐ' => 'ऐ', 'ও' => 'ओ', 'ঔ' => 'औ',

        // Consonants
        'ক' => 'क', 'খ' => 'ख', 'গ' => 'ग', 'ঘ' => 'घ', 'ঙ' => 'ङ',
        'চ' => 'च', 'ছ' => 'छ', 'জ' => 'ज', 'ঝ' => 'झ', 'ঞ' => 'ञ',
        'ট' => 'ट', 'ঠ' => 'ठ', 'ড' => 'ड', 'ঢ' => 'ढ', 'ণ' => 'ण',
        'ত' => 'त', 'থ' => 'थ', 'দ' => 'द', 'ধ' => 'ध', 'ন' => 'न',
        'প' => 'प', 'ফ' => 'फ', 'ব' => 'ब', 'ভ' => 'भ', 'ম' => 'म',
        'য' => 'य', 'র' => 'र', 'ল' => 'ल', 'শ' => 'श', 'ষ' => 'ष', 'স' => 'स', 'হ' => 'ह',

        // Complex Joint Letters (Ligatures)
        'ক্ষ' => 'क्ष', 'জ্ঞ' => 'ज्ञ', 'ত্র' => 'त्र', 'শ্র' => 'श्र',
        'দ্র' => 'द्र', 'প্র' => 'प्र', 'ব্র' => 'ब्र', 'স্ত্র' => 'स्त्र',
        'ক্র' => 'क्र', 'গ্র' => 'ग्र', 'ভ্র' => 'भ्र', 'ধ্র' => 'ध्र',

        // Vowel Modifiers (Matras)
        'া' => 'ा', 'ি' => 'ि', 'ী' => 'ी', 'ু' => 'ु', 'ূ' => 'ू',
        'ে' => 'े', 'ৈ' => 'ै', 'ো' => 'ो', 'ৌ' => 'ौ',

        // Anusvara & Chandrabindu
        'ং' => 'ं', 'ঁ' => 'ँ',

        // Halant (Virama)
        '্' => '्',

        // Numbers (Bangla to Hindi)
        '০' => '०', '১' => '१', '২' => '२', '৩' => '३', '৪' => '४',
        '৫' => '५', '৬' => '६', '৭' => '७', '৮' => '८', '৯' => '९',

        // Punctuation & Special Characters
        '।' => '.', '!' => '!', '?' => '?', ',' => ',', '.' => '.'
    ];

    public static function toHindi($text) {
        return strtr($text, self::$banglaToHindiMap);
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
