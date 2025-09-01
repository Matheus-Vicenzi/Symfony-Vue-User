import axios from "axios";

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;

console.log("API Base URL:", apiBaseUrl);

export const httpPublic = axios.create({
  baseURL: apiBaseUrl,
  headers: { "Content-Type": "application/json" },
});

export const httpAuth = axios.create({
  baseURL: apiBaseUrl,
  withCredentials: true,
  headers: { "Content-Type": "application/json" },
});