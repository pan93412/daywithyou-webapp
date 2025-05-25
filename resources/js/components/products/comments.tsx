import useSWR from 'swr';
import { commentResourceDataSchema } from '@/types/api-schema';

export interface ProductCommentsProps {
    productId: number;
}

export default function ProductComments({ productId }: ProductCommentsProps) {
    const { data, error } = useSWR(
        `/api/v1/products/${productId}/comments`,
        fetcher,
        {
            suspense: true
        }
    );

    if (error) {
        return (
            <>
                <span className="text-red-800">無法載入評論</span>
                <span className="text-sm text-zinc-600">{`${error}`}</span>
            </>
        );
    }

    const comments = data?.data ?? [];
    const totalComments = comments.length;
    const averageRating = comments.reduce((total, comment) => total + comment.star, 0) / totalComments;

    if (totalComments === 0) {
        return (
            <>
                <span className="text-zinc-600">目前還沒有評論</span>
            </>
        );
    }

    return (
        <>
            <div className="flex items-center gap-2 mb-2">
                <span className="text-yellow-500 text-xl">{renderStar(averageRating)}</span>
                <span className="text-zinc-600 text-sm">{averageRating} / 5.0 ({totalComments} 則評價)</span>
            </div>
            {comments.map((comment) => (
                <div key={comment.id} className="mb-6">
                    <p className="text-zinc-800 font-medium">{comment.user.name}</p>
                    <div className="text-yellow-500 text-base">{renderStar(comment.star)}</div>
                    <p className="text-zinc-600 text-sm">{comment.content}</p>
                </div>
            ))}
        </>
    )
}

async function fetcher(key: string) {
    const res = await fetch(key);
    if (!res.ok) {
        throw new Error(res.statusText);
    }

    const data = await res.json();
    return commentResourceDataSchema.parse(data);
}

function renderStar(rating: number): string {
    return '★'.repeat(Math.round(rating)) + '☆'.repeat(5 - Math.round(rating));
}
