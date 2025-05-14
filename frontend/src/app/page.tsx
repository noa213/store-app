'use client';
import React, { useState } from "react";
import SignUp from "@/components/auth/SignUp";
import Login from "@/components/auth/Login";
import { register, login, logout } from "@/api/authApi";

export default function Home() {
  const [isSignUp, setIsSignUp] = useState<boolean>(false);

  return (
    <div className="flex justify-center items-center min-h-screen bg-gray-100">
      <div className="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <button 
          onClick={logout} 
          className="w-full text-center py-2 bg-red-500 text-white rounded mb-4 hover:bg-red-600"
        >
          Logout
        </button>

        {isSignUp ? (
          <SignUp registerUser={register} closeSignUp={()=>setIsSignUp(false)} />
        ) : (
          <Login login={login} />
        )}

        <button 
          onClick={() => setIsSignUp(!isSignUp)} 
          className="w-full mt-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
          {isSignUp ? "Already have an account? Login" : "Don't have an account? Sign Up"}
        </button>
      </div>
    </div>
  );
}
