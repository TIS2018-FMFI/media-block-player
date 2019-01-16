// Class for representing settings page for the application.
// In this step the application is a collecting information about
// the playing options from the user.

class SettingsViewController extends ViewController {
    constructor(lecture, local) {
        super();
        this.lecture = lecture;
        this.lectureData;
        this.local = local;

    }

    renderHtml(html) {
        var langOptions = "";
        if (this.lecture.translations != null) {
            langOptions += "<select name='translation-lang' id='translation-lang'>";
            langOptions += "<option value=''>Choose your option</option>";
            for (var lang in this.lecture.translations) {
                langOptions += "<option value='" + this.lecture.translations[lang] + "'>" + lang + "</option>";
            }
            langOptions += "</select>";
        } else {
            langOptions += "<select name='translation-lang' id='translation-lang' class='disabled' disabled>";
            langOptions += "<option value=''>Choose your option</option>";
            langOptions += "</select>";
        }


        const htmlView = `
        <div class="container">
            <div class="row flex">
                <div class="col s12 l9">
                    <h4 class="">` + this.lecture.lecture_title + `</h4>
                </div>
                <div class="col s12 l3">
                  <div class="valign-wrapper">
                    <p>
                      <a class="waves-effect waves-light btn-small" id="back-to-lecture-select"><i class="material-icons left">arrow_back</i>Change media</a>
                    </p>
                  </div>
                </div>
            </div>
            <div class="row">
              <div class="col s12 offset-l3 l6">
                <div class="input-field col s12">
                  <select id="play-mode" name="play-mode">
                    <option value="default">Choose your option</option>
                    <option value="1">First listening</option>
                    <option value="2">Pronounciation training</option>
                    <option value="3">Pronounciation check</option>
                    <option value="4">Continual echoing</option>
                    <option value="5">Speaking check</option>
                  </select>
                  <label>Play settings</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col s12">
                <div class="divider"></div>
              </div>
              <div class="col s12 l6">
                <div class="row row-50">
                  <div class="col s10 offset-s1">
                    <div class="input-field col s12">
                      <input id="pause" type="number" min="0" max="20" name="pause" class="validate">
                      <label for="pause">Pause (sec)</label>
                    </div>
                    <div class="input-field col s12">
                      <input id="repeat" type="number" min="0" max="20" name="repeat" class="validate">
                      <label for="repeat">Repeat</label>
                    </div>
                    <div class="input-field col s12">
                      <input id="pause_repeat" type="number" min="0" max="20" name="pause_repeat" class="validate">
                      <label for="pause_repeat">Pause repeat (sec)</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col s12 l6">
                <div class="row row-50">
                  <div class="col s10 offset-s1">
                        <div class="row flex">
                          <div class="col s4">
                            <p>Direction</p>
                          </div>
                          <div class="col s8">
                            <div class="valign-wrapper" style="height:100%">
                              <!-- Switch -->
                              <div class="switch">
                                <label>
                                  Forward
                                  <input type="checkbox" name="direction" id="direction">
                                  <span class="lever"></span>
                                  Random
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col s4">
                            <p>Script</p>
                          </div>
                          <div class="col s8">
                            <p>
                              <label>
                                <input type="checkbox" class="filled-in" name="script" id="script-check"/>
                                <span></span>
                              </label>
                            </p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col s4">
                            <p>Translation</p>
                          </div>
                          <div class="col s8">
                            <p>
                              <label>
                                <input type="checkbox" class="filled-in" name="translation" id="trans-check"/>
                                <span></span>
                              </label>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            <div class="row">
              <div class="col s12">
                <div class="divider"></div>
              </div>
            </div>
            <div class="row">
              <div class="col s12 offset-l3 l6">
                <div class="input-field col s12">
                ` + langOptions + `
                  <label>Paralel text</label>
                </div>
              </div>
              <div class="col s12 center-align">
                <a class="waves-effect waves-light btn-large" id="start-lecture"><i class="material-icons right">play_arrow</i>Play</a>
              </div>
            </div>
        </div>
        `;
        super.renderHtml(htmlView);

        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    }

