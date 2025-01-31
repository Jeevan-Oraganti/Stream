export default class GlobalLoadingBar {
    static loadingStack = [];

    static addLoadingRequest(requestId) {
        this.loadingStack.push(requestId);
    }

    static removeLoadingRequest(requestId) {
        const index = this.loadingStack.indexOf(requestId);
        if (index !== -1) {
            this.loadingStack.splice(index, 1);
        }
    }

    static getLoadingStackLength() {
        return this.loadingStack.length;
    }
}
