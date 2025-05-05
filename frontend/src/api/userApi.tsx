// // services/userApi.ts
// import axios from "axios";

// const axiosInstance = axios.create({
//   baseURL: "http://localhost:3003/users",
//   withCredentials: true,
// });

// // טיפוסי משתמשים
// export interface User {
//   id?: string;
//   name: string;
//   email: string;
//   password?: string;
//   role?: string;
// }

// export interface LoginData {
//   email: string;
//   password: string;
// }

// // Login user
// export const login = async (data: LoginData): Promise<any> => {
//   try {
//     const response = await axiosInstance.post("/login", data);
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in login:", error.response?.data || error.message);
//     throw error;
//   }
// };

// // Get role of authenticated user
// export const getRoleAuthUser = async (): Promise<string> => {
//   try {
//     const response = await axiosInstance.get("/auth-user");
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in getRoleAuthUser:", error.response?.data || error.message);
//     throw error;
//   }
// };

// // Logout user
// export const logout = async (): Promise<any> => {
//   try {
//     const response = await axiosInstance.post("/logout");
//     localStorage.removeItem("token");
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in logout:", error.response?.data || error.message);
//     throw error;
//   }
// };


// // Signup new user
// export const signup = async (data: User): Promise<any> => {
//   try {
//     const response = await axiosInstance.post("/signup", data);
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in signup:", error.response?.data || error.message);
//     throw error;
//   }
// };

// // Get authenticated user info
// export const getUserInfo = async (): Promise<User> => {
//   try {
//     const response = await axiosInstance.get("/user");
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in getUserInfo:", error.response?.data || error.message);
//     throw error;
//   }
// };

// // Get all users
// export const getUsers = async (): Promise<User[]> => {
//   try {
//     const response = await axiosInstance.get("/index");
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in getUsers:", error.response?.data || error.message);
//     throw error;
//   }
// };

// // Add new user
// export const addUser = async (user: User): Promise<User> => {
//   try {
//     const response = await axiosInstance.post("/", user);
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in addUser:", error.response?.data || error.message);
//     throw error;
//   }
// };

// // Delete user by ID
// export const deleteUser = async (id: string): Promise<any> => {
//   try {
//     const response = await axiosInstance.delete(`/${id}`);
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in deleteUser:", error.response?.data || error.message);
//     throw error;
//   }
// };

// // Get user by ID
// export const getUserById = async (id: string): Promise<User> => {
//   try {
//     const response = await axiosInstance.get(`/${id}`);
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in getUserById:", error.response?.data || error.message);
//     throw error;
//   }
// };

// // Update user by ID
// export const updateUser = async (id: string, user: Partial<User>): Promise<User> => {
//   try {
//     const response = await axiosInstance.put(`/${id}`, user);
//     return response.data;
//   } catch (error: any) {
//     console.error("Error in updateUser:", error.response?.data || error.message);
//     throw error;
//   }
// };
