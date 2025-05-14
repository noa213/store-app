
import { Drink } from '@/types/drink';
import axiosInstance from "./axiosInstance";



export const getDrinks = async (): Promise<Drink[]> => {
    try {
      const response = await axiosInstance.get<{ data: Drink[] }>("/drinks");
      return response.data.data; 
    } catch (error: any) {
      console.error("Error in getDrinks:", error.response?.data || error.message);
      throw error;
    }
  };

  export const getDrinkById = async (drinkId: string): Promise<Drink> => {
    try {
      const response = await axiosInstance.get<{ data: Drink }>(`/drinks/${drinkId}`);
      return response.data.data;
    } catch (error: any) {
      console.error("Error in getDrinksById:", error.response?.data || error.message);
      throw error;
    }
  };

  export const getDrinksByUserId = async (userId: string): Promise<Drink> => {
    try {
      const response = await axiosInstance.get<{ data: Drink }>(`/drinks/user/${userId}`);
      return response.data.data;
    } catch (error: any) {
      console.error("Error in getDrinksByUserId:", error.response?.data || error.message);
      throw error;
    }
  };

  export const createDrink = async (formData: {
    name: string;
    ml: string;
    price: string;
  }): Promise<Drink> => {
    try {
      const response = await axiosInstance.post<{ data: Drink }>("/drinks", {
        name: formData.name,
        ml: parseFloat(formData.ml),
        price: parseFloat(formData.price),
      });
      return response.data.data;
    } catch (error: any) {
      console.error("Error in createDrink:", error.response?.data || error.message);
      throw error;
    }
  };
  
  export const updateDrink = async (
    drinkId: string,
    formData: {
      name: string;
      ml: string;
      price: string;
    }
  ): Promise<Drink> => {
    try {
      const response = await axiosInstance.put<{ data: Drink }>(`/drinks/${drinkId}`, {
        name: formData.name,
        ml: parseFloat(formData.ml),
        price: parseFloat(formData.price),
      });
      return response.data.data;
    } catch (error: any) {
      console.error("Error in updateDrink:", error.response?.data || error.message);
      throw error;
    }
  };
  
  export const deleteDrink = async (id: string): Promise<void> => {
    try {
      await axiosInstance.delete(`/drinks/${id}`);
    } catch (error: any) {
      console.error("Error in deleteDrink:", error.response?.data || error.message);
      throw error;
    }
  };
