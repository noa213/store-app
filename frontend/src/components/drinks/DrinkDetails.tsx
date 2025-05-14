'use client';

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { useParams } from "next/navigation";
import { getDrinkById } from "@/api/drinkApi";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { Drink } from "@/types/drink";

const DrinkDetails = ({ drinkId }: { drinkId: string }) => {
    console.log("drinkId", drinkId);
  const router = useRouter();
  const [drink, setDrink] = useState<Drink | null>(null);

  useEffect(() => {
    if (drinkId) fetchDrinkDetails();
  }, []);

  const fetchDrinkDetails = async () => {
    try {
      const drinkByUserId = await getDrinkById(drinkId); 
      setDrink(drinkByUserId);
    } catch (error: any) {
      console.error("Error fetching drink details:", error.response?.data || error.message);
      toast.error("Failed to fetch drink details", { position: "top-right" });
    }
  };

  if (!drink) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-gray-100">
        <p className="text-xl font-bold text-gray-600">Loading...</p>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 py-10">
      <ToastContainer />
      <div className="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div className="p-8">
          <h1 className="text-4xl font-extrabold text-gray-800 text-center mb-6">Drink Details</h1>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-8">
            <div className="text-center">
              <h2 className="text-lg font-semibold text-gray-500">Name</h2>
              <p className="text-2xl font-bold text-gray-800">{drink.name}</p>
            </div>
            <div className="text-center">
              <h2 className="text-lg font-semibold text-gray-500">Volume (ml)</h2>
              <p className="text-2xl font-bold text-gray-800">{drink.ml}</p>
            </div>
            <div className="text-center">
              <h2 className="text-lg font-semibold text-gray-500">Price</h2>
              <p className="text-2xl font-bold text-gray-800">${Number(drink.price).toFixed(2)}</p>
            </div>
            {/* <div className="text-center">
              <h2 className="text-lg font-semibold text-gray-500">Created By</h2>
              <p className="text-2xl font-bold text-gray-800">{drink.user?.name || "N/A"}</p>
            </div> */}
          </div>

          <div className="flex justify-center mt-10 space-x-6">
            <button
              className="bg-blue-500 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 transition transform hover:scale-105"
              onClick={() => router.push(`/drinks/edit/${drink.id}`)}
            >
              Edit Drink
            </button>
            <button
              className="bg-gray-500 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-gray-600 transition transform hover:scale-105"
              onClick={() => router.push("/drinks")}
            >
              Back to Drinks
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DrinkDetails;
