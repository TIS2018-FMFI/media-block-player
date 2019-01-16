
const navigationController = new NavigationController();
const libraryChooseMediaLanguageViewController = new LibraryChooseMediaLanguageViewController();
navigationController.present(libraryChooseMediaLanguageViewController);
$('#root').html('<ul id="tests">');
const testsList = $('#tests');

var responseLectures = {
    data: [["42","Russian","ru"],["16","French","fr"],["2","Albanian","sq"],["21","Hungarian","hu"]]
}

var responseLessons = {
    id: "1",
    lecture_title: "Medved1",
    description: "Medvedasdasdasfasfsaf",
    level: "1",
    audio_file_link: "http://www.st.fmph.uniba.sk:80/~hrebenar3/Projects/MBP/Data/Media/medved1.wav",
    original_text_link: "http://www.st.fmph.uniba.sk:80/~hrebenar3/Projects/MBP/Data/Scripts/medved1.txt",
    sync_file_link: "http://www.st.fmph.uniba.sk:80/~hrebenar3/Projects/MBP/Data/Syncs/medved1.mbpsf",
    translations: {
        en: "http://www.st.fmph.uniba.sk:80/~hrebenar3/Projects/MBP/Data/Translations/medved1_EN.txt",
        sk: "http://www.st.fmph.uniba.sk:80/~hrebenar3/Projects/MBP/Data/Translations/medved1_SK.txt",
    }
}

// LIBRARY CHOOSE MEDIA LANGUAGE VIEW CONTROLLER TESTS

// Tests for issues in catalog of requirements 3.1.9

// TEST 1 - Chcek if the connection on the online library is successful
libraryChooseMediaLanguageViewController.getAvailableLanguage();
var result = libraryChooseMediaLanguageViewController.availableLanguages;
if (result != undefined) {
    testsList.append('<li>Test 1 - OK</li>');
} else {
    testsList.append('<li>Test 1 - NOK</li>');
}

// TEST 2 - Check if language was correctly selected and language is in online library
libraryChooseMediaLanguageViewController.getAvailableLanguage();
var result = libraryChooseMediaLanguageViewController.availableLanguages;
if (result["data"][0][1] == responseLectures["data"][0][1]) {
    testsList.append('<li>Test 2 - OK</li>');
} else {
    testsList.append('<li>Test 2 - NOK</li>');
}

// TEST 3 - Select/option bar is in default set that selected language is undefined, then choose button is disabled
libraryChooseMediaLanguageViewController.getAvailableLanguage();
var result = libraryChooseMediaLanguageViewController.languagePicker[0].selectedIndex
if (result == 0) {
    testsList.append('<li>Test 3 - OK</li>');
} else {
    testsList.append('<li>Test 3 - NOK</li>');
}

// TEST 4 - If it is not select language, choose button is disabled
libraryChooseMediaLanguageViewController.languagePicker[0].selectedIndex = 0;
libraryChooseMediaLanguageViewController.languageValueChanged();
var trieda = libraryChooseMediaLanguageViewController.chooseButton[0].className;
var active = String(trieda.split("-")).split(" ");
if (active[active.length-1] == "disabled") {
    testsList.append('<li>Test 4 - OK</li>');

} else {
    testsList.append('<li>Test 4 - NOK</li>');
}

// TEST 5 - If it is select language, choose button is abled
libraryChooseMediaLanguageViewController.languagePicker[0].selectedIndex = 2;
libraryChooseMediaLanguageViewController.languageValueChanged();
var trieda = libraryChooseMediaLanguageViewController.chooseButton[0].className;
var active = String(trieda.split("-")).split(" ");
if (active[active.length-1] != "disabled") {
    testsList.append('<li>Test 5 - OK</li>');

} else {
    testsList.append('<li>Test 5 - NOK</li>');
}

// LIBRARY SELECT MEDIA LANGUAGE VIEW CONTROLLER TESTS

const librarySelectMediaLanguageViewController = new LibrarySelectMediaLanguageViewController();
librarySelectMediaLanguageViewController.language = "Russian";
librarySelectMediaLanguageViewController.shortLanguage = "ru";
librarySelectMediaLanguageViewController.url = "http://www.st.fmph.uniba.sk/~hrebenar3/Projects/MBP/api.php";

// TEST 6 
librarySelectMediaLanguageViewController.getAvailableLessons();
var res = librarySelectMediaLanguageViewController.listOfLessons;
if (Object.keys(res[0]).length == 8) {
    testsList.append('<li>Test 6 - OK</li>');
} else {
    testsList.append('<li>Test 6 - NOK</li>');
}
