/// This class try to get the available languages from the online library.
/// Upon successful request, the user get resposne and choose the language
/// and send language, language abbreviation, URL address
/// on the online library to another class, librarySelectMediaLanguage.
class LibraryChooseMediaLanguageViewController extends ViewController{

    constructor() {
        super();
        this.shortLanguage;
        this.language;
        this.availableLanguages;
        this.languagePicker;
        this.url = "MBPLibrary/api.php";
    }

    renderHtml(html) {
        const htmlView = `
            <section id="LibraryChooseMediaLanguageViewController" class="container">
                <div class="row row-50 m12">
                    <div class="col s12">
                        <h1 class="center">Choose Article Language</h1>
                    </div>
                </div>
                <div class="row row-25 m12">
                    <form action="#">
                        <select id="select-language" class="browser-default">
                            <option value="" selected></option>
                        </select>
                    </form>
                </div>

                <div class="row row-50">
                    <div class="col s12 center">
                        <h6><p id="error_alert"></p></h6>
                    </div>
                </div>
                <div class="row row-100">
                    <div class="col s12">
                        <a class="btn-small right" href="index.html">Back to my menu</a>
                    </div>
                </div>
            </section>
        `;
        super.renderHtml(htmlView);
      }

      setupProperties() {
          this.languagePicker =  $('#select-language');
      }

      viewDidLoad() {
          this.getAvailableLanguage();
      }

      setupEventListeners() {
          this.languageValueChanged = this.languageValueChanged.bind(this);
          this.languagePicker.change(this.languageValueChanged);
      }

      presentNextController() {
          const librarySelectMediaLanguageViewController = new LibrarySelectMediaLanguageViewController();
          librarySelectMediaLanguageViewController.language = this.language;
          librarySelectMediaLanguageViewController.shortLanguage = this.shortLanguage;
          librarySelectMediaLanguageViewController.url = this.url;
          this.navigationController.present(librarySelectMediaLanguageViewController);
      }

      /// This method try to set a selected language from listchoice
      /// and if atribute this.language is not null or empty string
      /// then invoke method this.presentNextController().
      languageValueChanged() {
          this.language = this.languagePicker[0].options[this.languagePicker[0].selectedIndex].text;
          this.shortLanguage = this.languagePicker[0].options[this.languagePicker[0].selectedIndex].value;
          if (!(this.isEmpty(this.language))) {
              this.presentNextController();
          }
      }

      // Private Methods

      /// This method checking if a string or another input is empty.
      /// It means, that is null or undefined.
      /// @param input - input, that you want to check
      /// @return - true or false
      isEmpty(input) {
          return (!input || 0 === input.length || input == undefined);
      }

      /// This method send request and get response with available languages from online library.
      /// And response is set in attribute this.availableLanguages.
      getAvailableLanguage() {
          var tmp = undefined;
            $.ajax({
               type: 'POST',
               url: this.url,
               crossOrigin: true,
               data: {
                   action: "get-avail-lang"
               },
               xhrFields: {
                 withCredentials: false
               },
               headers: {
                 "x-requested-with": true
               },
               dataType: 'json',
               async: false,
               success: function(data) {
                   tmp = data;
                   const attrValue = data["data"];
                   const selectLanguageElement = document.getElementById("select-language");
                   for (var attr in attrValue) {
                       var option = document.createElement("option");
                       option.text = attrValue[attr][1];
                       option.value = attrValue[attr][2];
                       selectLanguageElement.add(option);
                   }
               },
               error: function (XMLHttpRequest, textStatus, errorThrown) {
                  document.getElementById("error_alert").innerHTML = "Failed to connect to the server!";
               }
            });
            this.availableLanguages = tmp;
      }
}
