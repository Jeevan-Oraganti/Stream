export default class GlobalLoadingBar {
    static loadingStack = [];

    static addLoadingRequest(requestId) {
        if (!this.loadingStack.includes(requestId)) {
            this.loadingStack.push(requestId);
        }
    }

    static removeLoadingRequest(requestId) {
        this.loadingStack = this.loadingStack.filter(id => id !== requestId);
    }

    static getLoadingStackLength() {
        return this.loadingStack.length;
    }
}
