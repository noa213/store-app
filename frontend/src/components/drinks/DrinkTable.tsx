"use client";
<<<<<<< HEAD
import React from "react";

const DrinkTable = () => {
  const drinks = [
    { _id: "1", name: "Coca Cola", ml: 500, price: 6.5 },
    { _id: "2", name: "Sprite", ml: 330, price: 5.0 },
    { _id: "3", name: "Fanta", ml: 500, price: 6.0 },
  ];

  return (
    <div className="container mx-auto p-6 bg-white shadow-lg rounded-lg">
=======

import React, { useEffect, useState } from "react";
//import { getDrinks, deleteDrink } from "@/api/drinkApi";
import { useRouter, useSearchParams } from "next/navigation";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

interface Drink {
  id: string;
  name: string;
  ml: number;
  price: number;
  user_id: string;
}

const DrinkTable = () => {
  const [drinks, setDrinks] = useState<Drink[]>([]);
  const router = useRouter();
  const searchParams = useSearchParams();

  useEffect(() => {
    const added = searchParams.get("added");
    const error = searchParams.get("error");

    if (added) toast.success("Drink added successfully!", { position: "top-right" });
    if (error) toast.error(error, { position: "top-right" });
  }, [searchParams]);

  useEffect(() => {
    fetchDrinks();
  }, []);

  const fetchDrinks = async () => {
    try {
      //const { data as Drink } = await getDrinks();
     // setDrinks(data);
    } catch (error: any) {
      console.error("Error fetching drinks:", error.response?.data || error.message);
      toast.error("Failed to fetch drinks.", { position: "top-right" });
    }
  };

  const handleDelete = async (id: string) => {
    if (window.confirm("Are you sure you want to delete this drink?")) {
      try {
       // await deleteDrink(id);
        toast.success("Drink deleted successfully!", { position: "top-right" });
        fetchDrinks();
      } catch (error: any) {
        console.error("Error deleting drink:", error.response?.data || error.message);
        toast.error("Failed to delete drink.", { position: "top-right" });
      }
    }
  };

  return (
    <div className="container mx-auto p-6 bg-white shadow-lg rounded-lg">
      <ToastContainer />
>>>>>>> 174f0fe82835bae5c49874e9c547b2b7cb50043a
      <h2 className="text-3xl font-bold mb-6 text-center text-gray-800">Drinks Management</h2>
      <table className="w-full table-auto border-collapse border border-gray-300 shadow-sm rounded-lg">
        <thead className="bg-gray-100 text-gray-700">
          <tr>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Name</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Volume (ml)</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Price</th>
<<<<<<< HEAD
          </tr>
        </thead>
        <tbody>
          {drinks.map((drink) => (
=======
            <th className="px-6 py-3 border-b border-gray-200 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          {drinks.map((drink: any) => (
>>>>>>> 174f0fe82835bae5c49874e9c547b2b7cb50043a
            <tr key={drink._id} className="hover:bg-gray-50">
              <td className="px-6 py-4 border-b border-gray-200 text-gray-700">{drink.name}</td>
              <td className="px-6 py-4 border-b border-gray-200 text-gray-700">{drink.ml}</td>
              <td className="px-6 py-4 border-b border-gray-200 text-gray-700">
                ${drink.price.toFixed(2)}
              </td>
<<<<<<< HEAD
=======
              <td className="px-6 py-4 border-b border-gray-200 flex items-center space-x-2">
                <button
                  className="px-3 py-1 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded shadow"
                  onClick={() => router.push(`/drinks/details/${drink._id}`)}
                >
                  View
                </button>
                <button
                  className="px-3 py-1 text-sm font-medium text-white bg-green-500 hover:bg-green-600 rounded shadow"
                  onClick={() => router.push(`/drinks/edit/${drink._id}`)}
                >
                  Edit
                </button>
                <button
                  className="px-3 py-1 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded shadow"
                  onClick={() => handleDelete(drink._id)}
                >
                  Delete
                </button>
              </td>
>>>>>>> 174f0fe82835bae5c49874e9c547b2b7cb50043a
            </tr>
          ))}
        </tbody>
      </table>
<<<<<<< HEAD
=======
      <div className="flex justify-between mt-6">
        <button
          className="px-6 py-3 text-white bg-gray-700 hover:bg-gray-800 font-medium text-sm rounded shadow"
          onClick={() => router.push("/")}
        >
          Back to Dashboard
        </button>
        <button
          className="px-6 py-3 text-white bg-green-500 hover:bg-green-600 font-medium text-sm rounded shadow"
          onClick={() => router.push("/drinks/add")}
        >
          Add New Drink
        </button>
      </div>
>>>>>>> 174f0fe82835bae5c49874e9c547b2b7cb50043a
    </div>
  );
};

export default DrinkTable;
