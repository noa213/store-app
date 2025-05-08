"use client";

import React, { useEffect, useState } from "react";
import { useRouter, useParams } from "next/navigation";
import { getUserById, updateUser } from "@/api/userApi";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

// טיפוס עבור המשתמש
interface UserData {
  name: string;
  email: string;
  role: string;
}

export default function EditUser() {
  const router = useRouter();
  const params = useParams();
  const id = params?.id as string;
  const [user, setUser] = useState<UserData>({ name: "", email: "", role: "" });

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const data = await getUserById(id);

        if (
          typeof data === "object" &&
          data !== null &&
          "name" in data &&
          "email" in data &&
          "role" in data
        ) {
          setUser(data as UserData);
        } else {
          throw new Error("Invalid user data from server");
        }
      } catch (error: any) {
        toast.error("Failed to load user", { position: "top-right" });
        router.push("/users?error=notfound");
      }
    };

    if (id) fetchUser();
  }, [id, router]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    setUser({ ...user, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await updateUser(id, user);
      toast.success("User updated successfully!", { position: "top-right" });
      router.push("/users");
    } catch (error: any) {
      toast.error(error.response?.data?.message || "Failed to update user", {
        position: "top-right",
      });
    }
  };

  return (
    <div className="max-w-xl mx-auto mt-10 bg-white p-6 shadow-lg rounded">
      <ToastContainer />
      <h2 className="text-2xl font-bold mb-4">Edit User</h2>
      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="text"
          name="name"
          value={user.name}
          onChange={handleChange}
          className="w-full p-2 border rounded"
          required
        />
        <input
          type="email"
          name="email"
          value={user.email}
          onChange={handleChange}
          className="w-full p-2 border rounded"
          required
        />
        <select
          name="role"
          value={user.role}
          onChange={handleChange}
          className="w-full p-2 border rounded"
          required
        >
          <option value="">Select role</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
          <option value="superadmin">Superadmin</option>
        </select>
        <button type="submit" className="px-4 py-2 bg-blue-500 text-white rounded">
          Update User
        </button>
      </form>
    </div>
  );
}
