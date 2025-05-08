"use client";

import React, { useState, useEffect } from "react";
import { updateUser, getUserById } from "@/api/userApi";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

interface EditUserProps {
  userId: string;
  onClose: () => void;
  onUserUpdated: () => void;
}

const EditUser: React.FC<EditUserProps> = ({ userId, onClose, onUserUpdated }) => {
  const [user, setUser] = useState({
    name: "",
    email: "",
    role: "",
    password: "",
  });

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const userData = await getUserById(userId);
        setUser({
          name: userData.name,
          email: userData.email,
          role: userData.role,
          password: "", // לא נרצה להציג את הסיסמה
        });
      } catch (error: any) {
        console.error("Error fetching user data:", error.response?.data || error.message);
        toast.error("Failed to fetch user data.", { position: "top-right" });
      }
    };

    if (userId) {
      fetchUser();
    }
  }, [userId]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setUser((prevUser) => ({ ...prevUser, [name]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await updateUser(userId, user);
      toast.success("User updated successfully!", { position: "top-right" });
      onUserUpdated(); // Refresh the user list
      onClose();
    } catch (error: any) {
      console.error("Error updating user:", error.response?.data || error.message);
      toast.error("Failed to update user.", { position: "top-right" });
    }
  };

  return (
    <div className="max-w-md mx-auto p-6 bg-white shadow-md rounded relative">
      <ToastContainer />
      <h2 className="text-2xl font-bold mb-4">Edit User</h2>
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
          placeholder="Password (leave empty to keep current password)"
          className="w-full p-2 border border-gray-300 rounded"
        />
        <div className="flex justify-between">
          <button
            type="submit"
            className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
          >
            Save Changes
          </button>
          <button
            type="button"
            onClick={onClose}
            className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  );
};

export default EditUser;
