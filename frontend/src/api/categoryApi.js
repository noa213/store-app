import { http } from '@/services/http';

//get all the categories
export const getCategories= async () => {  
    try {
      const response = await http.get('/categories'); 
      if (response.status !== 200)
        throw new Error(`${response.status}: error fetching categories`);
  
      return response.data; 
  
    } catch (error) {
        console.error('Error:', error.message);
        throw new Error(error.message);    
    }
};

//get category by ID
export const getCategoryById= async (categoryId) => {  
    try {
      const response = await http.get(`/categories/${categoryId}`); 
      if (response.status !== 200)
        throw new Error(`${response.status}: ${response.msg}`);
  
      return response.data; 
  
    } catch (error) {
        console.error('Error:', error.message);
        throw new Error(error.message);    
    }
};

//add a category
export const addCategory= async (category) => {  
    try {
      const response = await http.post('/categories', category); 
      if (response.status !== 201)
        throw new Error(`${response.status}: ${response.msg}`);
  
      return response.data; 
  
    } catch (error) {
        console.error('Error:', error.message);
        throw new Error(error.message);    
    }
};

//update category by ID
export const updateCategory= async (categoryId, updatedCatgory) => {  
    try {
      const response = await http.put(`/categories/${categoryId}`, updatedCatgory); 
      if (response.status !== 200)
        throw new Error(`${response.status}: ${response.msg}`);
  
      return response.msg; 
  
    } catch (error) {
        console.error('Error:', error.message);
        throw new Error(error.message);    
    }
};

//delete category by ID
export const deleteCategory= async (categoryId) => {  
    try {
      const response = await http.delete(`/categories/${categoryId}`); 
      if (response.status !== 200)
        throw new Error(`${response.status}: ${response.msg}`);
  
      return response.msg; 
  
    } catch (error) {
        console.error('Error:', error.message);
        throw new Error(error.message);    
    }
};