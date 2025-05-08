"use client";

import React, { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { getUsers, getUserInfo, deleteUser } from "@/api/userApi";
import { toast, ToastContainer } from "react-toastify";
import { User } from "@/types/user";
import AddUser from "@/components/users/AddUser";
import ViewUser from "@/components/users/ViewUser";
import EditUser from "@/components/users/EditUser";
import "react-toastify/dist/ReactToastify.css";

const AllUsers = () => {
  const [users, setUsers] = useState<User[]>([]);
  const [authUser, setAuthUser] = useState<Partial<User>>({});
  const [showAddUser, setShowAddUser] = useState(false);
  const [showViewUser, setShowViewUser] = useState(false);
  const [showEditUser, setShowEditUser] = useState(false);
  const [selectedUserId, setSelectedUserId] = useState<string | null>(null);

  const router = useRouter();

  const getAuthUser = async () => {
    try {
      const user = await getUserInfo();
      setAuthUser(user);
    } catch (error: any) {
      toast.error("Failed to fetch user info.");
      router.push("/");
    }
  };

  const fetchUsers = async () => {
    try {
      const data = await getUsers();
      setUsers(data);
    } catch (error: any) {
      toast.error("Failed to fetch users.");
    }
  };

  useEffect(() => {
    getAuthUser();
    fetchUsers();
  }, []);

  const handleDelete = async (id: string) => {
    // if (authUser.role !== "admin" && authUser.role !== "superadmin") return;
    if (window.confirm("Are you sure you want to delete this user?")) {
      await deleteUser(id);
      fetchUsers();
    }
  };

  const handleEdit = (id: string) => {
    setSelectedUserId(id);
    setShowEditUser(true);
  };

  const handleView = (id: string) => {
    setSelectedUserId(id);
    setShowViewUser(true);
  };

  const handleCloseView = () => {
    setShowViewUser(false);
    setSelectedUserId(null);
  };

  return (
    <div className="container mx-auto p-6">
      <ToastContainer />
      <h2 className="text-3xl font-bold mb-4">Users Management</h2>
      <button onClick={() => setShowAddUser(true)} className="bg-green-500 px-4 py-2 text-white rounded">Add User</button>

      <table className="w-full mt-4">
        <thead>
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
                    <button onClick={() => handleEdit(user.id)}>Edit</button>
                    <button onClick={() => handleDelete(user.id)}>Delete</button>
                  </>
                )}
                 <button onClick={() => handleEdit(user.id)}>Edit</button>
                 <button onClick={() => handleDelete(user.id)}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {showAddUser && <AddUser onClose={() => setShowAddUser(false)} onUserAdded={fetchUsers} />}
      {showViewUser && selectedUserId && <ViewUser userId={selectedUserId} onClose={handleCloseView} />}
      {showEditUser && selectedUserId && <EditUser userId={selectedUserId} onClose={() => setShowEditUser(false)} onUserUpdated={fetchUsers} />}

      <button onClick={() => router.push("/")} className="mt-4 bg-blue-500 px-4 py-2 text-white rounded">Back to Dashboard</button>
    </div>
  );
};

export default AllUsers;
