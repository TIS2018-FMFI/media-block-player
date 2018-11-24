class NavigationController {

    constructor() {
        this.actualController;
        this.controllers = [];
    }

    present(viewController) {
        this.controllers.push(viewController);
        this.actualController = viewController;
        this.actualController.navigationController = this;
        this.actualController.present();
    }

}
