"use client";
import React, { useEffect, useState } from "react";
import CategoriesTable from "@/components/categories/CategoriesTable";
import AddCategoryForm from "@/components/categories/AddCategoryForm";
import EditCategoryForm from '@/components/categories/EditCategoryForm';
import { getAllCategories, addCategory, updateCategory, deleteCategory } from "@/api/categoryApi";
import { Category } from "@/types/category";

const CategoriesPage = () => {

  const [isAddCategory, setIsAddCategory] = useState(false);
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedCategory, setSelectedCategory] = useState<Category | null>(null);

  const fetchCategories = async () => {
    try {
      const data = await getAllCategories();
      setCategories(data);
    } catch (error) {
      console.error("Error fetching categories:", error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchCategories();
  }, []);

  const handleAddCategory = async (newCategory: Category) => {
    try {
      const res = await addCategory(newCategory);  
      const newCategoryWithId = { ...newCategory, id: res.id }; 
      setCategories(prev => [...prev, newCategoryWithId]); 
    } catch (error) {
      console.error("Error adding category:", error);
    }
  };

  const handleUpdateCategory = async (updatedCategory: Category) => {
    try {
      const res = await updateCategory(updatedCategory.id!, updatedCategory);  
      setCategories(categories =>
        categories.map(cat => cat.id === updatedCategory.id ? updatedCategory : cat)
      );    
    } catch (error) {
      console.error("Error updating category:", error);
    }
  };

  const handleDeleteCategory = async (categoryId: string) => {
    try {
      const res = await deleteCategory(categoryId);  
      setCategories(categories =>
        categories.filter(cat => cat.id !== categoryId)
      );    
    } catch (error) {
      console.error("Error delete category:", error);
    }
  };

  return (
    <>
      <div className="container mx-auto px-4 py-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-bold">Categories</h1>
          <button
            className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
            onClick={() => setIsAddCategory(true)}
          >
            Add New
          </button>
        </div>

        <CategoriesTable categories={categories} 
                         loading={loading}
                         setSelectedCategory={setSelectedCategory}
                         handleDeleteCategory={handleDeleteCategory} />
      </div>

      {isAddCategory && (
        <AddCategoryForm
          closePopup={() => setIsAddCategory(false)}
          handleAddCategory={handleAddCategory}
        />
      )}

      {selectedCategory && (
        <div className="mt-8 flex justify-center">
          <EditCategoryForm category={selectedCategory}
                        closePopup={() => setSelectedCategory(null)}
                        handleUpdateCategory={handleUpdateCategory} />
        </div>
      )}
    </>
  );
};

export default CategoriesPage;
