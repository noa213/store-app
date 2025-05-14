'use client';

import { useEffect, useState } from 'react';
import { getDrinks, deleteDrink } from '@/api/drinkApi';
import { Drink } from '@/types/drink';
import DrinkForm from './DrinkForm';
import { useRouter } from 'next/navigation';
import { toast } from 'react-toastify';

const DrinkTable = () => {
  const [drinks, setDrinks] = useState<Drink[]>([]);
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [selectedDrinkId, setSelectedDrinkId] = useState<string | undefined>(undefined);

  const router = useRouter();

  useEffect(() => {
    fetchDrinks();
  }, []);

  const fetchDrinks = async () => {
    try {
      const data = await getDrinks();
      setDrinks(data);
    } catch (error) {
      console.error('Error fetching drinks', error);
    }
  };

  const handleEdit = (id: string) => {
    setSelectedDrinkId(id);
    setIsFormOpen(true);
  };

  const handleCreate = () => {
    setSelectedDrinkId(undefined);
    setIsFormOpen(true);
  };

  const handleDelete = async (id: string) => {
    if (confirm('Are you sure you want to delete this drink?')) {
      try {
        await deleteDrink(id);
        toast.success('Drink deleted successfully!');
        fetchDrinks();
      } catch (error) {
        toast.error('Failed to delete drink.');
        console.error('Delete error:', error);
      }
    }
  };

  const handleShow = (id: string) => {
    router.push(`/drinks/${id}`);
  };

  const handleFormClose = () => {
    setIsFormOpen(false);
    setSelectedDrinkId(undefined);
  };

  const handleFormSuccess = () => {
    fetchDrinks();
    handleFormClose();
  };

  return (
    <div className="p-8">
      <h1 className="text-3xl font-bold mb-4">Drink List</h1>

      <button
        onClick={handleCreate}
        className="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
      >
        + Add Drink
      </button>

      <table className="w-full table-auto border-collapse border border-gray-300">
        <thead>
          <tr className="bg-gray-100">
            <th className="border px-4 py-2">Name</th>
            <th className="border px-4 py-2">Volume (ml)</th>
            <th className="border px-4 py-2">Price</th>
            <th className="border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {drinks.map((drink) => (
            <tr key={drink.id}>
              <td className="border px-4 py-2">{drink.name}</td>
              <td className="border px-4 py-2">{drink.ml}</td>
              <td className="border px-4 py-2">${Number(drink.price).toFixed(2)}</td>
              <td className="border px-4 py-2 space-x-2">
                <button
                  onClick={() => handleShow(drink.id)}
                  className="px-3 py-1 text-sm bg-gray-600 text-white rounded hover:bg-gray-700"
                >
                  Show
                </button>
                <button
                  onClick={() => handleEdit(drink.id)}
                  className="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                  Edit
                </button>
                <button
                  onClick={() => handleDelete(drink.id)}
                  className="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600"
                >
                  Delete
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {isFormOpen && (
        <DrinkForm
          drinkId={selectedDrinkId}
          onSuccess={handleFormSuccess}
          onCancel={handleFormClose}
        />
      )}
    </div>
  );
};

export default DrinkTable;
