import React from "react";
import DrinkDetails from "@/components/drinks/DrinkDetails";

export default function DrinkPage({ params }: { params: { id: string } }) {
    return <DrinkDetails drinkId={params.id} />;
}
