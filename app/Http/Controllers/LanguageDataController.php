<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserProgress;

class LanguageDataController extends Controller
{
    private $cacheTime = 86400; // 24 hours

    private $hausaDictionary = [
        'sannu' => [
                    'meaning' => 'Hello / Sorry (used as greeting or sympathy)',
                    'category' => 'Greeting / Sympathy'
                ],
        'yaro' => ['meaning' => 'Boy', 'category' => 'Noun'],
        'yana' => ['meaning' => 'He is (present continuous)', 'category' => 'Verb'],
        'karatu' => ['meaning' => 'Reading', 'category' => 'Verb'],
        'littafi' => ['meaning' => 'Book', 'category' => 'Noun'],
        'malam' => ['meaning' => 'Teacher', 'category' => 'Noun'],
        'koyarwa' => ['meaning' => 'Teaching', 'category' => 'Verb'],
        'makaranta' => ['meaning' => 'School', 'category' => 'Noun'],
        'tana' => ['meaning' => 'She is (present continuous)', 'category' => 'Verb'],
        'cin' => ['meaning' => 'Eating', 'category' => 'Verb'],
        'abinci' => ['meaning' => 'Food', 'category' => 'Noun'],
        'gida' => ['meaning' => 'Home/House', 'category' => 'Noun'],
        'yaya' => ['meaning' => 'How', 'category' => 'Adverb'],
        'aikinka' => ['meaning' => 'Your work', 'category' => 'Noun'],
        'ina' => ['meaning' => 'I (subject)', 'category' => 'Pronoun'],
        'son' => ['meaning' => 'Want/Love', 'category' => 'Verb'],
        'zuwa' => ['meaning' => 'To/Going to', 'category' => 'Preposition'],
        'kasuwa' => ['meaning' => 'Market', 'category' => 'Noun'],
        'mutum' => ['meaning' => 'Person', 'category' => 'Noun'],
        'kyau' => ['meaning' => 'Good/Beautiful', 'category' => 'Adjective'],
        'babu' => ['meaning' => 'There is no', 'category' => 'Verb'],
        'da' => ['meaning' => 'With/And', 'category' => 'Preposition'],
        'ce' => ['meaning' => 'Is (feminine)', 'category' => 'Verb'],
        'ne' => ['meaning' => 'Is (masculine)', 'category' => 'Verb'],
        'uwa' => ['meaning' => 'Mother', 'category' => 'Noun'],
        'uba' => ['meaning' => 'Father', 'category' => 'Noun'],
        'yayar' => ['meaning' => 'Older sister', 'category' => 'Noun'],
        'kani' => ['meaning' => 'Younger brother', 'category' => 'Noun'],
        'dada' => ['meaning' => 'Aunt', 'category' => 'Noun'],
        'kawu' => ['meaning' => 'Uncle', 'category' => 'Noun'],
        'yara' => ['meaning' => 'Children', 'category' => 'Noun'],
        'malama' => ['meaning' => 'Female teacher', 'category' => 'Noun'],
        'dalibi' => ['meaning' => 'Male student', 'category' => 'Noun'],
        'daliba' => ['meaning' => 'Female student', 'category' => 'Noun'],
        'aji' => ['meaning' => 'Class (room)', 'category' => 'Noun'],
        'almajiri' => ['meaning' => 'Student of Islamic school', 'category' => 'Noun'],
        'takalmi' => ['meaning' => 'Shoe', 'category' => 'Noun'],
        'tufafi' => ['meaning' => 'Clothes', 'category' => 'Noun'],
        'hula' => ['meaning' => 'Cap', 'category' => 'Noun'],
        'jaka' => ['meaning' => 'Bag', 'category' => 'Noun'],
        'kasa' => ['meaning' => 'Country/Earth/floor', 'category' => 'Noun'],
        'gari' => ['meaning' => 'Town/City', 'category' => 'Noun'],
        'kauye' => ['meaning' => 'Village', 'category' => 'Noun'],
        'kogi' => ['meaning' => 'River', 'category' => 'Noun'],
        'daji' => ['meaning' => 'Forest/Bush', 'category' => 'Noun'],
        'teku' => ['meaning' => 'Sea/Ocean', 'category' => 'Noun'],
        'rana' => ['meaning' => 'Sun/Day', 'category' => 'Noun'],
        'dare' => ['meaning' => 'Night', 'category' => 'Noun'],
        'watanni' => ['meaning' => 'Months', 'category' => 'Noun'],
        'makonni' => ['meaning' => 'Weeks', 'category' => 'Noun'],
        'shekara' => ['meaning' => 'Year', 'category' => 'Noun'],
        'idanu' => ['meaning' => 'Eyes', 'category' => 'Noun'],
        'kunne' => ['meaning' => 'Ear', 'category' => 'Noun'],
        'hanci' => ['meaning' => 'Nose', 'category' => 'Noun'],
        'baki' => ['meaning' => 'Mouth', 'category' => 'Noun'],
        'kai' => ['meaning' => 'head', 'category' => 'Noun'],
        'hannu' => ['meaning' => 'hand', 'category' => 'Noun'],
        'kafa' => ['meaning' => 'leg', 'category' => 'Noun'],
        'yasa' => ['meaning' => 'finger', 'category' => 'Noun'],
        'farshe' => ['meaning' => 'nail', 'category' => 'Noun'],
        'ciki' => ['meaning' => 'stomach', 'category' => 'Noun'],
        'cibiya' => ['meaning' => 'umbilical', 'category' => 'Noun'],
        'idon rafi' => ['meaning' => 'Riverbank', 'category' => 'Noun'],
        'akwai' => ['meaning' => 'There is/Exists', 'category' => 'Noun'],

        // Greetings
        'salamu alaikum' => ['meaning' => 'Peace be upon you', 'category' => 'Greeting'],
        'ina kwana' => ['meaning' => 'Good morning', 'category' => 'Greeting'],
        'ina wuni' => ['meaning' => 'Good afternoon', 'category' => 'Greeting'],
        'barka da yini' => ['meaning' => 'Good day', 'category' => 'Greeting'],
        'barka da rana' => ['meaning' => 'Good afternoon', 'category' => 'Greeting'],
        'barka da dare' => ['meaning' => 'Good night', 'category' => 'Greeting'],
        'nagode' => ['meaning' => 'Thank you', 'category' => 'Expression'],
        'don Allah' => ['meaning' => 'Please/For God’s sake', 'category' => 'Expression'],
        'hakuri' => ['meaning' => 'Sorry', 'category' => 'Expression'],
        'lafiya' => ['meaning' => 'Fine/Health', 'category' => 'Noun'],
        'ina lafiya' => ['meaning' => 'I am fine', 'category' => 'Question'],
        'lafiya lau' => ['meaning' => 'Very fine', 'category' => 'Expression'],

        'ruwa' => ['meaning' => 'Water', 'category' => 'Noun'],
        'toka' => ['meaning' => 'Fire/Ash', 'category' => 'Noun'],
        'mota' => ['meaning' => 'Car', 'category' => 'Noun'],
        'keke' => ['meaning' => 'Bicycle', 'category' => 'Noun'],
        'allo' => ['meaning' => 'Slate/Board', 'category' => 'Noun'],
        'talabijin' => ['meaning' => 'Television', 'category' => 'Noun'],
        'tebur' => ['meaning' => 'Table/Desk', 'category' => 'Noun'],
        'kujerar' => ['meaning' => 'Chair', 'category' => 'Noun'],
        'akwati' => ['meaning' => 'Box', 'category' => 'Noun'],
        'duniya' => ['meaning' => 'World/Life', 'category' => 'Noun'],
        'kofa' => ['meaning' => 'Door', 'category' => 'Noun'],
        'tagar' => ['meaning' => 'Window', 'category' => 'Noun'],
        'idanun' => ['meaning' => 'Eyes', 'category' => 'Noun'],
        'hannun' => ['meaning' => 'Hand', 'category' => 'Noun'],
        'kai' => ['meaning' => 'Head/You (masc.)', 'category' => 'Noun'],
        'zuciya' => ['meaning' => 'Heart', 'category' => 'Noun'],
        'hanya' => ['meaning' => 'Road', 'category' => 'Noun'],
        'tudu' => ['meaning' => 'Hill', 'category' => 'Noun'],
        'kifi' => ['meaning' => 'Fish', 'category' => 'Noun'],
        'doya' => ['meaning' => 'Yam', 'category' => 'Noun'],
        'agogo' => ['meaning' => 'Clock', 'category' => 'Noun'],
        'gashi' => ['meaning' => 'Hair', 'category' => 'Noun'],
        'hanya' => ['meaning' => 'Road/Way', 'category' => 'Noun'],
        'uwargida' => ['meaning' => 'Housewife/Madam', 'category' => 'Noun'],
        'sana\'a' => ['meaning' => 'Job/Profession', 'category' => 'Noun'],

        'sha' => ['meaning' => 'Drink', 'category' => 'Verb'],
        'biyan' => ['meaning' => 'Pay', 'category' => 'Verb'],
        'tashi' => ['meaning' => 'Wake/Stand up', 'category' => 'Verb'],
        'zauna' => ['meaning' => 'Sit', 'category' => 'Verb'],
        'gudu' => ['meaning' => 'Run', 'category' => 'Verb'],
        'tafiyah' => ['meaning' => 'Journey/Travel', 'category' => 'Verb'],
        'gyara' => ['meaning' => 'Fix/Repair', 'category' => 'Verb'],
        'rubutu' => ['meaning' => 'Writing', 'category' => 'Verb'],
        'fada' => ['meaning' => 'Say/Speak', 'category' => 'Verb'],
        'ji' => ['meaning' => 'Hear/Feel', 'category' => 'Verb'],
        'gani' => ['meaning' => 'See', 'category' => 'Verb'],
        'kashe' => ['meaning' => 'Kill/Turn off', 'category' => 'Verb'],
        'amshi' => ['meaning' => 'Answer/Receive', 'category' => 'Verb'],
        'kama' => ['meaning' => 'Catch/Arrest', 'category' => 'Verb'],
        'tuno' => ['meaning' => 'Remember', 'category' => 'Verb'],
        'dawowa' => ['meaning' => 'Return', 'category' => 'Verb'],
        'kira' => ['meaning' => 'Call', 'category' => 'Verb'],
        'aiki' => ['meaning' => 'Work', 'category' => 'Verb'],
        'rabo' => ['meaning' => 'Share/Divide', 'category' => 'Verb'],

        'baki' => ['meaning' => 'Black', 'category' => 'Adjective'],
        'fari' => ['meaning' => 'White', 'category' => 'Adjective'],
        'tsawo' => ['meaning' => 'Tall/Long', 'category' => 'Adjective'],
        'gajere' => ['meaning' => 'Short', 'category' => 'Adjective'],
        'sauri' => ['meaning' => 'Fast/Quick', 'category' => 'Adjective'],
        'jinkiri' => ['meaning' => 'Slow/Delay', 'category' => 'Adjective'],
        'dadi' => ['meaning' => 'Sweet/Delicious', 'category' => 'Adjective'],
        'zafi' => ['meaning' => 'Hot', 'category' => 'Adjective'],
        'sanyi' => ['meaning' => 'Cold', 'category' => 'Adjective'],
        'tsoho' => ['meaning' => 'Old (person)', 'category' => 'Adjective'],
        'sabon' => ['meaning' => 'New', 'category' => 'Adjective'],
        'mummuna' => ['meaning' => 'Ugly/Bad', 'category' => 'Adjective'],
        'arha' => ['meaning' => 'Cheap', 'category' => 'Adjective'],
        'tsada' => ['meaning' => 'Expensive', 'category' => 'Adjective'],

        'ni' => ['meaning' => 'Me/I', 'category' => 'Pronoun'],
        'kai' => ['meaning' => 'You (male)', 'category' => 'Pronoun'],
        'ke' => ['meaning' => 'You (female)', 'category' => 'Pronoun'],
        'shi' => ['meaning' => 'He', 'category' => 'Pronoun'],
        'ita' => ['meaning' => 'She', 'category' => 'Pronoun'],
        'mu' => ['meaning' => 'We', 'category' => 'Pronoun'],
        'ku' => ['meaning' => 'You all', 'category' => 'Pronoun'],
        'su' => ['meaning' => 'They', 'category' => 'Pronoun'],

        // Numbers
        'daya' => ['meaning' => 'One', 'category' => 'Number'],
        'biyu' => ['meaning' => 'Two', 'category' => 'Number'],
        'uku' => ['meaning' => 'Three', 'category' => 'Number'],
        'hudu' => ['meaning' => 'Four', 'category' => 'Number'],
        'biyar' => ['meaning' => 'Five', 'category' => 'Number'],
        'shida' => ['meaning' => 'Six', 'category' => 'Number'],
        'bakwai' => ['meaning' => 'Seven', 'category' => 'Number'],
        'takwas' => ['meaning' => 'Eight', 'category' => 'Number'],
        'tara' => ['meaning' => 'Nine', 'category' => 'Number'],
        'goma' => ['meaning' => 'Ten', 'category' => 'Number'],
        'goma sha daya' => ['meaning' => 'Eleven', 'category' => 'Number'],
        'sha biyu' => ['meaning' => 'Twelve', 'category' => 'Number'],
        'sha uku' => ['meaning' => 'Thirteen', 'category' => 'Number'],
        'sha hudu' => ['meaning' => 'Fourteen', 'category' => 'Number'],
        'sha biyar' => ['meaning' => 'Fifteen', 'category' => 'Number'],
        'sha shida' => ['meaning' => 'Sixteen', 'category' => 'Number'],
        'sha bakwai' => ['meaning' => 'Seventeen', 'category' => 'Number'],
        'sha takwas' => ['meaning' => 'Eighteen', 'category' => 'Number'],
        'sha tara' => ['meaning' => 'Nineteen', 'category' => 'Number'],
        'ashirin da daya' => ['meaning' => 'Twenty-one', 'category' => 'Number'],
        'ashirin da biyu' => ['meaning' => 'Twenty-two', 'category' => 'Number'],
        'ashirin da uku' => ['meaning' => 'Twenty-three', 'category' => 'Number'],
        'ashirin da hudu' => ['meaning' => 'Twenty-four', 'category' => 'Number'],
        'ashirin da biyar' => ['meaning' => 'Twenty-five', 'category' => 'Number'],
        'ashirin da shida' => ['meaning' => 'Twenty-six', 'category' => 'Number'],
        'ashirin da bakwai' => ['meaning' => 'Twenty-seven', 'category' => 'Number'],
        'ashirin da takwas' => ['meaning' => 'Twenty-eight', 'category' => 'Number'],
        'ashirin da tara' => ['meaning' => 'Twenty-nine', 'category' => 'Number'],
        'talatin da daya' => ['meaning' => 'Thirty-one', 'category' => 'Number'],
        'talatin da biyu' => ['meaning' => 'Thirty-two', 'category' => 'Number'],
        'talatin da uku' => ['meaning' => 'Thirty-three', 'category' => 'Number'],
        'talatin da hudu' => ['meaning' => 'Thirty-four', 'category' => 'Number'],
        'talatin da biyar' => ['meaning' => 'Thirty-five', 'category' => 'Number'],
        'talatin da shida' => ['meaning' => 'Thirty-six', 'category' => 'Number'],
        'talatin da bakwai' => ['meaning' => 'Thirty-seven', 'category' => 'Number'],
        'talatin da takwas' => ['meaning' => 'Thirty-eight', 'category' => 'Number'],
        'talatin da tara' => ['meaning' => 'Thirty-nine', 'category' => 'Number'],
        'arba\'in da daya' => ['meaning' => 'Forty-one', 'category' => 'Number'],
        'arba\'in da biyu' => ['meaning' => 'Forty-two', 'category' => 'Number'],
        'arba\'in da uku' => ['meaning' => 'Forty-three', 'category' => 'Number'],
        'arba\'in da hudu' => ['meaning' => 'Forty-four', 'category' => 'Number'],
        'arba\'in da biyar' => ['meaning' => 'Forty-five', 'category' => 'Number'],
        'arba\'in da shida' => ['meaning' => 'Forty-six', 'category' => 'Number'],
        'arba\'in da bakwai' => ['meaning' => 'Forty-seven', 'category' => 'Number'],
        'arba\'in da takwas' => ['meaning' => 'Forty-eight', 'category' => 'Number'],
        'arba\'in da tara' => ['meaning' => 'Forty-nine', 'category' => 'Number'],
        'hamsin' => ['meaning' => 'Fifty', 'category' => 'Number'],
        'hamsin da daya' => ['meaning' => 'Fifty-one', 'category' => 'Number'],
        'hamsin da biyu' => ['meaning' => 'Fifty-two', 'category' => 'Number'],
        'hamsin da uku' => ['meaning' => 'Fifty-three', 'category' => 'Number'],
        'hamsin da hudu' => ['meaning' => 'Fifty-four', 'category' => 'Number'],
        'hamsin da biyar' => ['meaning' => 'Fifty-five', 'category' => 'Number'],
        'hamsin da shida' => ['meaning' => 'Fifty-six', 'category' => 'Number'],
        'hamsin da bakwai' => ['meaning' => 'Fifty-seven', 'category' => 'Number'],
        'hamsin da takwas' => ['meaning' => 'Fifty-eight', 'category' => 'Number'],
        'hamsin da tara' => ['meaning' => 'Fifty-nine', 'category' => 'Number'],
        'sittin' => ['meaning' => 'Sixty', 'category' => 'Number'],
        'sittin da daya' => ['meaning' => 'Sixty-one', 'category' => 'Number'],
        'sittin da biyu' => ['meaning' => 'Sixty-two', 'category' => 'Number'],
        'sittin da uku' => ['meaning' => 'Sixty-three', 'category' => 'Number'],
        'sittin da hudu' => ['meaning' => 'Sixty-four', 'category' => 'Number'],
        'sittin da biyar' => ['meaning' => 'Sixty-six', 'category' => 'Number'],
        'sittin da shida' => ['meaning' => 'Sixty-six', 'category' => 'Number'],
        'sittin da bakwai' => ['meaning' => 'Sixty-seven', 'category' => 'Number'],
        'sittin da takwas' => ['meaning' => 'Sixty-eight', 'category' => 'Number'],
        'sittin da tara' => ['meaning' => 'Sixty-nine', 'category' => 'Number'],
        'saba\'in da daya' => ['meaning' => 'Seventy-one', 'category' => 'Number'],
        'tamanin da daya' => ['meaning' => 'Eighty-one', 'category' => 'Number'],
        'casa\'in da tara' => ['meaning' => 'Ninety-nine', 'category' => 'Number'],
        'dari' => ['meaning' => 'One hundred', 'category' => 'Number'],
        'dari da daya' => ['meaning' => 'One hundred and one', 'category' => 'Number'],
        'dari da biyu' => ['meaning' => 'One hundred and two', 'category' => 'Number'],
        'dari da goma' => ['meaning' => 'One hundred and ten', 'category' => 'Number'],
        'dari da sha biyar' => ['meaning' => 'One hundred and fifteen', 'category' => 'Number'],
        'dari da ashirin' => ['meaning' => 'One hundred and twenty', 'category' => 'Number'],
        'dari da ashirin da uku' => ['meaning' => 'One hundred and twenty-three', 'category' => 'Number'],
        'dari da hamsin' => ['meaning' => 'One hundred and fifty', 'category' => 'Number'],
        'dari da casa\'in da tara' => ['meaning' => 'One hundred and ninety-nine', 'category' => 'Number'],
        'dari biyu da daya' => ['meaning' => 'Two hundred and one', 'category' => 'Number'],
        'dari biyu da goma sha biyu' => ['meaning' => 'Two hundred and twelve', 'category' => 'Number'],
        'dari biyu da talatin' => ['meaning' => 'Two hundred and thirty', 'category' => 'Number'],
        'dari biyu da arba\'in da biyar' => ['meaning' => 'Two hundred and forty-five', 'category' => 'Number'],
        'dari biyu da casa\'in da tara' => ['meaning' => 'Two hundred and ninety-nine', 'category' => 'Number'],
        'dari uku da goma' => ['meaning' => 'Three hundred and ten', 'category' => 'Number'],
        'dari hudu da hamsin' => ['meaning' => 'Four hundred and fifty', 'category' => 'Number'],
        'dari biyar da sittin da shida' => ['meaning' => 'Five hundred and sixty-six', 'category' => 'Number'],
        'dari shida da saba\'in' => ['meaning' => 'Six hundred and seventy', 'category' => 'Number'],
        'dari bakwai da tamanin da biyu' => ['meaning' => 'Seven hundred and eighty-two', 'category' => 'Number'],
        'dari takwas da casa\'in' => ['meaning' => 'Eight hundred and ninety', 'category' => 'Number'],
        'dari tara da casa\'in da tara' => ['meaning' => 'Nine hundred and ninety-nine', 'category' => 'Number'],
        'dubu daya' => ['meaning' => 'One thousand', 'category' => 'Number'],
        'dubu biyu' => ['meaning' => 'two thousand', 'category' => 'Number'],
        'dubu uku' => ['meaning' => 'three thousand', 'category' => 'Number'],
        'dubu hudu' => ['meaning' => 'four thousand', 'category' => 'Number'],
        'dubu biyar' => ['meaning' => 'five thousand', 'category' => 'Number'],
        'dubu shida' => ['meaning' => 'six thousand', 'category' => 'Number'],
        'dubu bakwai' => ['meaning' => 'seven thousand', 'category' => 'Number'],
        'dubu takwas' => ['meaning' => 'eight thousand', 'category' => 'Number'],
        'dubu tara' => ['meaning' => 'nine thousand', 'category' => 'Number'],
        'dubu goma' => ['meaning' => 'ten thousand', 'category' => 'Number'],
        'dubu ashirin' => ['meaning' => 'twenty thousand', 'category' => 'Number'],
        'dubu talatin' => ['meaning' => 'thirty thousand', 'category' => 'Number'],
        'dubu arba\'in' => ['meaning' => 'forty thousand', 'category' => 'Number'],
        'dubu hamsin' => ['meaning' => 'fifty thousand', 'category' => 'Number'],
        'dubu sittin' => ['meaning' => 'sixty thousand', 'category' => 'Number'],
        'dubu saba\'in' => ['meaning' => 'seventy thousand', 'category' => 'Number'],
        'dubu tamanin' => ['meaning' => 'eighty thousand', 'category' => 'Number'],
        'dubu cas\'in' => ['meaning' => 'ninety thousand', 'category' => 'Number'],
        'dubu dari' => ['meaning' => 'hundred thousand', 'category' => 'Number'],
        'dubu dari biyu' => ['meaning' => 'two hundred thousand', 'category' => 'Number'],
        'dubu dari uku' => ['meaning' => 'three hundred thousand', 'category' => 'Number'],
        'dubu dari hudu' => ['meaning' => 'four hundred thousand', 'category' => 'Number'],
        'dubu dari biyar' => ['meaning' => 'five hundred thousand', 'category' => 'Number'],
        'dubu dari shida' => ['meaning' => 'six hundred thousand', 'category' => 'Number'],
        'dubu dari bakwai' => ['meaning' => 'seven hundred thousand', 'category' => 'Number'],
        'dubu dari takwas' => ['meaning' => 'eight hundred thousand', 'category' => 'Number'],
        'dubu dari tara' => ['meaning' => 'nine hundred thousand', 'category' => 'Number'],
        'milliyan daya' => ['meaning' => 'one million', 'category' => 'Number'],


        'ka mu' => ['meaning' => 'Pap', 'category' => 'Noun'],
        'tuwon shinkafa' => ['meaning' => 'Rice swallow', 'category' => 'Noun'],
        'tuwon masara' => ['meaning' => 'Corn swallow', 'category' => 'Noun'],
        'miyan kuka' => ['meaning' => 'Baobab soup', 'category' => 'Noun'],
        'miyan taushe' => ['meaning' => 'Vegetable soup', 'category' => 'Noun'],
        'kunun aya' => ['meaning' => 'Tiger nut drink', 'category' => 'Noun'],
        'waina' => ['meaning' => 'Rice cake/masa', 'category' => 'Noun'],
        'danwake' => ['meaning' => 'Bean dumpling', 'category' => 'Noun'],
        'kosai' => ['meaning' => 'Bean cake/akara', 'category' => 'Noun'],
        'suya' => ['meaning' => 'Roasted meat', 'category' => 'Noun'],
        'nama' => ['meaning' => 'Meat', 'category' => 'Noun'],
        'doya' => ['meaning' => 'Yam', 'category' => 'Noun'],
        'shinkafa' => ['meaning' => 'Rice', 'category' => 'Noun'],
        'ayaba' => ['meaning' => 'Banana', 'category' => 'Noun'],
        'lemo' => ['meaning' => 'Drink/Soda', 'category' => 'Noun'],
        'masara' => ['meaning' => 'corn', 'category' => 'Noun'],
        'dankali' => ['meaning' => 'potato', 'category' => 'Noun'],

        // Animals
        'raƙumi' => ['meaning' => 'Camel', 'category' => 'Noun'],
        'doki' => ['meaning' => 'Horse', 'category' => 'Noun'],
        'saniya' => ['meaning' => 'Cow', 'category' => 'Noun'],
        'tunkiya' => ['meaning' => 'Sheep', 'category' => 'Noun'],
        'akuya' => ['meaning' => 'Goat', 'category' => 'Noun'],
        'kaza' => ['meaning' => 'Chicken', 'category' => 'Noun'],
        'kare' => ['meaning' => 'Dog', 'category' => 'Noun'],
        'mage' => ['meaning' => 'Cat', 'category' => 'Noun'],
        'aku' => ['meaning' => 'Parrot', 'category' => 'Noun'],
        'zaki' => ['meaning' => 'Lion', 'category' => 'Noun'],
        'damisa' => ['meaning' => 'Leopard', 'category' => 'Noun'],
        'zomo' => ['meaning' => 'rabbit', 'category' => 'Noun'],
        'tattabara' => ['meaning' => 'pigeon', 'category' => 'Noun'],
        'jaki' => ['meaning' => 'Donkey', 'category' => 'Noun'],
        'alade' => ['meaning' => 'Pig', 'category' => 'Noun'],
        'barewa' => ['meaning' => 'Antelope', 'category' => 'Noun'],
        'doki mai ja' => ['meaning' => 'Mare', 'category' => 'Noun'],
        'shaƙo' => ['meaning' => 'Bull', 'category' => 'Noun'],
        'maraki' => ['meaning' => 'Calf', 'category' => 'Noun'],
        'ƙanƙara' => ['meaning' => 'Kid (young goat)', 'category' => 'Noun'],
        'giwa' => ['meaning' => 'Elephant', 'category' => 'Noun'],
        'barewa' => ['meaning' => 'Deer / Antelope', 'category' => 'Noun'],
        'kurege' => ['meaning' => 'Hyena', 'category' => 'Noun'],
        'kura' => ['meaning' => 'Monkey', 'category' => 'Noun'],
        'kifi' => ['meaning' => 'Fish', 'category' => 'Noun'],
        'kada' => ['meaning' => 'Crocodile', 'category' => 'Noun'],
        'maciji' => ['meaning' => 'Snake', 'category' => 'Noun'],
        'damisa' => ['meaning' => 'Tiger', 'category' => 'Noun'],
        'agwagwa' => ['meaning' => 'Duck', 'category' => 'Noun'],
        'kurciya' => ['meaning' => 'Dove', 'category' => 'Noun'],
        'gaggafa' => ['meaning' => 'Eagle', 'category' => 'Noun'],
        'tsuntsu' => ['meaning' => 'Bird', 'category' => 'Noun'],
        'kunkuru' => ['meaning' => 'Tortoise', 'category' => 'Noun'],
        'kuda' => ['meaning' => 'Fly', 'category' => 'Noun'],
        'sauro' => ['meaning' => 'Mosquito', 'category' => 'Noun'],
        'tururuwa' => ['meaning' => 'Ant', 'category' => 'Noun'],
        'ƙwaro' => ['meaning' => 'Beetle', 'category' => 'Noun'],
        'gizo-gizo' => ['meaning' => 'Spider', 'category' => 'Noun'],
        'kunkuru' => ['meaning' => 'Tortoise', 'category' => 'Noun'],
        'kada' => ['meaning' => 'Crocodile', 'category' => 'Noun'],

        'koyo' => ['meaning' => 'Learn', 'category' => 'Verb'],
        'kokari' => ['meaning' => 'Try/Make effort', 'category' => 'Verb'],
        'karɓa' => ['meaning' => 'Receive/Take', 'category' => 'Verb'],
        'jefa' => ['meaning' => 'Throw', 'category' => 'Verb'],
        'kulle' => ['meaning' => 'Lock', 'category' => 'Verb'],
        'bude' => ['meaning' => 'Open', 'category' => 'Verb'],
        'wanke' => ['meaning' => 'Wash', 'category' => 'Verb'],
        'kwana' => ['meaning' => 'Sleep/Spend night', 'category' => 'Verb'],
        'bari' => ['meaning' => 'Leave/Stop', 'category' => 'Verb'],
        'koya' => ['meaning' => 'Teach someone', 'category' => 'Verb'],
        'nemi' => ['meaning' => 'Search/Look for', 'category' => 'Verb'],
        'gane' => ['meaning' => 'Understand', 'category' => 'Verb'],
        'tsaya' => ['meaning' => 'Stop/Stand', 'category' => 'Verb'],
        'rufe' => ['meaning' => 'Close', 'category' => 'Verb'],
        'jira' => ['meaning' => 'Wait', 'category' => 'Verb'],
        'buga' => ['meaning' => 'Hit/Strike', 'category' => 'Verb'],
        'kula' => ['meaning' => 'Take care/Pay attention', 'category' => 'Verb'],

        'mai dadi' => ['meaning' => 'Delicious/Sweet', 'category' => 'Adjective'],
        'mai kyau' => ['meaning' => 'Beautiful/Good', 'category' => 'Adjective'],
        'mai wuya' => ['meaning' => 'Difficult/Hard', 'category' => 'Adjective'],
        'mai sauki' => ['meaning' => 'Easy/Simple', 'category' => 'Adjective'],
        'tsawo' => ['meaning' => 'Long/Tall', 'category' => 'Adjective'],
        'gajere' => ['meaning' => 'Short', 'category' => 'Adjective'],
        'tsabta' => ['meaning' => 'Clean', 'category' => 'Adjective'],
        'datti' => ['meaning' => 'Dirty', 'category' => 'Adjective'],
        'girman kai' => ['meaning' => 'Proud/Arrogant', 'category' => 'Adjective'],
        'kankani' => ['meaning' => 'Small/Little', 'category' => 'Adjective'],
        'babba' => ['meaning' => 'Big', 'category' => 'Adjective'],

        // ------------------- Expressions -------------------
        'ina so' => ['meaning' => 'I like/I love', 'category' => 'Expression'],
        'ban so' => ['meaning' => 'I don’t like', 'category' => 'Expression'],
        'muna tare' => ['meaning' => 'We are together', 'category' => 'Expression'],
        'yi hakuri' => ['meaning' => 'Be patient', 'category' => 'Expression'],
        'dan Allah yi min' => ['meaning' => 'Please do it for me', 'category' => 'Expression'],
        'ina jin yunwa' => ['meaning' => 'I am hungry', 'category' => 'Expression'],
        'ina jin kishirwa' => ['meaning' => 'I am thirsty', 'category' => 'Expression'],
        'babu matsala' => ['meaning' => 'No problem', 'category' => 'Expression'],
        'ina jiran ka' => ['meaning' => 'I am waiting for you', 'category' => 'Expression'],
        'sannu da zuwa' => ['meaning' => 'Welcome', 'category' => 'Greeting'],
        'sannu da aiki' => ['meaning' => 'Well done with work', 'category' => 'Greeting'],
        'sannu da kokari' => ['meaning' => 'Well done for the effort', 'category' => 'Expression'],
        'arziki' => ['meaning' => 'Wealth/Good fortune', 'category' => 'Expression'],
        'ina bukata' => ['meaning' => 'I need', 'category' => 'Expression'],
        'yi sauri' => ['meaning' => 'Hurry up', 'category' => 'Expression'],
        'ji tsoro' => ['meaning' => 'Be afraid', 'category' => 'Expression']
];


