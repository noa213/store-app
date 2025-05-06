"use client";
import React, { useEffect, useState } from "react";
import { getAllCategories } from '@/lib/api/categoryApi';
import { Category } from '@/types/category';

const CategoriesTable = () => {
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
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

    fetchCategories();
  }, []);

  if (loading) return <p className="text-center text-gray-600">Loading...</p>

  return (
    <div className="container mx-auto p-6 bg-white shadow-lg rounded-lg">
      <h2 className="text-3xl font-bold mb-6 text-center text-gray-800">Categories Management</h2>
      <table className="w-full table-auto border-collapse border border-gray-300 shadow-sm rounded-lg">
        <thead className="bg-gray-100 text-gray-700">
          <tr>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Name</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">URL</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Info</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">IMG</th>
          </tr>
        </thead>
        <tbody>
          {categories.map((category: Category) => (
            <tr key={category.id} className="hover:bg-gray-50">
              <td className="px-6 py-4 border-b border-gray-200">{category.name}</td>
              <td className="px-6 py-4 border-b border-gray-200">{category.url_name}</td>
              <td className="px-6 py-4 border-b border-gray-200">{category.info}</td>
              <td className="px-6 py-4 border-b border-gray-200">{category.img_url}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default CategoriesTable;
