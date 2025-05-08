import { Product } from "@/types/product";
import axios from "axios";

const BASE_URL = "/api/products";

export const getProducts = async (): Promise<{ data: Product[] }> => {
    return axios.get(BASE_URL);
};

export const deleteProduct = (id: string) => {
    return axios.delete(`${BASE_URL}/${id}`);
};

export const addProduct = (productData: any) => {
    return axios.post(BASE_URL, productData);
};

export const updateProduct = (id: string, productData: any) => {
    return axios.put(`${BASE_URL}/${id}`, productData);
};
