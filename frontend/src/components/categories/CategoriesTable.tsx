import React from "react";
import { Category } from "@/types/category";

type Props = {
  categories: Category[];
  loading: boolean;
  setSelectedCategory: (category: Category) => void;
  handleDeleteCategory: (categoryId: string) => void;
};

const CategoriesTable = ({ categories, loading, setSelectedCategory, handleDeleteCategory }: Props) => {
  console.log('render CategoriesTable')

  if (loading) return <p>Loading...</p>;

  return (
    <table className="w-full border">
      <thead>
        <tr className="bg-gray-100">
          <th className="p-2 border">Name</th>
          <th className="p-2 border">URL</th>
          <th className="p-2 border">Info</th>
          <th className="p-2 border">Image</th>
          <th className="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        {categories.map((cat) => (
          <tr key={cat.id}>
            <td className="p-2 border">{cat.name}</td>
            <td className="p-2 border">{cat.url_name}</td>
            <td className="p-2 border">{cat.info}</td>
            <td className="p-2 border">
              <img src={cat.img_url} alt={cat.name} className="w-16 h-16 object-cover" />
            </td>
            <td className="p-2 border cursor-pointer hover:bg-gray-100" onClick={() => handleDeleteCategory(cat.id!)}>Delete</td>
            <td className="p-2 border cursor-pointer hover:bg-gray-100" onClick={() => setSelectedCategory(cat)}>Edit</td>
          </tr>
        ))}
      </tbody>
    </table>
  );
};

export default CategoriesTable;