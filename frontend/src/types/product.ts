export interface Product {
    _id: string;
    name: string;
    category_url: string;
    img_url: string;
    price: number;
    info: string;
};

export interface ProductsResponse {
    products: Product[];
    current_page: number;
    total_pages: number;
};