    private $yorubaDictionary = [
        'pẹlẹ' => ['meaning' => 'Hello (greeting)', 'category' => 'Greeting'],
        'pele' => ['meaning' => 'Hello (greeting)', 'category' => 'Greeting'],

        'ọkùnrin' => ['meaning' => 'Man/Boy', 'category' => 'Noun'],
        'okunrin' => ['meaning' => 'Man/Boy', 'category' => 'Noun'],

        'obìnrin' => ['meaning' => 'Woman/Girl', 'category' => 'Noun'],
        'obinrin' => ['meaning' => 'Woman/Girl', 'category' => 'Noun'],

        'ń' => ['meaning' => 'Is (present continuous)', 'category' => 'Verb'],
        'n' => ['meaning' => 'Is (present continuous)', 'category' => 'Verb'],

        'kà' => ['meaning' => 'Read/Study', 'category' => 'Verb'],
        'ka' => ['meaning' => 'Read/Study', 'category' => 'Verb'],

        'ìwé' => ['meaning' => 'Book', 'category' => 'Noun'],
        'iwe' => ['meaning' => 'Book', 'category' => 'Noun'],

        'olùkọ́' => ['meaning' => 'Teacher', 'category' => 'Noun'],
        'oluko' => ['meaning' => 'Teacher', 'category' => 'Noun'],

        'ílò' => ['meaning' => 'Use', 'category' => 'Verb'],
        'ilo' => ['meaning' => 'Use', 'category' => 'Verb'],

        'ilé' => ['meaning' => 'House/Home', 'category' => 'Noun'],
        'ile' => ['meaning' => 'House/Home', 'category' => 'Noun'],

        'jẹun' => ['meaning' => 'Eating', 'category' => 'Verb'],
        'jeun' => ['meaning' => 'Eating', 'category' => 'Verb'],

        'oúnjẹ' => ['meaning' => 'Food', 'category' => 'Noun'],
        'ounje' => ['meaning' => 'Food', 'category' => 'Noun'],

        'báwo' => ['meaning' => 'How', 'category' => 'Adverb'],
        'bawo' => ['meaning' => 'How', 'category' => 'Adverb'],

        'iṣẹ́' => ['meaning' => 'Work', 'category' => 'Noun'],
        'ise' => ['meaning' => 'Work', 'category' => 'Noun'],

        'mo' => ['meaning' => 'I', 'category' => 'Pronoun'],

        'fẹ́' => ['meaning' => 'Want', 'category' => 'Verb'],
        'fe' => ['meaning' => 'Want', 'category' => 'Verb'],

        'lọ' => ['meaning' => 'Go', 'category' => 'Verb'],
        'lo' => ['meaning' => 'Go', 'category' => 'Verb'],

        'jàngbà' => ['meaning' => 'Market', 'category' => 'Noun'],
        'jangba' => ['meaning' => 'Market', 'category' => 'Noun'],

        'ẹ káàárọ̀' => ['meaning' => 'Good morning', 'category' => 'Greeting'],
        'e kaaaro' => ['meaning' => 'Good morning', 'category' => 'Greeting'],
        'e kaaro' => ['meaning' => 'Good morning', 'category' => 'Greeting'],

        'ẹ káàsán' => ['meaning' => 'Good afternoon', 'category' => 'Greeting'],
        'e kaasan' => ['meaning' => 'Good afternoon', 'category' => 'Greeting'],

        'ẹ káalẹ́' => ['meaning' => 'Good evening', 'category' => 'Greeting'],
        'e kaale' => ['meaning' => 'Good evening', 'category' => 'Greeting'],

        'ẹ káalẹ́ o' => ['meaning' => 'Good evening (polite)', 'category' => 'Greeting'],
        'e kaale o' => ['meaning' => 'Good evening (polite)', 'category' => 'Greeting'],

        'dáadáa ni' => ['meaning' => 'I am fine', 'category' => 'Expression'],
        'daadaa ni' => ['meaning' => 'I am fine', 'category' => 'Expression'],

        'ẹ ṣé' => ['meaning' => 'Thank you', 'category' => 'Expression'],
        'e se' => ['meaning' => 'Thank you', 'category' => 'Expression'],

        'ẹ jọ̀ọ́' => ['meaning' => 'Please', 'category' => 'Expression'],
        'e joo' => ['meaning' => 'Please', 'category' => 'Expression'],

        'má bínú' => ['meaning' => 'Sorry / Don’t be angry', 'category' => 'Expression'],
        'ma binu' => ['meaning' => 'Sorry / Don’t be angry', 'category' => 'Expression'],

        'ọ̀pẹ́' => ['meaning' => 'Thanks', 'category' => 'Expression'],
        'ope' => ['meaning' => 'Thanks', 'category' => 'Expression'],

        'ìwọ' => ['meaning' => 'You (singular)', 'category' => 'Pronoun'],
        'iwo' => ['meaning' => 'You (singular)', 'category' => 'Pronoun'],

        'ẹ̀yin' => ['meaning' => 'You (plural)', 'category' => 'Pronoun'],
        'eyin' => ['meaning' => 'You (plural)', 'category' => 'Pronoun'],

        'ọmọ' => ['meaning' => 'Child', 'category' => 'Noun'],
        'omo' => ['meaning' => 'Child', 'category' => 'Noun'],

        'ọrẹ́' => ['meaning' => 'Friend', 'category' => 'Noun'],
        'ore' => ['meaning' => 'Friend', 'category' => 'Noun'],

        'ẹbí' => ['meaning' => 'Family', 'category' => 'Noun'],
        'ebi' => ['meaning' => 'Family', 'category' => 'Noun'],

        'bàbá' => ['meaning' => 'Father', 'category' => 'Noun'],
        'baba' => ['meaning' => 'Father', 'category' => 'Noun'],

        'ìyá' => ['meaning' => 'Mother', 'category' => 'Noun'],
        'iya' => ['meaning' => 'Mother', 'category' => 'Noun'],

        'kọ́' => ['meaning' => 'Learn / Write', 'category' => 'Verb'],
        'ko' => ['meaning' => 'Learn / Write', 'category' => 'Verb'],

        'kọ́ni' => ['meaning' => 'Teach', 'category' => 'Verb'],
        'koni' => ['meaning' => 'Teach', 'category' => 'Verb'],

        'ẹ̀kọ́' => ['meaning' => 'Lesson', 'category' => 'Noun'],
        'eko' => ['meaning' => 'Lesson', 'category' => 'Noun'],

        'ilé-ẹ̀kọ́' => ['meaning' => 'School', 'category' => 'Noun'],
        'ile-eko' => ['meaning' => 'School', 'category' => 'Noun'],
        'ile eko' => ['meaning' => 'School', 'category' => 'Noun'],

        'ọmọ-ẹ̀kọ́' => ['meaning' => 'Student', 'category' => 'Noun'],
        'omo-eko' => ['meaning' => 'Student', 'category' => 'Noun'],
        'omo eko' => ['meaning' => 'Student', 'category' => 'Noun'],

        'ọ́fíìsì' => ['meaning' => 'Office', 'category' => 'Noun'],
        'ofiisi' => ['meaning' => 'Office', 'category' => 'Noun'],

        'ṣiṣẹ́' => ['meaning' => 'Work (verb)', 'category' => 'Verb'],
        'sise' => ['meaning' => 'Work (verb)', 'category' => 'Verb'],

        'wá' => ['meaning' => 'Come', 'category' => 'Verb'],
        'wa' => ['meaning' => 'Come', 'category' => 'Verb'],

        'dúró' => ['meaning' => 'Stand / Stop', 'category' => 'Verb'],
        'duro' => ['meaning' => 'Stand / Stop', 'category' => 'Verb'],

        'jókòó' => ['meaning' => 'Sit', 'category' => 'Verb'],
        'jokoo' => ['meaning' => 'Sit', 'category' => 'Verb'],

        'rí' => ['meaning' => 'See', 'category' => 'Verb'],
        'ri' => ['meaning' => 'See', 'category' => 'Verb'],

        'gbọ́' => ['meaning' => 'Hear', 'category' => 'Verb'],
        'gbo' => ['meaning' => 'Hear', 'category' => 'Verb'],

        'sọ̀rọ̀' => ['meaning' => 'Speak / Talk', 'category' => 'Verb'],
        'soro' => ['meaning' => 'Speak / Talk', 'category' => 'Verb'],

        'ra' => ['meaning' => 'Buy', 'category' => 'Verb'],

        'tà' => ['meaning' => 'Sell', 'category' => 'Verb'],
        'ta' => ['meaning' => 'Sell', 'category' => 'Verb'],

        'ọjà' => ['meaning' => 'Market', 'category' => 'Noun'],
        'oja' => ['meaning' => 'Market', 'category' => 'Noun'],

        'opópónà' => ['meaning' => 'Road', 'category' => 'Noun'],
        'opopona' => ['meaning' => 'Road', 'category' => 'Noun'],

        'ìlú' => ['meaning' => 'Town / City', 'category' => 'Noun'],
        'ilu' => ['meaning' => 'Town / City', 'category' => 'Noun'],

        'omi' => ['meaning' => 'Water', 'category' => 'Noun'],

        'aṣọ' => ['meaning' => 'Cloth / Clothes', 'category' => 'Noun'],
        'aso' => ['meaning' => 'Cloth / Clothes', 'category' => 'Noun'],

        'owo' => ['meaning' => 'Money', 'category' => 'Noun'],

        'àga' => ['meaning' => 'Chair', 'category' => 'Noun'],
        'aga' => ['meaning' => 'Chair', 'category' => 'Noun'],

        'ónì' => ['meaning' => 'Today', 'category' => 'Adverb'],
        'oni' => ['meaning' => 'Today', 'category' => 'Adverb'],

        'lọ́la' => ['meaning' => 'Tomorrow', 'category' => 'Adverb'],
        'lola' => ['meaning' => 'Tomorrow', 'category' => 'Adverb'],

        'lána' => ['meaning' => 'Yesterday', 'category' => 'Adverb'],
        'lana' => ['meaning' => 'Yesterday', 'category' => 'Adverb'],

        'ńlá' => ['meaning' => 'Big', 'category' => 'Adjective'],
        'nla' => ['meaning' => 'Big', 'category' => 'Adjective'],

        'kékeré' => ['meaning' => 'Small', 'category' => 'Adjective'],
        'kekere' => ['meaning' => 'Small', 'category' => 'Adjective'],

        'pọ̀' => ['meaning' => 'Many / Much', 'category' => 'Adjective'],
        'po' => ['meaning' => 'Many / Much', 'category' => 'Adjective'],

        //Numbers
        'ọ̀kan' => ['meaning' => 'One', 'category' => 'Number'],
        'okan' => ['meaning' => 'One', 'category' => 'Number'],
        'èjì' => ['meaning' => 'Two', 'category' => 'Number'],
        'eji' => ['meaning' => 'Two', 'category' => 'Number'],
        'ẹ̀ta' => ['meaning' => 'Three', 'category' => 'Number'],
        'eta' => ['meaning' => 'Three', 'category' => 'Number'],
        'ẹ̀rin' => ['meaning' => 'Four', 'category' => 'Number'],
        'erin' => ['meaning' => 'Four', 'category' => 'Number'],
        'márùn-ún' => ['meaning' => 'Five', 'category' => 'Number'],
        'marun-un' => ['meaning' => 'Five', 'category' => 'Number'],
        'marun un' => ['meaning' => 'Five', 'category' => 'Number'],
        'mẹ́fà' => ['meaning' => 'Six', 'category' => 'Number'],
        'mefa' => ['meaning' => 'Six', 'category' => 'Number'],
        'méje' => ['meaning' => 'Seven', 'category' => 'Number'],
        'meje' => ['meaning' => 'Seven', 'category' => 'Number'],
        'mẹ́jọ' => ['meaning' => 'Eight', 'category' => 'Number'],
        'mejo' => ['meaning' => 'Eight', 'category' => 'Number'],
        'mẹ́sàn-án' => ['meaning' => 'Nine', 'category' => 'Number'],
        'mesan-an' => ['meaning' => 'Nine', 'category' => 'Number'],
        'mesan an' => ['meaning' => 'Nine', 'category' => 'Number'],
        'mẹ́wàá' => ['meaning' => 'Ten', 'category' => 'Number'],
        'mewaa' => ['meaning' => 'Ten', 'category' => 'Number'],
        'mọ́kànlá' => ['meaning' => 'Eleven', 'category' => 'Number'],
        'mokanla' => ['meaning' => 'Eleven', 'category' => 'Number'],
        'mejila' => ['meaning' => 'Twelve', 'category' => 'Number'],
        'mejìlá' => ['meaning' => 'Twelve', 'category' => 'Number'],
        'mẹ́tàlá' => ['meaning' => 'Thirteen', 'category' => 'Number'],
        'metala' => ['meaning' => 'Thirteen', 'category' => 'Number'],
        'mẹ́rìnlá' => ['meaning' => 'Fourteen', 'category' => 'Number'],
        'merinla' => ['meaning' => 'Fourteen', 'category' => 'Number'],
        'meedogun' => ['meaning' => 'Fifteen', 'category' => 'Number'],
        'mẹ́ẹ̀dógún' => ['meaning' => 'Fifteen', 'category' => 'Number'],
        'mẹ́rìndínlógún' => ['meaning' => 'Sixteen', 'category' => 'Number'],
        'merindinlogun' => ['meaning' => 'Sixteen', 'category' => 'Number'],
        'mẹ́tàdínlógún' => ['meaning' => 'Seventeen', 'category' => 'Number'],
        'metadinlogun' => ['meaning' => 'Seventeen', 'category' => 'Number'],
        'méjìdínlógún' => ['meaning' => 'Eighteen', 'category' => 'Number'],
        'mejidinlogun' => ['meaning' => 'Eighteen', 'category' => 'Number'],
        'mọ́kàndínlógún' => ['meaning' => 'Nineteen', 'category' => 'Number'],
        'mokandinlogun' => ['meaning' => 'Nineteen', 'category' => 'Number'],
        'ogún' => ['meaning' => 'Twenty', 'category' => 'Number'],
        'ogun' => ['meaning' => 'Twenty', 'category' => 'Number'],
        'ogun-lekan' => ['meaning' => 'Twenty-one', 'category' => 'Number'],
        'ogun lekan' => ['meaning' => 'Twenty-one', 'category' => 'Number'],
        'ogún-lẹ́kan' => ['meaning' => 'Twenty-one', 'category' => 'Number'],
        'ogún-léjì' => ['meaning' => 'Twenty-two', 'category' => 'Number'],
        'ogun-leji' => ['meaning' => 'Twenty-two', 'category' => 'Number'],
        'ogun leji' => ['meaning' => 'Twenty-two', 'category' => 'Number'],
        'ogún-lẹ́ta' => ['meaning' => 'Twenty-three', 'category' => 'Number'],
        'ogun-leta' => ['meaning' => 'Twenty-three', 'category' => 'Number'],
        'ogun-leta' => ['meaning' => 'Twenty-three', 'category' => 'Number'],
        'ogún-lẹ́rin' => ['meaning' => 'Twenty-four', 'category' => 'Number'],
        'ogun-lerin' => ['meaning' => 'Twenty-four', 'category' => 'Number'],
        'ogun lerin' => ['meaning' => 'Twenty-four', 'category' => 'Number'],
        'ogún-lẹ́marùn-ún' => ['meaning' => 'Twenty-five', 'category' => 'Number'],
        'ogun-lemarun-un' => ['meaning' => 'Twenty-five', 'category' => 'Number'],
        'ogun lemarun un' => ['meaning' => 'Twenty-five', 'category' => 'Number'],
        'ogún-lẹ́mẹ́fà' => ['meaning' => 'Twenty-six', 'category' => 'Number'],
        'ogun-lemefa' => ['meaning' => 'Twenty-six', 'category' => 'Number'],
        'ogun lemefa' => ['meaning' => 'Twenty-six', 'category' => 'Number'],
        'ogún-lẹ́méje' => ['meaning' => 'Twenty-seven', 'category' => 'Number'],
        'ogun-lemeje' => ['meaning' => 'Twenty-seven', 'category' => 'Number'],
        'ogun lemeje' => ['meaning' => 'Twenty-seven', 'category' => 'Number'],
        'ogún-lẹ́mẹ́jọ' => ['meaning' => 'Twenty-eight', 'category' => 'Number'],
        'ogun-lemejo' => ['meaning' => 'Twenty-eight', 'category' => 'Number'],
        'ogun lemejo' => ['meaning' => 'Twenty-eight', 'category' => 'Number'],
        'ogún-lẹ́mẹ́sàn-án' => ['meaning' => 'Twenty-nine', 'category' => 'Number'],
        'ogun-lemesan-an' => ['meaning' => 'Twenty-nine', 'category' => 'Number'],
        'ogun lemesan an' => ['meaning' => 'Twenty-nine', 'category' => 'Number'],
        'ọgbọ̀n' => ['meaning' => 'Thirty', 'category' => 'Number'],
        'ogbon' => ['meaning' => 'Thirty', 'category' => 'Number'],
        'ọgbọ̀n-lẹ́kan' => ['meaning' => 'Thirty-one', 'category' => 'Number'],
        'ogbon-lekan' => ['meaning' => 'Thirty-one', 'category' => 'Number'],
        'ọgbọ̀n-léjì' => ['meaning' => 'Thirty-two', 'category' => 'Number'],
        'ogbon-leji' => ['meaning' => 'Thirty-two', 'category' => 'Number'],
        'ogbon-leta' => ['meaning' => 'Thirty-three', 'category' => 'Number'],
        'ọgbọ̀n-lẹ́ta' => ['meaning' => 'Thirty-three', 'category' => 'Number'],
        'ọgbọ̀n-lẹ́rin' => ['meaning' => 'Thirty-four', 'category' => 'Number'],
        'ogbon-lerin' => ['meaning' => 'Thirty-four', 'category' => 'Number'],
        'ogbon-lemarun-un' => ['meaning' => 'Thirty-five', 'category' => 'Number'],
        'ogbon-lemarun un' => ['meaning' => 'Thirty-five', 'category' => 'Number'],
        'ọgbọ̀n-lẹ́marùn-ún' => ['meaning' => 'Thirty-five', 'category' => 'Number'],
        'ọgbọ̀n-lẹ́mẹ́fà' => ['meaning' => 'Thirty-six', 'category' => 'Number'],
        'ogbon-lemefa' => ['meaning' => 'Thirty-six', 'category' => 'Number'],
        'ọgbọ̀n-lẹ́mẹ́jọ' => ['meaning' => 'Thirty-eight', 'category' => 'Number'],
        'ogbon-lemejo' => ['meaning' => 'Thirty-eight', 'category' => 'Number'],
        'ọgbọ̀n-lẹ́méje' => ['meaning' => 'Thirty-seven', 'category' => 'Number'],
        'ogbon-lemeje' => ['meaning' => 'Thirty-seven', 'category' => 'Number'],
        'ọgbọ̀n-lẹ́mẹ́sàn-án' => ['meaning' => 'Thirty-nine', 'category' => 'Number'],
        'ogbon-lemesan-an' => ['meaning' => 'Thirty-nine', 'category' => 'Number'],
        'ogbon lemesan an' => ['meaning' => 'Thirty-nine', 'category' => 'Number'],
        'ogoji' => ['meaning' => 'Forty', 'category' => 'Number'],
        'ogójì' => ['meaning' => 'Forty', 'category' => 'Number'],
        'àádọ́ta' => ['meaning' => 'Fifty', 'category' => 'Number'],
        'aadota' => ['meaning' => 'Fifty', 'category' => 'Number'],
        'àádọ́rin' => ['meaning' => 'Seventy', 'category' => 'Number'],
        'aadorin' => ['meaning' => 'Seventy', 'category' => 'Number'],
        'ọgọ́ta' => ['meaning' => 'Sixty', 'category' => 'Number'],
        'ogota' => ['meaning' => 'Sixty', 'category' => 'Number'],
        'ọgọ́rin' => ['meaning' => 'Eighty', 'category' => 'Number'],
        'ogorin' => ['meaning' => 'Eighty', 'category' => 'Number'],
        'àádọ́rùn-ún' => ['meaning' => 'Ninety', 'category' => 'Number'],
        'aadorun-un' => ['meaning' => 'Ninety', 'category' => 'Number'],
        'aadorun un' => ['meaning' => 'Ninety', 'category' => 'Number'],
        'ọgọ́rùn-ún' => ['meaning' => 'One hundred', 'category' => 'Number'],
        'ogorun-un' => ['meaning' => 'One hundred', 'category' => 'Number'],
        'ogorun un' => ['meaning' => 'One hundred', 'category' => 'Number'],

    ];

