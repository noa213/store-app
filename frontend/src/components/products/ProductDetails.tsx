'use client';

import { useParams, useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import { notFound } from 'next/navigation';
import { getProductById } from '@/api/productApi';
import { Product } from '@/types/product';
import { GiSkeleton } from 'react-icons/gi';
// import { Skeleton } from "@/components/ui/skeleton";

export default function ProductDetails() {
    const { id } = useParams();
    const router = useRouter();
    const [product, setProduct] = useState<Product | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchProduct = async () => {
            if (!id || typeof id !== 'string') return;

            try {
                const res = await getProductById(Number(id));
                if (!res) {
                    notFound();
                } else {
                    setProduct(res);
                }
            } catch (error) {
                console.error('Failed to fetch product:', error);
                notFound();
            } finally {
                setLoading(false);
            }
        };

        fetchProduct();
    }, [id]);
    
    if (loading) return <p className="text-center mt-10 text-gray-500">Loading product...</p>;
    if (!product) return <p className="text-center mt-10 text-red-500">Product not found</p>;

    return (
        <main className="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg mt-8">
            <button
                onClick={() => router.back()}
                className="mb-4 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded"
            >
                ← Back to Producs Management
            </button>

            <div className="flex flex-col md:flex-row gap-6">
                <img
                    src={product.img_url}
                    alt={product.name}
                    className="w-full md:w-1/2 rounded-md border"
                />
                <div>
                    <h1 className="text-3xl font-bold mb-2 text-gray-800">{product.name}</h1>
                    <p className="text-xl text-green-600 font-semibold mb-4">{product.price} ₪</p>
                    <p className="text-gray-600">{product.info}</p>
                </div>
            </div>
        </main>
    );
}
