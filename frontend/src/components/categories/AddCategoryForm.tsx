"use client";
import { useForm, SubmitHandler } from "react-hook-form";
import { addCategoryForm } from "@/validations/category";
import { zodResolver } from "@hookform/resolvers/zod";
import { useState } from "react";
import { z } from "zod";

type FormData = z.infer<typeof addCategoryForm>;

interface AddCategoryFormProps {
  closePopup: () => void;
  handleAddCategory: (data: FormData) => void;
};

export default function AddCategoryForm({ closePopup, handleAddCategory }: AddCategoryFormProps) {
  const { register, handleSubmit, formState: { errors } } = useForm<FormData>({
    resolver: zodResolver(addCategoryForm),
  });

  const [error, setError] = useState("");

  const onSubmit: SubmitHandler<FormData> = async (data) => {
    try {
      handleAddCategory(data);
    } catch (error: any) {
      setError(error.response?.data?.message || "An error occurred");
    } finally {
      closePopup();
    }
  };

  return (
    <div className="max-w-md mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
      <h1 className="text-2xl font-semibold text-center text-gray-800 mb-6">
        Add New Category
      </h1>
      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <div>
          <input
            type="text"
            placeholder="Category name"
            {...register("name")}
            className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
          {errors.name && (
            <p className="text-red-500 text-sm mt-1">{errors.name.message}</p>
          )}
        </div>
  
        <div>
          <input
            type="text"
            placeholder="URL name"
            {...register("url_name")}
            className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
          {errors.url_name && (
            <p className="text-red-500 text-sm mt-1">{errors.url_name.message}</p>
          )}
        </div>
  
        <div>
          <input
            type="text"
            placeholder="Info"
            {...register("info")}
            className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
          {errors.info && (
            <p className="text-red-500 text-sm mt-1">{errors.info.message}</p>
          )}
        </div>
  
        <div>
          <input
            type="text"
            placeholder="Image URL"
            {...register("img_url")}
            className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
          {errors.img_url && (
            <p className="text-red-500 text-sm mt-1">{errors.img_url.message}</p>
          )}
        </div>
  
        {error && <p className="text-red-500 text-center">{error}</p>}
  
        <button
          type="submit"
          className="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200"
        >
          Add
        </button>
      </form>
    </div>
  );
}
