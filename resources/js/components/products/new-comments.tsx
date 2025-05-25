import { useForm } from '@inertiajs/react';
import { FormEvent, useCallback } from 'react';
import { route } from 'ziggy-js';

export interface NewCommentsProps {
    productId: number;
}

export default function NewComments({ productId }: NewCommentsProps) {
    const { data, setData, post, processing, errors, reset } = useForm({
        star: 5,
        content: '',
    });

    const handleSubmit = useCallback(
        async (event: FormEvent<HTMLFormElement>) => {
            event.preventDefault();
            post(route('products.comment.store', { product: productId }), {
                preserveScroll: true,
            });
            reset();
        },
        [post, reset, productId],
    );

    return (
        <form onSubmit={handleSubmit} className="flex w-full flex-row items-end gap-x-4 rounded-xl bg-white p-6 shadow-lg">
            {/* Star Rating Field */}
            <div className="flex min-w-[90px] flex-shrink-0 flex-col">
                <div className="mb-1.5 flex items-center gap-2">
                    <label htmlFor="star" className="text-sm font-medium text-zinc-700">
                        評分
                    </label>
                    {errors.star && <div className="text-xs whitespace-nowrap text-red-600">{errors.star}</div>}
                </div>
                <input
                    id="star"
                    value={data.star}
                    onChange={(e) => setData('star', Number(e.target.value))}
                    type="number"
                    min="1"
                    max="5"
                    className="h-[40px] rounded-md border border-zinc-300 px-3 py-2 text-base text-zinc-800 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                />
            </div>

            {/* Comment Content Field (single-line input) */}
            <div className="flex flex-1 flex-col">
                <div className="mb-1.5 flex items-center gap-2">
                    <label htmlFor="content" className="text-sm font-medium text-zinc-700">
                        評論內容
                    </label>
                    {errors.content && <div className="text-xs whitespace-nowrap text-red-600">{errors.content}</div>}
                </div>

                <input
                    type="text"
                    id="content"
                    value={data.content}
                    onChange={(e) => setData('content', e.target.value)}
                    className="h-[40px] w-full rounded-md border border-zinc-300 px-3 py-2 text-base text-zinc-800 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                />
            </div>

            {/* Submit Button */}
            <button
                type="submit"
                className="bg-primary hover:bg-primary/90 flex h-[40px] flex-shrink-0 items-center justify-center rounded-md px-5 py-2 font-medium text-white shadow-sm transition-colors"
                style={{ minWidth: '70px' }}
                disabled={processing}
            >
                送出
            </button>
        </form>
    );
}
