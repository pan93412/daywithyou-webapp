export interface Data<T> {
    data: T;
}

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

export interface User {
    id: number;
    name: string;
    email: string;
}

export interface Comment {
    id: number;
    content: string;
    star: number;
    user: User;
}
