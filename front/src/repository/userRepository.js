import FetchService from '../services/fetchService';

const fetchService = new FetchService();

export default {
    async getUsers() {
        return await fetchService.get(`/users`);
    },
    async approveUser(userId) {
        return await fetchService.post(`/users/${userId}/approve`);
    },
    async revokeUser(userId) {
        return await fetchService.post(`/users/${userId}/revoke`);
    },
};
