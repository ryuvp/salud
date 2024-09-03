import '@/bootstrap';
//import { ElMessage } from 'element-plus';
import { isLogged, getToken } from '@/app/utils/auth';

// Create axios instance
const service = window.axios.create({
  baseURL: 'https://apiperu.dev/api',
  timeout: 10000, // Request timeout
});

const token_ = '7d4c819aecc89fcf69546be4930bd3cfe1b004efca821ce7519b72042eb70aab'

// Request intercepter
service.interceptors.request.use(
  config => {
    const token = isLogged();
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + token_; // Set JWT token
    }
    return config;
  },
  error => {
    // Do something with request error
    console.log(error); // for debug
    Promise.reject(error);
  }
);

// response pre-processing
service.interceptors.response.use(
  response => {
    return response.data;
  },
  error => {
    let message = error.message;
    if (error.response.data && error.response.data.message) {
      message = error.response.data.message;
    }

    ElMessage({
      message: message,
      type: 'error',
      duration: 5 * 1000,
    });
    return Promise.reject(error);
  }
);

export default service;
