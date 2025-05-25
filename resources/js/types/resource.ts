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
    };
}

export interface ProductIndex {
    id: number;
    name: string;
    summary: string;
    price: string;
    figure: string;
}

export interface Product {
    id: number;
    name: string;
    description: string;
    price: string;
    figure: string;
    created_at: Date;
    updated_at: Date;
}

export interface User {
    id: number;
    name: string;
    email: string;
}

export interface Comment {
    id: number;
    content: string;
    rating: number;
    user: User;
}

export interface NewsIndex {
    title: string;
    summary: string;
    slug: string;
    created_at: Date;
}

export interface News {
    title: string;
    slug: string;
    content: string;
    created_at: Date;
    updated_at: Date;
}
