import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function formatDate(date: Date): string {
    return new Intl.DateTimeFormat('zh-TW', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(date);
}

export function formatRating(rating: number): string {
    if (rating < 0 || rating > 5) {
        throw new Error('Rating must be between 0 and 5');
    }

    return '★'.repeat(Math.round(rating)) + '☆'.repeat(5 - Math.round(rating));
}
