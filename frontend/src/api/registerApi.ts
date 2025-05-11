import { http } from '@/lib/http';
import { User, LoginData, SignupData2 } from '@/types/user';

export const register = async (user: SignupData2) => {
    try {
        const response = await http.post<any>("/register", user);

        const token = response.data.data.token;
        localStorage.setItem("token", token); 
  
      return response.data;
    } catch (error: any) {
      console.error("Error in register user:", error.response?.data || error.message);
      throw error;
    }
};

export const login = async (data: LoginData) => {
    try {
        const response = await http.post<any>("/login", data);

        const token = response.data.data.token;
        localStorage.setItem("token", token); 

        return response.data;
    } catch (error: any) {
        console.error("Error in login:", error.response?.data || error.message);
        throw error;
    }
};

export const logout = async () => {
  try {
    const response = await http.post("/logout");

    localStorage.removeItem("token");
    console.log(response)
    return response.data;
  } catch (error: any) {
    console.error("Error in logout:", error.response?.data || error.message);
    throw error;
  }
};
  