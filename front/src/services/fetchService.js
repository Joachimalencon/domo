export default class FetchService {
    baseURL = `${import.meta.env.VITE_API_URL}/api`;

    async request(method, endpoint, data = null, customOptions = {}) {
        const url = `${this.baseURL}${endpoint}`;

        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                ...customOptions.headers,
            },
            ...customOptions,
        };

        const authToken = localStorage.getItem('domoAuthToken');
        if (authToken) {
            options.headers['Authorization'] = `Bearer ${authToken}`;
        }


        if (data) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);

        if (!response.ok) {
            if (response.status === 401) {
                localStorage.removeItem('domoAuthToken');
                if (endpoint !== '/login') {
                    window.location.reload();
                }
            }
            return response;
        }

        return await response.json();
    }

    get(endpoint, options) {
        return this.request('GET', endpoint, null, options);
    }

    post(endpoint, data, options) {
        return this.request('POST', endpoint, data, options);
    }

    patch(endpoint, data, options) {
        return this.request('PATCH', endpoint, data, options);
    }

    delete(endpoint, options) {
        return this.request('DELETE', endpoint, null, options);
    }
}
