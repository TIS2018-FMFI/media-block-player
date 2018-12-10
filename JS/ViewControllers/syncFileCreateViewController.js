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
        this.creatingDone = false; // Last block was created
    }

    renderHtml(html) {
        const htmlView = `
            <section id="SyncFileCreateViewController" class="container">
                <div class="row row-150">
                    <h2 id="actual-text" class="center"></h2>
                </div>
                <div class="row row-50 center">
                    <input class="w-45 m-lr-10" id="backward-speed" type="number" min="0.1" max="10" step="0.1" value="0.3">
                    <a class="btn m-lr-10" id="backward-button"><i class="material-icons">fast_rewind</i></a>
                    <a class="btn m-lr-10" id="play-pause-button"><i id="play-pause-icon" class="material-icons">play_circle_outline</i></a>
                    <a class="btn m-lr-10" id="forward-button"><i class="material-icons">fast_forward</i></a>
                    <input class="w-45 m-lr-10" id="forward-speed" type="number" min="0.1" max="10" step="0.1" value="0.3">
                </div>
                <div class="row row-50 center">
                    <a id="play-actual-block-button" class="btn"><i class="material-icons right">replay</i>Play actual block</a>
                </div>
                <div class="row row-50 center">
                    <a id="skip-block-button" class="btn m-lr-10">Skip block</a>
                    <a id="next-block-button" class="btn m-lr-10">NEXT BLOCK</a>
                </div>
                <div class="row row-100">
                    <a class="btn-small right" href="index.html">Back to my menu</a>
                </div>
            </section>
        `;
        super.renderHtml(htmlView);
    }

    setupProperties() {
        // Labels
        this.actualBlockText = $('#actual-text');
        this.playPauseIcon = $('#play-pause-icon');

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
            this.playPauseIcon.text('play_circle_outline');
        } else {
            this.sound.play();
            this.playPauseIcon.text('pause_circle_outline');
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