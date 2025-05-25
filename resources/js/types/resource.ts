export interface Data<T> {
    data: T;
}

export interface PaginatedData<T> extends Data<T> {
    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
        path: string;
        per_page: number;
        to: number;
        total: number;
    }
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
