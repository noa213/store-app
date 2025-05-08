"use client";

import React, { useEffect, useState } from "react";
import { getUserById } from "@/api/userApi";
import { User } from "@/types/user";

type ViewUserProps = {
  userId: string;
  onClose: () => void;
};

const ViewUser: React.FC<ViewUserProps> = ({ userId, onClose }) => {
  const [user, setUser] = useState<User | null>(null);

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const userData = await getUserById(userId);
        setUser(userData as User);
      } catch (error) {
        console.error("Error fetching user data:", error);
      }
    };

    fetchUser();
  }, [userId]);

  if (!user) {
    return <p>Loading user details...</p>;
  }

  return (
    <div>
      <h2 className="text-xl font-bold mb-4">User Details</h2>
      <p><strong>Name:</strong> {user.name}</p>
      <p><strong>Email:</strong> {user.email}</p>
      <p><strong>Role:</strong> {user.role}</p>
      <div>
        <button onClick={onClose}>Close</button>
      </div>
    </div>
  );
};

export default ViewUser;
