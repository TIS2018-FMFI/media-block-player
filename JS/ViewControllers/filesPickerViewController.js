class FilesPickerViewController extends ViewController {

    constructor() {
        super();

        this.fileName;
        this.sound;
        this.blocks;
    }

    renderHtml(html) {
        const htmlView = `
            <section id="FilesPickerViewController">
                <fieldset>
                    <legend>Choose Audio file</legend>
                    <div>
                        <input id="audio-file-picker" type="file" accept=".wav, .mp3" />
                    </div>
                </fieldset>
                <br>
                <fieldset>
                    <legend>Choose Text file</legend>
                    <div>
                        <input id="rewritten-audio-file-picker" type="file" accept=".txt" />
                    </div>
                </fieldset>
                <br>
                <br>
                <button id="start-creating-button" disabled style="font-size:20px;">Start creating</button>
                <br>
                <br>
                <a href="index.html">Back to my menu</a>
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
        syncFileCreateViewController.blocks = this.blocks;
        syncFileCreateViewController.fileName = this.fileName;
        this.navigationController.present(syncFileCreateViewController);
    }

    // Private Methods

    audioPickerValueChanged() {
        const audioFile = this.audioFilePicker[0].files[0];
        this.fileName = audioFile.name.split('.').slice(0, -1).join('.');
        this.getBase64(audioFile).then( data => {
            this.sound = new Howl({
                src: data
            });
        });
        this.setupStartCreatingButton();
    }

    rewrittenAudioPickerValueChanged() {
        const textFile = this.rewrittenAudioFilePicker[0].files[0];
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
        this.startCreatingButton.prop('disabled', !shouldEnableCreatingButton);
    }

    creatingButtonClicked() {
        this.presentNextController();
    }

    // Helper Methods

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

    getBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });
    }

}
