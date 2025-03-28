import axios from "axios";

export const API_URL = "http://localhost:8000";

const api = axios.create({
    baseURL: API_URL,
    timeout: 5000,
});

export default api;