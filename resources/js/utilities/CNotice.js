import axios from "axios";

export default class CNotice {

    static async acknowledge(noticeId) {
        try {
            await axios.post(`/notices/${noticeId}/acknowledge`);
        } catch (error) {
            console.error(error);
        }
    }

}
