"use client";
import React from "react";

const DrinkTable = () => {
  const drinks = [
    { _id: "1", name: "Coca Cola", ml: 500, price: 6.5 },
    { _id: "2", name: "Sprite", ml: 330, price: 5.0 },
    { _id: "3", name: "Fanta", ml: 500, price: 6.0 },
  ];

  return (
    <div className="container mx-auto p-6 bg-white shadow-lg rounded-lg">
      <h2 className="text-3xl font-bold mb-6 text-center text-gray-800">Drinks Management</h2>
      <table className="w-full table-auto border-collapse border border-gray-300 shadow-sm rounded-lg">
        <thead className="bg-gray-100 text-gray-700">
          <tr>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Name</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Volume (ml)</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Price</th>
          </tr>
        </thead>
        <tbody>
          {drinks.map((drink) => (
            <tr key={drink._id} className="hover:bg-gray-50">
              <td className="px-6 py-4 border-b border-gray-200 text-gray-700">{drink.name}</td>
              <td className="px-6 py-4 border-b border-gray-200 text-gray-700">{drink.ml}</td>
              <td className="px-6 py-4 border-b border-gray-200 text-gray-700">
                ${drink.price.toFixed(2)}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default DrinkTable;
