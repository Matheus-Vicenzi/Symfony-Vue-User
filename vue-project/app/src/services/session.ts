import { computed } from "vue";

export interface UserSession {
  id: number;
  name: string;
  email: string;
  logged_in_at: string;
}

export const getUserSession = (): UserSession | null => {
  const session = localStorage.getItem('user_session');
  if (!session) return null;
  try {
    return JSON.parse(session) as UserSession;
  } catch {
    return null;
  }
};

export const setUserSession = (user: UserSession) => {
  localStorage.setItem('user_session', JSON.stringify(user));
};

export const clearUserSession = () => {
  localStorage.removeItem('user_session');
};

export const isLoggedIn = computed(() => !!getUserSession())