    private $igboDictionary = [
        'kedu' => ['meaning' => 'Hello/How are you', 'category' => 'Greeting'],
        'nwoke' => ['meaning' => 'Man/Boy', 'category' => 'Noun'],
        'nwaanyị' => ['meaning' => 'Woman/Girl', 'category' => 'Noun'],
        'nwaanyi' => ['meaning' => 'Woman/Girl', 'category' => 'Noun'],
        'na' => ['meaning' => 'Is (present continuous)', 'category' => 'Verb'],
        'gụ' => ['meaning' => 'Read', 'category' => 'Verb'],
        'gu' => ['meaning' => 'Read', 'category' => 'Verb'],
        'akwụkwọ' => ['meaning' => 'Book/Paper', 'category' => 'Noun'],
        'akwukwo' => ['meaning' => 'Book/Paper', 'category' => 'Noun'],
        'onye nkuzi' => ['meaning' => 'Teacher', 'category' => 'Noun'],
        'ụlọ akwụkwọ' => ['meaning' => 'School', 'category' => 'Noun'],
        'ulo akwukwo' => ['meaning' => 'School', 'category' => 'Noun'],
        'eri' => ['meaning' => 'Eating', 'category' => 'Verb'],
        'nri' => ['meaning' => 'Food', 'category' => 'Noun'],
        'ụlọ' => ['meaning' => 'House/Home', 'category' => 'Noun'],
        'ulo' => ['meaning' => 'House/Home', 'category' => 'Noun'],
        'olee' => ['meaning' => 'How', 'category' => 'Adverb'],
        'ọrụ' => ['meaning' => 'Work', 'category' => 'Noun'],
        'oru' => ['meaning' => 'Work', 'category' => 'Noun'],
        'm' => ['meaning' => 'I', 'category' => 'Pronoun'],
        'chọ' => ['meaning' => 'Want', 'category' => 'Verb'],
        'cho' => ['meaning' => 'Want', 'category' => 'Verb'],
        'ga' => ['meaning' => 'Will/Go', 'category' => 'Verb'],
        'ahịa' => ['meaning' => 'Market', 'category' => 'Noun'],
        'ahia' => ['meaning' => 'Market', 'category' => 'Noun'],

        'kedu ka i mere?' => ['meaning' => 'How are you?', 'category' => 'Greeting'],
        'ụtụtụ ọma' => ['meaning' => 'Good morning', 'category' => 'Greeting'],
        'ututu oma' => ['meaning' => 'Good morning', 'category' => 'Greeting'],
        'ehihie ọma' => ['meaning' => 'Good afternoon', 'category' => 'Greeting'],
        'ehihie oma' => ['meaning' => 'Good afternoon', 'category' => 'Greeting'],
        'anyasị ọma' => ['meaning' => 'Good evening', 'category' => 'Greeting'],
        'anyasi oma' => ['meaning' => 'Good evening', 'category' => 'Greeting'],
        'daalụ' => ['meaning' => 'Thank you', 'category' => 'Expression'],
        'daalu' => ['meaning' => 'Thank you', 'category' => 'Expression'],
        'biko' => ['meaning' => 'Please', 'category' => 'Expression'],
        'ndo' => ['meaning' => 'Sorry', 'category' => 'Expression'],
        'ka ọ dị' => ['meaning' => 'It is well / I am fine', 'category' => 'Expression'],
        'ka o di' => ['meaning' => 'It is well / I am fine', 'category' => 'Expression'],

        'gi' => ['meaning' => 'You (singular)', 'category' => 'Pronoun'],
        'unu' => ['meaning' => 'You (plural)', 'category' => 'Pronoun'],
        'anyị' => ['meaning' => 'We', 'category' => 'Pronoun'],
        'anyi' => ['meaning' => 'We', 'category' => 'Pronoun'],
        'ha' => ['meaning' => 'They', 'category' => 'Pronoun'],
        'nwa' => ['meaning' => 'Child', 'category' => 'Noun'],
        'enyi' => ['meaning' => 'Friend', 'category' => 'Noun'],
        'nne' => ['meaning' => 'Mother', 'category' => 'Noun'],
        'nna' => ['meaning' => 'Father', 'category' => 'Noun'],

        'mụ' => ['meaning' => 'Learn', 'category' => 'Verb'],
        'mu' => ['meaning' => 'Learn', 'category' => 'Verb'],
        'kuzie' => ['meaning' => 'Teach', 'category' => 'Verb'],
        'nkuzi' => ['meaning' => 'Lesson / Teaching', 'category' => 'Noun'],
        'ụlọ ọrụ' => ['meaning' => 'Office', 'category' => 'Noun'],
        'ulo oru' => ['meaning' => 'Office', 'category' => 'Noun'],
        'ọrụ aka' => ['meaning' => 'Job / Handwork', 'category' => 'Noun'],
        'oru aka' => ['meaning' => 'Job / Handwork', 'category' => 'Noun'],
        'bịa' => ['meaning' => 'Come', 'category' => 'Verb'],
        'bia' => ['meaning' => 'Come', 'category' => 'Verb'],
        'gaa' => ['meaning' => 'Go', 'category' => 'Verb'],
        'kwụọ' => ['meaning' => 'Pay', 'category' => 'Verb'],
        'kwuo' => ['meaning' => 'Pay', 'category' => 'Verb'],
        'zụta' => ['meaning' => 'Buy', 'category' => 'Verb'],
        'zuta' => ['meaning' => 'Buy', 'category' => 'Verb'],
        'ree' => ['meaning' => 'Sell', 'category' => 'Verb'],
        'hụ' => ['meaning' => 'See', 'category' => 'Verb'],
        'hu' => ['meaning' => 'See', 'category' => 'Verb'],
        'ge nti' => ['meaning' => 'Listen', 'category' => 'Verb'],
        'kwuo' => ['meaning' => 'Speak', 'category' => 'Verb'],

        'okporo ụzọ' => ['meaning' => 'Road', 'category' => 'Noun'],
        'okporo uzo' => ['meaning' => 'Road', 'category' => 'Noun'],
        'obodo' => ['meaning' => 'Town / City', 'category' => 'Noun'],
        'mmiri' => ['meaning' => 'Water', 'category' => 'Noun'],
        'ego' => ['meaning' => 'Money', 'category' => 'Noun'],
        'uwe' => ['meaning' => 'Clothes', 'category' => 'Noun'],
        'oche' => ['meaning' => 'Chair', 'category' => 'Noun'],

        'taa' => ['meaning' => 'Today', 'category' => 'Adverb'],
        'echi' => ['meaning' => 'Tomorrow', 'category' => 'Adverb'],
        'ụnyaahụ' => ['meaning' => 'Yesterday', 'category' => 'Adverb'],
        'unyaahu' => ['meaning' => 'Yesterday', 'category' => 'Adverb'],
        'ukwu' => ['meaning' => 'Big', 'category' => 'Adjective'],
        'obere' => ['meaning' => 'Small', 'category' => 'Adjective'],
        'ọtụtụ' => ['meaning' => 'Many / Much', 'category' => 'Adjective'],
        'otutu' => ['meaning' => 'Many / Much', 'category' => 'Adjective'],

        //Numbers
        'otu' => ['meaning' => 'One', 'category' => 'Number'],
        'abụọ' => ['meaning' => 'Two', 'category' => 'Number'],
        'abuo' => ['meaning' => 'Two', 'category' => 'Number'],
        'atọ' => ['meaning' => 'Three', 'category' => 'Number'],
        'ato' => ['meaning' => 'Three', 'category' => 'Number'],
        'anọ' => ['meaning' => 'Four', 'category' => 'Number'],
        'ano' => ['meaning' => 'Four', 'category' => 'Number'],
        'ise' => ['meaning' => 'Five', 'category' => 'Number'],
        'isii' => ['meaning' => 'Six', 'category' => 'Number'],
        'asaa' => ['meaning' => 'Seven', 'category' => 'Number'],
        'asatọ' => ['meaning' => 'Eight', 'category' => 'Number'],
        'asato' => ['meaning' => 'Eight', 'category' => 'Number'],
        'itoolu' => ['meaning' => 'Nine', 'category' => 'Number'],
        'iri' => ['meaning' => 'Ten', 'category' => 'Number'],
        'iri na otu' => ['meaning' => 'Eleven', 'category' => 'Number'],
        'iri na abụọ' => ['meaning' => 'Twelve', 'category' => 'Number'],
        'iri na abuo' => ['meaning' => 'Twelve', 'category' => 'Number'],
        'iri na atọ' => ['meaning' => 'Thirteen', 'category' => 'Number'],
        'iri na ato' => ['meaning' => 'Thirteen', 'category' => 'Number'],
        'iri na anọ' => ['meaning' => 'Fourteen', 'category' => 'Number'],
        'iri na ano' => ['meaning' => 'Fourteen', 'category' => 'Number'],
        'iri na ise' => ['meaning' => 'Fifteen', 'category' => 'Number'],
        'iri na isii' => ['meaning' => 'Sixteen', 'category' => 'Number'],
        'iri na asaa' => ['meaning' => 'Seventeen', 'category' => 'Number'],
        'iri na asatọ' => ['meaning' => 'Eighteen', 'category' => 'Number'],
        'iri na asato' => ['meaning' => 'Eighteen', 'category' => 'Number'],
        'iri na itoolu' => ['meaning' => 'Nineteen', 'category' => 'Number'],
        'iri abụọ' => ['meaning' => 'Twenty', 'category' => 'Number'],
        'iri abuo' => ['meaning' => 'Twenty', 'category' => 'Number'],
        'iri abụọ na otu' => ['meaning' => 'Twenty-one', 'category' => 'Number'],
        'iri abuo na otu' => ['meaning' => 'Twenty-one', 'category' => 'Number'],
        'iri abụọ na abụọ' => ['meaning' => 'Twenty-two', 'category' => 'Number'],
        'iri abuo na abuo' => ['meaning' => 'Twenty-two', 'category' => 'Number'],
        'iri abụọ na atọ' => ['meaning' => 'Twenty-three', 'category' => 'Number'],
        'iri abuo na ato' => ['meaning' => 'Twenty-three', 'category' => 'Number'],
        'iri abụọ na anọ' => ['meaning' => 'Twenty-four', 'category' => 'Number'],
        'iri abuo na ano' => ['meaning' => 'Twenty-four', 'category' => 'Number'],
        'iri abụọ na ise' => ['meaning' => 'Twenty-five', 'category' => 'Number'],
        'iri abuo na ise' => ['meaning' => 'Twenty-five', 'category' => 'Number'],
        'iri abụọ na isii' => ['meaning' => 'Twenty-six', 'category' => 'Number'],
        'iri abụọ na asaa' => ['meaning' => 'Twenty-seven', 'category' => 'Number'],
        'iri abụọ na asatọ' => ['meaning' => 'Twenty-eight', 'category' => 'Number'],
        'iri abuo na asato' => ['meaning' => 'Twenty-eight', 'category' => 'Number'],
        'iri abụọ na itoolu' => ['meaning' => 'Twenty-nine', 'category' => 'Number'],
        'iri abuo na itoolu' => ['meaning' => 'Twenty-nine', 'category' => 'Number'],
        'iri atọ' => ['meaning' => 'Thirty', 'category' => 'Number'],
        'iri ato' => ['meaning' => 'Thirty', 'category' => 'Number'],
        'iri atọ na otu' => ['meaning' => 'Thirty-one', 'category' => 'Number'],
        'iri ato na otu' => ['meaning' => 'Thirty-one', 'category' => 'Number'],
        'iri atọ na abụọ' => ['meaning' => 'Thirty-two', 'category' => 'Number'],
        'iri ato na abuo' => ['meaning' => 'Thirty-two', 'category' => 'Number'],
        'iri atọ na atọ' => ['meaning' => 'Thirty-three', 'category' => 'Number'],
        'iri ato na ato' => ['meaning' => 'Thirty-three', 'category' => 'Number'],
        'iri atọ na anọ' => ['meaning' => 'Thirty-four', 'category' => 'Number'],
        'iri ato na ano' => ['meaning' => 'Thirty-four', 'category' => 'Number'],
        'iri atọ na ise' => ['meaning' => 'Thirty-five', 'category' => 'Number'],
        'iri ato na ise' => ['meaning' => 'Thirty-five', 'category' => 'Number'],
        'iri atọ na isii' => ['meaning' => 'Thirty-six', 'category' => 'Number'],
        'iri ato na isii' => ['meaning' => 'Thirty-six', 'category' => 'Number'],
        'iri atọ na asaa' => ['meaning' => 'Thirty-seven', 'category' => 'Number'],
        'iri ato na asaa' => ['meaning' => 'Thirty-seven', 'category' => 'Number'],
        'iri atọ na asatọ' => ['meaning' => 'Thirty-eight', 'category' => 'Number'],
        'iri ato na asato' => ['meaning' => 'Thirty-eight', 'category' => 'Number'],
        'iri atọ na itoolu' => ['meaning' => 'Thirty-nine', 'category' => 'Number'],
        'iri ato na itoolu' => ['meaning' => 'Thirty-nine', 'category' => 'Number'],
        'iri anọ' => ['meaning' => 'Forty', 'category' => 'Number'],
        'iri ano' => ['meaning' => 'Forty', 'category' => 'Number'],
        'iri anọ na otu' => ['meaning' => 'Forty-one', 'category' => 'Number'],
        'iri ano na otu' => ['meaning' => 'Forty-one', 'category' => 'Number'],
        'iri anọ na abụọ' => ['meaning' => 'Forty-two', 'category' => 'Number'],
        'iri ano na abuo' => ['meaning' => 'Forty-two', 'category' => 'Number'],
        'iri anọ na atọ' => ['meaning' => 'Forty-three', 'category' => 'Number'],
        'iri ano na ato' => ['meaning' => 'Forty-three', 'category' => 'Number'],
        'iri anọ na anọ' => ['meaning' => 'Forty-four', 'category' => 'Number'],
        'iri ano na ano' => ['meaning' => 'Forty-four', 'category' => 'Number'],
        'iri anọ na ise' => ['meaning' => 'Forty-five', 'category' => 'Number'],
        'iri ano na ise' => ['meaning' => 'Forty-five', 'category' => 'Number'],
        'iri anọ na isii' => ['meaning' => 'Forty-six', 'category' => 'Number'],
        'iri ano na isii' => ['meaning' => 'Forty-six', 'category' => 'Number'],
        'iri anọ na asaa' => ['meaning' => 'Forty-seven', 'category' => 'Number'],
        'iri ano na asaa' => ['meaning' => 'Forty-seven', 'category' => 'Number'],
        'iri anọ na asatọ' => ['meaning' => 'Forty-eight', 'category' => 'Number'],
        'iri ano na asato' => ['meaning' => 'Forty-eight', 'category' => 'Number'],
        'iri anọ na itoolu' => ['meaning' => 'Forty-nine', 'category' => 'Number'],
        'iri ano na itoolu' => ['meaning' => 'Forty-nine', 'category' => 'Number'],
        'iri ise' => ['meaning' => 'Fifty', 'category' => 'Number'],
        'iri ise na otu' => ['meaning' => 'Fifty-one', 'category' => 'Number'],
        'iri ise na abụọ' => ['meaning' => 'Fifty-two', 'category' => 'Number'],
        'iri ise na abuo' => ['meaning' => 'Fifty-two', 'category' => 'Number'],
        'iri ise na atọ' => ['meaning' => 'Fifty-three', 'category' => 'Number'],
        'iri ise na ato' => ['meaning' => 'Fifty-three', 'category' => 'Number'],
        'iri ise na anọ' => ['meaning' => 'Fifty-four', 'category' => 'Number'],
        'iri ise na ano' => ['meaning' => 'Fifty-four', 'category' => 'Number'],
        'iri ise na ise' => ['meaning' => 'Fifty-five', 'category' => 'Number'],
        'iri ise na isii' => ['meaning' => 'Fifty-six', 'category' => 'Number'],
        'iri ise na asaa' => ['meaning' => 'Fifty-seven', 'category' => 'Number'],
        'iri ise na asatọ' => ['meaning' => 'Fifty-eight', 'category' => 'Number'],
        'iri ise na asato' => ['meaning' => 'Fifty-eight', 'category' => 'Number'],
        'iri ise na itoolu' => ['meaning' => 'Fifty-nine', 'category' => 'Number'],
        'iri isii' => ['meaning' => 'Sixty', 'category' => 'Number'],
        'iri isii na otu' => ['meaning' => 'Sixty-one', 'category' => 'Number'],
        'iri isii na abụọ' => ['meaning' => 'Sixty-two', 'category' => 'Number'],
        'iri isii na abuo' => ['meaning' => 'Sixty-two', 'category' => 'Number'],
        'iri isii na atọ' => ['meaning' => 'Sixty-three', 'category' => 'Number'],
        'iri isii na ato' => ['meaning' => 'Sixty-three', 'category' => 'Number'],
        'iri isii na anọ' => ['meaning' => 'Sixty-four', 'category' => 'Number'],
        'iri isii na ano' => ['meaning' => 'Sixty-four', 'category' => 'Number'],
        'iri isii na ise' => ['meaning' => 'Sixty-five', 'category' => 'Number'],
        'iri isii na isii' => ['meaning' => 'Sixty-six', 'category' => 'Number'],
        'iri isii na asaa' => ['meaning' => 'Sixty-seven', 'category' => 'Number'],
        'iri isii na asatọ' => ['meaning' => 'Sixty-eight', 'category' => 'Number'],
        'iri isii na asato' => ['meaning' => 'Sixty-eight', 'category' => 'Number'],
        'iri isii na itoolu' => ['meaning' => 'Sixty-nine', 'category' => 'Number'],
        'iri asaa' => ['meaning' => 'Seventy', 'category' => 'Number'],
        'iri asaa na otu' => ['meaning' => 'Seventy-one', 'category' => 'Number'],
        'iri asaa na abụọ' => ['meaning' => 'Seventy-two', 'category' => 'Number'],
        'iri asaa na abuo' => ['meaning' => 'Seventy-two', 'category' => 'Number'],
        'iri asaa na atọ' => ['meaning' => 'Seventy-three', 'category' => 'Number'],
        'iri asaa na ato' => ['meaning' => 'Seventy-three', 'category' => 'Number'],
        'iri asaa na anọ' => ['meaning' => 'Seventy-four', 'category' => 'Number'],
        'iri asaa na ano' => ['meaning' => 'Seventy-four', 'category' => 'Number'],
        'iri asaa na ise' => ['meaning' => 'Seventy-five', 'category' => 'Number'],
        'iri asaa na isii' => ['meaning' => 'Seventy-six', 'category' => 'Number'],
        'iri asaa na asaa' => ['meaning' => 'Seventy-seven', 'category' => 'Number'],
        'iri asaa na asatọ' => ['meaning' => 'Seventy-eight', 'category' => 'Number'],
        'iri asaa na asato' => ['meaning' => 'Seventy-eight', 'category' => 'Number'],
        'iri asaa na itoolu' => ['meaning' => 'Seventy-nine', 'category' => 'Number'],
        'iri asatọ' => ['meaning' => 'Eighty', 'category' => 'Number'],
        'iri asato' => ['meaning' => 'Eighty', 'category' => 'Number'],
        'iri asatọ na otu' => ['meaning' => 'Eighty-one', 'category' => 'Number'],
        'iri asato na otu' => ['meaning' => 'Eighty-one', 'category' => 'Number'],
        'iri asatọ na abụọ' => ['meaning' => 'Eighty-two', 'category' => 'Number'],
        'iri asato na abuo' => ['meaning' => 'Eighty-two', 'category' => 'Number'],
        'iri asatọ na atọ' => ['meaning' => 'Eighty-three', 'category' => 'Number'],
        'iri asato na ato' => ['meaning' => 'Eighty-three', 'category' => 'Number'],
        'iri asatọ na anọ' => ['meaning' => 'Eighty-four', 'category' => 'Number'],
        'iri asato na ano' => ['meaning' => 'Eighty-four', 'category' => 'Number'],
        'iri asatọ na ise' => ['meaning' => 'Eighty-five', 'category' => 'Number'],
        'iri asato na ise' => ['meaning' => 'Eighty-five', 'category' => 'Number'],
        'iri asatọ na isii' => ['meaning' => 'Eighty-six', 'category' => 'Number'],
        'iri asato na isii' => ['meaning' => 'Eighty-six', 'category' => 'Number'],
        'iri asatọ na asaa' => ['meaning' => 'Eighty-seven', 'category' => 'Number'],
        'iri asato na asaa' => ['meaning' => 'Eighty-seven', 'category' => 'Number'],
        'iri asatọ na asatọ' => ['meaning' => 'Eighty-eight', 'category' => 'Number'],
        'iri asato na asato' => ['meaning' => 'Eighty-eight', 'category' => 'Number'],
        'iri asatọ na itoolu' => ['meaning' => 'Eighty-nine', 'category' => 'Number'],
        'iri asato na itoolu' => ['meaning' => 'Eighty-nine', 'category' => 'Number'],
        'iri itoolu' => ['meaning' => 'Ninety', 'category' => 'Number'],
        'iri itoolu na otu' => ['meaning' => 'Ninety-one', 'category' => 'Number'],
        'iri itoolu na abụọ' => ['meaning' => 'Ninety-two', 'category' => 'Number'],
        'iri itoolu na abuo' => ['meaning' => 'Ninety-two', 'category' => 'Number'],
        'iri itoolu na atọ' => ['meaning' => 'Ninety-three', 'category' => 'Number'],
        'iri itoolu na ato' => ['meaning' => 'Ninety-three', 'category' => 'Number'],
        'iri itoolu na anọ' => ['meaning' => 'Ninety-four', 'category' => 'Number'],
        'iri itoolu na ano' => ['meaning' => 'Ninety-four', 'category' => 'Number'],
        'iri itoolu na ise' => ['meaning' => 'Ninety-five', 'category' => 'Number'],
        'iri itoolu na isii' => ['meaning' => 'Ninety-six', 'category' => 'Number'],
        'iri itoolu na asaa' => ['meaning' => 'Ninety-seven', 'category' => 'Number'],
        'iri itoolu na asatọ' => ['meaning' => 'Ninety-eight', 'category' => 'Number'],
        'iri itoolu na itoolu' => ['meaning' => 'Ninety-nine', 'category' => 'Number'],
        'narị' => ['meaning' => 'One hundred', 'category' => 'Number'],
        'nari' => ['meaning' => 'One hundred', 'category' => 'Number'],

    ];

