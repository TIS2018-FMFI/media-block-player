
const navigationController = new NavigationController();
const filePickerViewController = new FilesPickerViewController();
navigationController.present( filePickerViewController );
$('#root').html('<ul id="tests">');
const testsList = $('#tests');

// NAVIGATION CONTROLLER TESTS

// TEST 1
if (navigationController.controllers.length === 1) {
    testsList.append('<li>Test1 - OK</li>');
} else {
    testsList.append('<li>Test1 - NOK</li>');
}

// TEST 2
if (navigationController.controllers[0] === filePickerViewController) {
    testsList.append('<li>Test2 - OK</li>');
} else {
    testsList.append('<li>Test2 - NOK</li>');
}

// TEST 3
if (navigationController.actualController === filePickerViewController) {
    testsList.append('<li>Test3 - OK</li>');
} else {
    testsList.append('<li>Test3 - NOK</li>');
}

// TEST 4
if (filePickerViewController.navigationController === navigationController) {
    testsList.append('<li>Test4 - OK</li>');
} else {
    testsList.append('<li>Test4 - NOK</li>');
}

// FILES PICKER VIEW CONTROLLER TESTS

// TEST 5
var parsText = "withoutSeparator";
var result = filePickerViewController.parseBlocks(parsText);

if (result.length == 1) {
    testsList.append('<li>Test5 - OK</li>');
} else {
    testsList.append('<li>Test5 - NOK</li>');
}

// TEST 5.2
if (result[0] === parsText) {
    testsList.append('<li>Test5.2 - OK</li>');
} else {
    testsList.append('<li>Test5.2 - NOK</li>');
}

// TEST 6
parsText = "one|separator";
result = filePickerViewController.parseBlocks(parsText);

if (result.length === 2) {
    testsList.append('<li>Test6 - OK</li>');
} else {
    testsList.append('<li>Test6 - NOK</li>');
}

// TEST 6.2
if (result[0] === "one" && result[1] === "separator") {
    testsList.append('<li>Test6.2 - OK</li>');
} else {
    testsList.append('<li>Test6.2 - NOK</li>');
}

// TEST 7
parsText = "";
result = filePickerViewController.parseBlocks(parsText);

if (result.length === 0) {
    testsList.append('<li>Test7 - OK</li>');
} else {
    testsList.append('<li>Test7 - NOK</li>');
}

// TEST 8
parsText = "||";
result = filePickerViewController.parseBlocks(parsText);

if (result.length === 0) {
    testsList.append('<li>Test8 - OK</li>');
} else {
    testsList.append('<li>Test8 - NOK</li>');
}
