'use client';

import { useEffect, useState } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import { getProductById, updateProduct } from '@/api/productApi';

const EditProduct = () => {
    const router = useRouter();
    const searchParams = useSearchParams();
    const productId = searchParams.get('id'); // נניח שה-ID מגיע ב-query param כמו ?id=123

    const [formData, setFormData] = useState({
        name: '',
        price: '',
        info: '',
        img_url: '',
        category_url: '',
    });

    const [loading, setLoading] = useState(false);

    useEffect(() => {
        const fetchProduct = async () => {
            if (!productId) return;
            try {
                const product = await getProductById(Number(productId));
                setFormData({
                    name: product.name || '',
                    price: product.price?.toString() || '',
                    info: product.info || '',
                    img_url: product.img_url || '',
                    category_url: product.category_url || '',
                });
            } catch (err) {
                console.error('Failed to load product:', err);
            }
        };

        fetchProduct();
    }, [productId]);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }));
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        try {
            await updateProduct(productId!, {
                ...formData,
                price: Number(formData.price),
            });
            router.push('/products');
        } catch (err) {
            console.error('Error updating product:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleCancel = () => {
        router.push('/products');
    };

    return (
        <div className="min-h-screen bg-gray-50 flex items-center justify-center p-4">
            <form
                onSubmit={handleSubmit}
                className="bg-white shadow-md rounded-2xl p-8 w-full max-w-md space-y-6"
            >
                <h3>dfdsgsdfgsty</h3>
                <h2 className="text-2xl font-bold text-gray-800 text-center">Edit Product</h2>

                <input
                    name="name"
                    placeholder="Product Name"
                    value={formData.name}
                    onChange={handleChange}
                    required
                    className="w-full border border-gray-300 rounded-lg px-4 py-2"
                />

                <input
                    name="price"
                    type="number"
                    placeholder="Price"
                    value={formData.price}
                    onChange={handleChange}
                    required
                    className="w-full border border-gray-300 rounded-lg px-4 py-2"
                />

                <textarea
                    name="info"
                    placeholder="Description"
                    value={formData.info}
                    onChange={handleChange}
                    className="w-full border border-gray-300 rounded-lg px-4 py-2 h-24 resize-none"
                />

                <input
                    name="category_url"
                    placeholder="Category"
                    value={formData.category_url}
                    onChange={handleChange}
                    className="w-full border border-gray-300 rounded-lg px-4 py-2"
                />

                <input
                    name="img_url"
                    placeholder="Image URL"
                    value={formData.img_url}
                    onChange={handleChange}
                    className="w-full border border-gray-300 rounded-lg px-4 py-2"
                />

                <div className="flex justify-between gap-4">
                    <button
                        type="submit"
                        disabled={loading}
                        className={`flex-1 py-2 rounded-lg text-white font-semibold ${loading ? 'bg-gray-400' : 'bg-blue-600 hover:bg-blue-700'
                            }`}
                    >
                        {loading ? 'Updating...' : 'Update'}
                    </button>

                    <button
                        type="button"
                        onClick={handleCancel}
                        className="flex-1 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    );
};

export default EditProduct;