    public function showHausaPOS(Request $request)
    {
        $sentence = $request->get('sentence', '');
        $analysis = [];
        $wordMeanings = [];

        if ($sentence) {
            $analysis = $this->analyzeWithDictionary($sentence, 'hausa');
            $wordMeanings = $this->getWordMeanings($sentence, 'hausa');

            if (Auth::check()) {
                UserProgress::recordActivity(Auth::id(), 'hausa_pos', $sentence);
            }
        }

        return view('learning.hausa_pos', [
            'sentence' => $sentence,
            'analysis' => $analysis,
            'wordMeanings' => $wordMeanings,
            'language' => 'hausa',
            'languageName' => 'Hausa',
            'module' => 'Dictionary-Powered Analysis',
            'sampleSentences' => [
                'Yaro yana karatu littafi',
                'Malam yana koyarwa a makaranta',
                'Tana cin abinci a gida',
                'Sannu, yaya aikinka?',
                'Ina son zuwa kasuwa'
            ]
        ]);
    }

    /**
     * Yoruba Dictionary-Powered Analysis Interface
     */
    public function showYorubaPOS(Request $request)
    {
        $sentence = $request->get('sentence', '');
        $analysis = [];
        $wordMeanings = [];

        if ($sentence) {
            $analysis = $this->analyzeWithDictionary($sentence, 'yoruba');
            $wordMeanings = $this->getWordMeanings($sentence, 'yoruba');

            if (Auth::check()) {
                UserProgress::recordActivity(Auth::id(), 'yoruba_pos', $sentence);
            }
        }

        return view('learning.yoruba_pos', [
            'sentence' => $sentence,
            'analysis' => $analysis,
            'wordMeanings' => $wordMeanings,
            'language' => 'yoruba',
            'languageName' => 'Yoruba',
            'module' => 'Dictionary-Powered Analysis',
            'sampleSentences' => [
                'Ọkùnrin ń kà ìwé',
                'Olùkọ́ ń kọ́ ní ilé ẹ̀kọ́',
                'Obìnrin ń jẹun ní ilé',
                'Pẹlẹ, báwo ni iṣẹ́ ń lọ?',
                'Mo fẹ́ lọ sí jàngbà'
            ]
        ]);
    }

