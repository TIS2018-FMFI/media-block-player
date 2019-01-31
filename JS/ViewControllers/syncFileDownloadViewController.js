/// Class for download created synchronization file
class SyncFileDownloadViewController extends ViewController {

    constructor() {
        super();

        this.fileName;
        this.blocksEndTimes;
        this.skipBlock;
    }

    renderHtml(html) {
        const htmlView = `
            <section id="SyncFileDownloadViewController" class="container">
                <div class="row row-100">
                    <div class="col s12">
                        <h2 id="file-name-label" class="center"></h2>
                    </div>
                </div>
                <div class="row row-50">
                    <div class="col s12 center">
                        <a id="file-download" class="waves-effect waves-light btn-large"><i class="material-icons right">get_app</i>DOWNLOAD</a>
                    </div>
                </div>
                <div class="row row-100">
                    <div class="col s12">
                        <a class="btn right" href="index.html">Back to my menu</a>
                    </div>
                </div>
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

    /// This method create json object from blocks end times array and
    /// skip blocks array. Then it will create html download link with
    /// {filename}.mbpsf file
    showSyncFileDownload() {
        const syncFileName = `${this.fileName}.mbpsf`;
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
