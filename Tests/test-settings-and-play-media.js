var selectedLecture = {
    id: 14,
    lecture_title: "Magyar Track B",
    level: 1,
    audio_file_link: "../audio/magyar-track-part-B.mp3",
    sync_file_link: "../sync-files/magyar-track-part-B.mbpsf",
    original_text_link: "../original-text/magyar-track-part-B.txt",
    translations: {
        Slovak: "../translations/magyar-track-part-B_SK.txt",
    }
}

const navigationController = new NavigationController();
const settingsViewController = new SettingsViewController(selectedLecture, false);
navigationController.present(settingsViewController);

$('#test-root').html('<ul id="tests" class="collection with-header">');
const testsList = $('#tests')

if (settingsViewController.lecture == selectedLecture) {
    testsList.append('<li class="collection-item">Test 1 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 1 - NOT OK</li>');
}

var parsText = "withoutSeparator";
var result = settingsViewController.parseBlocks(parsText);
if (result.length == 1) {
    testsList.append('<li class="collection-item">Test 2 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 2 - NOK</li>');
}
if (result[0] === parsText) {
    testsList.append('<li class="collection-item">Test 3 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 3 - NOT OK</li>');
}

parsText = "one|separator";
result = settingsViewController.parseBlocks(parsText);
if (result.length === 2) {
    testsList.append('<li class="collection-item">Test 4 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 4 - NOT OK</li>');
}
if (result[0] === "one" && result[1] === "separator") {
    testsList.append('<li class="collection-item">Test 5 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 5 - NOT OK</li>');
}

parsText = "";
result = settingsViewController.parseBlocks(parsText);
if (result.length === 0) {
    testsList.append('<li class="collection-item">Test 6 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 6 - NOT OK</li>');
}

parsText = "||";
result = settingsViewController.parseBlocks(parsText);
if (result.length === 0) {
    testsList.append('<li class="collection-item">Test 7 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 7 - NOT OK</li>');
}

//TRIGGER SETTINGS CHANGE
var lectureString = '{"local":false,"audio":"../audio/magyar-track-part-B.mp3","txtBlocks":["Információ.","Tessék mondani,","melyik pályaudvarra","érkezik a vonat Vársóból?","A Keleti pályaudvarra.","Hányadik vágányra érkezik?","Az ötödikre.","Melyik vágányról","indul a vonat Szegedre?","A második vágányról."],"paralelBlocks":["Informácia.","Prosím vás,","na ktoré nádražie","prichádza vlak z Varšavy?","Na Východné nádražie.","Na koľkiatu koľaj prichádza?","Na piatu.","Z ktorej koľaje","odchádza vlak do Szegedu?","Z druhej koľaje."],"sprites":{"block_0":[100,1650],"block_1":[1750,1020],"block_2":[2770,1130],"block_3":[3900,1790],"block_4":[5690,2310],"block_5":[8100,2010],"block_6":[10110,1480],"block_7":[11590,1760],"block_8":[13350,1260],"block_9":[14610,1990]},"playMode":"1","pause":0,"repeat":0,"pauseRepeat":0,"direction":false,"script":false,"trans":false}';
var testObj = JSON.parse(lectureString);
const playViewController = new PlayViewController(testObj);
navigationController.present(playViewController);

if (playViewController.settings == testObj) {
    testsList.append('<li class="collection-item">Test 8 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 8 - NOT OK</li>');
}

if (playViewController.player.actualBlock == 0) {
    testsList.append('<li class="collection-item">Test 9 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 9 - NOT OK</li>');
}

playViewController.nextBlock();
playViewController.nextBlock()
if (playViewController.player.actualBlock == 2) {
    testsList.append('<li class="collection-item">Test 10 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 10 - NOT OK</li>');
}

playViewController.pauseLecture();
if (playViewController.player.actualBlock == 2) {
    testsList.append('<li class="collection-item">Test 11 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 11 - NOT OK</li>');
}

playViewController.previousBlock()
if (playViewController.player.actualBlock == 1) {
    testsList.append('<li class="collection-item">Test 12 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 12 - NOT OK</li>');
}

playViewController.pauseLecture();
if (playViewController.player.actualBlock == 1) {
    testsList.append('<li class="collection-item">Test 13 - OK</li>');
} else {
    testsList.append('<li class="collection-item">Test 13 - NOT OK</li>');
}









//
