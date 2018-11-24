class SyncFileCreateViewController extends ViewController {

    constructor() {
        super();

        this.fileName;
        this.sound;
        this.blocks;

        this.blocksEndTimes = [] // End time of each block
        this.skipBlock = [] // End times of blocks marked as skipped
        this.textBlockIndex = 0; // Starting blocks array index
        this.actualSeek = 0; // Actual seek of audio
        this.creatingDone = false;
    }

    renderHtml(html) {
        const htmlView = `
            <section id="SyncFileCreateViewController">
                <h2 id="actual-text"></h2>
                <br>
                <input id="backward-speed" type="number" min="0.1" max="10" step="0.1" value="0.3">
                <button id="backward-button">Backward</button>
                <button id="play-pause-button">Play</button>
                <button id="forward-button">Forward</button>
                <input id="forward-speed" type="number" min="0.1" max="10" step="0.1" value="0.3">
                <br><br>
                <button id="play-actual-block-button">Play actual Block</button>
                <button id="skip-block-button">Skip Block</button>
                <button id="next-block-button">NEXT BLOCK</button>
                <br>
                <br>
                <a href="index.html">Back to my menu</a>
            </section>
        `;
        super.renderHtml(htmlView);
    }

    setupProperties() {
        // Labels
        this.actualBlockText = $('#actual-text');

        // Inputs
        this.backwardSpeedInput = $('#backward-speed');
        this.forwardSpeedInput = $('#forward-speed');

        // Actions
        this.playPauseButton = $('#play-pause-button');
        this.backwardButton = $('#backward-button');
        this.forwardButton = $('#forward-button');
        this.playActualBlockButton = $('#play-actual-block-button');
        this.skipBlockButton = $('#skip-block-button');
        this.nextBlockButton = $('#next-block-button');
    }

    setupEventListeners() {
        this.playPauseButtonClicked = this.playPauseButtonClicked.bind(this);
        this.backwardButtonClicked = this.backwardButtonClicked.bind(this);
        this.forwardButtonClicked = this.forwardButtonClicked.bind(this);
        this.playActualButtonClicked = this.playActualButtonClicked.bind(this);
        this.skipBlockButtonClicked = this.skipBlockButtonClicked.bind(this);
        this.nextBlockButtonClicked = this.nextBlockButtonClicked.bind(this);

        this.playPauseButton.on('click', this.playPauseButtonClicked);
        this.backwardButton.on('click', this.backwardButtonClicked);
        this.forwardButton.on('click', this.forwardButtonClicked);
        this.playActualBlockButton.on('click', this.playActualButtonClicked);
        this.skipBlockButton.on('click', this.skipBlockButtonClicked);
        this.nextBlockButton.on('click', this.nextBlockButtonClicked);
    }

    viewDidLoad() {
        this.actualBlockText.text( this.blocks[this.textBlockIndex] );
    }

    presentNextController() {
        const syncFileDownloadViewController = new SyncFileDownloadViewController();
        syncFileDownloadViewController.fileName = this.fileName;
        syncFileDownloadViewController.blocksEndTimes = this.blocksEndTimes;
        syncFileDownloadViewController.skipBlock = this.skipBlock;
        this.navigationController.present(syncFileDownloadViewController);
    }

    // Private Methods

    playPauseButtonClicked() {
        if (this.sound.playing()) {
            this.sound.pause();
            this.actualSeek = this.sound.seek();
            this.playPauseButton.text("Play");
        } else {
            this.sound.play();
            this.playPauseButton.text("Pause");
        }
    }

    backwardButtonClicked() {
        this.sound.seek( this.sound.seek() - Number(this.backwardSpeedInput.val()) );
        this.actualSeek = this.sound.seek();
    }

    forwardButtonClicked() {
        this.sound.seek( this.sound.seek() + Number(this.forwardSpeedInput.val()) );
        this.actualSeek = this.sound.seek();
    }

    playActualButtonClicked() {
        var lastPosition = 0;
        if (this.blocksEndTimes.length > 0) {
            lastPosition = this.blocksEndTimes[this.blocksEndTimes.length - 1];
        }
        const playbackLength = (this.actualSeek - lastPosition) * 1000;
        this.sound._sprite.actual = [lastPosition * 1000, playbackLength];
        this.sound.pause();
        this.sound.play('actual');
    }

    skipBlockButtonClicked() {
        this.actualSeek = this.sound.seek();
        this.blocksEndTimes.push( Math.round( this.sound.seek() * 100 ) / 100 );
        this.skipBlock.push( Math.round( this.sound.seek() * 100 ) / 100 );
    }

    nextBlockButtonClicked() {
        if (this.creatingDone) {
            this.presentNextController();
        } else if (this.textBlockIndex === this.blocks.length - 1) {
            this.actualBlockText.text('Sync file creating is done.');
            this.nextBlockButton.text('FINISH');
            this.creatingDone = true;
        } else {
            this.actualSeek = this.sound.seek();
            this.blocksEndTimes.push( Math.round( this.sound.seek() * 100 ) / 100 );

            this.textBlockIndex++;
            this.actualBlockText.text( this.blocks[this.textBlockIndex] );
        }
    }

}
