import FetchService from '../services/fetchService';

const fetchService = new FetchService();

export default {
    async login(payload) {
        return await fetchService.post('/login', payload);
    },
    async register(payload) {
        return await fetchService.post('/register', payload);
    },
    async getMe() {
        return await fetchService.get('/me');
    }
};
