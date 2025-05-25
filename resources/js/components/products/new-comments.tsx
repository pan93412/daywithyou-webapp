import { useForm } from '@inertiajs/react';
import { FormEvent, useCallback } from 'react';

export interface NewCommentsProps {
    productId: number
}

export default function NewComments({ productId }: NewCommentsProps) {
    const {data, setData, post, processing, errors, reset} = useForm({
        star: 5,
        content: '',
    });

    const handleSubmit = useCallback(async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        post(`/products/${productId}/new-comment`);
        reset();
    }, [post, reset, productId]);

    return (
        <form
            onSubmit={handleSubmit}
            className="flex flex-row items-end gap-x-4 w-full bg-white rounded-xl shadow-lg p-6"
        >
            {/* Star Rating Field */}
            <div className="flex flex-col min-w-[90px] flex-shrink-0">
                <div className="flex gap-2 items-center mb-1.5">
                    <label htmlFor="star" className="text-zinc-700 font-medium text-sm">評分</label>
                    {errors.star && (
                        <div className="text-red-600 text-xs whitespace-nowrap">{errors.star}</div>
                    )}
                </div>
                <input
                    id="star"
                    value={data.star}
                    onChange={e => setData('star', Number(e.target.value))}
                    type="number"
                    min="1"
                    max="5"
                    className="border border-zinc-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 text-zinc-800 text-base shadow-sm h-[40px]"
                />
            </div>

            {/* Comment Content Field (single-line input) */}
            <div className="flex flex-col flex-1">
                <div className="flex gap-2 items-center mb-1.5">
                    <label htmlFor="content" className="text-zinc-700 font-medium text-sm">評論內容</label>
                    {errors.content && (
                        <div className="text-red-600 text-xs whitespace-nowrap">{errors.content}</div>
                    )}
                </div>

                <input
                    type="text"
                    id="content"
                    value={data.content}
                    onChange={e => setData('content', e.target.value)}
                    className="border border-zinc-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500 text-zinc-800 text-base shadow-sm h-[40px] w-full"
                />
            </div>

            {/* Submit Button */}
            <button
                type="submit"
                className="bg-primary hover:bg-primary/90 text-white font-medium py-2 px-5 rounded-md transition-colors shadow-sm h-[40px] flex items-center justify-center flex-shrink-0"
                style={{ minWidth: '70px' }}
                disabled={processing}
            >
                送出
            </button>
        </form>
    )
}
