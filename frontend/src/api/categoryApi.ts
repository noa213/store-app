import { http } from '@/lib/http';

import { Category } from '@/types/category'

// get all categories
export const getAllCategories = async (): Promise<Category[]> => {
  try {
    const response = await http.get<any>('/categories');

    if (response.status !== 200)
      throw new Error(`${response.status}: error fetching categories`);

    return response.data.data.data;
  } catch (error: any) {
    console.error('Error:', error.message);
    throw new Error(error.message);
  }
};

// get category by ID
export const getCategoryById = async (categoryId: string): Promise<Category> => {
  try {
    const response = await http.get<Category>(`/categories/${categoryId}`);

    if (response.status !== 200)
      throw new Error(`${response.status}: ${response.statusText}`);

    return response.data;
  } catch (error: any) {
    console.error('Error:', error.message);
    throw new Error(error.message);
  }
};

// add a category
export const addCategory = async (category: Category): Promise<Category> => {
  try {
    const response = await http.post<Category>('/categories', category);
    if (response.status !== 201)
      throw new Error(`${response.status}: ${response.statusText}`);

    return response.data;
  } catch (error: any) {
    console.error('Error:', error.message);
    throw new Error(error.message);
  }
};

// update category by ID
export const updateCategory = async (
  categoryId: string,
  updatedCategory: Partial<Category>
): Promise<string> => {
  try {
    const response = await http.put<{ msg: string }>(`/categories/${categoryId}`, updatedCategory);

    if (response.status !== 200)
      throw new Error(`${response.status}: ${response.statusText}`);

    return response.data.msg;
  } catch (error: any) {
    console.error('Error:', error.message);
    throw new Error(error.message);
  }
};

// delete category by ID
export const deleteCategory = async (categoryId: string): Promise<string> => {
  try {
    const response = await http.delete<{ msg: string }>(`/categories/${categoryId}`);

    if (response.status !== 200)
      throw new Error(`${response.status}: ${response.statusText}`);

    return response.data.msg;
  } catch (error: any) {
    console.error('Error:', error.message);
    throw new Error(error.message);
  }
};
