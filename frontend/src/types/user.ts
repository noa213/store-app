export interface User {
  _id: string;
  name: string;
  email: string;
  role: string;
}

export interface LoginData {
  email: string;
  password: string;
}

export interface SignupData {
  name: string;
  email: string;
  password: string;
}