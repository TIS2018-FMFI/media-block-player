/// Class for pick audio and script file for needed for creating
/// synchornization file. Then you are able go to creating class
class FilesPickerViewController extends ViewController {

    constructor() {
        super();

        this.fileName;
        this.sound;
        this.playbackSound;
        this.blocks;
    }

    renderHtml(html) {
        const htmlView = `
            <section id="FilesPickerViewController" class="container">
                <div class="row row-100 m12">
                    <div class="col s12">
                        <h1 class="center">Choose files for creating</h1>
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
                                    <span><i class="material-icons right">description</i>Original Script</span>
                                    <input id="rewritten-audio-file-picker" type="file" accept=".txt" />
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
                        <!--<button id="start-creating-button" disabled style="font-size:20px;">Start creating</button>-->
                        <a id="start-creating-button" class="waves-effect waves-light btn-large disabled">Start Creating</a>
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
        this.audioFilePicker = $('#audio-file-picker');
        this.rewrittenAudioFilePicker = $('#rewritten-audio-file-picker');
        this.startCreatingButton = $('#start-creating-button');
    }

    setupEventListeners() {
        this.audioPickerValueChanged = this.audioPickerValueChanged.bind(this);
        this.rewrittenAudioPickerValueChanged = this.rewrittenAudioPickerValueChanged.bind(this);
        this.creatingButtonClicked = this.creatingButtonClicked.bind(this);

        this.audioFilePicker.change(this.audioPickerValueChanged);
        this.rewrittenAudioFilePicker.change(this.rewrittenAudioPickerValueChanged);
        this.startCreatingButton.on('click', this.creatingButtonClicked);
    }

    presentNextController() {
        const syncFileCreateViewController = new SyncFileCreateViewController();
        syncFileCreateViewController.sound = this.sound;
        syncFileCreateViewController.playbackSound = this.playbackSound;
        syncFileCreateViewController.blocks = this.blocks;
        syncFileCreateViewController.fileName = this.fileName;
        this.navigationController.present(syncFileCreateViewController);
    }

    // Private Methods

    audioPickerValueChanged() {
        const audioFile = this.audioFilePicker[0].files[0];
        if (audioFile === undefined) {
            this.setupStartCreatingButton();
            return;
        }
        this.fileName = audioFile.name.split('.').slice(0, -1).join('.');
        this.getBase64(audioFile).then( data => {
            this.sound = new Howl({
                src: data
            });
            this.playbackSound = new Howl({
                src: data
            });
        });
        this.setupStartCreatingButton();
    }

    rewrittenAudioPickerValueChanged() {
        const textFile = this.rewrittenAudioFilePicker[0].files[0];
        if (textFile === undefined) {
            this.setupStartCreatingButton();
            return;
        }
        const fileReader = new FileReader();
        fileReader.onload = () => {
            const text = fileReader.result;
            this.blocks = this.parseBlocks(text);
        };
        fileReader.readAsText(textFile);
        this.setupStartCreatingButton();
    }

    setupStartCreatingButton() {
        const audioPickerHasFile = this.audioFilePicker[0].files[0] !== undefined;
        const rewrittenAudioPickerHasFile = this.rewrittenAudioFilePicker[0].files[0] !== undefined;
        const shouldEnableCreatingButton = audioPickerHasFile && rewrittenAudioPickerHasFile;
        if (shouldEnableCreatingButton) {
            this.startCreatingButton.removeClass("disabled");
        } else {
            this.startCreatingButton.addClass("disabled");
        }
    }

    creatingButtonClicked() {
        this.presentNextController();
    }

    // Helper Methods

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

}
