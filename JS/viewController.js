class ViewController {

    constructor() {
        this.root = $('#root');
        this.navigationController;
    }

    present() {
        this.renderHtml();
        this.setupProperties();
        this.setupEventListeners();
        this.viewDidLoad();
    }

    // Renders HTML
    renderHtml(html) {
        this.root.html(html);
    }

    // Setup jQuery properties
    setupProperties() { }

    // Setup listener added on html elements
    setupEventListeners() { }

    // Method called after all setups
    viewDidLoad() { }

    // Initialize, setup and present new view controller
    presentNextController() { }

}