    setupProperties() {
        this.backBtn = $('#back-to-lecture-select');
        this.startLectureBtn = $('#start-lecture');
        this.playMode = $('#play-mode');
        this.transLang = $('#translation-lang');
        this.script = $('#script-check');
        this.paralel = $('#trans-check');
        this.direction = $('#direction');
        this.pause = $('#pause');
        this.repeat = $("#repeat");
        this.pauseRepeat = $("#pause_repeat");
    }

    setupEventListeners() {
        this.onBackBtnClick = this.onBackBtnClick.bind(this);
        this.onStartLectureClick = this.onStartLectureClick.bind(this);
        this.setupSettingsOnPlayModeChange = this.setupSettingsOnPlayModeChange.bind(this);

        this.startLectureBtn.on('click', this.onStartLectureClick);
        this.backBtn.on('click', this.onBackBtnClick);
        this.playMode.on('change', this.setupSettingsOnPlayModeChange);
    }

    // After selecting the options for lecture this method shows the next page.
    presentNextController() {
        this.navigationController.present(new PlayViewController(this.lectureData));
    }

    // private methots

    onBackBtnClick() {
        this.navigationController.presentLastShownController();
    }

    onStartLectureClick() {
        var playMode = this.playMode.val();
        var showText = this.script.prop('checked');
        var showParalelText = this.paralel.prop('checked');

        if (this.local) {
            this.lectureData = {
                local: true,
                audio: this.lecture.audio,
                txtBlocks: this.lecture.scriptBlocks,
                paralelBlocks: this.lecture.paralelBlocks,
                sprites: this.getSpritesFromJSON(this.lecture.syncFile),
                playMode: playMode,
                pause: this.pause.val() == "" ? 0 : this.pause.val(),
                repeat: this.repeat.val() == "" ? 0 : this.repeat.val(),
                pauseRepeat: this.pauseRepeat.val() == "" ? 0 : this.pauseRepeat.val(),
                direction: this.direction.prop('checked'),
                script: this.script.prop('checked'),
                trans: this.paralel.prop('checked')
            }
        } else {
            this.lectureData = {
                local: false,
                audio: this.lecture["audio_file_link"],
                txtBlocks: this.readOrginalText(this.lecture["original_text_link"]),
                paralelBlocks: this.transLang.val() == "" ? null : this.readParalelText(this.transLang.val()),
                sprites: this.readSyncFile(this.lecture["sync_file_link"]),
                playMode: playMode,
                pause: this.pause.val() == "" ? 0 : this.pause.val(),
                repeat: this.repeat.val() == "" ? 0 : this.repeat.val(),
                pauseRepeat: this.pauseRepeat.val() == "" ? 0 : this.pauseRepeat.val(),
                direction: this.direction.prop('checked'),
                script: this.script.prop('checked'),
                trans: this.paralel.prop('checked')
            }
        }
        this.presentNextController();
    }

