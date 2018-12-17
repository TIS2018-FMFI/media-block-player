/// Navigation Controller is wrapper for view controllers that are presented.
/// So you can then navigate through this controllers, you have controllers
/// array, where are all presented controllers, so you can go back for example
class NavigationController {

    constructor() {
        this.actualController;
        this.controllers = [];
    }

    /// Method for presenting view controller if using navigation controller.
    /// Then in every wiew controller you don't want present view controller
    /// directly, but call this present on navigation controller, so it can
    /// add next view controller to controllers array.
    /// @param viewController - view controller you are goint to present
    present(viewController) {
        this.controllers.push(viewController);
        this.actualController = viewController;
        this.actualController.navigationController = this;
        this.actualController.present();
    }

}
