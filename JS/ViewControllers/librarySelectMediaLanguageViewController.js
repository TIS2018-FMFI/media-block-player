/// This class have a methods to send request on the online library
/// and get to available lessons through the choosen lesson language.
/// Then you select one lesson and you are able go to setting class.
class LibrarySelectMediaLanguageViewController extends ViewController {

    constructor() {
        super();
        this.language;
        this.shortLanguage;
        this.url;

        this.lesson;
        this.listOfLessons;
        this.table;
    }

    renderHtml(html) {
        const htmlView = `
            <section id="LibrarySelectMediaLanguageViewController" class="container">
                <div class="row row-80 m12">
                    <div class="col s12">
                        <h1 class="center">${this.language} Articles</h1>
                    </div>
                </div>
                <div class="row row m12">
                    <form action="#">
                        <table id="avail-lecture" class='table'>
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Article name</th>
                              <th>Article Language</th>
                              <th>Difficulty</th>
                              <th>Description</th>
                              <th>Media files</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody id="bodyTable">
                            <tr>
                            </tr>
                          </tbody>
                        </table>
                    </form>
                </div>
                <div class="row row-20">
                    <div class="col s12 center">
                        <h6><p id="error_alert"></p></h6>
                    </div>
                </div>
                <div class="row row-20">
                    <div class="col s12">
                        <a class="btn-small left" href="media_select_from_library.html">Change Article language</a>
                        <a class="btn-small right" href="index.html">Back to my menu</a>
                    </div>
                </div>
            </section>
        `;
        super.renderHtml(htmlView);
      }

      setupProperties() {
          this.selectArticleFromTable = $('#avail-lecture');
          this.table = document.getElementById("avail-lecture");
      }

      viewDidLoad() {
          this.getAvailableLessons();
          this.setAvailableLessons();
      }

      setupEventListeners() {
           this.selectArticleFromTableClicked = this.selectArticleFromTableClicked.bind(this);
           this.selectArticleFromTable.on('click', this.selectArticleFromTableClicked);
      }

      presentNextController() {
          const settingsViewController = new SettingsViewController(this.lesson, false);
          this.navigationController.present(settingsViewController);
      }

      selectArticleFromTableClicked() {
          this.getSelectedLesson();
      }

      // Private Methods

      /// This method send Ajax request and get response with available lessons from online library.
      /// And response is set in attribute this.listOfLessons.
      getAvailableLessons() {
        let mbpPrimaryLang = this.shortLanguage;
        var tmp = undefined;
            $.ajax({
                type: 'POST',
                url: this.url,
                data: {
                    action: "get-lectures-in-lang",
                    primaryLang: mbpPrimaryLang,
                },
                dataType: 'json',
                async: false,
                success:function(data) {
                    tmp = data;
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    document.getElementById("error_alert").innerHTML = "Failed to connect to the server!";
                }
            });
            this.listOfLessons = tmp;
      }

      /// This method set available lessons from attribute this.listOfLessons to the HTML element table.
      setAvailableLessons() {
          for (var line in this.listOfLessons) {
              var row = this.table.insertRow();
              var id = row.insertCell(0);
              var lectureName = row.insertCell(1);
              var lectureLanguage = row.insertCell(2);
              var difficulty = row.insertCell(3);
              var description = row.insertCell(4);
              var mediaFiles = row.insertCell(5);
              var mediaSelectButton = row.insertCell(6);
              lectureLanguage.innerHTML = this.language;
              for (var key in this.listOfLessons[line]) {
                  if (key == "id") {
                      id.innerHTML = this.listOfLessons[line][key];
                      mediaSelectButton.innerHTML =
                      `<label>
                        <input id="`+this.listOfLessons[line][key]+`" class="with-gap" name="group1" type="radio"  />
                        <span></span>
                      </label>`;
                  }
                  else if (key == "lecture_title") {
                      lectureName.innerHTML = this.listOfLessons[line][key];
                  }
                  else if (key == "description") {
                      description.innerHTML = this.listOfLessons[line][key];
                  }
                  else if (key == "level") {
                      if (this.listOfLessons[line][key] == '1'){
                        difficulty.innerHTML = "A";
                      }
                      else if (this.listOfLessons[line][key] == '2'){
                        difficulty.innerHTML = "B";
                      }
                      else{
                        difficulty.innerHTML = "C";
                      }

                  }
                  else if (key == "audio_file_link") {
                      var pom = this.listOfLessons[line][key].split("/");
                      mediaFiles.innerHTML = pom[pom.length-1]+"<br>";
                  }
                  else if (key == "original_text_link") {
                      var pom = this.listOfLessons[line][key].split("/");
                      mediaFiles.innerHTML += pom[pom.length-1]+"<br>";
                  }
                  else if (key == "sync_file_link") {
                      var pom = this.listOfLessons[line][key].split("/");
                      mediaFiles.innerHTML += pom[pom.length-1]+"<br>";
                  }
                  else if (key == "translations") {
                      var trans = this.listOfLessons[line][key];
                      for (var tra in trans){
                          var pom = trans[tra].split("/");
                          mediaFiles.innerHTML += pom[pom.length-1]+", ";
                      }
                  }
              }
          }
      }

      /// This method get lesson, which was selected in table, and set to attribute this.lesson.
      getSelectedLesson() {
          for (var line in this.listOfLessons) {
              if (document.getElementById(this.listOfLessons[line]["id"]).checked == true) {
                  if ((this.listOfLessons[line]["audio_file_link"] != "" && this.listOfLessons[line]["audio_file_link"] != undefined) &&
                      (this.listOfLessons[line]["original_text_link"] != "" && this.listOfLessons[line]["original_text_link"] != undefined) &&
                      (this.listOfLessons[line]["sync_file_link"] != "" && this.listOfLessons[line]["sync_file_link"] != undefined)) {
                        this.lesson = this.listOfLessons[line];
                        $("#error_alert").text("");
                        this.presentNextController();
                  }
                  else {
                    $("#error_alert").text("You choose invalid lecture!");
                  }
              }
          }
          $("#error_alert").text("You haven't choosen any lecture!");
      }

}
