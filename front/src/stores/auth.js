import {defineStore} from 'pinia';
import {ref, watch} from 'vue';
import authRepository from '../repository/authRepository';

export const useAuthStore = defineStore('auth', () => {
    const token = ref(localStorage.getItem('domoAuthToken') || null);
    const isAuthenticated = ref(!!token.value);
    const isLoading = ref(false);
    const error = ref(null);
    const user = ref(null);
    const isApproved = ref(localStorage.getItem('domoApproval') || false);

    async function storeToken(expectedToken, tokenRef) {
        return new Promise((resolve) => {
            watch(
                tokenRef,
                (newToken) => {
                    if (newToken) {
                        localStorage.setItem('domoAuthToken', newToken);
                    } else {
                        localStorage.removeItem('domoAuthToken');
                    }

                    if (newToken === expectedToken) {
                        resolve();
                    }
                },
                {immediate: true}
            );
        });
    }

    async function storeApproval(expectedApproval, approvalRef) {
        return new Promise((resolve) => {
            watch(
                approvalRef,
                (newApproval) => {
                    if (newApproval) {
                        localStorage.setItem('domoApproval', newApproval);
                    } else {
                        localStorage.removeItem('domoApproval');
                    }

                    if (newApproval === expectedApproval) {
                        resolve();
                    }
                },
                {immediate: true}
            );
        });
    }

    async function login(payload) {
        isLoading.value = true;
        try {
            const response = await authRepository.login(payload);

            if (!response.token) {
                const responseError = await response.json();
                return {error: true, message: responseError.message};
            }

            token.value = response.token;

            await storeToken(response.token, token);

            isAuthenticated.value = true;
            await getMe();
            return {error: false};
        } finally {
            isLoading.value = false;
        }
    }

    async function register(payload) {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await authRepository.register(payload);

            if (!response.created) {
                const responseError = await response.json();
                return {error: true, message: responseError.message};
            }

            return {error: false};
        } finally {
            isLoading.value = false;
        }
    }

    async function getMe() {
        try {
            user.value = await authRepository.getMe();
            isApproved.value = user.value.approved;

            await storeApproval(user.value.approved, isApproved);
        } catch (err) {
            error.value = err.message;
        }
    }

    function logout() {
        token.value = null;
        user.value = null;
        isApproved.value = false;
        isAuthenticated.value = false;
        localStorage.removeItem('domoAuthToken');
        localStorage.removeItem('domoApproval');
    }

    return {token, isAuthenticated, isLoading, error, isApproved, user, login, register, logout, getMe};
});