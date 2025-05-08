import React from "react";
import { Category } from "@/app/types/category";
import { IoTrashOutline } from "react-icons/io5";

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
          <th className="p-2 border">Delete</th>
        </tr>
      </thead>
      <tbody>
        {categories.map((cat) => (
          <tr key={cat.id} className="cursor-pointer hover:bg-gray-100">
            <td className="p-2 border" onClick={() => setSelectedCategory(cat)}>{cat.name}</td>
            <td className="p-2 border" onClick={() => setSelectedCategory(cat)}>{cat.url_name}</td>
            <td className="p-2 border" onClick={() => setSelectedCategory(cat)}>{cat.info}</td>
            <td className="p-2 border" onClick={() => setSelectedCategory(cat)}>
              <img src={cat.img_url} alt={cat.name} className="w-16 h-16 object-cover" />
            </td>
            <td className="p-2 border flex justify-center items-center">
              <IoTrashOutline onClick={() => handleDeleteCategory(cat.id!)}/>
            </td>
          </tr>
        ))}
      </tbody>
    </table>
  );
};

export default CategoriesTable;
