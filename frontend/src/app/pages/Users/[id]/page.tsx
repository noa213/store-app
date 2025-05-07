import React from "react";
import ViewUser from "../../../components/users/ViewUser";

export default function UserPage({ params }: { params: { id: string } }) {
    return <ViewUser userId={params.id} />;
  }

