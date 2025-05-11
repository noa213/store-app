import { Product, ProductsResponse } from "@/types/product";
import axios from "axios";

const BASE_URL = "http://localhost:8000/api/products";

export const getProducts = async (): Promise<ProductsResponse> => {
    const res = await axios.get<ProductsResponse>(BASE_URL);
    return res.data;
};
export const getProductById = async (id: number): Promise<Product> => {
    const res = await axios.get<Product>(`${BASE_URL}/${id}`);
    return res.data;
};

export const deleteProduct = (id: number) => {
    return axios.delete(`${BASE_URL}/${id}`);
};

export const addProduct = (productData: any) => {
    return axios.post(BASE_URL, productData);
};

export const updateProduct = (id: string, productData: any) => {
    return axios.put(`${BASE_URL}/${id}`, productData);
};
