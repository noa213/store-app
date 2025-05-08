"use client";

import React, { useEffect, useState } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { getUsers, deleteUser, getUserInfo } from "@/app/lib/api/userApi";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

interface User {
  _id: string;
  name: string;
  email: string;
  role: string;
}

const AllUsers = () => {

  const [users, setUsers] = useState<User[]>([]);
  const [authUser, setAuthUser] = useState<Partial<User>>({});
  const router = useRouter();
  const searchParams = useSearchParams();

  const getAuthUser = async () => {
    try {
      const user = await getUserInfo();
      setAuthUser(user);
    } catch (error: any) {
      console.error("Error fetching user info:", error.response?.data || error.message);
      toast.error("Failed to fetch user info. Redirecting to Home Page.", { position: "top-right" });
      router.push("/");
    }
  };

  const fetchUsers = async () => {
    try {
      const data = await getUsers();
      setUsers(data);
    } catch (error: any) {
      console.error("Error fetching users:", error.response?.data || error.message);
      toast.error("Failed to fetch users.", { position: "top-right" });
    }
  };

  useEffect(() => {
    if (searchParams.get("adduser")) {
      toast.success("User added successfully!", { position: "top-right" });
    } else if (searchParams.get("error")) {
      toast.error(searchParams.get("error")!, { position: "top-right" });
    }
  }, [searchParams]);
  type Props = {
    refreshSignal?: boolean;
  };

  useEffect(() => {
    getAuthUser();
    fetchUsers();
  }, []);

  // useEffect(() => {
  //   if (refreshSignal) {
  //     fetchUsers();
  //   }
  // }, [refreshSignal]);

  useEffect(() => {
    if (!searchParams) return;

    if (searchParams.get("adduser")) {
      toast.success("User added successfully!", { position: "top-right" });
    } else if (searchParams.get("error")) {
      toast.error(searchParams.get("error")!, { position: "top-right" });
    }
  }, [searchParams]);

  const handleDelete = async (id: string) => {
    if (window.confirm("Are you sure you want to delete this user?")) {
      try {
        await deleteUser(id);
        toast.success("User deleted successfully!", { position: "top-right" });
        fetchUsers();
      } catch (error: any) {
        console.error("Error deleting user:", error.response?.data || error.message);
        toast.error("Failed to delete user.", { position: "top-right" });
      }
    }
  };

  const handleEdit = (id: string) => {
    router.push(`/users/edit/${id}`);
  };

  const handleView = (id: string) => {
    router.push(`/users/${id}`);
  };

  return (
    <div className="container mx-auto p-6 bg-white shadow-lg rounded-lg">
      <ToastContainer />
      <h2 className="text-3xl font-bold mb-6 text-center text-gray-800">Users Management</h2>
      <table className="w-full table-auto border-collapse border border-gray-300 shadow-sm rounded-lg">
        <thead className="bg-gray-100 text-gray-700">
          <tr>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Name</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Email</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Role</th>
            <th className="px-6 py-3 border-b border-gray-200 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          {users.map((user) => (
            <tr key={user._id} className="hover:bg-gray-50">
              <td className="px-6 py-4 border-b border-gray-200 text-gray-700">{user.name}</td>
              <td className="px-6 py-4 border-b border-gray-200 text-gray-700">{user.email}</td>
              <td className="px-6 py-4 border-b border-gray-200 text-gray-700">{user.role}</td>
              <td className="px-6 py-4 border-b border-gray-200 flex flex-wrap gap-2">
                <button
                  className="px-3 py-1 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded shadow"
                  onClick={() => handleView(user._id)}
                >
                  View
                </button>
                {(authUser.role === "admin" || authUser.role === "superadmin") && (
                  <>
                    <button
                      className="px-3 py-1 text-sm font-medium text-white bg-green-500 hover:bg-green-600 rounded shadow"
                      onClick={() => handleEdit(user._id)}
                    >
                      Edit
                    </button>
                    <button
                      className="px-3 py-1 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded shadow"
                      onClick={() => handleDelete(user._id)}
                    >
                      Delete
                    </button>
                  </>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      <div className="flex justify-between mt-6">
        <button
          className="px-6 py-3 text-white bg-gray-700 hover:bg-gray-800 font-medium text-sm rounded shadow"
          onClick={() => router.push("/")}
        >
          Back to Dashboard
        </button>
        <button
          className="px-6 py-3 text-white bg-green-500 hover:bg-green-600 font-medium text-sm rounded shadow"
          onClick={() => router.push("/")}
        >
          Add New User
        </button>
        {(authUser.role === "admin" || authUser.role === "superadmin") && (
          <button
            className="px-6 py-3 text-white bg-green-500 hover:bg-green-600 font-medium text-sm rounded shadow"
            onClick={() => router.push("/users/add")}
          >
            Add New User
          </button>

        )}
      </div>
    </div>
  );
};

export default AllUsers;
