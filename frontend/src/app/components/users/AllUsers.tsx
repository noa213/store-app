"use client";

import React, { useEffect, useState } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { getUsers, getUserInfo } from "@/app/lib/api/userApi";
import { toast, ToastContainer } from "react-toastify";
import { User } from "@/app/types/user";
import "react-toastify/dist/ReactToastify.css";

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
    if (typeof window !== "undefined") {
      getAuthUser();
      fetchUsers();
    }
  }, []);

  useEffect(() => {
    if (!searchParams) return;

    if (searchParams.get("adduser")) {
      toast.success("User added successfully!", { position: "top-right" });
    } else if (searchParams.get("error")) {
      toast.error(searchParams.get("error")!, { position: "top-right" });
    }
  }, [searchParams]);

  // const handleDelete = async (id: string) => {
  //   if (window.confirm("Are you sure you want to delete this user?")) {
  //     try {
  //       await deleteUser(id);
  //       toast.success("User deleted successfully!", { position: "top-right" });
  //       fetchUsers();
  //     } catch (error: any) {
  //       console.error("Error deleting user:", error.response?.data || error.message);
  //       toast.error("Failed to delete user.", { position: "top-right" });
  //     }
  //   }
  // };

  const handleView = (id: string) => {
    router.push(`/users/${id}`);
  };

  return (
    <div className="container mx-auto p-6 bg-white shadow-lg rounded-lg">
      <ToastContainer />
      <h2 className="text-3xl font-bold mb-6 text-center text-gray-800">Users Management</h2>
      <table className="w-full table-auto border-collapse border border-gray-300">
        <thead className="bg-gray-100">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {users.map((user) => (
            <tr key={user.id}>
              <td>{user.name}</td>
              <td>{user.email}</td>
              <td>{user.role}</td>
              <td>
                <button onClick={() => handleView(user.id)}>View</button>
                {(authUser.role === "admin" || authUser.role === "superadmin") && (
                  <>
                    {/* <button onClick={() => router.push(`/users/edit/${user._id}`)}>Edit</button>
                    <button onClick={() => handleDelete(user._id)}>Delete</button> */}
                  </>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default AllUsers;
