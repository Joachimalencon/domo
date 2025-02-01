import FetchService from '../services/fetchService';

const fetchService = new FetchService();

export default {
    async getContainers() {
        return await fetchService.get(`/dockers/containers`);
    },
    async startContainer(containerId) {
        return await fetchService.post(`/dockers/containers/${containerId}/start`);
    },
    async stopContainer(containerId) {
        return await fetchService.post(`/dockers/containers/${containerId}/stop`);
    },
};
