import type { UserSession } from '@/services/session';
import { httpPublic } from './http';

export interface LoginPayload { email: string; password: string; }

export async function doLogin(loginPayload: LoginPayload) {
    const response = await httpPublic.post<UserSession>('/auth/v1/login', loginPayload);
    return response.data;
}
