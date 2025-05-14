"use client";

import React, { useState } from "react";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

interface SignUpProps {
    closeSignUp: () => void
    registerUser: (data: any) => void;
  };
  
export default function SignUp({registerUser, closeSignUp}:SignUpProps) {
  const [user, setUser] = useState({
    name: "",
    email: "",
    role: "",
    password: "",
    password_confirmation: "",
    favs_ar: []
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setUser((prevUser) => ({ ...prevUser, [name]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await registerUser(user);
    } catch (error: any) {
      console.error("Error adding user:", error.response?.data || error.message);
      toast.error("Failed to add user.", { position: "top-right" });
    }
  };

  return (
    <div className="max-w-md mx-auto p-6 bg-white shadow-md rounded">
      <ToastContainer />
      <h2 className="text-2xl font-bold mb-4">Add New User</h2>
      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="text"
          name="name"
          value={user.name}
          onChange={handleChange}
          placeholder="Name"
          required
          className="w-full p-2 border border-gray-300 rounded"
        />
        <input
          type="email"
          name="email"
          value={user.email}
          onChange={handleChange}
          placeholder="Email"
          required
          className="w-full p-2 border border-gray-300 rounded"
        />
        <select
          name="role"
          value={user.role}
          onChange={handleChange}
          required
          className="w-full p-2 border border-gray-300 rounded"
        >
          <option value="">Select Role</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
          <option value="superadmin">Super Admin</option>
        </select>
        <input
          type="password"
          name="password"
          value={user.password}
          onChange={handleChange}
          placeholder="Password"
          required
          className="w-full p-2 border border-gray-300 rounded"
        />
        <input
          type="password"
          name="password_confirmation"
          value={user.password_confirmation}
          onChange={handleChange}
          placeholder="Password confirmation"
          required
          className="w-full p-2 border border-gray-300 rounded"
        />
        <div className="flex justify-between">
          <button
            type="submit"
            className="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded"
          >
            Add User
          </button>
          <button
            type="button"
            className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
            onClick={closeSignUp}
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  );
}