    /**
     * Igbo Dictionary-Powered Analysis Interface
     */
    public function showIgboPOS(Request $request)
    {
        $sentence = $request->get('sentence', '');
        $analysis = [];
        $wordMeanings = [];

        if ($sentence) {
            $analysis = $this->analyzeWithDictionary($sentence, 'igbo');
            $wordMeanings = $this->getWordMeanings($sentence, 'igbo');

            if (Auth::check()) {
                UserProgress::recordActivity(Auth::id(), 'igbo_pos', $sentence);
            }
        }

        return view('learning.igbo_pos', [
            'sentence' => $sentence,
            'analysis' => $analysis,
            'wordMeanings' => $wordMeanings,
            'language' => 'igbo',
            'languageName' => 'Igbo',
            'module' => 'Dictionary-Powered Analysis',
            'sampleSentences' => [
            'Nwoke na-agụ akwụkwọ',
            'Nwaanyị na-eri nri n\'ụlọ',
            'Chinedu bi na Enugu',
            'Nnamdi gara Lagos'
            ]
        ]);
    }

    /**
     * Analyze sentence using dictionary-based approach
     */
    private function analyzeWithDictionary($sentence, $language)
{
    $cacheKey = "{$language}_analysis_" . md5($sentence);

    return Cache::remember($cacheKey, $this->cacheTime, function () use ($sentence, $language) {

        $raw = $this->performDictionaryAnalysis($sentence, $language);

        return [
            'sentence' => $raw['sentence'],
            'analysis' => collect($raw['analysis'])->map(function ($item) {
                return [
                    'word' => $item['word'] ?? '',
                    'pos'  => $item['pos_tag'] ?? 'UNKNOWN',
                ];
            })->toArray(),
        ];
    });
}


