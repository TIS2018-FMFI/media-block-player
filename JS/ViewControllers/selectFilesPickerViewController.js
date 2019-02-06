/// Class for pick audio,script file,paralel script file and synchornization file
/// for needed for next optional settings. Then you are able go to setting class.
class SelectFilesPickerViewController extends ViewController {

    constructor() {
        super();
        this.scriptFileName;
        this.scriptBlocks;
        this.paralelFileName;
        this.paralelBlocks;
        this.sound;
        this.syncFile;
    }

    renderHtml(html) {
        const htmlView = `
            <section id="SelectFilesPickerViewController" class="container">
                <div class="row row-50 m12">
                    <div class="col s12">
                        <h1 class="center">Choose Media From Disk</h1>
                        <p id="file1"></p>
                        <p id="file2"></p>
                        <p id="file3"></p>
                        <p id="file4"></p>
                    </div>
                </div>
                <div class="row row-50">
                    <div class="col s6">
                        <form action="#">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span><i class="material-icons right">audiotrack</i>Audio</span>
                                    <input id="audio-file-picker" type="file" accept=".wav, .mp3" />
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col s6">
                        <form action="#">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span><i class="material-icons right">translate description</i>Original Script</span>
                                    <input id="rewritten-audio-file-picker" type="file" accept=".txt" />
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col s6">
                        <form action="#">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span><i class="material-icons right">description</i>Sync File</span>
                                    <input id="sync-file-picker" type="file" accept=".mbpsf" />
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col s6">
                        <form action="#">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span><i class="material-icons right">translate description</i>Parallel translation</span>
                                    <input id="paralel-rewritten-audio-file-picker" type="file" accept=".txt" />
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row row-50">
                    <div class="col s12 center">
                        <a id="select-button" class="waves-effect waves-light btn-large disabled">Select</a>
                    </div>
                </div>
                <div class="row row-50">
                    <div class="col s12">
                        <a class="btn-small right" href="index.html">Back to my menu</a>
                    </div>
                </div>
            </section>
        `;
        super.renderHtml(htmlView);
    }

    setupProperties() {
        this.audioFilePicker = $('#audio-file-picker');
        this.rewrittenAudioFilePicker = $('#rewritten-audio-file-picker');
        this.paralelRewrittenAudioFilePicker = $('#paralel-rewritten-audio-file-picker');
        this.syncFilePicker = $('#sync-file-picker');
        this.selectButton = $('#select-button');
    }

    setupEventListeners() {
        this.audioPickerValueChanged = this.audioPickerValueChanged.bind(this);
        this.rewrittenAudioPickerValueChanged = this.rewrittenAudioPickerValueChanged.bind(this);
        this.syncFilePickerValueChanged = this.syncFilePickerValueChanged.bind(this);
        this.paralelRewrittenAudioPickerValueChanged = this.paralelRewrittenAudioPickerValueChanged.bind(this);
        this.selectButtonClicked = this.selectButtonClicked.bind(this);

        this.audioFilePicker.change(this.audioPickerValueChanged);
        this.rewrittenAudioFilePicker.change(this.rewrittenAudioPickerValueChanged);
        this.syncFilePicker.change(this.syncFilePickerValueChanged);
        this.paralelRewrittenAudioFilePicker.change(this.paralelRewrittenAudioPickerValueChanged);
        this.selectButton.on('click', this.selectButtonClicked);
    }

    /// After selecting the options for lecture this method shows the next page.
    presentNextController() {
        var audio = new Howl({
            src: this.sound,
            sprite: this.getSpritesFromJSON(this.syncFile)
        });

        var obj = {
          lecture_title: "Article from local disc",
          audio: audio,
          scriptBlocks: this.scriptBlocks,
          paralelBlocks: this.paralelBlocks,
          syncFile: this.syncFile,
          sprites: this.getSpritesFromJSON(this.syncFile),
          translations: null
        }
        const settingsViewController = new SettingsViewController(obj, true);
        this.navigationController.present(settingsViewController);
    }

    // Private Methods

    syncFilePickerValueChanged(){
      const syncFile = this.syncFilePicker[0].files[0];
      if (syncFile === undefined) {
          this.setupSelectButton();
          return;
      }
      const fileReader = new FileReader();
      fileReader.onload = () => {
          const text = fileReader.result;
          this.syncFile = JSON.parse(text);
      };
      fileReader.readAsText(syncFile);
      this.setupSelectButton();
    }

    paralelRewrittenAudioPickerValueChanged(){
        const textFile = this.paralelRewrittenAudioFilePicker[0].files[0];
        if (textFile === undefined) {
            this.setupSelectButton();
            return;
        }
        const fileReader = new FileReader();
        fileReader.onload = () => {
            const text = fileReader.result;
            this.paralelBlocks = this.parseBlocks(text);
        };
        fileReader.readAsText(textFile);
        this.setupSelectButton();
    }

    audioPickerValueChanged() {
        const audioFile = this.audioFilePicker[0].files[0];
        if (audioFile === undefined) {
            this.setupSelectButton();
            return;
        }
        this.scriptFileName = audioFile.name.split('.').slice(0, -1).join('.');
        this.getBase64(audioFile).then( data => {
            this.sound = data;
        });
        this.setupSelectButton();
    }

    rewrittenAudioPickerValueChanged() {
        const textFile = this.rewrittenAudioFilePicker[0].files[0];
        if (textFile === undefined) {
            this.setupSelectButton();
            return;
        }
        const fileReader = new FileReader();
        fileReader.onload = () => {
            const text = fileReader.result;
            this.scriptBlocks = this.parseBlocks(text);
        };
        fileReader.readAsText(textFile);
        this.setupSelectButton();
    }

    setupSelectButton() {
        const audioPickerHasFile = this.audioFilePicker[0].files[0] !== undefined;
        const rewrittenAudioPickerHasFile = this.rewrittenAudioFilePicker[0].files[0] !== undefined;
        const paralelRewrittenAudioPickerHasFile = this.paralelRewrittenAudioFilePicker[0].files[0] !== undefined;
        const syncPickerHasFile = this.syncFilePicker[0].files[0] !== undefined;
        const shouldEnableSelectButton = audioPickerHasFile && rewrittenAudioPickerHasFile && syncPickerHasFile;
        if (shouldEnableSelectButton) {
            this.selectButton.removeClass("disabled");
        } else {
            this.selectButton.addClass("disabled");
        }
    }

    selectButtonClicked() {
        this.presentNextController();
    }

    // Helper Methods

    /// Encode file to base64 encoding
    /// @param file - file you want to encode
    /// @return - base64 string of file
    getBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });
    }

    /// This method is parsing text into blocks splitted by "|" character
    /// @param text - text you want to split
    /// @return method returns array of strings (blocks)
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
    getSpritesFromJSON(syncBlocks){
      var sprites = {};

      var currPos = 0;
      var currIndex = 0
      syncBlocks['blocks'].forEach((block, i) => {
        let currTime = block * 1000;
        if (!syncBlocks['skips'].includes(block)){
          sprites["block_" + currIndex] = [currPos, currTime - currPos];
          currIndex++;
        }
        currPos = currTime;
      });

      return sprites;
    }

}
