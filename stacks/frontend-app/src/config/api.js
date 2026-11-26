import axios from 'axios';

export const API_BASE_URL = (
  process.env.EXPO_PUBLIC_API_BASE_URL || 'http://localhost:8000'
).replace(/\/+$/, '');

// The signed-in user's token lives in memory for the life of the app session.
let authToken = null;

export function setAuthToken(token) {
  authToken = token;
}

export function clearAuthToken() {
  authToken = null;
}

export const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 15000,
  headers: { Accept: 'application/json' },
});

api.interceptors.request.use(config => {
  if (authToken) {
    config.headers.Authorization = `Bearer ${authToken}`;
  }
  return config;
});

// The API answers errors with a JSON body and a 4xx/5xx status.
// Turn that into the message the server sent instead of a generic axios error.
export function errorMessage(err, fallback = 'Network or server error.') {
  return err?.response?.data?.message || fallback;
}

export function isUnauthorized(err) {
  return err?.response?.status === 401;
}
