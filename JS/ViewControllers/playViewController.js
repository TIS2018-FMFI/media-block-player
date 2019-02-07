// This class is represeing the audio player GUI.

class PlayViewController extends ViewController {
    constructor(settings) {
        super();
        this.settings = settings;

        // this var is representing the audio player.
        this.player = new Player(this.settings);

    }

    renderHtml(html) {
        var controlBtns = "";

        if (this.isCheckingActive()) {
            controlBtns += `<button class="btn-large" id="play-sound-btn">`
            if (this.settings['pause'] == "99") {
                controlBtns += `<i class="material-icons">play_arrow</i>`;
            } else {
                controlBtns += `<i class="material-icons">check_circle</i>`;
            }
            controlBtns += `</button> `
        } else {
            controlBtns = `
            <button class="btn-large" id="play">
              <i class="material-icons">play_arrow</i>
            </button>

            <button class="btn-large" id="pause" style="display: none;">
              <i class="material-icons">pause</i>
            </button>
            `;
        }

        const htmlView = `
        <div class="container">
          <div class="row row-50">
            <div class="col m12 right-align">
              <a class="waves-effect waves-light btn-small" id="back-to-setting"><i class="material-icons left">arrow_back</i>Change settings</a>
            </div>
          </div>
          <div class="row row-100">
            <div class="col m12 center">
              <div id="original-text">

              </div>
            </div>
          </div>
          <div class="row row-50">
            <div class="col m12 center">
              <div id="paralel-text">

              </div>
            </div>
          </div>
          <div class="row">
            <div class="col m12 center">
              <div class="progress">
                  <div class="determinate" id="progress-bar" style="width: 0%"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col m12 center">
              <div id="control-btns">
                  <button class="btn" id="back">
                    <i class="material-icons">fast_rewind</i>
                  </button>

                  ` + controlBtns + `

                  <button class="btn" id="forward">
                    <i class="material-icons">fast_forward</i>
                  </button>
              </div>
            </div>
          </div>
        </div>
        `;

        super.renderHtml(htmlView);
    }

    setupProperties() {
        this.playBtn = $('#play');
        this.pauseBtn = $('#pause');
        this.nextBtn = $('#forward');
        this.backBtn = $('#back')
        this.backToSettingsBtn = $('#back-to-setting');
        this.progressBar = $('#progress-bar');

        if (this.isCheckingActive()) {
            this.checkBtn = $('#play-sound-btn');
        }
    }

    setupEventListeners() {
        this.playLecture = this.playLecture.bind(this);
        this.nextBlock = this.nextBlock.bind(this);
        this.previousBlock = this.previousBlock.bind(this);
        this.pauseLecture = this.pauseLecture.bind(this);
        this.backToSettings = this.backToSettings.bind(this);

        if (this.isCheckingActive()) {
            this.checkAnswer = this.checkAnswer.bind(this);
        }

        this.playBtn.on('click', this.playLecture);
        this.pauseBtn.on('click', this.pauseLecture);
        this.nextBtn.on('click', this.nextBlock);
        this.backBtn.on('click', this.previousBlock);
        this.backToSettingsBtn.on('click', this.backToSettings);

        if (this.isCheckingActive()) {
            this.checkBtn.on('click', this.checkAnswer);
        }

        this.setUpDefaultBtnOrder = this.setUpDefaultBtnOrder.bind(this);
        $(document).on('lectureEndedEvent', this.setUpDefaultBtnOrder);
        this.changeProgressBarWidth = this.changeProgressBarWidth.bind(this);
        $(document).on('progressBarChangeEvent', this.changeProgressBarWidth);

    }

    viewDidLoad() {
        if (this.isCheckingActive()) {
            this.player.play();
        }
    }

    // private Methods
    isCheckingActive() {
        if (this.settings['playMode'] == "3" || this.settings['playMode'] == "5" || this.settings['pause'] == "99") {
            return true;
        }

        return false;
    }

    setUpDefaultBtnOrder() {
        this.playBtn.css('display', 'inline-block');
        this.pauseBtn.css('display', 'none');
    }

    changeProgressBarWidth() {
        var newWidth = ((this.player.actualBlock + 1) / this.player.totalBlocks) * 100;
        newWidth = newWidth > 100 ? 100 : newWidth;
        this.progressBar.css('width', '' + newWidth + '%');
    }

    backToSettings() {
        this.player.sound.stop();
        this.player.sound.off();
        this.player.clearAllTimeOuts();
        this.navigationController.presentLastShownController();
    }

    playLecture() {
        if (this.player.paused) {
            this.player.paused = false;
        }
        this.player.play();
        this.playBtn.css('display', 'none');
        this.pauseBtn.css('display', 'inline-block');
    }

    pauseLecture() {
        this.player.pauseSound();
        this.playBtn.css('display', 'inline-block');
        this.pauseBtn.css('display', 'none');
    }

    nextBlock() {
        this.player.next();
    }

    previousBlock() {
        this.player.back();
    }

    checkAnswer() {
        this.player.playSound();
    }
}