    /**
     * Perform actual dictionary-based analysis
     */
    private function performDictionaryAnalysis($sentence, $language)
    {
        $dictionary = $this->getDictionary($language);
        $words = $this->tokenizeSentence($sentence);
        $analysis = [];

        foreach ($words as $word) {
            $cleanWord = strtolower(trim($word, " .,!?;:\"'"));

            // Check for multi-word phrases first
            $found = false;
            foreach ($dictionary as $phrase => $data) {
                if (strpos(strtolower($sentence), strtolower($phrase)) !== false && strlen($phrase) > 2) {
                    $analysis[] = [
                        'word' => $phrase,
                        'pos_tag' => $this->mapCategoryToPOSTag($data['category'], $phrase),
                        'meaning' => $data['meaning'],
                        'category' => $data['category'],
                        'confidence' => 'high'
                    ];
                    $found = true;
                }
            }

            // If no phrase found, check individual word
            if (!$found && isset($dictionary[$cleanWord])) {
                $data = $dictionary[$cleanWord];
                $analysis[] = [
                    'word' => $cleanWord,
                    'pos_tag' => $this->mapCategoryToPOSTag($data['category'], $cleanWord),
                    'meaning' => $data['meaning'],
                    'category' => $data['category'],
                    'confidence' => 'high'
                ];
            } elseif (!$found) {
                // Word not in dictionary
                $analysis[] = [
                    'word' => $cleanWord,
                    'pos_tag' => $this->guessPOSTag($cleanWord, $language),
                    'meaning' => 'Not found in dictionary',
                    'category' => 'Unknown',
                    'confidence' => 'low'
                ];
            }
        }

        return [
            'sentence' => $sentence,
            'analysis' => $analysis,
            'type' => 'dictionary_based',
            'language' => $language,
            'source' => 'linguasmart_dictionary'
        ];
    }

