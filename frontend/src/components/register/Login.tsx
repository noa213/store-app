"use client";

import React, { useState } from "react";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

interface LoginProps {
    login: (data: any) => void;
  };
  
export default function Login({login}:LoginProps) {
  const [user, setUser] = useState({
    email: "",
    password: "",
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setUser((prevUser) => ({ ...prevUser, [name]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await login(user);
    } catch (error: any) {
      console.error("Error login:", error.response?.data || error.message);
      toast.error("Failed in login.", { position: "top-right" });
    }
  };

  return (
    <div className="max-w-md mx-auto p-6 bg-white shadow-md rounded">
      <ToastContainer />
      <h2 className="text-2xl font-bold mb-4">Login User</h2>
      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="email"
          name="email"
          value={user.email}
          onChange={handleChange}
          placeholder="Email"
          required
          className="w-full p-2 border border-gray-300 rounded"
        />
        <input
          type="password"
          name="password"
          value={user.password}
          onChange={handleChange}
          placeholder="Password"
          required
          className="w-full p-2 border border-gray-300 rounded"
        />
        <div className="flex justify-between">
          <button
            type="submit"
            className="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded"
          >
            Login
          </button>
        </div>
      </form>
    </div>
  );
}
