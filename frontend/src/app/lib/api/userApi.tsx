import axios from "axios";
import { User, LoginData, SignupData } from '@/types/user';

const API_URL = "http://localhost:8000/api/users";

const axiosInstance = axios.create({
  baseURL: API_URL,
  withCredentials: true,
});

export const login = async (data: LoginData) => {
  try {
    const response = await axiosInstance.post("/login", data);
    return response.data;
  } catch (error: any) {
    console.error("Error in login:", error.response?.data || error.message);
    throw error;
  }
};

export const getRoleAuthUser = async () => {
  try {
    const response = await axiosInstance.get("/auth-user");
    return response.data;
  } catch (error: any) {
    console.error("Error in getRoleAuthUser:", error.response?.data || error.message);
    throw error;
  }
};

export const logout = async () => {
  try {
    const response = await axiosInstance.post("/logout");
    localStorage.removeItem("token");
    return response.data;
  } catch (error: any) {
    console.error("Error in logout:", error.response?.data || error.message);
    throw error;
  }
};

export const signup = async (data: SignupData) => {
  try {
    const response = await axiosInstance.post("/signup", data);
    return response.data;
  } catch (error: any) {
    console.error("Error in signup:", error.response?.data || error.message);
    throw error;
  }
};

export const getUserInfo = async (): Promise<User> => {
    try {
      const response = await axiosInstance.get<User>("");
      return response.data;
    } catch (error: any) {
      console.error("Error in getUserInfo:", error.response?.data || error.message);
      throw error;
    }
};

export const getUsers = async (): Promise<User[]> => {
    try {
      const response = await axiosInstance.get<User[]>("");
      return response.data;
    } catch (error: any) {
      console.error("Error in getUsers:", error.response?.data || error.message);
      throw error;
    }
};  
  
export const createUser = async (user: SignupData) => {
  try {
    const response = await axiosInstance.post("/", user);
    return response.data;
  } catch (error: any) {
    console.error("Error in addUser:", error.response?.data || error.message);
    throw error;
  }
};

export const deleteUser = async (id: string) => {
  try {
    const response = await axiosInstance.delete(`/${id}`);
    return response.data;
  } catch (error: any) {
    console.error("Error in deleteUser:", error.response?.data || error.message);
    throw error;
  }
};

export const getUserById = async (id: string) => {
  try {
    const url = `/${id}`;
    console.log("Requesting URL:", axiosInstance.defaults.baseURL + url);
    const response = await axiosInstance.get(url);
    return response.data;
  } catch (error: any) {
    console.error("Error in getUserById:", error.response?.data || error.message);
    throw error;
  }
};

export const updateUser = async (id: string, user: Partial<User>) => {
  try {
    const response = await axiosInstance.put(`/${id}`, user);
    return response.data;
  } catch (error: any) {
    console.error("Error in updateUser:", error.response?.data || error.message);
    throw error;
  }
};
