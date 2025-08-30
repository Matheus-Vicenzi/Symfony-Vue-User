import { httpPublic, httpAuth } from './http';

export interface UserInput {
  name: string;
  email: string;
  password: string;
}

export interface UserOutput {
  id: number;
  name: string;
  email: string;
}

export async function createUser(user: UserInput): Promise<UserOutput> {
  const response = await httpPublic.post<UserOutput>('/user/v1', user);
  return response.data;
}

export async function getUser(id: number) {
  const response = await httpAuth.get<UserOutput>(`/user/v1/${id}`);
  return response.data;
}
