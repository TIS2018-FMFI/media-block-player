/// ViewController is abstract class that represents one application screen
class ViewController {

    constructor() {
        this.root = $('#root');
        this.navigationController;
    }

    /// Presents actual app screen. Call present and it will draw HTML
    /// it will setup properties, event listeners and finally call viewDidLoad
    present() {
        this.renderHtml();
        this.setupProperties();
        this.setupEventListeners();
        this.viewDidLoad();
    }

    /// Renders HTML
    /// @param html - html you are going to draw, in extended view controller
    ///               you must call super.renderHtml(html)
    renderHtml(html) {
        this.root.html(html);
    }

    /// Setup jQuery properties
    setupProperties() { }

    /// Setup listener added on html elements
    setupEventListeners() { }

    /// Method called after all setups
    viewDidLoad() { }

    /// Initialize, setup and present new view controller
    /// This method is not called automatically. Its just nice place for
    /// setup next view controller, so every view controller which presents
    /// next view controller after some action, should implement this method.
    presentNextController() { }

}
