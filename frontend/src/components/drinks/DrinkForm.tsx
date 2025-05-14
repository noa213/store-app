"use client";

import { useEffect, useState } from "react";
import { getDrinkById, createDrink, updateDrink } from "@/api/drinkApi";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { Drink } from "@/types/drink";

type DrinkFormProps = {
  drinkId?: string;
  onSuccess?: () => void;
  onCancel?: () => void;
};

const DrinkForm = ({ drinkId, onSuccess, onCancel }: DrinkFormProps) => {
  const [formData, setFormData] = useState({
    name: "",
    ml: "",
    price: "",
  });

  const isEditMode = !!drinkId;

  useEffect(() => {
    if (isEditMode) {
      fetchDrink();
    } else {
      setFormData({
        name: "",
        ml: "",
        price: "",
      });
    }
  }, [drinkId]);
  
  const fetchDrink = async () => {
    try {
      const data = await getDrinkById(drinkId!);
      setFormData({
        name: data.name,
        ml: data.ml.toString(),
        price: data.price.toString(),
      });
    } catch (error: any) {
      toast.error("Failed to load drink data.");
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (isEditMode) {
        await updateDrink(drinkId!, formData);
        toast.success("Drink updated successfully!");
      } else {
        await createDrink(formData);
        toast.success("Drink created successfully!");
      }
      onSuccess?.(); 
    } catch (error: any) {
      toast.error("Failed to submit drink.");
    }
  };

  return (
    <div className="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
      <ToastContainer />
      <div className="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <h1 className="text-2xl font-bold mb-4 text-center">
          {isEditMode ? "Edit Drink" : "Create Drink"}
        </h1>
        <form onSubmit={handleSubmit} className="space-y-4">
          <input
            type="text"
            name="name"
            value={formData.name}
            placeholder="Drink Name"
            onChange={handleChange}
            required
            className="w-full px-4 py-2 border border-gray-300 rounded"
          />
          <input
            type="number"
            name="ml"
            value={formData.ml}
            placeholder="Volume (ml)"
            onChange={handleChange}
            required
            className="w-full px-4 py-2 border border-gray-300 rounded"
          />
          <input
            type="number"
            name="price"
            value={formData.price}
            placeholder="Price"
            onChange={handleChange}
            step="0.01"
            required
            className="w-full px-4 py-2 border border-gray-300 rounded"
          />
          <div className="flex justify-end space-x-4 mt-6">
            <button
              type="button"
              onClick={onCancel}
              className="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500"
            >
              Cancel
            </button>
            <button
              type="submit"
              className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              {isEditMode ? "Update" : "Create"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default DrinkForm;
