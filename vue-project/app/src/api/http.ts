import axios from "axios";

export const httpPublic = axios.create({
  baseURL: "/api",
  headers: { "Content-Type": "application/json" },
});

export const httpAuth = axios.create({
  baseURL: "/api",
  withCredentials: true,
  headers: { "Content-Type": "application/json" },
});