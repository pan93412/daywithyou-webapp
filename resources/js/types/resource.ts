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
    id: number; // only for API and should not be used
    slug: string;
    name: string;
    summary: string;
    price: string;
    figure: string;
}

export interface Product {
    id: number; // only for API and should not be used
    slug: string;
    name: string;
    description: string;
    price: string;
    figure: string;
    created_at: string;
    updated_at: string;
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
    created_at: string;
}

export interface News {
    title: string;
    slug: string;
    content: string;
    created_at: string;
    updated_at: string;
}

export interface OrderConfirmation {
    id: number;
    recipient_name: string;
    note: string | null;
    payment_method: 'cash' | 'line_pay' | 'bank_transfer' | string;
    created_at: string;
}

export interface Order {
    id: number;
    recipient_name: string;
    recipient_email: string;
    recipient_phone: string;
    recipient_address: string;
    recipient_city: string;
    recipient_zip_code: string;
    note: string | null;
    payment_method: 'cash' | 'line_pay' | 'bank_transfer' | string;
    created_at: string;
    updated_at: string;
    user_id: number;
    user?: User;
    order_items: OrderItem[];
}

export interface OrderIndex {
    id: number;
    recipient_name: string;
    payment_method: 'cash' | 'line_pay' | 'bank_transfer' | string;
    created_at: string;
}

export interface OrderItem {
    id: number;
    quantity: number;
    created_at: string;
    updated_at: string;
    order_id: number;
    product_id: number;
    product?: ProductIndex;
}
