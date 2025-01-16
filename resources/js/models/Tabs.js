class Tabs {
    static all(then) {
        return axios.get("/tabs").then(({ data }) => then(data));
    }
}

export default Tabs;
