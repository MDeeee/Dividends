import axios from 'axios';
import axiosRetry from 'axios-retry';

let originUrl = window.location.origin;
axios.defaults.baseURL = originUrl + '/api/';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Content-Type'] = 'application/json';

axiosRetry(axios, { retries: 3 });
window.axios = require('axios');

