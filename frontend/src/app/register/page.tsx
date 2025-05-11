"use client";
import React, { useEffect, useState } from "react";
import SignUp from "@/components/register/SignUp";
import Login from "@/components/register/Login";
import { register, login, logout } from "@/api/registerApi";

const RegisterPage = () => {

  return (
    <>
      <SignUp registerUser={register} closeSignUp={()=>console.log('!')}/>
      <Login login={login}/>
      <button className="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded" onClick={logout}>
        Logout
      </button>
    </>
  );
};

export default RegisterPage;
