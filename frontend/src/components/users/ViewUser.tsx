"use client";

import React, { useEffect, useState } from "react";
import { getUserById } from "@/app/lib/api/userApi";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

interface User {
  id: string;
  name: string;
  email: string;
  role: string;
}

interface Props {
  userId: string;
}

const ViewUser: React.FC<Props> = ({ userId }) => {
  const [user, setUser] = useState<User | null>(null);

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const data = await getUserById(userId);
        setUser(data as User);
      } catch (error: any) {
        toast.error("Failed to load user");
      }
    };

    fetchUser();
  }, [userId]);

  if (!user) return <div>Loading...</div>;

  return (
    <div className="max-w-xl mx-auto mt-10 bg-white p-6 shadow-lg rounded">
      <ToastContainer />
      <h2 className="text-2xl font-bold mb-4">User Details</h2>
      <p><strong>Name:</strong> {user.name}</p>
      <p><strong>Email:</strong> {user.email}</p>
      <p><strong>Role:</strong> {user.role}</p>
    </div>
  );
};

export default ViewUser;
