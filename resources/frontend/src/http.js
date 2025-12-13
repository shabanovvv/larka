import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

const http = axios.create({
    baseURL: API_BASE_URL,
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

const getCookie = (name) =>
    document.cookie
        .split('; ')
        .find((row) => row.startsWith(`${name}=`))
        ?.split('=')[1];

const applyXsrfHeader = () => {
    const token = getCookie('XSRF-TOKEN');
    if (token) {
        http.defaults.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(token);
    }
};

let csrfPromise = null;

const getCsrfToken = async () => {
    if (csrfPromise) {
        return csrfPromise;
    }

    csrfPromise = axios
        .get(`${API_BASE_URL}/api/csrf-cookie`, { withCredentials: true })
        .then(() => {
            console.log('CSRF получен');
            applyXsrfHeader();
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