    setupSettingsOnPlayModeChange(screen) {;
        var val = this.playMode.val();

        if (val == "1") {
            this.script.prop('checked', false);
            this.paralel.prop('checked', true);
            this.direction.prop('checked', false);
            this.pause.val("0");
            this.repeat.val("0");
            this.pauseRepeat.val("0");

            this.pause.next("label").addClass('active');
            this.repeat.next("label").addClass('active');
            this.pauseRepeat.next("label").addClass('active');

            this.script.prop('disabled', true);
            this.paralel.prop('disabled', true);
            this.direction.prop('disabled', true);
            this.pause.prop('disabled', true);
            this.repeat.prop('disabled', true);
            this.pauseRepeat.prop('disabled', true);
        } else if (val == "2") {
            this.script.prop('checked', true);
            this.paralel.prop('checked', true);
            this.direction.prop('checked', false);
            this.pause.val("0");
            this.repeat.val("1");
            this.pauseRepeat.val("2");

            this.pause.next("label").addClass('active');
            this.repeat.next("label").addClass('active');
            this.pauseRepeat.next("label").addClass('active');

            this.script.prop('disabled', true);
            this.paralel.prop('disabled', true);
            this.direction.prop('disabled', true);
            this.pause.prop('disabled', true);
            this.repeat.prop('disabled', true);
            this.pauseRepeat.prop('disabled', true);
        } else if (val == "3") {
            this.script.prop('checked', true);
            this.paralel.prop('checked', true);
            this.direction.prop('checked', false);
            this.pause.val("0");
            this.repeat.val("0");
            this.pauseRepeat.val("0");

            this.pause.next("label").addClass('active');
            this.repeat.next("label").addClass('active');
            this.pauseRepeat.next("label").addClass('active');

            this.script.prop('disabled', true);
            this.paralel.prop('disabled', true);
            this.direction.prop('disabled', false);
            this.pause.prop('disabled', true);
            this.repeat.prop('disabled', true);
            this.pauseRepeat.prop('disabled', true);
        } else if (val == "4") {
            this.script.prop('checked', true);
            this.paralel.prop('checked', true);
            this.direction.prop('checked', false);
            this.pause.val("0");
            this.repeat.val("0");
            this.pauseRepeat.val("0");

            this.pause.next("label").addClass('active');
            this.repeat.next("label").addClass('active');
            this.pauseRepeat.next("label").addClass('active');

            this.script.prop('disabled', true);
            this.paralel.prop('disabled', true);
            this.direction.prop('disabled', true);
            this.pause.prop('disabled', true);
            this.repeat.prop('disabled', true);
            this.pauseRepeat.prop('disabled', true);
        } else if (val == "5") {
            this.script.prop('checked', true);
            this.paralel.prop('checked', true);
            this.direction.prop('checked', false);
            this.pause.val("0");
            this.repeat.val("0");
            this.pauseRepeat.val("0");

            this.pause.next("label").addClass('active');
            this.repeat.next("label").addClass('active');
            this.pauseRepeat.next("label").addClass('active');

            this.script.prop('disabled', true);
            this.paralel.prop('disabled', true);
            this.direction.prop('disabled', false);
            this.pause.prop('disabled', true);
            this.repeat.prop('disabled', true);
            this.pauseRepeat.prop('disabled', true);
        } else {
            this.pause.val("");
            this.repeat.val("");
            this.pauseRepeat.val("");

            this.pause.next("label").removeClass('active');
            this.repeat.next("label").removeClass('active');
            this.pauseRepeat.next("label").removeClass('active');

            this.script.prop('disabled', false);
            this.paralel.prop('disabled', false);
            this.direction.prop('disabled', false);
            this.pause.prop('disabled', false);
            this.repeat.prop('disabled', false);
            this.pauseRepeat.prop('disabled', false);
        }
    }

    // This method is reading the sync file from the server, than
    // converting to audio sprite record, which will use the
    // module for audio.
    readSyncFile(link) {
        var txt = "";
        $.ajax({
            url: link,
            async: false,
            type: "get",
            success: function(data) {
                txt = data;
            }
        });

        var syncBlocks = JSON.parse(txt);

        return this.getSpritesFromJSON(syncBlocks)
    }

    getSpritesFromJSON(syncBlocks) {
        var sprites = {};

        var currPos = 0;
        var currIndex = 0
        syncBlocks['blocks'].forEach((block, i) => {
            let currTime = block * 1000;
            if (!syncBlocks['skips'].includes(block)) {
                sprites["block_" + currIndex] = [currPos, currTime - currPos];
                currIndex++;
            }
            currPos = currTime;
        });

        return sprites;
    }

    // This method reads the text file from the server
    // which contains the original text of audio.
    readOrginalText(link) {
        var txt = "";
        $.ajax({
            url: link,
            async: false,
            type: "get",
            success: function(data) {
                txt = data;
            }
        });
        return this.parseBlocks(txt);
    }

    // This method reads the text file from the server
    // which contains the translated text of audio.
    readParalelText(link) {
        var txt = "";
        $.ajax({
            url: link,
            async: false,
            type: "get",
            success: function(data) {
                txt = data;
            }
        });
        return this.parseBlocks(txt);
    }

    // This method is parsing text into blocks splitted by "|" character
    // @param text - text you want to split
    // @return method returns array of strings (blocks)
    parseBlocks(text) {
        var result = []
        const parsedBlocks = text.split("|");
        parsedBlocks.forEach(block => {
            var trimmed = block.trim();
            if (trimmed.length > 0) {
                result.push(trimmed);
            }
        });
        return result;
    }
}
