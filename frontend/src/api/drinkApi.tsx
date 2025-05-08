import axios from "axios";

const API_URL = "http://localhost:8000/api/drinks";

interface Drink {
    id: string;
    name: string;
    ml: number;
    price: number;
    user_id: string;
  }

// export const getDrinks = async (): Promise<{ data: Drink[] }> => {
//   const response = await axios.get(API_URL);
//   return response.data;
// };

export const addDrink = async (drink: { name: string; price: number }) => {
  const response = await axios.post(API_URL, drink);
  return response.data;
};

export const deleteDrink = async (id: string) => {
  const response = await axios.delete(`${API_URL}/${id}`);
  return response.data;
};

export const updateDrink = async (id: string, updatedDrink: { name: string; price: number }) => {
  const response = await axios.put(`${API_URL}/${id}`, updatedDrink);
  return response.data;
};
