import api from "./api";

export const postRequest = async (url, data) => {
    try {
        const response = await api.post(url, data);
        return response;
    } catch (error) {
        return error.response || {
            status: error.status,
            message: error.message
        };
    }
}


export const getRequest = async () => {
    try {
        const response = await api.get(url);
        return response;
    } catch (error) {
        return error.response || {
            status: error.status,
            message: error.message
        };
    }
}