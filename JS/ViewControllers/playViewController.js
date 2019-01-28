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

        if (this.settings['playMode'] == "3" || this.settings['playMode'] == "5"){
          controlBtns = `
            <button class="btn-large" id="play-sound-btn">
              <i class="material-icons">check_circle</i>
            </button>
            `;
        }
        else{
          controlBtns = `
            <button class="btn-large" id="play">
              <i class="material-icons">play_arrow</i>
            </button>

            <button class="btn-large" id="pause">
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
          <div class="row row-50">
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

        if (this.settings['playMode'] == '3' || this.settings['playMode'] == '5') {
            this.checkBtn = $('#play-sound-btn');
        }
    }

    setupEventListeners() {
        this.playLecture = this.playLecture.bind(this);
        this.nextBlock = this.nextBlock.bind(this);
        this.previousBlock = this.previousBlock.bind(this);
        this.pauseLecture = this.pauseLecture.bind(this);
        this.backToSettings = this.backToSettings.bind(this);

        if (this.settings['playMode'] == "3" || this.settings['playMode'] == "5") {
            this.checkAnswer = this.checkAnswer.bind(this);
        }

        this.playBtn.on('click', this.playLecture);
        this.pauseBtn.on('click', this.pauseLecture);
        this.nextBtn.on('click', this.nextBlock);
        this.backBtn.on('click', this.previousBlock);
        this.backToSettingsBtn.on('click', this.backToSettings);

        if (this.settings['playMode'] == "3" || this.settings['playMode'] == "5") {
            this.checkBtn.on('click', this.checkAnswer);
        }
    }

    viewDidLoad() {
      if (this.settings['playMode'] == "3" || this.settings['playMode'] == "5"){
        this.player.play();
      }
    }

    // private Methods
    backToSettings() {
        this.player.sound.stop();
        this.player.clearAllTimeOuts();
        this.navigationController.presentLastShownController();
    }

    playLecture() {
        if (this.player.paused) {
            this.player.paused = false;
        }
        this.player.play();
    }

    pauseLecture() {
        this.player.pauseSound();
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