    /**
     * Get word meanings for a sentence
     */
    private function getWordMeanings($sentence, $language)
    {
        $dictionary = $this->getDictionary($language);
        $words = $this->tokenizeSentence($sentence);
        $meanings = [];

        foreach ($words as $word) {
            $cleanWord = strtolower(trim($word, " .,!?;:\"'"));

            if (isset($dictionary[$cleanWord])) {
                $meanings[$cleanWord] = $dictionary[$cleanWord];
            } else {
                $meanings[$cleanWord] = ['meaning' => 'Meaning not found in dictionary', 'category' => 'Unknown'];
            }
        }

        return $meanings;
    }

    /**
     * Tokenize sentence into words
     */
    private function tokenizeSentence($sentence)
    {
        // Split by spaces and common punctuation
        return preg_split('/[\s,.!?;:]+/', $sentence, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Get the appropriate dictionary based on language
     */
    private function getDictionary($language)
    {
        switch ($language) {
            case 'hausa':
                return $this->hausaDictionary;
            case 'yoruba':
                return $this->yorubaDictionary;
            case 'igbo':
                return $this->igboDictionary;
            default:
                return [];
        }
    }

    /**
     * Map dictionary category to standard POS tag
     */
    private function mapCategoryToPOSTag($category, $word)
    {
        $mappings = [
            'Noun' => 'NOUN',
            'Verb' => 'VERB',
            'Adjective' => 'ADJ',
            'Adverb' => 'ADV',
            'Pronoun' => 'PRON',
            'Preposition' => 'PREP',
            'Conjunction' => 'CONJ',
            'Number' => 'NUM',
            'Greeting' => 'NOUN',
            'Expression' => 'NOUN',
            'Question' => 'NOUN',
            'Greeting / Sympathy' => 'NOUN'
        ];

        return $mappings[$category] ?? $this->guessPOSTag($word, 'general');
    }

    /**
     * Guess POS tag for unknown words
     */
    private function guessPOSTag($word, $language)
    {
        // Simple heuristics based on word endings/patterns
        $word = strtolower($word);

        // Common verb endings in Nigerian languages
        $verbEndings = ['a', 'e', 'i', 'o', 'u'];

        // If word ends with common verb ending, guess VERB
        if (in_array(substr($word, -1), $verbEndings)) {
            return 'VERB';
        }

        // Default to NOUN
        return 'NOUN';
    }

    private function normalizePOSAnalysis(array $rawAnalysis): array
{
    $normalized = [];

    foreach ($rawAnalysis as $key => $value) {

        // Case 1: ['word' => 'DET']
        if (is_string($key) && is_string($value)) {
            $normalized[] = [
                'word' => $key,
                'pos'  => strtoupper($value)
            ];
        }

        // Case 2: ['word', 'DET']
        elseif (is_array($value) && count($value) >= 2) {
            $normalized[] = [
                'word' => $value[0],
                'pos'  => strtoupper($value[1])
            ];
        }
    }

    return $normalized;
}

}
