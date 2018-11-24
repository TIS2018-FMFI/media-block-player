class SyncFileDownloadViewController extends ViewController {

    constructor() {
        super();

        this.fileName;
        this.blocksEndTimes;
        this.skipBlock;
    }

    renderHtml(html) {
        const htmlView = `
            <section id="SyncFileDownloadViewController">
                <h2 id="file-name-label"></h2>
                <a id="file-download"></a>
                <br>
                <br>
                <a href="index.html">Back to my menu</a>
            </section>
        `;
        super.renderHtml(htmlView);
    }

    setupProperties() {
        this.fileNameLabel = $('#file-name-label');
        this.fileLinkDownload = $('#file-download');
    }

    viewDidLoad() {
        this.showSyncFileDownload();
    }

    // Private Methods

    showSyncFileDownload() {
        const syncFileName = `${this.fileName}.mpbsf`;
        const syncFileObject = new Object();
        syncFileObject.blocks = this.blocksEndTimes;
        syncFileObject.skips = this.skipBlock;
        const syncFileJSON = JSON.stringify(syncFileObject);

        this.fileNameLabel.text(syncFileName);
        this.fileLinkDownload.attr('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(syncFileJSON));
        this.fileLinkDownload.attr('download', syncFileName);
        this.fileLinkDownload.html('DOWNLOAD');
    }

}
