import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

const http = axios.create({
    baseURL: API_BASE_URL,
    withCredentials: true,
    // Для SPA на другом origin (localhost:3000 -> localhost:80) axios по умолчанию
    // не добавляет XSRF заголовок. Включаем явную отправку XSRF токена.
    withXSRFToken: true,
    xsrfCookieName: 'XSRF-TOKEN',
    xsrfHeaderName: 'X-XSRF-TOKEN',
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

let csrfPromise = null;

const getCsrfToken = async () => {
    if (csrfPromise) {
        return csrfPromise;
    }

    csrfPromise = http
        .get('/api/csrf-cookie')
        .then(() => {
            console.log('CSRF получен');
        })
        .catch((error) => {
            console.error('Ошибка получения CSRF:', error);
        })
        .finally(() => {
            csrfPromise = null;
        });

    return csrfPromise;
};

http.interceptors.request.use(
    async (config) => {
        const method = config.method?.toLowerCase();
        if (['post', 'put', 'patch', 'delete'].includes(method)) {
            await getCsrfToken();
        }
        return config;
    },
    (error) => Promise.reject(error),
);

http.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config ?? {};

        if (error.response?.status === 419 && !originalRequest._retry) {
            originalRequest._retry = true;
            console.log('CSRF истек, получаем новый...');
            await getCsrfToken();
            return http(originalRequest);
        }

        if (error.response?.status === 401 && window.location.pathname !== '/login') {
            window.location.href = '/login';
        }

        return Promise.reject(error);
    },
);

export const fetchCsrf = getCsrfToken;
export default http;
