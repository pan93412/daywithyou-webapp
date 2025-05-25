export interface ProductIndex {
    id: number;
    name: string;
    description: string;
    price: string;
    image: string;
}

export interface Product {
    id: number;
    name: string;
    description: string;
    price: string;
    image: string;
    createdAt: Date;
    updatedAt: Date;
}
