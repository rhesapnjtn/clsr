import axios from 'axios';

window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = '/api';

axios.defaults.xsrfCookieName = 'XSRF-TOKEN';
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN';

// Axios automatically reads the XSRF-TOKEN cookie and sends it as the
// X-XSRF-TOKEN header on every request, which stays in sync even after
// the session (and therefore the CSRF token) is regenerated on login.

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 419) {
            window.location.reload();
        }
        if (error.response?.status === 401) {
            const bypass = ['/login', '/me'];
            const url = error.config?.url ?? '';
            if (!bypass.some((b) => url.includes(b)) && !url.includes('available')) {
                window.location.href = '/admin';
            }
        }
        return Promise.reject(error);
    }
);