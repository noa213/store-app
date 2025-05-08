"use client";

import { useEffect, useState } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { getProducts, deleteProduct } from "@/api/productApi";
import { Product } from "@/types/product";

const ProductTable = () => {
    const [products, setProducts] = useState<Product[]>([]);
    const router = useRouter();
    const searchParams = useSearchParams();

    useEffect(() => {
        const added = searchParams.get("added");
        const error = searchParams.get("error");

        if (added) {
            toast.success("Product added successfully!", { position: "top-right" });
        } else if (error) {
            toast.error(error, { position: "top-right" });
        }
    }, [searchParams]);

    useEffect(() => {
        fetchProducts();
    }, []);

    const fetchProducts = async () => {
        try {
            const { data } = await getProducts();
            setProducts(data);
        } catch (error: any) {
            console.error("Error fetching products:", error);
            toast.error("Failed to fetch products.", { position: "top-right" });
        }
    };

    const handleDelete = async (id: string) => {
        if (window.confirm("Are you sure you want to delete this product?")) {
            try {
                await deleteProduct(id);
                toast.success("Product deleted successfully!", {
                    position: "top-right",
                });
                fetchProducts();
            } catch (error: any) {
                console.error("Error deleting product:", error);
                toast.error("Failed to delete product.", { position: "top-right" });
            }
        }
    };

    const handleEdit = (id: string) => {
        router.push(`/products/edit/${id}`);
    };

    const handleView = (id: string) => {
        router.push(`/products/details/${id}`);
    };

    return (
        <div className="bg-white p-6 rounded-lg shadow-md">
            <ToastContainer />
            <h2 className="text-3xl font-bold mb-6 text-center text-gray-800">
                Products Management
            </h2>
            <table className="w-full border border-collapse border-gray-200 table-auto">
                <thead className="bg-gray-200">
                    <tr>
                        <th className="px-4 py-2 border border-gray-300">Image</th>
                        <th className="px-4 py-2 border border-gray-300">Name</th>
                        <th className="px-4 py-2 border border-gray-300">Category</th>
                        <th className="px-4 py-2 border border-gray-300">Price</th>
                        <th className="px-4 py-2 border border-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {products.map((product: any) => (
                        <tr key={product._id}>
                            <td className="px-4 py-2 border border-gray-300">
                                <img
                                    src={product.img_url}
                                    alt={product.name}
                                    className="h-16 w-16 object-cover rounded"
                                />
                            </td>
                            <td className="px-4 py-2 border border-gray-300">{product.name}</td>
                            <td className="px-4 py-2 border border-gray-300">{product.category_url}</td>
                            <td className="px-4 py-2 border border-gray-300">${product.price}</td>
                            <td className="px-4 py-2 border border-gray-300">
                                <button
                                    className="px-2 py-1 text-white bg-blue-500 rounded mr-2"
                                    onClick={() => handleView(product._id)}
                                >
                                    View
                                </button>
                                <button
                                    className="px-2 py-1 text-white bg-green-500 rounded mr-2"
                                    onClick={() => handleEdit(product._id)}
                                >
                                    Edit
                                </button>
                                <button
                                    className="px-2 py-1 text-white bg-red-500 rounded"
                                    onClick={() => handleDelete(product._id)}
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
            <div className="flex justify-between mt-4">
                <button
                    className="px-4 py-2 text-white bg-gray-500 rounded"
                    onClick={() => router.push("/")}
                >
                    Back to Dashboard
                </button>
                <button
                    className="px-4 py-2 text-white bg-green-500 rounded"
                    onClick={() => router.push("/products/add")}
                >
                    Add New Product
                </button>
            </div>
        </div>
    );
};

export default ProductTable;
