"use client";
import { useForm, SubmitHandler } from "react-hook-form";
import { editCcategoryForm } from "@/validations/category";
import { zodResolver } from "@hookform/resolvers/zod";
import { useState } from "react";
import { z } from "zod";
import { Category } from "@/types/category";

type FormData = z.infer<typeof editCcategoryForm>;

interface EditCategoryFormProps {
  category: Category;
  closePopup: () => void;
  handleUpdateCategory: (data: Category) => void;
}

const EditCategoryForm: React.FC<EditCategoryFormProps> = ({
  category,
  closePopup,
  handleUpdateCategory,
}) => {
  console.log('render EditCategoryForm')

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<FormData>({
    resolver: zodResolver(editCcategoryForm),
    defaultValues: {
      name: category.name,
      url_name: category.url_name,
      info: category.info,
      img_url: category.img_url,
    },
  });

  const [error, setError] = useState("");

  const onSubmit: SubmitHandler<FormData> = async (data) => {
    try {
      const updatedCategory = {
        ...category,
        ...data,
      };
      handleUpdateCategory(updatedCategory);
    } catch (err: any) {
      console.error(err);
      setError(err?.message || "Something went wrong");
    } finally {
      closePopup();
    }
  };

  return (
    <div className="max-w-md mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
      <h1 className="text-2xl font-semibold text-center text-gray-800 mb-6">
        Edit Category
      </h1>

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <div>
          <label className="block mb-1 font-medium">Name</label>
          <input
            type="text"
            {...register("name")}
            className="w-full border rounded px-3 py-2"
          />
          {errors.name && (
            <p className="text-red-500 text-sm">{errors.name.message}</p>
          )}
        </div>

        <div>
          <label className="block mb-1 font-medium">URL Name</label>
          <input
            type="text"
            {...register("url_name")}
            className="w-full border rounded px-3 py-2"
          />
          {errors.url_name && (
            <p className="text-red-500 text-sm">{errors.url_name.message}</p>
          )}
        </div>

        <div>
          <label className="block mb-1 font-medium">Info</label>
          <textarea
            {...register("info")}
            className="w-full border rounded px-3 py-2"
          />
          {errors.info && (
            <p className="text-red-500 text-sm">{errors.info.message}</p>
          )}
        </div>

        <div>
          <label className="block mb-1 font-medium">Image URL</label>
          <input
            type="text"
            {...register("img_url")}
            className="w-full border rounded px-3 py-2"
          />
          {errors.img_url && (
            <p className="text-red-500 text-sm">{errors.img_url.message}</p>
          )}
        </div>

        {error && <p className="text-red-500">{error}</p>}

        <div className="flex justify-end space-x-4 mt-4">
          <button
            type="button"
            onClick={closePopup}
            className="px-4 py-2 bg-gray-300 rounded"
          >
            Cancel
          </button>
          <button
            type="submit"
            className="px-4 py-2 bg-blue-600 text-white rounded"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  );
};

export default EditCategoryForm;
