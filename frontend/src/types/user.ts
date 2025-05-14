import { arrayOutputType } from "zod";

export interface User {
  id: string;
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

export interface SignupData2 {
  name: string;
  email: string;
  password: string;
  password_verification: string;
  favs_ar: string[];
}