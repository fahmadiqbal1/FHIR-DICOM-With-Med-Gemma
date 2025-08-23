import axios from 'axios';

const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';

// Create axios instance with default config
const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add request interceptor for auth token
apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('authToken');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Add response interceptor for error handling
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('authToken');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export class DashboardAPI {
  // Admin Dashboard - matches your Laravel adminStats() method
  static async getAdminStats() {
    const response = await apiClient.get('/dashboard/admin');
    return response.data;
  }

  // Doctor Dashboard - matches your Laravel doctorStats() method  
  static async getDoctorStats() {
    const response = await apiClient.get('/dashboard/doctor');
    return response.data;
  }

  // Lab Tech Dashboard - matches your Laravel labStats() method
  static async getLabStats() {
    const response = await apiClient.get('/dashboard/lab');
    return response.data;
  }

  // Radiologist Dashboard - matches your Laravel radiologistStats() method
  static async getRadiologistStats() {
    const response = await apiClient.get('/dashboard/radiologist');
    return response.data;
  }

  // Owner Dashboard - matches your Laravel ownerStats() method
  static async getOwnerStats() {
    const response = await apiClient.get('/dashboard/owner');
    return response.data;
  }

  // System Health - matches your Laravel health() method
  static async getSystemHealth() {
    const response = await apiClient.get('/dashboard/health');
    return response.data;
  }

  // Legacy Stats - matches your Laravel stats() method
  static async getLegacyStats() {
    const response = await apiClient.get('/dashboard/stats');
    return response.data;
  }

  // Format currency helper
  static formatCurrency(amount) {
    return formatCurrency(amount);
  }
}

// Authentication API
export class AuthAPI {
  static async login(credentials) {
    const response = await apiClient.post('/auth/login', credentials);
    return response.data;
  }

  static async logout() {
    const response = await apiClient.post('/auth/logout');
    localStorage.removeItem('authToken');
    localStorage.removeItem('user');
    return response.data;
  }

  static async getCurrentUser() {
    const response = await apiClient.get('/auth/user');
    return response.data;
  }
}

// Utility function to format currency - updated for dollar amounts
export const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(amount || 0);
};

// Utility function to format dates
export const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};

export default apiClient;
